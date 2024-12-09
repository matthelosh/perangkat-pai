<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue';
import { capitalize } from 'lodash';
const props = defineProps({cps: Array})
const activeFase = ref('A')
const elHeaders = ref([
    { text: 'Kode', value: 'kode'},
    { text: 'Elemen', value: 'label' },
    { text: 'Deskripsi CP', value: 'deskripsi_cp' }
])

const loading = ref(false)
const formCp = ref(false)
const formElemen = ref(false)
const cp =ref({})
const elemen = ref({})
const editCp = (item) => {
    cp.value = item
    formCp.value = true
}

const editElemen = (el) => {
    elemen.value = el
    formElemen.value = true
}

const simpanCp = async() => {
    router.post(route('cp.store'), cp.value, {
        onStart: visit => {
            loading.value = true
        },
        onSuccess: page => {
            loading.value = false
            formCp.value = false
            router.reload({only: ['cps']})
        }
    })
}
const simpanELemen = async() => {
    router.post(route('elemen.store'), elemen.value, {
        onStart: visit => {
            loading.value = true
        },
        onSuccess: page => {
            loading.value = false
            formElemen.value = false
            router.reload({only: ['cps']})
        }
    })
}
</script>

<template>
    <div>
        <el-collapse v-model="activeFase" accordion>
            <h3 class="font-semibold text-lg text-blue-800">Elemen Capaian Pelajaran Setiap Fase</h3>
            <el-collapse-item v-for="(cp,c) in props.cps" :key="cp.fase" :name="cp.fase">
                <template #title>
                    <div class="flex gap-2">
                        <h3 class="text-lg font-semibold">Fase {{ cp.fase }}</h3> 
                        <div class="toolbar-items">

                        </div>
                    </div>
                </template>
                <div>
                    <p class="mb-4 text-justify group">
                        {{ cp.teks }}
                        <el-button circle type="warning" @click="editCp(cp)" >
                            <Icon icon="mdi:edit" />
                        </el-button>
                    </p>
                    <el-table :data="cp.elemens" stripe>
                        <el-table-column width="100" label="Kode" prop="kode" />
                        <el-table-column width="200" label="Elemen" prop="label" />
                        <el-table-column width="400" label="Deskripsi CP" prop="deskripsi_cp" />
                        <el-table-column label="Opsi">
                            <template #default="scope">
                                <div class="flex gap-2">
                                    <el-button circle type="warning" @click="editElemen(scope.row)">
                                        <Icon icon="mdi:edit" />
                                    </el-button>
                                    <el-button circle type="danger">
                                        <Icon icon="mdi:delete" />
                                    </el-button>
                                </div>
                            </template>
                        </el-table-column>
                    </el-table>
                </div>
            </el-collapse-item>
        </el-collapse>

        <el-dialog v-model="formCp" >
            <template #header>
                <h3>Edit CP Fase {{ cp.fase }}</h3>
            </template>
                <el-form v-model="cp" v-loading="loading">
                    <el-form-item label="Teks CP">
                        <el-input type="textarea" v-model="cp.teks" rows="10" placeholder="Isi Teks CP"></el-input>
                    </el-form-item>
                    <el-form-item>
                    <el-button type="primary" size="small" @click="simpanCp">Simpan</el-button>
                </el-form-item>
                </el-form>
        </el-dialog>
        <el-dialog v-model="formElemen" :title="`Edit Deskripsi CP ${elemen.label}`">
            <el-form v-model="elemen" label-width="150" v-loading="loading">
                <el-form-item label="Deskripsi CP">
                    <el-input v-model="elemen.deskripsi_cp" type="textarea"></el-input>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" size="small" @click="simpanELemen">Simpan</el-button>
                </el-form-item>
            </el-form>
        </el-dialog>
    </div>
</template>

<style scoped>
.el-dialog, header.el-dialog__header {

    padding: 0!important;
}
.el-dialog header.el-dialog__header {
    margin: 0!important;
}
</style>