import Contacts from '@/Pages/Contacts/Index.vue';
import Dashboard from '@/Pages/Dashboard.vue';
import AdvancedSearch from '@/Pages/AdvancedSearch.vue';
import UserProfile from '@/Pages/Users/Profile.vue';
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
    {
        path: '/advanced-search',
        name: 'advanced-search',
        component: AdvancedSearch,
    },
    //dinamic route with id param to users profile
    {
        path: '/users/:id',
        name: 'users.profile',
        component: UserProfile,
    },
];

export default routes;
