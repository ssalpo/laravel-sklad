<template>
    <Head>
        <title>{{user?.id ? 'Обновление данных сотрудника' : 'Новый сотрудник'}}</title>
    </Head>

    <div class="content-header">
        <div class="container-fluid">
            <h1 class="m-0">{{ user?.id ? 'Обновление данных сотрудника' : 'Новый сотрудник' }}</h1>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <!-- form start -->
                <form @submit.prevent="submit">
                    <div class="card-body">
                        <div class="mx-auto col col-md-6">
                            <div class="form-group">
                                <label class="form-asterisk">Имя</label>
                                <input type="text" class="form-control"
                                       :class="{'is-invalid': errors.name}"
                                       v-model.trim="form.name">

                                <div v-if="errors.name" class="error invalid-feedback">
                                    {{ errors.name }}
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-asterisk">Логин для входа</label>
                                <input type="text" class="form-control"
                                       :class="{'is-invalid': errors.username}"
                                       v-model.trim="form.username">

                                <div v-if="errors.username" class="error invalid-feedback">
                                    {{ errors.username }}
                                </div>
                            </div>

                            <div class="form-group">
                                <label :class="{'form-asterisk': !user?.id}">Пароль</label>
                                <input type="password" class="form-control"
                                       :class="{'is-invalid': errors.password}"
                                       v-model.trim="form.password">

                                <div v-if="errors.password" class="error invalid-feedback">
                                    {{ errors.password }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <div class="mx-auto col col-md-6">
                            <button type="submit" :disabled="form.processing" class="btn btn-primary">
                                <span v-if="form.processing">
                                    <i class="fas fa-spinner fa-spin"></i> Сохранение...
                                </span>
                                <span v-else>{{ user?.id ? 'Сохранить' : 'Добавить' }}</span>
                            </button>

                            <Link :href="route('users.index')" :class="{disabled: form.processing}" class="btn btn-default ml-2">Отменить</Link>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
import {Head, Link, useForm} from "@inertiajs/inertia-vue3";

export default {
    props: ['user', 'errors'],
    components: {Head, Link},
    data() {
        return {
            form: useForm({
                name: this.user?.name,
                username: this.user?.username,
                password: this.user?.password
            }),
        }
    },
    methods: {
        submit() {
            if (!this.user?.id) {
                this.form.post('/users');
                return;
            }

            this.form.put(`/users/${this.user.id}`)
        }
    }
}
</script>
