<script setup>
import {
    Doc,
    Text,
    History,
    Paragraph,
    Dropcursor,
    FontFamily,
    Bold,
    Underline,
    Italic,
    Strike,
    Heading,
    Color,
    BulletList,
    OrderedList,
    TextAlign,
    Blockquote,
    Image,
    Table,
    HorizontalRule,
    SelectAll,
} from "element-tiptap-vue3-fixed";

import axios from "axios";
import { ref, watch } from "vue";
const extensions = [
    Doc,
    Text,
    History,
    Dropcursor,
    Paragraph.configure({ bubble: true }),
    Heading.configure({ bubble: true }),
    FontFamily,
    Color.configure({ bubble: true }),
    Underline.configure({ bubble: true }),
    Bold.configure({ bubble: true }),
    Italic.configure({ bubble: true }),
    TextAlign.configure({ bubble: true }),

    Blockquote,

    Strike.configure({ bubble: true }),
    BulletList,
    OrderedList,
    Image.configure({
        defaultWidth: 400,
        draggable: true,
        uploadRequest: async (file) => {
            let fd = new FormData();
            fd.append("image", file);
            return new Promise(async (resolve, reject) => {
                await axios.post(route("image.upload"), fd).then((res) => {
                    resolve(res.data.url);
                });
            });
        },
    }),
    Table,

    HorizontalRule,

    SelectAll,
];
const props = defineProps(["modelValue"]);
const emit = defineEmits(["update:modelValue"]);

const newContent = ref(props.modelValue || "");
watch(newContent, (newValue) => {
    emit("update:modelValue", newValue);
});
</script>

<template>
    <element-tiptap
        v-model:content="newContent"
        :extensions="extensions"
    ></element-tiptap>
</template>
