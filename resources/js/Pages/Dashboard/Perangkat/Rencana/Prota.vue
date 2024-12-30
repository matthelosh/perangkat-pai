<script setup>
import { ref, computed, defineAsyncComponent } from "vue";
import { usePage, router, Head, Link } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";
import { Calendar } from "v-calendar";
import "v-calendar/style.css";
import { ElNotification } from "element-plus";
import { weekOfMonth } from "@/helpers/ApeHelper";
import Layout from "@/layouts/DashboardLayout.vue";
import { cssFiles } from "@/helpers/printHelper";

const CetakProta = defineAsyncComponent(() =>
    import("@/components/Dashboard/Perangkat/Rencana/CetakProta.vue")
);

const page = usePage();
const mode = ref("list");
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
const haris = ref([
    "Minggu",
    "Senin",
    "Selasa",
    "Rabu",
    "Kamis",
    "Jumat",
    "Sabtu",
]);
const offDays = computed(() => {
    let days = [];
    const day = haris.value.lastIndexOf(page.props.rombel.jadwals[0].hari);
    for (let d = 0; d < 7; d++) {
        if (d !== day) days.push(d + 1);
    }
    return days;
});
const loading = ref(false);
const kaldik = ref({
    is_libur: true,
    warna: "red",
});
const params = computed(() => route().params);

const showFormProta = ref(false);
const prota = ref({
    guru_id: params.value.mine == "1" ? page.props.user.userable.nip : null,
    rombel_id: page.props.rombel.kode,
    tanggal: null,
    atp_id: null,
    minggu_ke: null,
    warna: "blue",
    aw: 0,
});

const disabledDates = computed(() => {
    let dates = [];
    attributes.value.forEach((dt) => {
        if (dt.is_libur) dates.push({ start: dt.startDate, end: dt.endDate });
    });
    dates.push({
        repeat: { weekdays: offDays.value },
    });
    dates.push({ start: new Date(2024, 4, 3), end: new Date(2024, 4, 3) });

    return dates;
});

const protas = computed(() => {
    const protas = page.props.atps.map((atp) => atp.protas ?? []);
    return protas.flat(2);
});

const sekolah = page.props.sekolahs[0];
const showKaldik = ref(false);
const attributes = computed(() => {
    let attrs = [];
    page.props.agendas.forEach((item, index) => {
        attrs.push({
            key: item.id,
            highlight: item.warna ?? "blue",
            dates: { start: new Date(item.mulai), end: new Date(item.selesai) },
            startDate: new Date(item.mulai),
            endDate: new Date(item.selesai),
            popover: {
                label: `${item.label} - ${item.deskripsi}`,
                visibility: "hover",
            },
            index: index,
            description: item.deskripsi,
            is_libur: item.is_libur,
        });
    });

    page.props.protas.forEach((prot, p) => {
        attrs.push({
            key: prot.atp
                ? prot.atp.id + "-" + (p + 1)
                : Math.floor(Math.random() * 100),
            highlight: !prot.atp
                ? prot.atp_id.includes("CADANGAN")
                    ? "purple"
                    : "orange"
                : "",

            dates: {
                start: new Date(prot.tanggal),
                end: new Date(prot.tanggal),
            },
            startDate: new Date(prot.tanggal),
            endtDate: new Date(prot.tanggal),
            popover: {
                label: `${
                    prot.atp
                        ? prot.atp.materi + " | " + prot.atp.konten
                        : prot.atp_id
                }`,
                visibility: "hover",
            },
            index: prot.atp
                ? prot.atp.id + "-" + (p + 1)
                : Math.floor(Math.random() * 100),
            description: prot.atp
                ? prot.atp.materi + " | " + prot.atp.konten
                : prot.atp_id,
            atp_id: prot.atp ? prot.atp.kode : null,
            prota: prot,
        });
    });

    return attrs;
});

const onDayClicked = async (calendar, $event) => {
    if (calendar.isDisabled) {
        let isLibur = calendar.attributeCells[0].data.is_libur;
        ElNotification({
            title: "Info",
            message: `Pilih tanggal sesuai jadwal,  ${
                isLibur ? "ini Hari libur!" : ""
            }`,
            type: "error",
        });
    } else {
        // console.log(calendar)
        if (calendar.attributeCells[0]?.data.prota) {
            prota.value = calendar.attributeCells[0].data.prota;
        }
        prota.value.tanggal = calendar.id;
        prota.value.minggu_ke =
            prota.value.minggu_ke ?? weekOfMonth(prota.value.tanggal);
        showFormProta.value = true;
    }
};

