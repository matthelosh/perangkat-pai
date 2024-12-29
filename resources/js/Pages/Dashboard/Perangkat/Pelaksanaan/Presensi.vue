<script setup>
import { ref, computed, defineAsyncComponent } from "vue";
import { usePage, Head, Link } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";

import { cssLink } from "@/helpers/printHelper.js";
import dayjs from "dayjs";
import localeData from "dayjs/plugin/localeData";
import "dayjs/locale/id";
dayjs.extend(localeData);
dayjs.locale("id");

const page = usePage();

import DashLayout from "@/layouts/DashboardLayout.vue";
const Kop = defineAsyncComponent(() => import("@/components/Umum/Kop.vue"));
const Ttd = defineAsyncComponent(() => import("@/components/Umum/Ttd.vue"));

const mode = ref("list");
const formJurnal = ref(false);
const tanggals = computed(() => {
    return page.props.protas.map((prota) => prota.tanggal);
});

const cetak = async () => {
    const el = document.querySelector(".cetak");

    let html = `
            <!doctype html>
            <html>
                <head>
                    <title>
                        Presensi Siswa
                    </title>
                    <link href="${cssLink(
                        page.props.app_env
                    )}" rel="stylesheet" />
                </head>
                <body>
                    ${el.outerHTML}
                </body>
            </html>
    `;

    let win = window.open("", "_blank", "width=800,height=700");
    win.document.write(html);

    setTimeout(() => {
        win.print();
    }, 500);
};
</script>

<template>
    <div title="Jurnal Pembelajaran">
        <el-card>
            <template #header>
                <div class="toolbar flex items-center justify-between">
                    <h3
                        class="font-bold uppercase tracking-wide flex gap-2 text-blue-800"
                    >
                        <Link :href="appRoute('pelaksanaan')">
                            <Icon
                                icon="mdi:arrow-left"
                                class="text-2xl text-green-700"
                            />
                        </Link>
                        Jurnal Pembelajaran Kelas
                        {{ page.props.rombel.label }} Semester
                        {{ page.props.semester.label }} Tapel
                        {{ page.props.tapel.label }}
                    </h3>
                    <div class="items">
                        <el-button :native-type="null" circle @click="cetak">
                            <Icon icon="mdi:printer" />
                        </el-button>
                    </div>
                </div>
            </template>
            <div class="card-body cetak">
                <Kop />
                <div class="title uppercase font-serif font-bold my-4">
                    <h3 class="text-center">Presensi Peserta Didik</h3>
                    <h3 class="text-center">
                        Tahun Pelajaran {{ page.props.tapel.label }} Semester
                        {{ page.props.semester.label }}
                    </h3>
                </div>
                <div class="meta">
                    <table>
                        <tr>
                            <td>Satuan Pendidikan</td>
                            <td class="px-2">:</td>
                            <td>{{ page.props.sekolahs[0].nama }}</td>
                        </tr>
                        <tr>
                            <td>Fase / Kelas</td>
                            <td class="px-2">:</td>
                            <td>
                                {{
                                    page.props.rombel.fase +
                                    " / " +
                                    page.props.rombel.label
                                }}
                            </td>
                        </tr>
                        <tr>
                            <td>Mata Pelajaran</td>
                            <td class="px-2">:</td>
                            <td>PAI dan Budi Pekerti</td>
                        </tr>
                    </table>
                </div>

                <div class="content my-6">
                    <el-scrollbar width="100%">
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th
                                        rowspan="2"
                                        class="border border-black p-2 text-center"
                                    >
                                        No
                                    </th>
                                    <th
                                        rowspan="2"
                                        class="border border-black p-2 text-center"
                                    >
                                        NISN
                                    </th>
                                    <th
                                        rowspan="2"
                                        class="border border-black p-2"
                                    >
                                        NISN
                                    </th>
                                </tr>
                                <tr>
                                    <th
                                        class="border border-black p-2 text-sm"
                                        v-for="tgl in tanggals"
                                        :key="tgl"
                                    >
                                        {{ dayjs(tgl).format("DD/MM") }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(siswa, s) in page.props.rombel
                                        .siswas"
                                    :key="siswa.nisn"
                                >
                                    <td
                                        class="border border-black p-2 text-center"
                                    >
                                        {{ s + 1 }}
                                    </td>
                                    <td
                                        class="border border-black p-2 text-center"
                                    >
                                        {{ siswa.nisn }}
                                    </td>
                                    <td class="border border-black p-2">
                                        {{ siswa.nama }}
                                    </td>
                                    <td
                                        class="border border-black p-2"
                                        v-for="tgl in tanggals"
                                        :key="tgl"
                                    ></td>
                                </tr>
                            </tbody>
                        </table>
                    </el-scrollbar>
                </div>

                <Ttd />
            </div>
        </el-card>
    </div>
</template>
