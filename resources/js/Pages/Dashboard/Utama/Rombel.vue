<script setup>
import { ref, computed, defineAsyncComponent, watch } from "vue";
import { usePage, Head, router } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";
import { ElAlert, ElMessage, ElMessageBox } from "element-plus";
import NProgress from "nprogress";
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

// Ambil Rombel dan Siswa dari Rapor
const rombels = ref([]);
const syncRapor = ref(false);
const getFromRapor = async () => {
    syncRapor.value = true;
    axios
        .get(
            route("rapor.rombel.get", {
                _query: { npsn: page.props.sekolahs[0].npsn },
            })
        )
        .then((res) => {
            // (rombels.value = res.data)
            rombels.value = res.data;
            console.log(res.data);
        });
};
const progress = ref(null);
const syncRombel = async () => {
    router.post(
        route("rapor.rombel.sync"),
        { datas: rombels.value },
        {
            onStart: () => {
                // NProgress.start();
                progress.value = 0;
            },
            onProgress: (event) => {
                progress.value = event.detail.progress.percentage;
            },
            onFinish: () => {
                progress.value = 100;
                ElMessageBox({
                    title: "Info",
                    message: page.props.flash.message,
                });
            },
        }
    );
};
</script>

<template>
    <Layout title="Data Rombel">
        <el-card>
            <template #header>
                <div class="toolbar flex items-center justify-between">
                    <h3>Data Rombel {{ page.props.tapel.label }}</h3>
                    <div class="toolbar-items flex gap-1">
                        <el-button-group>
                            <el-tooltip
                                content="Tambah Rombel"
                                placement="top-end"
                            >
                                <el-button
                                    @click="showFormPlus = true"
                                    :native-type="null"
                                >
                                    <Icon icon="mdi:plus" />
                                    Buat
                                </el-button>
                            </el-tooltip>
                            <el-tooltip
                                content="Impor dari Rapor SD"
                                placement="top-end"
                            >
                                <el-button
                                    @click="getFromRapor"
                                    :native-type="null"
                                >
                                    <Icon icon="mdi:sync" />
                                    Ambil
                                </el-button>
                            </el-tooltip>
                            <el-tooltip content="Cetak" placement="top-end">
                                <el-button :native-type="null">
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
                        :native-type="null"
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
                                                :native-type="null"
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
                                                :native-type="null"
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
                                            <el-button
                                                text
                                                type="danger"
                                                :native-type="null"
                                            >
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

        <el-dialog v-model="syncRapor" :show-close="false">
            <template #header>
                <div class="header flex p-0 justify-between">
                    <h3>Data Rombel di Rapor SD</h3>
                    <button
                        class="p-1 bg-red-400 text-white hover:bg-red-300 focus:bg-red-500"
                        @click="syncRapor = !syncRapor"
                    >
                        <Icon icon="mdi:close" />
                    </button>
                </div>
            </template>
            <template #default>
                <div class="dialog-content">
                    <el-table :data="rombels" stripe>
                        <el-table-column
                            label="#"
                            type="index"
                        ></el-table-column>
                        <el-table-column
                            label="Kode"
                            prop="kode"
                        ></el-table-column>
                        <el-table-column
                            label="Label"
                            prop="label"
                        ></el-table-column>
                        <el-table-column label="Jml Siswa">
                            <template #default="{ row }">
                                <span class="flex gap-2 items-center">
                                    <span
                                        class="flex items-center gap-1 bg-teal-100 py-1 px-2 w-[50px] font-bold"
                                    >
                                        <Icon
                                            icon="mdi-account-group"
                                            class="text-lg"
                                        />
                                        {{ row.siswas?.length }}
                                    </span>
                                    <span
                                        class="flex gap-1 items-center bg-sky-100 py-1 px-2 w-[50px] font-bold"
                                    >
                                        <Icon
                                            icon="mdi-human-female"
                                            class="text-lg"
                                        />
                                        {{
                                            row.siswas?.filter(
                                                (siswa) =>
                                                    siswa.jk == "Laki-laki"
                                            ).length
                                        }}
                                    </span>
                                    <span
                                        class="flex items-center gap-1 bg-pink-100 py-1 px-2 w-[50px] font-bold"
                                    >
                                        <Icon
                                            icon="mdi-human-female"
                                            class="text-lg"
                                        />
                                        {{
                                            row.siswas?.filter(
                                                (siswa) =>
                                                    siswa.jk == "Perempuan"
                                            ).length
                                        }}
                                    </span>
                                </span>
                            </template>
                        </el-table-column>
                    </el-table>
                </div>
            </template>
            <template #footer>
                <div class="flex items-center justify-between gap-2">
                    <div class="progress flex-grow h-6">
                        <el-progress
                            v-if="progress"
                            status="success"
                            :percentage="progress"
                            class="w-full"
                        ></el-progress>
                    </div>
                    <div>
                        <el-button
                            type="primary"
                            @click="syncRombel"
                            :native-type="null"
                        >
                            Sinkron
                        </el-button>
                    </div>
                </div>
            </template>
        </el-dialog>
    </Layout>
</template>

<style>
.el-dialog {
    padding: 0 !important;
}
header.el-dialog__header {
    /* padding: 10px; */
    margin: 0;
}
</style>
