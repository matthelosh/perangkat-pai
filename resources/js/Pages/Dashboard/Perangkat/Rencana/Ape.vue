<script setup>
import { ref, computed, defineAsyncComponent } from "vue";
import { usePage, Head, Link } from "@inertiajs/vue3";
import DashLayout from "@/layouts/DashboardLayout.vue";
import { Icon } from "@iconify/vue";
import dayjs from "dayjs";
import localeData from "dayjs/plugin/localeData";
import "dayjs/locale/id";
dayjs.extend(localeData);
dayjs.locale("id");
const Kop = defineAsyncComponent(() => import("@/components/Umum/Kop.vue"));
const Ttd = defineAsyncComponent(() => import("@/components/Umum/Ttd.vue"));
import {
    uppercase,
    weeksPerMonth,
    unefektif,
    weekPerSemester,
    sumUnEfektif,
    sumEfektif,
} from "@/helpers/ApeHelper";

const page = usePage();
const rombel = page.props.rombel;
// const semester = computed(() => page.props.semester.id)
const semester = ref("1");

const bulans = computed(() => {
    let months = dayjs.months();
    // return months.splice((page.props.periode.semester == '2' ? 0 : 6),6)
    return months.slice(
        semester.value == "1" ? 6 : 0,
        semester.value == "1" ? 12 : 6
    );
    // return months
});

const newTanggal = ref(null);

const tanggal = computed(
    () =>
        newTanggal.value ??
        (semester.value == "2"
            ? new Date().getFullYear() + "-01-10"
            : new Date().getFullYear() + "-07-10")
);

import { cssFiles } from "@/helpers/printHelper";
const cetak = async () => {
    let el = document.querySelector(".cetak");
    let html = `
        <!doctype html>
        <html>
            <head>
                <title>Rencana Pekan Efektif Kelas ${page.props.rombel.label}</title>
            </head>

            <body>
                ${el.outerHTML}
            </body>
        </html>
    `;

    let win = window.open("", "_blank", "width=800,height=600");
    win.document.write(html);
    const styles = await cssFiles();
    styles.forEach((css) => {
        const cssLink = win.document.createElement("link");
        cssLink.rel = "stylesheet";
        cssLink.href = css;
        win.document.head.append(cssLink);
    });
    setTimeout(() => {
        win.print();
        // win.close();
    }, 1000);
};
</script>

