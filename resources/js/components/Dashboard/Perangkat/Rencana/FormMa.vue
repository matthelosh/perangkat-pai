<script setup>
import { ref, computed, onBeforeMount, defineAsyncComponent } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue'
import { Doc, Text, Paragraph, Color,  Bold, Underline, Italic, BulletList, OrderedList, Strike, Highlight, Table } from 'element-tiptap-vue3-fixed'

const Ttd = defineAsyncComponent(() => import('@/components/Umum/Ttd.vue'))
const extensions = [
    Doc, Text, Paragraph,
    Color.configure({bubble: true}),
    Highlight.configure({bubble: true}),
    Strike.configure({bubble: true}),
    Bold.configure({bubble: true}),
    Underline.configure({bubble: true}),
    Italic.configure({bubble: true}),
    BulletList, OrderedList, 
    Table.configure({resizable:true}),
    
]
const page = usePage()
const props = defineProps({atp: Object, selectedMa: Object})
const emit = defineEmits(['close'])
const loading = ref(false)
const edit = ref(false)
const info = ref(false)
const ma = ref({
    pendahuluan: `<table class="w-full" width="100%" border="1" style="border-collapse:collapse;">
                    <thead>
                        <tr>
                            <th class="border border-black align-top p-2">Kegiatan Pembelajaran</th>    
                            <th class="border border-black align-top">Alokasi Waktu</th>    
                        </tr>    
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border border-black align-top p-2"></td>    
                            <td class="border border-black align-top p-2">15 Menit</td>    
                        </tr>    
                    </tbody>
        </table>`,
    inti: `<table class="w-full" width="100%" border="1" style="border-collapse:collapse;">
                    <thead>
                        <tr>
                            <th class="border border-black align-top p-2">Kegiatan Pembelajaran</th>    
                            <th class="border border-black align-top">Alokasi Waktu</th>    
                        </tr>    
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border border-black align-top p-2"></td>    
                            <td class="border border-black align-top p-2">11    0 Menit</td>    
                        </tr>    
                    </tbody>
        </table>`,
    penutup: `<table class="w-full" width="100%" border="1" style="border-collapse:collapse;">
                    <thead>
                        <tr>
                            <th class="border border-black align-top p-2">Kegiatan Pembelajaran</th>    
                            <th class="border border-black align-top">Alokasi Waktu</th>    
                        </tr>    
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border border-black align-top p-2"></td>    
                            <td class="border border-black align-top p-2">15 Menit</td>    
                        </tr>    
                    </tbody>
        </table>`,
})

const simpan = async() => {
    // console.log(ma.value)
    let data = ma.value
    data.p5 = props.atp.p5
    data.tps = props.atp.tps
    await router.post(route('modulajar.store'), {data: data}, {
        onStart: () => loading.value = true,
        onSuccess: () => {
            router.reload()
            edit.value = false
        },
        onFinish: () => loading.value = false
    })
}

const cetak = () => {
    const el = document.querySelector('.cetak')
    let cssLink =
        page.props.app_env == "local"
            ? "https://perangkat.test:5173/resources/css/app.css"
            : "/build/assets/app.css";
    let html = `<html>
                <head>
                    <title>Madul Ajar</title>    
                    <link href="${cssLink}" rel="stylesheet" />
                    <style>
                        ul.langkah table {
                            border: 1px solid black;
                            border-collapse: collapse;
                            width: 100%;
                        }
                            ul.langkah table td,
                            ul.langkah table th {
                                border: 1px solid black;
                                padding: 10px;
                            }
                    </style>
                </head>
                <body>
                    ${el.outerHTML}
                </body>
            </html>`;

    let win = window.open("", "_blank", "width=800,height=700");
    win.document.write(html);
    setTimeout(() => {
        win.print();
        // win.close();
    }, 1000);
}

const tanggal = computed(() => {
    const date = new Date()
    return date.getFullYear()+'-'+(date.getMonth()+1)+'-'+date.getDate()
}
)

onBeforeMount(() => {
    ma.value.atp_id = props.atp.id
    if (props.selectedMa) {ma.value = props.selectedMa}
})
</script>

