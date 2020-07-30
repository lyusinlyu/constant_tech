import Login from './components/auth/Login';
import Register from './components/auth/Register';
import Index from './components/categories/index';
import Create from './components/categories/create';
export const routes = [
    {
        path: '/login',
        name: 'login',
        component: Login,
        meta: {
            protected: false
        },
    },
    {
        path: '/register',
        name: 'register',
        component: Register,
        meta: {
            protected: false
        },
    },
    {
        path: '/',
        name: 'index',
        component: Index,
        meta: {
            protected: true
        },
    },
    {
        path: '/categories/create',
        name: 'create',
        component: Create,
        meta: {
            protected: true
        },
    },
    {
        path: '/categories/update/:id',
        name: 'update',
        component: Create,
        meta: {
            protected: true
        },
    },
    { path: '/*', redirect: { name: 'index' }}
];
