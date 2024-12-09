<script setup>
import { ref, computed } from 'vue'
import { usePage, Head, router } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue';
import { ElNotification } from 'element-plus';

const page = usePage()
const data = page.props.data

const onTapelChanged = (e, tapel) => {
    router.put(route('tapel.toggle', {id: tapel.id}), {status: e}, {
        onSuccess: page => {
            ElNotification({title: 'Info', message: page.props.flash.message, type: 'success'})
            router.reload()
        }, onError: errs => {
            Object.keys(errs).forEach(k => {
                setTimeout(() => {
                    ElNotification({title: 'Error', message: errs[k], type: 'error'})
                }, 500)
            })
        }
    })
}
const onSemesterChanged = (e, semester) => {
    router.put(route('semester.toggle', {id: semester.id}), {status: e}, {
        onSuccess: page => {
            ElNotification({title: 'Info', message: page.props.flash.message, type: 'success'})
            router.reload()
        }, onError: errs => {
            Object.keys(errs).forEach(k => {
                setTimeout(() => {
                    ElNotification({title: 'Error', message: errs[k], type: 'error'})
                }, 500)
            })
        }
    })
}

</script>

<template>
    <div>
    </div>
    <el-row :gutter="20">
        <el-col :span="8">
            <el-card>
                <template #header>
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-slate-600">Tahun Pelajaran </h3>

                        <el-button type="primary" size="small" circle>
                            <Icon icon="mdi-plus" class="text-lg" />
                        </el-button>
                    </div>
                </template>
                <div class="card-body">
                    <el-table :data="page.props.data.tapels">
                        <el-table-column label="#" type="index"></el-table-column>
                        <el-table-column label="Kode" prop="kode"></el-table-column>
                        <el-table-column label="Label" prop="label"></el-table-column>
                        <el-table-column label="Status">
                            <template #default="scope">
                                <!-- {{ scope.row.status }} -->
                                  <el-switch v-model="scope.row.status" :active-value="'active'" :inactive-value="'inactive'" @change="onTapelChanged($event,scope.row)"></el-switch>
                            </template>
                        </el-table-column>
                        <!-- <el-table-column label="tes" prop="status" /> -->
                    </el-table>
                </div>
            </el-card>
        </el-col>
        <el-col :span="8">
            <el-card>
                <template #header>
                    <h3 class="text-lg font-bold text-slate-600"> Semester </h3>
                </template>
                <div class="card-body">
                    <el-table :data="page.props.data.semesters">
                        <el-table-column label="#" type="index"></el-table-column>
                        <el-table-column label="Kode" prop="kode"></el-table-column>
                        <el-table-column label="Label" prop="label"></el-table-column>
                        <el-table-column label="Status">
                            <template #default="scope">
                                <!-- {{ scope.row.status }} -->
                                  <el-switch v-model="scope.row.status" :active-value="'active'" :inactive-value="'inactive'" @change="onSemesterChanged($event,scope.row)"></el-switch>
                            </template>
                        </el-table-column>
                    </el-table>

                </div>
            </el-card>
        </el-col>
    </el-row>
</template>