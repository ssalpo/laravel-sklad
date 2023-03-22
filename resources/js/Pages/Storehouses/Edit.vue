<template>
    <Head>
        <title>{{storehouse?.id ? 'Обновление данных пользователя' : 'Новый пользователя'}}</title>
    </Head>

    <div class="content-header">
        <div class="container">
            <h1 class="m-0">{{ storehouse?.id ? 'Обновление данных пользователя' : 'Новый пользователя' }}</h1>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="card card-primary">
                <!-- form start -->
                <form @submit.prevent="submit">
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-asterisk">Название</label>
                            <input type="text" class="form-control"
                                   :class="{'is-invalid': errors.name}"
                                   v-model.trim="form.name">

                            <div v-if="errors.name" class="error invalid-feedback">
                                {{ errors.name }}
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <button type="submit" :disabled="form.processing" class="btn btn-primary">
                            <span v-if="form.processing">
                                <i class="fas fa-spinner fa-spin"></i> Сохранение...
                            </span>
                            <span v-else>{{ storehouse?.id ? 'Сохранить' : 'Добавить' }}</span>
                        </button>

                        <Link :href="route('storehouses.index')" :class="{disabled: form.processing}" class="btn btn-default ml-2">Отменить</Link>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
import {Head, Link, useForm} from "@inertiajs/inertia-vue3";

export default {
    props: ['storehouse', 'errors'],
    components: {Head, Link},
    data() {
        return {
            form: useForm({
                name: this.storehouse?.name
            }),
        }
    },
    methods: {
        submit() {
            if (!this.storehouse?.id) {
                this.form.post('/storehouses');
                return;
            }

            this.form.put(`/storehouses/${this.storehouse.id}`)
        }
    }
}
</script>
