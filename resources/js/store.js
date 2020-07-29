export default {
    state: {
        title: '',
        categories: [],
        currentList: [],
        currentParentId: null,
    },

    mutations: {
        set_title(state, title) {
            state.title = title;
            console.log(state.title);
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
    },

    actions: {
        getCategories(context, nested) {
            axios.get(`/api/categories?all=${nested}`).then((response) => {
                context.commit('set_categories', response.data);
            }).catch(function (error) {
                console.log(error);
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