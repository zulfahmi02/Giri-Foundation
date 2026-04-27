<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Content;
use App\Models\ContentCategory;
use App\Models\Division;
use App\Models\Document;
use App\Models\DonationCampaign;
use App\Models\DonationUpdate;
use App\Models\OrganizationProfile;
use App\Models\OrganizationStat;
use App\Models\Page;
use App\Models\Partner;
use App\Models\Program;
use App\Models\ProgramCategory;
use App\Models\Role;
use App\Models\Tag;
use App\Models\TeamMember;
use App\Models\User;
use App\Models\Video;
use App\Support\TeamMemberStructureSlots;
use App\Support\PageContentDefaults;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GiriFoundationSeeder extends Seeder
{
    public function run(): void
    {
        $this->retireLegacyDefaultAdmin();

        $adminRole = Role::query()->updateOrCreate(
            ['name' => 'Admin'],
            ['description' => 'Mengelola akses panel, konfigurasi sistem, audit, dan data donasi.'],
        );

        Role::query()->updateOrCreate(
            ['name' => 'Editor'],
            ['description' => 'Mengelola konten, program, dokumen, dan informasi publik yayasan.'],
        );

        $contentOwner = User::query()->updateOrCreate(
            ['email' => 'editorial@giri.foundation'],
            [
                'name' => 'GIRI Editorial Desk',
                'password' => Str::random(40),
                'phone' => null,
                'status' => 'inactive',
                'avatar_url' => null,
                'email_verified_at' => null,
            ],
        );

        $this->seedInitialAdmin($adminRole);

        $admin = $contentOwner;

        $this->removeLegacyDummyContent();

        OrganizationProfile::query()->updateOrCreate(
            ['slug' => 'giri-foundation'],
            [
                'name' => 'GIRI FOUNDATION',
                'short_description' => 'Yayasan Giri Nusantara Sejahtera adalah lembaga independen yang fokus pada pemberdayaan masyarakat.',
                'full_description' => 'Yayasan Giri Nusantara Sejahtera, disingkat GNS, adalah lembaga independen yang berfokus pada pemberdayaan masyarakat. Yayasan ini didirikan di Bojonegoro pada 10 November 2024 dan berkedudukan di Bojonegoro, Provinsi Jawa Timur.' . "\n\n" . 'GNS berasaskan Pancasila, berlandaskan Undang-Undang Dasar 1945, Anggaran Dasar, Anggaran Rumah Tangga, serta ketetapan dan keputusan organ yayasan. Dalam menjalankan kegiatan, GNS bersifat independen dan tidak berafiliasi dengan organisasi politik, organisasi keagamaan, maupun organisasi lain yang tidak memiliki hubungan langsung dengan yayasan.' . "\n\n" . 'Ruang lingkup kerja yayasan mencakup pendidikan, ekonomi, lingkungan, gender, kesehatan, kebudayaan, riset, dan digitalisasi melalui kajian, pengabdian, advokasi, peningkatan kapasitas, kerja sama, publikasi, dan pelayanan keahlian bagi masyarakat.',
                'vision' => 'Terwujudnya kesejahteraan masyarakat Indonesia.',
                'mission' => 'Meningkatkan sumber daya manusia; mendorong kemandirian ekonomi masyarakat; melestarikan lingkungan; menguatkan kesetaraan gender; mendorong peningkatan kualitas kesehatan yang merata; melestarikan kebudayaan; serta mengembangkan kerja berbasis riset dan digitalisasi.',
                'values' => 'Pancasila' . "\n" . 'Independensi' . "\n" . 'Asah Asih Asuh' . "\n" . 'Musyawarah Mufakat' . "\n" . 'Transparansi dan Akuntabilitas',
                'founded_date' => '2024-11-10',
                'email' => 'info@giri.foundation',
                'phone' => null,
                'whatsapp_number' => null,
                'address' => 'Bojonegoro, Provinsi Jawa Timur, Indonesia',
                'google_maps_embed' => 'https://maps.google.com/?q=Bojonegoro+Jawa+Timur',
                'logo_url' => null,
                'favicon_url' => null,
            ],
        );

        foreach ([
            ['title' => 'Tahun Berdiri', 'value' => 2024, 'suffix' => null, 'icon' => 'event', 'sort_order' => 1],
            ['title' => 'Bidang Kegiatan', 'value' => 7, 'suffix' => null, 'icon' => 'category', 'sort_order' => 2],
            ['title' => 'Kekayaan Awal', 'value' => 200, 'suffix' => ' juta rupiah', 'icon' => 'account_balance', 'sort_order' => 3],
        ] as $stat) {
            OrganizationStat::query()->updateOrCreate(['title' => $stat['title']], $stat + ['is_active' => true]);
        }

        $divisions = collect([
            [
                'name' => 'Dewan Penasihat',
                'slug' => 'dewan-penasihat',
                'description' => 'Organ tertinggi yang melakukan pengawasan dan memberi nasihat kepada Pengurus dan Pembina.',
                'sort_order' => 1,
            ],
            [
                'name' => 'Dewan Pembina',
                'slug' => 'dewan-pembina',
                'description' => 'Organ yang memegang kewenangan legislatif dan yudikatif, termasuk penetapan kebijakan umum, pengesahan program kerja, serta perubahan AD/ART.',
                'sort_order' => 2,
            ],
            [
                'name' => 'Dewan Pengurus',
                'slug' => 'dewan-pengurus',
                'description' => 'Organ eksekutif yang menjalankan kegiatan yayasan melalui Ketua Yayasan, Sekretaris, Bendahara, Direktur, dan bidang-bidang.',
                'sort_order' => 3,
            ],
            [
                'name' => 'Bidang Kajian',
                'slug' => 'bidang-kajian',
                'description' => 'Kelengkapan organisasi untuk melaksanakan program utama di bidang pendidikan, ekonomi, lingkungan, gender, kesehatan, kebudayaan, riset, dan digitalisasi.',
                'sort_order' => 4,
            ],
        ])->mapWithKeys(fn (array $division) => [
            $division['slug'] => Division::query()->updateOrCreate(
                ['slug' => $division['slug']],
                $division + ['is_active' => true],
            ),
        ]);

        $upsertStructuralMember = function (string $slot, array $attributes): TeamMember {
            return TeamMember::query()->updateOrCreate(
                ['structure_slot' => $slot],
                $attributes + [
                    'structure_slot' => $slot,
                    'is_structural' => true,
                    'is_active' => true,
                ],
            );
        };

        $penasihat = $upsertStructuralMember(
            TeamMemberStructureSlots::Advisor,
            [
                'slug' => 'penasihat-yayasan',
                'name' => 'Belum ditentukan',
                'position' => 'Penasihat',
                'division' => 'Dewan Penasihat',
                'division_id' => $divisions['dewan-penasihat']->id,
                'parent_id' => null,
                'bio' => 'Dewan Penasihat melakukan pengawasan dan memberi nasihat kepada Pengurus dan Pembina dalam menjalankan kegiatan yayasan.',
                'photo_url' => null,
                'email' => null,
                'linkedin_url' => null,
                'sort_order' => 1,
            ],
        );

        $upsertStructuralMember(
            TeamMemberStructureSlots::TrusteeLeft,
            [
                'slug' => 'anggota-dewan-pembina-1',
                'name' => 'Belum ditentukan',
                'position' => 'Anggota Dewan Pembina',
                'division' => 'Dewan Pembina',
                'division_id' => $divisions['dewan-pembina']->id,
                'parent_id' => $penasihat->id,
                'bio' => 'Anggota Dewan Pembina membantu penetapan kebijakan umum, pengesahan program kerja, dan pengawasan arah yayasan.',
                'photo_url' => null,
                'email' => null,
                'linkedin_url' => null,
                'sort_order' => 10,
            ],
        );

        $pembina = $upsertStructuralMember(
            TeamMemberStructureSlots::TrusteePrimary,
            [
                'slug' => 'm-suaeb-abdullah',
                'name' => 'M. Suaeb Abdullah',
                'position' => 'Ketua Dewan Pembina',
                'division' => 'Dewan Pembina',
                'division_id' => $divisions['dewan-pembina']->id,
                'parent_id' => $penasihat->id,
                'bio' => 'Menjalankan kewenangan Dewan Pembina sesuai Anggaran Dasar, termasuk pengesahan program kerja, rancangan anggaran tahunan, laporan tahunan, dan kebijakan umum yayasan.',
                'photo_url' => null,
                'email' => null,
                'linkedin_url' => null,
                'sort_order' => 20,
            ],
        );

        $upsertStructuralMember(
            TeamMemberStructureSlots::TrusteeRight,
            [
                'slug' => 'anggota-dewan-pembina-2',
                'name' => 'Belum ditentukan',
                'position' => 'Anggota Dewan Pembina',
                'division' => 'Dewan Pembina',
                'division_id' => $divisions['dewan-pembina']->id,
                'parent_id' => $penasihat->id,
                'bio' => 'Anggota Dewan Pembina membantu penetapan kebijakan umum, pengesahan program kerja, dan pengawasan arah yayasan.',
                'photo_url' => null,
                'email' => null,
                'linkedin_url' => null,
                'sort_order' => 30,
            ],
        );

        $ketuaYayasan = TeamMember::query()->updateOrCreate(
            ['slug' => 'rian-adi-kurniawan'],
            [
                'name' => 'Rian Adi Kurniawan',
                'position' => 'Ketua Yayasan',
                'structure_slot' => null,
                'division' => 'Dewan Pengurus',
                'division_id' => $divisions['dewan-pengurus']->id,
                'parent_id' => $pembina->id,
                'bio' => 'Memimpin Dewan Pengurus sebagai organ eksekutif yang menjalankan kegiatan Yayasan Giri Nusantara Sejahtera.',
                'photo_url' => null,
                'email' => null,
                'linkedin_url' => null,
                'sort_order' => 40,
                'is_structural' => false,
                'is_active' => true,
            ],
        );

        $direktur = $upsertStructuralMember(
            TeamMemberStructureSlots::Director,
            [
                'slug' => 'direktur-yayasan',
                'name' => 'Belum ditentukan',
                'position' => 'Direktur',
                'division' => 'Dewan Pengurus',
                'division_id' => $divisions['dewan-pengurus']->id,
                'parent_id' => $pembina->id,
                'bio' => 'Direktur menjalankan koordinasi program, administrasi, dan bidang kerja yayasan di bawah arahan struktur pembina.',
                'photo_url' => null,
                'email' => null,
                'linkedin_url' => null,
                'sort_order' => 50,
            ],
        );

        foreach ([
            [
                'slug' => 'sekretaris-yayasan',
                'position' => 'Sekretaris Yayasan',
                'bio' => 'Sekretaris Yayasan mendukung administrasi, dokumentasi, dan tata kelola surat-menyurat organisasi.',
                'sort_order' => 60,
            ],
            [
                'slug' => 'bendahara-yayasan',
                'position' => 'Bendahara Yayasan',
                'bio' => 'Bendahara Yayasan mendukung pengelolaan keuangan dan laporan keuangan sesuai standar akuntansi yang berlaku.',
                'sort_order' => 70,
            ],
        ] as $pengurus) {
            $slot = $pengurus['slug'] === 'sekretaris-yayasan'
                ? TeamMemberStructureSlots::Secretary
                : TeamMemberStructureSlots::Treasurer;

            $upsertStructuralMember(
                $slot,
                [
                    'slug' => $pengurus['slug'],
                    'name' => 'Belum ditentukan',
                    'position' => $pengurus['position'],
                    'division' => 'Dewan Pengurus',
                    'division_id' => $divisions['dewan-pengurus']->id,
                    'parent_id' => $direktur->id,
                    'bio' => $pengurus['bio'],
                    'photo_url' => null,
                    'email' => null,
                    'linkedin_url' => null,
                    'sort_order' => $pengurus['sort_order'],
                ],
            );
        }

        foreach ([
            ['slug' => 'bidang-pendidikan', 'position' => 'Bidang Pendidikan', 'sort_order' => 80],
            ['slug' => 'bidang-ekonomi', 'position' => 'Bidang Ekonomi', 'sort_order' => 81],
            ['slug' => 'bidang-lingkungan', 'position' => 'Bidang Lingkungan', 'sort_order' => 82],
            ['slug' => 'bidang-gender', 'position' => 'Bidang Gender', 'sort_order' => 83],
            ['slug' => 'bidang-kesehatan', 'position' => 'Bidang Kesehatan', 'sort_order' => 84],
            ['slug' => 'bidang-kebudayaan', 'position' => 'Bidang Kebudayaan', 'sort_order' => 85],
            ['slug' => 'bidang-riset-digitalisasi', 'position' => 'Bidang Riset dan Digitalisasi', 'sort_order' => 86],
        ] as $bidang) {
            $slot = match ($bidang['slug']) {
                'bidang-pendidikan' => TeamMemberStructureSlots::FieldEducation,
                'bidang-ekonomi' => TeamMemberStructureSlots::FieldEconomy,
                'bidang-lingkungan' => TeamMemberStructureSlots::FieldEnvironment,
                'bidang-gender' => TeamMemberStructureSlots::FieldGender,
                'bidang-kesehatan' => TeamMemberStructureSlots::FieldHealth,
                'bidang-kebudayaan' => TeamMemberStructureSlots::FieldCulture,
                default => TeamMemberStructureSlots::FieldResearchDigital,
            };

            $upsertStructuralMember(
                $slot,
                [
                    'slug' => $bidang['slug'],
                    'name' => 'Belum ditentukan',
                    'position' => $bidang['position'],
                    'division' => 'Bidang Kajian',
                    'division_id' => $divisions['bidang-kajian']->id,
                    'parent_id' => $direktur->id,
                    'bio' => $bidang['position'].' melaksanakan program kerja sesuai ruang lingkup AD/ART Yayasan Giri Nusantara Sejahtera.',
                    'photo_url' => null,
                    'email' => null,
                    'linkedin_url' => null,
                    'sort_order' => $bidang['sort_order'],
                ],
            );
        }

        $programCategories = collect([
            ['name' => 'Pendidikan', 'slug' => 'pendidikan', 'description' => 'Peningkatan sumber daya manusia, literasi, dan kompetensi masyarakat.'],
            ['name' => 'Ekonomi', 'slug' => 'ekonomi', 'description' => 'Kemandirian ekonomi masyarakat dan penguatan kapasitas usaha.'],
            ['name' => 'Lingkungan', 'slug' => 'lingkungan', 'description' => 'Pelestarian sumber daya alam dan pengelolaan lingkungan hidup berkelanjutan.'],
            ['name' => 'Gender', 'slug' => 'gender', 'description' => 'Penguatan kesetaraan gender dalam pemberdayaan masyarakat.'],
            ['name' => 'Kesehatan', 'slug' => 'kesehatan', 'description' => 'Peningkatan kualitas kesehatan yang merata.'],
            ['name' => 'Kebudayaan', 'slug' => 'kebudayaan', 'description' => 'Pelestarian kebudayaan dan nilai sosial masyarakat.'],
            ['name' => 'Riset dan Digitalisasi', 'slug' => 'riset-digitalisasi', 'description' => 'Kajian ilmiah, data, publikasi, dan pengembangan berbasis digital.'],
        ])->mapWithKeys(fn (array $category) => [
            $category['slug'] => ProgramCategory::query()->updateOrCreate(['slug' => $category['slug']], $category),
        ]);

        $partners = collect([
            [
                'name' => 'Masyarakat dan Komunitas Lokal',
                'slug' => 'masyarakat-komunitas-lokal',
                'logo_url' => null,
                'website_url' => null,
                'type' => 'community',
                'description' => 'Pemangku kepentingan utama dalam kegiatan pengabdian, pemberdayaan, advokasi, dan pembinaan.',
            ],
            [
                'name' => 'Pemangku Kebijakan',
                'slug' => 'pemangku-kebijakan',
                'logo_url' => null,
                'website_url' => null,
                'type' => 'government',
                'description' => 'Penerima masukan, kritik konstruktif, dan rekomendasi berbasis kajian ilmiah serta data faktual.',
            ],
            [
                'name' => 'Mitra Nasional dan Internasional',
                'slug' => 'mitra-nasional-internasional',
                'logo_url' => null,
                'website_url' => null,
                'type' => 'ngo',
                'description' => 'Jejaring kerja sama untuk mendukung kinerja di bidang pendidikan, ekonomi, lingkungan, gender, kesehatan, kebudayaan, riset, dan digitalisasi.',
            ],
        ])->mapWithKeys(fn (array $partner) => [
            $partner['slug'] => Partner::query()->updateOrCreate(
                ['slug' => $partner['slug']],
                $partner + ['is_active' => true],
            ),
        ]);

        $researchProgram = Program::query()->updateOrCreate(
            ['slug' => 'kajian-riset-pemberdayaan-masyarakat'],
            [
                'title' => 'Kajian dan Riset Pemberdayaan Masyarakat',
                'excerpt' => 'Kajian pendidikan, ekonomi, lingkungan, gender, kesehatan, kebudayaan, riset, dan digitalisasi sebagai dasar informasi bagi pemangku kepentingan.',
                'description' => 'Program ini menjalankan kajian dan riset sebagai bahan informasi bagi stakeholders, dasar rekomendasi kebijakan, dan pijakan pengembangan program pemberdayaan masyarakat yang dapat dipertanggungjawabkan.',
                'category_id' => $programCategories['riset-digitalisasi']->id,
                'status' => 'published',
                'phase' => 'active',
                'start_date' => '2024-11-10',
                'end_date' => null,
                'location_name' => 'Bojonegoro dan wilayah kerja yayasan',
                'province' => 'Jawa Timur',
                'city' => 'Bojonegoro',
                'target_beneficiaries' => 'Masyarakat dan pemangku kepentingan',
                'beneficiaries_count' => 0,
                'budget_amount' => null,
                'featured_image_url' => '/image/logo.png',
                'is_featured' => true,
                'published_at' => now()->subMonths(2),
                'created_by' => $admin->id,
            ],
        );

        $communityProgram = Program::query()->updateOrCreate(
            ['slug' => 'pengabdian-dan-pemberdayaan-masyarakat'],
            [
                'title' => 'Pengabdian dan Pemberdayaan Masyarakat',
                'excerpt' => 'Pelaksanaan pengabdian dan pemberdayaan masyarakat di berbagai bidang kerja yayasan.',
                'description' => 'Program ini melaksanakan pengabdian, pemberdayaan, advokasi, dan pembinaan teknis maupun non-teknis dalam rangka meningkatkan kapasitas masyarakat.',
                'category_id' => $programCategories['pendidikan']->id,
                'status' => 'published',
                'phase' => 'active',
                'start_date' => '2024-11-10',
                'end_date' => null,
                'location_name' => 'Bojonegoro dan wilayah lain sesuai persetujuan Dewan Pembina',
                'province' => 'Jawa Timur',
                'city' => 'Bojonegoro',
                'target_beneficiaries' => 'Masyarakat umum dan generasi muda',
                'beneficiaries_count' => 0,
                'budget_amount' => null,
                'featured_image_url' => '/image/logo.png',
                'is_featured' => false,
                'published_at' => now()->subMonths(1),
                'created_by' => $admin->id,
            ],
        );

        $governanceProgram = Program::query()->updateOrCreate(
            ['slug' => 'sistem-lembaga-transparan-akuntabel'],
            [
                'title' => 'Sistem Lembaga yang Mandiri, Transparan, dan Akuntabel',
                'excerpt' => 'Pembangunan sistem kelembagaan untuk menjaga kemandirian, transparansi, dan akuntabilitas yayasan.',
                'description' => 'Program ini berfokus pada tata kelola kelembagaan, pembukuan sesuai standar akuntansi, laporan tahunan, serta penguatan sistem organisasi sebagaimana diatur dalam AD/ART.',
                'category_id' => $programCategories['riset-digitalisasi']->id,
                'status' => 'published',
                'phase' => 'active',
                'start_date' => '2024-11-10',
                'end_date' => null,
                'location_name' => 'Bojonegoro',
                'province' => 'Jawa Timur',
                'city' => 'Bojonegoro',
                'target_beneficiaries' => 'Organ yayasan dan publik',
                'beneficiaries_count' => 0,
                'budget_amount' => null,
                'featured_image_url' => '/image/logo.png',
                'is_featured' => false,
                'published_at' => now()->subWeeks(3),
                'created_by' => $admin->id,
            ],
        );

        $publicationProgram = Program::query()->updateOrCreate(
            ['slug' => 'publikasi-dan-penyebaran-informasi'],
            [
                'title' => 'Publikasi dan Penyebaran Informasi',
                'excerpt' => 'Penyediaan dan penyebaran informasi bermanfaat melalui publikasi.',
                'description' => 'Program ini mendorong kontribusi yayasan dalam memberikan tambahan pengetahuan dan informasi kepada masyarakat, khususnya generasi muda, melalui kegiatan publikasi.',
                'category_id' => $programCategories['riset-digitalisasi']->id,
                'status' => 'published',
                'phase' => 'active',
                'start_date' => '2024-11-10',
                'end_date' => null,
                'location_name' => 'Bojonegoro',
                'province' => 'Jawa Timur',
                'city' => 'Bojonegoro',
                'target_beneficiaries' => 'Masyarakat dan generasi muda',
                'beneficiaries_count' => 0,
                'budget_amount' => null,
                'featured_image_url' => '/image/logo.png',
                'is_featured' => false,
                'published_at' => now()->subWeeks(2),
                'created_by' => $admin->id,
            ],
        );

        $environmentProgram = Program::query()->updateOrCreate(
            ['slug' => 'pelestarian-lingkungan-dan-kebudayaan'],
            [
                'title' => 'Pelestarian Lingkungan dan Kebudayaan',
                'excerpt' => 'Dorongan peran yayasan dalam pelestarian sumber daya alam, lingkungan hidup, dan kebudayaan.',
                'description' => 'Program ini mengampanyekan prinsip pengelolaan yang bertanggung jawab dan berkelanjutan melalui pendidikan, pelatihan, dan peran nyata di lapangan.',
                'category_id' => $programCategories['lingkungan']->id,
                'status' => 'published',
                'phase' => 'upcoming',
                'start_date' => now()->addMonths(1)->toDateString(),
                'end_date' => null,
                'location_name' => 'Wilayah kerja yayasan',
                'province' => 'Jawa Timur',
                'city' => 'Bojonegoro',
                'target_beneficiaries' => 'Masyarakat dan komunitas lokal',
                'beneficiaries_count' => 0,
                'budget_amount' => null,
                'featured_image_url' => '/image/logo.png',
                'is_featured' => false,
                'published_at' => now()->subWeeks(1),
                'created_by' => $admin->id,
            ],
        );

        $researchProgram->partners()->sync([
            $partners['pemangku-kebijakan']->id => ['role' => 'Penerima rekomendasi berbasis kajian'],
            $partners['mitra-nasional-internasional']->id => ['role' => 'Jejaring pengembangan riset'],
        ]);

        $communityProgram->partners()->sync([
            $partners['masyarakat-komunitas-lokal']->id => ['role' => 'Penerima manfaat dan pelaksana berbasis komunitas'],
        ]);

        $publicationProgram->partners()->sync([
            $partners['masyarakat-komunitas-lokal']->id => ['role' => 'Penerima informasi publik'],
        ]);

        $researchProgram->galleries()->delete();
        $researchProgram->galleries()->createMany([
            [
                'file_url' => '/image/logo.png',
                'caption' => 'Logo GIRI Foundation sebagai identitas publik Yayasan Giri Nusantara Sejahtera.',
                'sort_order' => 1,
            ],
        ]);

        foreach ([
            [
                'slug' => 'penetapan-adart-gns',
                'program_id' => $governanceProgram->id,
                'title' => 'Penetapan AD/ART Yayasan',
                'summary' => 'Anggaran Dasar dan Anggaran Rumah Tangga ditetapkan di Bojonegoro pada 10 November 2024.',
                'description' => 'AD/ART menjadi dasar operasional, konseptual, dan tata kelola Yayasan Giri Nusantara Sejahtera.',
                'activity_date' => '2024-11-10',
                'location_name' => 'Bojonegoro',
                'featured_image_url' => '/image/logo.png',
                'status' => 'published',
                'published_at' => now()->subMonths(2),
                'created_by' => $admin->id,
            ],
            [
                'slug' => 'penyusunan-ruang-lingkup-kegiatan',
                'program_id' => $researchProgram->id,
                'title' => 'Penyusunan Ruang Lingkup Kegiatan',
                'summary' => 'Ruang lingkup kegiatan ditetapkan meliputi pendidikan, ekonomi, lingkungan, gender, kesehatan, kebudayaan, riset, dan digitalisasi.',
                'description' => 'Ruang lingkup ini menjadi rujukan bagi kajian, pengabdian, advokasi, pelayanan keahlian, kerja sama, dan publikasi yayasan.',
                'activity_date' => '2024-11-10',
                'location_name' => 'Bojonegoro',
                'featured_image_url' => '/image/logo.png',
                'status' => 'published',
                'published_at' => now()->subWeeks(6),
                'created_by' => $admin->id,
            ],
        ] as $activity) {
            Activity::query()->updateOrCreate(['slug' => $activity['slug']], $activity);
        }

        foreach ([
            [
                'slug' => 'filosofi-logo-giri-foundation',
                'title' => 'Filosofi Logo GIRI Foundation',
                'summary' => 'Perisai, warna kuning emas, hijau, dan nilai Asah Asih Asuh menjadi identitas gerak yayasan.',
                'description' => 'Video pengantar mengenai makna lambang GIRI Foundation berdasarkan AD/ART.',
                'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'thumbnail_url' => '/image/logo.png',
                'sort_order' => 1,
                'status' => 'published',
                'published_at' => now()->subWeeks(2),
                'created_by' => $admin->id,
            ],
            [
                'slug' => 'ruang-lingkup-pemberdayaan-masyarakat',
                'title' => 'Ruang Lingkup Pemberdayaan Masyarakat',
                'summary' => 'Pengenalan bidang kerja yayasan: pendidikan, ekonomi, lingkungan, gender, kesehatan, kebudayaan, riset, dan digitalisasi.',
                'description' => 'Video ringkas mengenai bidang kegiatan Yayasan Giri Nusantara Sejahtera.',
                'youtube_url' => 'https://www.youtube.com/watch?v=9bZkp7q19f0',
                'thumbnail_url' => '/image/logo.png',
                'sort_order' => 2,
                'status' => 'published',
                'published_at' => now()->subDays(10),
                'created_by' => $admin->id,
            ],
        ] as $video) {
            Video::query()->updateOrCreate(['slug' => $video['slug']], $video);
        }

        $contentCategories = collect([
            ['name' => 'Tata Kelola', 'slug' => 'tata-kelola', 'type' => 'story', 'description' => 'Informasi tentang kelembagaan, AD/ART, dan organ yayasan.'],
            ['name' => 'Pemberdayaan', 'slug' => 'pemberdayaan', 'type' => 'story', 'description' => 'Narasi mengenai ruang lingkup pemberdayaan masyarakat.'],
            ['name' => 'Publikasi Resmi', 'slug' => 'publikasi-resmi', 'type' => 'journal', 'description' => 'Dokumen dan naskah resmi yayasan.'],
            ['name' => 'Kebijakan Organisasi', 'slug' => 'kebijakan-organisasi', 'type' => 'article', 'description' => 'Tulisan mengenai kebijakan dan ketentuan organisasi.'],
        ])->mapWithKeys(fn (array $category) => [
            $category['slug'] => ContentCategory::query()->updateOrCreate(['slug' => $category['slug']], $category),
        ]);

        $tags = collect([
            ['name' => 'AD/ART', 'slug' => 'adart'],
            ['name' => 'Pemberdayaan', 'slug' => 'pemberdayaan'],
            ['name' => 'Tata Kelola', 'slug' => 'tata-kelola'],
            ['name' => 'Asah Asih Asuh', 'slug' => 'asah-asih-asuh'],
        ])->mapWithKeys(fn (array $tag) => [
            $tag['slug'] => Tag::query()->updateOrCreate(['slug' => $tag['slug']], $tag),
        ]);

        foreach ([
            [
                'slug' => 'yayasan-independen-pemberdayaan-masyarakat',
                'title' => 'Yayasan Independen untuk Pemberdayaan Masyarakat',
                'type' => 'story',
                'category_id' => $contentCategories['pemberdayaan']->id,
                'excerpt' => 'GNS bergerak sebagai lembaga independen yang fokus pada pemberdayaan masyarakat.',
                'body' => 'Yayasan Giri Nusantara Sejahtera bergerak sebagai lembaga independen yang berfokus pada pemberdayaan masyarakat. Ruang geraknya meliputi pendidikan, ekonomi, lingkungan, gender, kesehatan, kebudayaan, riset, dan digitalisasi.',
                'featured_image_url' => '/image/logo.png',
                'author_id' => $admin->id,
                'status' => 'published',
                'is_featured' => true,
                'published_at' => now()->subMonths(1),
                'seo_title' => 'Yayasan Independen untuk Pemberdayaan Masyarakat',
                'seo_description' => 'Profil ringkas GNS sebagai lembaga independen pemberdayaan masyarakat.',
            ],
            [
                'slug' => 'asah-asih-asuh-sebagai-filosofi-gerak',
                'title' => 'Asah Asih Asuh sebagai Filosofi Gerak',
                'type' => 'story',
                'category_id' => $contentCategories['tata-kelola']->id,
                'excerpt' => 'Asah Asih Asuh menjadi filosofi peningkatan kapasitas, saling menguatkan, dan saling membina.',
                'body' => 'Dalam lambang GIRI Foundation, Asah Asih Asuh dimaknai sebagai filosofi bahwa setiap gerak harus meningkatkan kapasitas sumber daya manusia, saling menguatkan dan membesarkan, serta saling membina satu sama lain.',
                'featured_image_url' => '/image/logo.png',
                'author_id' => $admin->id,
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now()->subWeeks(3),
                'seo_title' => 'Asah Asih Asuh sebagai Filosofi Gerak',
                'seo_description' => 'Filosofi Asah Asih Asuh dalam identitas GIRI Foundation.',
            ],
            [
                'slug' => 'independensi-dan-musyawarah-mufakat',
                'title' => 'Independensi dan Musyawarah Mufakat',
                'type' => 'story',
                'category_id' => $contentCategories['tata-kelola']->id,
                'excerpt' => 'GNS menegaskan sifat independen dan mengedepankan musyawarah mufakat dalam pengambilan keputusan.',
                'body' => 'AD/ART menegaskan bahwa Yayasan Giri Nusantara Sejahtera tidak berafiliasi dengan organisasi politik, organisasi keagamaan, maupun organisasi lain yang tidak memiliki hubungan langsung dengan yayasan. Pengambilan keputusan organisasi mengedepankan musyawarah untuk mufakat.',
                'featured_image_url' => '/image/logo.png',
                'author_id' => $admin->id,
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now()->subWeeks(2),
                'seo_title' => 'Independensi dan Musyawarah Mufakat',
                'seo_description' => 'Prinsip independensi dan musyawarah mufakat dalam AD/ART GNS.',
            ],
        ] as $story) {
            $content = Content::query()->updateOrCreate(['slug' => $story['slug']], $story);

            $content->tags()->sync(match ($story['slug']) {
                'asah-asih-asuh-sebagai-filosofi-gerak' => [$tags['asah-asih-asuh']->id, $tags['tata-kelola']->id],
                'independensi-dan-musyawarah-mufakat' => [$tags['adart']->id, $tags['tata-kelola']->id],
                default => [$tags['pemberdayaan']->id],
            });
        }

        foreach ([
            [
                'slug' => 'anggaran-dasar-yayasan-giri-nusantara-sejahtera',
                'title' => 'Anggaran Dasar Yayasan Giri Nusantara Sejahtera',
                'type' => 'journal',
                'category_id' => $contentCategories['publikasi-resmi']->id,
                'excerpt' => 'Naskah dasar organisasi yang mengatur nama, kedudukan, asas, visi, misi, tujuan, ruang lingkup, organ, keuangan, perubahan, dan pembubaran yayasan.',
                'body' => 'Anggaran Dasar Yayasan Giri Nusantara Sejahtera ditetapkan di Bojonegoro pada 10 November 2024 sebagai landasan dasar organisasi.',
                'featured_image_url' => '/image/logo.png',
                'author_id' => $admin->id,
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now()->subDays(18),
                'seo_title' => 'Anggaran Dasar GNS',
                'seo_description' => 'Ringkasan Anggaran Dasar Yayasan Giri Nusantara Sejahtera.',
            ],
            [
                'slug' => 'anggaran-rumah-tangga-yayasan-giri-nusantara-sejahtera',
                'title' => 'Anggaran Rumah Tangga Yayasan Giri Nusantara Sejahtera',
                'type' => 'article',
                'category_id' => $contentCategories['kebijakan-organisasi']->id,
                'excerpt' => 'Ketentuan internal mengenai disiplin organisasi, pemberhentian pengurus, organ yayasan, dan bidang kajian.',
                'body' => 'Anggaran Rumah Tangga mengatur disiplin organisasi, tata cara pemberhentian Dewan Pengurus, kedudukan Dewan Penasihat dan Dewan Pembina, serta struktur Dewan Pengurus dan Bidang Kajian.',
                'featured_image_url' => '/image/logo.png',
                'author_id' => $admin->id,
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now()->subDays(7),
                'seo_title' => 'Anggaran Rumah Tangga GNS',
                'seo_description' => 'Ringkasan Anggaran Rumah Tangga Yayasan Giri Nusantara Sejahtera.',
            ],
            [
                'slug' => 'kode-etik-kekayaan-dan-donasi',
                'title' => 'Kode Etik Kekayaan dan Donasi',
                'type' => 'opinion',
                'category_id' => $contentCategories['kebijakan-organisasi']->id,
                'excerpt' => 'Perolehan kekayaan yayasan harus sesuai AD/ART, dapat dipertanggungjawabkan secara etika dan hukum, serta bebas dari kepentingan partisan.',
                'body' => 'AD/ART mengatur bahwa sumbangan, bantuan, wakaf, hibah, hibah wasiat, dan perolehan lain harus tidak bertentangan dengan AD/ART atau peraturan perundang-undangan, dapat dipertanggungjawabkan secara etika dan hukum, serta bebas dari kepentingan partisan.',
                'featured_image_url' => '/image/logo.png',
                'author_id' => $admin->id,
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now()->subDays(5),
                'seo_title' => 'Kode Etik Kekayaan dan Donasi',
                'seo_description' => 'Ketentuan kode etik kekayaan dan donasi Yayasan Giri Nusantara Sejahtera.',
            ],
        ] as $publication) {
            Content::query()->updateOrCreate(['slug' => $publication['slug']], $publication);
        }

        foreach ([
            [
                'title' => 'Anggaran Dasar dan Anggaran Rumah Tangga GNS',
                'slug' => 'adart-yayasan-giri-nusantara-sejahtera',
                'category' => 'Dokumen Organisasi',
                'description' => 'Dokumen AD/ART Yayasan Giri Nusantara Sejahtera yang ditetapkan di Bojonegoro pada 10 November 2024.',
                'file_url' => '#',
                'thumbnail_url' => null,
                'file_type' => 'PDF',
                'file_size' => 443581,
                'download_count' => 0,
                'is_public' => true,
                'published_at' => now()->subMonths(1),
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Ringkasan Visi, Misi, dan Tujuan',
                'slug' => 'ringkasan-visi-misi-tujuan-gns',
                'category' => 'Profil Yayasan',
                'description' => 'Ringkasan visi, misi, tujuan, dan ruang lingkup kegiatan Yayasan Giri Nusantara Sejahtera.',
                'file_url' => '#',
                'thumbnail_url' => null,
                'file_type' => 'PDF',
                'file_size' => null,
                'download_count' => 0,
                'is_public' => true,
                'published_at' => now()->subWeeks(3),
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Kode Etik Kekayaan Yayasan',
                'slug' => 'kode-etik-kekayaan-yayasan',
                'category' => 'Tata Kelola',
                'description' => 'Ketentuan etik bagi sumbangan, bantuan, wakaf, hibah, hibah wasiat, dan perolehan lain.',
                'file_url' => '#',
                'thumbnail_url' => null,
                'file_type' => 'PDF',
                'file_size' => null,
                'download_count' => 0,
                'is_public' => true,
                'published_at' => now()->subWeeks(2),
                'created_by' => $admin->id,
            ],
        ] as $document) {
            Document::query()->updateOrCreate(['slug' => $document['slug']], $document);
        }

        $campaign = DonationCampaign::query()->updateOrCreate(
            ['slug' => 'dukungan-pemberdayaan-masyarakat-gns'],
            [
                'title' => 'Dukungan Pemberdayaan Masyarakat GNS',
                'short_description' => 'Dukungan tidak mengikat untuk kegiatan pemberdayaan masyarakat sesuai AD/ART dan kode etik kekayaan yayasan.',
                'description' => 'Kampanye ini mencatat minat dukungan publik untuk program pemberdayaan masyarakat. Setiap dukungan wajib sejalan dengan AD/ART, dapat dipertanggungjawabkan secara etika dan hukum, serta bebas dari kepentingan partisan.',
                'target_amount' => 200000000,
                'collected_amount' => 0,
                'start_date' => '2024-11-10',
                'end_date' => null,
                'banner_image_url' => '/image/logo.png',
                'status' => 'active',
                'is_featured' => true,
                'published_by' => $admin->id,
            ],
        );

        foreach ([
            [
                'title' => 'Dukungan mengikuti kode etik kekayaan',
                'content' => 'Sumbangan, bantuan, wakaf, hibah, dan perolehan lain harus tidak bertentangan dengan AD/ART, dapat dipertanggungjawabkan secara etika dan hukum, serta bebas dari kepentingan partisan.',
                'image_url' => '/image/logo.png',
                'published_at' => now()->subWeeks(2),
            ],
        ] as $update) {
            DonationUpdate::query()->updateOrCreate(
                ['campaign_id' => $campaign->id, 'title' => $update['title']],
                $update + ['campaign_id' => $campaign->id],
            );
        }

        foreach (PageContentDefaults::pages() as $slug => $page) {
            Page::query()->updateOrCreate(
                ['slug' => $slug],
                $page + [
                    'content' => $page['content'] ?? $page['title'],
                    'status' => 'published',
                    'published_at' => now()->subMonth(),
                    'created_by' => $admin->id,
                ],
            );
        }
    }

    private function retireLegacyDefaultAdmin(): void
    {
        $legacyAdmin = User::query()
            ->where('email', 'admin@giri.foundation')
            ->first();

        if (! $legacyAdmin || blank($legacyAdmin->password_hash) || (! Hash::check('password', $legacyAdmin->password_hash))) {
            return;
        }

        $legacyAdmin->roles()->detach();
        $legacyAdmin->forceFill([
            'name' => 'Legacy Disabled Admin',
            'password' => Str::random(40),
            'status' => 'inactive',
            'last_login_at' => null,
            'email_verified_at' => null,
        ])->save();
    }

    private function seedInitialAdmin(Role $adminRole): void
    {
        $name = env('FILAMENT_ADMIN_NAME');
        $email = env('FILAMENT_ADMIN_EMAIL');
        $password = env('FILAMENT_ADMIN_PASSWORD');

        if (blank($name) || blank($email) || blank($password)) {
            return;
        }

        $admin = User::query()->updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => $password,
                'phone' => null,
                'status' => 'active',
                'avatar_url' => null,
                'email_verified_at' => now(),
            ],
        );

        $admin->roles()->syncWithoutDetaching([$adminRole->id]);
    }

    private function removeLegacyDummyContent(): void
    {
        TeamMember::query()
            ->whereIn('slug', [
                'dewi-paramita',
                'bagus-naratama',
                'amara-giri',
                'elena-vance',
                'raka-nirwan',
                'sinta-maharani',
                'rafi-prasetya',
                'dina-samudra',
                'ayu-prameswari',
            ])
            ->delete();

        Activity::query()
            ->whereIn('slug', [
                'restoration-cycle-q4',
                'digital-hub-serangan',
            ])
            ->delete();

        Video::query()
            ->whereIn('slug', [
                'membaca-lanskap-bersama-komunitas',
                'arsip-video-program-pesisir',
            ])
            ->delete();

        Program::query()
            ->withTrashed()
            ->whereIn('slug', [
                'sacred-grove-restoration-project',
                'regenerative-agriculture',
                'literacy-project',
                'holistic-health-hubs',
                'indigo-weavers-collective',
                'mobile-health-sanctuaries',
            ])
            ->forceDelete();

        Content::query()
            ->withTrashed()
            ->whereIn('slug', [
                'silent-resonance-of-tradition',
                'silent-architect-high-valleys',
                'beyond-the-well',
                'roots-of-resilience',
                'designing-for-dignity',
                'jurnal-ketahanan-pesisir-2026',
                'berita-peluncuran-media-hub',
                'artikel-merawat-arsip-lapangan',
                'opini-kolaborasi-yang-bertahan-lama',
            ])
            ->forceDelete();

        Document::query()
            ->whereIn('slug', [
                '2023-annual-impact-narrative',
                'ethical-governance-framework',
                'quarterly-environmental-audit-q4',
                'strategic-plan-2026-2030',
            ])
            ->delete();

        DonationCampaign::query()
            ->where('slug', 'solar-power-for-giri-central-school')
            ->delete();

        Partner::query()
            ->whereIn('slug', [
                'global-alliance',
                'village-collective',
                'trust-co',
                'unity-fund',
            ])
            ->delete();

        Division::query()
            ->whereIn('slug', [
                'leadership',
                'editorial',
                'program',
                'partnership',
            ])
            ->delete();

        ProgramCategory::query()
            ->whereIn('slug', [
                'sustainability',
                'education',
                'health',
                'craft-preservation',
            ])
            ->delete();

        ContentCategory::query()
            ->whereIn('slug', [
                'culture',
                'education-stories',
                'conservation-strategy',
                'policy-journal',
                'organization-news',
                'thematic-articles',
                'editorial-opinion',
            ])
            ->delete();
    }
}
