import * as _ from 'lodash-es'

export const imgUrl = (url) => {
    if (url.includes('=')) {
        let splited = url.split('=')
        return `https://drive.google.com/uc?export=view&id=${splited[splited.length-1]}`
    } else {
        return url[0] == '/' ? url : '/'+url
    }
}

export const namaSekolah = (nama) => {
    const namas = nama.split(" ")
    // const negeri = namas[1][0] + namas[1].substring(1).toLowerCase()
    // const desa = namas[3][0] + namas[3].substring(1).toLowerCase()
    // return namas[0] +" "+negeri+" "+namas[2]+" "+desa
    let res =''
    for(let i=0; i < namas.length; i++) {
        res += i == 0 ? namas[i].toUpperCase() : " "+(namas[i][0].toUpperCase() + namas[i].substring(1).toLowerCase())
    }

    return res
}

export const paginate = (datas, currentPage) => {
    let pages = _.chunk(datas, 10)

    return { current: pages[currentPage-1], pageCount: pages.length, dataLength: datas.length}
}

export const tanggal = (date) => {
    const tanggal = new Date(date)
    const bulans = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    return tanggal.getDate()+' '+bulans[tanggal.getMonth()]+' '+tanggal.getFullYear()
}

export const terbilang = (num) => {
    let temp = "";
    const huruf = ["", "Satu", "Dua", "Tiga", "Empat","Lima","Enam", "Tujuh","Delapan","Sembilan","Sepuluh","Sebelas"];
    if (num < 12) {
        temp = " "+huruf[num]
    } else if(num < 20) {
        temp = terbilang(num - 10) + " Belas"
    } else if (num < 100) {
        temp = terbilang(Math.floor(num/10)) + " Puluh" + terbilang(num % 10);
    } else if (num < 200) {
        temp = " Seratus" + terbilang(num - 100);
    } else if (num < 1000) {
        temp = terbilang(Math.floor(num / 100)) + " Ratus" + terbilang(num % 100)
    } else if (num < 2000) {
        temp = " Seribu" + terbilang(num - 1000);
    } else if(num < 1_000_000) {
        temp = terbilang(Math.floor(num/1000)) + " Ribu" + terbilang(num % 1000);
    } else if (num < 1_000_000_000) {
        temp = terbilang(Math.floor(num / 1_000_000)) + " Juta" + terbilang(num % 1_000_000)
    } else if (num < 1_000_000_000_000) {
        temp = terbilang(Math.floor(num / 1_000_000_000)) + " Milyar" + terbilang(Math.fmod(num, 1_000_000_000) )
    } else if (num < 1_000_000_000_000_000) {
        temp = terbilang(Math.floor(num / 1_000_000_000_000)) + " Trilyun" + terbilang(Math.fmod(num, 1_000_000_000_000))
    }

    return temp
}

export default {imgUrl}