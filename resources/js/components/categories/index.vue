<template>
    <div class="categories-container">
        <router-link :to="{ name: 'create'}"><i class="fas fa-plus-circle pointer mb-10"></i></router-link>
        <div v-if="categories.length">
            <draggable class="list-group" tag="ul" v-model="categories" v-bind="dragOptions" :move="onMove"
                       @start="isDragging=true" @end="isDragging=false">
                    <nested-draggable :tasks="categories" />
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
                categories: [],
                updateData: [],
                dragOptions: {group: { name:'categories', pull:'clone', put:'true'}}
            }
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
        },
        created() {
            //set title for the page
            this.$store.commit('set_title', 'Categories');

            // Get all Categories
            this.$store.dispatch('getCategories', true);
        },
        methods: {

            // onMove event handler: get categories list to update
            onMove({ relatedContext, draggedContext }) {
                setTimeout(() => {
                    this.$store.commit("set_current_list", relatedContext['list']);
                }, 300)
            },

            // Find parentId for categories list
            findParentCategoryId() {
                if (!this.currentList[0].parent_id) return null;
                let parentId, id = this.currentList[0].id;
                for (let i = 0; i < this.categories.length; i++) {
                    if (this.categories[i].all_children_categories && this.categories[i].all_children_categories.length) {
                        parentId = this.searchCategoryParentId(this.categories[i].all_children_categories, this.categories[i].id, id);
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
                        pId = this.searchCategoryParent(arr[i].all_children_categories, arr[i].id, id);
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
                    console.log(error);
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
