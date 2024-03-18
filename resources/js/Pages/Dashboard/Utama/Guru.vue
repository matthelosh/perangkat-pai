<script setup>
import { ref } from 'vue';
import { Head, usePage, router } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
import Layout from '@/layouts/DashboardLayout.vue';
import { ElNotification } from 'element-plus'

const page = usePage()
const loading=ref(false)
const showDialog = ref(false)
const importDialog = ref(false)
const guru = ref({})
const akun = ref({})
const fotoUrl = ref('/img/guru-default.png')
const fileFoto = ref(null)
const importedData = ref([])
const showAccountForm = ref(false)

const addAccount = async(item) => {
    akun.value.id = item.id
    akun.value.nip = item.nip
    akun.value.email = item.belajar_id
    akun.value.name = item.nama
    akun.value.password = '12345'
    showAccountForm.value = true
}

const createAccount = async() => {
    router.post(route('user.store', 
                    {_query:{type: 'App\\Models\\Guru'}}), 
                    {data: JSON.stringify(akun.value)}, {
                        onStart: ()=>loading.value=true,
                        onSuccess: (page) => {
                            loading.value = false
                            router.reload({only: ['gurus']})
                            akun.value = {}
                            showAccountForm.value = false
                        }
                    })
}

const simpan = async() => {
    
    let fd = new FormData()
    fd.append("data",JSON.stringify(guru.value))
    if (fileFoto !== null) {
        fd.append("fileFoto", fileFoto.value)
    }
    
    router.post(route('guru.store'), fd, {
        onStart: ()=> {loading.value = true},
        onSuccess: (page) => {
            ElNotification({title: 'Info', message: 'Data Guru Disimpan', type: 'success'})
        },
        onFinish: () => {
            loading.value = false
            showDialog.value = false
            fotoUrl.value = '/img/guru-default.png'
        }
    })
}

const onFormGuruClosed = () => {
    guru.value = {}
    fotoUrl.value = '/img/guru-default.png'
    fileFoto.value = null
}

const impor = async() => {
    router.post(route('guru.import'), {datas: JSON.stringify(importedData.value)}, {
        onStart: () => loading.value=true,
        onSuccess: (page) => router.reload({only:['gurus']}),
        onError: (err) => console.log(err)
    })
}

const onFotoPicked = async(e)=>{
    let foto = e.target.files[0]
    fileFoto.value = foto
    fotoUrl.value = URL.createObjectURL(foto)
}


const defaultImg = () => fotoUrl.value

const edit = (item) => {
    guru.value = item
    guru.value.sekolah_id = ''
    if(item.foto) {
        fotoUrl.value = item.foto
    }
    showDialog.value = true
}

const hapus = async(id) => {
    router.delete(route('guru.destroy', {id: id}))
}
</script>

