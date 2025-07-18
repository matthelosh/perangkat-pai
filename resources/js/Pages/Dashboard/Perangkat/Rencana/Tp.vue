<script setup>
import { ref, computed, defineAsyncComponent, onBeforeMount, watch } from "vue";
import { Head, usePage, router } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";
import * as _ from "lodash";
import Layout from "@/layouts/DashboardLayout.vue";
import { ElNotification } from "element-plus";

const page = usePage();
const CetakTp = defineAsyncComponent(() =>
    import("@/components/Dashboard/Perangkat/Rencana/CetakTp.vue")
);
const FormSyncTp = defineAsyncComponent(() =>
    import("@/components/Dashboard/Perangkat/Rencana/FormSyncTp.vue")
);
const mine = ref(true);
const elemens = computed(() => page.props.elemens);
const fase = computed(() => route().params.fase);
const mode = ref("form");
const cpTeks = page.props.elemens;
const addTp = (elemen) => {
    const index = elemens.value.findIndex((el) => el.kode == elemen.kode);
    const params = computed(() => route().params ?? {});
    console.log(elemens.value[index]);
    elemens.value[index].tps.push({
        elemen_id: elemen.kode,
        fase: elemens.value[index].fase,
        kompetensi: "",
        materi: "",
        teks: "",
        guru_id: mine.value ? page.props.user.userable.nip : null,
    });
};

const addKompetensi = (ev, e, t) => {
    let content = ev.target.innerText;
    elemens.value[e].tps[t].kompetensi = content;
};
const addMateri = (ev, e, t) => {
    let content = ev.target.innerText;
    elemens.value[e].tps[t].materi = content;
};
const addTujuan = (ev, e, t) => {
    let content = ev.target.innerText;
    elemens.value[e].tps[t].teks = content;
};

const duplicateTP = (ev, e, t) => {
    let tr = ev.target.closest("tr");
    let tds = tr.querySelectorAll("td");
    let dup = {
        elemen_id: elemens.value[e].kode,
        fase: elemens.value[e].fase,
        kompetensi: tds[0].innerText,
        materi: tds[1].innerText,
        text: "",
    };

    elemens.value[e].tps.push(dup);
};
const onTujuanKeyPressed = async (ev, e, t) => {
    if (ev.key == "Enter") {
        ev.preventDefault();
        addTujuan(ev, e, t);
        duplicateTP(ev, e, t);
    } else if (ev.keyCode == "40") {
        let tbody = ev.target.closest("tbody");
        let trs = tbody.querySelectorAll("tr");
        let tr = trs[t + 1];
        let tds = tr.querySelectorAll("td");
        tds[2].focus();
    } else if (ev.keyCode == "38") {
        let tbody = ev.target.closest("tbody");
        let trs = tbody.querySelectorAll("tr");
        let tr = trs[t - 1];
        let tds = tr.querySelectorAll("td");
        tds[2].focus();
    }
};

const simpanTp = (elemen) => {
    router.post(
        route("tp.store", { _query: { mine: mine.value } }),
        { elemen: elemen },
        {
            onSuccess: (page) => {
                ElNotification({
                    title: "Info",
                    message: "Tujuan Pembelajaran disimpan",
                });
            },
        }
    );
};

const hapusTp = (tp, e, t) => {
    if (!tp.id) {
        elemens.value[e].tps.splice(t, 1);
    } else {
        router.delete(route("tp.destroy", { id: tp.id }), {
            onSuccess: (page) => {
                ElNotification({ title: "info", message: "TP Dihapus" });
                // router.reload({only: ['elemens']})
            },
            onError: (err) => {
                console.log(err);
            },
        });
    }
};

const tutup = () => {
    router.get(route("rencana"));
};

const onMineChanged = (e) => {
    let params = route().params;

    router.get(
        "/rencana/tp",
        { fase: params.fase, mine: mine.value },
        { preserveState: true, only: ["elemens"] }
    );
    // router.reload({only: ['elemens'], preserveState: true})
};

const groupMe = (datas, key) => {
    return Object.groupBy(datas, (data) => data[key]);
};

const cetak = async () => {
    mode.value = "cetak";
};

// SYnc TP Rapor SD
const formSyncTp = ref(false);
const closeFormSyncTp = () => {
    formSyncTp.value = false;
    router.reload({ only: ["elemens"] });
};
const syncTpRapor = async () => {
    formSyncTp.value = true;
};

// Drawer Materi
const drawerMateri = ref(false);

const params = computed(() => route().params);
onBeforeMount(() => {
    mine.value = params.mine == "true";
});
</script>

