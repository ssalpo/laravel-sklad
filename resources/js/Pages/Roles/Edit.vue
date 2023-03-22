<template>
    <Head>
        <title>{{role?.id ? 'Обновление данных роли' : 'Новая роль'}}</title>
    </Head>

    <div class="content-header">
        <div class="container">
            <h1 class="m-0">{{ role?.id ? 'Обновление данных роли' : 'Новая роль' }}</h1>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="card card-primary">
                <!-- form start -->
                <form @submit.prevent="submit">
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-asterisk">Ключ</label>
                            <input type="text" class="form-control"
                                   :class="{'is-invalid': errors.name}"
                                   v-model.trim="form.name">

                            <div v-if="errors.name" class="error invalid-feedback">
                                {{ errors.name }}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-asterisk">Название</label>
                            <input type="text" class="form-control"
                                   :class="{'is-invalid': errors.readable_name}"
                                   v-model.trim="form.readable_name" placeholder="Ключ на английском, например: admin, doctor">

                            <div v-if="errors.readable_name" class="error invalid-feedback">
                                {{ errors.readable_name }}
                            </div>
                        </div>

                        <div class="form-group" v-if="form.name !== 'admin'">
                            <label class="form-asterisk">Права</label>

                            <div class="custom-control custom-checkbox" v-for="(permission, id) in permissions">
                                <input class="custom-control-input"  v-model="form.permissions" :value="id" type="checkbox" :id="`permissionCheckbox${id}`">
                                <label :for="`permissionCheckbox${id}`" class="custom-control-label">
                                    {{permission}}
                                </label>
                            </div>

                            <div v-if="errors.permissions" class="invalid-feedback-simple">
                                {{ errors.permissions }}
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <button type="submit" :disabled="form.processing" class="btn btn-primary">
                            <span v-if="form.processing">
                                <i class="fas fa-spinner fa-spin"></i> Сохранение...
                            </span>
                            <span v-else>{{ role?.id ? 'Сохранить' : 'Добавить' }}</span>
                        </button>

                        <Link :href="route('roles.index')" :class="{disabled: form.processing}" class="btn btn-default ml-2">Отменить</Link>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
import {Head, Link, useForm} from "@inertiajs/inertia-vue3";

export default {
    props: ['role', 'errors', 'permissions'],
    components: {Head, Link},
    data() {
        return {
            form: useForm({
                name: this.role?.name,
                readable_name: this.role?.readable_name,
                permissions: this.role?.permissions || []
            }),
        }
    },
    methods: {
        submit() {
            if (!this.role?.id) {
                this.form.post('/roles');
                return;
            }

            this.form.put(`/roles/${this.role.id}`)
        }
    }
}
</script>
