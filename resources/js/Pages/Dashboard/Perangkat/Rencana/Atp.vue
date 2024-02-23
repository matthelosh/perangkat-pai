<script setup>
import { ref, computed, defineAsyncComponent, onBeforeMount } from 'vue';
import{ usePage, Head, router } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue';
import { Doc, Text, Paragraph, Color,  Bold, Underline, Italic, BulletList, OrderedList, Strike, Highlight } from 'element-tiptap-vue3-fixed'
import Layout from '@/layouts/DashboardLayout.vue'

const page = usePage()

const loading = ref(false)
const atps = ref([])
const mine = ref(false)
const kembali = () => {
    router.get(route('rencana'))
}
const extensions = [
    Doc, Text, Paragraph,
    Color.configure({bubble: true}),
    Highlight.configure({bubble: true}),
    Strike.configure({bubble: true}),
    Bold.configure({bubble: true}),
    Underline.configure({bubble: true}),
    Italic.configure({bubble: true}),
    BulletList, OrderedList
]

const params = computed(() => route().params)
const onMineChanged = (e) => {
    // let params = route().params;
    
    router.get('/rencana/atp', {fase: params.value.fase, mine: mine.value}, { preserveState: true, only: ['elemens']}
    )
}


const cetak = async() => {
    let el = document.querySelector('.cetak')
    let cssLink = page.props.app_env == 'local' ? 'https://perpai.test:5173/resources/css/app.css' : '/build/assets/app.css';
    let html = `<html>
                <head>
                    <title>Alur Tujuan Pembelajaran</title>    
                    <link href="${cssLink}" rel="stylesheet" />
                </head>
                <body>
                    ${el.outerHTML}
                </body>
            </html>`

    let win = window.open("", "_blank", "width=800,height=700")
    win.document.write(html)
    setTimeout(() => {
        win.print()
        win.close()
    }, 1000)
}

const tps = computed(() => {
    let res = []
    if (atp.value.elemen_id) {
        page.props.elemens.forEach(el => {
            if (el.kode == atp.value.elemen_id) {
                res = el.tps.map(tp => tp.teks)
            }
        })
    }

    return res
})

// Form Atp
const showForm = ref(false)
const atp = ref({})
const tingkats = computed(() => {
    return params.value.fase == 'C' ? ['5','6'] : (params.value.fase == 'B' ? ['3','4']: ['1','2'])
})
const simpanAtp = async() => {
    // atps.value.push(atp.value)
    loading.value = true
    router.post(route('atp.store', {_query: { mine: mine.value}}), { data: atp.value}, {
        onSuccess: (page) => {
            loading.value = false
            atp.value = {}
            showForm.value = false
            ElNotification({title: 'Info', message: page.props.message})
        },
        onError: err => {
            console.log(err)
            ElNotification({title: 'Error', message: err})
        }
    })
}



onBeforeMount(() => {
    atps.value = page.props.atps
    // parseTpAtp()
})
</script>

