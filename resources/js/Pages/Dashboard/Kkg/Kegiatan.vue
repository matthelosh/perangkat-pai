<script setup>
import { computed, ref } from "vue";
import { router, usePage, Head } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";

import Layout from "@/layouts/DashboardLayout.vue";
import { ElNotification } from "element-plus";
import { terbilang } from "@/helpers/misc";
import { cssFiles } from "@/helpers/printHelper.js";
const page = usePage();

const loading = ref(false);

const daftarHadir = (item) => {
    let win = window.open("", "_blank", "width=1200,height=900");
    let tr = "";
    page.props.gurus.forEach((guru, g) => {
        tr += `<tr>
                <td style="text-align:center;">${g + 1}</td>
                <td>${guru.nama}</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>`;
    });

    for (let i = 0; i < 6; i++) {
        tr += `<tr>
            <td style="text-align:center;">${
                i + (page.props.gurus.length + 1)
            }</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
        </tr>`;
    }

    let html = `<!doctype html>
            <html>
                <head>
                    <title>Daftar Hadir ${item.label}</title>  
                    <style>
                        th,td {
                            padding:20px;
                        }
                    </style>  
                </head>
                <body>
                    <h3 style="text-align:center;">DAFTAR HADIR RAPAT</h3>   
                    <h3 style="text-align:center;">KKG PAI KECAMATAN WAGIR</h3> 
                    
                    <p>Tanggal: ${new Date().toLocaleDateString({
                        date: "numeric",
                        month: "long",
                        year: "numeric",
                    })}</p>
                    <table border style="border-collapse: collapse;">
                        <thead>
                            <tr>
                                <th>No</th>    
                                <th>Nama</th>    
                                <th>Lembaga</th>    
                                <th>Jabatan</th>    
                                <th>Tanda Tangan</th>    
                            </tr>    
                        </thead>    
                        <tbody>
                            ${tr}
                        </tbody>
                    </table> 

                    <div style="display:flex; justify-content: flex-end; margin-top: 60px;">
                        <div>
                            <p>Wagir, ${new Date().toLocaleDateString()}</p>
                            <p>Ketua KKG PAI Kec. Wagir</p>
                            <p style="margin-top: 60px;">Saiful Arif, S.Ag., M. Pd.</p>
                            </div>
                    </div>
                </body>
            </html>
    `;
    win.document.write(html);

    setTimeout(() => {
        win.print();
    }, 1000);
};

const showFormKegiatan = ref(false);
const formKegiatanTitle = ref("Kegiatan Baru");
const newKegiatan = () => {
    showFormKegiatan.value = true;
};

// Kegiatan
const kegiatan = ref({
    tapel_id: page.props.tapel.kode,
    label: "",
    mulai: "",
    selesai: "",
    deskripsi: "",
    is_libur: false,
    warna: "green",
    pelaksana: "kkg",
});

const saveKegiatan = async () => {
    router.post(
        route("kaldik.store"),
        { data: kegiatan.value },
        {
            onStart: () => (loading.value = true),
            onSuccess: () => {
                ElNotification({
                    title: "Info",
                    message: "Data Kegiatan Disimpan",
                    type: "success",
                });
                router.reload({ only: ["kegiatans"] });
                kegiatan.value.label = "";
                kegiatan.value.mulai = "";
                kegiatan.value.selesai = "";
                kegiatan.value.deskripsi = "";
            },
            onFinish: () => (loading.value = false),
        }
    );
};

const editKegiatan = (item) => {
    kegiatan.value = item;
    showFormKegiatan.value = true;
};

const deleteKegiatan = async () => {};

// Kuitansi
const showKuitansi = ref(false);
const openKuitansi = (item) => {
    kegiatan.value = item;
    showKuitansi.value = true;
};
const formKuitansi = ref(false);
const kuitansi = ref({
    no: "",
    tanggal: "",
    jumlah: "",
});
const makeKuitansi = async () => {
    let item = kuitansi.value;
    (item.kegiatan_id = kegiatan.value.id),
        (item.terbilang = terbilang(item.jumlah));
    // console.log(item);
    router.post(route("kkg.kegiatan.kuitansi.store"), kuitansi.value, {
        onStart: () => (loading.value = true),
        onSuccess: () => {
            ElNotification({
                ttitle: "Info",
                message: "Kuitansi disimpan",
                type: "success",
            });
            router.reload({ only: ["kegiatans"] });
        },
        onFinish: () => (loading.value = false),
    });
};

