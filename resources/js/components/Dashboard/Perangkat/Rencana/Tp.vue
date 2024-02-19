<script setup>
import { ref, computed, defineAsyncComponent, onBeforeMount } from 'vue';
import {  Head, usePage, router } from '@inertiajs/vue3';
const props = defineProps({fase: String})

onBeforeMount(() => {
    router.get(route('tp.index', {_query: {fase: props.fase}}))
})
</script>

<template>
<div>
    <Head>
        <title>Tujuan Pembelajaran Fase {{ props.fase }}</title>
    </Head>
    
</div>

</template>