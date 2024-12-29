<script setup>
import { ref, computed, defineAsyncComponent, onBeforeMount, watch } from "vue";
import { usePage, Link, router } from "@inertiajs/vue3";
import DashLayout from "@/layouts/DashboardLayout.vue";
import { Icon } from "@iconify/vue";
import dayjs from "dayjs";
import localeData from "dayjs/plugin/localeData";
import "dayjs/locale/id";
dayjs.extend(localeData);
dayjs.locale("id");
import { cssLink } from "@/helpers/printHelper";
import {
    weekOfMonth,
    allUnefektif,
    weeksPerMonth,
    unefektif,
} from "@/helpers/ApeHelper";
import { ElNotification } from "element-plus";

const Kop = defineAsyncComponent(() => import("@/components/Umum/Kop.vue"));
const Ttd = defineAsyncComponent(() => import("@/components/Umum/Ttd.vue"));

const page = usePage();
const sekolah = computed(() => page.props.sekolahs[0]);
const params = route().params;
// const semester = ref('1')
const atps = ref([]);
const syahrs = computed(() => {
    let months = dayjs.months();
    return months.slice(
        page.props.semester.kode == "1" ? 6 : 0,
        page.props.semester.kode == "1" ? 12 : 6
    );
});
const pickerVisibilities = ref([]);
const weekDays = computed(() => {
    let wD = dayjs.weekdays();
    let weeks = [0, 1, 2, 3, 4, 5, 6];
    weeks.splice(wD.lastIndexOf(page.props.rombel.jadwals[0].hari), 1);
    return weeks;
});

const minggus = (m) => {
    return weeksPerMonth(
        m,
        page.props.rombel.jadwals[0].hari,
        page.props.semester.kode
    );
};

const eventsDate = computed(() => {
    let events = allUnefektif(syahrs.value, page.props.rombel.jadwals[0].hari);
    events.flat(2);

    // let liburs = []
    let offs = [];
    // for (let t=0; t < liburs.value.length; t++) {
    //     offs.push({
    //         minggu: weekOfMonth(liburs.value[t]),
    //         bulan: dayjs(liburs.value[t]).format('MMMM'),
    //         tanggal: dayjs(liburs.value[t]).format('YYYY-MM-DD')
    //     })
    // }
    liburs.value.forEach((libur, l) => {
        offs.push({
            minggu: weekOfMonth(libur.mulai),
            bulan: dayjs(libur.mulai).format("MMMM"),
            tanggal: libur.mulai,
        });
    });

    return offs;
});

const liburs = computed(() => {
    let all = [];
    for (let m = 0; m < syahrs.value.length; m++) {
        all.push(unefektif(syahrs.value[m], page.props.rombel.jadwals[0].hari));
    }
    return all.flat(2);
});

const cekLibur = (m, w) => {
    // console.log(m,w)
    return (
        eventsDate.value.filter((ev) => ev.bulan == m && ev.minggu == w)
            .length > 0
    );
};

const cetak = () => {
    let el = document.querySelector(".cetak");
    let style =
        page.props.app_env == "local"
            ? `<link href="stylesheet" href="${window.location.origin}:5173/resources/css/app.css" />`
            : (style = `<link href="stylesheet" href="${window.location.origin}/build/assets/app.css" /><link href="stylesheet" href="${window.location.origin}/build/assets/app2.css" />`);
    let html = `<html>
                <head>
                    <title>Program Semester</title>    
                    ${style}
                </head>
                <body>
                    ${el.outerHTML}
                </body>
            </html>`;

    let win = window.open("", "_blank", "width=800,height=700");
    win.document.write(html);
    setTimeout(() => {
        win.print();
        win.close();
    }, 1000);
};
</script>

