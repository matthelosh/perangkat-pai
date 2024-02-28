<script setup>
import { ref, computed, defineAsyncComponent } from 'vue'
import { usePage, Link } from '@inertiajs/vue3'
import DashLayout from '@/layouts/DashboardLayout.vue';
import { Icon } from '@iconify/vue'
import dayjs from 'dayjs'
import localeData from 'dayjs/plugin/localeData'
import 'dayjs/locale/id'
dayjs.extend(localeData)
dayjs.locale("id")
import { cssLink } from '@/helpers/printHelper'

const Kop = defineAsyncComponent(() => import('@/components/Umum/Kop.vue'))
const Ttd = defineAsyncComponent(() => import('@/components/Umum/Ttd.vue'))

const page = usePage()
const sekolah = computed(() => page.props.sekolahs[0])
const params = route().params;
const semester = ref('1')


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
</script>

<template>
<DashLayout title="Program Semester">
    <el-row>
        <el-col :span="24">
            <el-card :body-style="{background: '#efefef'}">
                <template #header>
                    <div class="flex items-center justify-between">
                        <div class="flex gap-2">
                            <Link :href="appRoute('rencana')">
                                <Icon icon="mdi:arrow-left" class="text-2xl text-green-800 font-bold" />
                            </Link>
                            <span class="text-blue-700 font-bold uppercase">Program Semester</span>
                        </div>
                        <div class="toolbar flex  w-[200px] gap-2">
                            <span>Semester</span>
                            <el-select v-model="semester" placeholder="Pilih Semester" class="2-[50px]">
                                <el-option v-for="sem in ['1','2']" :key="sem" :value="sem">Semester {{ sem }}</el-option>
                            </el-select>
                            <el-button text circle @click="cetak">
                                <Icon icon="mdi:printer" class="text-xl" />
                            </el-button>
                        </div>
                    </div>
                </template>
                <div class="card-body cetak font-serif w-full mx-auto bg-white p-4 shadow print:shadow-none rounded">
                    <Kop />
                    <div class="title uppercase text-center my-6 font-bold">
                        <h3 class="text-xl">PROGRAM Semester (PROSEM) {{ semester == '1' ? 'Ganjil' : 'Genap' }}</h3>
                        <h3 class="text-xl">TAHUN PELAJARAN {{ page.props.tapel.label }}</h3>
                    </div>
                    <div class="meta w-[90%] mx-auto"> 
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
                                <td>PAI dan Budi Pekerti</td>
                            </tr>
                            <tr>
                                <td>Hari</td>
                                <td>:</td>
                                <td>{{ page.props.rombel.jadwals[0].hari }}</td>
                            </tr>
                        </table>
                    </div>
                    <table class="w-[90%] mx-auto">
                        <thead>
                            <tr>
                                <th class="border border-black px-2">No</th>
                                <th class="border border-black px-2">Materi</th>
                                <th class="border border-black px-2">Tujuan Pembelajaran</th>
                                <th class="border border-black px-2">Alokasi Waktu</th>
                                <th class="border border-black px-2">Tanggal</th>
                            </tr>
                        </thead>
                    </table>
                    <Ttd />
                </div>
            </el-card>
        </el-col>
    </el-row>
</DashLayout>
</template>