<template>
<Layout title="Data Guru PAI">
    <div class="wrapper w-full">
        <el-card>
            <template #header>
                <div class="toolbar flex items-center justify-between">
                    <h3>Data Guru PAI</h3>

                    <el-button-group>
                        <el-popover placement="top-end" content="Impor data">
                            <template #reference>
                                <el-button @click="importDialog=true">
                                    <Icon icon="mdi:text-box-multiple" />
                                </el-button>
                            </template>
                        </el-popover>
                        <el-popover placement="top-end" content="Tambah baru">
                            <template #reference>
                                <el-button @click="showDialog=true">
                                    <Icon icon="mdi:plus" />
                                </el-button>
                            </template>
                        </el-popover>
                    </el-button-group>
                </div>
            </template>
            <div class="card-body">
                <el-table :data="page.props.gurus" height="75vh">
                    <el-table-column label="Foto">
                        <template #default="scope">
                            <el-avatar circle :size="38" :src="scope.row.foto ?? fotoUrl" @error="defaultImg"></el-avatar>
                            <!-- {{ scope.row.foto }} -->
                        </template>
                    </el-table-column>
                    <el-table-column label="Akun">
                        <template #default="scope">
                            {{ scope.row.account?.name }}
                        </template>
                    </el-table-column>
                    <el-table-column prop="nip" label="NIP"></el-table-column>
                    <el-table-column label="Nama" >
                        <template #default="scope">
                            <el-link type="primary" @click="edit(scope.row)">{{ scope.row.nama }}</el-link>
                        </template>
                    </el-table-column>
                    <el-table-column prop="jk" label="JK"></el-table-column>
                    <el-table-column prop="alamat" label="Alamat"></el-table-column>
                    <el-table-column prop="hp" label="No. HP"></el-table-column>
                    <el-table-column label="Satmikal">
                        <template #default="scope">
                            <small>{{ scope.row.sekolahs?.map(s => s.nama) }}</small>
                        </template>
                    </el-table-column>
                    <el-table-column label="Opsi">
                        <template #default="scope">
                            <div>
                                <el-popconfirm title="Yakin menghapus guru" width="200px" confirm-button-text="Yakin" cancel-button-text="Batal" @confirm="hapus(scope.row.id)">
                                    <template #reference>
                                        <el-button text type="danger">
                                            <Icon icon="mdi:delete" />
                                        </el-button>
                                    </template>
                                </el-popconfirm>
                                <el-tooltip  content="Reset Password" placement="top-end" v-if="scope.row.account" >
                                    <el-button text type="warning">
                                        <Icon icon="mdi:account-sync-outline" />
                                    </el-button>
                                </el-tooltip>
                                <el-tooltip content="Buatkan Akun" placement="top-end" v-if="!scope.row.account" >
                                    <el-button  text type="info" @click="addAccount(scope.row)">
                                        <Icon icon="mdi:account-plus" />
                                    </el-button>
                                </el-tooltip>
                            </div>
                        </template>
                    </el-table-column>
                </el-table>
            </div>
        </el-card>
    </div>

    <el-dialog v-model="showDialog" @closed="onFormGuruClosed" :draggable="true">
        <template #header>Form Guru</template>
        <div class="dialog-body">
            <el-form v-model="guru" label-position="top">
                <el-row class="w-ful bg-slate-100 mb-3" align="middle" justify="center">
                    <input type="file" ref="fotoInput" @change="onFotoPicked" class="hidden">
                    <el-image :src="fotoUrl" fit="cover" class="w-[100px] h-[100px] bg-slate-200 rounded-full my-6" @click="$refs.fotoInput.click()">
                    </el-image>
                </el-row>
                <el-row class="w-full" :gutter="10">
                    <el-col :span="8">
                        <el-form-item label="NIP" class="w-full">
                            <el-input type="text" style="width:100%;" placeholder="Masukkan NIP (jika ada)" v-model="guru.nip"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="8">
                        <el-form-item label="NUPTK" class="w-full">
                            <el-input type="text" style="width:100%;" placeholder="Masukkan NUPTK (jika ada)" v-model="guru.nuptk"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="8">
                        <el-form-item label="No Sertifikat PPG" class="w-full">
                            <el-input type="text" style="width:100%;" placeholder="Masukkan No Sertifikat (jika ada)" v-model="guru.no_sertifikat"></el-input>
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row class="w-full" :gutter="10">
                    <el-col :span="6">
                        <el-form-item class="w-full" label="NRG">
                            <el-input type="text" style="width:100%;" placeholder="NRG (jika ada)" v-model="guru.nrg"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="18">
                        <el-form-item class="w-full" label="Nama dan Gelar Pendidikan">
                            <el-row :gutter="5" class="w-full">
                                <el-col :span="4">
                                    <el-input type="text" style="width:100%;" placeholder="G. depan" v-model="guru.gelar_depan"></el-input>
                                </el-col>
                                <el-col :span="14">
                                    <el-input type="text" style="width:100%;" placeholder="Nama Lengkap tanpa gelar" v-model="guru.nama" required></el-input>
                                </el-col>
                                <el-col :span="6">
                                    <el-input type="text" style="width:100%;" placeholder="G. Belakang" v-model="guru.gelar_belakang"></el-input>
                                </el-col>
                            </el-row>
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row class="w-full" :gutter="10">
                    <el-col :span="4">
                        <el-form-item class="w-full" label="Jenis Kelamin">
                            <el-select style="width:100%;" v-model="guru.jk" placeholder="Pilih JK">
                                <el-option v-for="jk in ['Laki-laki', 'Perempuan']" :key="jk" :value="jk">{{ jk }}</el-option>
                            </el-select>
                        </el-form-item>
                    </el-col>
                    <el-col :span="8">
                        <el-form-item class="w-full" label="Alamat">
                            <el-input type="textarea" rows="1" placeholder="Alamat Rumah" style="width:100%" v-model="guru.alamat"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="4">
                        <el-form-item class="w-full" label="Status">
                            <el-select style="width:100%;" v-model="guru.status_kepegawaian" placeholder="Pilih Status Pegawai">
                                <el-option v-for="status in ['pns', 'p3k', 'gtt']" :key="status" :value="status">{{ status.toUpperCase() }}</el-option>
                            </el-select>
                        </el-form-item>
                    </el-col>
                    <el-col :span="8">
                        <el-form-item label="Satmikal">
                            <el-select v-model="guru.sekolah_id" placeholder="Pilih Sekolah" multiple>
                                <el-option v-for="sekolah in page.props.sekolahs" :key="sekolah.npsn" :value="sekolah.npsn" :label="sekolah.nama">{{sekolah.nama}}</el-option>
                            </el-select>
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row class="w-full" :gutter="10">
                    <el-col :span="10">
                        <el-form-item label="No. HP">
                            <el-input placeholder="No. HP" v-model="guru.hp"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item label="Akun Belajar.id">
                            <el-input type="email" placeholder="Akun Belajar ID" v-model="guru.belajar_id"></el-input>
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row class="w-full mt-4" align="middle" justify="center">
                    <el-button type="primary" :loading="loading" @click="simpan">Simpan</el-button>
                </el-row>
            </el-form>
        </div>
    </el-dialog>
    
    <el-dialog v-model="importDialog" fullscreen >
        <template #header>
            <h3>Impor Data guru</h3>
        </template>
        <div class="dialog-body">
            <el-button type="primary" :loading="loading" @click="impor">Impor</el-button>
            <vue-excel-editor v-model="importedData" class="w-full">
                <vue-excel-column label="NIP" field="nip" type="string" />
                <vue-excel-column label="NUPTK" field="nuptk" type="string" />
                <vue-excel-column label="Sertifikat" field="no_sertifikat" type="string" />
                <vue-excel-column label="NRG" field="nrg" type="string" />
                <vue-excel-column label="Gelar Depan" field="gelar_depan" type="string" />
                <vue-excel-column label="Nama Lengkap" field="nama" type="string" />
                <vue-excel-column label="Gelar Belakang" field="gelar_belakang" type="string" />
                <vue-excel-column label="JK" field="jk" type="string" />
                <vue-excel-column label="Alamat" field="alamat" type="string" />
                <vue-excel-column label="NPSN Sekolah" field="sekolah_id" type="string" />
                <vue-excel-column label="Status" field="status_kepegawaian" type="select" :options="['pns','p3k','gtt']" />
                <vue-excel-column label="ID Belajar" field="belajar_id" type="string" />
            </vue-excel-editor>
        </div>
    </el-dialog>

    <el-dialog v-model="showAccountForm" @closed="akun={}">
        <template #header>
            <h3>Form Akun {{ akun.name }} #{{ akun.id }}</h3>
        </template>
        <div class="dialog-body">
            <el-form v-model="akun" label-width="100px">
                <el-form-item label="Username" >
                    <el-input v-model="akun.name" placeholder="Username"></el-input>
                </el-form-item>
                <el-form-item label="Email" >
                    <el-input v-model="akun.email" placeholder="Email"></el-input>
                </el-form-item>
                <el-form-item label="Password" >
                    <el-input v-model="akun.password" placeholder="Password"></el-input>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="createAccount">Buat Akun</el-button>
                </el-form-item>
            </el-form>
        </div>
    </el-dialog>
</Layout>
</template>