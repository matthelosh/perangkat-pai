<script setup>
import { ref, computed, defineAsyncComponent } from "vue";
import { usePage, Head, Link, router } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";
import dayjs from "dayjs";
import localeData from "dayjs/plugin/localeData";
import "dayjs/locale/id";
dayjs.extend(localeData);
dayjs.locale("id");

const page = usePage();
import {
    Doc,
    Text,
    Paragraph,
    Color,
    Bold,
    Underline,
    Italic,
    BulletList,
    OrderedList,
    Strike,
    Highlight,
} from "element-tiptap-vue3-fixed";
const extensions = [
    Doc,
    Text,
    Paragraph,
    Color.configure({ bubble: true }),
    Highlight.configure({ bubble: true }),
    Strike.configure({ bubble: true }),
    Bold.configure({ bubble: true }),
    Underline.configure({ bubble: true }),
    Italic.configure({ bubble: true }),
    BulletList,
    OrderedList,
];

import DashLayout from "@/layouts/DashboardLayout.vue";
import { ElNotification } from "element-plus";
const FormMa = defineAsyncComponent(() =>
    import("@/components/Dashboard/Perangkat/Rencana/FormMa.vue")
);

const loading = ref(false);
const mode = ref("list");
const selectedAtp = ref(null);
const selectedMa = ref(null);

const editMa = (atp, ma) => {
    selectedAtp.value = atp;
    selectedMa.value = ma;
    mode.value = "form";
};
const makeMa = (atp) => {
    selectedAtp.value = atp;
    mode.value = "form";
};

const closeForm = () => {
    mode.value = "list";
    selectedAtp.value = null;
};

const hapusMa = async (id) => {
    router.delete(route("modulajar.destroy", { id: id }), {
        onStart: () => (loading.value = true),
        onSuccess: () => {
            ElNotification({
                title: "Info",
                message: page.props.flash.message,
                type: "success",
            });
            router.reload({ only: ["atps"] });
        },
        onFinish: () => (loading.value = false),
    });
};
</script>
<template>
    <div title="Modul Ajar">
        <el-card v-if="mode == 'list'">
            <template #header>
                <div class="toolbar flex items-center justify-between">
                    <h3 class="flex items-center gap-2">
                        <Link :href="appRoute('rencana')">
                            <Icon
                                icon="mdi:arrow-left"
                                class="text-2xl text-teal-700 hover:cursor-pointer"
                            />
                        </Link>
                        Modul Ajar Kelas {{ page.props.rombel.label }}
                    </h3>
                    <div class="items flex items-center gap-2"></div>
                </div>
            </template>
            <div class="card-body">
                <h3
                    class="mb-2 font-semibold text-blue-700 tracking-wide text-lg"
                >
                    Pilih Alur Tujuan Pembelajaran
                </h3>

                <el-collapse accordion>
                    <el-collapse-item
                        v-for="(atp, a) in page.props.atps"
                        :key="atp.kode"
                        :name="atp.kode"
                    >
                        <template #title>
                            <h3>
                                {{
                                    atp.materi +
                                    (atp.konten ? " | " + atp.konten : "")
                                }}
                            </h3>
                        </template>
                        <div class="content">
                            <el-button
                                :native-type="null"
                                type="primary"
                                @click="makeMa(atp)"
                            >
                                <Icon icon="mdi:plus" />
                                Buat
                            </el-button>
                            <el-table :data="atp.mas">
                                <el-table-column
                                    label="#"
                                    type="index"
                                ></el-table-column>
                                <el-table-column
                                    label="TP"
                                    prop="tps"
                                ></el-table-column>
                                <el-table-column label="Opsi">
                                    <template #default="{ row }">
                                        <el-button
                                            :native-type="null"
                                            type="primary"
                                            @click="editMa(atp, row)"
                                        >
                                            <Icon icon="mdi:magnify" />
                                            Lihat
                                        </el-button>
                                        <el-button
                                            :native-type="null"
                                            type="danger"
                                            @click="hapusMa(row.id)"
                                        >
                                            <Icon icon="mdi:delete" />
                                            Hapus
                                        </el-button>
                                    </template>
                                </el-table-column>
                            </el-table>
                        </div>
                    </el-collapse-item>
                </el-collapse>
            </div>
        </el-card>

        <FormMa
            :atp="selectedAtp"
            :selectedMa="selectedMa"
            @close="closeForm"
            v-if="mode == 'form'"
        />
    </div>
</template>
