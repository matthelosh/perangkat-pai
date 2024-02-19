<?php

namespace Database\Seeders;

use App\Models\Elemen;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ElemenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $elemens = [
            [
                'fase' => 'A',
                'cp_id' => '1',
                'kode' => 'qh-a',
                'label' => 'Al-Quran Hadits',
                'deskripsi_cp' => 'Pendidikan Agama Islam dan Budi Pekerti menekankan kemampuan mengenal huruf hijaiah dan harakatnya, huruf hijaiah bersambung, dan kemampuan membaca surah-surah pendek AlQuran dengan baik.'
            ],
            [
                'fase' => 'A',
                'cp_id' => '1',
                'kode' => 'aq-a',
                'label' => 'Akidah',
                'deskripsi_cp' => 'Peserta didik mengenal rukun iman kepada Allah melalui nama-namanya yang agung (asmaulhusna) dan mengenal para malaikat dan tugas yang diembannya'
            ],
            [
                'fase' => 'A',
                'cp_id' => '1',
                'kode' => 'ak-a',
                'label' => 'Akhlak',
                'deskripsi_cp' => 'Peserta didik terbiasa mempraktikkan nilai-nilai baik dalam kehidupan sehari-hari dalam ungkapan-ungkapan  positif baik untuk dirinya maupun sesama manusia, terutama orang tua dan guru. mulai mengenal norma yang ada di lingkungan sekitarnya. Peserta didik juga terbiasa percaya diri mengungkapkan pendapat pribadinya dan belajar menghargai pendapat yang berbeda. Peserta didik juga terbiasa melaksanakan tugas kelompok serta saling mendukung satu sama lain.'
            ],
            [
                'fase' => 'A',
                'cp_id' => '1',
                'kode' => 'fq-a',
                'label' => 'Fikih',
                'deskripsi_cp' => 'Peserta didik mampu mengenal rukun Islam dan kalimah syahadatain, menerapkan tata cara bersuci, salat fardu, azan, ikamah, zikir dan berdoa setelah salat.'
            ],
            [
                'fase' => 'A',
                'cp_id' => '1',
                'kode' => 'sp-a',
                'label' => 'Sejarah Peradaban Islam',
                'deskripsi_cp' => 'Peserta didik mampu menceritakan secara sederhana kisah beberapa nabi yang wajib diimani.'
            ],
            [
                'fase' => 'B',
                'cp_id' => '2',
                'kode' => 'qh-b',
                'label' => 'Al-Quran Hadits',
                'deskripsi_cp' => 'Peserta didik mampu membaca surah-surah pendek atau ayat Al-Quran dan menjelaskan pesan pokoknya dengan baik. Peserta didik mengenal hadis tentang kewajiban salat dan menjaga hubungan baik dengan sesama serta mampu menerapkan dalam kehidupan sehari-hari.'
            ],
            [
                'fase' => 'B',
                'cp_id' => '2',
                'kode' => 'aq-b',
                'label' => 'Akidah',
                'deskripsi_cp' => 'Peserta didik memahami sifat-sifat bagi Allah, beberapa asmaulhusna, mengenal kitab-kitab Allah, para nabi dan rasul Allah yang wajib diimani.'
            ],
            [
                'fase' => 'B',
                'cp_id' => '2',
                'kode' => 'ak-b',
                'label' => 'Akhlak',
                'deskripsi_cp' => 'Pada elemen akhlak, peserta didik menghormati dan berbakti kepada orang tua dan guru, dan menyampaikan ungkapan-ungkapan positif (kalimah ṫayyibah) dalam keseharian. Peserta didik memahami arti keragaman sebagai sebuah ketentuan dari Allah Swt. (sunnatullāh). Peserta didik mengenal norma yang ada di lingkungan sekitarnya dan lingkungan yang lebih luas, percaya diri mengungkapkan pendapat pribadi, memahami pentingnya musyawarah untuk mencapai kesepakatan dan pentingnya persatuan.'
            ],
            [
                'fase' => 'B',
                'cp_id' => '2',
                'kode' => 'fq-b',
                'label' => 'Fikih',
                'deskripsi_cp' => 'Pada elemen fikih, peserta didik dapat melaksanakan puasa, salat jumat dan salat sunah dengan baik, memahami konsep balig dan tanggung jawab yang menyertainya (taklīf).'
            ],
            [
                'fase' => 'B',
                'cp_id' => '2',
                'kode' => 'sp-b',
                'label' => 'Sejarah Peradaban Islam',
                'deskripsi_cp' => 'Dalam pemahamannya tentang sejarah, peserta didik mampu menceritakan kondisi Arab pra Islam, masa kanak-kanak dan remaja Nabi Muhammad saw. hingga diutus menjadi rasul, berdakwah, hijrah dan membangun Kota Madinah.'
            ],
            [
                'fase' => 'C',
                'cp_id' => '3',
                'kode' => 'qh-c',
                'label' => 'Al-Quran Hadits',
                'deskripsi_cp' => 'Peserta didik mampu membaca, menghafal, menulis, dan memahami pesan pokok surah-surah pendek dan ayat Al-Quran tentang keragaman dengan baik dan benar.'
            ],
            [
                'fase' => 'C',
                'cp_id' => '3',
                'kode' => 'aq-c',
                'label' => 'Akidah',
                'deskripsi_cp' => 'eserta didik dapat mengenal Allah melalui asmaulhusna, memahami keniscayaan peritiwa hari akhir, qadā dan qadr.'
            ],
            [
                'fase' => 'C',
                'cp_id' => '3',
                'kode' => 'ak-3',
                'label' => 'Akhlak',
                'deskripsi_cp' => 'Peserta didik mengenal dialog antar agama dan kepercayaan dan menyadari peluang dan tantangan yang bisa muncul dari keragaman di Indonesia. Peserta didik memahami arti ideologi secara sederhana dan pandangan hidup dan memahami pentingnya menjaga kesatuan atas keberagaman. Peserta didik juga memahami pentingnya introspeksi diri untuk menjadi pribadi yang lebih baik setiap harinya. Peserta didik memahami pentingnya pendapat yang logis, menerima perbedaan pendapat, dan menemukan titik kesamaan (kalimah sawā) untuk mewujudkan persatuan dan kerukunan. Peserta didik memahami peran manusia sebagai khalifah Allah di bumi untuk menebarkan kasih sayang dan tidak membuat kerusakan di muka bumi.'
            ],
            [
                'fase' => 'C',
                'cp_id' => '3',
                'kode' => 'fq-c',
                'label' => 'Fikih',
                'deskripsi_cp' => 'ada elemen fikih, peserta didik mampu memahami zakat, infak, sedekah dan hadiah, memahami ketentuan haji, halal dan haram serta mempraktikkan puasa sunnah.'
            ],
            [
                'fase' => 'C',
                'cp_id' => '3',
                'kode' => 'sp-c',
                'label' => 'Sejarah Peradaban Islam',
                'deskripsi_cp' => 'Pada elemen sejarah, peserta didik menghayati ibrah dari kisah Nabi Muhammad saw. di masa separuh akhir kerasulannya serta kisah al-khulafā al-rāsyidūn.'
            ],
        ];

        foreach($elemens as $elemen)
        {
            Elemen::create([
                'fase' => $elemen['fase'],
                'cp_id' => $elemen['cp_id'],
                'kode' => $elemen['kode'],
                'label' => $elemen['label'],
                'deskripsi_cp' => $elemen['deskripsi_cp']
            ]);
        }
    }
}
