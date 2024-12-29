<script setup>
import { computed, ref } from "vue";
import { router, usePage, Head } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";

import Layout from "@/layouts/DashboardLayout.vue";
const page = usePage();

const loading = ref(false);

const daftarHadir = (item) => {
    let win = window.open("", "_blank", "width=1200,height=900");
    let tr = "";
    page.props.gurus.forEach((guru, g) => {
        tr += `<tr>
                <td style="text-align:center;">${g + 1}</td>
                <td>${guru.nama}</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>`;
    });

    for (let i = 0; i < 6; i++) {
        tr += `<tr>
            <td style="text-align:center;">${
                i + (page.props.gurus.length + 1)
            }</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
        </tr>`;
    }

    let html = `<!doctype html>
            <html>
                <head>
                    <title>Daftar Hadir ${item.label}</title>  
                    <style>
                        th,td {
                            padding:20px;
                        }
                    </style>  
                </head>
                <body>
                    <h3 style="text-align:center;">DAFTAR HADIR RAPAT</h3>   
                    <h3 style="text-align:center;">KKG PAI KECAMATAN WAGIR</h3> 
                    
                    <p>Tanggal: ${new Date().toLocaleDateString({
                        date: "numeric",
                        month: "long",
                        year: "numeric",
                    })}</p>
                    <table border style="border-collapse: collapse;">
                        <thead>
                            <tr>
                                <th>No</th>    
                                <th>Nama</th>    
                                <th>Lembaga</th>    
                                <th>Jabatan</th>    
                                <th>Tanda Tangan</th>    
                            </tr>    
                        </thead>    
                        <tbody>
                            ${tr}
                        </tbody>
                    </table> 

                    <div style="display:flex; justify-content: flex-end; margin-top: 60px;">
                        <div>
                            <p>Wagir, ${new Date().toLocaleDateString()}</p>
                            <p>Ketua KKG PAI Kec. Wagir</p>
                            <p style="margin-top: 60px;">Saiful Arif, S.Ag., M. Pd.</p>
                            </div>
                    </div>
                </body>
            </html>
    `;
    win.document.write(html);

    setTimeout(() => {
        win.print();
    }, 1000);
};
</script>

<template>
    <div title="Kegiatan KKG">
        <Head title="Kegiatan KKG" />

        <div class="content">
            <div
                class="toolbar h-12 bg-slate-100 flex items-center justify-between p-2"
            >
                <h3>Agenda Kegiatan</h3>
                <div class="flex items-center gap-2">
                    <el-button
                        :native-type="null"
                        :disabled="!page.props.roles.includes('admin')"
                    >
                        <Icon icon="mdi-plus" />
                    </el-button>
                </div>
            </div>
            <div class="body">
                <el-table :data="page.props.kegiatans">
                    <el-table-column
                        prop="mulai"
                        label="Tanggal"
                    ></el-table-column>
                    <el-table-column
                        prop="label"
                        label="Nama Kegiatan"
                    ></el-table-column>
                    <el-table-column label="Opsi">
                        <template #default="scope">
                            <el-button
                                :native-type="null"
                                @click="daftarHadir(scope.row)"
                            >
                                <Icon icon="mdi:list-box" />
                            </el-button>
                        </template>
                    </el-table-column>
                </el-table>
            </div>
            <!-- {{ page.props.roles }} -->
        </div>
    </div>
</template>
