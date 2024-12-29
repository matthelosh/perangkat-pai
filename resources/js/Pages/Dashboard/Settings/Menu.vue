<script setup>
import { computed, ref } from "vue";
import { router, usePage, Head } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";

import Layout from "@/layouts/DashboardLayout.vue";
const page = usePage();

const loading = ref(false);
const showDialog = ref(false);
const menu = ref({});
const roles = ref(["admin", "gpai", "pengawas", "siswa"]);

const roots = computed(() => {
    return page.props.menus.filter((menu) => menu.parent_id == 0);
});

// Function
const hapus = async (id) => {
    await router.delete(route("menu.destroy", { id: id }), {
        onStart: () => {
            loading.value = true;
        },
        onSuccess: (page) => {
            router.reload({ only: ["menus"] });
        },
        onError: (err) => {
            Object.keys(err).forEach((k) => {
                ElNotification({ title: "Error", message: err[k] });
            });
        },
    });
};
const edit = (item) => {
    item.roles = item.roles.split(",");
    menu.value = item;
    showDialog.value = true;
};
const onSubmit = async () => {
    router.post(
        route("menu.store"),
        { menu: JSON.stringify(menu.value) },
        {
            onStart: () => {
                loading.value = true;
            },
            onSuccess: (page) => {
                loading.value = false;
                menu.value = {};
                router.reload({ only: ["menus"] });
            },
            onError: (err) => {
                Object.keys(err).forEach((k) => {
                    ElNotification({ title: "Error", message: err[k] });
                });
            },
        }
    );
};
</script>

<template>
    <div title="Pengaturan Menu">
        <Head title="Pengaturan Menu" />

        <el-row width="100%" class="py-2" justify="end">
            <!-- {{ page.props.menus }} -->
            <el-button-group :native-type="null">
                <el-button
                    :native-type="null"
                    type="primary"
                    @click="showDialog = true"
                >
                    <Icon icon="mdi:plus" />
                </el-button>
            </el-button-group>
        </el-row>
        <el-table :data="page.props.menus" stripe row-key="id">
            <el-table-column label="#index" width="60px">
                <template #default="scope">
                    {{ scope.row.id }}
                </template>
            </el-table-column>
            <el-table-column label="Label">
                <template #default="scope">
                    <el-tooltip
                        :content="`Edit Menu ${scope.row.label}`"
                        placement="top-start"
                    >
                        <el-button :native-type="null" @click="edit(scope.row)">
                            {{ scope.row.label }}
                        </el-button>
                    </el-tooltip>
                </template>
            </el-table-column>
            <el-table-column prop="url" label="URL/Route" />
            <el-table-column prop="icon" label="Icon" />
            <el-table-column prop="roles" label="Role" />
            <el-table-column prop="parent_id" label="Parent" />
            <el-table-column label="Opsi">
                <template #default="scope">
                    <el-popconfirm
                        :title="`Yakin menghapus menu ${scope.row.label}?`"
                        @confirm="hapus(scope.row.id)"
                    >
                        <template #reference>
                            <el-button :native-type="null" type="danger">
                                <Icon icon="mdi:delete" />
                            </el-button>
                        </template>
                    </el-popconfirm>
                </template>
            </el-table-column>
        </el-table>
        <el-dialog v-model="showDialog" title="Form Menu" @closed="menu = {}">
            <el-form v-model="menu" label-width="150px">
                <el-form-item label="Label">
                    <el-input
                        type="text"
                        placeholder="Label"
                        v-model="menu.label"
                    />
                </el-form-item>
                <el-form-item label="URL/Route">
                    <el-input
                        type="text"
                        placeholder="URL/Route"
                        v-model="menu.url"
                    />
                </el-form-item>
                <el-form-item label="Role">
                    <el-select
                        v-model="menu.roles"
                        multiple
                        placeholder="Role Pengguna"
                    >
                        <el-option
                            v-for="role in roles"
                            :key="role"
                            :value="role"
                            >{{ role }}</el-option
                        >
                    </el-select>
                </el-form-item>
                <el-form-item label="Icon">
                    <el-input
                        type="text"
                        placeholder="Icon"
                        v-model="menu.icon"
                    >
                        <template #prefix>
                            <Icon :icon="`mdi:${menu.icon}`" />
                        </template>
                    </el-input>
                </el-form-item>
                <el-form-item label="Menu Induk">
                    <el-select v-model="menu.parent_id" placeholder="Induk">
                        <el-option value="0">Induk</el-option>
                        <el-option
                            v-for="induk in roots"
                            :key="induk.id"
                            :value="induk.id"
                            >{{ induk.label }}</el-option
                        >
                    </el-select>
                </el-form-item>
                <el-form-item>
                    <el-button
                        :native-type="null"
                        type="primary"
                        :loading="loading"
                        @click="onSubmit"
                        >Simpan</el-button
                    >
                </el-form-item>
            </el-form>
        </el-dialog>
    </div>
</template>
