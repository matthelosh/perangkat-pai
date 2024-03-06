<script setup>
import { ref, computed, defineAsyncComponent } from 'vue';
import { Head, usePage, router } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';

import Layout from '@/layouts/DashboardLayout.vue'
const Cp = defineAsyncComponent(() => import('@/components/Dashboard/Perangkat/Kurikulum/Cp.vue'))
const Materi = defineAsyncComponent(() => import('@/components/Dashboard/Perangkat/Kurikulum/Materi.vue'))
const page = usePage();
const showStruktur = ref(false)
const showRasio = ref(false)


</script>

<template>
<Layout title="Kurikulum">
<div>
    <el-row>
        <el-col :span="24" class="mb-4">
            <el-card>
                <template #header>
                    <div class="toolbar flex items-center justify-between">
                        <h3 class="font-bold text-slate-800">Struktur Kurikulum</h3>
                        <div class="toolbar-items">
                            <el-button @click="showStruktur = !showStruktur">
                                Lihat
                                <Icon icon="mdi:magnify-add" />

                            </el-button>
                        </div>
                    </div>
                </template>
                <div class="card-body">
                    <object data="/docs/struktur-kurikulum-sd_mi.pdf" width="100%" :height="showStruktur ? '500' :'0'" class="transition-all" />
                </div>
            </el-card>
        </el-col>
    </el-row>
    <el-row>
        <el-col :span="24">
            <el-card>
                <template #header>
                    <h3>Capaian Pembelajaran PAI Fase A, B, C</h3>
                </template>
                <div class="card-body">
                    <div class="rasional">
                        <div class="title h-10 flex items-center justify-between">
                            <h3 class="font-semibold text-lg text-blue-800">Rasional Mata Pelajaran Pendidikan Agama Islam dan Budi Pekerti</h3>
                            <el-button type="primary" @click="showRasio = !showRasio">
                                <Icon icon="mdi:magnify-add"></Icon>
                            </el-button>
                        </div>
                        <div class="content transition-all text-justify p-3" v-if="showRasio">
                            <p class="my-2">Pendidikan Agama Islam dan Budi Pekerti secara bertahap dan holistik diarahkan untuk menyiapkan peserta didik agar mantap secara spiritual, berakhlak mulia, dan memiliki pemahaman akan dasar-dasar agama Islam serta cara penerapannya dalam kehidupan sehari-hari dalam wadah Negara Kesatuan Republik Indonesia. Pendidikan agama Islam dan Budi Pekerti secara umum harus mengarahkan peserta didik kepada (1) kecenderungan kepada kebaikan (al-ḥanīfiyyah), (2) sikap memperkenankan (al-samḥah), (3) akhlak mulia (makārim al-akhlāq), dan (4) kasih sayang untuk alam semesta (raḥmat li al-ālamīn). Dengan Pendidikan Agama Islam dan Budi Pekerti, dasar-dasar tersebut kemudian diterapkan oleh peserta didik dalam beriman dan bertakwa kepada Allah Swt., menjaga diri, peduli atas kemanusiaan dan lingkungan alam. Deskripsi dari penerapan ini akan tampak dalam beberapa elemen Pendidikan Agama Islam dan Budi Pekerti terutama dalam akhlak pribadi dan sosial, akidah, syari’at dan sejarah peradaban Islam</p>
                            <p class="my-2">Pendidikan Agama Islam dan Budi Pekerti bisa menjadi pedoman bagi peserta didik dalam menjaga diri dan menerapkan akhlak mulia setiap hari. Berbagai persoalan di masyarakat seperti krisis akhlak, radikalisme dan krisis lingkungan hidup dan lain-lain mempunyai jawaban dalam tradisi agama Islam. Dengan mempelajari dan menghayati Pendidikan Agama Islam dan Budi Pekerti, peserta didik mampu menghindari segala perubahan negatif yang terjadi di dunia sehingga tidak mengganggu perkembangan dirinya baik dalam hubungannya dengan Tuhan, diri sendiri, sesama warga negara, sesama manusia, maupun alam semesta.</p>
                            <p class="my-2">Dengan konteks Indonesia pada abad 21 yang semakin kompleks, pemahaman yang mendalam tentang agama sangat dibutuhkan, terutama dalam menghormati dan menghargai perbedaan. Pelajaran agama tidak hanya membahas hubungan manusia dengan Allah (ḥabl min Allāh), namun juga hubungan dengan diri sendiri, sesama warga negara, sesama manusia (ḥabl min al-nās) dan alam semesta. Untuk itu, dibutuhkan pendekatan yang beragam dalam proses belajar agama yang tidak hanya berupa ceramah, namun juga diskusi-interaktif, proses belajar yang bertumpu pada keingintahuan dan penemuan (inquiry and discovery learning), proses belajar yang berpihak pada anak (student-centered learning), proses belajar yang berbasis pada pemecahan masalah (problem based learning), pembelajaran berbasis proyek nyata dalam kehidupan (project based learning), dan proses belajar yang kolaboratif (collaborative learning). Berbagai pendekatan ini memberi ruang bagi tumbuhnya keterampilan yang berharga seperti budaya berpikir kritis, kecakapan berkomunikasi dan berkolaborasi, dan menjadi peserta didik yang kreatif</p>
                            <p class="my-2">Melalui muatan materi yang disajikannya dalam 5 (lima) elemen keilmuan Pendidikan Agama Islam dan Budi Pekerti antara lain al-Quran dan hadis, akidah, akhlak, fiqih, dan sejarah peradaban Islam, pelajaran agama Islam dapat berkontribusi dan menguatkan terbentuknya profil pelajar pancasila sebagai pelajar sepanjang hayat (min al-mahdi ila al-laḥdi) yang beriman dan bertakwa, serta berakhlak mulia, menyadari dirinya bagian dari penduduk dunia dengan berkepribadian dan punya kompetensi global, mandiri, kreatif, kritis, dan bergotong royong.</p>
                        </div>
                    </div>
                    <Cp :cps="page.props.cps" />
                </div>
            </el-card>
        </el-col>
    </el-row>
    <el-row class="py-4">
        <el-col :span="24">
            <Materi />
        </el-col>
    </el-row>
</div>
</Layout>
</template>