<template>
    <div>
    <el-card>
        <template #header>
            <div class="toolbar flex items-center justify-between">
                <h3 class="flex items-center gap-2">
                    <Icon icon="mdi:arrow-left" class="text-2xl text-teal-700 hover:cursor-pointer" @click="emit('close')" />
                    Form Modul Ajar Kelas {{ page.props.rombel.label }}
                </h3>
                <div class="items flex items-center gap-3">
                    <el-switch v-model="edit" :inactive-text="edit ? 'Edit' : 'Edit?'" />
                    <el-button :native-type="null"  circle v-if="!edit" @click="cetak">
                        <Icon icon="mdi:printer" />
                    </el-button>
                    <el-button :native-type="null"  circle class="ml-4" @click="info = !info">
                        <Icon icon="mdi:info" />
                    </el-button>
                </div>
            </div>
        </template>
        <div class="card-body font-serif cetak">
            <div class="title text-center uppercase font-bold mb-6">
                <h3 class="text-2xl">Modul Ajar</h3>
            </div>

            <div class="info-umum">
                <h2 class="text-xl uppercase font-bold mb-4">I. Informasi Umum</h2>

                <h3 class="uppercase font-bold">A. Identitas Modul</h3>
                <table>
                    <tr>
                        <td>Penyusun</td>
                        <td class="px-2">:</td>
                        <td>{{ page.props.user.userable.nama }}, {{ page.props.user.userable.gelar_belakang }}</td>
                    </tr>
                    <tr>
                        <td>Satuan Pendidikan</td>
                        <td class="px-2">:</td>
                        <td>{{ page.props.sekolahs[0].nama }}</td>
                    </tr>
                    <tr>
                        <td>Tahun Pelajaran</td>
                        <td class="px-2">:</td>
                        <td>{{ page.props.tapel.label}}</td>
                    </tr>
                    <tr>
                        <td>Mata Pelajaran</td>
                        <td class="px-2">:</td>
                        <td>PAI dan Budi Pekerti</td>
                    </tr>
                    <tr>
                        <td>Fase / Kelas</td>
                        <td class="px-2">:</td>
                        <td>{{ page.props.rombel.fase }} / {{ page.props.rombel.label }}</td>
                    </tr>
                    <tr>
                        <td>Materi</td>
                        <td class="px-2">:</td>
                        <td>{{ props.atp.materi }} | {{props.atp.konten}}</td>
                    </tr>
                    <tr>
                        <td>Alokasi Waktu</td>
                        <td class="px-2">:</td>
                        <td>{{ props.atp.aw }} JP x 35 Menit ({{ props.atp.aw / 4 }} Pertemuan)</td>
                    </tr>
                </table>

                <h3 class="uppercase font-bold mt-4">B. Profil Pelajar Pancasila</h3>
                <div >
                    <ul class="list-decimal pl-4 list-inside">
                        <li v-for="p5 in props.atp.p5.split(';')">
                            {{ p5 }}
                        </li>
                    </ul>
                </div>
                
            </div>

            <div class="inti">
                <h2 class="text-xl uppercase font-bold my-4">II. Komponen Inti</h2>
                <h3 class="uppercase font-bold mt-4">A. Tujuan Pembelajaran</h3>
                <ul class="pl-4 list-decimal">
                    <li v-for="(tp, t) in props.atp.tps.split(';')">{{ tp }}</li>
                </ul>

                <h3 class="uppercase font-bold mt-4">B. Pemahaman Bermakna</h3>
                <el-tiptap v-model:content="ma.pemahaman" :extensions="extensions" v-if="edit" />
                <div v-else v-html="ma.pemahaman" />
                <h3 class="uppercase font-bold mt-4">C. Media Pembelajaran</h3>
                <el-tiptap v-model:content="ma.media" :extensions="extensions" v-if="edit" />
                <div v-else v-html="ma.media" />
                <h3 class="uppercase font-bold mt-4">D. Pertanyaan pemantik</h3>
                <el-tiptap v-model:content="ma.pertanyaan" :extensions="extensions" v-if="edit" />
                <div v-else v-html="ma.pertanyaan" />
                <h3 class="uppercase font-bold mt-4">E. Kegiatan Pembelajaran</h3>
                <ul class="list-[lower-alpha] pl-8 langkah">
                    <li>
                        Kegiatan Pendahuluan
                        <el-tiptap v-model:content="ma.pendahuluan" :extensions="extensions" v-if="edit" />
                        <div v-else v-html="ma.pendahuluan" />
                    </li>
                    <li>
                        Kegiatan Inti
                        <el-tiptap v-model:content="ma.inti" :extensions="extensions" v-if="edit" />
                        <div v-else v-html="ma.inti" />
                    </li>
                    <li>
                        Kegiatan Penutup
                        <el-tiptap v-model:content="ma.penutup" :extensions="extensions" v-if="edit" />
                        <div v-else v-html="ma.penutup" />
                    </li>
                </ul>
                <h3 class="uppercase font-bold mt-4">F. Rencana Asesmen</h3>
                <el-tiptap v-model:content="ma.asesmen" :extensions="extensions" v-if="edit" />
                <div v-else v-html="ma.asesmen" />
                <h3 class="uppercase font-bold mt-4">G. Referensi</h3>
                <el-tiptap v-model:content="ma.referensi" :extensions="extensions" v-if="edit" />
                <div v-else v-html="ma.referensi" />
                <el-row class="w-full" align="middle" justify="center" v-if="edit">
                    <el-button :native-type="null"  type="primary" class="my-4 mx-auto uppercase" :loading="loading" @click="simpan">Simpan</el-button>
                </el-row>
            </div>

            <Ttd :tanggal="tanggal" v-if="!edit" />
        </div>
    </el-card>

    <!-- Drawer Info -->
    <el-drawer v-model="info" title="Konsep Modul Ajar">
        <article>
            <h3>Konsep</h3>
            <p>
                <ul class="list-disc pl-4">
                    <li>Modul ajar merupakan salah satu jenis perangkat ajar yang memuat rencana pelaksanaan pembelajaran, untuk membantu mengarahkan proses pembelajaran mencapai Capaian Pembelajaran (CP).</li>
                    <li>Jika satuan pendidikan menggunakan modul ajar yang disediakan pemerintah, maka modul ajar tersebut dapat dipadankan dengan RPP Plus, karena modul ajar tersebut memiliki komponen yang lebih lengkap dibanding RPP.</li>
                    <li>Jika satuan pendidikan mengembangkan modul ajar secara mandiri, maka modul ajar tersebut dapat dipadankan dengan <strong>RPP</strong>.</li>
                    <li>Satuan pendidikan dapat menggunakan berbagai perangkat ajar, termasuk modul ajar atau RPP, dengan kelengkapan komponen dan format yang beragam sesuai dengan karakteristik dan kebutuhan murid.</li>
                </ul>
            </p>
        </article>
        <article>
            <h3>Tujuan Pengembangan Modul Ajar</h3>
            <p class="mb-2">
                Pengembangan modul ajar bertujuan untuk menyediakan perangkat ajar yang dapat memandu guru melaksanakan pembelajaran.
            </p>
            <p class="mb-2">Dalam penggunaannya, guru memiliki kemerdekaan untuk:</p>
            <ul class="list-disc pl-4">
                <li>Memilih atau memodifikasi modul ajar yang sudah disediakan pemerintah untuk menyesuaikan dengan karakteristik murid, atau</li>
                <li>Menyusun sendiri modul ajar sesuai dengan karakteristik murid</li>
            </ul>
            <p class="mb-2">
                <ul class="list-decimal pl-4">
                    <li>
                        <strong>Esensial:</strong> pemahaman konsep dari setiap mata pelajaran melalui pengalaman belajar dan lintas disiplin.
                    </li>
                    <li><strong>Menarik, bermakna, dan menantang:</strong> menumbuhkan minat belajar dan melibatkan murid secara aktif dalam proses belajar; berhubungan dengan pengetahuan dan pengalaman yang dimiliki sebelumnya sehingga tidak terlalu kompleks, namun juga tidak terlalu mudah untuk tahap usianya.</li>
                    <li><strong>Relevan dan Kontekstual:</strong> berhubungan dengan pengetahuan dan pengalaman yang dimiliki sebelumnya, serta sesuai dengan konteks waktu dan lingkungan murid.</li>
                    <li><strong>Berkesinambungan:</strong> keterkaitan alur kegiatan pembelajaran sesuai dengan fase belajar murid.</li>
                </ul>
            </p>
        </article>
        <article>
            <h3>Komponen Modul Ajar</h3>
            <p>
                <ul class="list-disc pl-4">
                    <li>Modul ajar sekurang-kurangnya berisi tujuan pembelajaran, langkah pembelajaran (yang mencakup media pembelajaran yang akan digunakan), asesmen, serta informasi dan referensi belajar lainnya yang dapat membantu guru dalam melaksanakan pembelajaran.</li>
                    <li>Komponen modul ajar bisa ditambahkan sesuai dengan mata pelajaran dan kebutuhannya.</li>
                    <li>Guru di satuan pendidikan diberi kebebasan untuk mengembangkan komponen dalam modul ajar sesuai dengan konteks lingkungan dan kebutuhan belajar murid.</li>
                </ul>
            </p>
            <p>Uraian komponen inti modul ajar:</p>
            <el-collapse accordion>
                <el-collapse-item title="1. Tujuan Pembelajaran">
                    <ul class="list-disc pl-4">
                        <li>Tujuan pembelajaran harus mencerminkan hal-hal penting dari pembelajaran dan harus bisa diuji dengan berbagai bentuk asesmen sebagai bentuk dari unjuk pemahaman.</li>
                        <li>Tujuan Pembelajaran akan menentukan kegiatan belajar, sumber daya yang digunakan, kesesuaian dengan keberagaman murid, dan metode asesmen yang digunakan.</li>
                        <li>TUjuan pembelajaran pun bisa mencakup berbagai bentuk, mulai dari pengetahuan (fakta dan informasi), prosedural, pemahaman konseptualm pemikiran dan penalaran keterampilan, serta kolaborasi dan strategi komunikasi.</li>

                    </ul>
                </el-collapse-item>
                <el-collapse-item title="2. Kegiatan Pembelajaran">
                    <ul class="list-disc pl-4">
                        <li>Mencakup urutan kegiatan pembelajaran inti dalam bentuk langkah-langkah konkret, yan gdisertakan opsi/pembelajaran alternatif dan langkah untuk menyesuaikan dengan kebutuhan belajar murid.</li>
                        <li>Langkah kegiatan pembelajran ditulis secara berurutan sesuai dengan durasi waktu yang direncanakan, dalam tiga tahap, yaitu pendahuluan, inti, dan penutup berbasis metoda pembelajaran aktif.</li>
                    </ul>
                </el-collapse-item>
                <el-collapse-item title="3. Rencana Asesmen">
                    <ul class="list-disc pl-4">
                        <li>Rencana asesmen mencakup instrumen serta cara melakukan penilaian. Kriteria pencapaian harus ditentukan dengan jelas sesuai dengan tujuan pembelajaran yang ditetapkan.</li>
                        <li>Asesmen dapat berupa asesmen formatif maupun asesmen sumatif. Namun, kedua jenis asesmen ini tidak harus selalu digunakan dalam modul ajar, melainkan dapat disesuaikan tergantung pada cakupan tujuan pembelajaran dan kebutuhan murid.</li>
                        <li>Dalam merencanakan asesmen, guru juga perlu memahami salah satu prinsip asesmen dalam Kurikulum Merdeka adalah mendorong penggunaan berbagai bentuk asesmen, bukan hanya tes tertulis. Hal ini dilakukan agar pembelajaran bisa lebih terfokus pada kegiatan yang bermakna, serta informasi atau umpan balik dari asesmen tentang kemampuan murid juga menjadi lebih kaya dan bermanfaat dalam proses perancangan pembelajaran berikutnya.</li>
                    </ul>
                </el-collapse-item>
            </el-collapse>
        </article>
    </el-drawer>
    </div>
</template>

<style>
    ul.langkah table {
        width: 100%;
    }
    ul.langkah table td, th {
        border: 1px solid black;
        padding: 10px;
        /* border-collapse: collapse; */
    }
</style>