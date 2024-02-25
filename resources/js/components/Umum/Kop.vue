<script setup>
import { ref, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { capitalize } from '@/helpers/textFormat'
const page = usePage()
const sekolah = page.props.sekolahs[0]
</script>

<template>
<div class="w-full flex justify-around items-center font-serif border-double border-b-4 border-black pb-1">
    <div class="logo-kab ">
        <img src="/img/malangkab.png" class="w-[100px]" alt="Logo Kab" />
    </div>
    <div class="kop-tengah ">
        <h3 class="text-center font-bold uppercase leading-4">Pemerintah Kabupaten {{  sekolah.kabupaten }}</h3>
        <h3 class="text-center font-bold uppercase leading-4">Dinas Pendidikan</h3>
        <h3 class="text-center font-bold uppercase leading-4">Korwil Dinas Pendidikan Kecamatan Wagir</h3>
        <h3 class="text-center font-bold uppercase tracking-widest text-xl">{{sekolah.nama}}</h3>
        <p class="text-center text-sm leading-4 ">Alamat: {{ capitalize(sekolah.alamat) }}, {{ capitalize(sekolah.desa) }}, Kec. {{ capitalize(sekolah.kecamatan) }}, {{ sekolah.kode_pos }}</p>
        <p class="text-center text-sm leading-4">Telp.: {{ sekolah.telp }}, {{ capitalize(sekolah.desa) }}, Email: {{ sekolah.email }}, Website: {{ sekolah.website }}</p>
    </div>
    <div class="logo-sekolah  ">
        <img :src="sekolah.logo" class="w-[100px]" alt="Logo Sekolah" />
    </div>
</div>
</template>