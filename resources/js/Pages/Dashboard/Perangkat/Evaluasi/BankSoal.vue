<script setup>
import { paginate } from "@/helpers/arrayHelper";
import DashboardLayout from "@/layouts/DashboardLayout.vue";
import { Icon } from "@iconify/vue";
import { usePage, router } from "@inertiajs/vue3";
import { computed, defineAsyncComponent, ref } from "vue";
import { ElNotification } from "element-plus";

const FormSoal = defineAsyncComponent(() =>
    import("@/components/Dashboard/Perangkat/Evaluasi/FormSoal.vue")
);
const FormImporSoal = defineAsyncComponent(() =>
    import("@/components/Dashboard/Perangkat/Evaluasi/FormImporSoal.vue")
);

const Pagination = defineAsyncComponent(() =>
    import("@/components/Umum/Pagination.vue")
);

const loading = ref(false);
const page = usePage();
const paged = computed(() => {
    return paginate(page.props.soals, 18);
});
const currentPage = ref(0);
const changePage = (num) => {
    currentPage.value = num - 1;
};

const selectedSoal = ref(null);
const showFormSoal = ref(false);
const openFormSoal = (item) => {
    selectedSoal.value = item;
    showFormSoal.value = true;
};
const closeFormSoal = () => {
    selectedSoal.value = null;
    showFormSoal.value = false;
};

const hapusSoal = async (id) => {
    router.delete(route("soal.destroy", { id: id }), {
        onStart: () => {
            loading.value = true;
        },
        onSuccess: () => {
            ElNotification({
                title: "Info",
                message: page.props.flash.message,
                type: "success",
            });
        },
        onFinish: () => (loading.value = false),
    });
};

// Impor Soal
const showFormImpor = ref(false);
const closeFormImpor = () => {
    showFormImpor.value = false;
};
const openFormImpor = () => (showFormImpor.value = true);

defineOptions({
    layout: DashboardLayout,
});
</script>

<template>
    <div>
        <el-card>
            <template #header>
                <div class="flex items-center justify-between">
                    <h3>Data Soal</h3>
                    <div class="header-items flex items-center gap-2">
                        <el-button-group>
                            <el-button
                                :native-type="null"
                                type="primary"
                                class="hidden-sm-and-down"
                                @click="openFormImpor"
                            >
                                <Icon icon="mdi:import" />
                                Impor Soal
                            </el-button>
                            <el-button
                                :native-type="null"
                                type="primary"
                                @click="openFormSoal(null)"
                            >
                                <Icon icon="mdi:plus" />
                                Buat Soal
                            </el-button>
                        </el-button-group>
                        <el-button :native-type="null" type="success">
                            <Icon icon="mdi:file-excel" />
                            Unduh Soal
                        </el-button>
                    </div>
                </div>
            </template>

            <el-table :data="paged[currentPage]" max-height="75vh">
                <el-table-column
                    label="#"
                    prop="id"
                    width="60px"
                    sortable
                ></el-table-column>
                <el-table-column label="Pertanyaan">
                    <template #default="{ row }">
                        <div v-html="row.pertanyaan" />
                    </template>
                </el-table-column>
                <el-table-column
                    width="100px"
                    label="Tipe"
                    prop="tipe"
                ></el-table-column>
                <el-table-column
                    label="Kunci"
                    prop="kunci"
                    width="280px"
                ></el-table-column>
                <el-table-column
                    label="Kelas"
                    prop="tingkat"
                    sortable
                    width="80px"
                ></el-table-column>
                <el-table-column
                    label="Sem"
                    prop="semester"
                    sortable
                    width="80px"
                ></el-table-column>
                <el-table-column label="Elemen" width="200px">
                    <template #default="{ row }">
                        {{ row.tp.elemen.label }}
                    </template>
                </el-table-column>
                <el-table-column label="Opsi" fixed="right" width="80px">
                    <template #default="{ row }">
                        <el-button
                            :native-type="null"
                            type="warning"
                            link
                            size="large"
                            @click="openFormSoal(row)"
                            v-loading.fullscreen.lock="loading"
                        >
                            <Icon
                                icon="mdi:file-document-edit-outline"
                                class="text-lg"
                            />
                        </el-button>
                        <el-button
                            :native-type="null"
                            type="danger"
                            link
                            size="large"
                            @click="hapusSoal(row.id)"
                        >
                            <Icon icon="mdi:delete" class="text-lg" />
                        </el-button>
                    </template>
                </el-table-column>
            </el-table>
            <template #footer>
                <Pagination
                    :total="page.props.soals.length"
                    :perPage="18"
                    @change="changePage"
                />
            </template>
        </el-card>

        <FormSoal
            v-if="showFormSoal"
            :open="showFormSoal"
            :selected-soal="selectedSoal"
            @close="closeFormSoal"
        />
        <FormImporSoal
            v-if="showFormImpor"
            :open="showFormImpor"
            @close="closeFormImpor"
        />
    </div>
</template>