<template>
<Layout title="Alur Tujuan Pembelajaran">
    <Head title="Alur Tujuan Pembelajaran" />

    <el-card class="relative">
        <template #header>
            <el-affix :offset="60" >
                <div class="flex items-center justify-between sticky top-12 z-40 bg-white py-2">

                    <h3>Form Alur Tujuan Pembelajaran</h3>
                    <div class="items flex items-center gap-2">
                        <el-button type="primary" @click="cetak">
                            <Icon icon="mdi:printer" />
                        </el-button>
                        <el-button circle type="danger" @click="kembali">
                            <Icon icon="mdi:close" />
                        </el-button>
                    </div>
                </div>
            </el-affix>
        </template>
        <div class="card-body cetak">
            <h1 class="text-lg text-blue-900 font-bold text-center mb-4">Alur Tujuan Pembelajaran</h1>
            <table class="uppercase">
                <tr>
                    <td>Mata pelajaran</td>
                    <td class="px-1">:</td>
                    <td>Pendidikan Agama Islam dan Budi Pekerti</td>
                </tr>
                <tr>
                    <td>Fase</td>
                    <td class="px-1">:</td>
                    <td>{{ params.fase }}</td>
                </tr>
                <tr>
                    <td>Satuan Pendidikan</td>
                    <td class="px-1">:</td>
                    <td>{{ page.props.sekolahs[0].nama }}</td>
                </tr>
            </table>

            <table class="w-full my-2">
                <thead>
                    <tr>
                        <th class="border border-black p-2" rowspan="2">Capaian Umum PAI</th>
                        <th class="border border-black p-2" :colspan="page.props.elemens.length">Capaian Per Elemen</th>
                    </tr>
                    <tr>
                        <th class="border border-black p-2" v-for="(elemen, e) in page.props.elemens" :key="e">{{ elemen.label }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border border-black p-2 text-justify align-top">{{ page.props.cp.teks }}</td>
                        <td class="border border-black p-2 text-justify align-top" v-for="(elemen, e) in page.props.elemens" :key="e">{{ elemen.deskripsi_cp }}</td>
                    </tr>
                </tbody>
            </table>
            <div class="w-full flex h-10 bg-slate-100 justify-between items-center px-2 print:hidden">
                <el-switch v-model="mine" active-text="Punya saya" inactive-text="Dari sistem" @change="onMineChanged"></el-switch>
                <div class="items flex items-center gap-2">
                    <el-button circle @click="showForm = true">
                        <Icon icon="mdi:plus" />
                    </el-button>
                </div>
            </div>
            <table class="my-2 w-full">
                <thead>
                    <tr class="uppercase">
                        <th class="border border-black p-2">Elemen</th>
                        <th class="border border-black p-2">Materi Ajar</th>
                        <th class="border border-black p-2">Tingkat</th>
                        <th class="border border-black p-2">Semester</th>
                        <th class="border border-black p-2">Alokasi Waktu</th>
                        <th class="border border-black p-2">Tujuan Pembelajaran</th>
                        <th class="border border-black p-2">Alur Konten (Lingkup Materi)</th>
                        <th class="border border-black p-2">Asesmen</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="(el,e) in page.props.elemens" :key="e">
                        <tr v-for="(atp,a) in el.atps" :key="atp.kode">
                            <td class="border border-black p-2" v-if="(e+a===e)" :rowspan="(e+a===e) ? el.atps.length : '0'">{{ el.label }}</td>
                            <td class="border border-black px-2">{{ atp.materi }}</td>
                            <td class="border border-black px-2 text-center">{{ atp.tingkat }}</td>
                            <td class="border border-black px-2 text-center">{{ atp.semester }}</td>
                            <td class="border border-black px-2 text-center">{{ atp.aw }} JP</td>
                            <td class="border border-black px-2">{{ atp.tps }}</td>
                            <td class="border border-black px-2">
                                <span v-html="atp.konten" class="konten"></span>
                            </td>
                            <td class="border border-black px-2">
                                <span v-html="atp.asesmen" class="asesmen"></span>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
        
    </el-card>

    <el-dialog v-model="showForm" draggable>
        <template #header>
            <h3>Tambahkan Tujuan Pembelajaran {{atp.elemen_id}}</h3>
        </template>
        <div class="dialog-body">
            <el-form v-model="atp" label-position="top">
                <el-row :gutter=20>
                    <el-col :span="12">
                        <el-form-item label="Elemen">
                            <el-select v-model="atp.elemen_id" placeholder="Pilih Elemen">
                                <el-option v-for="(el, e) in page.props.elemens" :key="el.kode" :value="el.kode">{{ el.label }}</el-option>
                            </el-select>
                        </el-form-item>
                    </el-col>
                    <el-col :span="6">
                        <el-form-item label="Tingkat">
                            <el-select v-model="atp.tingkat" placeholder="Pilih Tingkat">
                                <el-option v-for="(tingkat, t) in tingkats" :key="tingkat" :value="tingkat">{{ tingkat}}</el-option>
                            </el-select>
                        </el-form-item>
                    </el-col>
                    <el-col :span="6">
                        <el-form-item label="Semester">
                            <el-select v-model="atp.semester" placeholder="Pilih Semester">
                                <el-option v-for="(sem, t) in ['1','2']" :key="t" :value="sem">{{ sem}}</el-option>
                            </el-select>
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row v-if="tps.length > 0">
                    <el-col :span="24">
                        <el-form-item label="Tujuan Pembelajaran">
                            <el-select v-model="atp.tps" placeholder="Bisa pilih lebih dari satu" multiple>
                                <el-option v-for="(tp,t) in tps" :key="t" :value="tp">{{ tp }}</el-option>
                            </el-select>
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row :gutter="20">
                    <el-col :span="19">
                        <el-form-item label="Materi Ajar">
                            <el-select v-model="atp.materi" placeholder="Pilih Materi/Bab">
                                <el-option v-for="(materi, m) in page.props.babs" :key="materi.id" :value="`${materi.bab}. ${materi.label}`">{{ materi.bab }}. {{ materi.label }}</el-option>
                            </el-select>
                        </el-form-item>
                    </el-col>
                    <el-col :span="5">
                        <el-form-item label="AW/JP">
                            <el-input type="number" placeholder="AW" v-model="atp.aw" />
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row v-if="tps.length > 0">
                    <el-col :span="24">
                        <el-form-item label="Konten Materi">
                            <!-- <el-input type="textarea" v-model="atp.konten" placeholder="Isi dengan konten materi/Sub materi" /> -->
                            <el-tiptap v-model:content="atp.konten" :extensions="extensions" />
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row v-if="tps.length > 0">
                    <el-col :span="24">
                        <el-form-item label="Asesmen">
                            <!-- <el-input type="textarea" v-model="atp.asesmen" placeholder="Asesmen" /> -->
                            <el-tiptap v-model:content="atp.asesmen" :extensions="extensions" />
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row justify="center">
                    <el-button type="primary" @click="simpanAtp" :loading="loading">Simpan</el-button>
                </el-row>
            </el-form>
        </div>
    </el-dialog>
</Layout>
</template>

<style>
.el-tiptap-popper {
    z-index: 99999!important;
}
.konten ul, .asesmen ul {
    list-style-type: decimal;
}
</style>