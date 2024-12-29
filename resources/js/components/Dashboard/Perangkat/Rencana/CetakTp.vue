<script setup>
import { ref, computed, defineAsyncComponent, onMounted } from "vue";
import { Head, usePage, router } from "@inertiajs/vue3";
// import route from 'vendor/tightenco/ziggy/src/js';
import { Icon } from "@iconify/vue";
import dayjs from "dayjs";
import localeData from "dayjs/plugin/localeData";
import "dayjs/locale/id";
dayjs.locale("id");
dayjs.extend(localeData);

const page = usePage();
const props = defineProps({ elemens: Array });
const emit = defineEmits(["close"]);
const tanggal = ref(dayjs().format("YYYY-MM-DD"));

const datas = computed(() => {
    let results = [];
    props.elemens.forEach((el, e) => {
        let item = {
            kode: el.kode,
            label: el.label,
            fase: el.fase,
            deskripsi_cp: el.deskripsi_cp,
            tps:
                el.tps.length > 0 || el.tps
                    ? Object.groupBy(el.tps, (elm) => elm.materi)
                    : null,
        };
        results.push(item);
    });
    return results;
});

const params = computed(() => route().params);

const cetak = async () => {
    let lembar = document.querySelector(".cetak").outerHTML;
    let style = "";
    if (page.props.app_env == "local") {
        style = `<link href="stylesheet" href="${window.location.origin}:5173/resources/css/app.css" />`;
    } else {
        style = `<link href="stylesheet" href="${window.location.origin}/build/assets/app.css" /><link href="stylesheet" href="${window.location.origin}/build/assets/app2.css" />`;
    }
    let html = `
            <!doctype html>
            <html>
                <head>
                    <title>Cetak Tujuan Pembelajaran</title>
                    ${style}
                </head>
                <body>
                    ${lembar}    
                </body>
            </html>
    
    `;

    let win = window.open("", "_blank", "width=600,height=800");
    win.document.open();
    win.document.write(html);

    setTimeout(() => {
        win.print();
        // win.close()
    }, 1000);
};

onMounted(() => {
    cetak();
});
</script>

<template>
    <div>
        <Head>
            <title>
                Lembar Cetak Tujuan Pembelajaran Fase {{ props.fase }}
            </title>
        </Head>
        <el-card>
            <template #header>
                <div class="flex justify-between items-center print:hidden">
                    <span class="text-xl font-bold text-blue-900"
                        >Lembar Cetak Tujuan Pembelajaran Fase {{ params.fase }}
                    </span>
                    <div class="toolbar-items flex items-center gap-2">
                        <el-date-picker
                            v-model="tanggal"
                            format="DD-MM-YYYY"
                            value-format="YYYY-MM-DD"
                        ></el-date-picker>
                        <el-button
                            :native-type="null"
                            type="primary"
                            @click="cetak"
                            class="print:hidden"
                        >
                            <Icon icon="mdi:printer" />
                        </el-button>
                        <el-button
                            :native-type="null"
                            type="danger"
                            circle
                            @click="emit('close')"
                            class="print:hidden"
                        >
                            <Icon icon="mdi:close" />
                        </el-button>
                    </div>
                </div>
            </template>
            <div class="card-body cetak">
                <h3 class="text-xl font-bold text-blue-900 text-center mb-4">
                    Analisis Tujuan Pembelajaran Fase {{ params.fase }}
                </h3>
                <table>
                    <tr>
                        <td>Satuan Pendidikan</td>
                        <td>:</td>
                        <td>{{ page.props.sekolahs[0].nama }}</td>
                    </tr>
                    <tr>
                        <td>Penyusun</td>
                        <td>:</td>
                        <td>
                            {{ page.props.user.userable.nama }},
                            {{ page.props.user.userable.gelar_belakang }}
                        </td>
                    </tr>
                </table>
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="border border-black p-1 bg-slate-200">
                                Elemen
                            </th>
                            <th
                                class="border border-black p-1 bg-slate-200 w-[25%]"
                            >
                                Deskripsi CP
                            </th>
                            <th class="border border-black p-1 bg-slate-200">
                                Kompetensi
                            </th>
                            <th class="border border-black p-1 bg-slate-200">
                                Materi Inti
                            </th>
                            <th
                                class="border border-black p-1 bg-slate-200 w-[40%]"
                            >
                                Tujuan Pembelajaran
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="(el, e) in datas" :key="el.kode">
                            <tr
                                v-for="(key, t) in Object.keys(el.tps)"
                                :key="key"
                            >
                                <td
                                    class="border border-black px-1 align-top"
                                    v-if="e + t == e"
                                    :rowspan="
                                        e + t === e
                                            ? Object.keys(el.tps).length
                                            : ''
                                    "
                                >
                                    {{ el.label }}
                                </td>
                                <td
                                    class="border border-black px-1 align-top"
                                    v-if="e + t == e"
                                    :rowspan="
                                        e + t === e
                                            ? Object.keys(el.tps).length
                                            : ''
                                    "
                                >
                                    {{ el.deskripsi_cp }}
                                </td>
                                <td class="border border-black px-1 align-top">
                                    {{ el.tps[key][0].kompetensi }}
                                </td>
                                <td class="border border-black px-1 align-top">
                                    {{ key }}
                                </td>
                                <td class="border border-black px-1 align-top">
                                    <ul>
                                        <li
                                            v-for="(tp, t) in el.tps[key]"
                                            :key="tp.kode"
                                        >
                                            <span class="flex gap-2">
                                                <span
                                                    >{{
                                                        tp.kode.split("-")[1]
                                                    }}.</span
                                                >
                                                <span>{{ tp.teks }}</span>
                                            </span>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>

                <div class="ttd grid grid-cols-3 my-6">
                    <div>
                        <p>&nbsp;</p>
                        <p>Kepala Sekolah</p>

                        <p class="underline font-bold uppercase mt-14">
                            {{ page.props.sekolahs[0].nama_ks }},
                            {{ page.props.user.userable.gelar_belakang }}
                        </p>
                        <p>NIP. {{ page.props.sekolahs[0].nip_ks }}</p>
                    </div>
                    <div></div>
                    <div>
                        <p>
                            {{ page.props.sekolahs[0].desa }},
                            {{ dayjs(tanggal).format("D MMMM YYYY") }}
                        </p>
                        <p>Guru PAI</p>

                        <p class="underline font-bold uppercase mt-14">
                            {{ page.props.user.userable.nama }},
                            {{ page.props.user.userable.gelar_belakang }}
                        </p>
                        <p>NIP. {{ page.props.user.userable.nip }}</p>
                    </div>
                </div>
            </div>
        </el-card>
    </div>
</template>
