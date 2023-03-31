<template>
    <Head>
        <title>{{clientDiscount?.id ? 'Обновление данных' : 'Новая скидка'}}</title>
    </Head>

    <div class="content-header">
        <div class="container">
            <h1 class="m-0">{{ clientDiscount?.id ? 'Обновление данных' : 'Новая скидка' }}</h1>
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
                                <label class="form-asterisk">Номенклатура</label>
                                <select class="form-control"
                                        :class="{'is-invalid': errors.nomenclature_id}"
                                        v-model.trim="form.nomenclature_id">
                                    <option :value="nomenclature.id"
                                            :selected="!form.nomenclature_id && index === 0"
                                            v-for="(nomenclature, index) in nomenclatures">{{ nomenclature.name }}
                                    </option>
                                </select>

                                <div v-if="errors.nomenclature_id" class="error invalid-feedback">
                                    {{ errors.nomenclature_id }}
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-asterisk">Сумма скидки</label>
                                <input type="text" class="form-control"
                                       :class="{'is-invalid': errors.discount}"
                                       v-model.trim="form.discount">

                                <div v-if="errors.discount" class="error invalid-feedback">
                                    {{ errors.discount }}
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
                                <span v-else>{{ clientDiscount?.id ? 'Сохранить' : 'Добавить' }}</span>
                            </button>

                            <Link :href="route('client-discounts.index', client.id)" :class="{disabled: form.processing}" class="btn btn-default ml-2">Отменить</Link>
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
    props: ['client', 'clientDiscount', 'nomenclatures', 'errors'],
    components: {Head, Link},
    data() {
        return {
            form: useForm({
                nomenclature_id: this.clientDiscount?.nomenclature_id,
                discount: this.clientDiscount?.discount
            }),
        }
    },
    methods: {
        submit() {
            if (!this.clientDiscount?.id) {
                this.form.post(route('client-discounts.store', {client: this.client.id}));
                return;
            }

            this.form.put(route('client-discounts.update', {client: this.client.id, client_discount: this.clientDiscount.id}))
        }
    }
}
</script>
