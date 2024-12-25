<script setup>
import DashLayout from "@/layouts/DashboardLayout.vue";
import { Icon } from "@iconify/vue";
import { usePage } from "@inertiajs/vue3";
import { computed, defineAsyncComponent, ref } from "vue";

const page = usePage();
const Analisis = defineAsyncComponent(() =>
    import("@/components/Dashboard/Perangkat/Evaluasi/Analisis.vue")
);
const showAnalisis = ref(false);
const selectedAsesmen = ref(null);
const openAnalisis = (item) => {
    selectedAsesmen.value = item;
    showAnalisis.value = true;
};
const closeAnalisis = (item) => {
    selectedAsesmen.value = null;
    showAnalisis.value = false;
};
</script>

<template>
    <DashLayout>
        <!-- {{ page.props.asesmens }} -->
        <Analisis
            :asesmen="selectedAsesmen"
            :rombel="page.props.rombel"
            :open="showAnalisis"
            @close="closeAnalisis"
            v-if="showAnalisis"
        />
        <el-alert
            v-if="page.props.asesmens.length < 1"
            :closable="false"
            type="error"
            title="Belum ada Asesmen Formatif"
            description="Buat dulu di menu Utama > Asesmen"
        ></el-alert>
        <el-row v-else>
            <el-col>
                <el-card>
                    <template #header>
                        Data Asesmen Formatif Kelas
                        {{ page.props.rombel.label }} -
                        {{ page.props.rombel.tapel }}</template
                    >
                    <template #default>
                        <el-table :data="page.props.asesmens">
                            <el-table-column
                                label="No"
                                type="index"
                            ></el-table-column>
                            <el-table-column
                                label="Judul"
                                prop="label"
                            ></el-table-column>
                            <el-table-column label="Jml Soal">
                                <template #default="{ row }">
                                    <span
                                        >PG:
                                        {{ row.jml_soal.split(",")[0] }}</span
                                    >,
                                    <span
                                        >Isian:
                                        {{ row.jml_soal.split(",")[1] }}</span
                                    >,
                                    <span
                                        >Uraian:
                                        {{ row.jml_soal.split(",")[2] }}</span
                                    >,
                                </template>
                            </el-table-column>
                            <el-table-column label="Kunci PG">
                                <template #default="{ row }">
                                    {{ row.kunci }}
                                </template>
                            </el-table-column>
                            <el-table-column label="Opsi">
                                <template #default="{ row }">
                                    <el-button @click="openAnalisis(row)">
                                        <Icon icon="mdi:magnify" />
                                        Analisis</el-button
                                    >
                                </template>
                            </el-table-column>
                        </el-table>
                    </template>
                </el-card>
            </el-col>
        </el-row>
    </DashLayout>
</template>
