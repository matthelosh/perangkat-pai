<script setup>
import { ref, computed } from "vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";
import Layout from "@/layouts/DashboardLayout.vue";

const page = usePage();
const search = ref(null);
const users = computed(() => {
    return search.value == null
        ? page.props.users
        : page.props.users.filter((user, u) =>
              user.name.toLowerCase().includes(search.value.toLowerCase())
          );
});
</script>

<template>
    <div title="Pengaturan Akun">
        <!-- {{ page.props.users }} -->
        <el-table :data="users">
            <el-table-column
                prop="name"
                vertical-align="top"
                label="Username"
            ></el-table-column>
            <el-table-column prop="email" label="Email"></el-table-column>
            <el-table-column label="Roles">
                <template #default="props">
                    <ul>
                        <li v-for="role in props.row.roles" :key="role">
                            {{ role.name }}
                        </li>
                    </ul>
                </template>
            </el-table-column>
            <el-table-column label="Persmission">
                <template #default="props">
                    <ul>
                        <li
                            v-for="permission in props.row.roles[0].permissions"
                            :key="permission"
                        >
                            {{ permission.name }}
                        </li>
                    </ul>
                </template>
            </el-table-column>
            <el-table-column label="Opsi">
                <template #default="scope">
                    <el-button :native-type="null" type="warning">
                        <Icon icon="mdi:edit" />
                    </el-button>
                </template>
            </el-table-column>
        </el-table>

        <el-row :gutter="20">
            <el-col :span="12" class="roles">
                <ul>
                    <li v-for="role in page.props.roles" :key="role.id">
                        {{ role.name }}
                    </li>
                </ul>
            </el-col>
            <el-col :span="12" class="permissions">
                <ul>
                    <li
                        v-for="permission in page.props.permissions"
                        :key="permission.id"
                    >
                        {{ permission.name }}
                    </li>
                </ul>
            </el-col>
        </el-row>
    </div>
</template>
