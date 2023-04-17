<template>
    <Head>
        <title>{{debt?.id ? 'Обновление данных' : 'Новый долг'}}</title>
    </Head>

    <div class="content-header">
        <div class="container-fluid">
            <h1 class="m-0">{{ debt?.id ? 'Обновление данных' : 'Новый долг' }}</h1>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <!-- form start -->
                <form @submit.prevent="submit">
                    <div class="card-body">

                        <div v-if="!debt?.id" class="mx-auto col col-md-6">
                            <div class="form-group">
                                <label class="form-asterisk">Заявка</label>

                                <select2-orders
                                    :is-invalid="errors.order_id !== undefined"
                                    :params="{without_debt: 1}"
                                    v-model="form.order_id"
                                />

                                <div v-if="errors.order_id" class="error invalid-feedback-simple">
                                    {{ errors.order_id }}
                                </div>
                            </div>
                        </div>

                        <div class="mx-auto col col-md-6">
                            <div class="form-group">
                                <label class="form-asterisk">Сумма</label>
                                <numeric-field :precision="4" class="form-control"
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

                            <Link :href="route('orders.index')" :class="{disabled: form.processing}"
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
import CustomSelect from "../../Shared/CustomSelect.vue";
import NumericField from "../../Shared/NumericField.vue";
import Select2Orders from "../../Shared/Select2Orders.vue";

export default {
    props: ['orders', 'debt', 'client', 'selectedOrder', 'errors'],
    components: {Select2Orders, NumericField, CustomSelect, Head, Link},
    directives: {maska: vMaska},
    data() {
        return {
            form: useForm({
                order_id: this.debt?.order_id || this.selectedOrder,
                amount: this.debt?.amount,
                comment: this.debt?.comment
            }),
        }
    },
    methods: {
        submit() {
            if (!this.debt?.id) {
                this.form.post(route('client.debts.store', this.client.id));
                return;
            }

            this.form.put(route('client.debts.update', {client: this.client.id, debt: this.debt.id}))
        }
    }
}
</script>
