<script setup>
import { ref, computed, defineAsyncComponent } from 'vue';
import { usePage, Head, Link } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue'
import dayjs from 'dayjs'
import localeData from 'dayjs/plugin/localeData'
import 'dayjs/locale/id'
dayjs.extend(localeData)
dayjs.locale("id")

const page = usePage()
import { Doc, Text, Paragraph, Color,  Bold, Underline, Italic, BulletList, OrderedList, Strike, Highlight } from 'element-tiptap-vue3-fixed'
const extensions = [
    Doc, Text, Paragraph,
    Color.configure({bubble: true}),
    Highlight.configure({bubble: true}),
    Strike.configure({bubble: true}),
    Bold.configure({bubble: true}),
    Underline.configure({bubble: true}),
    Italic.configure({bubble: true}),
    BulletList, OrderedList
]

import DashLayout from '@/layouts/DashboardLayout.vue'
const FormMa = defineAsyncComponent(() => import('@/components/Dashboard/Perangkat/Rencana/FormMa.vue'))

const mode = ref('list')
const selectedAtp = ref(null)

const makeMa = (atp) => {
    selectedAtp.value = atp
    mode.value = 'form'
}

const closeForm = () => {
    mode.value = 'list'
    selectedAtp.value = null
}
</script>
<template>
<DashLayout title="Modul Ajar">
    <el-card v-if="mode=='list'">
        <template #header>
            <div class="toolbar flex items-center justify-between">
                <h3 class="flex items-center gap-2">
                    <Link :href="appRoute('rencana')">
                        <Icon icon="mdi:arrow-left" class="text-2xl text-teal-700 hover:cursor-pointer" />
                    </Link>
                    Modul Ajar Kelas {{ page.props.rombel.label }}
                </h3>
                <div class="items flex items-center gap-2">

                </div>
            </div>
        </template>
        <div class="card-body" >
            <h3 class="mb-2 font-semibold text-blue-700 tracking-wide text-lg">Pilih Alur Tujuan Pembelajaran</h3>

            <el-collapse accordion>
                <el-collapse-item v-for="(atp,a) in page.props.atps" :key="atp.kode" :name="atp.kode">
                    <template #title>
                        <h3>{{ atp.materi + (atp.konten ? ' | '+atp.konten : '') }}</h3>
                    </template>
                    <div class="content">

                        <el-button type="primary" @click="makeMa(atp)">Buat</el-button>
                    </div>
                </el-collapse-item>
            </el-collapse>
        </div>
    </el-card>

    <FormMa :atp="selectedAtp" @close="closeForm" v-if="mode == 'form'" />
</DashLayout>

</template>