const printKuitansis = async () => {
    let win = window.open("", "_blank", "width=800,height=1200");
    let pages = Math.ceil(kegiatan.value.kuitansis.length / 2);
    let laman = "";
    for (let p = 0; p < pages; p++) {
        let items = "";
        kegiatan.value.kuitansis.forEach((item, k) => {
            if (k - p === p - 1 || k / 2 === p) {
                items += `
                    <div class="wrapper border border-dashed border-black my-4 break-inside-avoid">
                    <div class="kop grid grid-cols-6 border-b border-b-4 border-b-double border-b-black p-4">
                        <div class="logo col-span-1">
                            <img src="/img/logo_kkg.png" class="w-[70px]" >    
                        </div>    
                        <div class="identitas col-span-4 text-center uppercase">
                            <h3 class="text-lg font-bold">${
                                kegiatan.value.label
                            }</h3>    
                            <h2 class="text-lg font-bold">Kelompok Kerja Guru-PAI Kecamatan Wagir</h2>
                            <h2 class="text-lg font-bold">TAHUN ${
                                page.props.tapel.label
                            }</h2>
                        </div>
                    </div>
                    <div class="content">
                        <div class="meta my-6">
                            <h2 class="text-center text-2xl font-bold">Kwitansi</h2>    
                            <p class="mx-auto text-center">No.: ${
                                item.no
                            }/KKG-PAI/${new Date().getFullYear()}</p>
                        </div>   
                        <table class="border mx-auto text-lg">
                            <tbody>
                                <tr>
                                    <td class="border-b py-2 px-1">Sudah Terima dari</td>    
                                    <td class="border-b py-2 px-1">:</td>
                                    <td class="border-b py-2 px-1">${
                                        item.dari
                                    }</td>
                                </tr>    
                                <tr>
                                    <td class="border-b py-2 px-1">Banyaknya Uang</td>
                                    <td class="border-b py-2 px-1">:</td>
                                    <td class="border-b py-2 px-1">
                                        <p class="p-2 bg-slate-100 font-black tetx-lg">
                                            ${item.terbilang} Rupiah
                                            </p>
                                     </td>
                                </tr>
                                <tr>
                                    <td class="border-b py-2 px-1">Untuk Pembayarabn</td>    
                                    <td class="border-b py-2 px-1">:</td>
                                    <td class="border-b py-2 px-1">Pendaftaran ${
                                        kegiatan.value.label
                                    }</td>
                                </tr>
                                <tr>
                                    <td class="border-b py-2 px-1">Jumlah</td>    
                                    <td class="border-b py-2 px-1">:</td>
                                    <td class="border-b py-2 px-1">
                                        <p class="border border-4 border-black font-black text-right">
                                            ${item.jumlah.toLocaleString(
                                                "id-ID",
                                                {
                                                    style: "currency",
                                                    currency: "IDR",
                                                }
                                            )}
                                            </p>
                                    </td>
                                </tr>
                            </tbody>    
                        </table> 
                    </div>
                    <div class="ttd  mt-8 mb-3 flex justify-end w-[90%]">
                        <div class="relative">
                            <p>Malang, ${new Date(
                                item.tanggal
                            ).toLocaleDateString("id-ID", {
                                day: "2-digit",
                                month: "short",
                                year: "numeric",
                            })}</p> 
                            <p>Bendahara,</p>   

                            <img src="/ttd/${
                                page.props.penguruses.filter(
                                    (i) => i.nama == "Bendahara I"
                                )[0].guru?.nip
                            }.png" class="absolute h-[125px] -translate-y-6" />
                            <img src="/img/stempel.png" class="absolute  -translate-y-10 -translate-x-[80px] mix-blend-multiply h-[135px]"  />
                            <p class="underline font-bold uppercase tracking-wider mt-16">${
                                page.props.penguruses.filter(
                                    (i) => i.nama == "Bendahara I"
                                )[0].guru?.nama ?? ".........................."
                            }</p>
                            <p>NIP. ${
                                page.props.penguruses.filter(
                                    (i) => i.nama == "Bendahara I"
                                )[0].guru?.nip ?? ".........................."
                            }</p>

                        </div>
                    </div>
                </div>
                `;
            }
        });

        laman += items;
    }

    let html = `
        <!doctype html>
        <html>
            <head>
                <title>Cetak Kuitansi ${kegiatan.value.label}</title>
            </head>
            <body>${laman}</body>
        </html>
    `;
    win.document.write(html);
    const styles = await cssFiles();
    styles.forEach((css) => {
        const link = win.document.createElement("link");
        link.rel = "stylesheet";
        link.href = css;
        win.document.head.append(link);
    });
    win.document.close();
    setTimeout(() => {
        win.print();
        // win.close();
    }, 1000);
};

