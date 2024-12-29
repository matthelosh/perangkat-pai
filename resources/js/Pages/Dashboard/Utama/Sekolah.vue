<script setup>
import { ref, computed } from "vue";
import { Head, usePage, router } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";
import Layout from "@/layouts/DashboardLayout.vue";

const page = usePage();

const showDialog = ref(false);
const importDialog = ref(false);
const sekolah = ref({});

const importedData = ref([]);

const simpan = async () => {
    router.post(route("sekolah.store"), sekolah.value, {
        onStart: () => {},
        onSuccess: (page) => {
            router.reload({ only: ["sekolahs"] });
        },
        onError: (err) => {},
    });
};

const edit = (row) => {
    sekolah.value = row;
    showDialog.value = true;
};

const hapus = async (sekolah_id) => {
    router.delete(route("sekolah.destroy", { id: sekolah_id }));
};

const impor = async () => {
    router.post(route("sekolah.import"), { sekolahs: importedData.value });
};
defineOptions({
    layout: Layout,
});
</script>

<template>
    <!-- <Layout title="Data Sekolah"> -->
    <div>
        <Head title="Data Sekolah" />
        <el-row class="w-full">
            <el-col :span="24">
                <el-card>
                    <template #header>
                        <div class="w-full flex items-center justify-between">
                            <h3>Data Sekolah</h3>
                            <el-button-group :native-type="null">
                                <el-popover
                                    placement="top-end"
                                    content="Impor data"
                                >
                                    <template #reference>
                                        <el-button
                                            :native-type="null"
                                            @click="importDialog = true"
                                        >
                                            <Icon
                                                icon="mdi:text-box-multiple"
                                            />
                                        </el-button>
                                    </template>
                                </el-popover>
                                <el-popover
                                    placement="top-end"
                                    content="Tambah baru"
                                >
                                    <template #reference>
                                        <el-button
                                            :native-type="null"
                                            @click="showDialog = true"
                                        >
                                            <Icon icon="mdi:plus" />
                                        </el-button>
                                    </template>
                                </el-popover>
                            </el-button-group>
                        </div>
                    </template>
                    <div class="card-body">
                        <el-table :data="page.props.sekolahs">
                            <el-table-column label="NPSN">
                                <template #default="scope">
                                    <el-link
                                        type="primary"
                                        :underline="false"
                                        @click="edit(scope.row)"
                                        >{{ scope.row.npsn }}</el-link
                                    >
                                </template>
                            </el-table-column>
                            <el-table-column
                                label="Nama"
                                prop="nama"
                            ></el-table-column>
                            <el-table-column
                                label="Alamat"
                                prop="alamat"
                            ></el-table-column>
                            <el-table-column
                                label="Desa"
                                prop="desa"
                            ></el-table-column>
                            <el-table-column
                                label="Nama KS"
                                prop="nama_ks"
                            ></el-table-column>
                            <el-table-column
                                label="NIP KS"
                                prop="nip_ks"
                            ></el-table-column>
                            <el-table-column label="Opsi">
                                <template #default="scope">
                                    <el-popconfirm
                                        title="Yakin menghapus sekolah ini?"
                                        @confirm="hapus(scope.row.id)"
                                    >
                                        <template #reference>
                                            <el-button :native-type="null">
                                                <Icon icon="mdi:delete" />
                                            </el-button>
                                        </template>
                                    </el-popconfirm>
                                </template>
                            </el-table-column>
                        </el-table>
                    </div>
                </el-card>
            </el-col>
        </el-row>

        <el-dialog v-model="showDialog" @closed="sekolah = {}">
            <template #header>
                <h3>Formulir Sekolah</h3>
            </template>
            <el-form
                id="form-sekolah"
                v-model="sekolah"
                label-position="top"
                class="p-2"
            >
                <el-row class="w-full" :gutter="10">
                    <el-col :span="4">
                        <el-form-item label="NPSN" class="w-full">
                            <el-input
                                type="text"
                                placeholder="Nomor Pokok Sekolah Nasional"
                                v-model="sekolah.npsn"
                                style="width: 100%"
                            ></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="8">
                        <el-form-item label="NSS" class="w-full">
                            <el-input
                                type="text"
                                placeholder="Nomor Statistik Sekolah"
                                v-model="sekolah.nss"
                                style="width: 100%"
                            ></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item label="Nama Sekolah" class="w-full">
                            <el-input
                                type="text"
                                placeholder="Nama Sekolah"
                                v-model="sekolah.nama"
                                style="width: 100%"
                            ></el-input>
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row class="w-full" :gutter="10">
                    <el-col :span="12">
                        <el-form-item label="Alamat" class="w-full">
                            <el-input
                                type="text"
                                placeholder="Alamat Sekolah"
                                v-model="sekolah.alamat"
                                style="width: 100%"
                            ></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="4">
                        <el-form-item class="w-full" label="RT/RW">
                            <el-row class="w-full">
                                <el-col :span="12">
                                    <el-input
                                        type="text"
                                        placeholder="RT"
                                        v-model="sekolah.rt"
                                        style="width: 100%"
                                    ></el-input>
                                </el-col>
                                <el-col :span="12">
                                    <el-input
                                        type="text"
                                        placeholder="RW"
                                        v-model="sekolah.rw"
                                        style="width: 100%"
                                    ></el-input>
                                </el-col>
                            </el-row>
                        </el-form-item>
                    </el-col>
                    <el-col :span="8">
                        <el-form-item label="Desa" class="w-full">
                            <el-input
                                type="text"
                                placeholder="Desa"
                                v-model="sekolah.desa"
                                style="width: 100%"
                            ></el-input>
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row class="w-full" :gutter="10">
                    <el-col :span="10">
                        <el-form-item label="Telp/HP" class="w-full">
                            <el-input
                                type="text"
                                placeholder="Telp/HP"
                                v-model="sekolah.telp"
                                style="width: 100%"
                            ></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="14">
                        <el-form-item label="Email" class="w-full">
                            <el-input
                                type="email"
                                placeholder="Email"
                                v-model="sekolah.email"
                                style="width: 100%"
                            ></el-input>
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row class="w-full" :gutter="10">
                    <el-col :span="8">
                        <el-form-item label="Website" class="w-full">
                            <el-input
                                type="text"
                                placeholder="Website"
                                v-model="sekolah.website"
                                style="width: 100%"
                            ></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="8">
                        <el-form-item label="Nama KS" class="w-full">
                            <el-input
                                type="text"
                                placeholder="Kepala Sekolah"
                                v-model="sekolah.nama_ks"
                                style="width: 100%"
                            ></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="8">
                        <el-form-item label="NIP KS" class="w-full">
                            <el-input
                                type="text"
                                placeholder="NIP KS"
                                v-model="sekolah.nip_ks"
                                style="width: 100%"
                            ></el-input>
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row class="w-full mt-4" align="middle" justify="center">
                    <el-button
                        :native-type="null"
                        size="default"
                        @click="simpan"
                        type="primary"
                    >
                        Simpan
                    </el-button>
                </el-row>
            </el-form>
        </el-dialog>

        <el-dialog v-model="importDialog" :fullscreen="true">
            <template #header>
                <h3>Cek Data Sekolah Sebelum diimpor</h3>
                <el-button :native-type="null" type="primary" @click="impor"
                    >Impor</el-button
                >
            </template>
            <div class="dialog-body w-full bg-slate-100 p-3">
                <vue-excel-editor v-model="importedData" class="w-full">
                    <vue-excel-column
                        label="NPSN"
                        field="npsn"
                        type="string"
                        width="80px"
                    />
                    <vue-excel-column
                        label="NSS"
                        field="nss"
                        type="string"
                        width="80px"
                    />
                    <vue-excel-column
                        label="Nama"
                        field="nama"
                        type="string"
                        width="80px"
                    />
                    <vue-excel-column
                        label="Alamat"
                        field="alamat"
                        type="string"
                        width="80px"
                    />
                    <vue-excel-column
                        label="RT"
                        field="rt"
                        type="string"
                        width="80px"
                    />
                    <vue-excel-column
                        label="RW"
                        field="rw"
                        type="string"
                        width="80px"
                    />
                    <vue-excel-column
                        label="Desa"
                        field="desa"
                        type="string"
                        width="80px"
                    />
                    <vue-excel-column
                        label="Email"
                        field="email"
                        type="string"
                        width="80px"
                    />
                    <vue-excel-column
                        label="Nama KS"
                        field="nama_ks"
                        type="string"
                        width="80px"
                    />
                    <vue-excel-column
                        label="NIP KS"
                        field="nip_ks"
                        type="string"
                        width="80px"
                    />
                </vue-excel-editor>
            </div>
        </el-dialog>
    </div>
    <!-- </Layout> -->
</template>
<style>
#form-sekolah .el-input {
    --el-input-width: 100px;
}
</style>
