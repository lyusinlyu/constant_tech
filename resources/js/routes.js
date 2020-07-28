import Index from './components/categories/index';
import Create from './components/categories/create';
export const routes = [
    {
        path: '/',
        name: 'index',
        component: Index
    },
    {
        path: '/categories/create',
        name: 'create',
        component: Create
    },
    { path: '/*', redirect: { name: 'index' }}
];
