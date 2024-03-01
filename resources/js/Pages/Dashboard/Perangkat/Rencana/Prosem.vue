<script setup>
import { ref, computed, defineAsyncComponent, onBeforeMount, watch } from 'vue'
import { usePage, Link, router } from '@inertiajs/vue3'
import DashLayout from '@/layouts/DashboardLayout.vue';
import { Icon } from '@iconify/vue'
import dayjs from 'dayjs'
import localeData from 'dayjs/plugin/localeData'
import 'dayjs/locale/id'
dayjs.extend(localeData)
dayjs.locale("id")
import { cssLink } from '@/helpers/printHelper'
import { unefektif, allUnefektif, tes } from '@/helpers/ApeHelper';
import { ElNotification } from 'element-plus';

const Kop = defineAsyncComponent(() => import('@/components/Umum/Kop.vue'))
const Ttd = defineAsyncComponent(() => import('@/components/Umum/Ttd.vue'))

const page = usePage()
const sekolah = computed(() => page.props.sekolahs[0])
const params = route().params;
const semester = ref('1')
const atps = ref([
])
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
    let tr = ''
    page.props.atps.forEach((atp, a) => {

    })
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
    // console.log(disabled.includes(time.getFullYear()+'-'+time.getMonth()+'-'+time.getDay()))
    return weekDays.value.includes(time.getDay()) || disabled.includes(time.getFullYear()+'-'+time.getMonth()+'-'+time.getDay())
}

const onTanggalChanged = ( atp_id) => {
    let atp = atps.value.find(atp => atp.id == atp_id)
    if(atp.prosems && atp.prosems.tanggals?.length > 0) {
        atp.rombel_id = page.props.rombel.kode
        router.post(route('prosem.store'), {atp}, {
            // preserveState: true,
            onSuccess: (page) => {
                // parseTanggalProsem()
                router.reload({only: ['atps']})
                ElNotification({title: 'Info', message: 'Prosem disimpan', type: 'success'})
            },
            onError: (err) => {
                router.reload({only: ['atps']})
                ElNotification({title: 'Error', message: err, type: 'error'})
            }
        })
    } else {
        router.delete(route('prosem.destroy', {id:atp_id}), {
            preserveState: true,
            onSuccess: (page) => {
                console.log(page)
                // parseTanggalProsem()
                ElNotification({title: 'Info', message: 'Prosem dihapus', type: 'success'})
            },
            onError: (err) => {
                ElNotification({title: 'Error', message: err, type: 'error'})
            }
        })
    }

    // console.log(prosem)
}


const parseTanggalProsem = async() => {
    page.props.atps.forEach((atp, a) => {
        atps.value.push(atp)
        atps.value[a].prosems = {
            tanggals : atps.value[a].prosems ? atps.value[a].prosems.map(p => p.tanggal) : [],
            ids: atps.value[a].prosems ? atps.value[a].prosems.map(p => p.id) : []
        }   
    })
}

onBeforeMount(() => {
    
    parseTanggalProsem()
    
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
                        <div class="toolbar flex">
                            <el-button text circle @click="cetak">
                                <Icon icon="mdi:printer" class="text-xl" />
                            </el-button>
                        </div>
                    </div>
                </template>
                <div class="card-body cetak font-serif w-full mx-auto bg-white p-4 shadow print:shadow-none rounded">
                    <Kop />
                    <!-- {{ page.props.atps }} -->
                    <div class="title uppercase text-center my-6 font-bold">
                        <h3 class="text-xl">PROGRAM Semester (PROSEM) {{ page.props.semester.kode == '1' ? 'Ganjil' : 'Genap' }}</h3>
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
                    <table class="w-[90%] mx-auto my-8" v-if="atps.length > 0">
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
                            <template v-for="(atp, a) in atps">
                                <tr>
                                    <td class="border border-black p-2 text-center">{{ a+1 }}</td>
                                    <td class="border border-black p-2">{{ atp.materi }}</td>
                                    <td class="border border-black p-2">{{ atp.tps }}</td>
                                    <td class="border border-black p-2 text-center">{{ atp.aw }}</td>
                                    <td class="border border-black p-2">
                                        <!-- {{atp.prosems}} -->
                                        <el-date-picker v-model="atps[a].prosems.tanggals" format="DD-MM-YYYY" value-format="YYYY-MM-DD" placeholder="Tanggal Pelaksanaan" :disabled-date="disabledDates" :size="'small'" type="dates" @change="onTanggalChanged( atp.id )"></el-date-picker>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                    <div v-else class="my-6 mx-12">
                        <el-alert  type="error" style="padding: 20px;">
                            <template #title>
                                <span class="text-lg font-semibold">
                                    Anda belum menyusun ATP Kelas {{ page.props.rombel.label }} Semester {{  page.props.semester.kode }} Tahun {{ page.props.tapel.label }}
                                </span>
                            </template>
                            <p>Susun ATP <Link :href="appRoute('atp.index', {_query: {fase: params.fase, mine: params.mine}})" class=" font-semibold text-blue-400 uppercase bg-blue-100 px-3 py-1 shadow">di sini</Link></p>
                        </el-alert>
                    </div>
                    <Ttd />
                </div>
            </el-card>
        </el-col>
    </el-row>
</DashLayout>
</template>