const printOne = async (item) => {
    // console.log(page.props.penguruses.filter((it) => it.nama == "Ketua"));
    let win = window.open("", "_blank", "width=800,height=1200");

    let html = `
        <!doctype html>
        <html>
            <head>
                <title>Kuitansi Pembayaran Kegiatan ${
                    kegiatan.value.label
                }</title>
            </head>
            <body>
                <div class="wrapper border border-dashed border-black">
                    <div class="kop grid grid-cols-6 border-b border-b-4 border-b-double border-b-black p-4">
                        <div class="logo col-span-1">
                            <img src="/img/logo_kkg.png" class="w-[70px]" >    
                        </div>    
                        <div class="identitas col-span-4 text-center uppercase">
                            <h3 class="text-lg font-bold">${
                                kegiatan.value.label
                            }</h3>    
                            <h2 class="text-lg font-bold">Kelompok Kerja Guru-PAI Kecamatan Wagir</h2>
                            <h2 class="text-lg font-bold">TAHUN ${
                                page.props.tapel.label
                            }</h2>
                        </div>
                    </div>
                    <div class="content">
                        <div class="meta my-6">
                            <h2 class="text-center text-2xl font-bold">Kwitansi</h2>    
                            <p class="mx-auto text-center">No.: ${
                                item.no
                            }/KKG-PAI/${new Date().getFullYear()}</p>
                        </div>   
                        <table class="border mx-auto text-lg">
                            <tbody>
                                <tr>
                                    <td class="border-b py-2 px-1">Sudah Terima dari</td>    
                                    <td class="border-b py-2 px-1">:</td>
                                    <td class="border-b py-2 px-1">${
                                        item.dari
                                    }</td>
                                </tr>    
                                <tr>
                                    <td class="border-b py-2 px-1">Banyaknya Uang</td>
                                    <td class="border-b py-2 px-1">:</td>
                                    <td class="border-b py-2 px-1">
                                        <p class="p-2 bg-slate-100 font-black tetx-lg">
                                            ${item.terbilang} Rupiah
                                            </p>
                                     </td>
                                </tr>
                                <tr>
                                    <td class="border-b py-2 px-1">Untuk Pembayarabn</td>    
                                    <td class="border-b py-2 px-1">:</td>
                                    <td class="border-b py-2 px-1">Pendaftaran ${
                                        kegiatan.value.label
                                    }</td>
                                </tr>
                                <tr>
                                    <td class="border-b py-2 px-1">Jumlah</td>    
                                    <td class="border-b py-2 px-1">:</td>
                                    <td class="border-b py-2 px-1">
                                        <p class="border border-4 border-black font-black text-right">
                                            ${item.jumlah.toLocaleString(
                                                "id-ID",
                                                {
                                                    style: "currency",
                                                    currency: "IDR",
                                                }
                                            )}
                                            </p>
                                    </td>
                                </tr>
                            </tbody>    
                        </table> 
                    </div>
                    <div class="ttd  mt-8 mb-3 flex justify-end w-[90%]">
                        <div class="relative">
                            <p>Malang, ${new Date(
                                item.tanggal
                            ).toLocaleDateString("id-ID", {
                                day: "2-digit",
                                month: "short",
                                year: "numeric",
                            })}</p> 
                            <p>Bendahara,</p>   

                            <img src="/ttd/${
                                page.props.penguruses.filter(
                                    (i) => i.nama == "Bendahara I"
                                )[0].guru?.nip
                            }.png" class="absolute h-[125px] -translate-y-6" />
                            <img src="/img/stempel.png" class="absolute  -translate-y-10 -translate-x-[80px] mix-blend-multiply h-[135px]"  />
                            <p class="underline font-bold uppercase tracking-wider mt-16">${
                                page.props.penguruses.filter(
                                    (i) => i.nama == "Bendahara I"
                                )[0].guru?.nama ?? ".........................."
                            }</p>
                            <p>NIP. ${
                                page.props.penguruses.filter(
                                    (i) => i.nama == "Bendahara I"
                                )[0].guru?.nip ?? ".........................."
                            }</p>

                        </div>
                    </div>
                </div>
            </body>
        </html>
    
    `;

    win.document.write(html);
    const styles = await cssFiles();
    styles.forEach((css) => {
        const link = win.document.createElement("link");
        link.rel = "stylesheet";
        link.href = css;
        win.document.head.append(link);
    });
    win.document.close();
    setTimeout(() => {
        win.print();
        win.close();
    }, 1000);
};
</script>

