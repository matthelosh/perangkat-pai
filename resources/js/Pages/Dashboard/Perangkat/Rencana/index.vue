<script setup>
import { ref, computed, defineAsyncComponent } from "vue";
import { usePage, router, Link } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";
import Layout from "@/layouts/DashboardLayout.vue";
const page = usePage();

const Jadwal = defineAsyncComponent(() =>
    import("@/components/Dashboard/Perangkat/Rencana/Jadwal.vue")
);

const mode = ref("list");

const rombels = computed(() => {
    return Object.groupBy(page.props.rombels, (rombel) => rombel.fase);
});

const buka = (doc, fase, tingkat = null) => {
    router.get(
        route(doc, { _query: { fase: fase, tingkat: tingkat, mine: "false" } })
    );
};

const close = () => {
    mode.value = "list";
};
</script>

<template>
    <div title="Rencana Pembelajaran">
        <el-row class="w-full my-6">
            <el-col :span="24">
                <Jadwal />
            </el-col>
        </el-row>
        <el-row :gutter="20" v-if="mode == 'list'">
            <el-col :span="8" v-for="fase in ['A', 'B', 'C']" :key="fase">
                <el-card>
                    <template #header>
                        <h3>Fase {{ fase }}</h3>
                    </template>
                    <div class="card-body">
                        <el-menu>
                            <el-menu-item
                                index="1"
                                @click="buka('tp.index', fase)"
                            >
                                <Icon
                                    icon="mdi:chart-finance"
                                    class="mr-2 text-lg"
                                />
                                <span>Tujuan Pembelajaran</span>
                            </el-menu-item>
                            <el-menu-item
                                index="2"
                                @click="buka('atp.index', fase)"
                            >
                                <Icon
                                    icon="mdi:timeline"
                                    class="mr-2 text-lg"
                                />
                                <span>Alur Tujuan Pembelajaran</span>
                            </el-menu-item>
                            <el-sub-menu
                                index="3"
                                v-if="
                                    page.props.rombels &&
                                    page.props.rombels.length > 0
                                "
                            >
                                <template #title>
                                    <Icon icon="mdi:poll" />
                                    <span>&nbsp;Analisis Pekan Efektif</span>
                                </template>
                                <el-menu-item-group>
                                    <el-menu-item
                                        v-for="(rombel, r) in rombels[fase]"
                                        :key="rombel.kode"
                                        :index="`3-${r + 1}`"
                                        @click="
                                            buka(
                                                'ape.index',
                                                fase,
                                                rombel.tingkat
                                            )
                                        "
                                        >{{ rombel.label }}</el-menu-item
                                    >
                                </el-menu-item-group>
                            </el-sub-menu>
                            <el-sub-menu
                                index="4"
                                v-if="
                                    page.props.rombels &&
                                    page.props.rombels.length > 0
                                "
                            >
                                <template #title>
                                    <Icon icon="mdi:calendar-check" />
                                    <span>&nbsp;Prota</span>
                                </template>
                                <el-menu-item-group>
                                    <el-menu-item
                                        v-for="(rombel, r) in rombels[fase]"
                                        :key="rombel.kode"
                                        :index="`4-${r + 1}`"
                                        @click="
                                            buka(
                                                'prota.index',
                                                fase,
                                                rombel.tingkat
                                            )
                                        "
                                        >{{ rombel.label }}</el-menu-item
                                    >
                                </el-menu-item-group>
                            </el-sub-menu>
                            <el-sub-menu
                                index="5"
                                v-if="
                                    page.props.rombels &&
                                    page.props.rombels.length > 0
                                "
                            >
                                <template #title>
                                    <Icon icon="mdi:calendar-check" />
                                    <span>&nbsp;Prosem</span>
                                </template>
                                <el-menu-item-group>
                                    <el-menu-item
                                        v-for="(rombel, r) in rombels[fase]"
                                        :key="rombel.kode"
                                        :index="`5-${r + 1}`"
                                        @click="
                                            buka(
                                                'prosem.index',
                                                fase,
                                                rombel.tingkat
                                            )
                                        "
                                        >{{ rombel.label }}</el-menu-item
                                    >
                                </el-menu-item-group>
                            </el-sub-menu>
                            <el-sub-menu
                                index="6"
                                v-if="
                                    page.props.rombels &&
                                    page.props.rombels.length > 0
                                "
                            >
                                <template #title>
                                    <Icon icon="mdi:book" />
                                    <span>&nbsp;Modul Ajar</span>
                                </template>
                                <el-menu-item-group>
                                    <el-menu-item
                                        v-for="(rombel, r) in rombels[fase]"
                                        :key="rombel.kode"
                                        :index="`5-${r + 1}`"
                                        @click="
                                            buka(
                                                'modulajar.index',
                                                fase,
                                                rombel.tingkat
                                            )
                                        "
                                        >{{ rombel.label }}</el-menu-item
                                    >
                                </el-menu-item-group>
                            </el-sub-menu>
                        </el-menu>
                    </div>
                </el-card>
            </el-col>
        </el-row>
    </div>
</template>
