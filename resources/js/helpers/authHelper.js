import { usePage } from '@inertiajs/vue3';
const page = usePage()
export const hasRole = (role) => {
    return page.props.roles.map(role => role.name).includes(role)
}

export const isAdmin = () => {
    return hasRole('admin')
}

export default {hasRole, isAdmin}