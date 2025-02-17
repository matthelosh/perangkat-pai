<script setup>
import { ref, computed } from "vue";
import { usePage, router, Head } from "@inertiajs/vue3";
import { Calendar } from "v-calendar";
import "v-calendar/style.css";
import { ElNotification } from "element-plus";

import Layout from "@/layouts/DashboardLayout.vue";
const page = usePage();
const layout = computed(() => {
    const width = window.innerWidth;
    let cols =
        width < 414
            ? 1
            : width <= 768
            ? 2
            : width <= 1366
            ? 3
            : width <= 1920
            ? 4
            : 6;
    let rows =
        width < 414
            ? 12
            : width <= 768
            ? 6
            : width <= 1366
            ? 4
            : width <= 1920
            ? 3
            : 2;
    return {
        cols,
        rows,
    };
});
const loading = ref(false);
const kaldik = ref({
    is_libur: true,
    warna: "red",
});
const showKaldik = ref(false);
const attributes = computed(() => {
    let attrs = [];
    page.props.agendas.forEach((item, index) => {
        attrs.push({
            key: item.id,
            highlight: item.warna,
            dates: { start: new Date(item.mulai), end: new Date(item.selesai) },
            startDate: new Date(item.mulai),
            endDate: new Date(item.selesai),
            popover: {
                label: `${item.label} - ${item.deskripsi}`,
                visibility: "hover",
            },
            index: index,
            description: item.deskripsi,
            customData: item,
        });
    });

    return attrs;
});

const onDayClicked = async (calendar, $event) => {
    let items = [];
    if (calendar.attributeCells.length > 0) {
        const cells = calendar.attributeCells;
        // console.log(cells)
        await cells.forEach((cell) =>
            items.push({ index: cell.data.index, label: cell.data.description })
        );
        let selectedKaldik = page.props.agendas.find(
            (element) => element.id == cells[0].data.key
        );
        selectedKaldik.is_libur = selectedKaldik.is_libur == 1 ? true : false;
        kaldik.value = selectedKaldik;
    } else {
        kaldik.value.mulai = calendar.id;
        kaldik.value.selesai = calendar.id;
    }

    showKaldik.value = true;
};

const simpan = () => {
    loading.value = true;
    let data = kaldik.value;
    data.tapel_id = page.props.tapel.kode;
    router.post(
        route("kaldik.store"),
        { data: data },
        {
            onSuccess: (page) => {
                loading.value = false;
                showKaldik.value = false;
                ElNotification({ title: "Info", message: "Agenda disimpan" });
            },
            onError: (err) => {
                loading.value = false;
                ElNotification({ title: "Error", message: err });
            },
        }
    );
};

const closeDialog = () => {
    showKaldik.value = !showKaldik.value;
    onDialogClose();
};
const onDialogClose = () => {
    loading.value = false;
    kaldik.value = {
        is_libur: true,
        warna: "#ff3344",
    };
};

const dayClicked = (day) => {
    console.log(day);
};

const hapus = () => {
    loading.value = true;
    router.delete(route("kaldik.destroy", { id: kaldik.value.id }), {
        onSuccess: (page) => {
            loading.value = false;
            ElNotification({
                title: "Info",
                message: "Kaldik dihapus",
                type: "success",
            });
        },
        onError: (err) => {
            loading.value = false;
            ElNotification({ title: "Error", message: err, type: "error" });
        },
    });
};
</script>

<template>
    <div title="Kalender Pendidikan">
        <Head title="Kalender Pendidikan" />
        <h3 class="text-center font-bold uppercase text-blue-900 text-2xl mb-8">
            KALENDER PENDIDIKAN {{ page.props.tapel.label }}
        </h3>
        <div class="w-full flex justify-center">
            <Calendar
                :columns="layout.cols"
                :rows="layout.rows"
                is-expanded
                :min-date="`${page.props.tapel.label.split('/')[0]}-07-01`"
                :max-date="`${page.props.tapel.label.split('/')[1]}-06-30`"
                locale="id"
                @dayclick="onDayClicked"
                timezone="Asia/Jakarta"
                :attributes="attributes"
            />
        </div>

        <el-dialog
            v-model="showKaldik"
            @close="onDialogClose"
            :show-close="false"
        >
            <template #header>
                <div class="w-full flex justify-between">
                    <span>Form Agenda</span>
                    <div class="toolbar flex gap-1">
                        <el-button
                            :native-type="null"
                            type="danger"
                            :loading="loading"
                            @click="hapus"
                            >Hapus</el-button
                        >
                        <el-button
                            :native-type="null"
                            type="primary"
                            :loading="loading"
                            @click="simpan"
                            >Simpan</el-button
                        >
                    </div>
                </div>
            </template>
            <div class="dialog-body">
                <el-form v-model="kaldik" label-position="top">
                    <el-row>
                        <el-col :span="24">
                            <el-form-item label="Label">
                                <el-input
                                    v-model="kaldik.label"
                                    placeholder="Nama Kegiatan"
                                ></el-input>
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-row :gutter="10" class="w-full">
                        <el-col :span="8">
                            <el-form-item label="Mulai">
                                <el-date-picker
                                    v-model="kaldik.mulai"
                                    placeholder="Tanggal Mulai"
                                    format="DD-MM-YYYY"
                                    value-format="YYYY-MM-DD"
                                />
                            </el-form-item>
                        </el-col>
                        <el-col :span="8">
                            <el-form-item label="Selesai">
                                <el-date-picker
                                    v-model="kaldik.selesai"
                                    placeholder="Tanggal Selesai"
                                    format="DD-MM-YYYY"
                                    value-format="YYYY-MM-DD"
                                />
                            </el-form-item>
                        </el-col>
                        <el-col :span="8">
                            <el-form-item label="Pelaksana">
                                <el-select
                                    v-model="kaldik.pelaksana"
                                    placeholder="Pilih Pelaksanan"
                                >
                                    <el-option
                                        label="KKG"
                                        value="kkg"
                                    ></el-option>
                                    <el-option
                                        label="Lembaga"
                                        value="lembaga"
                                    ></el-option>
                                </el-select>
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-row>
                        <el-col :span="24">
                            <el-form-item label="Deskripsi">
                                <el-input
                                    type="textarea"
                                    placeholder="Deskripsi Kegiatan"
                                    v-model="kaldik.deskripsi"
                                />
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-row>
                        <el-col :span="12">
                            <el-switch
                                v-model="kaldik.is_libur"
                                active-text="Libur"
                                inactive-text="Efektif"
                            ></el-switch>
                        </el-col>
                    </el-row>
                </el-form>
            </div>
            <template #footer>
                <div class="flex items-center justify-end">
                    <el-button
                        type="info"
                        :native-type="null"
                        @click="closeDialog"
                        >Batal</el-button
                    >
                </div>
            </template>
        </el-dialog>
    </div>
</template>
