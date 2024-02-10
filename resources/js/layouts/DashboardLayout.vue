<script setup>
import { ref, computed, defineAsyncComponent } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue'

const page = usePage()
const props = defineProps({title: String})
import SideNav from './SideNav.vue'
const sideNav = ref(null)

const showSide = ref(false)

const viewPort = computed(() => {
    let width = window.innerWidth
    return width >= 2048 ? 'xl' : (width > 1024 ? 'lg' : (width > 768 ? 'md' : width > 414 ? 'sm' : 'xs'))
    
})

const toggleSideNav = () => {
    showSide.value = !showSide.value
    sideNav.value.toggleSide()
}
</script>

<template>
    <Head :title="props.title" />
    <div class="common-layout">
        <el-container class="h-[100vh]">
            <el-aside  class="bg-slate-300 overflow-x-hidden transition-all h-max-screen relative" width="auto">
                <SideNav ref="sideNav" :user="page.props.user" :vp="viewPort" />
            </el-aside>
            <el-container>
                <el-header class="bg-slate-600 flex justify-between items-center text-white" height="60px">
                    <span class="flex gap-1">
                        <Icon :icon="`mdi:${showSide ? 'close' : 'menu'}`" class="text-white text-2xl cursor-pointer" @click="toggleSideNav" />
                        <span v-if="!showSide">Header</span>
                    </span>
                    <div class="header-items">
                        <el-popover trigger="hover">
                            <template #reference>
                                <el-avatar circle>
                                    <Icon icon="mdi:account-tie" />
                                </el-avatar>
                            </template>
                            <template #default>
                                <h3 class="font-bold text-slate-700">{{ page.props.user.name }}</h3>
                                <Link class="flex items-center gap-1 w-full hover:text-red-300" :href="appRoute('logout')" method="post" as="button">
                                    Keluar
                                    <Icon icon="mdi:exit-to-app" />
                                </Link>
                            </template>
                        </el-popover>
                    </div>
                </el-header>
                <el-main class="bg-slate-200">
                    <slot />
                </el-main>
            </el-container>
        </el-container>
    </div>
</template>