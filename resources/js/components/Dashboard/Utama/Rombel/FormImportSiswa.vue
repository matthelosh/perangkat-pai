<script setup>
import { watch, ref, onBeforeMount } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import { read, utils } from "xlsx";
import axios from "axios";

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

const prevRombels = ref([])
const prevRombel = ref({})

const impor = async () => {
    let data = {
        rombel_id: props.selectedRombel.id,
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

const getPrevRombel = async() => {
    axios.post(route('rombel.prev', { _query: {currentTapel: page.props.tapel.id, sekolah_id: page.props.sekolahs[0].npsn}}))
            .then(res => {
                prevRombels.value = res.data.rombels
            })
}

const onRombelSelected = (e) => {
    importedData.value = prevRombels.value[e].siswas
}

onBeforeMount(() => {
    // console.log(props.selectedRombel);
    rombel.value = props.selectedRombel;
    getPrevRombel()
});
</script>

<template>
    <div>
        <div
            class="w-full mb-4 h-10 bg-slate-200 flex items-center justify-between p-2"
        >
            {{ props.selectedRombel.label }}
            <div class="flex items-center gap-2">
                    <el-select placeholder="Dari Rombel Sebelumnya" width="250" style="width: 200px" @change="onRombelSelected">
                        <el-option v-for="(rombel, r) in prevRombels" :key="r" :value="r" :label="rombel.label"></el-option>
                    </el-select>
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
        </div>
        <el-table :data="importedData" stripe>
            <el-table-column label="NISN" prop="nisn" />
            <el-table-column label="Nama" prop="nama" />
            <el-table-column label="JK" prop="jk" />
        </el-table>
        <!-- {{ prevRombel.siswas }} -->
    </div>
</template>
