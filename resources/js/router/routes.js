import Contacts from '@/Pages/Contacts/Index.vue';
import Dashboard from '@/Pages/Dashboard.vue';

const routes = [
    {
        path: '/dashboard',
        name: 'dashboard',
        component: Dashboard,
    },
    {
        path: '/contacts',
        name: 'contacts',
        component: Contacts,
    },
];

export default routes;
