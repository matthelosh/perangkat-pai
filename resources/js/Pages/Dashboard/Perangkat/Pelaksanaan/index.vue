<script setup>
import { ref, computed, defineAsyncComponent } from 'vue';
import { usePage, Head, Link, router } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue';

const page = usePage()

import DashLayout from '@/layouts/DashboardLayout.vue'
</script>

<template>
    <DashLayout title="Pelaksanaan pembelajaran">
        <h3 class="text-center uppercase font-bold text-2xl tracking-wide">Perangkat Ajar</h3>
        <el-row class="w-full" :gutter="20">
            <el-col :span="8" v-for="(rombel, r) in page.props.rombels" :key="rombel.kode">
                <el-card class="my-2">
                    <template #header>
                        <h3 class="uppercase font-semibold text-blue-800">Kelas {{ rombel.label }}</h3>
                    </template>
                    <div class="card-body">
                        <ul class="pl-4 list-[lower-alpha]">
                            <li class="py-2">
                                <Link :href="appRoute('perangkat.modulajar', {_query: {rombel: rombel.kode}})">Modul Ajar</Link>
                            </li>
                            <li class="py-2">
                                <Link :href="appRoute('perangkat.presensi', {_query: {rombel: rombel.kode}})">Presensi Siswa</Link>
                            </li>
                            <li class="py-2">
                                <Link :href="appRoute('perangkat.jurnal', {_query: {rombel: rombel.kode}})">Jurnal Mengajar</Link>
                            </li>
                        </ul>
                    </div>
                </el-card>
            </el-col>
        </el-row>
    </DashLayout>
</template>