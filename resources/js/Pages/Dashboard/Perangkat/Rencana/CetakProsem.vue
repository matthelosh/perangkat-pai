<script setup>
import { ref, computed, defineAsyncComponent, onBeforeMount, watch } from "vue";
import { usePage, Link, router } from "@inertiajs/vue3";
import DashLayout from "@/layouts/DashboardLayout.vue";
import { Icon } from "@iconify/vue";
import dayjs from "dayjs";
import localeData from "dayjs/plugin/localeData";
import "dayjs/locale/id";
dayjs.extend(localeData);
dayjs.locale("id");
import { cssLink } from "@/helpers/printHelper";
import { unefektif, allUnefektif, weeksPerMonth } from "@/helpers/ApeHelper";
import { ElNotification } from "element-plus";

const Kop = defineAsyncComponent(() => import("@/components/Umum/Kop.vue"));
const Ttd = defineAsyncComponent(() => import("@/components/Umum/Ttd.vue"));

const page = usePage();
const sekolah = computed(() => page.props.sekolahs[0]);
const params = route().params;
// const semester = ref('1')
const atps = ref([]);
const syahrs = computed(() => {
    let months = dayjs.months();
    return months.slice(
        page.props.semester.kode == "1" ? 6 : 0,
        page.props.semester.kode == "1" ? 12 : 6
    );
});
const pickerVisibilities = ref([]);
const weekDays = computed(() => {
    let wD = dayjs.weekdays();
    let weeks = [0, 1, 2, 3, 4, 5, 6];
    weeks.splice(wD.lastIndexOf(page.props.rombel.jadwals[0].hari), 1);
    return weeks;
});

const minggus = (m) => {
    return weeksPerMonth(
        m,
        page.props.rombel.jadwals[0].hari,
        page.props.semester.kode
    );
};
</script>

