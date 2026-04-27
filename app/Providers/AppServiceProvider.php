<?php

namespace App\Providers;

use App\Models\Activity;
use App\Models\Content;
use App\Models\ContentCategory;
use App\Models\Division;
use App\Models\Document;
use App\Models\DonationCampaign;
use App\Models\OrganizationProfile;
use App\Models\Page;
use App\Models\Partner;
use App\Models\Program;
use App\Models\ProgramCategory;
use App\Models\TeamMember;
use App\Models\Video;
use App\Support\FrontendCache;
use App\Support\SeoData;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::preventLazyLoading(! app()->isProduction());
        Carbon::setLocale(config('app.locale'));

        RateLimiter::for('public-form-submissions', function (Request $request): Limit {
            return Limit::perMinute(5)->by($request->ip() . '|' . $request->path());
        });

        $this->registerFrontendCacheInvalidators();

        View::composer('layouts.site', function ($view): void {
            $view->with('siteProfile', null);
            $view->with('siteSummary', 'Lembaga independen yang fokus pada pemberdayaan masyarakat.');
            $view->with('donateCtaCampaign', null);
            $view->with('footerPages', collect());

            $view->with(FrontendCache::remember(
                'site-shell:data',
                fn (): array => rescue(
                    function (): array {
                        $siteProfile = OrganizationProfile::query()->first();

                        return [
                            'siteProfile' => $siteProfile,
                            'siteSummary' => $siteProfile?->short_description ?? 'Lembaga independen yang fokus pada pemberdayaan masyarakat.',
                            'donateCtaCampaign' => DonationCampaign::query()->published()->featured()->first(),
                            'footerPages' => Page::query()
                                ->published()
                                ->whereIn('slug', ['about', 'contact', 'media', 'publikasi'])
                                ->get()
                                ->keyBy('slug'),
                        ];
                    },
                    [
                        'siteProfile' => null,
                        'siteSummary' => 'Lembaga independen yang fokus pada pemberdayaan masyarakat.',
                        'donateCtaCampaign' => null,
                        'footerPages' => collect(),
                    ],
                    report: false,
                ),
                [FrontendCache::SiteShell],
            ));

            $view->with('seo', SeoData::fromViewData($view->getData(), request()));
        });
    }

    private function registerFrontendCacheInvalidators(): void
    {
        $this->registerFrontendCacheInvalidator(Activity::class, [
            FrontendCache::HomePage,
            FrontendCache::MediaPage,
        ]);

        $this->registerFrontendCacheInvalidator(Content::class, [
            FrontendCache::HomePage,
            FrontendCache::PublicationsPage,
            FrontendCache::Sitemap,
        ]);

        $this->registerFrontendCacheInvalidator(ContentCategory::class, [
            FrontendCache::HomePage,
            FrontendCache::PublicationsPage,
        ]);

        $this->registerFrontendCacheInvalidator(Division::class, [
            FrontendCache::AboutPage,
        ]);

        $this->registerFrontendCacheInvalidator(Document::class, [
            FrontendCache::HomePage,
            FrontendCache::PublicationsPage,
            FrontendCache::DonatePage,
            FrontendCache::ResourcesPage,
        ]);

        $this->registerFrontendCacheInvalidator(DonationCampaign::class, [
            FrontendCache::SiteShell,
            FrontendCache::DonatePage,
        ]);

        $this->registerFrontendCacheInvalidator(OrganizationProfile::class, [
            FrontendCache::SiteShell,
            FrontendCache::HomePage,
            FrontendCache::AboutPage,
        ]);

        $this->registerFrontendCacheInvalidator(Page::class, [
            FrontendCache::SiteShell,
            FrontendCache::FrontendPages,
            FrontendCache::Sitemap,
        ]);

        $this->registerFrontendCacheInvalidator(Partner::class, [
            FrontendCache::HomePage,
            FrontendCache::ProgramsPage,
            FrontendCache::PartnersPage,
        ]);

        $this->registerFrontendCacheInvalidator(Program::class, [
            FrontendCache::HomePage,
            FrontendCache::ProgramsPage,
            FrontendCache::PartnersPage,
            FrontendCache::Sitemap,
        ]);

        $this->registerFrontendCacheInvalidator(ProgramCategory::class, [
            FrontendCache::HomePage,
            FrontendCache::ProgramsPage,
            FrontendCache::PartnersPage,
        ]);

        $this->registerFrontendCacheInvalidator(TeamMember::class, [
            FrontendCache::AboutPage,
        ]);

        $this->registerFrontendCacheInvalidator(Video::class, [
            FrontendCache::HomePage,
            FrontendCache::MediaPage,
        ]);
    }

    /**
     * @param  class-string<Model>  $modelClass
     * @param  list<string>  $segments
     */
    private function registerFrontendCacheInvalidator(string $modelClass, array $segments): void
    {
        $invalidate = static function () use ($segments): void {
            FrontendCache::bump($segments);
        };

        $modelClass::saved($invalidate);
        $modelClass::deleted($invalidate);

        if (in_array(SoftDeletes::class, class_uses_recursive($modelClass), true)) {
            $modelClass::restored($invalidate);
            $modelClass::forceDeleted($invalidate);
        }
    }
}
