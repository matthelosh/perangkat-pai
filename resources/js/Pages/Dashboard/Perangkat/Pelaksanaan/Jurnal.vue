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
const Kop = defineAsyncComponent(() => import("@/components/Umum/Kop.vue"));
const Ttd = defineAsyncComponent(() => import("@/components/Umum/Ttd.vue"));

import DashLayout from "@/layouts/DashboardLayout.vue";

const mode = ref("list");
const formJurnal = ref(false);

const cetak = () => {
    const el = document.querySelector(".cetak");

    let html = `
            <!doctype html>
            <html>
                <head>
                    <title>Jurnal Pembelajaran</title>
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
        win.close();
    }, 500);
};
</script>

<template>
    <DashLayout title="Jurnal Pembelajaran">
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
                    <div class="items flex items-center gap-2">
                        <el-button :native-type="null" circle @click="cetak">
                            <Icon icon="mdi:printer" />
                        </el-button>
                    </div>
                </div>
            </template>
            <div class="card-body font-serif cetak">
                <div
                    class="paper w-full bg-slate-100 mb-4 p-4 print:bg-white break-inside-avoid"
                    v-for="(prota, p) in page.props.protas"
                    :key="p"
                >
                    <Kop />
                    <div class="title uppercase text-center font-bold">
                        <h3>Jurnal Pembelajaran</h3>
                        <h3>{{ page.props.sekolahs[0].nama }}</h3>
                    </div>
                    <div class="identitas">
                        <table>
                            <tr>
                                <td>Fase/Kelas</td>
                                <td class="px-1">:</td>
                                <td>
                                    {{ page.props.rombel.fase }} /
                                    {{ page.props.rombel.label }}
                                </td>
                            </tr>
                            <tr>
                                <td>Tapel | Semester</td>
                                <td class="px-1">:</td>
                                <td>
                                    {{ page.props.tapel.label }} |
                                    {{ prota.semester }}
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="konten my-6">
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th
                                        class="border border-black p-2 bg-slate-200"
                                    >
                                        Hari/Tgl
                                    </th>
                                    <th
                                        class="border border-black p-2 bg-slate-200"
                                    >
                                        Pembelajaran Ke
                                    </th>
                                    <th
                                        class="border border-black p-2 bg-slate-200"
                                    >
                                        ATP
                                    </th>
                                    <th
                                        class="border border-black p-2 bg-slate-200"
                                    >
                                        Materi Pokok
                                    </th>
                                    <th
                                        class="border border-black p-2 bg-slate-200"
                                    >
                                        Kegiatan Pembelajaran
                                    </th>
                                    <th
                                        class="border border-black p-2 bg-slate-200"
                                    >
                                        Asesmen
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td
                                        class="border border-black p-1 align-top"
                                    >
                                        {{
                                            dayjs(prota.tanggal).format(
                                                "dddd, DD/MM/YYYY"
                                            )
                                        }}
                                    </td>
                                    <td
                                        class="border border-black p-1 align-top"
                                    >
                                        {{ prota.atp?.materi ?? prota.atp_id }}
                                    </td>
                                    <td
                                        class="border border-black p-1 align-top"
                                    >
                                        {{ prota.atp?.tps ?? prota.atp_id }}
                                    </td>
                                    <td
                                        class="border border-black p-1 align-top"
                                    >
                                        {{ prota.atp?.konten ?? prota.atp_id }}
                                    </td>
                                    <td
                                        class="border border-black p-1 align-top"
                                    ></td>
                                    <td
                                        class="border border-black p-1 align-top"
                                    >
                                        <div
                                            v-if="prota.atp.asesmen"
                                            v-html="prota.atp.asesmen"
                                        />
                                        <div v-else v-html="prota.atp_id" />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <Ttd />
                </div>
            </div>
        </el-card>

        <el-dialog v-model="formJurnal" fullscreen> </el-dialog>
    </DashLayout>
</template>
