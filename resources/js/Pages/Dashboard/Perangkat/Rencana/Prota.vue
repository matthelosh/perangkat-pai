<script setup>
import { ref, computed , defineAsyncComponent} from 'vue';
import { Head, usePage, Link, router } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
import { cssLink } from '@/helpers/printHelper'

import DashLayout from '@/layouts/DashboardLayout.vue'
const Kop = defineAsyncComponent(() => import('@/components/Umum/Kop.vue'))
const Ttd = defineAsyncComponent(() => import('@/components/Umum/Ttd.vue'))

const page = usePage()
const sekolah = computed(() => page.props.sekolahs[0])
const tanggal = computed(() => {
    return '20'+page.props.tapel.kode.substring(0,2)+'-07-10'
})

const params = computed(() => route().params)
const cetak = async() => {
    const el = document.querySelector(".cetak")
    let html = `
        <!doctype html>
        <html>
            <head>
                <title>Program Tahunan Kelas ${page.props.rombel.label} - ${sekolah.value.nama}</title>    
                <link href="${cssLink(page.props.app_env)}" rel="stylesheet" />
            </head>
            <body>${el.outerHTML}</body>
        </html>
    `

    let win = window.open("","_blank", "width=800, height=600")
    win.document.write(html)

    setTimeout(() => {
        win.print()
        win.close()
    }, 500)
}

const kode = (teks, index) => {
    let split = teks.split("-")
    return params.value.mine == '1' ? [split[1],split[2],split[3]].join("")+"-"+(index+1) : split.join("")+"-"+(index+1)
}
</script>

<template>
<DashLayout title="Program Tahunan">
    <Head title="Program Tahunan" />

    <el-row class="w-full" v-if="page.props.atps.length > 0">
        <el-col :span="24">
            <el-card :body-style="{background: '#efefef',}">
                <template #header>
                    <div class="toolbar flex items-center justify-between">
                        <h3 class="font-bold text-blue-700 uppercase flex items-center gap-2">
                            <Link :href="appRoute('rencana')" class="text-slate-700 font-bold text-2xl">
                                <Icon icon="mdi:arrow-left" />
                            </Link>
                            Prota {{ sekolah.nama }} | {{ page.props.tapel.label }}
                        </h3>
                        <div class="items flex items-center gap-2">
                            <el-button type="primary" @click="cetak">
                                <Icon icon="mdi:printer" />
                            </el-button>
                        </div>
                    </div>
                </template>
                <div class="card-body cetak font-serif w-[33m] print:w-full mx-auto bg-white p-4 shadow print:shadow-none rounded">
                    <Kop />
                    <div class="title uppercase text-center my-6 font-bold">
                        <h3 class="text-xl">PROGRAM TAHUNAN (PROTA)</h3>
                        <h3 class="text-xl">TAHUN PELAJARAN {{ page.props.tapel.label }}</h3>
                    </div>
                    <div class="meta w-[80%] print:w-[90%] mx-auto"> 
                        <table>
                            <tr>
                                <td>Satuan Pendidikan</td>
                                <td>:</td>
                                <td>{{ sekolah.nama }}</td>
                            </tr>
                            <tr>
                                <td>Kelas</td>
                                <td>:</td>
                                <td>{{ page.props.rombel.label }}</td>
                            </tr>
                            <tr>
                                <td>Mata Pelajaran</td>
                                <td>:</td>
                                <td>PAI</td>
                            </tr>
                        </table>
                    </div>

                    <div class="content my-8">
                        <table class="w-[80%] print:w-[90%] mx-auto" >
                            <thead>
                                <tr>
                                    <th class="border border-black p-2 bg-slate-200">No</th>
                                    <th class="border border-black p-2 bg-slate-200">ATP</th>
                                    <th class="border border-black p-2 bg-slate-200">Tujuan Pembelajaran</th>
                                    <th class="border border-black p-2 bg-slate-200">BAB</th>
                                    <th class="border border-black p-2 bg-slate-200">Materi Pokok</th>
                                    <th class="border border-black p-2 bg-slate-200">Alokasi Waktu</th>
                                    <th class="border border-black p-2 bg-slate-200">Semester</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="(atp, a) in page.props.atps" :key="atp.kode">
                                    <tr>
                                        <td class="border border-black p-2 text-center">{{ a+1 }}</td>
                                        <td class="border border-black p-2">{{ kode(atp.kode, a) }}</td>
                                        <td class="border border-black p-2">{{ atp.tps }}</td>
                                        <td class="border border-black p-2">{{ atp.materi }}</td>
                                        <td class="border border-black p-2"><span v-html="atp.konten" /></td>
                                        <td class="border border-black p-2 text-center">{{ atp.aw }} JP</td>
                                        <td class="border border-black p-2 text-center">{{ atp.semester }}</td>
                                    </tr>
                                </template>
                                <tr>
                                    <td class="border border-black p-2 text-right font-bold bg-slate-200" colspan="5">JUMLAH</td>
                                    <td class="border border-black p-2 text-center font-black" >{{ page.props.atps.reduce((a,c) => a+parseInt(c.aw), 0) }} JP</td>
                                    <td class="border border-black p-2 bg-slate-200" ></td>

                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <Ttd :tanggal="tanggal" />
                </div>
            </el-card>
        </el-col>
    </el-row>
    <el-row v-else>
        <el-alert type="error">
            <template #title>
                <h3 class="text-xl flex items-center gap-2">
                    <Icon icon="mdi:alert-circle-outline" class="text-4xl" />
                    Data Prota Kelas {{ page.props.rombel.label }} Belum Ada
                </h3>
            </template>
            Susun dulu Alur Tujuan Pembelajaran (ATP) <Link class="text-blue-400 font-bold uppercase" :href="appRoute('atp.index', {_query: {fase: params.fase, mine: '1'}})" >Di Sini</Link>
        </el-alert>
    </el-row>
</DashLayout>
</template>