<template>
    <button v-if="!transaction?.id" type="button" @click="form.type = 1" class="btn btn-outline-success mr-2"
            :class="[btnSize, btnClass]"
            data-toggle="modal" :data-target="`#${uid}`">
        <span class="fa fa-plus-circle"></span>
        Приход
    </button>

    <button v-if="!transaction?.id" type="button" @click="form.type = 2" class="btn btn-outline-danger"
            :class="[btnSize, btnClass]" data-toggle="modal"
            :data-target="`#${uid}`">
        <span class="fa fa-minus-circle"></span>
        Уход
    </button>

    <button v-if="transaction?.id" class="btn btn-sm btn-outline-primary" :class="[btnSize, btnClass]"
            data-toggle="modal"
            :data-target="`#${uid}`">
        <span class="fa fa-pen"></span>
    </button>

    <Teleport to="body">

        <div class="modal fade" :id="uid">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                            {{ transaction?.id ? `Редактирование операции №${transaction.id}` : `Новый ${typeLabels[this.form.type]}` }}
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="submit">
                        <div class="modal-body">

                            <div class="form-group">
                                <label class="form-asterisk">Сумма</label>
                                <numeric-field class="form-control"
                                               :class="{'is-invalid': form.errors.amount}"
                                               v-model="form.amount"/>

                                <div v-if="form.errors.amount" class="error invalid-feedback">
                                    {{ form.errors.amount }}
                                </div>
                            </div>

                            <div class="form-group" v-if="form.type === 2">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" id="irrevocably" v-model="form.is_irrevocably"
                                           type="checkbox"/>

                                    <label for="irrevocably" class="custom-control-label">
                                        Безвозвратный?
                                    </label>
                                </div>

                                <div v-if="form.errors.is_irrevocably" class="invalid-feedback-simple">
                                    {{ form.errors.is_irrevocably }}
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Комментарий</label>
                                <input type="text" class="form-control"
                                       :class="{'is-invalid': form.errors.comment}"
                                       v-model.trim="form.comment">

                                <div v-if="form.errors.comment" class="error invalid-feedback">
                                    {{ form.errors.comment }}
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer text-right">
                            <button :disabled="form.processing"
                                    type="submit" class="btn btn-primary">
                                <span v-if="form.processing">
                                    <i class="fas fa-spinner fa-spin"></i> {{ transaction?.id ? 'Сохранение' : 'Создание' }}...
                                </span>
                                <span v-else>
                                    {{ transaction?.id ? 'Изменить' : 'Создать' }}
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

    </Teleport>
</template>

<script>
import {useForm} from "@inertiajs/inertia-vue3";
import NumericField from "./NumericField.vue";

let uid = 0

export default {
    name: "cash-transaction-buttons",
    components: {NumericField},
    props: {
        btnSize: {
            type: String,
            default: 'btn-sm'
        },
        btnClass: String,
        transaction: Object
    },
    data() {
        ++uid;

        return {
            uid: `cash-transaction-modal-${uid}`,
            form: useForm({
                type: this.transaction?.type,
                amount: this.transaction?.amount,
                is_irrevocably: this.transaction?.is_irrevocably,
                comment: this.transaction?.comment
            }),
            typeLabels: {
                1: 'приход',
                2: 'уход',
            }
        };
    },
    methods: {
        submit() {
            let options = {
                onSuccess: () => {
                    $(`#${this.uid}`).modal('hide');

                    if (!this.transaction?.id) {
                        this.form.reset();
                    }
                },
                preserveScroll: true,
                preserveState: true
            };

            if (!this.transaction?.id) {
                this.form.post(route('cash-transactions.store'), options);
                return;
            }

            this.form.put(route('cash-transactions.update', this.transaction.id), options)
        }
    }
}
</script>
