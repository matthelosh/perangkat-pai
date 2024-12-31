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
    Link,
    Color,
    BulletList,
    OrderedList,
    TextAlign,
    LineHeight,
    Indent,
    Blockquote,
    Image,
    Table,
    Iframe,
    HorizontalRule,
    Fullscreen,
    Print,
    SelectAll,
    CodeView,
    CodeBlock,
    Code,
} from "element-tiptap-vue3-fixed";
import CodeMirror from "codemirror";
import "codemirror/mode/xml/xml.js";
import "codemirror/mode/javascript/javascript.js";
import "codemirror/mode/css/css.js";
import "codemirror/addon/selection/active-line.js";
import "codemirror/addon/edit/closetag.js";
import { ElNotification } from "element-plus";
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
    Indent,
    Blockquote,
    LineHeight,
    Link.configure({ bubble: true }),
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
    Iframe,
    HorizontalRule,
    Print,
    SelectAll,
    Fullscreen,
    CodeView.configure({
        codemirror: CodeMirror,
        codemirrorOptions: {
            mode: "xml",
            theme: "default",
            lineNumbers: true,
            styleActiveLine: true,
            autoCloseTags: true,
            lineWrapping: true,
            tabSize: 2,
            line: true,
        },
    }),
    CodeBlock,
    Code,
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
