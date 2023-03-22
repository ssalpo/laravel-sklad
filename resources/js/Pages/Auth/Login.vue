<template>
    <Head>
        <title>Авторизация</title>
    </Head>

    <div class="login-page">
        <div class="login-box">
            <!-- /.login-logo -->
            <div class="card card-outline card-primary">
                <div class="card-header text-center">
                    <span class="h2">Авторизация</span>
                </div>
                <div class="card-body">

                    <form @submit.prevent="submit">
                        <div class="input mb-3">
                            <input type="text"
                                   v-model="form.username"
                                   :class="{'is-invalid': form.errors.username}"
                                   class="form-control" placeholder="Логин">

                            <div v-if="form.errors.username" class="error invalid-feedback">
                                {{ form.errors.username }}
                            </div>
                        </div>
                        <div class="input mb-3">
                            <input type="password"
                                   v-model="form.password"
                                   :class="{'is-invalid': form.errors.password}"
                                   class="form-control" placeholder="Пароль">

                            <div v-if="form.errors.password" class="error invalid-feedback">
                                {{ form.errors.password }}
                            </div>
                        </div>
                        <div class="row">

                            <!-- /.col -->
                            <div class="offset-4 col-4">
                                <button :disabled="form.processing" type="submit" class="btn btn-primary btn-block">Войти</button>
                            </div>
                            <!-- /.col -->
                        </div>
                    </form>

                    <!-- /.social-auth-links -->

                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
</template>

<script>
import {Head, Link, useForm} from '@inertiajs/inertia-vue3'
import AuthLayout from "../../Layouts/AuthLayout.vue";

export default {
    layout: AuthLayout,
    components: {Link, Head},
    data() {
        return {
            form: useForm({
                username: null,
                password: null
            }),
        }
    },
    methods: {
        submit() {
            this.form.post('/login');
        }
    },
}
</script>
