<script setup>
import { ref, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { capitalize } from '@/helpers/textFormat'
import dayjs from 'dayjs'
import localeData from 'dayjs/plugin/localeData'
import 'dayjs/locale/id'
dayjs.extend(localeData)
dayjs.locale("id")

const page = usePage()
const props = defineProps({tanggal: String})
const sekolah = page.props.sekolahs[0]
const tanggal = props.tanggal ?? '..........................'
</script>

<template>
<div class="w-full flex justify-around items-center font-serif my-4">
    <div class="ttd_ks relative">
        <p class="leading-4">&nbsp;</p>
        <p class="leading-4">Kepala Sekolah</p>
        <img :src="`/storage/ttd-kepsek/${page.props.sekolahs[0].nip_ks}.png`" class="absolute" />
        <p class="leading-4 mt-20  underline font-bold"><span class="uppercase">{{ sekolah.nama_ks}}</span> </p>
        <p class="leading-4 tracking-wide">NIP. {{ sekolah.nip_ks}}</p>
    </div>
    <div class="kop-tengah ">
        
    </div>
    <div class="ttd-gpai  ">
        <p class="leading-4">{{ capitalize(sekolah.desa) }}, <span contenteditable class="bg-yellow-200 print:bg-white">{{ dayjs(props.tanggal).format('DD MMMM YYYY') }}</span></p>
        <p class="leading-4">Guru PAI</p>
        <p class="leading-4 mt-20  underline font-bold"><span class="uppercase">{{ page.props.user.userable.nama}}</span>, {{ page.props.user.userable.gelar_belakang }} </p>
        <p class="leading-4 tracking-wide">NIP. {{ page.props.user.userable.nip }}</p>
    </div>
</div>
</template>