<template>
    <div title="Tujuan Pembelajaran">
        <el-card v-if="mode == 'form'">
            <template #header>
                <div class="w-full flex items-center justify-between">
                    <div class="title flex gap-1">
                        <el-button :native-type="null" @click="tutup" link>
                            <Icon
                                icon="mdi:arrow-left"
                                class="text-xl font-bold"
                            />
                        </el-button>
                        <h3>Tujuan Pembelajaran</h3>
                    </div>
                    <div class="toolbar-items flex gap-1">
                        <el-button-group>
                            <el-button
                                :native-type="null"
                                type="primary"
                                @click="syncTpRapor"
                            >
                                <Icon icon="mdi:list-box" />
                                Sinkron TP Rapor
                            </el-button>
                            <el-button
                                :native-type="null"
                                type="primary"
                                @click="drawerMateri = !drawerMateri"
                            >
                                <Icon icon="mdi:list-box" />
                                Lihat Materi
                            </el-button>
                        </el-button-group>
                        <el-button :native-type="null" circle @click="cetak">
                            <Icon icon="mdi:printer" />
                        </el-button>
                    </div>
                </div>
            </template>
            <div class="card-body">
                <el-row>
                    <el-switch
                        v-model="mine"
                        active-text="Punya saya"
                        inactive-text="Dari sistem"
                        @change="onMineChanged"
                    ></el-switch>
                </el-row>
                <el-collapse accordion>
                    <el-collapse-item
                        v-for="(elemen, e) in page.props.elemens"
                        :key="elemen.kode"
                    >
                        <template #title>
                            <span class="text-lg font-bold text-slate-700">
                                {{ elemen.label }}
                                <span class="text-sky-600 font-light text-sm"
                                    >[{{ elemen.tps?.length }} TP]</span
                                >
                            </span>
                        </template>
                        <div class="w-full">
                            <el-button-group :native-type="null">
                                <el-tooltip
                                    content="Tambah Tujuan"
                                    placement="top-start"
                                >
                                    <el-button
                                        :native-type="null"
                                        size="small"
                                        @click="addTp(elemen)"
                                        type="success"
                                        class="bg-green-500"
                                    >
                                        <Icon icon="mdi:plus" />
                                    </el-button>
                                </el-tooltip>
                                <el-tooltip
                                    content="Simpan Tujuan"
                                    placement="top-start"
                                >
                                    <el-button
                                        :native-type="null"
                                        size="small"
                                        @click="simpanTp(elemen)"
                                        type="primary"
                                        class="bg-sky-500"
                                    >
                                        <Icon icon="mdi:send" />
                                    </el-button>
                                </el-tooltip>
                            </el-button-group>
                            <table>
                                <tr>
                                    <th class="align-top">CP</th>
                                    <th class="align-top px-2">:</th>
                                    <td class="align-top text-justify">
                                        {{ elemen.deskripsi_cp }}
                                    </td>
                                </tr>
                            </table>
                            <el-scrollbar max-height="500">
                                <table
                                    class="w-full bg-white"
                                    v-if="elemen.tps.length > 0"
                                >
                                    <thead>
                                        <tr>
                                            <th class="border border-slate-600">
                                                Kompetensi
                                            </th>
                                            <th class="border border-slate-600">
                                                Materi Esensial
                                            </th>
                                            <th class="border border-slate-600">
                                                Tujuan Pembelajaran
                                            </th>
                                            <th class="border border-slate-600">
                                                Opsi
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="(tp, t) in elemen.tps"
                                            :key="tp.kode"
                                            class="hover:bg-sky-100 transition-all duration-300"
                                        >
                                            <td
                                                class="border border-slate-600 p-1"
                                                contenteditable
                                                @blur="
                                                    addKompetensi($event, e, t)
                                                "
                                            >
                                                {{ tp.kompetensi }}
                                            </td>
                                            <td
                                                class="border border-slate-600 p-1"
                                                contenteditable
                                                @blur="addMateri($event, e, t)"
                                            >
                                                {{ tp.materi }}
                                            </td>
                                            <td
                                                class="border border-slate-600 p-1"
                                                contenteditable
                                                @blur="addTujuan($event, e, t)"
                                                @keyup="
                                                    onTujuanKeyPressed(
                                                        $event,
                                                        e,
                                                        t
                                                    )
                                                "
                                            >
                                                {{ tp.teks }}
                                            </td>
                                            <td
                                                class="border border-slate-600 p-1 w-[13%] text-center"
                                            >
                                                <el-button
                                                    :native-type="null"
                                                    circle
                                                    text
                                                    type="primary"
                                                    @click="
                                                        duplicateTP(
                                                            $event,
                                                            e,
                                                            t
                                                        )
                                                    "
                                                    ><Icon
                                                        icon="mdi:file-document-multiple"
                                                        class="text-xl"
                                                /></el-button>
                                                <el-popconfirm
                                                    title="Yakin menghapus TP ini?"
                                                    confirm-button-text="Yakin"
                                                    cancel-button-text="Batal"
                                                    @confirm="hapusTp(tp, e, t)"
                                                >
                                                    <template #reference>
                                                        <el-button
                                                            :native-type="null"
                                                            circle
                                                            text
                                                            type="danger"
                                                            ><Icon
                                                                icon="mdi:close-circle"
                                                                class="text-xl"
                                                        /></el-button>
                                                    </template>
                                                </el-popconfirm>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </el-scrollbar>
                        </div>
                    </el-collapse-item>
                </el-collapse>
            </div>
        </el-card>

        <CetakTp
            v-if="mode == 'cetak'"
            :elemens="elemens"
            @close="mode = 'form'"
        />
        <FormSyncTp
            v-if="formSyncTp"
            :open="formSyncTp"
            :selected-fase="params.fase"
            @close="closeFormSyncTp"
        />
        <el-drawer v-model="drawerMateri" :title="`Materi Fase ${params.fase}`">
            <template #default>
                <div>
                    <ol class="list-disc list-inside">
                        <li v-for="materi in page.props.materis">
                            <span>
                                {{ materi.bab }}
                                <small> [Kelas {{ materi.tingkat }}]</small>
                                {{ materi.label }}
                            </span>
                        </li>
                    </ol>
                </div>
            </template>
        </el-drawer>
    </div>
</template>
