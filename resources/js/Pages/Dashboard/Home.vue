<script setup>
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';

import Layout from '@/layouts/DashboardLayout.vue'

</script>

<template>
    <Layout title="Dashboard">
            <el-row class="w-full">
                <el-col :span="24">
                    <el-card width="100%">
                        <h1>Dashboard</h1>
                    </el-card>
                </el-col>
            </el-row>
    </Layout>
</template>