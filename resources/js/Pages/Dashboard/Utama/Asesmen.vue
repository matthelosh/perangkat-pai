<script setup>
import { ref } from "vue";
import { usePage, router, Link } from "@inertiajs/vue3";
import DashLayout from "@/layouts/DashboardLayout.vue";
import { Icon } from "@iconify/vue";

const page = usePage();
const loading = ref(false);
const mode = ref("list");

const asesmen = ref({});
const openForm = () => {
    asesmen.value.jml_soal = [0, 0, 0];
    mode.value = "form";
};

const closeForm = () => {
    asesmen.value = {};
    mode.value = "list";
};

const isLetter = (e) => {
    let char = String.fromCharCode(e.keyCode);
    if (/^[A-Da-d]+$/.test(char)) return true;
    else {
        e.preventDefault();
        alert("Hanya boleh A|B|C|D");
    }
};

const simpan = async () => {
    // console.log(asesmen.value);
    router.post(route("asesmen.store"), asesmen.value, {
        onStart: () => {
            loading.value = true;
        },
        onSuccess: () => {
            mode.value = "list";
            asesmen.value = {};
            router.reload({ only: ["asesmens"] });
        },
        onError: (err) => {
            console.log(err);
        },
        onFinish: () => (loading.value = false),
    });
};

const edit = (item) => {
    item.jml_soal = item.jml_soal.split(",");
    asesmen.value = item;

    mode.value = "form";
};
const hapus = async (id) => {
    // console.log(id);
    // router.post(route("asesmen.destroy", { id: id, _method: "delete" }), null, {
    //     onSuccess: () => {
    //         router.reload({ only: ["asesmens"] });
    //     },
    //     onError: (err) => console.log(err),
    // });
    axios
        .post(route("asesmen.destroy", { id: id, _method: "DELETE" }))
        .then((res) => {
            console.log(res);
            router.reload({ only: ["asesmens"] });
        });
};
</script>

<template>
    <div title="Data Asesmen">
        <el-row>
            <el-col :span="24">
                <div
                    class="toolbar flex items-center justify-between bg-slate-400 p-2 text-white"
                >
                    <h3>
                        Asesmen {{ page.props.semester.label }}
                        {{ page.props.tapel.label }}
                    </h3>
                    <div class="tool-items flex items-center">
                        <Link
                            :href="appRoute('soal.index')"
                            class="flex items-center gap-1 mr-3"
                        >
                            <Icon icon="mdi:table-question" />
                            Bank Soal
                        </Link>
                        <el-button
                            :native-type="null"
                            @click="openForm"
                            v-if="mode == 'list'"
                        >
                            <Icon icon="mdi-plus" />
                        </el-button>
                        <el-button
                            :native-type="null"
                            @click="closeForm"
                            type="danger"
                            v-if="mode == 'form'"
                        >
                            <Icon icon="mdi-close" />
                        </el-button>
                    </div>
                </div>
                <el-table :data="page.props.asesmens" v-if="mode == 'list'">
                    <el-table-column label="No" type="index"></el-table-column>
                    <el-table-column
                        label="Label"
                        prop="label"
                    ></el-table-column>
                    <el-table-column
                        label="Kunci"
                        prop="kunci"
                    ></el-table-column>
                    <el-table-column
                        label="Kelas"
                        prop="tingkat"
                    ></el-table-column>
                    <el-table-column label="Periode">
                        <template #default="{ row }">
                            <span>
                                Semester {{ row.semester }} Tahun
                                {{ row.tapel }}</span
                            >
                        </template>
                    </el-table-column>
                    <el-table-column label="Jml Soal">
                        <template #default="{ row }">
                            <span>
                                {{ row.soals?.length }}
                            </span>
                        </template>
                    </el-table-column>
                    <el-table-column label="Opsi">
                        <template #default="{ row }">
                            <el-button-group :native-type="null">
                                <el-button
                                    :native-type="null"
                                    @click="edit(row)"
                                >
                                    <Icon icon="mdi:pencil" />
                                </el-button>
                                <el-button
                                    :native-type="null"
                                    type="danger"
                                    @click="hapus(row.id)"
                                >
                                    <Icon icon="mdi:delete" />
                                </el-button>
                            </el-button-group>
                        </template>
                    </el-table-column>
                </el-table>

                <!-- Form Asesmen -->
                <div v-if="mode == 'form'" class="bg-white p-4">
                    <h3 class="text-center my-2">Form Asesmen</h3>
                    <el-form
                        class="w-[400px] mx-auto border p-4 rounded shadow-lg"
                        label-position="top"
                        @submit.prevent="simpan"
                    >
                        <el-form-item label="Judul">
                            <el-input
                                v-model="asesmen.label"
                                placeholder="Judul Asesmen"
                            ></el-input>
                        </el-form-item>
                        <el-row :gutter="20">
                            <el-col :span="8">
                                <el-form-item label="Tingkat">
                                    <el-select
                                        v-model="asesmen.tingkat"
                                        placeholder="Kelas"
                                    >
                                        <el-option
                                            v-for="tingkat in [
                                                '1',
                                                '2',
                                                '3',
                                                '4',
                                                '5',
                                                '6',
                                            ]"
                                            :value="tingkat"
                                            :label="`Kelas ${tingkat}`"
                                        ></el-option>
                                    </el-select>
                                </el-form-item>
                            </el-col>
                            <el-col :span="8">
                                <el-form-item label="Tipe">
                                    <el-select
                                        v-model="asesmen.tipe"
                                        placeholder="Tipe"
                                    >
                                        <el-option
                                            v-for="tipe in ['uh', 'pts', 'pas']"
                                            :value="tipe"
                                            :label="`${tipe.toUpperCase()}`"
                                        ></el-option>
                                    </el-select>
                                </el-form-item>
                            </el-col>
                            <el-col :span="24">
                                <el-row :gutter="10">
                                    <el-col :span="8">
                                        <el-form-item label="Jml Soal PG">
                                            <el-input
                                                placeholder="Soal"
                                                v-model="asesmen.jml_soal[0]"
                                            ></el-input>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="8">
                                        <el-form-item label="Jml Soal Uraian">
                                            <el-input
                                                placeholder="Soal"
                                                v-model="asesmen.jml_soal[1]"
                                            ></el-input>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="8">
                                        <el-form-item label="Jml Soal Essay">
                                            <el-input
                                                placeholder="Soal"
                                                v-model="asesmen.jml_soal[2]"
                                            ></el-input>
                                        </el-form-item>
                                    </el-col>
                                </el-row>
                            </el-col>
                        </el-row>
                        <el-row>
                            <el-col>
                                <el-form-item
                                    :label="`Kunci Jawaban: ${
                                        asesmen?.kunci
                                            ? asesmen?.kunci?.length
                                            : '0'
                                    }`"
                                >
                                    <el-input
                                        type="textarea"
                                        placeholder="Ketikkan Kunci Jawaban"
                                        v-model="asesmen.kunci"
                                        @keypress="isLetter($event)"
                                    ></el-input>
                                </el-form-item>
                            </el-col>
                        </el-row>
                        <el-row justify="center">
                            <el-button
                                :native-type="null"
                                type="primary"
                                @click="simpan"
                                >Simpan</el-button
                            >
                        </el-row>
                    </el-form>
                </div>
            </el-col>
        </el-row>
    </div>
</template>
