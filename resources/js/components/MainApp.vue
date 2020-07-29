<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card bg-light text-dark">
                    <div class="card-header">{{ title }}</div>
                    <div class="card-body">
                        <router-view></router-view>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'main-app',
        computed: {
            title() {
                return this.$store.state.title;
            },
        },
    }
</script>
