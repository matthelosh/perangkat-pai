<script setup>
import { ref, computed, defineAsyncComponent } from "vue";
import { Icon } from "@iconify/vue";
import { usePage, router, Link } from "@inertiajs/vue3";
import dayjs from "dayjs";

dayjs.locale("id");
const page = usePage();

const Kop = defineAsyncComponent(() => import("@/components/Umum/Kop.vue"));
const Ttd = defineAsyncComponent(() => import("@/components/Umum/Ttd.vue"));

const jadwals = ref([]);

const showJadwal = (hari, jamke) => {
    // return jadwals.value.find(item => item.hari == hari)
    if (page.props.jadwals.length > 0) {
        let jadwal = page.props.jadwals.find(
            (item) =>
                item.hari == hari &&
                jamke >= parseInt(item.jamke) &&
                jamke < parseInt(item.jamke) + parseInt(item.jml_jam)
        );

        return jadwal;
    } else {
        return "-";
    }
};

const jadwal = ref({
    hari: "Senin",
    jamke: "1",
});
const showForm = ref(false);
const close = () => {
    showForm.value = false;
    jadwal.value = { hari: "Senin", jamke: "1" };
};

const simpan = async (e) => {
    e.preventDefault();
    let data = jadwal.value;
    data.tapel = page.props.tapel.kode;
    data.guru_id = page.props.user.userable.nip;
    // if(data.id) { data._method = 'PUT'}
    router.post(route("jadwal.store"), data, {
        onSuccess: (page) => {
            jadwal.value = { hari: "Senin", jamke: "1" };
            showForm.value = false;
        },
        onError: (err) => {
            Object.keys(err).forEach((k) => {
                notify(err[k], "error");
            });
        },
    });
};

const edit = (item) => {
    jadwal.value = item;
    showForm.value = true;
};

const rombels = computed(() => {
    return page.props.rombels.map((item) => {
        return { text: item.label, value: item.kode };
    });
});

const jams = computed(() => {
    let res = [];

    for (let i = 0; i < 10; i++) {
        res.push(
            dayjs("2024-02-27 07:00")
                .add(35 * i, "minute")
                .format("HH:mm")
        );
    }

    return res;
});

const cetak = async () => {
    let style =
        page.props.app_env == "local"
            ? `<link href="stylesheet" href="${window.location.origin}:5173/resources/css/app.css" />`
            : (style = `<link href="stylesheet" href="${window.location.origin}/build/assets/app.css" /><link href="stylesheet" href="${window.location.origin}/build/assets/app2.css" />`);
    let element = document.querySelector(".cetak");
    let html = `
            <!doctype html>
            <html>
                <head>
                    <title>Jadwal Pelajaran PAI | ${page.props.user.userable.nama}</title>    
                    ${style}
                    </head>

                <body>
                    
                    ${element.outerHTML}
                </body>
            </html>
    `;

    let win = window.open("", "_blank", "width=800,height=600");
    win.document.write(html);
    setTimeout(() => {
        win.print();
        // win.close()
    }, 500);
};
</script>

