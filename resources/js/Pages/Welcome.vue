<script setup>
// import Example from '@/components/ExampleComponent.vue'
import { Link } from '@inertiajs/vue3'
</script>

<template>
    <div class="wrapper w-screen h-screen overflow-x-hidden bg-green-900 text-green-50 flex items-center justify-center">
        <div class="text-center">
            <img src="/img/logo.webp" alt="Logo" height="100px" style="height: 78px; margin: 10px auto;" >
            <h1>PERANGKAT PEMBELAJARAN PAI</h1>
            <h3>KKG PAI KECAMATAN WAGIR</h3>
            <Link class="text-green-400" href="/masuk">Login</Link>
        </div>
    </div>
</template>
