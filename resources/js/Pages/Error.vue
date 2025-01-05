<script setup>
import { Icon } from "@iconify/vue";
import { Link } from "@inertiajs/vue3";
const props = defineProps({ status: Number, message: String });
</script>

<template>
    <div class="w-screen h-screen flex items-center justify-center bg-red-100">
        <div class="content p-4 w-[400px]">
            <h1 class="text-center text-8xl text-red-700">{{ status }}</h1>
            <h3
                class="text-center text-red-700 text-xl font-bold tracking-wider"
            >
                {{ message }}
            </h3>
            <Link
                :href="'/dashboard'"
                class="text-center mx-auto flex items-center justify-center text-teal-400 mt-4 tracking-widest gap-2"
            >
                <Icon icon="mdi:home" />
                Kembali
            </Link>
        </div>
    </div>
</template>
