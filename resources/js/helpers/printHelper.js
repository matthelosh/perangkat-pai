import { usePage } from "@inertiajs/vue3"
const page = usePage()
export const cssFiles = () => {
    const appEnv = page.props.app_env
    const baseUrl = appEnv === 'local' ? import.meta.env.VITE_DEV_SERVER_URL || 'https://perangkat.test:5173' : ''
    return appEnv == 'local' ? [`${baseUrl}/resources/css/app.css`] : ['/build/assets/app.css', '/build/assets/app2.css']
}


export default {cssFiles}