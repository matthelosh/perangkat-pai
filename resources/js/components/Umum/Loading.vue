<script setup>
import { Icon } from "@iconify/vue";
</script>

<template>
    <div
        class="fixed top-0 right-0 bottom-0 left-0 bg-slate-800 opacity-70 flex justify-center items-center z-[9999]"
    >
        <div class="content text-center">
            <Icon icon="mdi:loading" class="text-white text-4xl animate-spin" />
        </div>
    </div>
</template>
