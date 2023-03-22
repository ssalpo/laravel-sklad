<template>
    <Head>
        <title>{{unit?.id ? 'Обновление данных' : 'Новая ед. измерения'}}</title>
    </Head>

    <div class="content-header">
        <div class="container">
            <h1 class="m-0">{{ unit?.id ? 'Обновление данных' : 'Новая ед. измерения' }}</h1>
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
                            <span v-else>{{ unit?.id ? 'Сохранить' : 'Добавить' }}</span>
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
    props: ['unit', 'errors'],
    components: {Head, Link},
    data() {
        return {
            form: useForm({
                name: this.unit?.name
            }),
        }
    },
    methods: {
        submit() {
            if (!this.unit?.id) {
                this.form.post(route('units.store'));
                return;
            }

            this.form.put(route('units.update', this.unit.id))
        }
    }
}
</script>
