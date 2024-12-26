<script setup>
import { ref, computed, onMounted, reactive } from "vue";
import { router, Head, usePage, Link } from "@inertiajs/vue3";
import { ElNotification } from "element-plus";
import { Icon } from "@iconify/vue";
const page = usePage();
const user = ref({});
const loading = ref(false);
const rules = reactive({
    name: [{ required: true, message: "Masukkan username", trigger: "blur" }],
    password: [
        { required: true, message: "Masukkan Password", trigger: "blur" },
    ],
});

const onSubmit = async () => {
    await router.post(route("auth.login"), user.value, {
        onBefore: (page) => {
            loading.value = true;
        },
        onError: (err) => {
            Object.keys(err).forEach((k) => {
                setTimeout(() => {
                    ElNotification({
                        title: "Error",
                        message: err[k],
                        type: "error",
                    });
                }, 500);
            });
        },
        onFinish: (page) => {
            loading.value = false;
        },
    });
};

onMounted(() => {
    // const formLogin = document.querySelector("#form-login")
    // formLogin.addEventListener("keyup", onSubmit, {once: true})
});
</script>

<template>
    <div
        class="relative wrapper w-screen h-screen bg-gradient-to-br from-orange-200 via-lime-200 to-sky-200 flex items-center justify-center"
    >
        <Head title="Silahkan Masuk | Perangkat Ajar PAI" />
        <Link
            :href="appRoute('home')"
            class="absolute z-20 top-4 right-4 text-sky-600 hover:text-sky-800"
            v-if="page.props.auth"
            >Dashboard</Link
        >
        <img
            src="/img/logo_kkg.png"
            alt="Logo KKG"
            class="absolute top-20 sm:top-52 h-[150px] sm:h-[200px]"
        />
        <el-form
            @keyup.enter="onSubmit"
            :rules="rules"
            id="form-login"
            v-model="user"
            label-position="top"
            class="bg-slate-50 p-4 w-[325px] sm:w-[480px] rounded-lg shadow hover:shadow-lg transition-all"
            @submit="onSubmit"
        >
            <el-form-item label="Username">
                <el-input
                    placeholder="Masukkan username"
                    v-model="user.name"
                    required
                    size="large"
                ></el-input>
            </el-form-item>
            <el-form-item label="Password">
                <el-input
                    type="password"
                    v-model="user.password"
                    show-password
                    placeholder="Masukkan Password"
                    size="large"
                    required
                ></el-input>
            </el-form-item>
            <el-button @click="onSubmit" :disabled="loading" type="primary">
                <Icon v-if="loading" icon="mdi:loading" class="animate-spin" />
                Masuk
            </el-button>
        </el-form>
    </div>
</template>
