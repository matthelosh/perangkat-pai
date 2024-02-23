<?php

namespace Database\Seeders;

use App\Models\MateriAjar;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MateriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $materis = [
            [
                'tingkat' => '1',
                'semester' => '1',
                'bab' => 'Bab 1',
                'label' => 'Aku Cinta Al-Quran',
                'elemen' => 'Al-Quran Hadits'
            ],
            [
                'tingkat' => '1',
                'semester' => '1',
                'bab' => 'Bab 2',
                'label' => 'Mengenal Rukun Iman',
                'elemen' => 'Akidah'
            ],
            [
                'tingkat' => '1',
                'semester' => '1',
                'bab' => 'Bab 3',
                'label' => 'Aku Suka Membaca Basmalah dan Hamdalah',
                'elemen' => 'Akhlak'
            ],
            [
                'tingkat' => '1',
                'semester' => '1',
                'bab' => 'Bab 4',
                'label' => 'Mengenal Rukun Islam',
                'elemen' => 'Fikih'
            ],
            [
                'tingkat' => '1',
                'semester' => '1',
                'bab' => 'Bab 5',
                'label' => 'Nabi dan Rasul Panutanku',
                'elemen' => 'Sejarah Peradaban Islam'
            ],
            [
                'tingkat' => '1',
                'semester' => '2',
                'bab' => 'Bab 6',
                'label' => 'Al-Quran Pedoman Hidupku',
                'elemen' => 'Al-Quran Hadits'
            ],
            [
                'tingkat' => '1',
                'semester' => '2',
                'bab' => 'Bab 7',
                'label' => 'Kasih Sayang Terhadap Sesama',
                'elemen' => 'Akidah'
            ],
            [
                'tingkat' => '1',
                'semester' => '2',
                'bab' => 'Bab 8',
                'label' => 'Aku Suka Berterima Kasih dan Disiplin',
                'elemen' => 'Akhlak'
            ],
            [
                'tingkat' => '1',
                'semester' => '2',
                'bab' => 'Bab 9',
                'label' => 'Membiasakan Hidup Bersih',
                'elemen' => 'Fikih'
            ],
            [
                'tingkat' => '1',
                'semester' => '2',
                'bab' => 'Bab 10',
                'label' => 'Nabi Adam as. Manusia Pertama',
                'elemen' => 'Sejarah Peradaban Islam'
            ],
            [
                'tingkat' => '2',
                'semester' => '1',
                'bab' => 'Bab 1',
                'label' => 'Ayo Belajar Al-Quran',
                'elemen' => 'Al-Quran Hadits'
            ],
            [
                'tingkat' => '2',
                'semester' => '1',
                'bab' => 'Bab 2',
                'label' => 'Mari Mengenal Allah Swt.',
                'elemen' => 'Akidah'
            ],
            [
                'tingkat' => '2',
                'semester' => '1',
                'bab' => 'Bab 3',
                'label' => 'Ayo Berperilaku Terpuji',
                'elemen' => 'Akhlak'
            ],
            [
                'tingkat' => '2',
                'semester' => '1',
                'bab' => 'Bab 4',
                'label' => 'Alhamdulillah, Aku Bisa Salat',
                'elemen' => 'Fikih'
            ],
            [
                'tingkat' => '2',
                'semester' => '1',
                'bab' => 'Bab 5',
                'label' => 'Asyiknya Belajar Kisah Nabi Nuh as.',
                'elemen' => 'Sejarah Peradaban Islam'
            ],
            [
                'tingkat' => '2',
                'semester' => '2',
                'bab' => 'Bab 6',
                'label' => 'Senang Bisa Membaca Al-Quran',
                'elemen' => 'Al-Quran Hadits'
            ],
            [
                'tingkat' => '2',
                'semester' => '2',
                'bab' => 'Bab 7',
                'label' => 'Mari Mengenal Malaikat-Malaikat Allah',
                'elemen' => 'Akidah'
            ],
            [
                'tingkat' => '2',
                'semester' => '2',
                'bab' => 'Bab 8',
                'label' => 'Aku Senang Bisa Berakhlak Terpuji',
                'elemen' => 'Akhlak'
            ],
            [
                'tingkat' => '2',
                'semester' => '2',
                'bab' => 'Bab 9',
                'label' => 'Ayo Zikir dan Doa Setelah Salat',
                'elemen' => 'Fikih'
            ],
            [
                'tingkat' => '2',
                'semester' => '2',
                'bab' => 'Bab 10',
                'label' => 'Asyiknya Belajar Kisah Para Nabi',
                'elemen' => 'Sejarah Peradaban Islam'
            ],
            [
                'tingkat' => '3',
                'semester' => '1',
                'bab' => 'Bab 1',
                'label' => 'Asyiknya Belajar Surah Al-\'Alaq',
                'elemen' => 'Al-Quran Hadits'
            ],
            [
                'tingkat' => '3',
                'semester' => '1',
                'bab' => 'Bab 2',
                'label' => 'Ayo Mengenal Tuhan Kita.',
                'elemen' => 'Akidah'
            ],
            [
                'tingkat' => '3',
                'semester' => '1',
                'bab' => 'Bab 3',
                'label' => 'Perilaku Terpuji Adalah Kepribadianku',
                'elemen' => 'Akhlak'
            ],
            [
                'tingkat' => '3',
                'semester' => '1',
                'bab' => 'Bab 4',
                'label' => 'Aku Bangga Mampu Berpuasa',
                'elemen' => 'Fikih'
            ],
            [
                'tingkat' => '3',
                'semester' => '1',
                'bab' => 'Bab 5',
                'label' => 'Aku Suka Belajar Sejarah Islam',
                'elemen' => 'Sejarah Peradaban Islam'
            ],
            [
                'tingkat' => '3',
                'semester' => '2',
                'bab' => 'Bab 6',
                'label' => 'Senangnya Belajar Hadits',
                'elemen' => 'Al-Quran Hadits'
            ],
            [
                'tingkat' => '3',
                'semester' => '2',
                'bab' => 'Bab 7',
                'label' => 'Ayo Beriman Kepada Kitab-kitab Allah Swt.',
                'elemen' => 'Akidah'
            ],
            [
                'tingkat' => '3',
                'semester' => '2',
                'bab' => 'Bab 8',
                'label' => 'Aku Senang Berkalimah Thayyibah',
                'elemen' => 'Akhlak'
            ],
            [
                'tingkat' => '3',
                'semester' => '2',
                'bab' => 'Bab 9',
                'label' => 'Senangnya Salat Rawatib',
                'elemen' => 'Fikih'
            ],
            [
                'tingkat' => '3',
                'semester' => '2',
                'bab' => 'Bab 10',
                'label' => 'Nabi Muhammad saw. Rasulku',
                'elemen' => 'Sejarah Peradaban Islam'
            ],
            [
                'tingkat' => '4',
                'semester' => '1',
                'bab' => 'Bab 1',
                'label' => 'Mari Mengaji dan Mengkaji Q.S. Al-Hujurat(49):13 dan Hadis Tentang Keragaman',
                'elemen' => 'Al-Quran Hadits'
            ],
            [
                'tingkat' => '4',
                'semester' => '1',
                'bab' => 'Bab 2',
                'label' => 'Teladan Mulia Asmaul Husna',
                'elemen' => 'Akidah'
            ],
            [
                'tingkat' => '4',
                'semester' => '1',
                'bab' => 'Bab 3',
                'label' => 'Indahnya Saling Menghargai dalam Keragaman',
                'elemen' => 'Akhlak'
            ],
            [
                'tingkat' => '4',
                'semester' => '1',
                'bab' => 'Bab 4',
                'label' => 'Menyambut Usia Baligh',
                'elemen' => 'Fikih'
            ],
            [
                'tingkat' => '4',
                'semester' => '1',
                'bab' => 'Bab 5',
                'label' => 'Kisah Hijrah Nabi Muhammad saw. ke Madinah',
                'elemen' => 'Sejarah Peradaban Islam'
            ],
            [
                'tingkat' => '4',
                'semester' => '2',
                'bab' => 'Bab 6',
                'label' => 'Mari Mengaji dan Mengkaji Q.S. At-Tin dan Hadis tentang Silaturahmi',
                'elemen' => 'Al-Quran Hadits'
            ],
            [
                'tingkat' => '4',
                'semester' => '2',
                'bab' => 'Bab 7',
                'label' => 'Beriman Kepada Rasul-rasul Allah',
                'elemen' => 'Akidah'
            ],
            [
                'tingkat' => '4',
                'semester' => '2',
                'bab' => 'Bab 8',
                'label' => 'Aku Anak Saleh',
                'elemen' => 'Akhlak'
            ],
            [
                'tingkat' => '4',
                'semester' => '2',
                'bab' => 'Bab 9',
                'label' => 'Mengenal Salat Jumat, Duha dan Tahajud',
                'elemen' => 'Fikih'
            ],
            [
                'tingkat' => '4',
                'semester' => '2',
                'bab' => 'Bab 10',
                'label' => 'Kisah Nabi Muhammad saw. Membangun Kota Madinah',
                'elemen' => 'Sejarah Peradaban Islam'
            ],
            [
                'tingkat' => '5',
                'semester' => '1',
                'bab' => 'Bab 1',
                'label' => 'Menyayangi Anak Yatim',
                'elemen' => 'Al-Quran Hadits'
            ],
            [
                'tingkat' => '5',
                'semester' => '1',
                'bab' => 'Bab 2',
                'label' => 'Lebih Dekat dengan Nama-Nama Allah',
                'elemen' => 'Akidah'
            ],
            [
                'tingkat' => '5',
                'semester' => '1',
                'bab' => 'Bab 3',
                'label' => 'Aku Anak Saleh',
                'elemen' => 'Akhlak'
            ],
            [
                'tingkat' => '5',
                'semester' => '1',
                'bab' => 'Bab 4',
                'label' => 'Hidup Lapang dengan Berbagi',
                'elemen' => 'Fikih'
            ],
            [
                'tingkat' => '5',
                'semester' => '1',
                'bab' => 'Bab 5',
                'label' => 'Meneladani Perjuangan Rasulullah saw',
                'elemen' => 'Sejarah Peradaban Islam'
            ],
            [
                'tingkat' => '5',
                'semester' => '2',
                'bab' => 'Bab 6',
                'label' => 'Hidup Damai dalam Kebersamaan',
                'elemen' => 'Al-Quran Hadits'
            ],
            [
                'tingkat' => '5',
                'semester' => '2',
                'bab' => 'Bab 7',
                'label' => 'Ketika Kehidupan Telah Berhenti',
                'elemen' => 'Akidah'
            ],
            [
                'tingkat' => '5',
                'semester' => '2',
                'bab' => 'Bab 8',
                'label' => 'Senangnya Berteman',
                'elemen' => 'Akhlak'
            ],
            [
                'tingkat' => '5',
                'semester' => '2',
                'bab' => 'Bab 9',
                'label' => 'Ibadah Haji dan Kurban',
                'elemen' => 'Fikih'
            ],
            [
                'tingkat' => '5',
                'semester' => '2',
                'bab' => 'Bab 10',
                'label' => 'Keteladanan Khulafaurrasyidin',
                'elemen' => 'Sejarah Peradaban Islam'
            ],
            [
                'tingkat' => '6',
                'semester' => '1',
                'bab' => 'Bab 1',
                'label' => 'Belajar Al-Quran dan Hadis',
                'elemen' => 'Al-Quran Hadits'
            ],
            [
                'tingkat' => '6',
                'semester' => '1',
                'bab' => 'Bab 2',
                'label' => 'Allah Swt. Maha Segalanya',
                'elemen' => 'Akidah'
            ],
            [
                'tingkat' => '6',
                'semester' => '1',
                'bab' => 'Bab 3',
                'label' => 'Hidup Damai dengan Saling Memaafkan',
                'elemen' => 'Akhlak'
            ],
            [
                'tingkat' => '6',
                'semester' => '1',
                'bab' => 'Bab 4',
                'label' => 'Hukum Halal dan Haram',
                'elemen' => 'Fikih'
            ],
            [
                'tingkat' => '6',
                'semester' => '1',
                'bab' => 'Bab 5',
                'label' => 'Jasa Khulafaurrasyidin Untuk Dunia',
                'elemen' => 'Sejarah Peradaban Islam'
            ],
            [
                'tingkat' => '6',
                'semester' => '2',
                'bab' => 'Bab 6',
                'label' => 'Mengaji Al-Quran Surah Al-A\'la',
                'elemen' => 'Al-Quran Hadits'
            ],
            [
                'tingkat' => '6',
                'semester' => '2',
                'bab' => 'Bab 7',
                'label' => 'Indahnya Ketetapan Allah',
                'elemen' => 'Akidah'
            ],
            [
                'tingkat' => '6',
                'semester' => '2',
                'bab' => 'Bab 8',
                'label' => 'Peduli Lingkungan',
                'elemen' => 'Akhlak'
            ],
            [
                'tingkat' => '6',
                'semester' => '2',
                'bab' => 'Bab 9',
                'label' => 'MEngamalkan Puasa Sunah',
                'elemen' => 'Fikih'
            ],
            [
                'tingkat' => '6',
                'semester' => '2',
                'bab' => 'Bab 10',
                'label' => 'Meneladai Jasa Khalifah Usman bin Affan dan Ali bin Abi Thalib',
                'elemen' => 'Sejarah Peradaban Islam'
            ],
        ];

        foreach($materis as $materi) {
            MateriAjar::create([
                'tingkat' => $materi['tingkat'], 
                'semester' => $materi['semester'],
                'bab' => $materi['bab'],
                'label' => $materi['label'],
                'elemen' => $materi['elemen']
            ]);
        }
    }
}
