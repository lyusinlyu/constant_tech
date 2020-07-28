<template>
    <div class="categories-container">
        <router-link :to="{ name: 'create'}"><i class="fas fa-plus-circle pointer"></i></router-link>
        <div v-if="categories.length">
            <draggable class="list-group" tag="ul" v-model="categories" v-bind="dragOptions" :move="onMove" @start="isDragging=true" @end="isDragging=false">
                <transition-group type="transition" :name="'flip-list'">
                    <li class="list-group-item mb-10" v-for="element in categories" :key="element.order">
                        <span class="category-item" :style="{background: '#'+(Math.random()*0xFFFFFF<<0).toString(16)}"></span>
                        <i :class="element.fixed? 'fa fa-anchor' : 'glyphicon glyphicon-pushpin'" @click=" element.fixed=! element.fixed" aria-hidden="true"></i>
                        {{element.name}}
                        <span class="badge">{{element.order}}</span>
                    </li>
                </transition-group>
            </draggable>
        </div>
        <div v-else>There is no existing Category yet.</div>
    </div>
</template>
<script>
    import draggable from 'vuedraggable';
    export default {
        name: 'index',
        components: {
            draggable,
        },
        data: function () {
            return {
                categories: []
            }
        },
        mounted() {
            this.getCategories();
            console.log('mounted');
        },
        methods: {
            getCategories() {
                axios.get('/api/categories').then((response) => {
                    this.categories = response.data;
                    console.log(response)
                }).catch(function (error) {
                    console.log(error);
                });
            },
            deleteEntry(id, index) {
                if (confirm("Do you really want to delete it?")) {
                    var app = this;
                    axios.delete('/api/v1/companies/' + id)
                        .then(function (resp) {
                            app.companies.splice(index, 1);
                        })
                        .catch(function (resp) {
                            alert("Could not delete company");
                        });
                }
            }
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
