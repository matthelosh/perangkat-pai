<script setup>
import { ref, computed } from "vue";
import { Link, usePage } from "@inertiajs/vue3";

import Layout from "@/layouts/DashboardLayout.vue";
import Admin from "@/components/Dashboard/Home/Admin.vue";
import Gpai from "@/components/Dashboard/Home/Gpai.vue";

const page = usePage();
const role = computed(() => page.props.roles[0]);
const is = computed(() => {
    return role.value == "admin" ? Admin : Gpai;
});
</script>

<template>
    <Layout title="Dashboard">
        <component :is="is"> </component>
    </Layout>
</template>
