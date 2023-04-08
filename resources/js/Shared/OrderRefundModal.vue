<template>
    <button type="button" class="btn" :class="[btnSize, btnStyle]" data-toggle="modal" :data-target="`#${uid}`">
        Возврат
    </button>

    <Teleport to="body">

        <div class="modal fade" :id="uid">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Возврат товара по заявке №{{ orderId }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-asterisk">Количество</label>
                            <numeric-field type="text" class="form-control"
                                           :class="{'is-invalid': form.errors.quantity || isMaxQuantityError}"
                                           v-model.number="form.quantity"/>

                            <div v-if="form.errors.quantity" class="error invalid-feedback">
                                {{ form.errors.quantity }}
                            </div>

                            <div v-if="isMaxQuantityError" class="error invalid-feedback">
                                Максимум для отмены {{nomenclatureQuantity}} шт.
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-asterisk">Причина возврата</label>
                            <input type="text" class="form-control"
                                   :class="{'is-invalid': form.errors.comment}"
                                   v-model.trim="form.comment">

                            <div v-if="form.errors.comment" class="error invalid-feedback">
                                {{ form.errors.comment }}
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer text-right">
                        <button @click="submit"
                                :disabled="form.processing || isMaxQuantityError"
                                type="button" class="btn btn-primary">Добавить</button>
                    </div>
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
    name: "order-refund-modal",
    components: {NumericField},
    props: {
        btnSize: {
            type: String,
            default: 'btn-sm'
        },
        btnStyle: {
            type: String,
            default: 'btn-outline-danger'
        },
        orderId: Number,
        orderItemId: Number,
        nomenclatureId: Number,
        nomenclatureQuantity: Number,
        forProfile: {
            type: Boolean,
            default: false
        }
    },
    data() {
        ++uid;

        return {
            uid: `refund-modal-${uid}`,
            form: useForm({
                order_id: this.orderId,
                order_item_id: this.orderItemId,
                nomenclature_id: this.nomenclatureId,
                quantity: null,
                comment: null
            })
        };
    },
    computed: {
        isMaxQuantityError() {
            return this.form.quantity > this.nomenclatureQuantity
        }
    },
    methods: {
        submit() {
            if(this.isMaxQuantityError) return;

            this.form.post(route((this.forProfile ? 'my.' : '') + 'nomenclature-operations.order-refund'), {
                onSuccess: () => {
                    this.form.reset();
                    $(`#${this.uid}`).modal('hide');
                },
                preserveScroll: true,
                preserveState: true
            });
        }
    }
}
</script>
