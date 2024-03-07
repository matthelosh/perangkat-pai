<script setup>
import { ref, computed } from 'vue';
import { usePage, Head, Link } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue'
import dayjs from 'dayjs'
import localeData from 'dayjs/plugin/localeData'
import 'dayjs/locale/id'
dayjs.extend(localeData)
dayjs.locale("id")

const page = usePage()

import DashLayout from '@/layouts/DashboardLayout.vue'

const mode = ref('list')
const formJurnal = ref(false)


</script>

<template>
<DashLayout title="Jurnal Pembelajaran">
    <el-card>
        <template #header>
            <div class="toolbar flex items-center justify-between">
                <h3 class="font-bold uppercase tracking-wide flex gap-2 text-blue-800">
                    <Link :href="appRoute('pelaksanaan')">
                        <Icon icon="mdi:arrow-left" class="text-2xl text-green-700" />
                    </Link>
                    Jurnal Pembelajaran Kelas {{ page.props.rombel.label }} Semester {{ page.props.semester.label }} Tapel {{ page.props.tapel.label }}
                </h3>
            </div>
        </template>
        <div class="card-body">
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="border border-black bg-slate-100 p-2">No</th>
                        <th class="border border-black bg-slate-100 p-2">Kode</th>
                        <th class="border border-black bg-slate-100 p-2">Elemen</th>
                        <th class="border border-black bg-slate-100 p-2">ATP</th>
                        <th class="border border-black bg-slate-100 p-2">AW</th>
                        <th class="border border-black bg-slate-100 p-2">Tanggal</th>
                        <th class="border border-black bg-slate-100 p-2">Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(prota, pr) in page.props.protas" :key="prota.id">
                        <td class="border border-black p-2 text-center">{{ pr+1 }}</td>
                        <td class="border border-black p-2 text-center">{{ prota.atp?.kode }}</td>
                        <td class="border border-black p-2">{{ prota.atp.elemen.label }}</td>
                        <td class="border border-black p-2">
                            <ul v-if="prota.atp.tps" class="list-disc pl-4">
                                <li v-for="(tp, t) in prota.atp.tps.split(';')" :key="t">
                                    {{ tp }}
                                </li>
                            </ul>
                            <p v-else>{{ prota.atp.materi }}</p>
                        </td>
                        <td class="border border-black p-2 text-center">{{ prota.aw }} JP</td>
                        <td class="border border-black p-2 text-center">{{ dayjs(prota.tanggal).format('DD/MM/YYYY') }}</td>
                        <td class="border border-black p-2 text-center">
                            <el-button circle type="primary" @click="formJurnal = !formJurnal">
                                <Icon icon="mdi:pencil" />
                            </el-button>
                            <el-button circle type="success">
                                <Icon icon="mdi:printer" />
                            </el-button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </el-card>

    <el-dialog  v-model="formJurnal">

    </el-dialog>
</DashLayout>
</template>