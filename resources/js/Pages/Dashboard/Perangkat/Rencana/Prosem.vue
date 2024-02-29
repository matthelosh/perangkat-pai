<script setup>
import { ref, computed, defineAsyncComponent, onBeforeMount } from 'vue'
import { usePage, Link } from '@inertiajs/vue3'
import DashLayout from '@/layouts/DashboardLayout.vue';
import { Icon } from '@iconify/vue'
import dayjs from 'dayjs'
import localeData from 'dayjs/plugin/localeData'
import 'dayjs/locale/id'
dayjs.extend(localeData)
dayjs.locale("id")
import { cssLink } from '@/helpers/printHelper'
import { unefektif, allUnefektif, tes } from '@/helpers/ApeHelper';

const Kop = defineAsyncComponent(() => import('@/components/Umum/Kop.vue'))
const Ttd = defineAsyncComponent(() => import('@/components/Umum/Ttd.vue'))

const page = usePage()
const sekolah = computed(() => page.props.sekolahs[0])
const params = route().params;
const semester = ref('1')
const prosems = ref([])
const syahrs = computed(() => dayjs.months())
const pickerVisibilities = ref([])
const weekDays = computed(() => {
    let wD = dayjs.weekdays()
    let weeks = [0,1,2,3,4,5,6];
    weeks.splice(wD.lastIndexOf(page.props.rombel.jadwals[0].hari), 1)
    return weeks
})

const allEvents = computed(() => {
    let all = allUnefektif(syahrs.value.slice((semester.value == '1' ? 6 : 0), (semester.value == '1' ? 12 : 6)),page.props.rombel.jadwals[0].hari)
    let dates = []
    for ( let evs of all) {
        for ( let ev of evs ) {
            dates.push(ev)
        }
    }

    return all
})

const disableDates = computed(() => {
    let disabled = []
    for ( let ae of allEvents.value) {
        let date = new Date(ae)
        disabled.push(`${date.getFullYear()}-${(date.getMonth()-1)}-${date.getDate()}`)
    }

    return disabled
})

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

const disabledDates = (time) => {
    let disabled = []
    for ( let ae of allEvents.value.flat(2) ) {
        let date = new Date(ae)
        disabled.push(`${date.getFullYear()}-${date.getMonth()}-${date.getDate()}`)
    }
    console.log(disabled.includes(time.getFullYear()+'-'+time.getMonth()+'-'+time.getDay()))
    // return disabled.includes(time.getFullYear()+'-'+time.getMonth()+'-'+time.getDate())
    // (time.getTime() < new Date(page.props.tapel.label.split("/")[0]+'-'+'07-01')).getTime() ||  (time.getTime() > new Date(page.props.tapel.label.split("/")[1]+'-'+'12-31').getTime())
    return weekDays.value.includes(time.getDay()) || disabled.includes(time.getFullYear()+'-'+time.getMonth()+'-'+time.getDay())
}

onBeforeMount(() => {
    // prosems.value = page.props.atps
    
    page.props.atps.forEach((atp, a) => {
        atp.tanggal = ''
        prosems.value.push(atp)
    })
})
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
                <!-- <p>{{allEvents}}</p> -->
                {{ weekDays }}
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
                    <table class="w-[90%] mx-auto my-8">
                        <thead>
                            <tr>
                                <th class="border border-black px-2">No</th>
                                <th class="border border-black px-2">Materi</th>
                                <th class="border border-black px-2">Tujuan Pembelajaran</th>
                                <th class="border border-black px-2">Alokasi Waktu</th>
                                <th class="border border-black px-2">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-for="(atp, a) in page.props.atps">
                                <tr>
                                    <td class="border border-black p-2 text-center">{{ a+1 }}</td>
                                    <td class="border border-black p-2">{{ atp.materi }}</td>
                                    <td class="border border-black p-2">{{ atp.tps }}</td>
                                    <td class="border border-black p-2 text-center">{{ atp.aw }}</td>
                                    <td class="border border-black p-2">
                                        <el-date-picker v-model="prosems[a]['tanggal']" format="DD-MM-YYYY" value-format="YYYY-MM-DD" placeholder="Tanggal Pelaksanaan" :disabled-date="disabledDates" :size="'small'" type="date"></el-date-picker>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                    <Ttd />
                </div>
            </el-card>
        </el-col>
    </el-row>
</DashLayout>
</template>