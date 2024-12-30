<script setup>
import { ref, computed, defineAsyncComponent, onBeforeMount } from "vue";
import { usePage, Head, router, Link } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";
import {
    Doc,
    Text,
    Paragraph,
    Color,
    Bold,
    Underline,
    Italic,
    BulletList,
    OrderedList,
    Strike,
    Highlight,
} from "element-tiptap-vue3-fixed";
import Layout from "@/layouts/DashboardLayout.vue";
import dayjs from "dayjs";
import localeData from "dayjs/plugin/localeData";
import "dayjs/locale/id";
dayjs.locale("id");
dayjs.extend(localeData);
import { ElNotification } from "element-plus";
import { effectiveOfYear } from "@/helpers/ApeHelper";

import { cssFiles } from "@/helpers/printHelper";
const page = usePage();
const tanggal = ref(dayjs().format("YYYY-MM-DD"));

const Kop = defineAsyncComponent(() => import("@/components/Umum/Kop.vue"));
const Ttd = defineAsyncComponent(() => import("@/components/Umum/Ttd.vue"));
const selectedMateri = ref(null);
const loading = ref(false);
const atps = computed(() =>
    page.props.atps.filter((at) => at.tingkat == atp.value.tingkat)
);
const mine = ref(true);
const tingkat = ref(null);
const kembali = () => {
    router.get(route("rencana"));
};
const extensions = [
    Doc,
    Text,
    Paragraph,
    Color.configure({ bubble: true }),
    Highlight.configure({ bubble: true }),
    Strike.configure({ bubble: true }),
    Bold.configure({ bubble: true }),
    Underline.configure({ bubble: true }),
    Italic.configure({ bubble: true }),
    BulletList,
    OrderedList,
];

const params = computed(() => route().params);
const onMineChanged = (e) => {
    // let params = route().params;
    // alert('tes')
    router.get(
        "/rencana/atp",
        { fase: params.value.fase, mine: mine.value },
        { preserveState: true, only: ["elemens"] }
    );

    // atps.value = page.props.atps
};

