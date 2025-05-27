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
import { ElMessage, ElMessageBox, ElNotification } from "element-plus";
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

const showCetak = ref(false);

const cetak = async () => {
    let el = document.querySelector(".cetak");
    let trs = "";
    atps.value.forEach((atp, a) => {
        trs += `
                <tr>
                    <td class="border border-black align-top p-2">
                                    ${atp.elemen.label}
                                </td>
                                <td class="border border-black align-top p-2">
                                    ${atp.materi}
                                </td>
                                <td
                                    class="border border-black align-top p-2 text-center"
                                >
                                    ${atp.tingkat}
                                </td>
                                <td
                                    class="border border-black align-top p-2 text-center"
                                >
                                    ${atp.semester}
                                </td>
                                <td
                                    class="border border-black align-top p-2 text-center"
                                >
                                    ${atp.aw}
                                </td>
                                <td class="border border-black align-top p-2">
                                    ${atp.tps ? atp.tps : "Asesmen Sumatif"}
                                </td>
                                <td class="border border-black align-top p-2">
                                    ${atp.konten}
                                </td>
                                <td class="border border-black align-top p-2">
                                    ${atp.p5}
                                </td>
                                <td
                                    class="border border-black pl-6 py-2 align-top"
                                >
                                    ${atp.asesmen}
                                </td>
                                <td
                                    class="border border-black pl-6 py-2 align-top"
                                >
                                    ${atp.mas?.kode ?? ""}
                                </td>
                </tr>
        `;
    });

    let html = `<html>
                    <head>
                        <title>Alur Tujuan Pembelajaran</title>
                        <style>
                            .hidden-print {
                                display: none;
                            }
                        </style>
                    </head>
                    <body>
                        <h3 class="text-center text-blue-800 font-bold tracking-wide uppercase">Alur Tujuan Pembelajaran</h3>
                        <table class="uppercase my-8">
                            <tbody>
                                <tr>
                                    <td>Mata Pelajaran</td>
                                    <td>:</td>
                                    <td>Pendidikan Agama Islam dan Budi Pekerti </td>
                                </tr>
                                <tr>
                                    <td>Fase / Kelas</td>
                                    <td>:</td>
                                    <td>${params.value.fase} / ${
        atp.value.tingkat
    }</td>
                                </tr>
                                <tr>
                                    <td>Satuan Pendidikan</td>
                                    <td>:</td>
                                    <td>${page.props.sekolahs[0].nama} </td>
                                </tr>
                            </tbody>
                        </table>

                        <table class="border">
                            <thead>
                                <tr>
                                    <th class="border border-black p-2">Elemen</th>
                                    <th class="border border-black p-2">Materi</th>
                                    <th class="border border-black p-2">Kelas</th>
                                    <th class="border border-black p-2">Semester</th>
                                    <th class="border border-black p-2">AW</th>
                                    <th class="border border-black p-2">Tujuan Pembelajaran</th>
                                    <th class="border border-black p-2">Konten Materi</th>
                                    <th class="border border-black p-2">Dimensi P5</th>
                                    <th class="border border-black p-2">Asesmen</th>
                                    <th class="border border-black p-2">Modul Ajar</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${trs}
                            </tbody>
                        </table>

                        <div class="mt-6 my-4 w-full flex items-start justify-between">
                                <div>
                                    <p>&nbsp;</p>
                                    <p>Kepala Sekolah,</p>
                                    <p class="mt-10 font-bold underline">${
                                        page.props.sekolahs[0].nama_ks
                                    }</p>
                                    <p>NIP. ${page.props.sekolahs[0].nip_ks}</p>
                                </div>
                                <div>
                                    <p>Wagir, ${new Date(
                                        tanggal.value
                                    ).toLocaleDateString("id-ID", {
                                        day: "2-digit",
                                        month: "long",
                                        year: "numeric",
                                    })}</p>
                                    <p>Guru PAI Bp</p>
                                    <p class="mt-10 font-bold underline">${
                                        page.props.user.userable.nama
                                    }, ${
        page.props.user.userable.gelar_belakang
    }</p>
                                    <p>NIP. ${page.props.user.userable.nip}</p>
                                </div>
                        </div>
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
        showCetak.value = false;
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
    let data = item;
    // Extract the chapter number and label from item.materi
    const [chapter, ...labelParts] = item.materi.split(", ");
    const label = labelParts.join(", "); // rejoin in case label contains commas
    let mate = item.materi.split(".");

    // Find the material by matching both chapter and label
    const materi = page.props.babs.find((bab) => bab.bab === mate[0]);
    console.log(materi);
    if (materi) {
        data.materi = materi.bab + ". " + materi.label;
    } else if (item.materi.startsWith("Asesmen Sumatif")) {
        // Handle Asesmen Sumatif case
        data.materi = item.materi;
    }

    data.tps = data.tps
        ? Array.isArray(data.tps)
            ? data.tps
            : data.tps.split(";")
        : [];
    data.konten = data.konten
        ? Array.isArray(data.konten)
            ? data.konten
            : data.konten.split(";")
        : [];
    data.p5 = data.p5
        ? Array.isArray(data.p5)
            ? data.p5
            : data.p5.split(";")
        : [];

    atp.value = data;
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
    ElMessageBox.confirm(
        "Prota, Prosem dan Modul Ajar dari ATP ini akan dihapus semua",
        "Peringatan",
        {
            confirmButtonText: "Yakin",
            cancelButtonText: "Batal",
            type: "warning",
        }
    )
        .then(async () => {
            await router.post(
                `/rencana/atp/destroy/all?fase=${params.value.fase}&mine=${params.value.mine}&tingkat=${tingkat.value}`,
                {
                    onSuccess: () => {
                        router.reload({ only: ["elemens"] });
                        ElMessage({
                            type: "success",
                            message: "Data ATP dihapus semua",
                        });
                    },
                    onError: (err) => {
                        console.log(err);
                        ElMessage({
                            type: "error",
                            message: "Gagal Menghapus Semua",
                        });
                    },
                }
            );
        })
        .catch((err) => {
            ElMessage({
                type: "error",
                message: "Gagal menghapus semua",
            });
        });
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
                        <div class="flex gap-1 items-center">
                            <el-button text @click="kembali">
                                <Icon icon="mdi:arrow-left" class="text-2xl" />
                            </el-button>
                            <h3 class="font-bold text-blue-800 text-lg">
                                Form Alur Tujuan Pembelajaran
                            </h3>
                        </div>
                        <div class="items flex items-center gap-2">
                            <!-- <el-switch v-model="mine" @change="onMineChanged" /> -->
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
                        </div>
                    </div>
                </el-affix>
            </template>
            <div class="card-body cetak" v-if="atp.tingkat">
                <Kop v-if="showCetak" />
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
                <el-affix :offset="60">
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
                            <el-button
                                :native-type="null"
                                circle
                                @click="addAtp"
                            >
                                <Icon icon="mdi:plus" />
                            </el-button>
                        </div>
                    </div>
                </el-affix>
                <el-table :data="atps" :border="true" stripe>
                    <el-table-column
                        label="#"
                        type="index"
                        width="50"
                    ></el-table-column>
                    <el-table-column label="Elemen" width="150">
                        <template #default="{ row }">
                            {{ row.elemen.label }}
                        </template>
                    </el-table-column>
                    <el-table-column label="Materi" width="200">
                        <template #default="{ row }">
                            {{ row.materi }}
                        </template>
                    </el-table-column>
                    <el-table-column label="Kelas" width="50">
                        <template #default="{ row }">
                            {{ row.tingkat }}
                        </template>
                    </el-table-column>
                    <el-table-column label="Sem" width="50">
                        <template #default="{ row }">
                            {{ row.semester }}
                        </template>
                    </el-table-column>
                    <el-table-column label="AW" width="50">
                        <template #default="{ row }">
                            {{ row.aw }} JP
                        </template>
                    </el-table-column>

                    <el-table-column label="Tujuan Pembelajaran">
                        <template #default="{ row }">
                            <ul class="list-disc pl-4" v-if="row.tps">
                                <li
                                    v-for="(tp, t) in Array.isArray(row.tps)
                                        ? row.tps
                                        : row.tps.split(';')"
                                    :key="t + 'tp'"
                                >
                                    {{ tp }}
                                </li>
                            </ul>
                            <p v-else>Asesmen Sumatif</p>
                        </template>
                    </el-table-column>
                    <el-table-column label="Konten" width="200">
                        <template #default="{ row }">
                            <div v-if="row.konten !== ''">
                                <ul class="pl-4 list-disc">
                                    <li
                                        v-for="(
                                            kont, k
                                        ) in typeof row.konten === 'string'
                                            ? row.konten.split(';')
                                            : row.konten"
                                        :key="k"
                                    >
                                        {{ kont }}
                                    </li>
                                </ul>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column label="Dimensi P5" width="200">
                        <template #default="{ row }">
                            <div v-if="row.p5 !== ''">
                                <ul class="pl-4 list-disc">
                                    <li
                                        v-for="(p5, p) in typeof row.p5 ===
                                        'string'
                                            ? row.p5.split(';')
                                            : row.p5"
                                        :key="p"
                                    >
                                        {{ p5 }}
                                    </li>
                                </ul>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column label="Asesmen" width="80">
                        <template #default="{ row }">
                            <span v-html="row.asesmen" class="asesmen"></span>
                        </template>
                    </el-table-column>
                    <el-table-column label="Modul Ajar" width="200">
                        <template #default="{ row }">
                            <ul>
                                <li v-for="ma in row.mas">
                                    Modul Ajar {{ ma.kode }}
                                </li>
                            </ul>
                        </template>
                    </el-table-column>
                    <el-table-column
                        v-if="!showCetak"
                        label="Opsi"
                        class-name="hidden-print"
                        width="150"
                    >
                        <template #default="{ row }">
                            <div class="flex items-center gap-1">
                                <Link
                                    :href="`/rencana/modulajar?fase=A&tingkat=${row.tingkat}&mine=true`"
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
                                    @click="edit(row)"
                                >
                                    <Icon icon="mdi:edit" class="text-xl" />
                                </el-button>
                                <el-popconfirm
                                    title="Yakin menghapus ATP ini?"
                                    @confirm="hapus(row)"
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
                        </template>
                    </el-table-column>
                </el-table>
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
