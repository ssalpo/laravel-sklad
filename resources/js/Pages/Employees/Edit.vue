<template>
    <Head>
        <title>{{employee?.id ? 'Обновление данных сотрудника' : 'Новый сотрудник'}}</title>
    </Head>

    <div class="content-header">
        <div class="container-fluid">
            <h1 class="m-0">{{ employee?.id ? 'Обновление данных сотрудника' : 'Новый сотрудник' }}</h1>
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
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <div class="mx-auto col col-md-6 text-right">
                            <button type="submit" :disabled="form.processing" class="btn btn-primary">
                                <span v-if="form.processing">
                                    <i class="fas fa-spinner fa-spin"></i> Сохранение...
                                </span>
                                <span v-else>{{ employee?.id ? 'Сохранить' : 'Добавить' }}</span>
                            </button>

                            <Link :href="route('employees.index')" :class="{disabled: form.processing}" class="btn btn-default ml-2">Отменить</Link>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
import {Head, Link, useForm} from "@inertiajs/inertia-vue3";
import { vMaska } from "maska";

export default {
    props: ['employee', 'errors'],
    components: {Head, Link},
    directives: { maska: vMaska },
    data() {
        return {
            form: useForm({
                name: this.employee?.name
            }),
        }
    },
    methods: {
        submit() {
            if (!this.employee?.id) {
                this.form.post(route('employees.store'));
                return;
            }

            this.form.put(route('employees.update', this.employee.id))
        }
    }
}
</script>
