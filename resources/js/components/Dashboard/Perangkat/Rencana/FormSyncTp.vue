<script setup>
const props = defineProps({ open: Boolean, selectedFase: String });
const emit = defineEmits(["close"]);
import { Icon } from "@iconify/vue";
import axios from "axios";
import { onBeforeMount, ref } from "vue";
const loading = ref(false);
const show = ref(false);
const localTps = ref([]);
const raporTps = ref([]);

const list = async () => {
    try {
        const { data } = await axios.get(
            route("rapor.tp.get", { _query: { fase: props.selectedFase } })
        );

        localTps.value = data.localTps;
        raporTps.value = data.raporTps;
    } catch (err) {
        console.log(err);
    }
};

onBeforeMount(async () => {
    show.value = props.open;
    await list();
});
</script>

<template>
    <el-dialog
        v-model="show"
        fullscreen
        @close="emit('close')"
        :show-close="false"
    >
        <template #header="{ close }">
            <div class="flex items-center justify-between">
                <h3>Sinkron TP Rapor</h3>
                <div class="items flex items-center gap-1">
                    <el-button-group>
                        <el-button>
                            <Icon icon="mdi:upload" />
                            Sinkron
                        </el-button>
                        <el-button>
                            <Icon icon="mdi:download" />
                            Impor
                        </el-button>
                    </el-button-group>
                    <el-button
                        icon
                        @click="close"
                        :native-type="null"
                        type="danger"
                    >
                        <Icon icon="mdi:close" />
                    </el-button>
                </div>
            </div>
        </template>
        <template #default>
            <el-row :gutter="10">
                <el-col :span="12">
                    <h3>TP Lokal</h3>
                    <el-table :data="localTps" max-height="88vh">
                        <el-table-column
                            label="No"
                            type="index"
                        ></el-table-column>
                        <el-table-column
                            label="Elemen"
                            prop="elemen.label"
                            width="150px"
                        ></el-table-column>
                        <el-table-column
                            label="Teks"
                            prop="teks"
                        ></el-table-column>
                    </el-table>
                </el-col>
                <el-col :span="12">
                    <h3>TP Rapor</h3>
                    <el-table :data="raporTps" max-height="88vh">
                        <el-table-column
                            label="No"
                            type="index"
                        ></el-table-column>
                        <el-table-column
                            label="Elemen"
                            prop="elemen"
                            width="150px"
                        ></el-table-column>
                        <el-table-column
                            label="Teks"
                            prop="teks"
                        ></el-table-column>
                    </el-table>
                </el-col>
            </el-row>
        </template>
    </el-dialog>
</template>
