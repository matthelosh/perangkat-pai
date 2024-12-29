<script setup>
import { computed, ref } from "vue";
import { Head, usePage, router } from "@inertiajs/vue3";
import { Search } from "@element-plus/icons-vue";
import { Icon } from "@iconify/vue";
import Layout from "@/layouts/DashboardLayout.vue";
import { ElNotification } from "element-plus";
import { paginate } from "@/helpers/arrayHelper";

const page = usePage();
const loading = ref(false);
const search = ref(null);
const datas = computed(() => {
    return !search.value
        ? page.props.siswas
        : page.props.siswas.filter((siswa) =>
              siswa.nama.toLowerCase().includes(search.value.toLowerCase())
          );
});

const currentPage = ref(0);
const onPageChanged = (page) => {
    currentPage.value = page - 1;
};
const paged = computed(() => {
    return paginate(datas.value, 25);
});
</script>

<template>
    <div>
        <el-card>
            <template #header>
                <div class="flex items-center justify-between">
                    <h3>Data Peserta Didik</h3>
                    <el-input
                        type="text"
                        placeholder="Cari Nama"
                        style="max-width: 300px"
                        :suffix-icon="Search"
                        v-model="search"
                        clearable
                    ></el-input>
                </div>
            </template>
            <el-table :data="paged[currentPage]" max-height="75vh">
                <el-table-column label="#" type="index"></el-table-column>
                <el-table-column label="NISN" prop="nisn"></el-table-column>
                <el-table-column
                    label="Nama"
                    prop="nama"
                    sortable
                ></el-table-column>
                <el-table-column label="JK" prop="jk"></el-table-column>
                <el-table-column label="Rombel" sortable>
                    <template #default="scope">
                        {{ scope.row.rombels[0]?.label }}
                    </template>
                </el-table-column>
                <el-table-column label="Lembaga" sortable>
                    <template #default="{ row }">
                        {{ row.rombels[0]?.sekolah?.nama }}
                    </template>
                </el-table-column>
            </el-table>
            <template #footer>
                <div class="flex justify-between items-center">
                    <div></div>
                    <el-pagination
                        background
                        layout="total, prev, pager, next, jumper"
                        :total="page.props.siswas.length"
                        :page-count="paged.length"
                        @current-change="onPageChanged"
                    ></el-pagination>
                </div>
            </template>
        </el-card>
    </div>
</template>
