<script setup>
import { ref, computed } from "vue";
import { usePage, Link, router } from "@inertiajs/vue3";

import DashLayout from "@/layouts/DashboardLayout.vue";

const page = usePage();
const mode = ref("list");
</script>

<template>
    <DashLayout title="Perangkat Evaluasi">
        <el-row class="w-full" :gutter="20">
            <el-col
                :span="8"
                :md="8"
                :xl="4"
                v-for="(rombel, r) in page.props.rombels"
                :key="r"
            >
                <el-card class="mb-4">
                    <template #header>
                        <div class="toolbar flex items-center justify-between">
                            <h3 class="uppercase text-blue-800 font-bold">
                                Kelas {{ rombel.label }}
                            </h3>
                            <div class="items flex gap-2 items-center"></div>
                        </div>
                    </template>
                    <div class="card-body">
                        <ul class="list-[upper-roman] pl-4">
                            <li class="mb-2">
                                <Link
                                    :href="
                                        appRoute(
                                            'perangkat.evaluasi.formatif',
                                            { _query: { rombel: rombel.kode } }
                                        )
                                    "
                                    >Asesmen Formatif</Link
                                >
                            </li>
                            <li class="mb-2">
                                <Link
                                    :href="
                                        appRoute(
                                            'perangkat.evaluasi.sumatif.pts',
                                            { _query: { rombel: rombel.kode } }
                                        )
                                    "
                                    >Asesmen Tengah Semester</Link
                                >
                            </li>
                            <li class="mb-2">
                                <Link
                                    :href="
                                        appRoute(
                                            'perangkat.evaluasi.sumatif.pas',
                                            { _query: { rombel: rombel.kode } }
                                        )
                                    "
                                    >Asesmen Akhir Semester</Link
                                >
                            </li>
                        </ul>
                    </div>
                </el-card>
            </el-col>
        </el-row>
    </DashLayout>
</template>
