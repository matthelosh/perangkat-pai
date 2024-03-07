<script setup>
import { ref, computed, onBeforeMount } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue'
import { Doc, Text, Paragraph, Color,  Bold, Underline, Italic, BulletList, OrderedList, Strike, Highlight, Table } from 'element-tiptap-vue3-fixed'
const extensions = [
    Doc, Text, Paragraph,
    Color.configure({bubble: true}),
    Highlight.configure({bubble: true}),
    Strike.configure({bubble: true}),
    Bold.configure({bubble: true}),
    Underline.configure({bubble: true}),
    Italic.configure({bubble: true}),
    BulletList, OrderedList, Table
]
const page = usePage()
const props = defineProps({atp: Object})
const emit = defineEmits(['close'])

const ma = ref({
    pendahuluan: `<table class="w-full" width="100%" border="1" style="border-collapse:collapse;">
                    <thead>
                        <tr>
                            <th class="border border-black align-top p-2">Kegiatan Pembelajaran</th>    
                            <th class="border border-black align-top">Alokasi Waktu</th>    
                        </tr>    
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border border-black align-top p-2"></td>    
                            <td class="border border-black align-top p-2">10 Menit</td>    
                        </tr>    
                    </tbody>
        </table>`
})

onBeforeMount(() => {
    ma.value.atp_id = props.atp.kode
})
</script>

<template>
    <el-card>
        <template #header>
            <div class="toolbar flex items-center justify-between">
                <h3 class="flex items-center gap-2">
                    <Icon icon="mdi:arrow-left" class="text-2xl text-teal-700 hover:cursor-pointer" @click="emit('close')" />
                    Form Modul Ajar Kelas {{ page.props.rombel.label }}
                </h3>
                <div class="items flex items-center gap-2">
                    
                </div>
            </div>
        </template>
        <div class="card-body font-serif">
            <div class="title text-center uppercase font-bold mb-6">
                <h3 class="text-2xl">Modul Ajar</h3>
            </div>

            <div class="info-umum">
                <h2 class="text-xl uppercase font-bold mb-4">I. Informasi Umum</h2>

                <h3 class="uppercase font-bold">A. Identitas Modul</h3>
                <table>
                    <tr>
                        <td>Penyusun</td>
                        <td class="px-2">:</td>
                        <td>{{ page.props.user.userable.nama }}, {{ page.props.user.userable.gelar_belakang }}</td>
                    </tr>
                    <tr>
                        <td>Satuan Pendidikan</td>
                        <td class="px-2">:</td>
                        <td>{{ page.props.sekolahs[0].nama }}</td>
                    </tr>
                    <tr>
                        <td>Tahun Pelajaran</td>
                        <td class="px-2">:</td>
                        <td>{{ page.props.tapel.label}}</td>
                    </tr>
                    <tr>
                        <td>Mata Pelajaran</td>
                        <td class="px-2">:</td>
                        <td>PAI dan Budi Pekerti</td>
                    </tr>
                    <tr>
                        <td>Fase / Kelas</td>
                        <td class="px-2">:</td>
                        <td>{{ page.props.rombel.fase }} / {{ page.props.rombel.label }}</td>
                    </tr>
                    <tr>
                        <td>Alokasi Waktu</td>
                        <td class="px-2">:</td>
                        <td>{{ props.atp.aw }} JP x 35 Menit ({{ props.atp.aw / 4 }} Pertemuan)</td>
                    </tr>
                </table>

                <h3 class="uppercase font-bold mt-4">B. Kompetensi Awal</h3>
                <el-tiptap v-model:content="ma.kompetensi_awal" :extensions="extensions" />
                <h3 class="uppercase font-bold mt-4">C. Profil Pelajar Pancasila</h3>
                <el-tiptap v-model:content="ma.p5" :extensions="extensions" />
                <h3 class="uppercase font-bold mt-4">D. Sarana dan Prasarana</h3>
                <el-tiptap v-model:content="ma.sarpras" :extensions="extensions" />
                <h3 class="uppercase font-bold mt-4">E. Target Peserta Didik</h3>
                <el-select v-model="ma.target_siswa" placeholder="Pilih Target Siswa">
                    <el-option value="Peserta Didik Reguler" label="Peserta Didik Reguler" />
                    <el-option value="Peserta Didik Berkebutuhan Khusus" label="Peserta Didik Berkebutuhan Khusus" />
                </el-select>
                <h3 class="uppercase font-bold mt-4">F. Model Pembelajaran</h3>
                <el-select v-model="ma.model" placeholder="Pilih Model Pembelajaran">
                    <el-option value="Tatap Muka" label="Tatap Muka" />
                    <el-option value="Daring" label="Daring" />
                    <el-option value="Hybrid" label="Hybrid" />
                </el-select>
            </div>

            <div class="inti">
                <h2 class="text-xl uppercase font-bold my-4">II. Komponen Inti</h2>
                <h3 class="uppercase font-bold mt-4">A. Tujuan Pembelajaran</h3>
                <ul class="pl-4 list-decimal">
                    <li v-for="(tp, t) in props.atp.tps.split(';')">{{ tp }}</li>
                </ul>

                <h3 class="uppercase font-bold mt-4">B. Pemahaman Bermakna</h3>
                <el-tiptap v-model:content="ma.pemahaman" :extensions="extensions" />
                <h3 class="uppercase font-bold mt-4">C. Pertanyaan pemantik</h3>
                <el-tiptap v-model:content="ma.pertanyaan" :extensions="extensions" />
                <h3 class="uppercase font-bold mt-4">D. Persiapan Pembelajaran</h3>
                <el-tiptap v-model:content="ma.persiapan" :extensions="extensions" />
                <h3 class="uppercase font-bold mt-4">E. KEgiatan Pembelarajan</h3>
                <ul class="list-[lower-alpha] pl-8">
                    <li>
                        Kegiatan Pendahuluan
                        <el-tiptap v-model:content="ma.pendahuluan" :extensions="extensions" />
                    </li>
                    <li>
                        Kegiatan Inti
                        <el-tiptap v-model:content="ma.inti" :extensions="extensions" />
                    </li>
                    <li>
                        Kegiatan Penutup
                        <el-tiptap v-model:content="ma.penutup" :extensions="extensions" />
                    </li>
                </ul>

            </div>
        </div>
    </el-card>
</template>