<template>
    <div title="Analisis Pekan Efektif">
        <Head title="Analisis Pekan Efektif" />
        <div class="wrapper">
            <el-card>
                <template #header>
                    <div class="toolbar h-10 flex items-center justify-between">
                        <h3
                            class="font-bold text-blue-800 flex items-center gap-2"
                        >
                            <Link :href="appRoute('rencana')">
                                <Icon
                                    icon="mdi:arrow-left"
                                    class="text-2xl text-red-500 font-bold"
                                />
                            </Link>
                            Buat Analisis Pekan Efektif Kelas {{ rombel.label }}
                        </h3>
                        <div class="items flex items-center gap-3">
                            <el-date-picker
                                v-model="newTanggal"
                                format="DD/MM/YYYY"
                                value-format="YYYY-MM-DD"
                                placeholder="Tanggal"
                            />
                            <el-button
                                :native-type="null"
                                @click="cetak"
                                circle
                                text
                            >
                                <Icon icon="mdi:printer" class="text-xl" />
                            </el-button>
                            <label for="semester" class="bg-blue-200 px-2 py-1">
                                Semester
                                <select v-model="semester">
                                    <option
                                        v-for="sem in ['1', '2']"
                                        :key="sem"
                                        :value="sem"
                                    >
                                        {{ sem }}
                                    </option>
                                </select>
                            </label>
                        </div>
                    </div>
                </template>
                <div class="card-body">
                    <div class="cetak">
                        <Kop />
                        <el-row class="">
                            <el-col :span="24">
                                <h2 class="text-center font-bold uppercase">
                                    RENCANA PEKAN EFEKTIF
                                </h2>
                                <h2 class="text-center font-bold uppercase">
                                    TAHUN PELAJARAN {{ page.props.tapel.label }}
                                </h2>
                            </el-col>
                        </el-row>
                        <el-row class="w-full mt-6 mx-8">
                            <el-col :span="24">
                                <table>
                                    <tr>
                                        <td>Satuan Pendidikan</td>
                                        <td>:</td>
                                        <td>
                                            {{ page.props.sekolahs[0].nama }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Kelas/Semester</td>
                                        <td>:</td>
                                        <td>
                                            {{ page.props.rombel.label }} /
                                            {{ semester }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Mata Pelajaran</td>
                                        <td>:</td>
                                        <td>PAI dan Budi Pekerti</td>
                                    </tr>
                                    <tr>
                                        <td>Jadwal</td>
                                        <td>:</td>
                                        <td>
                                            {{
                                                page.props.rombel.jadwals
                                                    .length > 0
                                                    ? page.props.rombel
                                                          .jadwals[0]?.hari
                                                    : "-"
                                            }}
                                        </td>
                                    </tr>
                                </table>
                            </el-col>
                        </el-row>
                        <el-row class="mx-8">
                            <el-col :span="24">
                                <div class="my-4">
                                    <el-scrollbar width="100vw" horizontal>
                                        <table
                                            style="
                                                border-collapse: collapse;
                                                width: 100%;
                                            "
                                        >
                                            <thead>
                                                <tr>
                                                    <th
                                                        class="px-3 border border-black"
                                                        rowspan="2"
                                                    >
                                                        NO
                                                    </th>
                                                    <th
                                                        class="px-3 border border-black"
                                                        rowspan="2"
                                                    >
                                                        BULAN
                                                    </th>
                                                    <th
                                                        class="px-3 border border-black"
                                                        colspan="3"
                                                    >
                                                        JUMLAH PEKAN
                                                    </th>
                                                    <th
                                                        class="px-3 border border-black"
                                                        rowspan="2"
                                                    >
                                                        KETERANGAN
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th
                                                        class="px-3 border border-black"
                                                    >
                                                        JUMLAH PEKAN
                                                    </th>
                                                    <th
                                                        class="px-3 border border-black"
                                                    >
                                                        TDK EFEKTIF
                                                    </th>
                                                    <th
                                                        class="px-3 border border-black"
                                                    >
                                                        EFEKTIF
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr
                                                    v-for="(bulan, b) in bulans"
                                                    :key="bulan"
                                                >
                                                    <td
                                                        class="border border-black px-3 text-center"
                                                    >
                                                        {{ b + 1 }}
                                                    </td>
                                                    <td
                                                        class="border border-black px-3"
                                                    >
                                                        {{ uppercase(bulan) }}
                                                    </td>
                                                    <td
                                                        class="border border-black px-3 text-center"
                                                    >
                                                        {{
                                                            weeksPerMonth(
                                                                bulan,
                                                                page.props
                                                                    .rombel
                                                                    .jadwals[0]
                                                                    .hari,
                                                                semester
                                                            )
                                                        }}
                                                    </td>
                                                    <td
                                                        class="border border-black px-3 text-center"
                                                    >
                                                        {{
                                                            unefektif(
                                                                bulan,
                                                                page.props
                                                                    .rombel
                                                                    .jadwals[0]
                                                                    .hari,
                                                                semester
                                                            ).length
                                                        }}
                                                    </td>
                                                    <td
                                                        class="border border-black px-3 text-center"
                                                    >
                                                        {{
                                                            weeksPerMonth(
                                                                bulan,
                                                                page.props
                                                                    .rombel
                                                                    .jadwals[0]
                                                                    .hari
                                                            ) -
                                                            unefektif(
                                                                bulan,
                                                                page.props
                                                                    .rombel
                                                                    .jadwals[0]
                                                                    .hari,
                                                                semester
                                                            ).length
                                                        }}
                                                    </td>
                                                    <td
                                                        class="border border-black"
                                                    >
                                                        <ol
                                                            class="list-disc pl-6"
                                                            v-for="(
                                                                even, e
                                                            ) in unefektif(
                                                                bulan,
                                                                page.props
                                                                    .rombel
                                                                    .jadwals[0]
                                                                    .hari,
                                                                semester
                                                            )"
                                                            :key="e"
                                                        >
                                                            <!-- <li class="" :style="`color: ${even.warna};`">{{ even ? dayjs(even.start).locale("id").format('ddd, DD/MM/YYYY') : '' }}: {{ even.description }}</li> -->
                                                            <li
                                                                class=""
                                                                :style="`color: ${even.warna};`"
                                                            >
                                                                {{
                                                                    dayjs(
                                                                        even.mulai
                                                                    ).format(
                                                                        "DD MMM YYYY"
                                                                    )
                                                                }}:
                                                                {{
                                                                    even.deskripsi
                                                                }}
                                                            </li>
                                                        </ol>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        class="border border-black text-center font-bold"
                                                        colspan="2"
                                                    >
                                                        JUMLAH
                                                    </td>
                                                    <td
                                                        class="border border-black text-center font-bold"
                                                    >
                                                        {{
                                                            weekPerSemester(
                                                                bulans,
                                                                page.props
                                                                    .rombel
                                                                    .jadwals[0]
                                                                    .hari
                                                            )
                                                        }}
                                                    </td>
                                                    <td
                                                        class="border border-black text-center font-bold"
                                                    >
                                                        {{
                                                            sumUnEfektif(
                                                                bulans,
                                                                page.props
                                                                    .rombel
                                                                    .jadwals[0]
                                                                    .hari,
                                                                semester
                                                            )
                                                        }}
                                                    </td>
                                                    <td
                                                        class="border border-black text-center font-bold"
                                                    >
                                                        {{
                                                            sumEfektif(
                                                                bulans,
                                                                page.props
                                                                    .rombel
                                                                    .jadwals[0]
                                                                    .hari,
                                                                semester
                                                            )
                                                        }}
                                                    </td>
                                                    <td
                                                        class="border border-black text-center font-bold"
                                                    ></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </el-scrollbar>
                                </div>
                            </el-col>
                        </el-row>

                        <Ttd :tanggal="tanggal" />
                    </div>
                </div>
            </el-card>
        </div>
    </div>
</template>
