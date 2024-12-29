export const paginate = (data, perPage) => {
    const pages = []
    for (let i=0;i < data.length; i+= perPage) {
        pages.push(data.slice(i, i + perPage))
    }

    return pages
}

export default { paginate }