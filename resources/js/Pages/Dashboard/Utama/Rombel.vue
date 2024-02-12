<script setup>
import { ref, computed, defineAsyncComponent, watch } from 'vue';
import { usePage, Head, router } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
import { ElMessage, ElMessageBox } from 'element-plus'
import Layout from '@/layouts/DashboardLayout.vue';

const page = usePage()

const loading = ref(false)
const showFormPlus = ref(false)
const rombel = ref({})
const romans = ref(['0', 'I', 'II','III','IV','V','VI'])
const hurufs = ref(['', 'SATU', 'DUA', 'TIGA','EMPAT','LIMA','ENAM'])
const activeName = ref('1')

const simpan = async() => {
    router.post(route('rombel.store'), {data: JSON.stringify(rombel.value)}, {
        onStart: () => loading.value = true,
        onSuccess: () => {
            loading.value = false
            router.reload({only: ['rombels']})
        },
        onError: (err) => {
            ElNotification({title: 'Error', message: err.message})
        }
    })
}

watch(rombel, (newV, oldV) => {
    if (showFormPlus) {
        rombel.value.fase = newV.tingkat >=5 ? 'C' : (newV.tingkat >=3 ? 'B' : 'A')
        rombel.value.kode = (typeof(newV.pararel) === 'undefined' || newV.pararel == '0') ? (page.props.tapel.kode+'-'+page.props.sekolahs[0].npsn+'-'+newV.tingkat) : (page.props.tapel.kode+'-'+page.props.sekolahs[0].npsn+'-'+newV.tingkat+newV.pararel)
        rombel.value.label = (typeof(newV.pararel) === 'undefined' || newV.pararel == '0') ? (romans.value[newV.tingkat]+' ('+hurufs.value[newV.tingkat]+')') : (romans.value[newV.tingkat]+newV.pararel+' ('+hurufs.value[newV.tingkat]+' '+newV.pararel+')')
    }
}, {deep: true});

const hapus = async(item) => {
    ElMessageBox.confirm(`Yakin menghapus rombel ${rombel.label}?`, 'Warning',
        {
            confirmButtonText: "Ya",
            cancelButtonText: "Batal",
            type: 'warning'
        }
    ).then(() => {
        router.delete(route('rombel.destroy',{id:item.id}), {
            onSuccess: () => ElMessage({type: 'info', message: 'Rombel dihapus'}),
            onError: (err) => {
                Object.keys(err).forEach(k=> {
                    ElMessage({type: 'warning', message: err[k]})
                })
            }
        })
    }).catch(() => {
        ElMessage({type: 'info', message: 'Batal Menghapus Rombel'})
    })
}

const onFormPlusClose = () => {

    rombel.value = {}
}

const edit = (item) => {
    rombel.value = item
    showFormPlus.value = true
}

</script>

<template>
    <Layout title="Data Rombel">
        <el-card>
            <template #header>
                <div class="toolbar flex items-center justify-between">
                    <h3> Data Rombel </h3>
                    <div class="toolbar-items flex gap-1">
                        <el-button-group>
                            <el-tooltip content="Tambah Rombel" placement=top-end>
                                <el-button @click="showFormPlus=true">
                                    <Icon icon="mdi:plus" />
                                </el-button>
                            </el-tooltip>
                            <el-tooltip content="Cetak" placement=top-end>
                                <el-button>
                                    <Icon icon="mdi:printer" />
                                </el-button>
                            </el-tooltip>
                        </el-button-group>
                    </div>
                </div>
            </template>
            <div class="card-body">
                <el-collapse v-model="activeName" accordion>
                    <el-collapse-item v-for="(item, r) in page.props.rombels" :key="item.kode" :name="r+1">
                        <template #title>
                            <div class="title group flex gap-2" >
                                <span>Kelas {{item.label}}</span>
                                <span class="hidden group-hover:inline flex gap-2">
                                    <el-link type="primary" :underline="false" class="mr-2" @click.stop="edit(item)">Edit?</el-link>
                                    <el-link type="danger" :underline="false" @click.stop="hapus(item)">Hapus?</el-link>
                                </span>
                            </div>
                        </template>
                        <div>
                            <el-row :gutter="10">
                                <el-col :span="12">
                                    <h4>Data Siswa:</h4>
                                    <p>Laki-laki: <span v-if="item.siswas">{{item.siswas.filter(siswa => siswa.jk == 'Laki-laki').length}}</span> | Perempuan: <span v-if="item.siswas">{{item.siswas.filter(siswa => siswa.jk == 'Perempuan').length}}</span> | Total: <span v-if="item.siswas">{{item.siswas.length}}</span></p>
                                </el-col>
                                <el-col :span="12" justify="end">
                                    <el-button-group>
                                        <el-tooltip content="Tambah Peserta Didik Baru" placement="top-end">
                                            <el-button type="primary">
                                                <Icon icon="mdi:account-plus" />
                                            </el-button>
                                        </el-tooltip>
                                        <el-tooltip content="Import Data Peserta Didik Baru" placement="top-start">
                                            <el-button type="success">
                                                <Icon icon="mdi:file-multiple" />
                                            </el-button>
                                        </el-tooltip>
                                    </el-button-group>
                                </el-col>
                            </el-row>

                        </div>
                        <el-table :data="item.siswas ?? []">
                            <el-table-column label="NISN" prop="nisn" />
                            <el-table-column label="Nama" prop="nama" />
                            <el-table-column label="JK" prop="jk" />
                        </el-table>
                    </el-collapse-item>
                </el-collapse>
            </div>
        </el-card>

        <el-dialog v-model="showFormPlus" draggable @closed="onFormPlusClose">
            <template #header>
                <h3> Formulir Rombel </h3>
            </template>
            <div class="dialog-body">
                <el-form v-model="rombel" label-position="top">
                    <el-row :gutter="10">
                        <el-col :span="8">
                            <el-form-item label="Tingkat">
                                <el-select v-model="rombel.tingkat" placeholder="Pilih Tingkat">
                                    <el-option v-for="tingkat in [1,2,3,4,5,6]" :key="tingkat" :value="tingkat">{{tingkat}}</el-option>
                                </el-select>
                            </el-form-item>
                        </el-col>
                        <el-col :span="8">
                            <el-form-item label="Pararel">
                                <el-select v-model="rombel.pararel" placeholder="Pilih Pararel">
                                    <el-option v-for="pararel in ['0','A','B','C']" :key="pararel" :value="pararel">{{pararel}}</el-option>
                                </el-select>
                            </el-form-item>
                        </el-col>
                        <el-col :span="8">
                            <el-form-item label="Fase">
                                <el-select v-model="rombel.fase" placeholder="Pilih Fase" readonly>
                                    <el-option v-for="(fase, f ) in ['A','B','C']" :key="f" :value="fase">{{fase}}</el-option>
                                </el-select>
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-row :gutter="10">
                        <el-col :span="8">
                            <el-form-item label="Label">
                                <el-input placeholder="Label" v-model="rombel.label"></el-input>
                            </el-form-item>
                        </el-col>
                        <el-col :span="8">
                            <el-form-item label="Kode Rombel">
                                <el-input placeholder="Kode Rombel" v-model="rombel.kode"></el-input>
                            </el-form-item>
                        </el-col>
                        <el-col :span="8">
                            <el-form-item label="&nbsp;">
                                <el-button type="primary" :loading="loading" @click="simpan">Simpan</el-button>
                            </el-form-item>
                        </el-col>
                    </el-row>
                </el-form>
            </div>
        </el-dialog>
    </Layout>
</template>
