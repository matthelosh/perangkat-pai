<script setup>
import { ref, computed } from "vue";
import { usePage, router, Head, Link } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";
import { Calendar } from 'v-calendar'
import 'v-calendar/style.css'
import { ElNotification } from "element-plus";
import { weekOfMonth } from "@/helpers/ApeHelper";
import Layout from "@/layouts/DashboardLayout.vue";

const page = usePage();
const layout = computed(() => {
    const width = window.innerWidth
    let cols = width < 414 ? 1 : (width <= 768 ? 2 : (width <= 1366 ? 3 : (width <= 1920 ? 4 : 6)))
    let rows = width < 414 ? 12 : (width <= 768 ? 6 : (width <= 1366 ? 4 : (width <= 1920 ? 3 : 2)))
    return {
        cols, rows
    }
})
const haris = ref(['Minggu', 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'])
const offDays = computed(() => {
    let days = []
    const day = haris.value.lastIndexOf(page.props.rombel.jadwals[0].hari)
    for (let d=0; d < 7; d++) {
        if (d !== day) days.push(d+1)
    }
    return days
})
const loading=ref(false)
const kaldik = ref({
    is_libur: true,
    warna: 'red'
})
const params = computed(() => route().params)

const showFormProta = ref(false)
const prota = ref({
    guru_id: params.value.mine == '1' ? page.props.user.userable.nip : null,
    rombel_id: page.props.rombel.kode,
    tanggal: null,
    atp_id: null,
    minggu_ke: null,
    aw: 0,
})

const disabledDates = computed(() => {
    let dates = []
    attributes.value.forEach(dt => {
        if (dt.is_libur) dates.push({start: dt.startDate, end: dt.endDate})
    })
    dates.push({
        repeat: { weekdays: offDays.value}
    })
    dates.push({start: new Date(2024, 4, 3), end: new Date(2024, 4, 3)})

    return dates
})

const protas = computed(() => {
    const protas = page.props.atps.map(atp => atp.protas ?? [])
    return protas.flat(2)
})

const sekolah = page.props.sekolahs[0]
const showKaldik = ref(false)
const attributes = computed(() => {
    let attrs = []
    page.props.agendas.forEach((item, index) => {
        attrs.push({
            key: item.id, 
            highlight: item.warna, 
            dates: {start: new Date(item.mulai), end: new Date(item.selesai)},
            startDate: new Date(item.mulai),
            endDate: new Date(item.selesai),
            popover: {
                label: `${item.label} - ${item.deskripsi}`,
                visibility: 'hover'
            },
            index: index,
            description: item.deskripsi,
            is_libur: item.is_libur
        })
    })

    page.props.atps.forEach((atp,a) => {
        if (atp.protas && atp.protas.length > 0) {
            atp.protas.forEach((prot, p) => {
                attrs.push({
                    key: prot.kode,
                    highlight: 'blue',
                    dates: { start: new Date(prot.tanggal), end: new Date(prot.tanggal)},
                    startDate: new Date(prot.tanggal),
                    endtDate: new Date(prot.tanggal),
                    popover: {
                        label: `${atp.materi} - ${atp.konten}`,
                        visibility: 'hover'
                    }, 
                    index: ((a+1)+'-'+(p+1)),
                    description: atp.tps,
                    atp_id: atp.kode,
                    prota: prot
                })
            })
        }
    })


    return attrs
})

const onDayClicked = async(calendar, $event) => {
    if (calendar.isDisabled) {
        let isLibur = calendar.attributeCells[0].data.is_libur
        ElNotification({title: 'Info', message: `Pilih tanggal sesuai jadwal,  ${isLibur ? 'ini Hari libur!' : ''}`, type: 'error'})
    } else {
        // console.log(calendar)
        if (calendar.attributeCells[0]?.data.prota) {
            prota.value = calendar.attributeCells[0].data.prota
        } 
        prota.value.tanggal = calendar.id
        prota.value.minggu_ke = weekOfMonth(prota.value.tanggal)
        showFormProta.value = true
    }
}

const simpan = () => {
    loading.value = true
    router.post(route('prota.store', {_query: params.value}), {data: prota.value}, {
       onSuccess: (page) => {
           loading.value = false
           ElNotification({title: 'Info', message: 'Prota Disimpan', type:'success'})
        },
        onError: (err) => {
           loading.value = false
            ElNotification({title: 'Error', message: err, type:'error'})
            console.log(err)
        }
    })
    // console.log(prota.value)
}

const onDialogClose = () => {
    loading.value = false
    prota.value = {
        guru_id: params.value.mine == '1' ? page.props.user.userable.nip : null,
        rombel_id: page.props.rombel.kode,
        tanggal: null,
        atp_id: null,
        minggu_ke: null,
        aw: 0,
    }
}

const onAtpChanged = () => {
    if (!['PTS','PAS'].includes(prota.value.atp_id)) {
        let selectedAtp = page.props.atps.find(atp => atp.kode == prota.value.atp_id)
        prota.value.aw = selectedAtp.aw
        prota.value.semester = selectedAtp.semester
        
    } else {
        prota.value.aw = 0
    }
}

const cetak = async() => {

}
</script>

<template>
    <Layout title="Kalender Pendidikan">
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
                <div class="card-body">
                    <!-- {{ attributes }} -->
                    <div class="w-full flex justify-center">
                        <Calendar :disabled-dates="disabledDates" :columns="layout.cols" :rows="layout.rows" is-expanded :min-date="`${page.props.tapel.label.split('/')[0]}-07-01`" :max-date="`${page.props.tapel.label.split('/')[1]}-06-30`" locale="id" @dayclick="onDayClicked" timezone="Asia/Jakarta" :attributes="attributes" />
                    </div>
                </div>
            </el-card>
        </el-col>
    </el-row>
    <el-dialog v-model="showFormProta" draggable @close="onDialogClose">
        <template #header>
            <h3>Form Program Tahunan</h3>
        </template>
        <div class="dialog-body" draggable>
            <el-form v-model="prota" label-position="top" :inline=true>
                <el-form-item label="Tanggal">
                    <el-date-picker v-model="prota.tanggal" disabled />
                </el-form-item>
                <el-form-item label="Pilih Materi ATP" width="50%" class="w-[49%]">
                    <el-select v-model="prota.atp_id" @change="onAtpChanged">
                        <el-option value="PTS">PTS</el-option>
                        <el-option value="PAS">PAS</el-option>
                        <el-option v-for="(atp, a) in page.props.atps" :key="atp.kode" :value="atp.kode">
                            <span v-html="atp.konten" />
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="AW (JP)">
                    <el-input type="number" v-model="prota.aw" placeholder="Alokasi Waktu" />
                </el-form-item>
                <el-form-item label="Semester" class="w-[30%]">
                    <el-select v-model="prota.semester" placeholder="Pilih Semester">
                        <el-option value="1">Semester 1</el-option>
                        <el-option value="2">Semester 2</el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="Minggu Ke" class="w-[25%]">
                    <el-select v-model="prota.minggu_ke" placeholder="Pilih Minggu">
                        <el-option v-for="m in 5" :key="m" :value="m">{{m}}</el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="&nbsp;">
                    <el-button type="primary" :loading="loading" @click="simpan">Simpan</el-button>
                </el-form-item>
            </el-form>
        </div>
    </el-dialog>
    </Layout>
</template>