<template>
    <div title="Cetak Program Semester">
        <el-row class="w-full">
            <el-col :span="24">
                <el-card>
                    <template #header>
                        <div class="toolbar flex justify-between items-center">
                            <h3
                                class="font-bold text-blue-600 uppercase flex items-center gap-2"
                            >
                                <Link
                                    :href="
                                        appRoute('rencana', { _query: params })
                                    "
                                >
                                    <Icon
                                        icon="mdi:arrow-left"
                                        class="text-xl"
                                    />
                                </Link>
                                Program Semester
                                {{ page.props.semester.kode }} ({{
                                    page.props.semester.kode == "1"
                                        ? "Ganjil"
                                        : "Genap"
                                }}) Kelas {{ page.props.rombel.label }} Tahun
                                {{ page.props.tapel.label }}
                            </h3>
                            <div class="toolbar-items flex items-center gap-2">
                                <el-button
                                    :native-type="null"
                                    circle
                                    type=""
                                    @click="cetak"
                                >
                                    <Icon icon="mdi:printer" />
                                </el-button>
                            </div>
                        </div>
                    </template>
                    <div class="card-body cetak">
                        <Kop />

                        <!-- {{ eventsDate }} -->
                        <div class="title uppercase text-center my-6 font-bold">
                            <h3 class="text-xl">
                                PROGRAM Semester (PROSEM)
                                {{
                                    page.props.semester.kode == "1"
                                        ? "Ganjil"
                                        : "Genap"
                                }}
                            </h3>
                            <h3 class="text-xl">
                                TAHUN PELAJARAN {{ page.props.tapel.label }}
                            </h3>
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
                                    <td>
                                        {{ page.props.rombel.jadwals[0].hari }}
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <el-scrollbar width="100vw">
                            <table class="my-8 w-full">
                                <thead>
                                    <tr>
                                        <th
                                            class="border border-black p-2"
                                            rowspan="2"
                                        >
                                            No
                                        </th>
                                        <th
                                            class="border border-black p-2"
                                            rowspan="2"
                                        >
                                            Materi
                                        </th>
                                        <th
                                            class="border border-black p-2"
                                            rowspan="2"
                                        >
                                            Alur Tujuan Pembelajaran
                                        </th>
                                        <th
                                            class="border border-black p-2"
                                            rowspan="2"
                                        >
                                            Konten Materi
                                        </th>
                                        <th
                                            class="border border-black p-2"
                                            rowspan="2"
                                        >
                                            Alokasi Waktu
                                        </th>
                                        <th
                                            v-for="m in syahrs"
                                            :key="m"
                                            class="border border-black p-2"
                                            :colspan="minggus(m)"
                                        >
                                            {{ m }}
                                        </th>
                                    </tr>
                                    <tr>
                                        <template
                                            v-for="m in syahrs"
                                            :key="m + 'a'"
                                        >
                                            <th
                                                class="border border-black p-2"
                                                v-for="w in minggus(m)"
                                                :key="w"
                                            >
                                                {{ w }}
                                            </th>
                                        </template>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="(prota, p) in page.props.protas"
                                        :key="p"
                                    >
                                        <td
                                            class="border border-black p-2 text-center"
                                        >
                                            {{ p + 1 }}
                                        </td>
                                        <td class="border border-black p-2">
                                            {{
                                                prota.atp?.materi ??
                                                prota.atp_id
                                            }}
                                        </td>
                                        <td class="border border-black p-2">
                                            <ul
                                                v-if="prota.atp?.tps"
                                                class="pl-4 list-disc"
                                            >
                                                <li
                                                    v-for="(
                                                        tp, t
                                                    ) in prota.atp.tps.split(
                                                        ';'
                                                    )"
                                                    :key="t"
                                                >
                                                    {{ tp }}
                                                </li>
                                            </ul>
                                            <p v-else>
                                                {{
                                                    prota.atp
                                                        ? prota.atp.materi
                                                        : prota.atp_id
                                                }}
                                            </p>
                                        </td>
                                        <td class="border border-black p-2">
                                            <ul
                                                v-if="prota.atp?.tps"
                                                class="pl-4 list-disc"
                                            >
                                                <li
                                                    v-for="(
                                                        kn, t
                                                    ) in prota.atp.konten.split(
                                                        ';'
                                                    )"
                                                    :key="t"
                                                >
                                                    {{ kn }}
                                                </li>
                                            </ul>
                                            <p v-else>
                                                {{
                                                    prota.atp
                                                        ? prota.atp.materi
                                                        : prota.atp_id
                                                }}
                                            </p>
                                        </td>
                                        <td
                                            class="border border-black p-2 text-center"
                                        >
                                            {{ prota.aw }} JP
                                        </td>
                                        <template
                                            v-for="m in syahrs"
                                            :key="m + 'a'"
                                        >
                                            <td
                                                class="border border-black p-2"
                                                v-for="w in minggus(m)"
                                                :key="w"
                                                :class="
                                                    prota.minggu_ke == w &&
                                                    dayjs(prota.tanggal).format(
                                                        'MMMM'
                                                    ) == m
                                                        ? 'bg-white'
                                                        : cekLibur(m, w)
                                                        ? 'bg-red-100'
                                                        : 'bg-slate-200'
                                                "
                                            >
                                                <span
                                                    v-if="
                                                        prota.minggu_ke == w &&
                                                        dayjs(
                                                            prota.tanggal
                                                        ).format('MMMM') == m
                                                    "
                                                    >{{
                                                        dayjs(
                                                            prota.tanggal
                                                        ).format("DD/MM")
                                                    }}</span
                                                >
                                                <span v-else>
                                                    <p v-if="cekLibur(m, w)">
                                                        Libur
                                                    </p>
                                                </span>
                                            </td>
                                        </template>
                                    </tr>
                                </tbody>
                            </table>
                        </el-scrollbar>
                        <p>Keterangan:</p>
                        <!-- {{ liburs }} -->
                        <ul class="list-decimal pl-6">
                            <template v-for="(libur, l) in liburs" :key="l">
                                <li class="text-red-800">
                                    {{
                                        dayjs(libur.mulai).format("DD/MM/YYYY")
                                    }}: {{ libur.label }}
                                </li>
                            </template>
                        </ul>
                        <Ttd />
                    </div>
                </el-card>
            </el-col>
        </el-row>
    </div>
</template>
