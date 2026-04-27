<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Content extends Model
{
    /** @use HasFactory<\Database\Factories\ContentFactory> */
    use HasFactory;
    use SoftDeletes;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'slug',
        'type',
        'category_id',
        'excerpt',
        'body',
        'featured_image_url',
        'author_id',
        'status',
        'is_featured',
        'published_at',
        'seo_title',
        'seo_description',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function scopePublished(Builder $query): void
    {
        $query->where('status', 'published')->whereNotNull('published_at');
    }

    public function scopeStories(Builder $query): void
    {
        $query->where('type', 'story');
    }

    public function scopeFeatured(Builder $query): void
    {
        $query->where('is_featured', true);
    }

    public function scopeEditorialPublications(Builder $query): void
    {
        $query->whereIn('type', ['journal', 'news', 'article', 'opinion']);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ContentCategory::class, 'category_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'content_tags');
    }

    public function files(): HasMany
    {
        return $this->hasMany(ContentFile::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function displayTitle(): string
    {
        return $this->translatedStoryValue('title');
    }

    public function displayExcerpt(): string
    {
        return $this->translatedStoryValue('excerpt');
    }

    public function displayBody(): string
    {
        return $this->translatedStoryValue('body');
    }

    public function displaySeoTitle(): string
    {
        return $this->translatedStoryValue('seo_title');
    }

    public function displaySeoDescription(): string
    {
        return $this->translatedStoryValue('seo_description');
    }

    public function displayAuthorName(): string
    {
        $authorName = $this->author?->name;

        if ($authorName === null) {
            return app()->isLocale('id') ? 'Meja Editorial' : 'Editorial Desk';
        }

        if (app()->isLocale('id') && $authorName === 'GIRI Editorial Desk') {
            return 'Meja Editorial GIRI';
        }

        return $authorName;
    }

    private function translatedStoryValue(string $attribute): string
    {
        $value = (string) ($this->{$attribute} ?? '');

        if (! app()->isLocale('id')) {
            return $value;
        }

        $translation = $this->storyTranslationMap()[$this->slug][$attribute] ?? null;

        if (! is_array($translation)) {
            return $value;
        }

        return $value === $translation['english'] ? $translation['indonesian'] : $value;
    }

    /**
     * @return array<string, array<string, array{english: string, indonesian: string}>>
     */
    private function storyTranslationMap(): array
    {
        return [
            'silent-resonance-of-tradition' => [
                'title' => [
                    'english' => 'The Silent Resonance of Tradition.',
                    'indonesian' => 'Resonansi Sunyi Tradisi.',
                ],
                'excerpt' => [
                    'english' => 'Ancient weaving practices in the Himalayan foothills are creating sustainable futures for the next generation of artisans.',
                    'indonesian' => 'Praktik menenun kuno di kaki Himalaya sedang membangun masa depan berkelanjutan bagi generasi perajin berikutnya.',
                ],
                'body' => [
                    'english' => 'Ancient weaving in the Himalayan foothills is not a static artifact. It is a living economy, a social architecture, and a language of care.' . "\n\n" . 'This story traces how a younger generation of Giri artisans is rebuilding confidence, market access, and local pride through material knowledge passed down across families.',
                    'indonesian' => 'Tenun kuno di kaki Himalaya bukanlah artefak yang diam. Ia adalah ekonomi hidup, arsitektur sosial, dan bahasa kepedulian.' . "\n\n" . 'Cerita ini menelusuri bagaimana generasi muda perajin Giri membangun kembali kepercayaan diri, akses pasar, dan kebanggaan lokal melalui pengetahuan material yang diwariskan antarkeluarga.',
                ],
                'seo_title' => [
                    'english' => 'The Silent Resonance of Tradition',
                    'indonesian' => 'Resonansi Sunyi Tradisi',
                ],
                'seo_description' => [
                    'english' => 'A story from the field on weaving, heritage, and resilient livelihoods.',
                    'indonesian' => 'Cerita dari lapangan tentang tenun, warisan budaya, dan penghidupan yang tangguh.',
                ],
            ],
            'silent-architect-high-valleys' => [
                'title' => [
                    'english' => 'The Silent Architect: Reforestation in the High Valleys',
                    'indonesian' => 'Arsitek Sunyi: Reforestasi di Lembah Tinggi',
                ],
                'excerpt' => [
                    'english' => 'Why biodiversity-first reforestation outperforms monoculture planting in fragile upland ecosystems.',
                    'indonesian' => 'Mengapa reforestasi yang mendahulukan biodiversitas lebih efektif daripada monokultur di ekosistem dataran tinggi yang rapuh.',
                ],
                'body' => [
                    'english' => 'Reforestation becomes architecture when it is designed to outlast the people who initiate it. In the high valleys, species diversity and community governance matter more than planting density alone.' . "\n\n" . 'This field report explains why restoring complexity, not just tree count, is central to long-term ecological health.',
                    'indonesian' => 'Reforestasi menjadi arsitektur ketika dirancang untuk bertahan lebih lama daripada para penggagasnya. Di lembah tinggi, keragaman spesies dan tata kelola komunitas lebih penting daripada sekadar kepadatan tanam.' . "\n\n" . 'Laporan lapangan ini menjelaskan mengapa memulihkan kompleksitas, bukan hanya jumlah pohon, adalah inti dari kesehatan ekologis jangka panjang.',
                ],
                'seo_title' => [
                    'english' => 'The Silent Architect',
                    'indonesian' => 'Arsitek Sunyi',
                ],
                'seo_description' => [
                    'english' => 'Reforestation strategy and community stewardship in upland valleys.',
                    'indonesian' => 'Strategi reforestasi dan pendampingan komunitas di lembah dataran tinggi.',
                ],
            ],
            'beyond-the-well' => [
                'title' => [
                    'english' => 'Beyond the Well: Water as a Catalyst for Education.',
                    'indonesian' => 'Melampaui Sumur: Air sebagai Pendorong Pendidikan.',
                ],
                'excerpt' => [
                    'english' => 'Clean water infrastructure is reclaiming hours of learning time for girls in rural communities.',
                    'indonesian' => 'Infrastruktur air bersih mengembalikan jam belajar bagi anak perempuan di komunitas pedesaan.',
                ],
                'body' => [
                    'english' => 'When water access improves, classrooms change. Attendance stabilizes, domestic burdens shift, and students return to routines that make aspiration practical.' . "\n\n" . 'This report connects basic infrastructure to long-term educational continuity.',
                    'indonesian' => 'Ketika akses air membaik, ruang kelas ikut berubah. Kehadiran menjadi lebih stabil, beban domestik berkurang, dan siswa kembali pada rutinitas yang membuat cita-cita terasa nyata.' . "\n\n" . 'Laporan ini menghubungkan infrastruktur dasar dengan kesinambungan pendidikan jangka panjang.',
                ],
                'seo_title' => [
                    'english' => 'Beyond the Well',
                    'indonesian' => 'Melampaui Sumur',
                ],
                'seo_description' => [
                    'english' => 'Water access and educational equity in rural settings.',
                    'indonesian' => 'Akses air dan kesetaraan pendidikan di wilayah pedesaan.',
                ],
            ],
            'roots-of-resilience' => [
                'title' => [
                    'english' => 'Roots of Resilience: The Urban Farm Project.',
                    'indonesian' => 'Akar Ketangguhan: Proyek Kebun Kota.',
                ],
                'excerpt' => [
                    'english' => 'How reclaiming vacant lots transformed a food desert into a local ecosystem of health and livelihood.',
                    'indonesian' => 'Bagaimana pemanfaatan lahan kosong mengubah kawasan minim pangan menjadi ekosistem lokal yang sehat dan produktif.',
                ],
                'body' => [
                    'english' => 'Urban farming becomes resilient when communities own the rhythm of planting, harvesting, and distribution. In this project, food access and local dignity advance together.',
                    'indonesian' => 'Pertanian kota menjadi tangguh ketika komunitas memiliki ritme tanam, panen, dan distribusinya sendiri. Dalam proyek ini, akses pangan dan martabat lokal bertumbuh bersama.',
                ],
                'seo_title' => [
                    'english' => 'Roots of Resilience',
                    'indonesian' => 'Akar Ketangguhan',
                ],
                'seo_description' => [
                    'english' => 'Community-led farming and local food systems.',
                    'indonesian' => 'Pertanian yang dipimpin komunitas dan sistem pangan lokal.',
                ],
            ],
            'designing-for-dignity' => [
                'title' => [
                    'english' => 'Designing for Dignity: Our New Housing Initiative.',
                    'indonesian' => 'Merancang untuk Martabat: Inisiatif Hunian Baru Kami.',
                ],
                'excerpt' => [
                    'english' => 'Architecture as a tool for empathy and social restoration in urban centers.',
                    'indonesian' => 'Arsitektur sebagai alat empati dan pemulihan sosial di kawasan perkotaan.',
                ],
                'body' => [
                    'english' => 'Built form communicates value. This archive entry explores how housing design can restore participation, agency, and psychological safety.',
                    'indonesian' => 'Ruang terbangun menyampaikan nilai. Entri arsip ini mengeksplorasi bagaimana desain hunian dapat memulihkan partisipasi, daya kendali, dan rasa aman secara psikologis.',
                ],
                'seo_title' => [
                    'english' => 'Designing for Dignity',
                    'indonesian' => 'Merancang untuk Martabat',
                ],
                'seo_description' => [
                    'english' => 'Architecture as social restoration.',
                    'indonesian' => 'Arsitektur sebagai sarana pemulihan sosial.',
                ],
            ],
        ];
    }
}