<template>
    <DashLayout title="Cetak Program Semester">
        <el-row class="w-full">
            <el-col :span="24">
                <el-card>
                    <template #header>
                        <div class="toolbar flex justify-between items-center">
                            <h3
                                class="font-bold text-blue-600 uppercase flex items-center gap-2"
                            >
                                <Link
                                    :href="
                                        appRoute('prosem.index', {
                                            _query: params,
                                        })
                                    "
                                >
                                    <Icon
                                        icon="mdi:arrow-left"
                                        class="text-xl"
                                    />
                                </Link>
                                Program Semester
                                {{ page.props.semester.kode }} ({{
                                    page.props.semester.kode == "1"
                                        ? "Ganjil"
                                        : "Genap"
                                }}) Kelas {{ page.props.rombel.label }} Tahun
                                {{ page.props.tapel.label }}
                            </h3>
                            <div class="toolbar-items flex items-center gap-2">
                                <el-button :native-type="null" circle type="">
                                    <Icon icon="mdi:printer" />
                                </el-button>
                            </div>
                        </div>
                    </template>
                    <div class="card-body cetak">
                        <Kop />
                        <div class="title uppercase text-center my-6 font-bold">
                            <h3 class="text-xl">
                                PROGRAM Semester (PROSEM)
                                {{
                                    page.props.semester.kode == "1"
                                        ? "Ganjil"
                                        : "Genap"
                                }}
                            </h3>
                            <h3 class="text-xl">
                                TAHUN PELAJARAN {{ page.props.tapel.label }}
                            </h3>
                        </div>
                        <div class="meta w-[90%] mx-auto">
                            <table>
                                <tr>
                                    <td>Satuan Pendidikan</td>
                                    <td>:</td>
                                    <td>{{ sekolah.nama }}</td>
                                </tr>
                                <tr>
                                    <td>Kelas</td>
                                    <td>:</td>
                                    <td>{{ page.props.rombel.label }}</td>
                                </tr>
                                <tr>
                                    <td>Mata Pelajaran</td>
                                    <td>:</td>
                                    <td>PAI dan Budi Pekerti</td>
                                </tr>
                                <tr>
                                    <td>Hari</td>
                                    <td>:</td>
                                    <td>
                                        {{ page.props.rombel.jadwals[0].hari }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <el-scrollbar width="100vw">
                            <table class="my-8 w-full">
                                <thead>
                                    <tr>
                                        <th
                                            class="border border-black p-2"
                                            rowspan="2"
                                        >
                                            No
                                        </th>
                                        <th
                                            class="border border-black p-2"
                                            rowspan="2"
                                        >
                                            Materi
                                        </th>
                                        <th
                                            class="border border-black p-2"
                                            rowspan="2"
                                        >
                                            Alur Tujuan Pembelajaran
                                        </th>
                                        <th
                                            class="border border-black p-2"
                                            rowspan="2"
                                        >
                                            Konten Materi
                                        </th>
                                        <th
                                            class="border border-black p-2"
                                            rowspan="2"
                                        >
                                            Alokasi Waktu
                                        </th>
                                        <th
                                            v-for="m in syahrs"
                                            :key="m"
                                            class="border border-black p-2"
                                            :colspan="minggus(m)"
                                        >
                                            {{ m }}
                                        </th>
                                    </tr>
                                    <tr>
                                        <template
                                            v-for="m in syahrs"
                                            :key="m + 'a'"
                                        >
                                            <th
                                                class="border border-black p-2"
                                                v-for="w in minggus(m)"
                                                :key="w"
                                            >
                                                {{ w }}
                                            </th>
                                        </template>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template
                                        v-for="(atp, a) in page.props.atps"
                                        :key="atp.kode"
                                    >
                                        <tr
                                            v-for="(prosem, p) in atp.prosems"
                                            :key="prosem.id"
                                        >
                                            <td
                                                class="border border-black text-center"
                                                v-if="a + p == a"
                                                :rowspan="
                                                    a + p === a
                                                        ? atp.prosems.length
                                                        : ''
                                                "
                                            >
                                                {{ a + 1 }}
                                            </td>
                                            <td
                                                class="border border-black"
                                                v-if="a + p == a"
                                                :rowspan="
                                                    a + p === a
                                                        ? atp.prosems.length
                                                        : ''
                                                "
                                            >
                                                {{ atp.materi }}
                                            </td>
                                            <td
                                                class="border border-black p-2"
                                                v-if="a + p == a"
                                                :rowspan="
                                                    a + p === a
                                                        ? atp.prosems.length
                                                        : ''
                                                "
                                            >
                                                {{ atp.tps }}
                                            </td>
                                            <td
                                                class="border border-black p-2"
                                                v-if="a + p == a"
                                                :rowspan="
                                                    a + p === a
                                                        ? atp.prosems.length
                                                        : ''
                                                "
                                            >
                                                <span v-html="atp.konten" />
                                            </td>
                                            <td
                                                class="border border-black text-center"
                                                v-if="a + p == a"
                                                :rowspan="
                                                    a + p === a
                                                        ? atp.prosems.length
                                                        : ''
                                                "
                                            >
                                                {{ atp.aw }}
                                            </td>
                                            <template
                                                v-for="m in syahrs"
                                                :key="m + 'a'"
                                            >
                                                <td
                                                    class="border border-black p-2"
                                                    v-for="w in minggus(m)"
                                                    :key="w"
                                                >
                                                    <span
                                                        v-if="
                                                            prosem.atp_id ==
                                                                atp.id &&
                                                            prosem.minggu_ke ==
                                                                w &&
                                                            dayjs(
                                                                prosem.tanggal
                                                            ).format('MMMM') ==
                                                                m
                                                        "
                                                        >{{
                                                            dayjs(
                                                                prosem.tanggal
                                                            ).format("DD/MM")
                                                        }}</span
                                                    >
                                                    <!-- <span v-else>{{ prosem.minggu_ke }}</span> -->
                                                </td>
                                            </template>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </el-scrollbar>
                        <Ttd />
                    </div>
                </el-card>
            </el-col>
        </el-row>
    </DashLayout>
</template>