const simpan = () => {
    loading.value = true;
    console.log(prota.value);
    router.post(
        route("prota.store", { _query: params.value }),
        { data: prota.value },
        {
            onSuccess: (page) => {
                loading.value = false;
                ElNotification({
                    title: "Info",
                    message: "Prota Disimpan",
                    type: "success",
                });
                showFormProta.value = false;
            },
            onError: (err) => {
                loading.value = false;
                ElNotification({ title: "Error", message: err, type: "error" });
                console.log(err);
            },
        }
    );
    // console.log(prota.value)
};

const onDialogClose = () => {
    loading.value = false;
    prota.value = {
        guru_id: params.value.mine == "1" ? page.props.user.userable.nip : null,
        rombel_id: page.props.rombel.kode,
        tanggal: null,
        atp_id: null,
        minggu_ke: null,
        aw: 0,
    };
};

const onAtpChanged = (e) => {
    if (!["PTS", "PAS", "CADANGAN"].includes(prota.value.atp_id)) {
        let selectedAtp = page.props.atps.find(
            (atp) => atp.kode == prota.value.atp_id
        );
        console.log(e);
        prota.value.aw = selectedAtp.aw;
        prota.value.semester = selectedAtp.semester;
    } else {
        prota.value.aw = 0;
        prota.value.atp_id =
            prota.value.atp_id + " Semester " + prota.value.semester;
    }
};

const hapusProta = () => {
    router.delete(route("prota.destroy", { id: prota.value.id }), {
        onSuccess: (page) => {
            prota.value = {
                guru_id:
                    params.value.mine == "1"
                        ? page.props.user.userable.nip
                        : null,
                rombel_id: page.props.rombel.kode,
                tanggal: null,
                atp_id: null,
                minggu_ke: null,
                warna: "blue",
                aw: 0,
            };
            showFormProta.value = false;
        },
    });
};

const cetak = async () => {
    let trs = "";
    page.props.protas.forEach((prota, a) => {
        trs += `
        <tr>
            <td class="border border-black p-2 align-top">${a + 1}</td>
            <td class="border border-black p-2 align-top">${
                prota.atp?.kode ?? "-"
            }</td>
            <td class="border border-black p-2 align-top">${
                prota.atp
                    ? prota.atp.tps !== ""
                        ? prota.atp.tps
                        : prota.atp.materi
                    : prota.atp_id
            }</td>
            <td class="border border-black p-2 align-top">${prota.aw}</td>
            <td class="border border-black p-2 align-top">${prota.semester}</td>
        </tr>
        `;
    });

    let html = `<!doctype html>
                <html>
                    <head>
                        <title>Program Tahunan ${page.props.tapel.label}</title>    
                    </head>
                    <body>
                        <h3></h3>
                        <table class="w-[80%] mx-auto">
                            <thead>
                                <tr>
                                    <th class="border border-black px-2">No</th>    
                                    <th class="border border-black px-2">Kode</th>    
                                    <th class="border border-black px-2">ATP</th>    
                                    <th class="border border-black px-2">AW</th>    
                                    <th class="border border-black px-2">SMT</th>    
                                </tr>    
                            </thead>  
                            <tbody>
                                ${trs}
                            </tbody>  
                        </table>    
                    </body>
                </html>
    
    `;

    let win = window.open("", "_blank", "width=800,height=700");
    win.document.write(html);
    const styles = await cssFiles();
    styles.forEach((css) => {
        const link = win.document.createElement("link");
        link.rel = "stylesheet";
        link.href = css;
        win.document.head.append(link);
    });

    setTimeout(() => {
        win.print();
        // win.close()
    }, 1000);
};
</script>

