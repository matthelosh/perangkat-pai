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
    const date = dayjs(tanggal)
    const firstDayOfMonth = date.startOf('month')
    const firstWeekday = firstDayOfMonth.day()
    const dayOfMonth = date.date()

    // console.log('Date calculation for:', tanggal)
    // console.log('First day of month:', firstDayOfMonth.format('YYYY-MM-DD'))
    // console.log('First weekday:', firstWeekday)
    // console.log('Day of month:', dayOfMonth)

    // Adjusted calculation to properly handle first week
    const adjustedDay = dayOfMonth + firstWeekday - 1
    const weekNum = Math.ceil(adjustedDay / 7)

    // console.log('Calculated week number:', weekNum)
    return weekNum
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

export const unefektif = (month, day, semester) => {
    // console.log('Processing unefektif for month:', month, 'day:', day)
    let events = [];
    page.props.kaldiks.forEach(kaldik => {
        // console.log('Processing kaldik:', kaldik.label)
        // console.log('Start date:', kaldik.mulai, 'End date:', kaldik.selesai)
        let rentang = dayjs(kaldik.selesai).diff(dayjs(kaldik.mulai), 'day')
        // console.log('Date range:', rentang, 'days')
        if ( rentang < 1 ) {
            if ((haris.lastIndexOf(day)) == dayjs(kaldik.mulai).get('day') && (bulans.lastIndexOf(month) == dayjs(kaldik.mulai).get('month'))) {
                events.push(kaldik)
            } else {
            }

        } else {
                for (let h = 0; h < rentang+1; h++) {
                    let currentDate = dayjs(kaldik.mulai).add(h, 'day')
                    let hari = currentDate.get('day')
                    let bulan = currentDate.get('month')
                    //console.log(page.props.tapel.label.split("/")[0] == currentDate.format("YYYY"))
                    const tahun = semester && (semester == 1) ? page.props.tapel.label.split("/")[0] : page.props.tapel.label.split("/")[1]
                    // console.log(tahun == currentDate.format(""))

                    // console.log('Checking date:', currentDate.format('YYYY-MM-DD'))
                    // console.log('Comparing day:', hari, 'with', haris.lastIndexOf(day), hari == haris.lastIndexOf(day))
                    // console.log('Comparing month:', bulan, 'with', bulans.lastIndexOf(month))
                    if (hari == haris.lastIndexOf(day) && bulan == bulans.lastIndexOf(month) && semester && (currentDate.format("YYYY") == tahun)) {
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
    return weekPerSemester(months, day, semester) - sumUnEfektif(months, day, semester)
}

export const sumUnEfektif = (months, day, semester) => {
    let total = 0;
    for( let m of months) {
        total += unefektif(m, day, semester).length
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
