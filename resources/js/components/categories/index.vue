<template>
    <div class="categories-container">
        <div class="row justify-content-between">
            <div class="col-sm-5">
                <router-link :to="{ name: 'create'}"><i class="fas fa-plus-circle pointer mb-10"></i></router-link>
            </div>
            <div class="col-sm-6">
                <select v-model="selectedCategoryId" class="form-control">
                    <option value="">All categories</option>
                    <option v-for="category in categories" :value="category.id">{{ category.name }}</option>
                </select>
            </div>
        </div>
        <div v-if="categoriesNested.length">
            <draggable class="list-group" tag="ul" v-model="categoriesNested" v-bind="dragOptions"
                       @start="isDragging=true" @end="isDragging=false">
                    <nested-draggable :tasks="categoriesNested" />
            </draggable>
        </div>
        <div v-else>There is no existing Category yet.</div>
    </div>
</template>
<script>
    import draggable from 'vuedraggable';
    import nestedDraggable from './nested'

    export default {
        name: 'index',
        components: {
            draggable,
            nestedDraggable,
        },
        data: function () {
            return {
                updateData: [],
                dragOptions: {group: { name:'categories', pull:'clone', put:'true'}},
                search: '',
                selectedCategoryId: null,
            }
        },
        computed: {
            categories() {
                return this.$store.state.categories;
            },
            categoriesNested: {
                // getter
                get: function () {
                    return this.$store.state.categoriesNested;
                },
                // setter
                set: function (newValue) {
                    return newValue
                },
            },
            currentList() {
                return this.$store.state.currentList;
            }
        },
        watch: {
            currentList: function (val) {
                if (val && val.length) {
                    this.setupUpdateData();
                }
            },
            selectedCategoryId(val) {
                // Get selected Category with children
                this.$store.dispatch('getCategories', {nested: true, id: val ? this.selectedCategoryId : ""})
                    .then((response) => {
                        this.categoriesNested = response
                    })
                    .catch((error) => {});
            }
        },
        created() {
            //set title for the page
            this.$store.commit('set_title', 'Categories');

            // Get all Categories with children
            this.$store.dispatch('getCategories', {nested: true, id: ""});

            // Get all Categories
            this.$store.dispatch('getCategories', {nested: "", id: ""});
        },
        methods: {
            // Find parentId for categories list
            findParentCategoryId() {
                if (!this.currentList[0].parent_id) return null;
                let parentId, id = this.currentList[0].id;
                for (let i = 0; i < this.categoriesNested.length; i++) {
                    if (this.categoriesNested[i].all_children_categories && this.categoriesNested[i].all_children_categories.length) {
                        parentId = this.searchCategoryParentId(this.categoriesNested[i].all_children_categories, this.categoriesNested[i].id, id);
                        if (parentId) return parentId;
                    }
                }
            },
            searchCategoryParentId(arr, parentId, id) {
                let pId;
                for (let i = 0; i < arr.length; i++) {
                    if (arr[i].id == id) {
                        return parentId;
                    } else if (arr[i].all_children_categories && arr[i].all_children_categories.length) {
                        pId = this.searchCategoryParentId(arr[i].all_children_categories, arr[i].id, id);
                    }
                }
                return pId;
            },

            // Setup order and parentId properties based on position in the categories tree.
            setupUpdateData() {
                let parentId = this.findParentCategoryId();
                this.updateData = this.currentList.map(function(item, index) {
                    return {
                        id: item.id,
                        name: item.name,
                        order: index,
                        parent_id: parentId
                    };
                });
                this.update(this.updateData);
            },

            //update categories list
            update(data) {
                if (!data.length) return;
                this.$store.dispatch('updateCategories', data)
                    .then((response) => {
                        this.$toast.success(response.message)
                    })
                .catch((error) => {
                    this.$toast.error('Something went wrong')
                });
            },
        }
    }
</script>
<style scoped>
    .dragArea {
        min-height: 50px;
        /*outline: 1px dashed;*/
    }
    .category-item {
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 5px;
    }
    .list-group-item {
        border: 0px solid transparent;
        box-shadow: 0px 0px 10px #ddd;
    }
</style>
