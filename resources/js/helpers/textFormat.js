export const capitalize = (text) => {
    let res = ''
    let words = text.split(" ")
    return words.reduce((a,w) => a+" "+(w[0].toUpperCase()+w.substring(1).toLowerCase()), '')
}

export default { capitalize }
