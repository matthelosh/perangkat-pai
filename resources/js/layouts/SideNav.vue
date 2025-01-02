<script setup>
import { ref, computed, onBeforeMount } from "vue";
import { router, Link, usePage } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";

const page = usePage();
const props = defineProps({ user: Object, vp: String });
const isCollapse = ref(false);

const items = ref([]);

const defaultActive = ref("");

const handleClose = (key, keyPath) => {
    // console.log(key, keyPath);
};
const handleOpen = (key, keyPath) => {
    // console.log(key, keyPath);
    // defaultActive.value = key;
};

const showOverlay = ref(false);
const go = (menu, m) => {
    // sessionStorage.setItem("activeItem", String(m));
    defaultActive.value = String(m);
    router.get(
        route(menu.url),
        {},
        {
            // replace: true,
            preserveScroll: true,
            preserveState: false,
            onBefore: () => (showOverlay.value = true),
            onFinish: () => (showOverlay.value = false),
        }
    );
};

const toggleSide = () => {
    isCollapse.value = !isCollapse.value;
};

defineExpose({ toggleSide });

const currentRoute = computed(() => route().current());
onBeforeMount(() => {
    // defaultActive.value = "1";
    items.value = page.props.navs;

    // if (
    //     localStorage.getItem("menuCollapse") === null &&
    //     ["xs", "sm"].includes(props.vp)
    // ) {
    //     isCollapse.value = true;
    //     localStorage.setItem("menuCollapse", "true");
    // }
    isCollapse.value = ["xs", "sm", "md", "lg"].includes(props.vp);
    const findActiveMenu = (items) => {
        for (let i = 0; i < items.length; i++) {
            if (items[i].url === currentRoute.value) {
                defaultActive.value = String(i + 1);
                return;
            }

            if (items[i].children?.length) {
                items[i].children.forEach((child, j) => {
                    if (child.url === currentRoute.value) {
                        defaultActive.value = `${i + 1}-${j + 1}`;
                    }
                });
            }
        }
    };

    findActiveMenu(items.value);
    // if (["xs", "sm"].includes(props.vp)) {
    //     isCollapse.value = true;
    // }
});
</script>

<template>
    <div
        :class="isCollapse ? 'w-0' : 'w-[250px]'"
        class="fixed md:relative top-0 bottom-0 left-0 z-40 h-[100vh] bg-slate-100"
    >
        <div
            class="relative side-info w-full bg-slate-100 flex flex-wrap justify-center items-center h-[250px]"
        >
            <figure class="w-[80%] rounded-full bg-slate-200 overflow-hidden">
                <el-image
                    src="/img/guru-default.png"
                    fit="cover"
                    class="w-[100%]"
                ></el-image>
            </figure>
            <h3
                class="absolute h-10 flex items-center justify-center bg-opacity-95 bottom-0 bg-slate-400 w-full text-center text-slate-50 font-bold uppercase"
                v-if="!isCollapse"
            >
                {{ props.user.name }}
            </h3>
        </div>
        <!-- {{ props.vp }} -->
        <div class="side-nav">
            <el-scrollbar height="70vh">
                <el-menu
                    :default-active="defaultActive"
                    class="el-menu-side-nav"
                    @open="handleOpen"
                    @close="handleClose"
                    active-text-color="#ff0000"
                >
                    <template v-for="(menu, m) in items" :key="m">
                        <el-menu-item
                            :index="String(m + 1)"
                            @click="go(menu, String(m + 1))"
                            v-if="menu.children.length < 1"
                        >
                            <el-icon>
                                <Icon :icon="`mdi:${menu.icon}`" />
                            </el-icon>
                            <template #title>{{ menu.label }}</template>
                        </el-menu-item>
                        <el-sub-menu v-else :index="String(m + 1)">
                            <template #title>
                                <el-icon
                                    ><Icon :icon="`mdi:${menu.icon}`"
                                /></el-icon>
                                <span>{{ menu.label }}</span>
                            </template>
                            <el-menu-item
                                v-for="(child, c) in menu.children"
                                :index="m + 1 + '-' + (c + 1)"
                                @click="go(child, m + 1 + '-' + (c + 1))"
                            >
                                <el-icon>
                                    <Icon :icon="`mdi:${child.icon}`" />
                                </el-icon>
                                <p>{{ child.label }}</p>
                            </el-menu-item>
                        </el-sub-menu>
                    </template>
                    <!-- <Link href="/rencana">Rencana</Link>
                    <Link href="/dashboard">Dashboard</Link> -->
                </el-menu>
            </el-scrollbar>
        </div>

        <div
            class="toggle-btn absolute right-0 bg-red-400 top-0 p-4 h-[60px] flex items-center justify-center md:hidden"
            @click="toggleSide"
        >
            <Icon icon="mdi:close" class="text-white" />
        </div>
    </div>
</template>

<style>
.el-menu-active-color {
    color: red;
}
</style>
