<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue';
const props = defineProps({cps: Array})
const activeFase = ref('A')
const elHeaders = ref([
    { text: 'Kode', value: 'kode'},
    { text: 'Elemen', value: 'label' },
    { text: 'Deskripsi CP', value: 'deskripsi_cp' }
])
</script>

<template>
    <div>
        <el-collapse v-model="activeFase" accordion>
            <h3 class="font-semibold text-lg text-blue-800">Elemen Capaian Pelajaran Setiap Fase</h3>
            <el-collapse-item v-for="(cp,c) in props.cps" :key="cp.fase" :name="cp.fase">
                <template #title>
                    <div class="flex gap-2">
                        <h3 class="text-lg font-semibold">{{ cp.fase }}</h3> 
                        <div class="toolbar-items">

                        </div>
                    </div>
                </template>
                <div>
                    <p class="mb-4 text-justify">{{ cp.teks }}</p>
                    <el-table :data="cp.elemens" stripe>
                        <el-table-column width="100" label="Kode" prop="kode" />
                        <el-table-column width="200" label="Elemen" prop="label" />
                        <el-table-column width="400" label="Deskripsi CP" prop="deskripsi_cp" />
                        <el-table-column label="Opsi">
                            <template #default="scope">
                                <div class="flex gap-2">
                                    <el-button circle type="warning">
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
    </div>
</template>