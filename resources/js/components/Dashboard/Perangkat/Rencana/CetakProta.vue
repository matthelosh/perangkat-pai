<script setup>
import { ref, computed, defineAsyncComponent } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue'
import { cssLink } from '@/helpers/printHelper.js'

const Kop = defineAsyncComponent(() => import('@/Components/Umum/Kop.vue'))
const Ttd = defineAsyncComponent(() => import('@/Components/Umum/Ttd.vue'))

const page = usePage()
const props = defineProps({protas:Array})
const emit = defineEmits(['close'])

const params = computed(() => route().params)

const kode = (teks) => {
    teks = teks?.split("-").splice(1,3) ?? ['U','H']
    return teks.join("")
}

const cetak = () => {
    let el = document.querySelector(".cetak")

    let html = `
            <!doctype html>
            <html>
                <head>
                    <title>Program Tahunan</title>
                    <link href="${cssLink(page.props.app_env)}" rel="stylesheet" />
                </head>
                <body>
                    ${el.outerHTML}
                </body>
            </html>
    `
    let win = window.open("","_blank","width=800,height=700")
    win.document.write(html)
    setTimeout(() => {
        win.print()
    }, 500)    
}

</script>

<template>
    <div>
        <el-card>
            <template #header>
                <div class="toolbar flex items-center justify-between">
                    <h1 class="flex items-center gap-2">
                        <Icon icon="mdi:arrow-left" class="text-2xl text-teal-700" @click="emit('close')" />
                        Program Tahunan
                    </h1>
                    <div class="items flex items-center gap-2">
                        <el-button circle @click="cetak">
                            <Icon icon="mdi:printer" />
                        </el-button>
                    </div>
                </div>
            </template>
            <div class="cetak w-full p-4 font-serif">
                <Kop />
                <div class="title uppercase text-center font-bold my-6 font-serif">
                    <h3>PROGRAM TAHUNAN (PROTA)</h3>
                    <h3>Tahun Pelajaran {{ page.props.tapel.label }}</h3>
                </div>

                <div class="identitas">
                    <table>
                        <tr>
                            <td>Satuan Pendidikan</td>
                            <td class="px-2">:</td>
                            <td>{{ page.props.sekolahs[0].nama }}</td>
                        </tr>
                        <tr>
                            <td>Fase / Kelas</td>
                            <td class="px-2">:</td>
                            <td>{{ params.fase +' / '+ page.props.rombel.label }}</td>
                        </tr>
                    </table>
                </div>

                <div class="content my-6">
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th class="bg-slate-100 p-2 border border-black">No</th>
                                <th class="bg-slate-100 p-2 border border-black">Kode</th>
                                <th class="bg-slate-100 p-2 border border-black">Alur Tujuan Pembelajaran</th>
                                <th class="bg-slate-100 p-2 border border-black">Alokasi Waktu</th>
                                <th class="bg-slate-100 p-2 border border-black">Semester</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(prota, pr) in page.props.protas" :key="pr">
                                <td class="border border-black p-2 text-center">{{ pr+1 }}</td>
                                <td class="border border-black p-2 text-center">{{ kode(prota.atp?.kode)}}</td>
                                <td class="border border-black p-2">
                                    <ul v-if="prota.atp && prota.atp.tps" class="pl-4 list-disc">
                                        <li v-for="tp in prota.atp.tps.split(';')" :key="tp.kode">{{ tp }}</li>
                                    </ul>
                                    <p v-else-if="prota.atp && !prota.atp.tps" class="uppercase">{{ prota.atp.materi }}</p>
                                    <p v-else>{{ prota.atp_id }}</p>
                                </td>
                                <td class="border border-black p-2 text-center">{{ prota.aw }} JP</td>
                                <td class="border border-black p-2 text-center">{{ prota.semester + (prota.semester == '1' ? ' (Ganjil)' : ' (Genap)' )}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <Ttd />
            </div>
        </el-card>
    </div>
</template>