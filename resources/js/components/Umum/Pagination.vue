<script setup>
defineProps({ total: Number });
const emit = defineEmits(["change"]);

const onChange = (e) => {
    emit("change", e);
};
</script>
<template>
    <el-pagination
        background
        layout="prev, pager, next"
        :total="total"
        @current-change="onChange"
    />
</template>
