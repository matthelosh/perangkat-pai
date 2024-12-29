<script setup>
import { ref, computed } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import { ElNotification } from "element-plus";
import { hasRole } from "@/helpers/authHelper";
import { read, utils } from "xlsx";
import { Icon } from "@iconify/vue";
const page = usePage();
const materis = computed(() =>
    Object.groupBy(page.props.materis, (materi) => materi.tingkat)
);

const loading = ref(false);
const selectedMateri = ref(null);
const formKonten = ref(false);
const newKonten = ref({});
const showFormKonten = (materi) => {
    selectedMateri.value = materi;
    newKonten.value = {
        materi_id: materi.id,
        tingkat: materi.tingkat,
        fase: materi.tingkat > 4 ? "C" : materi.tingkat > 2 ? "B" : "A",
    };
    formKonten.value = true;
};

const addKonten = () => {
    router.post(
        route("submateri.store"),
        { data: newKonten.value },
        {
            onSuccess: (page) => {
                ElNotification({
                    title: "Info",
                    message: "Submateri disimpan",
                    type: "success",
                });
                router.reload();
                newKonten.value.label = "";
            },
            onError: (err) => {
                console.log(err);
            },
        }
    );
};

const hapusKonten = (konten) => {
    router.delete(route("submateri.destroy", { id: konten.id }), {
        onSuccess: (page) => {
            router.reload();
            ElNotification({
                title: "Info",
                message: "Submateri dihapus",
                type: "success",
            });
        },
        onError: (err) => {
            console.log(err);
        },
    });
};

// IMpor Sub Materi
const onFileSubmateriPicke = async (e) => {
    const file = e.target.files[0];
    const ab = await file.arrayBuffer();

    const wb = await read(ab);
    const ws = wb.Sheets[wb.SheetNames[0]];
    const datas = utils.sheet_to_json(ws);

    router.post(
        route("kurikulum.submateri.impor"),
        { datas: datas },
        {
            onStart: () => (loading.value = true),
            onSuccess: () => {
                ElNotification({
                    title: "Info",
                    message: page.props.flash.message,
                    type: "success",
                });
                router.reload();
            },
            onFinish: () => (loading.value = false),
        }
    );
};
</script>

<template>
    <div>
        <el-card>
            <template #header>
                <div class="toolbar flex justify-between items-center">
                    <h3
                        class="font-bold text-blue-800 text-lg flex justify-between"
                    >
                        Materi Pembelajaran
                    </h3>
                    <div class="left">
                        <input
                            type="file"
                            ref="fileSubmateri"
                            accept=".xlsx,.xls,.ods"
                            @change="onFileSubmateriPicke"
                            class="hidden"
                        />
                        <el-button
                            :native-type="null"
                            type="primary"
                            @click="$refs.fileSubmateri.click()"
                            >Impor Submateri</el-button
                        >
                    </div>
                </div>
            </template>
            <div class="card-body">
                <el-collapse accordion>
                    <el-collapse-item
                        v-for="tingkat in 6"
                        :key="tingkat"
                        :name="tingkat"
                    >
                        <template #title>
                            <h3 class="font-semibold text-lg uppercase">
                                Kelas {{ tingkat }}
                            </h3>
                        </template>
                        <el-collapse accordion>
                            <el-collapse-item
                                v-for="(materi, m) in materis[tingkat]"
                                :key="materi"
                                :name="m + 1"
                            >
                                <template #title>
                                    <div class="flex items-center gap-2 group">
                                        Bab {{ m + 1 }}. {{ materi.label }}
                                        <span class="group-hover:inline">
                                            <el-button
                                                :native-type="null"
                                                type="primary"
                                                @click.stop="
                                                    showFormKonten(materi)
                                                "
                                                >Tambah konten materi</el-button
                                            >
                                        </span>
                                    </div>
                                </template>
                                <ul class="pl-6 list-[upper-alpha]">
                                    <li
                                        v-for="(konten, k) in materi.kontens"
                                        :key="konten.id"
                                    >
                                        <div class="group">
                                            {{ konten.label }}
                                            <span
                                                class="hidden group-hover:inline"
                                                v-if="hasRole('admin')"
                                            >
                                                <el-popconfirm
                                                    title="Yakin hapus konten?"
                                                    @confirm="
                                                        hapusKonten(konten)
                                                    "
                                                >
                                                    <template #reference>
                                                        <el-button
                                                            :native-type="null"
                                                            type="danger"
                                                            text
                                                            >Hapus?</el-button
                                                        >
                                                    </template>
                                                </el-popconfirm>
                                            </span>
                                        </div>
                                    </li>
                                </ul>
                            </el-collapse-item>
                        </el-collapse>
                    </el-collapse-item>
                </el-collapse>
            </div>
        </el-card>

        <el-dialog v-model="formKonten">
            <template #header>
                <h3>
                    Tambahkan Konten Bab
                    {{
                        selectedMateri.id -
                        10 * parseInt(selectedMateri.tingkat - 1)
                    }}. {{ selectedMateri.label }}
                </h3>
            </template>
            <div class="dialog-body">
                <el-form v-model="newKonten" label-position="top">
                    <el-form-item label="Konten / Sub Materi" class="w-[100%]">
                        <el-input
                            type="textarea"
                            placeholder="Isi Sub Materi / Konten"
                            v-model="newKonten.label"
                            @keyup.enter="addKonten"
                        />
                    </el-form-item>
                    <el-form-item>
                        <el-button
                            :native-type="null"
                            type="primary"
                            @click="addKonten"
                            >Tambahkan</el-button
                        >
                    </el-form-item>
                </el-form>
            </div>
        </el-dialog>

        <Teleport to="body" v-if="loading">
            <div
                class="loading fixed top-0 right-0 bottom-0 left-0 z-[99999] bg-slate-400 bg-opacity-70 backdrop-blur flex items-center justify-center"
            >
                <div class="content text-center text-white">
                    <Icon
                        icon="mdi-loading"
                        class="animate-spin text-8xl mx-auto"
                    />
                    <p class="text-center mt-8">Tunggu sebentar</p>
                </div>
            </div>
        </Teleport>
    </div>
</template>
