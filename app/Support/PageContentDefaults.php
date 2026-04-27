<?php

namespace App\Support;

class PageContentDefaults
{
    /**
     * @return array<string, array<string, mixed>>
     */
    public static function pages(): array
    {
        return [
            'home' => [
                'title' => 'Beranda',
                'template' => 'home',
                'seo_title' => 'GIRI Foundation - Yayasan Giri Nusantara Sejahtera',
                'seo_description' => 'Lembaga independen yang fokus pada pemberdayaan masyarakat.',
                'hero_data' => [
                    'kicker' => 'Yayasan Giri Nusantara Sejahtera',
                    'title_prefix' => 'Pemberdayaan masyarakat untuk',
                    'highlight' => 'kesejahteraan',
                    'title_suffix' => 'Indonesia.',
                    'body' => 'GNS adalah lembaga independen yang bergerak di bidang pendidikan, ekonomi, lingkungan, gender, kesehatan, kebudayaan, riset, dan digitalisasi.',
                    'primary_cta_label' => 'Dukung Program',
                    'primary_cta_url' => '/donate',
                    'secondary_cta_label' => 'Jelajahi Program',
                    'secondary_cta_url' => '/programs',
                ],
                'section_data' => [
                    'programs' => [
                        'kicker' => 'Program',
                        'title' => 'Ruang lingkup pemberdayaan sesuai AD/ART.',
                        'cta_label' => 'Lihat Semua Program',
                    ],
                    'media' => [
                        'kicker' => 'Media',
                        'title' => 'Aktivitas dan video kelembagaan.',
                        'activities_label' => 'Aktivitas',
                        'videos_label' => 'Video',
                        'cta_label' => 'Jelajahi Media',
                    ],
                    'publications' => [
                        'kicker' => 'Publikasi',
                        'title' => 'Dokumen, kebijakan, dan informasi resmi yayasan.',
                        'editorial_label' => 'Publikasi Resmi',
                        'archive_label' => 'Dokumen',
                        'cta_label' => 'Lihat Publikasi',
                    ],
                    'closing' => [
                        'kicker' => 'Terhubung Dengan Kami',
                        'title' => 'Bangun kerja sama pemberdayaan masyarakat bersama GNS.',
                        'body' => 'Kami terbuka untuk percakapan mengenai kajian, pengabdian, advokasi, pembinaan, publikasi, dan kerja sama yang sejalan dengan AD/ART.',
                        'primary_cta_label' => 'Hubungi Kami',
                        'primary_cta_url' => '/contact',
                        'secondary_cta_label' => 'Lihat Media',
                        'secondary_cta_url' => '/media',
                    ],
                ],
            ],
            'about' => [
                'title' => 'Tentang',
                'template' => 'about',
                'seo_title' => 'Tentang Yayasan Giri Nusantara Sejahtera',
                'seo_description' => 'Profil, visi, misi, dan struktur Yayasan Giri Nusantara Sejahtera.',
                'hero_data' => [
                    'kicker' => 'Tentang Yayasan',
                    'title_prefix' => 'Lembaga independen untuk',
                    'highlight' => 'pemberdayaan',
                    'title_suffix' => 'masyarakat.',
                    'body' => 'Yayasan Giri Nusantara Sejahtera berdiri di Bojonegoro pada 10 November 2024 dan berasaskan Pancasila.',
                ],
                'section_data' => [
                    'brand' => [
                        'kicker' => 'Identitas Lembaga',
                        'title' => 'GIRI FOUNDATION',
                        'subtitle' => 'Yayasan Giri Nusantara Sejahtera, disingkat GNS, adalah lembaga independen yang fokus pada pemberdayaan masyarakat.',
                        'note' => 'Bojonegoro, Jawa Timur. Ditetapkan pada 10 November 2024.',
                    ],
                    'profile' => [
                        'kicker' => 'Profil Lembaga',
                        'title' => 'Landasan, sifat, dan ruang gerak yayasan.',
                    ],
                    'values' => [
                        'kicker' => 'Nilai Kami',
                        'title' => 'Prinsip yang menuntun keputusan organisasi.',
                        'description_template' => ':value menjadi dasar dalam menjaga pemberdayaan masyarakat, independensi, dan akuntabilitas yayasan.',
                    ],
                    'personnel' => [
                        'kicker' => 'Personil',
                        'title' => 'Organ yayasan sesuai Anggaran Dasar.',
                    ],
                ],
            ],
            'programs' => [
                'title' => 'Program',
                'template' => 'programs',
                'seo_title' => 'Program Yayasan Giri Nusantara Sejahtera',
                'seo_description' => 'Program pemberdayaan masyarakat sesuai ruang lingkup AD/ART.',
                'hero_data' => [
                    'kicker' => 'Program',
                    'title_prefix' => 'Kajian, pengabdian, dan',
                    'highlight' => 'pemberdayaan',
                    'title_suffix' => 'masyarakat.',
                    'body' => 'Program GNS mencakup pendidikan, ekonomi, lingkungan, gender, kesehatan, kebudayaan, riset, dan digitalisasi.',
                ],
                'section_data' => [
                    'active' => [
                        'kicker' => 'Program Aktif',
                        'title' => 'Kegiatan yang sedang dijalankan yayasan.',
                    ],
                    'partnership' => [
                        'kicker' => 'Program Kerja Sama',
                        'title' => 'Kerja sama dengan masyarakat, pemangku kebijakan, dan mitra strategis.',
                    ],
                    'upcoming' => [
                        'kicker' => 'Program Mendatang',
                        'title' => 'Agenda yang disiapkan untuk perluasan pemberdayaan.',
                    ],
                    'archive' => [
                        'kicker' => 'Arsip Program',
                        'title' => 'Rekam kegiatan yang menjadi bagian dari tata kelola yayasan.',
                    ],
                ],
            ],
            'media' => [
                'title' => 'Media',
                'template' => 'media',
                'seo_title' => 'Media GIRI Foundation',
                'seo_description' => 'Dokumentasi aktivitas dan video Yayasan Giri Nusantara Sejahtera.',
                'hero_data' => [
                    'kicker' => 'Media',
                    'title_prefix' => 'Dokumentasi',
                    'highlight' => 'aktivitas',
                    'title_suffix' => 'dan informasi yayasan.',
                    'body' => 'Media memuat dokumentasi kegiatan kelembagaan, program, dan informasi publik GNS.',
                ],
                'section_data' => [
                    'activities' => [
                        'kicker' => 'Aktivitas',
                        'title' => 'Dokumentasi kegiatan yayasan.',
                    ],
                    'videos' => [
                        'kicker' => 'Video',
                        'title' => 'Video pengantar kelembagaan dan program.',
                    ],
                ],
            ],
            'publikasi' => [
                'title' => 'Publikasi',
                'template' => 'publikasi',
                'seo_title' => 'Publikasi Yayasan Giri Nusantara Sejahtera',
                'seo_description' => 'Dokumen organisasi, kebijakan, dan publikasi resmi GNS.',
                'hero_data' => [
                    'kicker' => 'Publikasi',
                    'title_prefix' => 'Dokumen, kebijakan, dan',
                    'highlight' => 'informasi',
                    'title_suffix' => 'resmi.',
                    'body' => 'Publikasi memuat AD/ART, kebijakan organisasi, dan informasi yang bermanfaat bagi masyarakat.',
                ],
                'section_data' => [
                    'journals' => [
                        'kicker' => 'Dokumen Resmi',
                        'title' => 'Naskah dan ringkasan kelembagaan.',
                    ],
                    'archives' => [
                        'kicker' => 'Arsip',
                        'title' => 'Dokumen organisasi yang dapat diakses publik.',
                    ],
                    'news' => [
                        'kicker' => 'Berita',
                        'title' => 'Kabar terbaru yayasan.',
                    ],
                    'articles' => [
                        'kicker' => 'Artikel',
                        'title' => 'Tulisan mengenai kebijakan dan program.',
                    ],
                    'opinions' => [
                        'kicker' => 'Opini',
                        'title' => 'Catatan dan refleksi kelembagaan.',
                    ],
                ],
            ],
            'stories' => [
                'title' => 'Cerita Dari Lapangan',
                'template' => 'stories',
                'seo_title' => 'Cerita GIRI Foundation',
                'seo_description' => 'Narasi mengenai pemberdayaan masyarakat dan tata kelola GNS.',
                'hero_data' => [
                    'kicker' => 'Cerita Pilihan',
                    'primary_cta_label' => 'Baca Arsip Lengkap',
                ],
                'section_data' => [
                    'newsletter' => [
                        'title' => 'Ikuti perkembangan pemberdayaan masyarakat.',
                        'body' => 'Dapatkan ringkasan kegiatan, publikasi, dan pembelajaran kelembagaan GNS.',
                    ],
                    'archive' => [
                        'kicker' => 'Lebih Banyak Dari Arsip',
                    ],
                ],
            ],
            'contact' => [
                'title' => 'Kontak',
                'template' => 'contact',
                'seo_title' => 'Kontak Yayasan Giri Nusantara Sejahtera',
                'seo_description' => 'Hubungi GNS untuk kerja sama, informasi, dan kegiatan pemberdayaan masyarakat.',
                'hero_data' => [
                    'kicker' => 'Kontak',
                    'title_prefix' => 'Mulai percakapan',
                    'highlight' => 'pemberdayaan',
                    'title_suffix' => 'bersama.',
                    'body' => 'Hubungi kami untuk kerja sama, kajian, publikasi, advokasi, atau informasi kelembagaan.',
                ],
                'section_data' => [
                    'details' => [
                        'kicker' => 'Informasi Kontak',
                        'title' => 'Kanal komunikasi Yayasan Giri Nusantara Sejahtera.',
                    ],
                    'form' => [
                        'title' => 'Kirim pesan kepada pengurus yayasan.',
                    ],
                    'location' => [
                        'kicker' => 'Lokasi',
                        'title' => 'Kedudukan yayasan.',
                    ],
                ],
            ],
            'donate' => [
                'title' => 'Donasi',
                'template' => 'donate',
                'seo_title' => 'Dukung GIRI Foundation',
                'seo_description' => 'Dukungan untuk kegiatan pemberdayaan masyarakat sesuai kode etik kekayaan yayasan.',
                'hero_data' => [
                    'kicker' => 'Dukungan',
                    'title_prefix' => 'Dukungan tidak mengikat untuk',
                    'highlight' => 'pemberdayaan',
                    'title_suffix' => 'masyarakat.',
                ],
                'section_data' => [
                    'documents' => [
                        'kicker' => 'Dokumen',
                        'title' => 'Dasar tata kelola dukungan',
                        'link_label' => 'Buka arsip',
                    ],
                ],
            ],
            'resources' => [
                'title' => 'Dokumen & Wawasan',
                'template' => 'resources',
                'seo_title' => 'Dokumen Yayasan Giri Nusantara Sejahtera',
                'seo_description' => 'Arsip dokumen organisasi, profil, dan tata kelola GNS.',
                'hero_data' => [
                    'kicker' => 'Arsip Dokumen',
                    'title_prefix' => 'Dokumen &',
                    'highlight' => 'Wawasan',
                    'title_suffix' => '',
                    'body' => 'Telusuri AD/ART, ringkasan visi-misi, kode etik, dan dokumen tata kelola yayasan.',
                ],
                'section_data' => [
                    'filters' => [
                        'search_label' => 'Cari',
                        'search_placeholder' => 'Cari berdasarkan judul atau deskripsi...',
                        'category_label' => 'Kategori',
                        'submit_label' => 'Saring Dokumen',
                    ],
                ],
            ],
            'partners' => [
                'title' => 'Kemitraan',
                'template' => 'partners',
                'seo_title' => 'Kemitraan Yayasan Giri Nusantara Sejahtera',
                'seo_description' => 'Kerja sama nasional maupun internasional untuk mendukung ruang lingkup kegiatan GNS.',
                'hero_data' => [
                    'kicker' => 'Kemitraan',
                    'title_prefix' => 'Kerja sama untuk',
                    'highlight' => 'pemberdayaan',
                    'title_suffix' => 'yang bertanggung jawab.',
                    'body' => 'GNS membangun kerja sama dengan masyarakat, pemangku kebijakan, dan mitra nasional maupun internasional yang sejalan dengan AD/ART.',
                ],
                'section_data' => [
                    'highlight' => [
                        'label' => 'Mitra Strategis',
                        'body' => 'Kemitraan mendukung kegiatan pendidikan, ekonomi, lingkungan, gender, kesehatan, kebudayaan, riset, dan digitalisasi.',
                    ],
                    'collaborators' => [
                        'kicker' => 'Kolaborator',
                        'title' => 'Ruang kerja sama untuk mendukung kinerja yayasan.',
                    ],
                    'programs' => [
                        'kicker' => 'Program Bersama Mitra',
                        'title' => 'Kegiatan yang melibatkan masyarakat dan pemangku kepentingan.',
                    ],
                    'inquiry' => [
                        'kicker' => 'Bangun Kerja Sama',
                        'title' => 'Mulai percakapan kemitraan.',
                        'submit_label' => 'Kirim Permintaan',
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function definition(string $slug): array
    {
        return static::pages()[$slug] ?? [];
    }
}
