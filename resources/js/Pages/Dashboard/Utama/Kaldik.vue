<script setup>
import { ref, computed } from "vue";
import { usePage, router, Head } from "@inertiajs/vue3";
import { Calendar } from 'v-calendar'
import 'v-calendar/style.css'

import Layout from "@/layouts/DashboardLayout.vue";

const page = usePage();
</script>

<template>
    <Layout title="Kalender Pendidikan">
        <Head title="Kalender Pendidikan" />
        <Calendar />
    </Layout>
</template>
