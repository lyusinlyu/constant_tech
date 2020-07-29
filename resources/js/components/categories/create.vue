<template>
    <div class="create">
        <div class="card form-wrapper">
            <span class="category-item" :style="{background: '#'+(Math.random()*0xFFFFFF<<0).toString(16)}"></span>
            <div class="card-body form-body">
                <form @submit.prevent="save">
                    <div class="form-group row">
                        <input type="text" v-model="category.name" placeholder="Name" :class="['form-control', {'is-invalid' : formErrors.name}]" @keyup="formErrors.name = ''">
                        <span role="alert" class="invalid-feedback" v-if="formErrors.name">
                            <strong>{{formErrors.name}}</strong>
                        </span>
                    </div>
                    <div class="form-group row">
                        <select v-model="category.parent_id" class="form-control" :disabled="$route.params.id && !category.parent_id">
                            <option value="" disabled>Select parent Category</option>
                            <option v-for="category in categories" :value="category.id">{{ category.name }}</option>
                        </select>
                    </div>
                    <div class="form-group row">
                        <input type="submit" value="Save">
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "create",
        data() {
            return {
                categories: [],
                category: {},
                formErrors: {
                    name: '',
                },
            };
        },
        created() {
            //set title for the page
            this.$store.commit('set_title', this.$route.params.id ? 'Update the Category' : 'Create a Category');

            // Get all Categories
            this.$store.dispatch('getCategories', "")
                .then((response) => {
                    //getCategory if update
                    if (this.$route.params.id) {
                        let check = setInterval(() => {
                            if (this.categories && this.categories.length) {
                                this.setCategoryData(this.$route.params.id);
                                clearInterval(check);
                            }
                        }, 100);
                    }
                })
                .catch((error) => {});
        },
        computed: {
            categoriesData: {
                // getter
                get: function () {
                    return this.categories = this.$store.state.categories;
                },
                // setter
                set: function (newValue) {
                    return this.categories = newValue
                },
            },
        },
        methods: {
            setCategoryData(id) {
              this.category = this.categories.find((category) => {
                  return category.id == id;
              });
            },
            save() {
                this.formErrors.name = !this.category.name ? 'Category name is required' : '';
                if (this.formErrors.name) return;
                if (this.$route.params.id) {
                    this.$store.dispatch('updateCategories', [this.category])
                        .then((response) => {
                            this.$toast.success(response.data.message);
                            setTimeout(() => {
                                this.$router.push('index')
                            }, 1000)
                        })
                        .catch((error) => {
                            this.$toast.error('Something went wrong')
                        });
                } else {
                    axios.post('/api/categories', this.category)
                        .then((response) => {
                            this.$toast.success(response.data.message);
                            setTimeout(() => {
                                this.$router.push('index')
                            }, 1000)
                        }).catch(function (error) {
                            this.$toast.error('Something went wrong')
                    });
                }
            }
        }
    }
</script>


<style>
    .category-item {
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 5px;
    }



    .create {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 50vh;
    }
    .form-wrapper {
        flex-direction: row;
        min-width: 480px;
        max-width: 500px;
        border: none;
        box-shadow: 1px 2px 8px #ccc;
        border-radius: 15px;
    }

    .form-body {
        padding: 30px 30px 20px;
    }

    .form-body input {
        border: none;
        border-bottom: 1px solid #eee;
        border-radius: 0;
    }

    .form-body .form-control:focus {
        box-shadow: unset;
    }

    .form-body input[type=submit] {
        background: #68a3d6;
        color: #fff;
        border-radius: 3px;
        padding: 3px 20px;
        border: 1px solid #68a3d6;
        margin-top: 15px;
    }

    .form-body input[type=submit]:hover {
        background: #588ab5;
        border-color: #588ab5;
        cursor: pointer;
    }

    .form-footer span {
        font-size: 12px;
        color: #555;
    }

    .form-footer a {
        color: #588ab5;
        font-weight: 600;
        cursor: pointer;
        font-style: italic;
    }


</style>