<template>
    <div title="Kegiatan KKG">
        <Head title="Kegiatan KKG" />

        <div class="content">
            <div
                class="toolbar h-12 bg-slate-100 flex items-center justify-between p-2"
            >
                <h3>Agenda Kegiatan</h3>
                <div class="flex items-center gap-2">
                    <el-button
                        :native-type="null"
                        :disabled="!page.props.roles.includes('admin')"
                        @click="newKegiatan"
                    >
                        <Icon icon="mdi-plus" />
                    </el-button>
                </div>
            </div>
            <div class="body">
                <el-table :data="page.props.kegiatans">
                    <el-table-column
                        prop="mulai"
                        label="Tanggal"
                    ></el-table-column>
                    <el-table-column
                        prop="label"
                        label="Nama Kegiatan"
                    ></el-table-column>
                    <el-table-column label="Opsi">
                        <template #default="scope">
                            <div class="flex items-center gap-2">
                                <el-button-group>
                                    <el-button
                                        :native-type="null"
                                        @click="daftarHadir(scope.row)"
                                        title="Daftar Hadir"
                                    >
                                        <Icon icon="mdi:list-box" />
                                    </el-button>
                                    <el-button
                                        :native-type="null"
                                        @click="openKuitansi(scope.row)"
                                        title="Kuitansi"
                                        :type="
                                            scope.row.kuitansis.length > 0
                                                ? 'success'
                                                : ''
                                        "
                                    >
                                        <Icon icon="mdi:paper-roll" />
                                    </el-button>
                                    <el-button
                                        :native-type="null"
                                        @click="editKegiatan(scope.row)"
                                        title="Edit"
                                        type="warning"
                                    >
                                        <Icon icon="mdi:square-edit-outline" />
                                    </el-button>
                                    <el-button
                                        :native-type="null"
                                        @click="deleteKegiatan(scope.row)"
                                        title="Hapus"
                                        type="danger"
                                    >
                                        <Icon icon="mdi:delete" />
                                    </el-button>
                                </el-button-group>
                            </div>
                        </template>
                    </el-table-column>
                </el-table>
            </div>
            <!-- {{ page.props.roles }} -->
        </div>
        <!-- Dialog new Kegiatan -->
        <el-dialog
            v-model="showFormKegiatan"
            :title="formKegiatanTitle"
            width="400px"
        >
            <el-form v-model="kegiatan" label-position="top">
                <el-row :gutter="20">
                    <el-col :span="24">
                        <el-form-item label="Nama Kegiatan">
                            <el-input
                                placeholder="Masukkan nama kegiatan"
                                v-model="kegiatan.label"
                            ></el-input>
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row :gutter="20">
                    <el-col :span="12">
                        <el-form-item label="Tanggal Mulai">
                            <el-date-picker
                                placeholder="Tanggal dimulai"
                                v-model="kegiatan.mulai"
                                type="date"
                                format-value="YYYY-MM-DD"
                                format="DD MMM YYYY"
                            ></el-date-picker>
                        </el-form-item>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item label="Tanggal Selesai">
                            <el-date-picker
                                placeholder="Tanggal selesai"
                                v-model="kegiatan.selesai"
                                type="date"
                                format-value="YYYY-MM-DD"
                                format="DD MMM YYYY"
                            ></el-date-picker>
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row :gutter="20">
                    <el-col :span="24">
                        <el-form-item label="Keterangan">
                            <el-input
                                type="textarea"
                                placeholder="Keterangan"
                                v-model="kegiatan.deskripsi"
                            ></el-input>
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row :gutter="20" justify="center">
                    <el-button
                        :native-type="null"
                        type="primary"
                        :loading="loading"
                        @click="saveKegiatan"
                    >
                        Simpan
                    </el-button>
                </el-row>
            </el-form>
        </el-dialog>

        <!-- Dialog Kuitansi -->
        <el-dialog v-model="showKuitansi" fullscreen>
            <template #header>
                <div class="flex items-center justify-between">
                    <h3>Kuitansi {{ kegiatan.label }}</h3>
                    <el-button
                        style="margin-right: 20px"
                        :native-type="null"
                        type="primary"
                        @click="printKuitansis"
                    >
                        <Icon icon="mdi:printer-check" />
                        Cetak Semua
                    </el-button>
                </div>
            </template>
            <template #default>
                <div v-if="kegiatan.kuitansis.length < 1">
                    <el-alert type="error">
                        <h1 class="text-lg mb-2">
                            Belum ada Kuitansi untuk kegiatan ini.
                        </h1>
                        <el-button
                            :native-type="null"
                            type="primary"
                            @click="formKuitansi = !formKuitansi"
                            >Buat Kuitansi?</el-button
                        >
                    </el-alert>
                    <!-- Form Kuitansi -->
                    <el-card v-if="formKuitansi" style="margin-top: 20px">
                        <template #header>
                            <div class="flex items-center justify-between">
                                <h3>Form Kuitansi</h3>
                                <el-button
                                    circle
                                    :native-type="null"
                                    type="danger"
                                    @click="formKuitansi = false"
                                >
                                    <Icon icon="mdi:close" />
                                </el-button>
                            </div>
                        </template>
                        <el-form v-model="kuitansi" label-width="100">
                            <el-form-item label="Nomor">
                                <el-input
                                    placeholder="Masukkan Nomor Kuitansi"
                                    v-model="kuitansi.no"
                                ></el-input>
                            </el-form-item>
                            <el-form-item label="Tanggal">
                                <el-date-picker
                                    placeholder="Tanggal Pembayaran"
                                    v-model="kuitansi.tanggal"
                                    format-value="YYYY-MM-DD"
                                    format="DD MMM YYYY"
                                ></el-date-picker>
                            </el-form-item>
                            <el-form-item label="Jumlah">
                                <el-input
                                    type="number"
                                    placeholder="Jumlah Pembayaran"
                                    v-model="kuitansi.jumlah"
                                ></el-input>
                            </el-form-item>
                        </el-form>
                        <template #footer>
                            <div class="flex justify-end">
                                <el-button
                                    :native-type="null"
                                    type="primary"
                                    @click="makeKuitansi"
                                    >Simpan</el-button
                                >
                            </div>
                        </template>
                    </el-card>
                </div>
                <div v-else>
                    <el-scrollbar max-height="95vh">
                        <template v-for="(kuitansi, k) in kegiatan.kuitansis">
                            <el-card style="margin-bottom: 20px">
                                <template #header>
                                    <div
                                        class="flex items-center justify-between"
                                    >
                                        <h3>
                                            {{ kuitansi.no }}
                                        </h3>
                                        <el-button @click="printOne(kuitansi)">
                                            <Icon icon="mdi:printer" />
                                        </el-button>
                                    </div>
                                </template>
                                <template #default>
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td>Diterima dari</td>
                                                <td>:</td>
                                                <td>{{ kuitansi.dari }}</td>
                                            </tr>
                                            <tr>
                                                <td>Banyaknya Uang</td>
                                                <td>:</td>
                                                <td>
                                                    {{ kuitansi.terbilang }}
                                                    Rupiah
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Untuk Pembayaran</td>
                                                <td>:</td>
                                                <td>
                                                    Pendaftaran
                                                    {{ kegiatan.label }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Jumlah</td>
                                                <td>:</td>
                                                <td>
                                                    {{
                                                        kuitansi.jumlah.toLocaleString(
                                                            "id-ID",
                                                            {
                                                                style: "currency",
                                                                currency: "IDR",
                                                            }
                                                        )
                                                    }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </template>
                            </el-card>
                        </template>
                    </el-scrollbar>
                </div>
            </template>
        </el-dialog>
    </div>
</template>

<style scoped>
tr td:nth-child(2) {
    padding: 10px;
}
</style>
