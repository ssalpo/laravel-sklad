<template>
    <Head>
        <title>{{user?.id ? 'Обновление данных пользователя' : 'Новый пользователя'}}</title>
    </Head>

    <div class="content-header">
        <div class="container">
            <h1 class="m-0">{{ user?.id ? 'Обновление данных пользователя' : 'Новый пользователя' }}</h1>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="card card-primary">
                <!-- form start -->
                <form @submit.prevent="submit">
                    <div class="card-body">
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
                            <label class="form-asterisk">Роль</label>
                            <select class="form-control"
                                   :class="{'is-invalid': errors.role}"
                                   v-model.trim="form.role">
                                <option :value="role" v-for="(description, role) in roles">{{description}}</option>
                            </select>

                            <div v-if="errors.role" class="error invalid-feedback">
                                {{ errors.role }}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-asterisk">Пароль</label>
                            <input type="password" class="form-control"
                                   :class="{'is-invalid': errors.password}"
                                   v-model.trim="form.password">

                            <div v-if="errors.password" class="error invalid-feedback">
                                {{ errors.password }}
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <button type="submit" :disabled="form.processing" class="btn btn-primary">
                            <span v-if="form.processing">
                                <i class="fas fa-spinner fa-spin"></i> Сохранение...
                            </span>
                            <span v-else>{{ user?.id ? 'Сохранить' : 'Добавить' }}</span>
                        </button>

                        <Link :href="route('users.index')" :class="{disabled: form.processing}" class="btn btn-default ml-2">Отменить</Link>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
import {Head, Link, useForm} from "@inertiajs/inertia-vue3";

export default {
    props: ['user', 'errors', 'roles'],
    components: {Head, Link},
    data() {
        return {
            form: useForm({
                name: this.user?.name,
                username: this.user?.username,
                password: this.user?.password,
                role: this.user?.role || 'doctor'
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
