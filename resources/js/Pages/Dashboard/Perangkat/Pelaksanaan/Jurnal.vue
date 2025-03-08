<script setup>
import { ref, computed, defineAsyncComponent } from "vue";
import { usePage, Head, Link } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";
import { cssFiles } from "@/helpers/printHelper.js";
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
const jurnal = ref({
    absensi: [],
    foto: null,
});

const cetak = async () => {
    const el = document.querySelector(".cetak");

    let html = `
            <!doctype html>
            <html>
                <head>
                    <title>Jurnal Pembelajaran</title>
                </head>
                <body>
                    ${el.outerHTML}
                </body>
            </html>
    `;

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
        win.close();
    }, 500);
};

const isiJurnal = () => {
    formJurnal.value = true;
};

const inputFoto = ref(null);
const onFotoPicked = async (e) => {
    const foto = e.target.files[0];
};
</script>

<template>
    <div title="Jurnal Pembelajaran">
        <el-card>
            <template #header>
                <!-- <el-affix :offset="60"> -->
                <div class="toolbar flex items-center justify-between bg-white">
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
                <!-- </el-affix> -->
            </template>

            <!-- On Large Screen and Print -->
            <div class="card-body font-serif cetak hidden sm:block">
                <div
                    class="paper w-[60%] mx-auto h-auto print:h-full p-8 print:pl-8 print:w-full bg-slate-100 mb-4 p-4 print:bg-white break-inside-avoid"
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
                                        class="border border-black p-1 pl-6 align-top"
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
                    <p>Absensi:</p>
                    <div
                        class="catatan border border-2 border-black p-4 h-28"
                    ></div>
                    <p class="mt-4">Catatan:</p>
                    <div
                        class="catatan border border-2 border-black p-4 h-40"
                    ></div>
                    <Ttd :tanggal="prota.tanggal" />
                </div>
            </div>
            <!-- On Mobile -->
            <template v-for="(prota, p) in page.props.protas">
                <el-card class="my-4">
                    <template #header>
                        <h4>
                            {{ prota.atp?.materi }} <br />
                            <small>{{
                                dayjs(prota.tanggal).format("DD/MM/YYYY")
                            }}</small>
                        </h4>
                    </template>
                    <template #default>
                        <!-- <ul class="list-decimal pl-4">
                            <li v-for="item in prota.atp?.konten.split(';')">
                                {{ item }}
                            </li>
                        </ul> -->
                        {{ prota.atp?.konten }}
                    </template>
                    <template #footer>
                        <div class="flex items-center justify-between">
                            <el-button
                                :native-type="null"
                                type="primary"
                                @click="isiJurnal"
                                >Isi Jurnal</el-button
                            >
                            <el-button :native-type="null">Cetak</el-button>
                        </div>
                    </template>
                </el-card>
            </template>
        </el-card>

        <el-dialog v-model="formJurnal" fullscreen title="Isi Jurnal">
            <el-form v-model="jurnal" label-position="top">
                <el-form-item label="Absensi">
                    <el-select
                        v-model="jurnal.absensi"
                        multiple
                        placeholder="Pilih Siswa"
                        filterable
                    >
                        <el-option
                            v-for="siswa in page.props.rombel.siswas"
                            :value="siswa.nisn"
                            :label="siswa.nama"
                        ></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="Foto">
                    <input type="file" ref="inputFoto" @change="onFotoPicked" />
                    <el-image width="100%"></el-image>
                </el-form-item>
                <el-form-item label="Catatan">
                    <el-input
                        type="textarea"
                        v-model="jurnal.catatan"
                        placeholder="Catatan Pembelajaran"
                    ></el-input>
                </el-form-item>
            </el-form>
        </el-dialog>
    </div>
</template>
