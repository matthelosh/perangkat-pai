<script setup>
import { ref, computed, onMounted, reactive } from 'vue';
import { router } from '@inertiajs/vue3'
import { ElNotification } from 'element-plus';

const user = ref({})
const loading = ref(false)
const rules = reactive({
    name: [
        { required: true, message: 'Masukkan username', trigger: 'blur' }
    ],
    password: [
        { required: true, message: 'Masukkan Password', trigger: 'blur' }
    ],
})

const onSubmit = async() => {
    
    await router.post(route('auth.login'), user.value, {
        onBefore: (page) => {
            loading.value = true
        },
        onError: (err) => {
            Object.keys(err).forEach(k => {
                setTimeout(() => {
                    ElNotification({title: 'Error', message: err[k]})
                }, 500)
            })
        },
        onFinish: (page) => {
            loading.value = false
        }
    })
}

onMounted(() => {
// const formLogin = document.querySelector("#form-login")
// formLogin.addEventListener("keyup", onSubmit, {once: true})
})
</script>

<template>
    <div class="wrapper w-screen h-screen bg-gray-300 flex items-center justify-center">
        <el-form @keyup.enter="onSubmit" :rules="rules" id="form-login" v-model="user" class="bg-slate-50 p-8 pb-4 rounded-lg shadow hover:shadow-lg transition-all" @submit="onSubmit">
            <el-form-item label="Username">
                <el-input placeholder="Masukkan username" v-model="user.name" required></el-input>
            </el-form-item>
            <el-form-item label="Password">
                <el-input type="password" v-model="user.password" show-password placeholder="Masukkan Password" required></el-input>
            </el-form-item>
            <el-form-item>
                <el-button type="primary" :loading="loading" @click="onSubmit">Masuk</el-button>
            </el-form-item>
        </el-form>
    </div>
</template>