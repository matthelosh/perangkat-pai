<script setup>
import { ref, computed, onBeforeMount } from "vue";
import { usePage, Link, router } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";
import { writeFile, utils } from "xlsx";

const loading = ref(false);
const page = usePage();
const answers = ref([]);

const props = defineProps({ asesmen: Object, open: Boolean, rombel: Object });
const emit = defineEmits(["close"]);
const show = props.open;

const isLetter = (e, index) => {
    let char = String.fromCharCode(e.keyCode);
    if (/^[A-Da-d]+$/.test(char)) {
        if (answers.value[index].pgs.length >= props.asesmen.kunci.length) {
            e.preventDefault();
            alert("Jumlah Jawaban sudah penuh");
        }
        return true;
    } else {
        e.preventDefault();
        alert("Hanya boleh A|B|C|D");
    }
};

const isNumber = (e) => {
    // console.log(e);
    if (e.target.value > 1 || e.target.value < 0) {
        alert("Masukkan nilai desimal antara 0 sampai 1. Contoh: 0,7");
        e.target.focus();
        e.target.value = 0;
    }
};

const skor = (index) => {
    let res = [];
    let pg = 0;
    let skor = 0;
    let isian = answers.value[index].isians.reduce(
        (a, c) => a + parseFloat(c),
        0.0
    );
    let uraian = answers.value[index].uraians.reduce(
        (a, c) => a + parseFloat(c),
        0.0
    );
    for (let i = 0; i < props.asesmen.kunci.length; i++) {
        pg +=
            props.asesmen.kunci[i]?.toLowerCase() ==
            answers.value[index].pgs[i]?.toLowerCase()
                ? 1
                : 0;
    }

    let soals = props.asesmen.jml_soal.split(",");
    let max =
        parseInt(soals[0]) * 1 +
        parseInt(soals[1]) * 2 +
        parseInt(soals[2]) * 3;
    skor = pg + isian * 2 + uraian * 3;
    return parseFloat(((skor / max) * 100).toFixed(2));
    // return isian;
};

const cetak = () => {
    let win = window.open("", "_blank", "width=1200,height=900");

    const soals = props.asesmen.jml_soal.split(",");
    let pgTh = "";
    let isianTh = "";
    let uraianTh = "";
    let tr = "";

    answers.value.forEach((siswa, s) => {
        let pgTd = "";
        let isianTd = "";
        let uraianTd = "";
        for (
            let p = 0;
            p < parseInt(props.asesmen.jml_soal.split(",")[0]);
            p++
        ) {
            pgTd += `
                <td class="border text-center p-2 ${
                    siswa.pgs[p]?.toLowerCase() !==
                    props.asesmen.kunci[p]?.toLowerCase()
                        ? "bg-red-100 text-red-600"
                        : ""
                }" >
                ${siswa.pgs[p] ?? 0}
                </td>
            `;
        }

        for (
            let is = 0;
            is < parseInt(props.asesmen.jml_soal.split(",")[1]);
            is++
        ) {
            isianTd += `
                <td class="border text-center p-2">${
                    answers.value[s].isians[is + 1] ?? 0
                }</td>
            `;
            // isianTd += `<td class="border">${is}</td>`;
        }
        for (
            let ur = 0;
            ur < parseInt(props.asesmen.jml_soal.split(",")[2]);
            ur++
        ) {
            uraianTd += `
                <td class="border text-center p-2">${
                    answers.value[s].uraians[ur + 1] ?? 0
                }</td>
            `;
        }

        tr += `
            <tr>
                <td class="border p-2 text-center">${s + 1}</td>
                <td class="border p-2 text-center">${siswa.siswa_id}</td>
                <td class="border p-2 ">${siswa.nama}</td>
                <td class="border p-2 text-center">${siswa.jk}</td>
                ${pgTd}
                ${isianTd}
                ${uraianTd}
                <td class="border p-2 text-center">${skor(s)}</td>
            </tr>
        `;
    });

    for (let i = 0; i < parseInt(soals[0]); i++) {
        pgTh += `<th class="border">${i + 1}</th>`;
    }
    for (let i = 0; i < parseInt(soals[1]); i++) {
        isianTh += `<th class="border">${i + 1}</th>`;
    }
    for (let i = 0; i < parseInt(soals[2]); i++) {
        uraianTh += `<th class="border">${i + 1}</th>`;
    }

    let style =
        page.props.app_env == "local"
            ? `<link href="stylesheet" href="${window.location.origin}:5173/resources/css/app.css" />`
            : (style = `<link href="stylesheet" href="${window.location.origin}/build/assets/app.css" /><link href="stylesheet" href="${window.location.origin}/build/assets/app2.css" />`);

    let html = `
        <!doctype html>
        <html>
            <head>
                <title>${props.asesmen.label}</title>
                ${style}
            </head>
            <body>
                <h3 class="text-center">Hasil ${props.asesmen.label}</h3>

                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="border" rowspan="2">No</th>
                            <th class="border" rowspan="2">NISN</th>
                            <th class="border" rowspan="2">Nama</th>
                            <th class="border" rowspan="2">JK</th>
                            <th class="border" colspan="${parseInt(
                                props.asesmen.jml_soal.split(",")[0]
                            )}">Pilihan Ganda</th>
                            ${
                                props.asesmen.jml_soal.split(",")[1] > 0
                                    ? '<th class="border" colspan="' +
                                      props.asesmen.jml_soal.split(",")[1] +
                                      '">Isian</th>'
                                    : ""
                            }
                            ${
                                props.asesmen.jml_soal.split(",")[2] > 0
                                    ? '<th class="border" colspan="' +
                                      props.asesmen.jml_soal.split(",")[2] +
                                      '">Uraian</th>'
                                    : ""
                            }
                            <th class="border" rowspan="2">Skor</th>
                        </tr>
                        <tr>
                            ${pgTh}
                            ${isianTh}
                            ${uraianTh}
                        </tr>
                    </thead>
                    <tbody>
                        ${tr}
                    </tbody>
                </table>
            </body>
        </html>
    `;

    win.document.write(html);

    setTimeout(() => {
        win.print();
    }, 500);
};

