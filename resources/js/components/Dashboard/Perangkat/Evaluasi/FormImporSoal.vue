<script setup>
import { Icon } from "@iconify/vue";
import { defineAsyncComponent, onBeforeMount, ref } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import { ElNotification } from "element-plus";
const props = defineProps({ open: Boolean });
const emit = defineEmits(["close"]);
const tingkats = ref(["1", "2", "3", "4", "5", "6"]);
const tps = ref([]);
const elemens = ref([]);

const page = usePage();
const loading = ref(false);
const newSoal = ref({
    tingkat: "",
    elemen_id: "",
    tp_id: "",
    pertanyaan: "Tulis pertanyaan",
    a: "",
    b: "",
    c: "",
    d: "",
    kunci: "a",
});
const show = ref(false);
const error = ref("");

const onKelasChange = async (tingkat) => {
    try {
        loading.value = true;
        error.value = "";
        newSoal.value.tingkat = String(tingkat);
        newSoal.value.elemen_id = "";
        newSoal.value.tp_id = "";
        tps.value = [];
        await getElemen(tingkat);
    } catch (err) {
        error.value = "Failed to load elemen data: " + err.message;
    } finally {
        loading.value = false;
    }
};

const onElemenChange = async (elemen) => {
    try {
        loading.value = true;
        error.value = "";
        newSoal.value.elemen_id = String(elemen);
        newSoal.value.tp_id = "";
        await getTp();
    } catch (err) {
        error.value = "Failed to load TP data: " + err.message;
    } finally {
        loading.value = false;
    }
};

const getElemen = async (tingkat) => {
    try {
        const results = await axios.post(
            route("elemen.index", { _query: { tingkat: tingkat } })
        );
        elemens.value = results.data.elemens || [];
    } catch (err) {
        elemens.value = [];
        throw err;
    }
};
const getTp = async () => {
    try {
        const results = await axios.post(
            route("tp.list", {
                _query: {
                    tingkat: newSoal.value.tingkat,
                    elemen: newSoal.value.elemen_id,
                },
            })
        );
        tps.value = results.data.tps || [];
    } catch (err) {
        tps.value = [];
        throw err;
    }
};

const simpanSoal = async () => {
    await router.post(route("soal.store"), newSoal.value, {
        onStart: () => {
            loading.value = true;
        },
        onSuccess: () => {
            ElNotification({
                title: "Info",
                message: page.props.flash.message,
                type: "success",
            });
            emit("close");
        },
        onFinish: () => (loading.value = false),
    });
};

onBeforeMount(() => {
    show.value = props.open;
});
</script>

<template>
    <el-dialog fullscreen :show-close="false" v-model="show">
        <template #header>
            <el-affix :offset="0">
                <div
                    class="flex items-center justify-between pl-2 pr-1 py-2 shadow-lg bg-white"
                >
                    <h3>Form Soal</h3>

                    <el-button
                        type="danger"
                        :native-type="null"
                        @click="emit('close')"
                    >
                        <Icon icon="mdi:close" />
                    </el-button>
                </div>
            </el-affix>
        </template>
        <div
            class="content max-h-[79vh] min-h-[75vh] sm:min-h-[86vh] sm:max-h-[86vh] overflow-y-auto max-w-[100vw] overflow-x-hidden"
        >
            <el-alert
                v-if="error"
                :title="error"
                type="error"
                show-icon
                class="mb-4"
            />
            <el-form label-position="top" :disabled="loading">
                <el-row :gutter="10">
                    <el-col :span="24" :sm="8">
                        <el-card>
                            <h3 class="font-bold text-sky-800">Data Soal</h3>
                            <el-row :gutter="10">
                                <el-col :span="24" :sm="5">
                                    <el-form-item label="Kelas">
                                        <el-select
                                            v-model="newSoal.tingkat"
                                            placeholder="Pilih Kelas"
                                            size="large"
                                            @change="onKelasChange"
                                        >
                                            <el-option
                                                v-for="tingkat in tingkats"
                                                :value="tingkat"
                                                :label="`Kelas ${tingkat}`"
                                            />
                                        </el-select>
                                    </el-form-item>
                                </el-col>
                                <el-col :span="24" :sm="9">
                                    <el-form-item label="Elemen">
                                        <el-select
                                            v-model="newSoal.elemen_id"
                                            placeholder="Pilih Elemen"
                                            size="large"
                                            @change="onElemenChange"
                                            :disabled="elemens.length < 1"
                                            value-key="kode"
                                        >
                                            <el-option
                                                v-for="elemen in elemens"
                                                :value="elemen.kode"
                                                :label="elemen.label"
                                            />
                                        </el-select>
                                    </el-form-item>
                                </el-col>
                                <el-col :span="24" :sm="6">
                                    <el-form-item label="Tipe Soal">
                                        <el-select
                                            v-model="newSoal.tipe"
                                            placeholder="Pilih Tipe Soal"
                                            size="large"
                                        >
                                            <el-option
                                                v-for="tipe in [
                                                    'pilihan',
                                                    'isian',
                                                    'uraian',
                                                ]"
                                                :value="tipe"
                                                :label="tipe.toUpperCase()"
                                            ></el-option>
                                        </el-select>
                                    </el-form-item>
                                </el-col>
                                <el-col :span="24" :sm="4">
                                    <el-form-item label="Unduh Format">
                                        <el-button
                                            size="large"
                                            :native-type="null"
                                            type="warning"
                                        >
                                            <Icon icon="mdi:download" />
                                            Unduh
                                        </el-button>
                                    </el-form-item>
                                </el-col>
                            </el-row>
                            <el-row :gutter="10">
                                <el-col :span="24" :sm="20">
                                    <el-form-item label="Ambil File Soal">
                                        <input
                                            type="file"
                                            ref="fileImporSoal"
                                            accept=".xls, .xlsx, .ods"
                                        />
                                    </el-form-item>
                                </el-col>
                                <el-col :span="24" :sm="4">
                                    <el-form-item label="Upload File">
                                        <el-button
                                            size="large"
                                            :native-type="null"
                                            type="primary"
                                        >
                                            <Icon icon="mdi:upload" />
                                            Upload
                                        </el-button>
                                    </el-form-item>
                                </el-col>
                            </el-row>
                        </el-card>
                    </el-col>
                    <el-col :span="24" :sm="16">
                        <el-card>
                            <h3 class="text-sky-800 font-bold">
                                Data Pertanyaan
                            </h3>
                        </el-card>
                    </el-col>
                </el-row>
            </el-form>
        </div>
        <template #footer>
            <div class="flex justify-end">
                <el-button
                    :native-type="null"
                    type="primary"
                    size="large"
                    @click="simpanSoal"
                    >Simpan
                </el-button>
            </div>
        </template>
    </el-dialog>
</template>

<style>
.el-dialog,
header.el-dialog__header {
    padding: 0 !important;
}

.el-dialog__body {
    padding: 20px !important;
}

.wrapped-option {
    white-space: normal !important;
    height: auto !important;
    padding: 5px 12px !important;
}

.wrapped-option .el-select-dropdown__item {
    white-space: normal !important;
    height: auto !important;
    line-height: 0.2rem !important;
}
</style>
