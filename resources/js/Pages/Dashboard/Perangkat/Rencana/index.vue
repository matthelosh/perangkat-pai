<script setup>
import { ref, computed, defineAsyncComponent } from 'vue';
import { usePage, router, Link } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
import Layout from '@/layouts/DashboardLayout.vue';
const page = usePage()
const mode = ref('list')



const buka = (doc, fase) => {
    router.get(route(doc, {_query: {fase: fase}}))
}

const close = () => {
    mode.value = 'list'
}
</script>

<template>
<Layout title="Rencana Pembelajaran">
    <el-row :gutter="20" v-if="mode == 'list'">
        <el-col :span="8" v-for="fase in ['A','B','C']" :key="fase">
            <el-card>
                <template #header>
                    <h3>Fase {{ fase }}</h3>
                </template>
                <div class="card-body">
                    <el-menu>
                        <el-menu-item index="1" @click="buka('tp.index', fase)">
                            <Icon icon="mdi:chart-finance" class="mr-2 text-lg" />
                            <span>Tujuan Pembelajaran</span>
                        </el-menu-item>
                    </el-menu>
                </div>
            </el-card>
        </el-col>
    </el-row>    
</Layout>
</template>