<template>
    <div title="Program Tahunan">
        <Head title="Program Tahunan" />
        <el-row
            class="w-full"
            v-if="page.props.atps.length > 0 && mode == 'list'"
        >
            <el-col :span="24">
                <el-card :body-style="{ background: '#efefef' }">
                    <template #header>
                        <div class="toolbar flex items-center justify-between">
                            <h3
                                class="font-bold text-blue-700 uppercase flex items-center gap-2"
                            >
                                <Link
                                    :href="appRoute('rencana')"
                                    class="text-slate-700 font-bold text-2xl"
                                >
                                    <Icon icon="mdi:arrow-left" />
                                </Link>
                                Prota {{ sekolah.nama }} |
                                {{ page.props.tapel.label }} | Kelas
                                {{ page.props.rombel.label }}
                            </h3>
                            <div class="items flex items-center gap-2">
                                <el-button
                                    :native-type="null"
                                    type=""
                                    circle
                                    @click="mode = 'cetak'"
                                >
                                    <Icon icon="mdi:printer" />
                                </el-button>
                            </div>
                        </div>
                    </template>
                    <div class="card-body">
                        <!-- {{ attributes }} -->
                        <div
                            class="title mb-4 bg-white h-12 flex items-center justify-center"
                        >
                            <h1 class="text-center font-bold">
                                Jumlah: {{ page.props.atps.length }}ATP
                            </h1>
                        </div>
                        <div class="w-full flex justify-center">
                            <Calendar
                                :disabled-dates="disabledDates"
                                :columns="layout.cols"
                                :rows="layout.rows"
                                is-expanded
                                :min-date="`${
                                    page.props.tapel.label.split('/')[0]
                                }-07-01`"
                                :max-date="`${
                                    page.props.tapel.label.split('/')[1]
                                }-06-30`"
                                locale="id"
                                @dayclick="onDayClicked"
                                timezone="Asia/Jakarta"
                                :attributes="attributes"
                            />
                        </div>
                    </div>
                </el-card>
            </el-col>
        </el-row>
        <CetakProta
            :protas="page.props.protas"
            @close="mode = 'list'"
            v-if="mode == 'cetak'"
        />
        <el-drawer
            direction="rtl"
            v-model="showFormProta"
            draggable
            @close="onDialogClose"
        >
            <template #header>
                <div class="w-full flex justify-between items-center">
                    <h3>Form Program Tahunan</h3>
                    <div class="items flex items-center gap-2" v-if="prota.id">
                        <el-popconfirm
                            title="Yakin menghapus Prota ini?"
                            @confirm="hapusProta"
                        >
                            <template #reference>
                                <el-button :native-type="null" type="danger"
                                    >Hapus</el-button
                                >
                            </template>
                        </el-popconfirm>
                    </div>
                </div>
            </template>
            <div class="dialog-body">
                <el-form v-model="prota" label-position="top" :inline="true">
                    <el-form-item label="Tanggal" class="w-[50%]">
                        <el-date-picker v-model="prota.tanggal" disabled />
                    </el-form-item>
                    <el-form-item label="Semester" class="w-[30%]">
                        <el-select
                            v-model="prota.semester"
                            placeholder="Pilih Semester"
                        >
                            <el-option value="1">Semester 1</el-option>
                            <el-option value="2">Semester 2</el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item
                        label="Pilih Materi ATP"
                        width="100%"
                        class="w-[100%]"
                    >
                        <el-select
                            v-model="prota.atp_id"
                            @change="onAtpChanged"
                            filterable
                        >
                            <el-option value="PTS">PTS</el-option>
                            <el-option value="PAS">PAS</el-option>
                            <el-option value="CADANGAN">CADANGAN</el-option>
                            <el-option
                                v-for="(atp, a) in page.props.atps"
                                :key="atp.kode"
                                :value="atp.kode"
                            >
                                <div>{{ atp.materi + " | " + atp.konten }}</div>
                            </el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="AW (JP)">
                        <el-input
                            type="number"
                            v-model="prota.aw"
                            placeholder="Alokasi Waktu"
                        />
                    </el-form-item>
                    <el-form-item label="Minggu Ke" class="w-[25%]">
                        <el-select
                            v-model="prota.minggu_ke"
                            placeholder="Pilih Minggu"
                        >
                            <el-option v-for="m in 5" :key="m" :value="m">{{
                                m
                            }}</el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="&nbsp;">
                        <el-button
                            :native-type="null"
                            type="primary"
                            :loading="loading"
                            @click="simpan"
                            >Simpan</el-button
                        >
                    </el-form-item>
                </el-form>
            </div>
        </el-drawer>
    </div>
</template>
