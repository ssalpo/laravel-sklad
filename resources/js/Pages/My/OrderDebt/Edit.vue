<template>
    <Head>
        <title>Долг по заказу №{{order.id}}</title>
    </Head>

    <div class="content-header">
        <div class="container-fluid">
            <h1 class="m-0">Долг по заказу №{{order.id}}</h1>
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
                                <label class="form-asterisk">Сумма</label>
                                <numeric-field class="form-control"
                                       :class="{'is-invalid': errors.amount}"
                                       v-model="form.amount" />

                                <div v-if="errors.amount" class="error invalid-feedback">
                                    {{ errors.amount }}
                                </div>
                            </div>
                        </div>
                        <div class="mx-auto col col-md-6">
                            <div class="form-group">
                                <label>Комментарий</label>
                                <input type="text" class="form-control"
                                       :class="{'is-invalid': errors.comment}"
                                       v-model.trim="form.comment">

                                <div v-if="errors.comment" class="error invalid-feedback">
                                    {{ errors.comment }}
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
                                <span v-else>{{ debt?.id ? 'Сохранить' : 'Добавить' }}</span>
                            </button>

                            <Link :href="route('my.orders.index')" :class="{disabled: form.processing}"
                                  class="btn btn-default ml-2">Отменить
                            </Link>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
import {Head, Link, useForm} from "@inertiajs/inertia-vue3";
import {vMaska} from "maska";
import NumericField from "../../../Shared/NumericField.vue";

export default {
    props: ['order', 'debt', 'errors'],
    components: {NumericField, Head, Link},
    directives: {maska: vMaska},
    data() {
        return {
            form: useForm({
                amount: this.debt?.amount,
                comment: this.debt?.comment
            }),
        }
    },
    methods: {
        submit() {
            this.form.post(route('my.order-debts.store', this.order.id));
        }
    }
}
</script>
