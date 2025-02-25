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
import { cssFiles } from "@/helpers/printHelper";
import {
    weekOfMonth,
    allUnefektif,
    weeksPerMonth,
    unefektif,
} from "@/helpers/ApeHelper";
import { ElNotification } from "element-plus";

const model = ref("2");

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

    let offs = [];
    liburs.value.forEach((libur, l) => {
        offs.push({
            minggu: weekOfMonth(libur.mulai),
            bulan: dayjs(libur.mulai).format("MMMM"),
            tanggal: libur.mulai,
        });
    });

    return offs;
});

const allprotas = computed(() => {
    let elemens = [];
    page.props.elemens.forEach((item, i) => {
        item.tanggal = item.atps[0].prota.tanggal;
        elemens.push(item);
    });
    let res = [...elemens, ...nonAtps.value];
    return res.sort((a, b) => new Date(a.tanggal) - new Date(b.tanggal));
});

const nonAtps = computed(() => {
    return page.props.protas.filter((prota) => prota.atp == null);
});

const liburs = computed(() => {
    let all = [];
    for (let m = 0; m < syahrs.value.length; m++) {
        all.push(unefektif(syahrs.value[m], page.props.rombel.jadwals[0].hari));
    }
    return all.flat(2);
});

const cekLibur = (m, w) => {
    console.log(m, w);
    return (
        eventsDate.value.filter((ev) => ev.bulan == m && ev.minggu == w)
            .length > 0
    );
};

const cetak = async () => {
    let el = document.querySelector(".cetak");
    let html = `<html>
                <head>
                    <title>Program Semester</title>    
                </head>
                <body>
                    ${el.outerHTML}
                </body>
            </html>`;

    let win = window.open("", "_blank", "width=800,height=700");
    win.document.write(html);
    const styles = await cssFiles();
    styles.forEach((css) => {
        const link = win.document.createElement("link");
        link.rel = "stylesheet";
        link.href = css;
        win.document.head.append(link);
    });
    setTimeout(() => {
        win.print();
        // win.close();
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
                                <div class="w-[100px] flex gap-2">
                                    Model
                                    <el-select
                                        v-model="model"
                                        placeholder="Pilih Model"
                                    >
                                        <el-option value="1">Model 1</el-option>
                                        <el-option value="2">Model 2</el-option>
                                    </el-select>
                                </div>
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
                        <!-- {{ allprotas }} -->
                        <!-- Model 2 -->
                        <table class="border w-full" v-if="model == '2'">
                            <thead>
                                <tr>
                                    <th
                                        class="border border-black py-2"
                                        rowspan="2"
                                    >
                                        Bab
                                    </th>
                                    <th
                                        class="border border-black py-2"
                                        rowspan="2"
                                    >
                                        Elemen
                                    </th>
                                    <th
                                        class="border border-black py-2"
                                        rowspan="2"
                                    >
                                        Materi
                                    </th>
                                    <th
                                        class="border border-black py-2"
                                        rowspan="2"
                                    >
                                        Sub Materi
                                    </th>
                                    <th
                                        class="border border-black py-2"
                                        rowspan="2"
                                    >
                                        Alokasi Waktu
                                    </th>
                                    <th
                                        v-for="m in syahrs"
                                        :key="m"
                                        class="border border-black py-2"
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
                                            class="border border-black px-2"
                                            v-for="w in minggus(m)"
                                            :key="w"
                                        >
                                            {{ w }}
                                        </th>
                                    </template>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="(elemen, e) in allprotas">
                                    <tr
                                        v-for="(atp, a) in elemen.atps"
                                        :key="a"
                                        class="group relative"
                                        v-if="elemen.atps"
                                    >
                                        <td
                                            class="border border-black p-1 w-[80px]"
                                            v-if="a === 0"
                                            :rowspan="elemen.atps.length"
                                        >
                                            <button
                                                class="absolute top-1 text-sky-600 font-bold"
                                                :native-type="null"
                                                type="primary"
                                            >
                                                <Icon icon="mdi:plus" />
                                            </button>
                                            {{ elemen.materis[0].bab }}
                                        </td>
                                        <td
                                            class="border border-black p-1"
                                            style="writing-mode: vertical-lr"
                                            v-if="a === 0"
                                            :rowspan="elemen.atps.length"
                                        >
                                            {{ elemen.label }}
                                        </td>
                                        <td
                                            class="border border-black p-1"
                                            :colspan="
                                                atp.konten.length == '0' &&
                                                a === elemen.atps.length - 1
                                                    ? '2'
                                                    : '0'
                                            "
                                            :rowspan="
                                                a === 0
                                                    ? elemen.atps.length - 1
                                                    : 1
                                            "
                                            v-if="
                                                a === 0 ||
                                                a === elemen.atps.length - 1
                                            "
                                        >
                                            <div
                                                v-if="
                                                    a === 0 ||
                                                    a === elemen.atps.length - 1
                                                "
                                            >
                                                {{ atp.materi }}
                                            </div>
                                            <div v-else>
                                                {{ elemen.atps.length }}
                                            </div>
                                        </td>
                                        <td
                                            class="border border-black p-1"
                                            v-if="atp.konten"
                                        >
                                            {{ atp.konten }}
                                        </td>
                                        <td
                                            class="border border-black p-1 text-center"
                                        >
                                            {{ atp.aw }} JP
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
                                                    atp.prota?.minggu_ke == w &&
                                                    dayjs(
                                                        atp.prota.tanggal
                                                    ).format('MMMM') == m
                                                        ? 'bg-white'
                                                        : cekLibur(m, w)
                                                        ? 'bg-red-100'
                                                        : 'bg-slate-200'
                                                "
                                            >
                                                <span
                                                    v-if="
                                                        atp.prota?.minggu_ke ==
                                                            w &&
                                                        dayjs(
                                                            atp.prota.tanggal
                                                        ).format('MMMM') == m
                                                    "
                                                    >{{
                                                        dayjs(
                                                            atp.prota.tanggal
                                                        ).format("DD")
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
                                    <tr v-else>
                                        <td
                                            class="border border-black p-2"
                                            colspan="4"
                                        >
                                            {{ elemen.atp_id }}
                                        </td>
                                        <td
                                            class="border border-black p-2 text-center"
                                        >
                                            {{ elemen.aw }} JP
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
                                                    elemen.minggu_ke == w &&
                                                    dayjs(
                                                        elemen.tanggal
                                                    ).format('MMMM') == m
                                                        ? 'bg-white'
                                                        : cekLibur(m, w)
                                                        ? 'bg-red-100'
                                                        : 'bg-slate-200'
                                                "
                                            >
                                                <span
                                                    v-if="
                                                        elemen.minggu_ke == w &&
                                                        dayjs(
                                                            elemen.tanggal
                                                        ).format('MMMM') == m
                                                    "
                                                    >{{
                                                        dayjs(
                                                            elemen.tanggal
                                                        ).format("DD")
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
                                </template>
                            </tbody>
                        </table>

                        <el-scrollbar width="100vw" v-if="model == '1'">
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
                                                        ).format("DD")
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
