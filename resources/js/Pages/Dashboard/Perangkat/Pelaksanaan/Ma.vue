<script setup>
import { ref, computed } from 'vue';
import { usePage, Head, Link } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue'
import { Doc, Text, Paragraph, Color,  Bold, Underline, Italic, BulletList, OrderedList, Strike, Highlight } from 'element-tiptap-vue3-fixed'
import dayjs from 'dayjs'
import localeData from 'dayjs/plugin/localeData'
import 'dayjs/locale/id'
dayjs.extend(localeData)
dayjs.locale("id")

const page = usePage()
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

const mode = ref('list')
const ma = ref({})
</script>
<template>
<DashLayout title="Modul Ajar">
    <el-card>
        <template #header>
            <div class="toolbar flex items-center justify-between">
                <h3 class="flex items-center gap-2">
                    <Link :href="appRoute('pelaksanaan')">
                        <Icon icon="mdi:arrow-left" class="text-2xl text-teal-700 hover:cursor-pointer" />
                    </Link>
                    Form Modul Ajar
                </h3>
                <div class="items flex items-center gap-2">

                </div>
            </div>
        </template>
        <div class="card-body">
            <div class="informasi">
                <h3>A. Informasi Umum</h3>
                
            </div>
            <el-tiptap v-model:content="ma.p5" :extensions="extensions" />
        </div>
    </el-card>
</DashLayout>

</template>