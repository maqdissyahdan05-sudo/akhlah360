<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AkhlaqValue;
use App\Models\AssessmentIndicator;

class AkhlaqValuesSeeder extends Seeder
{
    public function run(): void
    {
        $values = [
            [
                'name' => 'Amanah', // 8 questions
                'desc' => 'Memegang teguh kepercayaan yang diberikan.',
                'indicators' => [
                    'Memenuhi janji dan komitmen pekerjaan secara konsisten.',
                    'Bertanggung jawab atas tugas, keputusan, dan tindakan yang dilakukan.',
                    'Menjaga kerahasiaan data dan informasi perusahaan dengan ketat.',
                    'Menunjukkan integritas dan kejujuran dalam setiap tindakan.',
                    'Tidak menyalahgunakan wewenang untuk kepentingan pribadi.',
                    'Menyampaikan laporan kerja secara jujur dan transparan.',
                    'Berani mengakui kesalahan dan bertanggung jawab untuk memperbaikinya.',
                    'Konsisten antara perkataan dan perbuatan di lingkungan kerja.'
                ]
            ],
            [
                'name' => 'Kompeten', // 9 questions
                'desc' => 'Terus belajar dan mengembangkan kapabilitas.',
                'indicators' => [
                    'Meningkatkan kompetensi diri untuk merespons tantangan yang selalu berubah.',
                    'Membantu orang lain belajar dan mengembangkan kemampuan mereka.',
                    'Menyelesaikan tugas dengan kualitas dan hasil terbaik.',
                    'Aktif mencari *feedback* (umpan balik) untuk perbaikan kinerja.',
                    'Selalu memperbarui pengetahuan terkait bidang pekerjaannya.',
                    'Mampu menyelesaikan pekerjaan tepat waktu sesuai standar.',
                    'Berbagi ilmu dan pengalaman secara sukarela kepada rekan kerja.',
                    'Mampu mengambil keputusan yang tepat berdasarkan data dan analisis.',
                    'Menunjukkan kemahiran dalam menggunakan perangkat/teknologi kerja.'
                ]
            ],
            [
                'name' => 'Harmonis', // 8 questions
                'desc' => 'Saling peduli dan menghargai perbedaan.',
                'indicators' => [
                    'Menghargai setiap orang apapun latar belakangnya.',
                    'Suka menolong orang lain yang sedang mengalami kesulitan.',
                    'Membangun lingkungan kerja yang kondusif, aman, dan nyaman.',
                    'Menghindari perilaku diskriminatif dan *bullying* di tempat kerja.',
                    'Menjadi penengah yang baik ketika terjadi konflik dalam tim.',
                    'Menjaga komunikasi yang santun dengan semua level jabatan.',
                    'Menghargai perbedaan pendapat dalam setiap diskusi.',
                    'Menunjukkan rasa empati terhadap situasi rekan kerja.'
                ]
            ],
            [
                'name' => 'Loyal', // 8 questions
                'desc' => 'Berdedikasi dan mengutamakan kepentingan Bangsa dan Negara.',
                'indicators' => [
                    'Menjaga nama baik sesama karyawan, pimpinan, BUMN, dan Negara.',
                    'Rela berkorban waktu dan tenaga untuk mencapai target perusahaan.',
                    'Patuh kepada pimpinan sepanjang tidak bertentangan dengan hukum dan etika.',
                    'Mengutamakan kepentingan perusahaan di atas kepentingan pribadi/golongan.',
                    'Mendukung setiap kebijakan dan keputusan strategis perusahaan.',
                    'Menunjukkan rasa bangga menjadi bagian dari perusahaan.',
                    'Mampu menjaga reputasi perusahaan di ranah publik (termasuk media sosial).',
                    'Mendedikasikan kemampuan terbaik untuk kemajuan organisasi.'
                ]
            ],
            [
                'name' => 'Adaptif', // 9 questions
                'desc' => 'Terus berinovasi dan antusias dalam menggerakkan ataupun menghadapi perubahan.',
                'indicators' => [
                    'Cepat menyesuaikan diri untuk menjadi lebih baik dalam perubahan.',
                    'Terus-menerus melakukan perbaikan mengikuti perkembangan teknologi.',
                    'Bertindak proaktif dalam menghadapi masalah dan mencari solusi.',
                    'Mampu berpikir kreatif (out of the box) dalam bekerja.',
                    'Tidak mudah menyerah saat menghadapi tantangan atau hal baru.',
                    'Menginisiasi ide-ide baru yang berdampak positif pada efisiensi kerja.',
                    'Antusias dalam menyambut program transformasi perusahaan.',
                    'Terbuka terhadap cara kerja baru yang lebih efektif.',
                    'Mampu bekerja optimal dalam situasi yang tidak menentu (ambigu).'
                ]
            ],
            [
                'name' => 'Kolaboratif', // 8 questions
                'desc' => 'Membangun kerja sama yang sinergis.',
                'indicators' => [
                    'Memberi kesempatan kepada berbagai pihak untuk berkontribusi.',
                    'Terbuka dalam bekerja sama untuk menghasilkan nilai tambah.',
                    'Menggerakkan pemanfaatan berbagai sumber daya untuk tujuan bersama.',
                    'Menghilangkan sekat atau silo antar departemen/divisi.',
                    'Bersikap kooperatif saat bekerja dalam tim lintas fungsi.',
                    'Mengapresiasi kontribusi dan keberhasilan rekan kerja.',
                    'Aktif melibatkan anggota tim dalam pemecahan masalah (brainstorming).',
                    'Mampu berbagi peran dan tanggung jawab dengan adil dalam proyek.'
                ]
            ]
        ];

        foreach ($values as $v) {
            $value = AkhlaqValue::create([
                'value_name' => $v['name'],
                'description' => $v['desc'],
            ]);

            foreach ($v['indicators'] as $ind) {
                AssessmentIndicator::create([
                    'value_id' => $value->value_id,
                    'indicator_statement' => $ind,
                ]);
            }
        }
    }
}
