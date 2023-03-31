<template>
    <Head>
        <title>{{client?.id ? 'Обновление данных клиента' : 'Новый клиент'}}</title>
    </Head>

    <div class="content-header">
        <div class="container">
            <h1 class="m-0">{{ client?.id ? 'Обновление данных клиента' : 'Новый клиент' }}</h1>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="card card-primary">
                <!-- form start -->
                <form @submit.prevent="submit">
                    <div class="card-body">
                        <div class="mx-auto col col-md-6">
                            <div class="form-group">
                                <label class="form-asterisk">Наименование</label>
                                <input type="text" class="form-control"
                                       :class="{'is-invalid': errors.name}"
                                       v-model.trim="form.name">

                                <div v-if="errors.name" class="error invalid-feedback">
                                    {{ errors.name }}
                                </div>
                            </div>
                            <div class="form-group">
                            <label>Телефон</label>
                            <input type="text" class="form-control"
                                   v-maska data-maska="+############"
                                   :class="{'is-invalid': errors.phone}"
                                   v-model.trim="form.phone">

                            <div v-if="errors.phone" class="error invalid-feedback">
                                {{ errors.phone }}
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
                                <span v-else>{{ client?.id ? 'Сохранить' : 'Добавить' }}</span>
                            </button>

                            <Link :href="route('clients.index')" :class="{disabled: form.processing}" class="btn btn-default ml-2">Отменить</Link>
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
    props: ['client', 'errors'],
    components: {Head, Link},
    directives: { maska: vMaska },
    data() {
        return {
            form: useForm({
                name: this.client?.name,
                phone: this.client?.phone
            }),
        }
    },
    methods: {
        submit() {
            if (!this.client?.id) {
                this.form.post(route('clients.store'));
                return;
            }

            this.form.put(route('clients.update', this.client.id))
        }
    }
}
</script>
