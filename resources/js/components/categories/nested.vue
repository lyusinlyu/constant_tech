<template>
    <draggable class="dragArea" :move="onMove" tag="ul" :list="tasks" :group="{ name: 'g1' }">
        <li v-for="el in tasks" :key="el.id" :data-id="el.id">
            <span class="category-item" :style="{background: '#'+(Math.random()*0xFFFFFF<<0).toString(16)}"></span>
            <p>{{ el.name }}</p>
            <div v-if="el.all_children_categories && el.all_children_categories.length">
                <nested-draggable :move="onMove" :tasks="el.all_children_categories" />
            </div>
        </li>
    </draggable>
</template>
<script>
    import draggable from "vuedraggable";
    export default {
        props: {
            tasks: {
                required: true,
                type: Array
            }
        },
        data: function() {
            return {
                updateData: [],
                parentId: null,
                list: [],
            }
        },
        components: {
            draggable
        },
        name: "nested-draggable",
        methods: {
            onMove({ relatedContext, draggedContext }) {
                const relatedElement = relatedContext.element;
                const draggedElement = draggedContext.element;
                setTimeout(() => {
                    this.$store.commit("set_current_list", relatedContext['list']);
                }, 300)
            },
            update(data) {
                console.log('updateeeeeeeee nested', data);
                if (data.length) {
                    axios.post('/api/categories', data).then((response) => {
                        // this.categories = response.data;
                        // this.orderList();
                        // console.log(response)
                    }).catch(function (error) {
                        // console.log(error);
                    });
                }
            },
        }
    };
</script>
<style scoped>
.category-item {
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 5px;
}
</style>
