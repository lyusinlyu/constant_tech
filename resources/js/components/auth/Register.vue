<template>
    <div class="login">
        <div class="card form-wrapper">
            <span class="category-item" :style="{background: '#'+(Math.random()*0xFFFFFF<<0).toString(16)}"></span>
            <div class="card-body form-body">
                <form @submit.prevent="registration">
                    <div class="form-group row">
                        <input type="text" v-model="form.name" placeholder="Name" :class="['form-control', {'is-invalid' : formErrors.name}]" @keyup="formErrors.name = ''">
                        <span role="alert" class="invalid-feedback" v-if="formErrors.name">
                            <strong>{{formErrors.name}}</strong>
                        </span>
                    </div>
                    <div class="form-group row">
                        <input type="text" v-model="form.email" placeholder="Email Address" :class="['form-control', {'is-invalid' : formErrors.email}]" @keyup="formErrors.email = ''">
                        <span role="alert" class="invalid-feedback" v-if="formErrors.email">
                            <strong>{{formErrors.email}}</strong>
                        </span>
                    </div>
                    <div class="form-group row">
                        <input type="password" v-model="form.password" placeholder="Password" :class="['form-control', {'is-invalid' : formErrors.password}]" @keyup="formErrors.password = ''">
                        <span role="alert" class="invalid-feedback" v-if="formErrors.password">
                            <strong>{{formErrors.password}}</strong>
                        </span>
                    </div>
                    <div class="form-group row">
                        <input type="password" v-model="form.password_confirmation" placeholder="Password Confirmation" :class="['form-control']" @keyup="formErrors.password = ''">
                    </div>
                    <div class="form-group row">
                        <input type="submit"  value="Register">
                    </div>
                    <div class="form-footer">
                        <span>Already Have An Acoount? </span>
                        <router-link :to="{ name: 'login'}">Login Here</router-link>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import {register} from '../../helpers/api';
    export default {
        name: "register",
        data() {
            return {
                form: {
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation: ''
                },
                formErrors: {
                    name: '',
                    email: '',
                    password: ''
                },
            }
        },
        created() {
            //set title for the page
            this.$store.commit('set_title', 'Register');
        },
        methods: {
            registration() {
                const credentials = this.$data.form;
                register(credentials)
                    .then((res) => {
                        this.$store.commit("login_success", {access_token: res});
                            this.$router.push({name: 'index'});
                        })
                    .catch((error) => {
                        const response = error.response;
                        if (response) {
                            const statusCode = response.status;
                            const data = response.data;

                            switch (statusCode) {

                                case 422: {
                                    const errObj = data.errors;

                                    for (const key in errObj) {
                                        this.formErrors[key] = errObj[key][0];
                                    }

                                    break;
                                }
                            }
                        }
                    });
            },
        }
    }
</script>


<style>
    .login {
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
    .form-header {
        background: #d7dee5;
        padding-top: 10px;
        padding-bottom: 10px;
        font-size: 20px;
        color: #999;
        font-weight: 600;
        border: none;
        display: flex;
        align-items: center;
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
        color: #555;
        font-size: 12px;
    }

    .form-footer a {
        font-size: 12px;
        color: #588ab5;
        font-weight: 600;
        cursor: pointer;
    }


</style>
