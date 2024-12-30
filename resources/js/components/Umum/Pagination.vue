<script setup>
defineProps({ total: Number, perPage: Number });
const emit = defineEmits(["change"]);

const onChange = (e) => {
    emit("change", e);
};
</script>
<template>
    <el-pagination
        background
        layout="total, prev, pager, next"
        :page-size="perPage"
        :total="total"
        @current-change="onChange"
    />
</template>
