<template>
    <draggable class="dragArea" :move="onMove" tag="ul" :list="tasks" :group="{ name: 'g1' }">
            <li v-for="el in tasks" :key="el.id" :data-id="el.id">
                <span class="category-item" :style="{background: '#'+(Math.random()*0xFFFFFF<<0).toString(16)}"></span>
                <span>{{ el.name }}</span>
                <router-link :to="{ path: `categories/update/${el.id}`}"><i class="fas fa-pen-square pointer"></i></router-link>
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
                //
            }
        },
        components: {
            draggable
        },
        name: "nested-draggable",
        methods: {
            // onMove event handler: get categories list to update
            onMove({ relatedContext, draggedContext }) {
                if (!draggedContext.element.parent_id && relatedContext.element.parent_id) {
                    return false
                }
                setTimeout(() => {
                    this.$store.commit("set_current_list", relatedContext['list']);
                }, 300)
            },
        }
    };
</script>
<style scoped>
    .dragArea {
        min-height: 50px;
    }
.category-item {
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 5px;
}
</style>
