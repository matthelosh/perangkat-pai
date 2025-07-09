<script setup>
import { watch, ref, onBeforeMount } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import { ElNotification } from 'element-plus';

const page = usePage();
const props = defineProps({ show: Boolean, selectedRombel: Object });
const emit = defineEmits(["close"]);

const loading = ref(false);
// const shown = ref(false);
const rombel = ref({});
const romans = ref(["0", "I", "II", "III", "IV", "V", "VI"]);
const hurufs = ref(["", "SATU", "DUA", "TIGA", "EMPAT", "LIMA", "ENAM"]);

watch(
    rombel,
    (newV, oldV) => {
        rombel.value.fase =
            newV.tingkat >= 5 ? "C" : newV.tingkat >= 3 ? "B" : "A";
        rombel.value.kode =
            typeof newV.pararel === "undefined" || newV.pararel == "0"
                ? page.props.sekolahs[0].npsn +
                  "-" +
                  page.props.tapel.kode +
                  "-" +
                  newV.tingkat
                : page.props.tapel.kode +
                  "-" +
                  page.props.sekolahs[0].npsn +
                  "-" +
                  newV.tingkat +
                  newV.pararel;
        rombel.value.label =
            typeof newV.pararel === "undefined" || newV.pararel == "0"
                ? romans.value[newV.tingkat] +
                  " (" +
                  hurufs.value[newV.tingkat] +
                  ")"
                : romans.value[newV.tingkat] +
                  newV.pararel +
                  " (" +
                  hurufs.value[newV.tingkat] +
                  " " +
                  newV.pararel +
                  ")";
    },
    { deep: true }
);

const simpan = async () => {
    router.post(
        route("rombel.store"),
        { data: JSON.stringify(rombel.value) },
        {
            onStart: () => (loading.value = true),
            onSuccess: () => {
                loading.value = false;
                emit("close");
            },
            onError: (err) => {
                ElNotification({ title: "Error", message: err.message });
            },
        }
    );
};

onBeforeMount(() => {
    // shown.value = props.show;
});
</script>

<template>
    <div class="dialog-body">
        <el-form v-model="rombel" label-position="top">
            <el-row :gutter="10">
                <el-col :span="8">
                    <el-form-item label="Tingkat">
                        <el-select
                            v-model="rombel.tingkat"
                            placeholder="Pilih Tingkat"
                        >
                            <el-option
                                v-for="tingkat in [1, 2, 3, 4, 5, 6]"
                                :key="tingkat"
                                :value="tingkat"
                                >{{ tingkat }}</el-option
                            >
                        </el-select>
                    </el-form-item>
                </el-col>
                <el-col :span="8">
                    <el-form-item label="Pararel">
                        <el-select
                            v-model="rombel.pararel"
                            placeholder="Pilih Pararel"
                        >
                            <el-option
                                v-for="pararel in ['0', 'A', 'B', 'C']"
                                :key="pararel"
                                :value="pararel"
                                >{{ pararel }}</el-option
                            >
                        </el-select>
                    </el-form-item>
                </el-col>
                <el-col :span="8">
                    <el-form-item label="Fase">
                        <el-select
                            v-model="rombel.fase"
                            placeholder="Pilih Fase"
                            readonly
                        >
                            <el-option
                                v-for="(fase, f) in ['A', 'B', 'C']"
                                :key="f"
                                :value="fase"
                                >{{ fase }}</el-option
                            >
                        </el-select>
                    </el-form-item>
                </el-col>
            </el-row>
            <el-row :gutter="10">
                <el-col :span="8">
                    <el-form-item label="Label">
                        <el-input
                            placeholder="Label"
                            v-model="rombel.label"
                        ></el-input>
                    </el-form-item>
                </el-col>
                <el-col :span="8">
                    <el-form-item label="Kode Rombel">
                        <el-input
                            placeholder="Kode Rombel"
                            v-model="rombel.kode"
                        ></el-input>
                    </el-form-item>
                </el-col>
                <el-col :span="8">
                    <el-form-item label="&nbsp;">
                        <el-button
                            :native-type="null"
                            type="primary"
                            :loading="loading"
                            @click="simpan"
                            >Simpan</el-button
                        >
                    </el-form-item>
                </el-col>
            </el-row>
        </el-form>
    </div>
</template>