const cetak = async () => {
    let el = document.querySelector(".cetak");
    let html = `<html>
                <head>
                    <title>Alur Tujuan Pembelajaran</title>    
                </head>
                <body>
                    ${el.outerHTML}
                </body>
            </html>`;

    let win = window.open("", "_blank", "width=800,height=700");
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

const tps = computed(() => {
    let res = [];
    if (atp.value.elemen_id) {
        page.props.elemens.forEach((el) => {
            if (el.kode == atp.value.elemen_id) {
                res = el.tps.map((tp) => tp.teks);
            }
        });
    }

    return res;
});

const totalAw = computed(() => {
    let aws = atps.value.reduce((a, c) => a + parseInt(c.aw), 0);

    return aws;
});

// Form Atp
const showForm = ref(false);
const atp = ref({});
const tingkats = computed(() => {
    return params.value.fase == "C"
        ? ["5", "6"]
        : params.value.fase == "B"
        ? ["3", "4"]
        : ["1", "2"];
});

const addAtp = () => {
    if (selectedMateri.value !== null) {
        atp.value.materi =
            selectedMateri.value.bab + ", " + selectedMateri.value.label;
    }

    showForm.value = true;
};
const simpanAtp = async () => {
    loading.value = true;
    router.post(
        route("atp.store", { _query: { mine: mine.value } }),
        { data: atp.value },
        {
            onSuccess: (page) => {
                loading.value = false;
                atp.value = {
                    konten: null,
                    aw: 0,
                    tps: null,
                    tingkat: atp.value.tingkat,
                };
                showForm.value = false;
                ElNotification({
                    title: "Info",
                    message: "Alur TP disimpan",
                    type: "success",
                });
            },
            onError: (err) => {
                loading.value = false;
                console.log(err);
                ElNotification({
                    title: "Error",
                    message: err.message,
                    type: "error",
                });
            },
        }
    );
};

const edit = (item) => {
    atp.value = item;
    showForm.value = true;
};

const onAwChanged = async (e, item) => {
    if (e.target.innerText !== item.aw) {
        await router.patch(
            route("atp.update", { id: item.id }),
            { aw: e.target.innerText },
            {
                onSuccess: (page) => {
                    router.reload({ only: ["elemens"] });
                },
            }
        );
    }
};

const hapusSemua = async () => {
    await router.post(
        `/rencana/atp/destroy/all?fase=${params.value.fase}&mine=${params.value.mine}`,
        {
            onSuccess: (page) => {
                router.reload({ only: ["elemens"] });
            },
            onError: (err) => {
                console.log(err);
            },
        }
    );
};

const hapus = (item) => {
    router.delete(route("atp.destroy", { id: item.id }), {
        onSuccess: (page) => {
            ElNotification({
                title: "Info",
                message: "Alur TP disimpan",
                type: "success",
            });
        },
        onError: (err) => console.log(err),
    });
};

const onMateriChanged = (e) => {
    if (typeof e === "object") {
        atp.value.materi = e.bab + ". " + e.label;
    } else {
        atp.value.materi = e;
    }
};

const p5s = ref([
    "Beriman, bertakwa kepada Tuhan Yang Maha Esa, dan berakhlak mulia",
    "Mandiri",
    "Bergotong-royong",
    "Berkebhinekaan global",
    "Bernalar kritis",
    "Kreatif",
]);

onBeforeMount(() => {
    // atps.value = page.props.atps
    // mine.value = params.value.mine
    // parseTpAtp()
});
</script>

<template>
    <div title="Alur Tujuan Pembelajaran">
        <Head title="Alur Tujuan Pembelajaran" />

        <el-card class="relative">
            <template #header>
                <el-affix :offset="60">
                    <div
                        class="flex items-center justify-between sticky top-12 z-40 bg-white py-2"
                    >
                        <h3>Form Alur Tujuan Pembelajaran</h3>
                        <div class="items flex items-center gap-2">
                            <el-switch v-model="mine" @change="onMineChanged" />
                            <el-button :native-type="null" type="primary"
                                >{{ totalAw }} JP</el-button
                            >
                            <el-select
                                placeholder="Pilih Kelas"
                                v-model="atp.tingkat"
                            >
                                <el-option
                                    v-for="t in tingkats"
                                    :key="t"
                                    :value="t"
                                    :label="'Kelas ' + t"
                                />
                            </el-select>
                            <el-date-picker
                                v-model="tanggal"
                                format="DD-MM-YYYY"
                                value-format="YYYY-MM-DD"
                                placeholder="Tanggal dibuat"
                            />
                            <el-button
                                :native-type="null"
                                type="primary"
                                @click="cetak"
                            >
                                <Icon icon="mdi:printer" />
                            </el-button>
                            <el-button
                                :native-type="null"
                                circle
                                type="danger"
                                @click="kembali"
                            >
                                <Icon icon="mdi:close" />
                            </el-button>
                        </div>
                    </div>
                </el-affix>
            </template>
            <div class="card-body cetak" v-if="atp.tingkat">
                <Kop />
                <h1
                    class="text-lg text-blue-900 font-bold text-center mb-4 uppercase"
                >
                    Alur Tujuan Pembelajaran
                </h1>
                <table class="uppercase">
                    <tr>
                        <td>Mata pelajaran</td>
                        <td class="px-1">:</td>
                        <td>Pendidikan Agama Islam dan Budi Pekerti</td>
                    </tr>
                    <tr>
                        <td>Fase / Kelas</td>
                        <td class="px-1">:</td>
                        <td>{{ params.fase }} / {{ atp.tingkat }}</td>
                    </tr>
                    <tr>
                        <td>Satuan Pendidikan</td>
                        <td class="px-1">:</td>
                        <td>{{ page.props.sekolahs[0].nama }}</td>
                    </tr>
                </table>
                <div class="print:hidden">
                    <el-collapse>
                        <el-collapse-item>
                            <template #title>
                                <div class="print:hidden">
                                    Lihat Capaian Pembelajaran
                                </div>
                            </template>
                            <table class="w-full my-2">
                                <thead>
                                    <tr>
                                        <th
                                            class="border border-black p-2"
                                            rowspan="2"
                                        >
                                            Capaian Umum PAI
                                        </th>
                                        <th
                                            class="border border-black p-2"
                                            :colspan="page.props.elemens.length"
                                        >
                                            Capaian Per Elemen
                                        </th>
                                    </tr>
                                    <tr>
                                        <th
                                            class="border border-black p-2"
                                            v-for="(elemen, e) in page.props
                                                .elemens"
                                            :key="e"
                                        >
                                            {{ elemen.label }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td
                                            class="border border-black p-2 text-justify align-top"
                                        >
                                            {{ page.props.cp.teks }}
                                        </td>
                                        <td
                                            class="border border-black p-2 text-justify align-top"
                                            v-for="(elemen, e) in page.props
                                                .elemens"
                                            :key="e"
                                        >
                                            {{ elemen.deskripsi_cp }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </el-collapse-item>
                    </el-collapse>
                </div>
                <div
                    class="w-full flex h-10 bg-slate-100 justify-between items-center px-2 print:hidden"
                >
                    <!-- <el-switch v-model="mine" active-value="1" inactive-value="0" active-text="Punya saya" inactive-text="Dari sistem" @change="onMineChanged"></el-switch> -->
                    <div>Tambahkan ATP</div>
                    <div class="items flex items-center gap-2">
                        <el-popconfirm
                            :title="`Anda akan menghapus seluruh ATP dase ${params.fase}`"
                            @confirm="hapusSemua"
                        >
                            <template #reference>
                                <el-button :native-type="null" circle>
                                    <Icon
                                        icon="mdi:delete"
                                        class="text-red-500"
                                    />
                                </el-button>
                            </template>
                        </el-popconfirm>
                        <el-button :native-type="null" circle @click="addAtp">
                            <Icon icon="mdi:plus" />
                        </el-button>
                    </div>
                </div>
                <el-scrollbar>
                    <table class="my-2 w-full">
                        <thead>
                            <tr class="uppercase bg-slate-300">
                                <th class="border border-black p-2">Elemen</th>
                                <th class="border border-black p-2">
                                    Materi Ajar
                                </th>
                                <th class="border border-black p-2">Tingkat</th>
                                <th class="border border-black p-2">
                                    Semester
                                </th>
                                <th class="border border-black p-2">
                                    Alokasi Waktu
                                </th>
                                <th class="border border-black p-2">
                                    Tujuan Pembelajaran
                                </th>
                                <th class="border border-black p-2">
                                    Alur Konten (Lingkup Materi)
                                </th>
                                <th class="border border-black p-2">
                                    Dimensi P5
                                </th>
                                <th class="border border-black p-2">Asesmen</th>
                                <th class="border border-black p-2">
                                    Modul Ajar
                                </th>
                                <th
                                    class="border border-black p-2 print:hidden"
                                >
                                    Opsi
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- {{page.props.atps}} -->
                            <tr v-for="(atp, a) in atps" :key="a">
                                <td class="border border-black align-top p-2">
                                    {{ atp.elemen.label }}
                                </td>
                                <td class="border border-black align-top p-2">
                                    {{ atp.materi }}
                                </td>
                                <td
                                    class="border border-black align-top p-2 text-center"
                                >
                                    {{ atp.tingkat }}
                                </td>
                                <td
                                    class="border border-black align-top p-2 text-center"
                                >
                                    {{ atp.semester }}
                                </td>
                                <td
                                    class="border border-black align-top p-2 text-center"
                                >
                                    {{ atp.aw }}
                                </td>
                                <td class="border border-black align-top p-2">
                                    <ul class="list-disc pl-4" v-if="atp.tps">
                                        <li
                                            v-for="(tp, t) in atp.tps.split(
                                                ';'
                                            )"
                                            :key="t + 'tp'"
                                        >
                                            {{ tp }}
                                        </li>
                                    </ul>
                                    <p v-else>Asesmen Sumatif</p>
                                </td>
                                <td class="border border-black align-top p-2">
                                    <div v-if="atp.konten !== ''">
                                        <ul class="pl-4 list-disc">
                                            <li
                                                v-for="(
                                                    kont, k
                                                ) in typeof atp.konten ===
                                                'string'
                                                    ? atp.konten.split(';')
                                                    : atp.konten"
                                                :key="k"
                                            >
                                                {{ kont }}
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                                <td class="border border-black align-top p-2">
                                    <div v-if="atp.p5 !== ''">
                                        <ul class="pl-4 list-disc">
                                            <li
                                                v-for="(
                                                    p5, p
                                                ) in typeof atp.p5 === 'string'
                                                    ? atp.p5.split(';')
                                                    : atp.p5"
                                                :key="p"
                                            >
                                                {{ p5 }}
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                                <td
                                    class="border border-black pl-6 py-2 align-top"
                                >
                                    <span
                                        v-html="atp.asesmen"
                                        class="asesmen"
                                    ></span>
                                </td>
                                <td
                                    class="border border-black pl-6 py-2 align-top"
                                >
                                    <ul>
                                        <li v-for="ma in atp.mas">
                                            Modul Ajar {{ ma.id }}
                                        </li>
                                    </ul>
                                </td>
                                <td
                                    class="border border-black px-2 print:hidden text-center"
                                >
                                    <div class="flex items-center gap-1">
                                        <Link
                                            :href="`https://perangkat.test/rencana/modulajar?fase=A&tingkat=${atp.tingkat}&mine=true`"
                                            class="flex items-center gap-1 text-sky-500 mr-2"
                                        >
                                            <Icon icon="mdi:plus" />
                                            MA
                                        </Link>
                                        <el-button
                                            :native-type="null"
                                            circle
                                            text
                                            class="p-2"
                                            type="warning"
                                            @click="edit(atp)"
                                        >
                                            <Icon
                                                icon="mdi:edit"
                                                class="text-xl"
                                            />
                                        </el-button>
                                        <el-popconfirm
                                            title="Yakin menghapus ATP ini?"
                                            @confirm="hapus(atp)"
                                        >
                                            <template #reference>
                                                <el-button
                                                    :native-type="null"
                                                    circle
                                                    text
                                                    class="p-2"
                                                    type="danger"
                                                >
                                                    <Icon
                                                        icon="mdi:delete"
                                                        class="text-xl"
                                                    />
                                                </el-button>
                                            </template>
                                        </el-popconfirm>
                                    </div>
                                </td>
                            </tr>
                            <!-- <template v-for="(el,e) in page.props.elemens" :key="e">
                            <tr v-for="(atp,a) in el.atps" :key="atp.kode">
                                <td class="border border-black p-2" v-if="(e+a===e)" :rowspan="(e+a===e) ? el.atps.length : '0'">{{ el.label }}</td>
                                <td class="border border-black p-2 align-top">{{ atp.materi }}</td>
                                <td class="border border-black p-2 text-center align-top">{{ atp.tingkat }}</td>
                                <td class="border border-black p-2 text-center align-top">{{ atp.semester }}</td>
                                <td class="border border-black p-2 text-center align-top"><span @blur="onAwChanged($event, atp)" class="px-2 bg-yellow-100 print:bg-white" contenteditable>{{ atp.aw }}</span> JP</td>
                                <td class="border border-black p-2 align-top">
                                    <div v-if="atp.tps !== ''">
                                        <ul class="pl-4 list-decimal">
                                            <li v-for="(tp,t) in ((typeof atp.tps === 'string') ? atp.tps.split(';') : atp.tps)" :key="t">{{ tp }}</li>
                                        </ul>
                                    </div>
                                    <p v-else>Asesmen Sumatif</p>
                                </td>
                                <td class="border border-black p-2 align-top">
                                    <div v-if="atp.konten !== ''">
                                        <ul class="pl-4 list-disc">
                                            <li v-for="(kont,k) in ((typeof atp.konten === 'string') ? atp.konten.split(';') : atp.konten)" :key="k">{{ kont }}</li>
                                        </ul>
                                    </div>
                                </td>
                                <td class="border border-black p-2 align-top">
                                    <span v-html="atp.asesmen" class="asesmen"></span>
                                </td>
                                <td class="border border-black px-2 print:hidden text-center">
                                    <div class="flex items-center gap-1">
                                        <el-button :native-type="null"  circle text class="p-2" type="warning" @click="edit(atp)">
                                            <Icon icon="mdi:edit" class="text-xl" />
                                        </el-button>
                                        <el-popconfirm title="Yakin menghapus ATP ini?" @confirm="hapus(atp)">
                                            <template #reference>
                                                <el-button :native-type="null"  circle text class="p-2" type="danger" >
                                                    <Icon icon="mdi:delete" class="text-xl" />
                                                </el-button>
                                            </template>
                                        </el-popconfirm>
                                    </div>
                                </td>
                            </tr>
                        </template> -->
                            <tr class="bg-slate-300">
                                <td
                                    colspan="4"
                                    class="border border-black text-right p-2 font-bold"
                                >
                                    Total Alokasi Waktu
                                </td>
                                <td
                                    class="border border-black p-2 text-center font-bold"
                                >
                                    {{ totalAw }} JP <br />
                                    ({{ totalAw / 4 }} TM)
                                </td>
                                <td
                                    class="border border-black p-2 text-center font-bold"
                                ></td>
                                <td
                                    class="border border-black p-2 text-center font-bold"
                                ></td>
                                <td
                                    class="border border-black p-2 text-center font-bold"
                                ></td>
                                <td
                                    class="border border-black p-2 text-center font-bold"
                                ></td>
                                <td
                                    class="border border-black p-2 text-center font-bold"
                                ></td>
                                <td
                                    class="border border-black p-2 text-center font-bold print:hidden"
                                ></td>
                            </tr>
                        </tbody>
                    </table>
                </el-scrollbar>
                <Ttd :tanggal="tanggal" />
            </div>
            <el-alert type="warning" v-else>
                <template #title>
                    <div class="flex gap-1 items-center">
                        <Icon
                            icon="mdi:alert-box"
                            class="text-red-400 text-2xl"
                        />
                        <h3 class="font-semibold">Pilih Kelas Dulu</h3>
                    </div>
                </template>
            </el-alert>
        </el-card>

        <el-dialog v-model="showForm" draggable>
            <template #header>
                <h3>Tambahkan Tujuan Pembelajaran {{ atp.elemen_id }}</h3>
            </template>
            <div class="dialog-body">
                <el-form v-model="atp" label-position="top">
                    <el-row :gutter="20">
                        <el-col :span="12">
                            <el-form-item label="Elemen">
                                <el-select
                                    v-model="atp.elemen_id"
                                    placeholder="Pilih Elemen"
                                >
                                    <el-option
                                        v-for="(el, e) in page.props.elemens"
                                        :key="el.kode"
                                        :value="el.kode"
                                        >{{ el.label }}</el-option
                                    >
                                </el-select>
                            </el-form-item>
                        </el-col>
                        <el-col :span="6">
                            <el-form-item label="Tingkat">
                                <el-select
                                    v-model="atp.tingkat"
                                    placeholder="Pilih Tingkat"
                                >
                                    <el-option
                                        v-for="(tingkat, t) in tingkats"
                                        :key="tingkat"
                                        :value="tingkat"
                                        >{{ tingkat }}</el-option
                                    >
                                </el-select>
                            </el-form-item>
                        </el-col>
                        <el-col :span="6">
                            <el-form-item label="Semester">
                                <el-select
                                    v-model="atp.semester"
                                    placeholder="Pilih Semester"
                                >
                                    <el-option
                                        v-for="(sem, t) in ['1', '2']"
                                        :key="t"
                                        :value="sem"
                                        >{{ sem }}</el-option
                                    >
                                </el-select>
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-row :gutter="20">
                        <el-col :span="24">
                            <el-form-item label="Materi Ajar">
                                <el-select
                                    v-model="selectedMateri"
                                    placeholder="Pilih Materi/Bab"
                                    value-key="id"
                                    @change="onMateriChanged"
                                    filterable
                                >
                                    <el-option
                                        :value="`Asesmen Sumatif ${selectedMateri?.bab}`"
                                        >Asesmen Sumatif
                                        {{ selectedMateri?.bab }}</el-option
                                    >
                                    <el-option
                                        v-for="(
                                            materi, m
                                        ) in page.props.babs.filter(
                                            (bab) => bab.tingkat == atp.tingkat
                                        )"
                                        :key="materi.id"
                                        :value="materi"
                                        :label="`${materi.bab}. ${materi.label} (Kelas ${materi.tingkat})`"
                                    />
                                </el-select>
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-row v-if="tps.length > 0">
                        <el-col :span="19">
                            <el-form-item label="Konten Materi">
                                <el-select
                                    v-model="atp.konten"
                                    value-key="kode"
                                    v-if="
                                        selectedMateri && selectedMateri.kontens
                                    "
                                    multiple
                                    placeholder="Bisa pilih lebih dari satu"
                                >
                                    <el-option
                                        v-for="(
                                            konten, k
                                        ) in selectedMateri.kontens"
                                        :key="k"
                                        :value="konten.label"
                                        >{{
                                            k + 1 + ". " + konten.label
                                        }}</el-option
                                    >
                                </el-select>
                            </el-form-item>
                        </el-col>
                        <el-col :span="5">
                            <el-form-item label="AW/JP">
                                <el-input
                                    type="number"
                                    placeholder="AW"
                                    v-model="atp.aw"
                                />
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-row v-if="tps.length > 0">
                        <el-col :span="24">
                            <el-form-item label="Tujuan Pembelajaran">
                                <el-select
                                    v-model="atp.tps"
                                    placeholder="Bisa pilih lebih dari satu"
                                    multiple
                                    filterable
                                >
                                    <el-option
                                        v-for="(tp, t) in tps"
                                        :key="t"
                                        :value="tp"
                                        >{{ t + 1 + ". " + tp }}</el-option
                                    >
                                </el-select>
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-row v-if="tps.length > 0">
                        <el-col :span="24">
                            <el-form-item label="Dimensi P5">
                                <!-- <el-input type="textarea" v-model="atp.asesmen" placeholder="Asesmen" /> -->
                                <el-select
                                    v-model="atp.p5"
                                    placeholder="Bisa pilih lebih dari satu"
                                    multiple
                                    filterable
                                >
                                    <el-option
                                        v-for="(p5, p) in p5s"
                                        :key="p"
                                        :value="p5"
                                        >{{ p + 1 + ". " + p5 }}</el-option
                                    >
                                </el-select>
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-row v-if="tps.length > 0">
                        <el-col :span="24">
                            <el-form-item label="Asesmen">
                                <!-- <el-input type="textarea" v-model="atp.asesmen" placeholder="Asesmen" /> -->
                                <el-tiptap
                                    v-model:content="atp.asesmen"
                                    :extensions="extensions"
                                />
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-row justify="center">
                        <el-button
                            :native-type="null"
                            type="primary"
                            @click="simpanAtp"
                            :loading="loading"
                            >Simpan</el-button
                        >
                    </el-row>
                </el-form>
            </div>
        </el-dialog>
    </div>
</template>

<style>
.el-tiptap-popper {
    z-index: 99999 !important;
}
.konten ul,
.asesmen ul {
    list-style-type: decimal;
}
</style>
