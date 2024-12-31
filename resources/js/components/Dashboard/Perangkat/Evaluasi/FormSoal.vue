<script setup>
import { Icon } from "@iconify/vue";
import { defineAsyncComponent, onBeforeMount, ref } from "vue";

const props = defineProps({ selectedSoal: Object, open: Boolean });
const emit = defineEmits(["close"]);
const tingkats = ref(["1", "2", "3", "4", "5", "6"]);
const tps = ref([]);
const elemens = ref([]);
const TextEditor = defineAsyncComponent(() =>
    import("@/components/Umum/TextEditor.vue")
);
const Option = defineAsyncComponent(() =>
    import("@/components/Umum/SimpleText.vue")
);

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
    console.log(newSoal.value);
};

onBeforeMount(() => {
    show.value = props.open;
    if (props.selectedSoal) {
        newSoal.value = {
            tingkat: String(props.selectedSoal.tingkat || ""),
            elemen_id: String(props.selectedSoal.elemen_id || ""),
            tp_id: String(props.selectedSoal.tp_id || ""),
        };
    }
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
            class="content max-h-[95vh] min-h-[86vh] overflow-y-auto max-w-[100vw] overflow-x-hidden"
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
                    <el-col :span="24" :sm="16">
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
                </el-row>
                <el-row :gutter="10">
                    <el-col :span="24">
                        <el-form-item label="Tujuan Pembelajaran">
                            <el-select
                                v-model="newSoal.tp_id"
                                placeholder="Pilih TP"
                                size="large"
                                value-key="kode"
                                :disabled="tps.length < 1"
                                filterable
                            >
                                <el-option
                                    v-for="tp in tps"
                                    :value="tp.kode"
                                    class="wrapped-option"
                                    :label="tp.teks"
                                >
                                </el-option>
                            </el-select>
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row :gutter="10">
                    <el-col :span="24" :sm="12">
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
                    <el-col :span="24" :sm="12">
                        <el-form-item label="Level Soal">
                            <el-select
                                v-model="newSoal.level"
                                placeholder="Pilih Level Soal"
                                size="large"
                            >
                                <el-option
                                    v-for="level in ['lot', 'mot', 'hot']"
                                    :value="level"
                                    :label="level.toUpperCase()"
                                ></el-option>
                            </el-select>
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row :gutter="10">
                    <el-col>
                        <h3>Pertanyaan</h3>
                        <TextEditor v-model="newSoal.pertanyaan" />
                    </el-col>
                </el-row>
                <el-row :gutter="10" v-if="newSoal.tipe == 'pilihan'">
                    <el-col>
                        <h3 class="mt-4 font-bold text-sky-800">Pilihan A</h3>
                        <Option v-model="newSoal.a" />
                    </el-col>
                    <el-col>
                        <h3 class="mt-4 font-bold text-sky-800">Pilihan B</h3>
                        <Option v-model="newSoal.b" />
                    </el-col>
                    <el-col>
                        <h3 class="mt-4 font-bold text-sky-800">Pilihan C</h3>
                        <Option v-model="newSoal.c" />
                    </el-col>
                    <el-col>
                        <h3 class="mt-4 font-bold text-sky-800">Pilihan D</h3>
                        <Option v-model="newSoal.d" />
                    </el-col>
                </el-row>
                <el-row>
                    <el-col>
                        <el-form-item label="Kunci Jawaban" class="mt-6">
                            <!-- <el-radio-group
                                v-model="newSoal.kunci"
                                v-if="newSoal.tipe == 'pilihan'"
                            >
                                <el-radio :value="'a'">A</el-radio>
                                <el-radio :value="'b'">B</el-radio>
                                <el-radio :value="'c'">C</el-radio>
                                <el-radio :value="'d'">D</el-radio>
                            </el-radio-group> -->
                            <div
                                v-if="newSoal.tipe == 'pilihan'"
                                class="flex justify-between items-center w-full mr-8 text-lg"
                            >
                                <label
                                    for="radio"
                                    v-for="v in ['a', 'b', 'c', 'd']"
                                    class="flex items-center gap-1"
                                >
                                    <input
                                        type="radio"
                                        :value="v"
                                        v-model="newSoal.kunci"
                                    />
                                    {{ v.toUpperCase() }}
                                </label>
                            </div>
                            <Option v-else v-model="newSoal.kunci"></Option>
                        </el-form-item>
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
