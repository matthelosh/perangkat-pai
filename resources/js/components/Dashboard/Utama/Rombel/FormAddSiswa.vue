<script setup>
import { ref, computed, onBeforeMount } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import { Icon } from '@iconify/vue';
import axios from 'axios'

const page = usePage();
const props = defineProps({ siswa: Object, rombel: Object });
const loading =ref(false)

const siswa = ref({});
const rombel = ref({});
const siswas = ref([]);
const cariSiswa = async() => {
    loading.value = true
    axios.get('/siswa/'+siswa.value.nisn).then( res => {
        siswa.value = res.data.siswa
    }).catch(err => {
    }).finally(() => loading.value = false )
}

const onSiswaAdded = () => {
    let data = siswa.value;
    data.rombel_id = rombel.value.id;
    data.sekolah_id = page.props.sekolahs[0].npsn;
    router.post(
        route("siswa.store"),
        { data: JSON.stringify(data) },
        {
            onSuccess: () => {
                siswa.value = {};
                // router.reload({ only: ["rombels"] });
            },
            onError: (err) => {
                Object.keys(err).forEach((k) => {
                    ElMessage({ type: "warning", message: err[k] });
                });
            },
        }
    );
};
onBeforeMount(() => {
    rombel.value = props.rombel;
});
</script>

<template>
    <div>
        <el-form v-model="siswa" label-width="100px">
            <el-form-item label="NISN">
                <el-input placeholder="Isikan NISN" v-model="siswa.nisn" />
            </el-form-item>
            <el-form-item label="Nama">
                <el-input
                    placeholder="Isikan Nama Lengkap"
                    v-model="siswa.nama"
                />
            </el-form-item>
            <el-form-item label="Jenis Kelamin">
                <el-select v-model="siswa.jk" placeholder="Plih Jenis Kelammin">
                    <el-option
                        v-for="jk in ['Laki-laki', 'Perempuan']"
                        :key="jk"
                        :value="jk"
                        >{{ jk }}</el-option
                    >
                </el-select>
            </el-form-item>
            <el-form-item>
                    <el-button @click="cariSiswa" :disabled="loading">
                        <Icon icon="mdi:magnify" />
                        Cari
                    </el-button>
                <el-button :native-type="null" @click="onSiswaAdded" :disabled="loading"
                    >Tambah</el-button
                >
            </el-form-item>
        </el-form>
        <el-table :data="rombel.siswas">
            <el-table-column label="NISN" prop="nisn" />
            <el-table-column label="Nama" prop="nama" />
            <el-table-column label="JK" prop="jk" />
        </el-table>
    </div>
</template>
