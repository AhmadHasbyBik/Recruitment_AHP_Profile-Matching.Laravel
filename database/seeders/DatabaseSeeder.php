<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Criteria;
use App\Models\CriteriaStatus;
use App\Models\Candidate;
use App\Models\CandidateCriteria;
use App\Models\Weight;
use App\Models\AhpPairwiseComparison;
use App\Models\ProfileMatchingResult;
use App\Models\Vacancy;
use App\Models\IdealProfileValue;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Buat status kriteria
        $this->createCriteriaStatuses();

        // Buat kriteria
        $this->createCriterias();

        // Buat lowongan pekerjaan
        $this->createVacancies();

        // Buat user utama (jangan diubah)
        $this->createMainUsers();

        // Buat user dan kandidat untuk masing-masing posisi
        $this->createPositionCandidates();

        // Buat perbandingan AHP
        $this->createAhpComparisons();

        // Hitung bobot dari perbandingan AHP
        AhpPairwiseComparison::calculateWeights();

        // Buat nilai ideal
        $this->createIdealProfileValues();
    }

    private function createCriteriaStatuses()
    {
        $statuses = [
            ['code' => 'A1', 'name' => 'Core'],
            ['code' => 'A2', 'name' => 'Secondary'],
        ];

        foreach ($statuses as $status) {
            CriteriaStatus::create($status);
        }
    }

    private function createCriterias()
    {
        $criterias = [
            [
                'code' => 'C1',
                'name' => 'Pendidikan',
                'criteria_status_id' => 1,
                'type' => 'core',
                'description' => 'Latar belakang pendidikan yang dimiliki kandidat'
            ],
            [
                'code' => 'C2',
                'name' => 'Kemampuan',
                'criteria_status_id' => 1,
                'type' => 'core',
                'description' => 'Kecakapan atau skill yang relevan dengan posisi'
            ],
            [
                'code' => 'C3',
                'name' => 'Pengalaman',
                'criteria_status_id' => 1,
                'type' => 'core',
                'description' => 'Riwayat kerja dan jam terbang dalam bidang terkait'
            ],
            [
                'code' => 'C4',
                'name' => 'Wawancara',
                'criteria_status_id' => 2,
                'type' => 'secondary',
                'description' => 'Penilaian selama proses wawancara langsung'
            ],
            [
                'code' => 'C5',
                'name' => 'Sertifikasi Pendukung',
                'criteria_status_id' => 2,
                'type' => 'secondary',
                'description' => 'Dokumen pendukung seperti sertifikat atau pelatihan'
            ],
        ];

        foreach ($criterias as $criteria) {
            Criteria::create($criteria);
        }
    }

    private function createVacancies()
    {
        $vacancies = [
            [
                'position' => 'Sales',
                'description' => 'Bertanggung jawab untuk menjual produk dan membangun hubungan dengan pelanggan guna meningkatkan penjualan.',
                'open_date' => now()->subDays(10),
                'close_date' => now()->addDays(20),
                'is_active' => true,
            ],
            [
                'position' => 'Digital Marketing',
                'description' => 'Merancang dan mengelola strategi pemasaran digital untuk meningkatkan visibilitas dan engagement online.',
                'open_date' => now()->subDays(15),
                'close_date' => now()->addDays(15),
                'is_active' => true,
            ],

        ];

        foreach ($vacancies as $vacancy) {
            Vacancy::create($vacancy);
        }
    }

    private function createMainUsers()
    {
        $users = [
            ['name' => 'Super Admin', 'email' => 'superadmin@example.com', 'password' => bcrypt('password'), 'role' => 'super_admin'],
            ['name' => 'HRD', 'email' => 'hrd@example.com', 'password' => bcrypt('password'), 'role' => 'hrd'],
            ['name' => 'Direktur', 'email' => 'direktur@example.com', 'password' => bcrypt('password'), 'role' => 'direktur'],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }

    private function createPositionCandidates()
    {
        $positions = [
            [
                'vacancy_id' => 1,
                'position' => 'Sales',
                'candidates' => [
                    [
                        'user' => ['name' => 'Ahmad Firdaus', 'email' => 'ahmad.firdaus@example.com', 'password' => bcrypt('password'), 'role' => 'user'],
                        'candidate' => [
                            'name' => 'Ahmad Firdaus',
                            'phone' => '081234567891',
                            'address' => 'Jl. KH. Wahid Hasyim No. 15, Kec. Jombang, Kabupaten Jombang, Jawa Timur',
                            'resume' => 'Ahmad_Firdaus_CV.pdf',
                            'criteria_values' => [5, 4, 5, 4, 4]
                        ]
                    ],
                    [
                        'user' => ['name' => 'Nurul Aisyah', 'email' => 'nurul.aisyah@example.com', 'password' => bcrypt('password'), 'role' => 'user'],
                        'candidate' => [
                            'name' => 'Nurul Aisyah',
                            'phone' => '081234567892',
                            'address' => 'Perum Taman Kertosari Blok C-12, Kec. Kota Kediri, Kabupaten Kediri, Jawa Timur',
                            'resume' => 'Nurul_Aisyah_CV.pdf',
                            'criteria_values' => [4, 3, 4, 3, 3]
                        ]
                    ],
                    [
                        'user' => ['name' => 'Reza Saputra', 'email' => 'reza.saputra@example.com', 'password' => bcrypt('password'), 'role' => 'user'],
                        'candidate' => [
                            'name' => 'Reza Saputra',
                            'phone' => '081234567893',
                            'address' => 'Jl. Diponegoro No. 78, Kec. Lamongan, Kabupaten Lamongan, Jawa Timur',
                            'resume' => 'Reza_Saputra_CV.pdf',
                            'criteria_values' => [5, 5, 4, 4, 4]
                        ]
                    ],
                    [
                        'user' => ['name' => 'Linda Putri', 'email' => 'linda.putri@example.com', 'password' => bcrypt('password'), 'role' => 'user'],
                        'candidate' => [
                            'name' => 'Linda Putri',
                            'phone' => '081234567894',
                            'address' => 'Ds. Mojokrapak RT 03/RW 05, Kec. Tembelang, Kabupaten Jombang, Jawa Timur',
                            'resume' => 'Linda_Putri_CV.pdf',
                            'criteria_values' => [4, 4, 3, 4, 3]
                        ]
                    ],
                    [
                        'user' => ['name' => 'Rudi Hartono', 'email' => 'rudi.hartono@example.com', 'password' => bcrypt('password'), 'role' => 'user'],
                        'candidate' => [
                            'name' => 'Rudi Hartono',
                            'phone' => '081234567895',
                            'address' => 'Jl. Panglima Sudirman No. 45, Kec. Pesantren, Kota Kediri, Jawa Timur',
                            'resume' => 'Rudi_Hartono_CV.pdf',
                            'criteria_values' => [5, 4, 5, 3, 4]
                        ]
                    ],
                    [
                        'user' => ['name' => 'Siti Zulaikha', 'email' => 'siti.zulaikha@example.com', 'password' => bcrypt('password'), 'role' => 'user'],
                        'candidate' => [
                            'name' => 'Siti Zulaikha',
                            'phone' => '081234567896',
                            'address' => 'Perum Graha Permata Blok D-8, Kec. Babat, Kabupaten Lamongan, Jawa Timur',
                            'resume' => 'Siti_Zulaikha_CV.pdf',
                            'criteria_values' => [4, 5, 4, 4, 3]
                        ]
                    ],
                    [
                        'user' => ['name' => 'Budi Santoso', 'email' => 'budi.santoso@example.com', 'password' => bcrypt('password'), 'role' => 'user'],
                        'candidate' => [
                            'name' => 'Budi Santoso',
                            'phone' => '081234567897',
                            'address' => 'Jl. Hayam Wuruk No. 32, Kec. Jombang, Kabupaten Jombang, Jawa Timur',
                            'resume' => 'Budi_Santoso_CV.pdf',
                            'criteria_values' => [3, 4, 5, 3, 4]
                        ]
                    ],
                    [
                        'user' => ['name' => 'Eka Yuliana', 'email' => 'eka.yuliana@example.com', 'password' => bcrypt('password'), 'role' => 'user'],
                        'candidate' => [
                            'name' => 'Eka Yuliana',
                            'phone' => '081234567898',
                            'address' => 'Ds. Ngadilangkung RT 02/RW 04, Kec. Sukorame, Kabupaten Kediri, Jawa Timur',
                            'resume' => 'Eka_Yuliana_CV.pdf',
                            'criteria_values' => [5, 4, 4, 4, 3]
                        ]
                    ],
                    [
                        'user' => ['name' => 'Aditya Pratama', 'email' => 'aditya.pratama@example.com', 'password' => bcrypt('password'), 'role' => 'user'],
                        'candidate' => [
                            'name' => 'Aditya Pratama',
                            'phone' => '081234567899',
                            'address' => 'Jl. Veteran No. 12, Kec. Karangbinangun, Kabupaten Lamongan, Jawa Timur',
                            'resume' => 'Aditya_Pratama_CV.pdf',
                            'criteria_values' => [5, 5, 5, 5, 4]
                        ]
                    ],
                    [
                        'user' => ['name' => 'Dinda Safira', 'email' => 'dinda.safira@example.com', 'password' => bcrypt('password'), 'role' => 'user'],
                        'candidate' => [
                            'name' => 'Dinda Safira',
                            'phone' => '081234567810',
                            'address' => 'Perum Griya Indah Permai Blok A-5, Kec. Peterongan, Kabupaten Jombang, Jawa Timur',
                            'resume' => 'Dinda_Safira_CV.pdf',
                            'criteria_values' => [4, 3, 4, 3, 3]
                        ]
                    ],
                    [
                        'user' => ['name' => 'Tio Pratama', 'email' => 'tio.pratama@example.com', 'password' => bcrypt('password'), 'role' => 'user'],
                        'candidate' => [
                            'name' => 'Tio Pratama',
                            'phone' => '081234567811',
                            'address' => 'Jl. Trunojoyo No. 56, Kec. Mojoroto, Kota Kediri, Jawa Timur',
                            'resume' => 'Tio_Pratama_CV.pdf',
                            'criteria_values' => [5, 5, 4, 5, 4]
                        ]
                    ],
                    [
                        'user' => ['name' => 'Yuni Astuti', 'email' => 'yuni.astuti@example.com', 'password' => bcrypt('password'), 'role' => 'user'],
                        'candidate' => [
                            'name' => 'Yuni Astuti',
                            'phone' => '081234567812',
                            'address' => 'Ds. Banjarwati RT 01/RW 03, Kec. Tikung, Kabupaten Lamongan, Jawa Timur',
                            'resume' => 'Yuni_Astuti_CV.pdf',
                            'criteria_values' => [4, 4, 5, 4, 3]
                        ]
                    ],
                    [
                        'user' => ['name' => 'Adi Pratama', 'email' => 'adi.pratama@example.com', 'password' => bcrypt('password'), 'role' => 'user'],
                        'candidate' => [
                            'name' => 'Adi Pratama',
                            'phone' => '081234567813',
                            'address' => 'Jl. Pahlawan No. 23, Kec. Diwek, Kabupaten Jombang, Jawa Timur',
                            'resume' => 'Adi_Pratama_CV.pdf',
                            'criteria_values' => [5, 4, 4, 3, 4]
                        ]
                    ],
                    [
                        'user' => ['name' => 'Lina Kurniasih', 'email' => 'lina.kurniasih@example.com', 'password' => bcrypt('password'), 'role' => 'user'],
                        'candidate' => [
                            'name' => 'Lina Kurniasih',
                            'phone' => '081234567814',
                            'address' => 'Perum Pesona Asri Blok E-7, Kec. Ngadiluwih, Kabupaten Kediri, Jawa Timur',
                            'resume' => 'Lina_Kurniasih_CV.pdf',
                            'criteria_values' => [4, 5, 4, 4, 3]
                        ]
                    ],
                    [
                        'user' => ['name' => 'Reza Aulia', 'email' => 'reza.aulia@example.com', 'password' => bcrypt('password'), 'role' => 'user'],
                        'candidate' => [
                            'name' => 'Reza Aulia',
                            'phone' => '081234567815',
                            'address' => 'Jl. Ahmad Yani No. 89, Kec. Glagah, Kabupaten Lamongan, Jawa Timur',
                            'resume' => 'Reza_Aulia_CV.pdf',
                            'criteria_values' => [5, 4, 5, 4, 4]
                        ]
                    ],
                    [
                        'user' => ['name' => 'Tika Dwi', 'email' => 'tika.dwi@example.com', 'password' => bcrypt('password'), 'role' => 'user'],
                        'candidate' => [
                            'name' => 'Tika Dwi',
                            'phone' => '081234567816',
                            'address' => 'Ds. Cukir RT 04/RW 02, Kec. Diwek, Kabupaten Jombang, Jawa Timur',
                            'resume' => 'Tika_Dwi_CV.pdf',
                            'criteria_values' => [4, 4, 3, 4, 3]
                        ]
                    ],
                    [
                        'user' => ['name' => 'Rizky Arifin', 'email' => 'rizky.arifin@example.com', 'password' => bcrypt('password'), 'role' => 'user'],
                        'candidate' => [
                            'name' => 'Rizky Arifin',
                            'phone' => '081234567817',
                            'address' => 'Jl. Dr. Sutomo No. 34, Kec. Pesantren, Kota Kediri, Jawa Timur',
                            'resume' => 'Rizky_Arifin_CV.pdf',
                            'criteria_values' => [3, 4, 4, 3, 3]
                        ]
                    ],
                    [
                        'user' => ['name' => 'Fina Nabila', 'email' => 'fina.nabila@example.com', 'password' => bcrypt('password'), 'role' => 'user'],
                        'candidate' => [
                            'name' => 'Fina Nabila',
                            'phone' => '081234567818',
                            'address' => 'Perum Taman Lamongan Indah Blok F-9, Kec. Lamongan, Kabupaten Lamongan, Jawa Timur',
                            'resume' => 'Fina_Nabila_CV.pdf',
                            'criteria_values' => [5, 4, 4, 4, 3]
                        ]
                    ],
                    [
                        'user' => ['name' => 'Irfan Hidayat', 'email' => 'irfan.hidayat@example.com', 'password' => bcrypt('password'), 'role' => 'user'],
                        'candidate' => [
                            'name' => 'Irfan Hidayat',
                            'phone' => '081234567819',
                            'address' => 'Jl. Gajah Mada No. 67, Kec. Jombang, Kabupaten Jombang, Jawa Timur',
                            'resume' => 'Irfan_Hidayat_CV.pdf',
                            'criteria_values' => [4, 5, 4, 3, 4]
                        ]
                    ],
                    [
                        'user' => ['name' => 'Desi Wulandari', 'email' => 'desi.wulandari@example.com', 'password' => bcrypt('password'), 'role' => 'user'],
                        'candidate' => [
                            'name' => 'Desi Wulandari',
                            'phone' => '081234567820',
                            'address' => 'Ds. Puhsarang RT 05/RW 01, Kec. Semen, Kabupaten Kediri, Jawa Timur',
                            'resume' => 'Desi_Wulandari_CV.pdf',
                            'criteria_values' => [5, 4, 5, 4, 3]
                        ]
                    ],
                    [
                        'user' => ['name' => 'Gita Anggraeni', 'email' => 'gita.anggraeni@example.com', 'password' => bcrypt('password'), 'role' => 'user'],
                        'candidate' => [
                            'name' => 'Gita Anggraeni',
                            'phone' => '081234567821',
                            'address' => 'Jl. Basuki Rahmat No. 11, Kec. Sukodadi, Kabupaten Lamongan, Jawa Timur',
                            'resume' => 'Gita_Anggraeni_CV.pdf',
                            'criteria_values' => [4, 4, 4, 3, 3]
                        ]
                    ],
                    [
                        'user' => ['name' => 'Risma Nurul', 'email' => 'risma.nurul@example.com', 'password' => bcrypt('password'), 'role' => 'user'],
                        'candidate' => [
                            'name' => 'Risma Nurul',
                            'phone' => '081234567822',
                            'address' => 'Perum Taman Jombang Asri Blok B-3, Kec. Jombang, Kabupaten Jombang, Jawa Timur',
                            'resume' => 'Risma_Nurul_CV.pdf',
                            'criteria_values' => [5, 5, 4, 4, 4]
                        ]
                    ],
                    [
                        'user' => ['name' => 'Firdaus Putra', 'email' => 'firdaus.putra@example.com', 'password' => bcrypt('password'), 'role' => 'user'],
                        'candidate' => [
                            'name' => 'Firdaus Putra',
                            'phone' => '081234567823',
                            'address' => 'Jl. Patimura No. 45, Kec. Gampengrejo, Kabupaten Kediri, Jawa Timur',
                            'resume' => 'Firdaus_Putra_CV.pdf',
                            'criteria_values' => [4, 4, 5, 3, 4]
                        ]
                    ],
                    [
                        'user' => ['name' => 'Andi Setiawan', 'email' => 'andi.setiawan@example.com', 'password' => bcrypt('password'), 'role' => 'user'],
                        'candidate' => [
                            'name' => 'Andi Setiawan',
                            'phone' => '081234567824',
                            'address' => 'Ds. Made RT 02/RW 06, Kec. Kembangbahu, Kabupaten Lamongan, Jawa Timur',
                            'resume' => 'Andi_Setiawan_CV.pdf',
                            'criteria_values' => [5, 5, 5, 4, 4]
                        ]
                    ]
                ]
            ],
            [
                'vacancy_id' => 2,
                'position' => 'Digital Marketing',
                'candidates' => [
                    [
                        'user' => ['name' => 'Faisal Nugroho', 'email' => 'faisal.nugroho@example.com', 'password' => bcrypt('password'), 'role' => 'user'],
                        'candidate' => [
                            'name' => 'Faisal Nugroho',
                            'phone' => '081234567825',
                            'address' => 'Jl. KH. Abdul Hamid No. 32, Kec. Sumobito, Kabupaten Jombang, Jawa Timur',
                            'resume' => 'Faisal_Nugroho_CV.pdf',
                            'criteria_values' => [4, 5, 4, 3, 4]
                        ]
                    ],
                    [
                        'user' => ['name' => 'Rina Aulia', 'email' => 'rina.aulia@example.com', 'password' => bcrypt('password'), 'role' => 'user'],
                        'candidate' => [
                            'name' => 'Rina Aulia',
                            'phone' => '081234567826',
                            'address' => 'Perum Graha Kediri Permai Blok G-4, Kec. Kota Kediri, Kabupaten Kediri, Jawa Timur',
                            'resume' => 'Rina_Aulia_CV.pdf',
                            'criteria_values' => [5, 4, 5, 4, 3]
                        ]
                    ],
                    [
                        'user' => ['name' => 'Agus Santoso', 'email' => 'agus.santoso@example.com', 'password' => bcrypt('password'), 'role' => 'user'],
                        'candidate' => [
                            'name' => 'Agus Santoso',
                            'phone' => '081234567827',
                            'address' => 'Jl. Raya Paciran No. 56, Kec. Paciran, Kabupaten Lamongan, Jawa Timur',
                            'resume' => 'Agus_Santoso_CV.pdf',
                            'criteria_values' => [4, 5, 4, 3, 4]
                        ]
                    ],
                    [
                        'user' => ['name' => 'Taufik Rahman', 'email' => 'taufik.rahman@example.com', 'password' => bcrypt('password'), 'role' => 'user'],
                        'candidate' => [
                            'name' => 'Taufik Rahman',
                            'phone' => '081234567828',
                            'address' => 'Ds. Peterongan RT 03/RW 02, Kec. Peterongan, Kabupaten Jombang, Jawa Timur',
                            'resume' => 'Taufik_Rahman_CV.pdf',
                            'criteria_values' => [5, 5, 4, 4, 4]
                        ]
                    ],
                    [
                        'user' => ['name' => 'Dedi Wahyudi', 'email' => 'dedi.wahyudi@example.com', 'password' => bcrypt('password'), 'role' => 'user'],
                        'candidate' => [
                            'name' => 'Dedi Wahyudi',
                            'phone' => '081234567829',
                            'address' => 'Jl. Brawijaya No. 78, Kec. Banyakan, Kabupaten Kediri, Jawa Timur',
                            'resume' => 'Dedi_Wahyudi_CV.pdf',
                            'criteria_values' => [4, 4, 5, 3, 4]
                        ]
                    ],
                    [
                        'user' => ['name' => 'Yudha Putra', 'email' => 'yudha.putra@example.com', 'password' => bcrypt('password'), 'role' => 'user'],
                        'candidate' => [
                            'name' => 'Yudha Putra',
                            'phone' => '081234567830',
                            'address' => 'Perum Taman Lamongan Sejahtera Blok H-6, Kec. Lamongan, Kabupaten Lamongan, Jawa Timur',
                            'resume' => 'Yudha_Putra_CV.pdf',
                            'criteria_values' => [5, 4, 4, 4, 3]
                        ]
                    ],
                    [
                        'user' => ['name' => 'Bimo Aulia', 'email' => 'bimo.aulia@example.com', 'password' => bcrypt('password'), 'role' => 'user'],
                        'candidate' => [
                            'name' => 'Bimo Aulia',
                            'phone' => '081234567831',
                            'address' => 'Jl. Pahlawan No. 90, Kec. Mojoagung, Kabupaten Jombang, Jawa Timur',
                            'resume' => 'Bimo_Aulia_CV.pdf',
                            'criteria_values' => [4, 5, 4, 3, 4]
                        ]
                    ],
                    [
                        'user' => ['name' => 'Rizal Prasetyo', 'email' => 'rizal.prasetyo@example.com', 'password' => bcrypt('password'), 'role' => 'user'],
                        'candidate' => [
                            'name' => 'Rizal Prasetyo',
                            'phone' => '081234567832',
                            'address' => 'Ds. Puhsarang RT 01/RW 05, Kec. Semen, Kabupaten Kediri, Jawa Timur',
                            'resume' => 'Rizal_Prasetyo_CV.pdf',
                            'criteria_values' => [5, 5, 5, 4, 4]
                        ]
                    ],
                    [
                        'user' => ['name' => 'Herman Sulaiman', 'email' => 'herman.sulaiman@example.com', 'password' => bcrypt('password'), 'role' => 'user'],
                        'candidate' => [
                            'name' => 'Herman Sulaiman',
                            'phone' => '081234567833',
                            'address' => 'Jl. Raya Babat No. 34, Kec. Babat, Kabupaten Lamongan, Jawa Timur',
                            'resume' => 'Herman_Sulaiman_CV.pdf',
                            'criteria_values' => [4, 4, 4, 3, 4]
                        ]
                    ],
                    [
                        'user' => ['name' => 'Aditya Saputra', 'email' => 'aditya.saputra@example.com', 'password' => bcrypt('password'), 'role' => 'user'],
                        'candidate' => [
                            'name' => 'Aditya Saputra',
                            'phone' => '081234567834',
                            'address' => 'Perum Griya Jombang Elok Blok C-8, Kec. Jombang, Kabupaten Jombang, Jawa Timur',
                            'resume' => 'Aditya_Saputra_CV.pdf',
                            'criteria_values' => [5, 4, 5, 4, 3]
                        ]
                    ],
                    [
                        'user' => ['name' => 'Novita Sari', 'email' => 'novita.sari@example.com', 'password' => bcrypt('password'), 'role' => 'user'],
                        'candidate' => [
                            'name' => 'Novita Sari',
                            'phone' => '081234567835',
                            'address' => 'Jl. Dr. Soetomo No. 67, Kec. Gurah, Kabupaten Kediri, Jawa Timur',
                            'resume' => 'Novita_Sari_CV.pdf',
                            'criteria_values' => [4, 5, 4, 3, 4]
                        ]
                    ],
                    [
                        'user' => ['name' => 'Sella Putri', 'email' => 'sella.putri@example.com', 'password' => bcrypt('password'), 'role' => 'user'],
                        'candidate' => [
                            'name' => 'Sella Putri',
                            'phone' => '081234567836',
                            'address' => 'Ds. Karanggeneng RT 04/RW 03, Kec. Karanggeneng, Kabupaten Lamongan, Jawa Timur',
                            'resume' => 'Sella_Putri_CV.pdf',
                            'criteria_values' => [5, 4, 4, 4, 3]
                        ]
                    ],
                    [
                        'user' => ['name' => 'Rangga Fikri', 'email' => 'rangga.fikri@example.com', 'password' => bcrypt('password'), 'role' => 'user'],
                        'candidate' => [
                            'name' => 'Rangga Fikri',
                            'phone' => '081234567837',
                            'address' => 'Jl. Pemuda No. 12, Kec. Bandarkedungmulyo, Kabupaten Jombang, Jawa Timur',
                            'resume' => 'Rangga_Fikri_CV.pdf',
                            'criteria_values' => [4, 5, 4, 3, 4]
                        ]
                    ],
                    [
                        'user' => ['name' => 'Triana Sari', 'email' => 'triana.sari@example.com', 'password' => bcrypt('password'), 'role' => 'user'],
                        'candidate' => [
                            'name' => 'Triana Sari',
                            'phone' => '081234567838',
                            'address' => 'Perum Kediri Regency Blok F-2, Kec. Mojoroto, Kota Kediri, Jawa Timur',
                            'resume' => 'Triana_Sari_CV.pdf',
                            'criteria_values' => [5, 5, 5, 4, 4]
                        ]
                    ],
                    [
                        'user' => ['name' => 'Mona Oktaviani', 'email' => 'mona.oktaviani@example.com', 'password' => bcrypt('password'), 'role' => 'user'],
                        'candidate' => [
                            'name' => 'Mona Oktaviani',
                            'phone' => '081234567839',
                            'address' => 'Jl. Raya Deket No. 23, Kec. Deket, Kabupaten Lamongan, Jawa Timur',
                            'resume' => 'Mona_Oktaviani_CV.pdf',
                            'criteria_values' => [4, 4, 4, 3, 3]
                        ]
                    ],
                    [
                        'user' => ['name' => 'Rafi Maulana', 'email' => 'rafi.maulana@example.com', 'password' => bcrypt('password'), 'role' => 'user'],
                        'candidate' => [
                            'name' => 'Rafi Maulana',
                            'phone' => '081234567840',
                            'address' => 'Ds. Mojotengah RT 02/RW 04, Kec. Bareng, Kabupaten Jombang, Jawa Timur',
                            'resume' => 'Rafi_Maulana_CV.pdf',
                            'criteria_values' => [5, 4, 5, 4, 3]
                        ]
                    ],
                    [
                        'user' => ['name' => 'Putri Ananda', 'email' => 'putri.ananda@example.com', 'password' => bcrypt('password'), 'role' => 'user'],
                        'candidate' => [
                            'name' => 'Putri Ananda',
                            'phone' => '081234567841',
                            'address' => 'Jl. Soekarno Hatta No. 45, Kec. Ngasem, Kabupaten Kediri, Jawa Timur',
                            'resume' => 'Putri_Ananda_CV.pdf',
                            'criteria_values' => [4, 5, 4, 3, 4]
                        ]
                    ],
                    [
                        'user' => ['name' => 'Alvin Saputra', 'email' => 'alvin.saputra@example.com', 'password' => bcrypt('password'), 'role' => 'user'],
                        'candidate' => [
                            'name' => 'Alvin Saputra',
                            'phone' => '081234567842',
                            'address' => 'Perum Taman Lamongan Asri Blok E-7, Kec. Lamongan, Kabupaten Lamongan, Jawa Timur',
                            'resume' => 'Alvin_Saputra_CV.pdf',
                            'criteria_values' => [5, 4, 4, 4, 3]
                        ]
                    ],
                    [
                        'user' => ['name' => 'Citra Wijaya', 'email' => 'citra.wijaya@example.com', 'password' => bcrypt('password'), 'role' => 'user'],
                        'candidate' => [
                            'name' => 'Citra Wijaya',
                            'phone' => '081234567843',
                            'address' => 'Jl. KH. Hasyim Asyari No. 56, Kec. Ngoro, Kabupaten Jombang, Jawa Timur',
                            'resume' => 'Citra_Wijaya_CV.pdf',
                            'criteria_values' => [4, 5, 4, 3, 4]
                        ]
                    ],
                    [
                        'user' => ['name' => 'Dwi Maulana', 'email' => 'dwi.maulana@example.com', 'password' => bcrypt('password'), 'role' => 'user'],
                        'candidate' => [
                            'name' => 'Dwi Maulana',
                            'phone' => '081234567844',
                            'address' => 'Ds. Puncu RT 03/RW 01, Kec. Puncu, Kabupaten Kediri, Jawa Timur',
                            'resume' => 'Dwi_Maulana_CV.pdf',
                            'criteria_values' => [5, 5, 5, 4, 4]
                        ]
                    ],
                    [
                        'user' => ['name' => 'Lita Pertiwi', 'email' => 'lita.pertiwi@example.com', 'password' => bcrypt('password'), 'role' => 'user'],
                        'candidate' => [
                            'name' => 'Lita Pertiwi',
                            'phone' => '081234567845',
                            'address' => 'Jl. Raya Blimbing No. 78, Kec. Blimbing, Kabupaten Lamongan, Jawa Timur',
                            'resume' => 'Lita_Pertiwi_CV.pdf',
                            'criteria_values' => [4, 4, 4, 3, 3]
                        ]
                    ],
                    [
                        'user' => ['name' => 'Sari Kurniawati', 'email' => 'sari.kurniawati@example.com', 'password' => bcrypt('password'), 'role' => 'user'],
                        'candidate' => [
                            'name' => 'Sari Kurniawati',
                            'phone' => '081234567846',
                            'address' => 'Perum Jombang Permai Blok D-9, Kec. Jombang, Kabupaten Jombang, Jawa Timur',
                            'resume' => 'Sari_Kurniawati_CV.pdf',
                            'criteria_values' => [5, 4, 5, 4, 3]
                        ]
                    ],
                    [
                        'user' => ['name' => 'Dini Anggraini', 'email' => 'dini.anggraini@example.com', 'password' => bcrypt('password'), 'role' => 'user'],
                        'candidate' => [
                            'name' => 'Dini Anggraini',
                            'phone' => '081234567847',
                            'address' => 'Jl. Ahmad Dahlan No. 34, Kec. Plosoklaten, Kabupaten Kediri, Jawa Timur',
                            'resume' => 'Dini_Anggraini_CV.pdf',
                            'criteria_values' => [4, 5, 4, 3, 4]
                        ]
                    ],
                    [
                        'user' => ['name' => 'Anisa Arif', 'email' => 'anisa.arif@example.com', 'password' => bcrypt('password'), 'role' => 'user'],
                        'candidate' => [
                            'name' => 'Anisa Arif',
                            'phone' => '081234567848',
                            'address' => 'Ds. Sukorame RT 01/RW 02, Kec. Sukorame, Kabupaten Lamongan, Jawa Timur',
                            'resume' => 'Anisa_Arif_CV.pdf',
                            'criteria_values' => [5, 4, 4, 4, 3]
                        ]
                    ],
                    [
                        'user' => ['name' => 'Indriani Dewi', 'email' => 'indriani.dewi@example.com', 'password' => bcrypt('password'), 'role' => 'user'],
                        'candidate' => [
                            'name' => 'Indriani Dewi',
                            'phone' => '081234567849',
                            'address' => 'Jl. Raya Perak No. 12, Kec. Perak, Kabupaten Jombang, Jawa Timur',
                            'resume' => 'Indriani_Dewi_CV.pdf',
                            'criteria_values' => [4, 5, 4, 3, 4]
                        ]
                    ]
                ]
            ]
        ];

        foreach ($positions as $position) {
            foreach ($position['candidates'] as $candidateData) {
                // Buat user
                $user = User::create($candidateData['user']);

                // Buat candidate
                $candidate = Candidate::create([
                    'name' => $candidateData['candidate']['name'],
                    'email' => $candidateData['user']['email'],
                    'phone' => $candidateData['candidate']['phone'],
                    'address' => $candidateData['candidate']['address'],
                    'vacancy_id' => $position['vacancy_id'],
                    'user_id' => $user->id,
                    'resume' => $candidateData['candidate']['resume'],
                    'status' => 'registered'
                ]);

                // Buat nilai kriteria
                foreach ($candidateData['candidate']['criteria_values'] as $index => $value) {
                    CandidateCriteria::create([
                        'candidate_id' => $candidate->id,
                        'criteria_id' => $index + 1,
                        'value' => $value
                    ]);
                }
            }
        }
    }

    private function createAhpComparisons()
    {
        $comparisons = [
            ['criteria1_id' => 1, 'criteria2_id' => 2, 'value' => 3.0000],
            ['criteria1_id' => 1, 'criteria2_id' => 3, 'value' => 4.0000],
            ['criteria1_id' => 1, 'criteria2_id' => 4, 'value' => 5.0000],
            ['criteria1_id' => 1, 'criteria2_id' => 5, 'value' => 9.0000],
            ['criteria1_id' => 2, 'criteria2_id' => 3, 'value' => 3.0000],
            ['criteria1_id' => 2, 'criteria2_id' => 4, 'value' => 5.0000],
            ['criteria1_id' => 2, 'criteria2_id' => 5, 'value' => 7.0000],
            ['criteria1_id' => 3, 'criteria2_id' => 4, 'value' => 3.0000],
            ['criteria1_id' => 3, 'criteria2_id' => 5, 'value' => 5.0000],
            ['criteria1_id' => 4, 'criteria2_id' => 5, 'value' => 3.0000],
        ];

        foreach ($comparisons as $comp) {
            // Pastikan criteria1_id < criteria2_id
            $criteria1Id = min($comp['criteria1_id'], $comp['criteria2_id']);
            $criteria2Id = max($comp['criteria1_id'], $comp['criteria2_id']);
            $value = ($comp['criteria1_id'] < $comp['criteria2_id'])
                ? $comp['value']
                : 1 / $comp['value'];

            AhpPairwiseComparison::updateOrCreate(
                ['criteria1_id' => $criteria1Id, 'criteria2_id' => $criteria2Id],
                ['value' => $value]
            );

            // Tambahkan kebalikan jika bukan diagonal
            if ($criteria1Id != $criteria2Id) {
                AhpPairwiseComparison::updateOrCreate(
                    ['criteria1_id' => $criteria2Id, 'criteria2_id' => $criteria1Id],
                    ['value' => 1 / $value]
                );
            }
        }
    }

    private function createIdealProfileValues()
    {
        $vacancies = Vacancy::all();
        $criterias = Criteria::all();

        foreach ($vacancies as $vacancy) {
            foreach ($criterias as $criteria) {
                IdealProfileValue::create([
                    'vacancy_id'  => $vacancy->id,
                    'criteria_id' => $criteria->id,
                    'value' => 5 // Nilai ideal maksimum
                ]);
            }
        }
    }
}
