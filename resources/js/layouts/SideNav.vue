<script setup>
import { ref, computed } from 'vue';
import { router, Link } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue';

const props = defineProps({user: Object})
const isCollapse = ref(false)

const items = ref([
    {
        label: 'Dashboard',
        url: 'home',
        icon: 'home',
        roles: ['admin','gpai','pengawas','siswa'],
        children: null,
    }, 
    {
        label: 'Tentang Kami',
        url: 'about',
        icon: 'leaf',
        roles: ['admin','gpai','pengawas','siswa'],
        children: null,
    },
    {
        label: "Utama",
        url: '#',
        icon: 'database',
        roles: ['admin','gpai','pengawas','siswa'],
        children: [
            {
                label: "Sekolah",
                url: 'sekolah',
                icon: 'city',
                roles: ['admin','gpai'],
            }
        ]
    }

])

const defaultActive = computed(() => sessionStorage.getItem("activeItem") ?? "1")

const handleClose = (key, keyPath) => {
    console.log(key, keyPath)
}
const handleOpen = (key, keyPath) => {
    // defaultActive.value = "2"
}
const go = (menu, m) => {
    sessionStorage.setItem("activeItem", String(m))
    router.visit(route(menu.url), {preserveState: true})
}


</script>

<template>
        <div class="relative side-info w-full bg-slate-100 h-[250px] flex flex-wrap justify-center items-center">
            <figure class="w-[80%] rounded-full bg-slate-200 overflow-hidden">
                <el-image src="/img/guru-default.png" fit="cover" class="w-[100%]"></el-image>
            </figure>
            <h3 class="absolute h-10 flex items-center justify-center bg-opacity-95 bottom-0 bg-slate-400 w-full text-center text-slate-50 font-bold uppercase">{{ props.user.name }}</h3>
        </div>
        <div class="side-nav">
            <el-scrollbar height="70vh">
                <el-menu
                    :default-active="defaultActive"
                    class="el-menu-side-nav"
                    @open="handleOpen"
                    @close="handleClose"
                    :collapse="isCollapse">
                    <template v-for="(menu,m) in items" :key="m">
                        <el-menu-item :index="String(m+1)"  @click="go(menu,String(m+1))" v-if="menu.children === null">
                            <el-icon>
                                <Icon :icon="`mdi:${menu.icon}`" />
                            </el-icon>
                            <template #title>{{ menu.label }}</template>
                        </el-menu-item>
                        <el-sub-menu v-else :index="String(m+1)">
                            <template #title>{{ menu.label }}</template>
                            <el-menu-item v-for="(child, c) in menu.children" :index="(m+1)+'-'+(c+1)"  @click="go(child, (m+1)+'-'+(c+1))">
                                <el-icon>
                                    <Icon :icon="`mdi:${child.icon}`" />
                                </el-icon>
                                <template #title>{{ child.label }}</template>
                            </el-menu-item>
                        </el-sub-menu>
                    </template>
                </el-menu>
            </el-scrollbar>            
        </div>
        
</template>