import dayjs from 'dayjs'
import 'dayjs/locale/id'
import localeData from 'dayjs/plugin/localeData'
import axios from 'axios'

dayjs.extend(localeData)
dayjs.locale("id")
dayjs().localeData()

import { usePage } from '@inertiajs/vue3'
const page = usePage()

const bulans = dayjs.months()
const haris = dayjs.weekdays()

export const tes = () => page.props.kaldiks
export const capitalize = (s) => s && s.length > 0 && s[0].toUpperCase() + s.slice(1);
export const uppercase = (s) => s.toUpperCase();
export const weeksPerMonth = (month, hari, semester) => {
    let monthNumber = bulans.lastIndexOf(month)
    let year = semester == '1' ? page.props.tapel.label.split("/")[0] : page.props.tapel.label.split("/")[1]
    let date = dayjs(new Date(year+'-'+ (monthNumber+1)))
    let weekday = haris.lastIndexOf(hari)
    let dif = (7 + (weekday - date.day())) % 7 + 1
    let totalWeeks = Math.floor((date.daysInMonth() - dif) / 7) +1
   
    return totalWeeks
    
}



export const weekOfMonth = (tanggal) => {
    const month = dayjs(tanggal).format('MMMM')
    const hari = dayjs(tanggal).format('dddd')
    const semester = dayjs(tanggal).get('month') >= 6 ? '1' : '2'
    const weekdays  = weeksPerMonth(month, hari, semester)
    let days = dayjs(tanggal).daysInMonth()
    let date = dayjs(tanggal)
    let i = 0;
    for (let d = parseInt(date.format('D')); d < days; d +=7) {
        i++
    }
    return weekdays === 5 ? weekdays - i : (weekdays+1)-i
}

export const allUnefektif = (months, day) => {
    let dates = []
    for(let m of months) {
        if (unefektif(m, day).length > 0 ) {
            dates.push(unefektif(m, day).map(d => d.mulai))
        } 
    }
    return dates
}

export const unefektif = (month, day) => {
    console.log(month, day)
    let events = [];
     page.props.kaldiks.forEach(kaldik => {
        // let rentang = dayjs(kaldik.selesai).date() - dayjs(kaldik.mulai).date()
        let rentang = dayjs(kaldik.selesai).diff(dayjs(kaldik.mulai), 'day')
        if ( rentang < 1 ) {
            if ((haris.lastIndexOf(day)) == dayjs(kaldik.mulai).get('day') && (bulans.lastIndexOf(month) == dayjs(kaldik.mulai).get('month'))) {
                events.push(kaldik)
            } else {
            }
            
        } else {
                for (let h = 0; h < rentang+1; h++) {
                    let hari = dayjs(kaldik.mulai).add(h, 'day').get('day')
                    let bulan = dayjs(kaldik.mulai).add(h, 'day').get('month')
                    if (hari == haris.lastIndexOf(day) && bulan == bulans.lastIndexOf(month)) {
                        events.push({
                            bulan: bulan,
                            hari: hari,
                            label: kaldik.label,
                            deskripsi: kaldik.deskripsi,
                            mulai: dayjs(kaldik.mulai).add(h, 'day').format('YYYY-MM-DD'),
                            selesai: dayjs(kaldik.mulai).add(h, 'day').format('YYYY-MM-DD'),
                            warna: kaldik.warna
                        })
                    }
                }
        } 
    })
    return events
}

export const effectiveOfYear = (hari) => {
    let total = 0;
    for (let s=1; s < 2;s++) {
        total + sumEfektif(bulans, hari, s)
    }

    return total
}

export const sumEfektif = (months, day, semester) => {
    return weekPerSemester(months, day, semester) - sumUnEfektif(months, day)
}

export const sumUnEfektif = (months, day) => {
    let total = 0;
    for( let m of months) {
        total += unefektif(m, day).length
    }

    return total
}

export const weekPerSemester = (months, day) => {
    let total = 0;
    for( let m of months) {
        total += weeksPerMonth(m, day)
    }

    return total
}