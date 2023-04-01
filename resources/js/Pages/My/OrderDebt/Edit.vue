<template>
    <Head>
        <title>Долг по заказу №{{order.id}}</title>
    </Head>

    <div class="content-header">
        <div class="container">
            <h1 class="m-0">Долг по заказу №{{order.id}}</h1>
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
                                <label class="form-asterisk">Сумма</label>
                                <input type="text" class="form-control"
                                       :class="{'is-invalid': errors.amount}"
                                       v-model.trim="form.amount">

                                <div v-if="errors.amount" class="error invalid-feedback">
                                    {{ errors.amount }}
                                </div>
                            </div>
                        </div>
                        <div class="mx-auto col col-md-6">
                            <div class="form-group">
                                <label class="form-asterisk">Комментарий</label>
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
                                <span v-else>{{ client?.id ? 'Сохранить' : 'Добавить' }}</span>
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

export default {
    props: ['order', 'errors'],
    components: {Head, Link},
    directives: {maska: vMaska},
    data() {
        return {
            form: useForm({
                amount: this.client?.amount,
                comment: this.client?.comment
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