const unduh = async () => {
    let res = [];
    answers.value.forEach((ans, a) => {
        let bio = {
            no: a + 1,
            nisn: ans.siswa_id,
            nama: ans.nama,
            jk: ans.jk,
        };

        let pg = [...ans.pgs].map((o, i) =>
            o == props.asesmen.kunci[i] ? 1 : 0
        );
        let r = [...pg, ...ans.isians, ...ans.uraians];
        let ana = r.reduce(
            (obj, value, index) => ({
                ...obj,
                ["s#" + (index + 1).toString()]: value,
            }),
            {}
        );
        let item = Object.assign({}, bio, ana, { skor: skor(a) });
        res.push(item);
    });

    // console.log(res);
    const ws = utils.json_to_sheet(res);
    const range = utils.decode_range(ws["!ref"]);
    for (let R = range.s.r; R <= range.e.r; ++R) {
        for (let C = range.s.c; C <= range.e.c; ++C) {
            const cellAddress = utils.encode_cell({ r: R, c: C });
            if (!ws[cellAddress]) continue;

            ws[cellAddress].s = {
                border: {
                    top: { style: "thin", color: { rgb: "000000" } },
                    bottom: { style: "thin", color: { rgb: "000000" } },
                    left: { style: "thin", color: { rgb: "000000" } },
                    right: { style: "thin", color: { rgb: "000000" } },
                },
            };
        }
    }

    const wb = utils.book_new();

    utils.book_append_sheet(wb, ws, ` ${props.rombel.label}`);

    writeFile(wb, `Analisis ${props.asesmen.label}.xlsx`, {
        compression: true,
    });
};

// Simpan Jawaban
const simpan = async () => {
    let datas = []; //siswa_id, asesmen_id, pgs, isians, uraians, skor
    answers.value.forEach((siswa, s) => {
        datas.push({
            siswa_id: siswa.siswa_id,
            asesmen_id: props.asesmen.id,
            pgs:
                siswa.pgs !== ""
                    ? siswa.pgs
                    : Array(parseInt(props.asesmen.jml_soal.split(",")[0]))
                          .fill("x")
                          .join(""),
            isians:
                siswa.isians.length > 0
                    ? siswa.isians.join(",")
                    : Array(parseInt(props.asesmen.jml_soal.split(",")[1]))
                          .fill(0)
                          .join(","),
            uraians:
                siswa.uraians.length > 0
                    ? siswa.uraians.join(",")
                    : Array(parseInt(props.asesmen.jml_soal.split(",")[2]))
                          .fill(0)
                          .join(","),
            skor: skor(s),
        });
    });
    // console.log(datas);
    router.post(
        route("perangkat.evaluasi.nilai.store", {
            _query: {
                tipe: props.asesmen.tipe,
                rombel_id: props.rombel.id,
            },
        }),
        { datas: datas },
        {
            onStart: () => (loading.value = true),
            onSuccess: () => {
                router.reload({ only: ["asesmen"] });
                init();
            },
            onError: (err) => {
                console.log(err);
            },
            onFinish: () => (loading.value = false),
        }
    );
};

const fileExcel = ref(null);
const impor = async () => {
    loading.value = true;
    fileExcel.value.click();

    fileExcel.value.onchange = () => {
        const files = fileExcel.value.files;

        if (files.length > 0) {
            console.log(files[0]);
        }
    };
};

