<?php

use App\Filament\Resources\Activities\Pages\CreateActivity;
use App\Filament\Resources\Activities\Schemas\ActivityForm;
use App\Models\Activity;
use App\Models\ActivityGallery;
use App\Models\ActivityVideo;
use App\Models\Program;
use App\Models\User;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

it('allows an activity to exist without a program', function (): void {
    $activity = Activity::factory()->withoutProgram()->create();

    expect($activity->program_id)->toBeNull()
        ->and($activity->program)->toBeNull();
});

it('keeps activities and clears their optional program when a program is deleted', function (): void {
    $program = Program::factory()->create();
    $activity = Activity::factory()->for($program)->create();

    $program->forceDelete();

    expect($activity->fresh())->not->toBeNull()
        ->and($activity->fresh()->program_id)->toBeNull();
});

it('stores ordered photos and videos for an activity', function (): void {
    $activity = Activity::factory()->withoutProgram()->create();

    ActivityGallery::factory()->for($activity)->create(['caption' => 'Foto kedua', 'sort_order' => 2]);
    ActivityGallery::factory()->for($activity)->create(['caption' => 'Foto pertama', 'sort_order' => 1]);
    ActivityVideo::factory()->for($activity)->create(['title' => 'Video kedua', 'sort_order' => 2]);
    ActivityVideo::factory()->for($activity)->create(['title' => 'Video pertama', 'sort_order' => 1]);

    expect($activity->galleries()->pluck('caption')->all())->toBe(['Foto pertama', 'Foto kedua'])
        ->and($activity->videos()->pluck('title')->all())->toBe(['Video pertama', 'Video kedua']);
});

it('removes photo and video records when their activity is deleted', function (): void {
    $activity = Activity::factory()->withoutProgram()->create();
    $gallery = ActivityGallery::factory()->for($activity)->create();
    $video = ActivityVideo::factory()->for($activity)->create();

    $activity->delete();

    $this->assertModelMissing($gallery);
    $this->assertModelMissing($video);
});

it('renders a published activity with multiple photos and videos without a program', function (): void {
    Storage::fake('public');
    Storage::disk('public')->put('activities/gallery/satu.jpg', 'image');
    Storage::disk('public')->put('activities/gallery/dua.jpg', 'image');

    $activity = Activity::factory()->withoutProgram()->published()->create([
        'title' => 'Dokumentasi Kegiatan Mandiri',
        'slug' => 'dokumentasi-kegiatan-mandiri',
        'description' => 'Penjelasan lengkap kegiatan mandiri.',
    ]);

    ActivityGallery::factory()->for($activity)->create([
        'file_url' => 'activities/gallery/satu.jpg',
        'caption' => 'Foto lapangan pertama',
        'sort_order' => 1,
    ]);
    ActivityGallery::factory()->for($activity)->create([
        'file_url' => 'activities/gallery/dua.jpg',
        'caption' => 'Foto lapangan kedua',
        'sort_order' => 2,
    ]);
    ActivityVideo::factory()->for($activity)->create([
        'title' => 'Rekaman kegiatan',
        'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
    ]);

    $this->get(route('activities.show', $activity))
        ->assertSuccessful()
        ->assertSee('Dokumentasi Kegiatan Mandiri')
        ->assertSee('Aktivitas Umum')
        ->assertSee('Foto lapangan pertama')
        ->assertSee('Foto lapangan kedua')
        ->assertSee('https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ', false);

    $this->get(route('media.index'))
        ->assertSuccessful()
        ->assertSee(route('activities.show', $activity), false);
});

it('does not expose draft activity detail pages', function (): void {
    $activity = Activity::factory()->withoutProgram()->create();

    $this->get(route('activities.show', $activity))->assertNotFound();
});

it('configures optional program and relationship repeaters in the admin form', function (): void {
    $schema = ActivityForm::configure(Schema::make());
    $program = $schema->getComponentByStatePath('program_id', withHidden: true);

    expect($program)->toBeInstanceOf(Select::class)
        ->and($program->isRequired())->toBeFalse()
        ->and($schema->getComponentByStatePath('galleries', withHidden: true))->toBeInstanceOf(Repeater::class)
        ->and($schema->getComponentByStatePath('videos', withHidden: true))->toBeInstanceOf(Repeater::class);
});

it('creates an activity with multiple photos and videos from the admin form', function (): void {
    Storage::fake('public');
    $this->actingAs(User::factory()->create());

    Livewire::test(CreateActivity::class)
        ->fillForm([
            'program_id' => null,
            'title' => 'Aktivitas Multimedia',
            'slug' => 'aktivitas-multimedia',
            'description' => 'Aktivitas umum dengan banyak dokumentasi foto dan video.',
            'status' => 'draft',
            'galleries' => [
                [
                    'file_url' => [UploadedFile::fake()->image('foto-satu.jpg')],
                    'caption' => 'Foto satu',
                ],
                [
                    'file_url' => [UploadedFile::fake()->image('foto-dua.jpg')],
                    'caption' => 'Foto dua',
                ],
            ],
            'videos' => [
                [
                    'title' => 'Video satu',
                    'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                ],
                [
                    'title' => 'Video dua',
                    'youtube_url' => 'https://youtu.be/aqz-KE-bpKQ',
                ],
            ],
        ])
        ->call('create')
        ->assertHasNoFormErrors()
        ->assertRedirect();

    $activity = Activity::query()->where('slug', 'aktivitas-multimedia')->firstOrFail();

    expect($activity->program_id)->toBeNull()
        ->and($activity->galleries()->count())->toBe(2)
        ->and($activity->videos()->count())->toBe(2)
        ->and($activity->galleries()->pluck('caption')->all())->toBe(['Foto satu', 'Foto dua'])
        ->and($activity->videos()->pluck('title')->all())->toBe(['Video satu', 'Video dua']);
});
