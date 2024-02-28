export const cssLink = (appEnv) => {
    return appEnv == 'local' ? 'https://perpai.test:5173/resources/css/app.css' : '/build/aseets/app.css'
}


export default {cssLink}