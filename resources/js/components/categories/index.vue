<template>
    <div class="categories-container">
        <router-link :to="{ name: 'create'}"><i class="fas fa-plus-circle pointer mb-10"></i></router-link>
        <div v-if="categories.length">
            <draggable class="list-group" tag="ul" v-model="categories" v-bind="dragOptions" :move="onMove"
                       @start="isDragging=true" @end="isDragging=false">
                <transition-group type="transition" :name="'flip-list'">
                    <li class="list-group-item mb-10" v-for="element in categories" :key="element.id" :data-id="element.id">
                        <span class="category-item" :style="{background: '#'+(Math.random()*0xFFFFFF<<0).toString(16)}"></span>
                        <i :class="element.fixed? 'fa fa-anchor' : 'glyphicon glyphicon-pushpin'" @click=" element.fixed=! element.fixed" aria-hidden="true"></i>
                        {{element.name}}
                        <div v-if="element.all_children_categories && element.all_children_categories.length">
                            <nested-draggable :tasks="element.all_children_categories" />
                        </div>
                    </li>
                </transition-group>
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
                let arr = parentId == null ? this.currentList.slice(0).reverse() : this.currentList;
                this.updateData = arr.map(function(item, index, array) {
                    return {
                        id: item.id,
                        order: index,
                        parent_id: parentId
                    };
                });
                this.update(this.updateData);
            },
            //update categories list
            update(data) {
                if (!data.length) return;
                this.$store.dispatch('updateCategories', data).then((response) => {
                    console.log('success')
                })
                .catch((error) => {});
            },
        }
    }
</script>
<style scoped>
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
