<template>
    <div class="">
        <navbar></navbar>
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
    import Navbar from './Navbar';
    export default {
        name: 'main-app',
        components: {
            Navbar
        },
        computed: {
            title() {
                return this.$store.state.title;
            },
        },
    }
</script>
