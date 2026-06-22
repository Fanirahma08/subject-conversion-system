<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            ['code' => 'IC220C', 'name' => 'Pendidikan Kewarganegaraan / Kewarganegaraan', 'sks' => 2, 'semester' => 2],
            ['code' => 'IC120C', 'name' => 'Pendidikan Pancasila / Pancasila', 'sks' => 2, 'semester' => 1],
            ['code' => 'IC210C', 'name' => 'Technoprenuership', 'sks' => 2, 'semester' => 6],
            ['code' => 'IC310C', 'name' => 'Manusia dan Teknologi', 'sks' => 2, 'semester' => 4],
            ['code' => 'IC320C', 'name' => 'Bahasa Indonesia', 'sks' => 2, 'semester' => 1],
            ['code' => 'IC420C', 'name' => 'Pendidikan Agama', 'sks' => 2, 'semester' => 1],
            ['code' => 'IS1101', 'name' => 'Kalkulus', 'sks' => 3, 'semester' => 1],
            ['code' => 'IS11112', 'name' => 'Matematika Diskrit', 'sks' => 3, 'semester' => 2],
            ['code' => 'IS1112', 'name' => 'Dasar Sistem Informasi', 'sks' => 2, 'semester' => 2],
            ['code' => 'IS11312', 'name' => 'Bahasa Inggris 2', 'sks' => 2, 'semester' => 2],
            ['code' => 'IS11332', 'name' => 'Kecakapan Antar Personal', 'sks' => 2, 'semester' => 2],
            ['code' => 'IS1201', 'name' => 'Basis Data 1', 'sks' => 2, 'semester' => 3],
            ['code' => 'IS1202', 'name' => 'Praktikum Basis Data 1', 'sks' => 1, 'semester' => 1],
            ['code' => 'IS1203', 'name' => 'Dasar Pemrograman', 'sks' => 2, 'semester' => 3],
            ['code' => 'IS1204', 'name' => 'Praktikum Dasar Pemrograman', 'sks' => 1, 'semester' => 1],
            ['code' => 'IS1205', 'name' => 'Aplikasi Perkantoran', 'sks' => 2, 'semester' => 1],
            ['code' => 'IS1206', 'name' => 'Algoritma dan Struktur Data', 'sks' => 3, 'semester' => 3],
            ['code' => 'IS1312', 'name' => 'Arsitektur dan Organisasi Komputer', 'sks' => 2, 'semester' => 2],
            ['code' => 'IS1411', 'name' => 'Dasar Infrastruktur Teknologi Informasi', 'sks' => 2, 'semester' => 1],
            ['code' => 'IS1502', 'name' => 'Bahasa Inggris 1', 'sks' => 2, 'semester' => 1],
            ['code' => 'IS1522', 'name' => 'Pengantar Akuntansi', 'sks' => 2, 'semester' => 2],
            ['code' => 'IS1612', 'name' => 'Pengantar Bisnis dan Manajemen', 'sks' => 2, 'semester' => 2],
            ['code' => 'IS1742', 'name' => 'Pemrograman Berorientasi Objek', 'sks' => 2, 'semester' => 2],
            ['code' => 'IS1743', 'name' => 'Praktikum Pemrograman Berorientasi Objek', 'sks' => 1, 'semester' => 2],
            ['code' => 'IS2101', 'name' => 'Pemrograman Web 1', 'sks' => 2, 'semester' => 3],
            ['code' => 'IS2102', 'name' => 'Praktikum Pemrograman Web 1', 'sks' => 1, 'semester' => 3],
            ['code' => 'IS2103', 'name' => 'Etika Profesi IT', 'sks' => 2, 'semester' => 1],
            ['code' => 'IS21114', 'name' => 'Statistika dan Probabilitas', 'sks' => 3, 'semester' => 4],
            ['code' => 'IS2113', 'name' => 'Sistem Informasi Manajemen', 'sks' => 2, 'semester' => 3],
            ['code' => 'IS2133', 'name' => 'Proses Bisnis', 'sks' => 2, 'semester' => 3],
            ['code' => 'IS2201', 'name' => 'Aljabar Linier', 'sks' => 3, 'semester' => 3],
            ['code' => 'IS2234', 'name' => 'Basis Data 2', 'sks' => 2, 'semester' => 4],
            ['code' => 'IS2235', 'name' => 'Praktikum Basis Data 2', 'sks' => 1, 'semester' => 4],
            ['code' => 'IS2324', 'name' => 'Sistem Operasi', 'sks' => 2, 'semester' => 3],
            ['code' => 'IS2334', 'name' => 'Arsitektur SI/TI Perusahaan', 'sks' => 3, 'semester' => 4],
            ['code' => 'IS2503', 'name' => 'Perbankan', 'sks' => 2, 'semester' => 3],
            ['code' => 'IS2534', 'name' => 'Analisis dan Perancangan Sistem Informasi', 'sks' => 3, 'semester' => 5],
            ['code' => 'IS2744', 'name' => 'Pemrograman WEB 2', 'sks' => 2, 'semester' => 4],
            ['code' => 'IS2745', 'name' => 'Praktikum Pemrograman WEB 2', 'sks' => 1, 'semester' => 4],
            ['code' => 'IS2754', 'name' => 'Pengantar Pemrograman Mobile', 'sks' => 2, 'semester' => 4],
            ['code' => 'IS3108', 'name' => 'Jaringan dan Komunikasi Data', 'sks' => 2, 'semester' => 7],
            ['code' => 'IS31436', 'name' => 'Enterprise Information System', 'sks' => 3, 'semester' => 6],
            ['code' => 'IS3215', 'name' => 'Pengantar Audit Sistem Informasi', 'sks' => 2, 'semester' => 5],
            ['code' => 'IS3225', 'name' => 'Pemrograman Database', 'sks' => 2, 'semester' => 5],
            ['code' => 'IS3227', 'name' => 'Praktikum Pemrograman Database', 'sks' => 1, 'semester' => 5],
            ['code' => 'IS3235', 'name' => 'Pemrograman Mobile', 'sks' => 3, 'semester' => 5],
            ['code' => 'IS3245', 'name' => 'Perencanaan Strategis Sistem Informasi', 'sks' => 3, 'semester' => 5],
            ['code' => 'IS3302', 'name' => 'Metode Penelitian', 'sks' => 3, 'semester' => 5],
            ['code' => 'IS3313', 'name' => 'Multimedia', 'sks' => 3, 'semester' => 5],
            ['code' => 'IS3321', 'name' => 'Rekayasa Perangkat Lunak', 'sks' => 3, 'semester' => 5],
            ['code' => 'IS3436', 'name' => 'Manajemen Proyek SI', 'sks' => 3, 'semester' => 6],
            ['code' => 'IS3626', 'name' => 'Audit Sistem Informasi', 'sks' => 3, 'semester' => 6],
            ['code' => 'IS41018', 'name' => 'Tugas Akhir', 'sks' => 6, 'semester' => 8],
            ['code' => 'IS4403', 'name' => 'Seminar Proposal', 'sks' => 2, 'semester' => 7],
            ['code' => 'IS4405', 'name' => 'Proyek Penelitian', 'sks' => 2, 'semester' => 7],
            ['code' => 'IS4417', 'name' => 'Manajemen Resiko SI', 'sks' => 3, 'semester' => 7],
            ['code' => 'IS4648', 'name' => 'Tata Kelola Sistem Informasi', 'sks' => 3, 'semester' => 8],
            ['code' => 'IS4717', 'name' => 'komputer dan Masyarakat', 'sks' => 2, 'semester' => 7],
            ['code' => 'MP2314', 'name' => 'Manajemen Kualitas SI/TI', 'sks' => 3, 'semester' => 6],
            ['code' => 'MP4447', 'name' => 'Perencanaan Keberlangsungan Bisnis', 'sks' => 3, 'semester' => 7],
            ['code' => 'MP4717', 'name' => 'Sistem Informasi Geografis', 'sks' => 3, 'semester' => 7],
            ['code' => 'MP4817', 'name' => 'Sistem Informasi Akuntansi', 'sks' => 3, 'semester' => 7],
            ['code' => 'MP4827', 'name' => 'Sistem Informasi Perbankan', 'sks' => 3, 'semester' => 7],
            ['code' => 'MPIS31026', 'name' => 'e-Business', 'sks' => 3, 'semester' => 6],
            ['code' => 'MPIS31236', 'name' => 'Data Mining', 'sks' => 3, 'semester' => 6],
            ['code' => 'MPIS31237', 'name' => 'Decission Support System', 'sks' => 3, 'semester' => 6],
            ['code' => 'MPIS31437', 'name' => 'Enterprise Application Integration', 'sks' => 3, 'semester' => 6],
            ['code' => 'MPIS3846', 'name' => 'Keamanan Sistem Informasi', 'sks' => 3, 'semester' => 6],
            ['code' => 'MPIS3936', 'name' => 'Inovasi Sistem Informasi di Organisasi dan Masyarakat', 'sks' => 3, 'semester' => 6],
        ];

        foreach ($subjects as $subjectData) {
            Subject::updateOrCreate(
                ['code' => $subjectData['code']],
                [
                    'name' => $subjectData['name'],
                    'sks' => $subjectData['sks'],
                    'semester' => $subjectData['semester'],
                    'university_id' => null, // Internal
                    'prodi' => 'Sistem Informasi',
                ]
            );
        }
    }
}
