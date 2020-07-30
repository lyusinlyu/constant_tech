import {fetchBoards as apiFetchBoards} from './helpers/api';

export default {
    state: {
        isLoggedIn: !!localStorage.getItem('token'),
        currentUser: null,
        title: '',
        categories: [],
        categoriesNested: [],
        currentList: [],
        currentParentId: null,
    },

    mutations: {
        login_success(state, payload) {
            console.log('payload', payload);
            localStorage.setItem( "token", payload.access_token );
            state.isLoggedIn = true;
        },
        logout(state) {
            state.isLoggedIn = false;
            localStorage.removeItem('token');
            state.currentUser = null;
        },
        set_user(state, user) {
            state.currentUser = user;
        },
        set_title(state, title) {
            state.title = title;
        },
        set_current_list(state, list) {
            state.currentList = list;
        },
        set_current_parent_id(state, id) {
            state.currentParentId = id;
        },
        set_categories(state, data) {
            state.categories = data;
        },
        set_categories_nested(state, data) {
            state.categoriesNested = data;
        },
    },

    actions: {
        getCategories(context, params) {
            return new Promise((res, rej) => {
                axios.get(`/api/categories?all=${params.nested}&id=${params.id}`)
                    .then((response) => {
                        if (params.nested) {
                            context.commit('set_categories_nested', response.data);
                        } else {
                            context.commit('set_categories', response.data);
                        }
                        res(response.data);
                    }).catch(function (error) {
                    rej(error);
                });
            });
        },

        updateCategories(context, data) {
            return new Promise((res, rej) => {
                axios.put('/api/categories', data)
                    .then((response) => {
                    res(response.data)
                    }).catch(function (error) {
                        rej(error);
                    });
            });
        }
    }
}
