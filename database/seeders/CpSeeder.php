<?php

namespace Database\Seeders;

use App\Models\Cp;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cps = [
            [
                'fase' => 'A',
                'teks' => 'Pada akhir Fase A, pada elemen Al-Qur’an-Hadis peserta didik dapat mengenal huruf hijaiah dan harakatnya, huruf hijaiah bersambung, dan mampu membaca surah-surah pendek Al-Qur’an dengan baik. Dalam elemen akidah, peserta didik mengenal rukun iman, iman kepada Allah melalui nama-namanya yang agung (asmaulhusna) dan mengenal para malaikat dan tugas yang  diembannya. Pada elemen akhlak, peserta didik terbiasa mempraktikkan nilai-nilai baik dalam kehidupan sehari-hari dalam ungkapan-ungkapan positif baik untuk dirinya maupun sesama manusia, terutama orang tua dan guru. Peserta didik juga memahami pentingnya tradisi memberi dalam ajaran agama Islam. Mereka mulai mengenal norma yang ada di lingkungan sekitarnya. Peserta didik juga terbiasa percaya diri mengungkapkan pendapat pribadinya dan belajar menghargai pendapat yang berbeda. Peserta didik juga terbiasa melaksanakan tugas kelompok serta memahami pentingnya mengenali kekurangan diri dan kelebihan temannya demi terwujudnya suasana saling mendukung satu sama lain. Dalam elemen fikih, peserta didik dapat mengenal rukun Islam dan kalimah syahadatain, menerapkan tata cara bersuci, salat fardu, azan, ikamah, zikir dan berdoa setelah salat. Dalam pemahamannya tentang sejarah, peserta didik mampu menceritakan secara sederhana kisah beberapa nabi yang wajib diimani.'
            ],
            [
                'fase' => 'B',
                'teks' => 'Fase B (Umumnya untuk kelas III dan IV SD/MI/Program Paket A)Pada akhir Fase B, pada elemen Al-Qur’an Hadis peserta didik mampu membaca surah-surah pendek atau ayat Al-Qur’an dan menjelaskan pesan pokoknya dengan baik. Peserta didik mengenal hadis tentang kewajiban salat dan menjaga hubungan baik dengan sesama serta mampu menerapkan dalam kehidupan sehari-hari. Pada elemen akidah peserta didik memahami sifat-sifat bagi Allah, beberapa asmaulhusna, mengenal kitab-kitab Allah, para nabi dan rasul Allah yang wajib diimani. Pada elemen akhlak, peserta didik menghormati dan berbakti kepada orang tua dan guru, dan menyampaikan ungkapan-ungkapan positif (kalimah ṫayyibah) dalam keseharian. Peserta didik memahami arti keragaman sebagai sebuah ketentuan dari Allah Swt. (sunnatullāh). Peserta didik mengenal norma yang ada di lingkungan sekitarnya dan lingkungan yang lebih luas, percaya diri mengungkapkan pendapat pribadi, memahami pentingnya musyawarah untuk mencapai kesepakatan dan pentingnya persatuan. Pada elemen fikih, peserta didik dapat melaksanakan puasa, salat jumat dan salat sunah dengan baik, memahami konsep balig dan tanggung jawab yang menyertainya (taklīf). Dalam pemahamannya tentang sejarah, peserta didik mampu menceritakan kondisi Arab pra Islam, masa kanak-kanak dan remaja Nabi Muhammad saw. hingga diutus menjadi rasul, berdakwah, hijrah dan membangun Kota Madinah'
            ],
            [
                'fase' => 'C',
                'teks' => 'Pada akhir Fase C, pada elemen Al-Qur’an Hadis peserta didik mampu membaca, menghafal, menulis, dan memahami pesan pokok surah-surah pendek dan ayat Al-Qur’an tentang keragaman dengan baik dan benar. Pada elemen akidah, peserta didik dapat mengenal Allah melalui asmaulhusna, memahami keniscayaan peritiwa hari akhir, qadāʾ dan qadr. Pada elemen akhlak, peserta didik mengenal dialog antar agama dan kepercayaan dan menyadari peluang dan tantangan yang bisa muncul dari keragaman di Indonesia. Peserta didik memahami arti ideologi secara sederhana dan pandangan hidup dan memahami pentingnya menjaga kesatuan atas keberagaman. Peserta didik juga memahami pentingnya introspeksi diri untuk menjadi pribadi yang lebih baik setiap harinya. Peserta didik memahami pentingnya '
            ],
        ];

        foreach($cps as $cp) {
            Cp::create([
                'fase' => $cp['fase'],
                'teks' => $cp['teks'],
            ]);
        }
    }
}
