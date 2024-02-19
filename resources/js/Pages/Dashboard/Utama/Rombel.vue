<script setup>
import { ref, computed, defineAsyncComponent, watch } from "vue";
import { usePage, Head, router } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";
import { ElMessage, ElMessageBox } from "element-plus";

import Layout from "@/layouts/DashboardLayout.vue";

const FormRombel = defineAsyncComponent(() =>
    import("@/components/Dashboard/Utama/Rombel/FormRombel.vue")
);

const FormAddSiswa = defineAsyncComponent(() =>
    import("@/components/Dashboard/Utama/Rombel/FormAddSiswa.vue")
);

const FormImportSiswa = defineAsyncComponent(() =>
    import("@/components/Dashboard/Utama/Rombel/FormImportSiswa.vue")
);
const page = usePage();

const loading = ref(false);
const showFormPlus = ref(false);
const showFormaddSiswa = ref(false);
const rombel = ref({});
const rombelIndex = ref(null);

const activeName = ref("1");
const siswa = ref({});
const inputFile = ref(null);
const importedData = ref([]);
const showDialogImpor = ref(false);

const hapus = async (item) => {
    ElMessageBox.confirm(`Yakin menghapus rombel ${rombel.label}?`, "Warning", {
        confirmButtonText: "Ya",
        cancelButtonText: "Batal",
        type: "warning",
    })
        .then(() => {
            router.delete(route("rombel.destroy", { id: item.id }), {
                onSuccess: () =>
                    ElMessage({ type: "info", message: "Rombel dihapus" }),
                onError: (err) => {
                    Object.keys(err).forEach((k) => {
                        ElMessage({ type: "warning", message: err[k] });
                    });
                },
            });
        })
        .catch(() => {
            ElMessage({ type: "info", message: "Batal Menghapus Rombel" });
        });
};

const onFormPlusClose = () => {
    rombel.value = {};
    showFormPlus.value = false;
};

const edit = (item) => {
    rombel.value = item;
    showFormPlus.value = true;
};

const addSiswa = (item, r) => {
    rombel.value = item;
    // rombel.value.siswas=[]
    rombelIndex.value = r;
    // rombel.value.siswas.push({nisn: '', nama: '', jk: ''})
    showFormaddSiswa.value = true;
};

const hapusSiswa = async (rombel_id, siswa_id) => {
    router.post(
        route("rombel.detach.siswa", { id: rombel_id, siswa: siswa_id }),
        {
            onStart: () => (loading.value = true),
            onSuccess: () => {
                Elnotification({ title: "Info", message: "Siswa dikeluarkan" });
                router.reload({ only: ["rombels"] });
            },
            onError: (err) => {
                ElNotification({ title: "error", message: "Error" });
            },
        }
    );
};
const onFormAddSiswaClose = () => {
    showFormaddSiswa.value = false;
    rombel.value = {};
    siswa.value = {};
};

const showFormImport = async (item) => {
    rombel.value = item;
    showDialogImpor.value = true;
};

const onFormImportHide = () => {
    showDialogImpor.value = false;
    rombel.value = {};
};
</script>

