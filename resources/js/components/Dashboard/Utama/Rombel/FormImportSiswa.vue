<script setup>
import { watch, ref, onBeforeMount } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import { read, utils } from "xlsx";

const page = usePage();
const props = defineProps({ selectedRombel: Object });
const emit = defineEmits(["close"]);

const loading = ref(false);
const rombel = ref({});
const importedData = ref([]);
const inputFile = ref(null);

const onFilePicked = async (e) => {
    let file = e.target.files[0];
    const ab = await file.arrayBuffer();

    const wb = read(ab);

    const ws = wb.Sheets[wb.SheetNames[0]];
    const datas = utils.sheet_to_json(ws);
    let res = [];
    datas.forEach((item, i) => {
        res.push({ nisn: item.nisn, nama: item.nama, jk: item.jk });
    });

    importedData.value = res;
    // console.log(data)
};

const impor = async () => {
    let data = {
        rombel_id: rombel.value.id,
        sekolah_id: page.props.sekolahs[0].npsn,
        datas: JSON.stringify(importedData.value),
    };
    let form = document.querySelector("#formFile");
    router.post(route("siswa.impor"), data, {
        onStart: () => (loading.value = true),
        onSuccess: () => {
            importedData.value = [];
            rombel.value = {};
            form.reset();
            emit("close");
        },
    });
};

onBeforeMount(() => {
    // console.log(props.selectedRombel);
    rombel.value = props.selectedRombel;
});
</script>

<template>
    <div>
        <div
            class="w-full mb-4 h-10 bg-slate-200 flex items-center justify-between p-2"
        >
            <form id="formFile">
                <input
                    type="file"
                    @change="onFilePicked"
                    accept=".xlsx, .xls, .ods, .csv"
                    ref="inputFile"
                />
            </form>
            <el-button
                type="primary"
                @click="impor"
                v-if="importedData.length > 0"
                >Impor</el-button
            >
        </div>
        <el-table :data="importedData" stripe>
            <el-table-column label="NISN" prop="nisn" />
            <el-table-column label="Nama" prop="nama" />
            <el-table-column label="JK" prop="jk" />
        </el-table>
    </div>
</template>