<template>
    <div class="w-full relative">
        <div
            class="toolbar h-10 bg-slate-200 flex items-center justify-between p-3 text-slate-800"
        >
            <h1 class="flex gap-1">
                <Icon icon="mdi:calendar" class="text-2xl" />
                Jadwal Pelajaran
            </h1>
            <div class="items flex gap-4">
                <button
                    @click="cetak"
                    class="hover:bg-slate-600 rounded-full p-1 transition-all"
                >
                    <Icon
                        icon="mdi:printer"
                        class="text-xl hover:text-slate-200 active:text-slate-100"
                    />
                </button>
                <button
                    @click="showForm = true"
                    class="hover:bg-slate-600 rounded-full p-1 transition-all"
                >
                    <Icon
                        icon="mdi:plus-circle"
                        class="text-xl hover:text-slate-200 active:text-slate-100"
                    />
                </button>
            </div>
        </div>
        <div class="content p-3 bg-white cetak">
            <Kop class="print:flex" />
            <div class="title my-4">
                <h3 class="font-bold text-xl text-center uppercase">
                    jadwal Pelajaran Pendidikan Agama Islam (PAI)
                </h3>
                <h3 class="font-bold text-xl text-center uppercase">
                    TP. {{ page.props.tapel.label }} Semester
                    {{ page.props.semester.kode == "1" ? "Ganjil" : "genap" }}
                </h3>
            </div>
            <table class="border w-full" v-if="page.props.rombels.length > 0">
                <thead>
                    <tr class="bg-slate-300">
                        <th rowspan="2" class="border p-2 border-black">
                            Jamke
                        </th>
                        <th colspan="6" class="border p-2 border-black">
                            Hari
                        </th>
                    </tr>
                    <tr class="bg-slate-300">
                        <th class="border p-2 border-black">Senin</th>
                        <th class="border p-2 border-black">Selasa</th>
                        <th class="border p-2 border-black">Rabu</th>
                        <th class="border p-2 border-black">Kamis</th>
                        <th class="border p-2 border-black">Jumat</th>
                        <th class="border p-2 border-black">Sabtu</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="jamke in 10" :key="jamke">
                        <td
                            class="border text-center p-2 border-black bg-slate-300"
                        >
                            {{ jamke }} | {{ jams[jamke - 1] }}
                        </td>
                        <td
                            class="border text-center p-2 border-black"
                            :class="
                                showJadwal('Senin', jamke)
                                    ? 'bg-white'
                                    : 'bg-slate-200'
                            "
                        >
                            <button
                                class="hover:text-teal-500"
                                @click="edit(showJadwal('Senin', jamke))"
                            >
                                {{ showJadwal("Senin", jamke)?.rombel?.label }}
                            </button>
                        </td>
                        <td
                            class="border text-center p-2 border-black"
                            :class="
                                showJadwal('Selasa', jamke)
                                    ? 'bg-white'
                                    : 'bg-slate-200'
                            "
                        >
                            <button
                                class="hover:text-teal-500"
                                @click="edit(showJadwal('Selasa', jamke))"
                            >
                                {{ showJadwal("Selasa", jamke)?.rombel?.label }}
                            </button>
                        </td>
                        <td
                            class="border text-center p-2 border-black"
                            :class="
                                showJadwal('Rabu', jamke)
                                    ? 'bg-white'
                                    : 'bg-slate-200'
                            "
                        >
                            <button
                                class="hover:text-teal-500"
                                @click="edit(showJadwal('Rabu', jamke))"
                            >
                                {{ showJadwal("Rabu", jamke)?.rombel?.label }}
                            </button>
                        </td>
                        <td
                            class="border text-center p-2 border-black"
                            :class="
                                showJadwal('Kamis', jamke)
                                    ? 'bg-white'
                                    : 'bg-slate-200'
                            "
                        >
                            <button
                                class="hover:text-teal-500"
                                @click="edit(showJadwal('Kamis', jamke))"
                            >
                                {{ showJadwal("Kamis", jamke)?.rombel?.label }}
                            </button>
                        </td>
                        <td
                            class="border text-center p-2 border-black"
                            :class="
                                showJadwal('Jumat', jamke)
                                    ? 'bg-white'
                                    : 'bg-slate-200'
                            "
                        >
                            <button
                                class="hover:text-teal-500"
                                @click="edit(showJadwal('Jumat', jamke))"
                            >
                                {{ showJadwal("Jumat", jamke)?.rombel?.label }}
                            </button>
                        </td>
                        <td
                            class="border text-center p-2 border-black"
                            :class="
                                showJadwal('Sabtu', jamke)
                                    ? 'bg-white'
                                    : 'bg-slate-200'
                            "
                        >
                            <button
                                class="hover:text-teal-500"
                                @click="edit(showJadwal('Sabtu', jamke))"
                            >
                                {{ showJadwal("Sabtu", jamke)?.rombel?.label }}
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <el-alert type="error" v-else title="Buat Rombel dulu." class="p-3">
                <p class="mb-4">Anda belum membuat rombel</p>
                <Link
                    :href="appRoute('rombel.index')"
                    class="px-3 py-2 bg-sky-300 text-sky-800 rounded-xl font-bold"
                    >Buat Rombel</Link
                >
            </el-alert>
            <!-- {{ jadwals }} -->
            <Ttd class="hidden print:flex" />
        </div>

        <div
            class="w-full bg-slate-100 bg-opacity-20 backdrop-blur-sm fixed top-0 right-0 bottom-0 left-0 flex items-center justify-center z-40"
            @click.self="close"
            v-if="showForm"
        >
            <div class="dialog bg-white rounded shadow w-[400px] max-w-[800px]">
                <div class="toolbar p-3 flex items-center bg-slate-300">
                    Formulir Jadwal
                </div>
                <div class="content px-3 py-2">
                    <form ref="formJadwal" @submit.prevent="simpan">
                        <div class="row w-full my-2">
                            <label for="rombel_id">Rombel</label>
                            <select
                                v-model="jadwal.rombel_id"
                                class="w-full bg-slate-50 border-none"
                            >
                                <option
                                    v-for="rombel in rombels"
                                    :value="rombel.value"
                                    :key="rombel"
                                >
                                    {{ rombel.text }}
                                </option>
                            </select>
                        </div>
                        <div class="row w-full my-2">
                            <label for="hari">Hari</label>
                            <select
                                v-model="jadwal.hari"
                                class="w-full bg-slate-50 border-none"
                            >
                                <option
                                    v-for="hari in [
                                        'Senin',
                                        'Selasa',
                                        'Rabu',
                                        'Kamis',
                                        'Jumat',
                                        'Sabtu',
                                    ]"
                                    :value="hari"
                                    :key="hari"
                                >
                                    {{ hari }}
                                </option>
                            </select>
                        </div>
                        <div class="row w-full my-2">
                            <label for="jml_jam">Jumlah Jam</label>
                            <input
                                type="number"
                                size="2"
                                placeholder="Jumlah Jam Mengajar"
                                v-model="jadwal.jml_jam"
                                class="w-full bg-slate-50 border-none"
                            />
                        </div>
                        <div class="row w-full my-2">
                            <label for="jamke">Jam Ke</label>
                            <select
                                v-model="jadwal.jamke"
                                class="w-full bg-slate-50 border-none"
                            >
                                <option
                                    v-for="jam in 10"
                                    :value="jam"
                                    :key="jam"
                                >
                                    {{ jam }}
                                </option>
                            </select>
                        </div>
                        <div
                            class="row w-full my-2 flex items-center justify-center"
                        >
                            <button
                                class="bg-sky-400 hover:bg-sky-600 active:bg-sky-500 text-white py-1 px-3 rounded"
                                @click="simpan"
                            >
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
@media print {
    button {
        border: none;
        outline: none;
        background: white;
    }
}
</style>