<template>
    <Layout title="Data Rombel">
        <el-card>
            <template #header>
                <div class="toolbar flex items-center justify-between">
                    <h3>Data Rombel</h3>
                    <div class="toolbar-items flex gap-1">
                        <el-button-group>
                            <el-tooltip
                                content="Tambah Rombel"
                                placement="top-end"
                            >
                                <el-button @click="showFormPlus = true">
                                    <Icon icon="mdi:plus" />
                                </el-button>
                            </el-tooltip>
                            <el-tooltip content="Cetak" placement="top-end">
                                <el-button>
                                    <Icon icon="mdi:printer" />
                                </el-button>
                            </el-tooltip>
                        </el-button-group>
                    </div>
                </div>
            </template>
            <div class="card-body">
                <el-alert type="error" v-if="page.props.rombels.length < 1">
                    <template #title>
                        <span class="flex items-center">
                            <Icon icon="mdi:alert-circle" class="text-2xl" />
                            <h3 class="text-lg">Peringatan!</h3>
                        </span>
                    </template>
                    <p>Anda belum membuat Rombongan Belajar</p>
                    <el-button
                        type="warning"
                        plain
                        round
                        class="my-4"
                        size="large"
                        @click="showFormPlus = true"
                        >Buat Rombel</el-button
                    >
                </el-alert>
                <el-collapse v-model="activeName" accordion>
                    <el-collapse-item
                        class="group"
                        v-for="(item, r) in page.props.rombels"
                        :key="item.kode"
                        :name="r + 1"
                    >
                        <template #title>
                            <div class="title flex gap-2">
                                <span>Kelas {{ item.label }}</span>
                                <span class="hidden group-hover:flex gap-2">
                                    <el-link
                                        type="primary"
                                        :underline="false"
                                        @click.stop="edit(item)"
                                        >Edit?</el-link
                                    >
                                    <el-link
                                        type="danger"
                                        :underline="false"
                                        @click.stop="hapus(item)"
                                        >Hapus?</el-link
                                    >
                                </span>
                            </div>
                        </template>
                        <div>
                            <el-row :gutter="10">
                                <el-col :span="12">
                                    <h4>Data Siswa:</h4>
                                    <p>
                                        Laki-laki:
                                        <span v-if="item.siswas">{{
                                            item.siswas.filter(
                                                (siswa) =>
                                                    siswa.jk == "Laki-laki"
                                            ).length
                                        }}</span>
                                        | Perempuan:
                                        <span v-if="item.siswas">{{
                                            item.siswas.filter(
                                                (siswa) =>
                                                    siswa.jk == "Perempuan"
                                            ).length
                                        }}</span>
                                        | Total:
                                        <span v-if="item.siswas">{{
                                            item.siswas.length
                                        }}</span>
                                    </p>
                                </el-col>
                                <el-col :span="12" justify="end">
                                    <el-button-group>
                                        <el-tooltip
                                            content="Tambah Peserta Didik Baru"
                                            placement="top-end"
                                        >
                                            <el-button
                                                type="primary"
                                                @click="addSiswa(item, r)"
                                            >
                                                <Icon icon="mdi:account-plus" />
                                            </el-button>
                                        </el-tooltip>
                                        <el-tooltip
                                            content="Import Data Peserta Didik Baru"
                                            placement="top-start"
                                        >
                                            <el-button
                                                type="success"
                                                @click="showFormImport(item)"
                                            >
                                                <Icon
                                                    icon="mdi:file-multiple"
                                                />
                                            </el-button>
                                        </el-tooltip>
                                    </el-button-group>
                                </el-col>
                            </el-row>
                        </div>
                        <el-table :data="item.siswas" stripe height="400px">
                            <el-table-column label="NISN" prop="nisn" />
                            <el-table-column label="Nama" prop="nama" />
                            <el-table-column label="JK" prop="jk" />
                            <el-table-column label="Opsi">
                                <template #default="scope">
                                    <el-popconfirm
                                        title="Yakin Hapus Siswa?"
                                        @confirm="
                                            hapusSiswa(item.id, scope.row.id)
                                        "
                                    >
                                        <template #reference>
                                            <el-button text type="danger">
                                                <Icon
                                                    icon="mdi:close-circle"
                                                    class="text-2xl"
                                                />
                                            </el-button>
                                        </template>
                                    </el-popconfirm>
                                </template>
                            </el-table-column>
                        </el-table>
                    </el-collapse-item>
                </el-collapse>
            </div>
        </el-card>

        <el-dialog v-model="showDialogImpor" @close="onFormImportHide">
            <template #header>
                <h3>Data Siswa yang akan dimasukkan</h3>
            </template>

            <div class="dialog-body">
                <FormImportSiswa
                    :selectedRombel="rombel"
                    @close="onFormImportHide"
                />
            </div>
        </el-dialog>
        <el-dialog v-model="showFormaddSiswa" draggable>
            <template #header>
                <h3>Form tambah Siswa Kelas {{ rombel.label }}</h3>
            </template>
            <div class="dialog-body">
                <FormAddSiswa :rombel="rombel" @close="onFormAddSiswaClose" />
            </div>
        </el-dialog>

        <el-dialog v-model="showFormPlus">
            <template #header>
                <h3>Formulir Rombel</h3>
            </template>
            <FormRombel :selectedRombel="rombel" @close="onFormPlusClose" />
        </el-dialog>
    </Layout>
</template>