const init = () => {
    if (props.asesmen.analises.length < 1) {
        let ans = [];
        props.rombel.siswas.forEach((siswa) => {
            ans.push({
                siswa_id: siswa.nisn,
                nama: siswa.nama,
                jk: siswa.jk,
                pgs: "",
                isians: [],
                uraians: [],
                resPgs: "",
                skor: 0,
            });
        });
        answers.value = ans;
    } else {
        let ans = [];
        props.asesmen.analises.forEach((nilai) => {
            ans.push({
                siswa_id: nilai.siswa_id,
                nama: nilai.siswa.nama,
                jk: nilai.siswa.jk,
                pgs: nilai.pgs.replaceAll(",", ""),
                isians: [...nilai.isians.split(",")].filter((n) => n !== ""),
                uraians: [...nilai.uraians.split(",")].filter((n) => n !== ""),
                resPgs: "",
            });
        });
        answers.value = ans;
    }
};

const goto = (link) => {
    router.get(route(link));
};

onBeforeMount(() => {
    init();
});
</script>

<template>
    <el-dialog
        fullscreen
        v-model="show"
        @close="emit('close')"
        :show-close="false"
    >
        <template #header>
            <div class="p-3 flex items-center justify-between bg-slate-400">
                <h3 class="text-white flex justify-between items-center gap-4">
                    <span class="flex items-center gap-2">
                        Analisis
                        {{ props.asesmen.label }}
                    </span>
                    <span
                        >Kunci Jawaban:
                        <span class="tracking-widest border p-2 rounded">
                            {{ props.asesmen.kunci.toUpperCase() }}
                        </span>
                    </span>
                </h3>
                <div class="tools">
                    <input
                        type="file"
                        ref="fileExcel"
                        accept=".xls, .xlsx, .ods"
                        class="hidden"
                    />
                    <el-button :native-type="null" @click="impor">
                        <Icon icon="mdi:file-upload" />
                        Impor
                    </el-button>
                    <el-button :native-type="null" @click="unduh">
                        <Icon icon="mdi:file-excel-box" />
                        Unduh
                    </el-button>
                    <el-button :native-type="null" @click="cetak">
                        <Icon icon="mdi:printer" />
                        Cetak
                    </el-button>
                    <el-button :native-type="null" @click="simpan">
                        <Icon icon="mdi:hdd" />
                        Simpan
                    </el-button>
                    <el-button
                        :native-type="null"
                        type="danger"
                        @click="emit('close')"
                    >
                        <Icon icon="mi:close" />
                    </el-button>
                </div>
            </div>
        </template>
        <el-row class="w-full">
            <el-col>
                <div class="p-3 bg-white">
                    <el-table :data="props.rombel.siswas" max-height="86vh">
                        <el-table-column
                            label="No"
                            type="index"
                            fixed="left"
                        ></el-table-column>
                        <el-table-column
                            label="NISN"
                            prop="nisn"
                            width="100"
                            fixed="left"
                        ></el-table-column>
                        <el-table-column
                            label="Nama"
                            prop="nama"
                            fixed="left"
                            width="300"
                        ></el-table-column>
                        <el-table-column
                            label="JK"
                            prop="jk"
                            width="100"
                        ></el-table-column>
                        <el-table-column
                            label="Pilihan Ganda (Bobot 1)"
                            #default="scope"
                            width="250"
                        >
                            <el-input
                                v-model="answers[scope.$index].pgs"
                                placeholder="Masukkan Jawaban Siswa"
                                @keypress="isLetter($event, scope.$index)"
                            >
                                <template #suffix
                                    >{{ answers[scope.$index].pgs.length }}
                                </template>
                            </el-input>
                        </el-table-column>
                        <el-table-column
                            label="Isian (Bobot 2)"
                            :width="
                                props.asesmen.jml_soal.split(',')[1] > 5
                                    ? '550'
                                    : '350'
                            "
                            #default="scope"
                        >
                            <div class="flex gap-1">
                                <el-input
                                    type="number"
                                    min="0"
                                    max="1"
                                    v-for="is in parseInt(
                                        props.asesmen.jml_soal.split(',')[1]
                                    )"
                                    v-model="
                                        answers[scope.$index].isians[is - 1]
                                    "
                                    @blur="isNumber($event)"
                                ></el-input>
                            </div>
                        </el-table-column>
                        <el-table-column
                            label="Uraian (Bobot 3)"
                            #default="scope"
                            :width="
                                props.asesmen.jml_soal.split(',')[2] > 5
                                    ? '550'
                                    : '300'
                            "
                        >
                            <div class="flex gap-1">
                                <el-input
                                    type="number"
                                    min="0"
                                    max="1"
                                    v-for="ur in parseInt(
                                        props.asesmen.jml_soal.split(',')[2]
                                    )"
                                    v-model="
                                        answers[scope.$index].uraians[ur - 1]
                                    "
                                    @blur="isNumber($event)"
                                ></el-input>
                            </div>
                        </el-table-column>
                        <el-table-column
                            label="Skor"
                            #default="scope"
                            fixed="right"
                            width="80"
                        >
                            {{ skor(scope.$index) }}
                        </el-table-column>
                    </el-table>
                </div>
            </el-col>
        </el-row>
    </el-dialog>
</template>

<style>
.el-dialog {
    padding: 0 !important;
}

.el-ialog__header {
    margin: 0 !important;
    padding: 0 !important;
}
</style>
