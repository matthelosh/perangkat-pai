<script setup>
import { ref, computed, defineAsyncComponent } from "vue";
import { Head, Link, usePage } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";

const page = usePage();
const props = defineProps({ title: String });
import SideNav from "./SideNav.vue";
const sideNav = ref(null);

const showSide = ref(localStorage.getItem("menuCollapsed") !== "true");

const viewPort = computed(() => {
    let width = window.innerWidth;
    return width >= 2048
        ? "xl"
        : width > 1024
        ? "lg"
        : width > 768
        ? "md"
        : width > 414
        ? "sm"
        : "xs";
});

const toggleSideNav = () => {
    showSide.value = !showSide.value;
    localStorage.setItem("menuCollapsed", (!showSide.value).toString());
    sideNav.value.toggleSide();
};

const processing = computed(() => page.props.processing);
</script>

<template>
    <Head :title="props.title" />
    <div class="common-layout">
        <el-container class="h-[100vh]">
            <el-aside
                class="bg-slate-300 print:hidden overflow-x-hidden transition-all h-max-screen relative"
                width="auto"
            >
                <SideNav ref="sideNav" :user="page.props.user" :vp="viewPort" />
            </el-aside>
            <el-container>
                <el-header
                    class="bg-slate-600 print:hidden flex justify-between items-center text-white"
                    height="60px"
                >
                    <span class="flex gap-1">
                        <Icon
                            icon="mdi:menu"
                            class="text-white text-2xl cursor-pointer"
                            @click="toggleSideNav"
                        />
                        <span v-if="!showSide">{{ props.title }}</span>
                    </span>
                    <div class="header-items">
                        <el-popover trigger="hover">
                            <template #reference>
                                <el-avatar circle>
                                    <Icon icon="mdi:account-tie" />
                                </el-avatar>
                            </template>
                            <template #default>
                                <h3 class="font-bold text-slate-700">
                                    {{ page.props.user.name }}
                                </h3>
                                <Link
                                    class="flex items-center gap-1 w-full hover:text-red-300"
                                    :href="appRoute('logout')"
                                    method="post"
                                    as="button"
                                >
                                    Keluar
                                    <Icon icon="mdi:exit-to-app" />
                                </Link>
                            </template>
                        </el-popover>
                    </div>
                </el-header>
                <el-main
                    class="bg-slate-200"
                    :style="`padding: ${
                        ['xs', 'sm'].includes(viewPort) ? '0!important' : '20px'
                    }`"
                >
                    <div
                        v-show="processing"
                        class="loading-overlay fixed top-0 right-0 bottom-0 left-0 bg-slate-200 bg-opacity-70 z-[999999999]"
                    >
                        Processing
                    </div>
                    <slot />
                </el-main>
            </el-container>
        </el-container>
    </div>
</template>
