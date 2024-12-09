<script setup>
import { ref } from 'vue';
import { Head, usePage, router } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
import Layout from '@/layouts/DashboardLayout.vue';
import { ElNotification } from 'element-plus'

const page = usePage()
const loading=ref(false)

</script>

<template>
    <Layout>
        <el-card>
            <template #header>
                <div class="flex items-center justify-between">
                    <h3>Data Peserta Didik</h3>
                </div>
            </template>
            <el-table :data="page.props.siswas" max-height="75vh">
                <el-table-column label="#" type="index"></el-table-column>
                <el-table-column label="NISN" prop="nisn"></el-table-column>
                <el-table-column label="Nama" prop="nama"></el-table-column>
                <el-table-column label="JK" prop="jk"></el-table-column>
                <el-table-column label="Rombel" sortable>
                    <template #default="scope">
                        {{ scope.row.rombels[0]?.label }}
                    </template>
                </el-table-column>
            </el-table>
            <template #footer>
                <p>Jumlah: {{ page.props.siswas.length }}</p>
            </template>
        </el-card>
    </Layout>
</template>