<script setup>
import { ref, computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import { capitalize } from "@/helpers/textFormat";
const page = usePage();
const sekolah = page.props.sekolahs[0];

const logo = (img) => {
    return img ?? "/img/tutwuri.png";
};
</script>

<template>
    <div
        class="w-full flex justify-around items-center font-serif pb-1"
        style="border-bottom: 3px double black"
    >
        <div class="logo-kab">
            <img
                src="/img/malangkab.png"
                class="h-[80px]"
                alt="Logo Kab"
                style="height: 80px"
            />
        </div>
        <div class="kop-tengah">
            <h4
                class="text-center font-bold uppercase"
                style="line-height: 1rem; margin: 0 auto"
            >
                Pemerintah Kabupaten {{ sekolah.kabupaten }}
            </h4>
            <h4
                class="text-center font-bold uppercase"
                style="line-height: 1rem; margin: 0 auto"
            >
                Dinas Pendidikan
            </h4>
            <h5
                class="text-center font-bold uppercase"
                style="line-height: 1rem; margin: 0 auto"
            >
                Korwil Dinas Pendidikan Kecamatan Wagir
            </h5>
            <h3
                class="text-center font-bold uppercase tracking-widest text-xl"
                style="line-height: 1rem; margin: 0 auto"
            >
                {{ sekolah.nama }}
            </h3>
            <p
                class="text-center text-sm leading-4"
                style="line-height: 1rem; margin: 0 auto"
            >
                Alamat: {{ capitalize(sekolah.alamat) }},
                {{ capitalize(sekolah.desa) }}, Kec.
                {{ capitalize(sekolah.kecamatan) }}, {{ sekolah.kode_pos }}
            </p>
            <p
                class="text-center text-sm leading-4"
                style="line-height: 1rem; margin: 0 auto"
            >
                Telp.: {{ sekolah.telp ?? "-" }}, Email: {{ sekolah.email }},
                Website:
                {{ sekolah.website }}
            </p>
        </div>
        <div class="logo-sekolah">
            <img
                :src="logo(sekolah.logo)"
                class="h-[100px]"
                alt=""
                onerror="this.error=null;this.src='/img/tutwuri.png'"
                style="height: 80px"
            />
        </div>
    </div>
</template>
