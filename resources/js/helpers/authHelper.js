import { usePage } from '@inertiajs/vue3';
const page = usePage()
export const hasRole = (role) => {
    return page.props.user.roles.map(role => role.name).includes(role)
}

export default {hasRole}