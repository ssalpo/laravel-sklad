<template>
    <button
        type="button"
        class="btn btn-sm btn-outline-success"
        @click="toggleModal"
    >
        Оплатить
    </button>

    <bs-modal :is-open="isModalOpen" @close="isModalOpen = false">
        <template #header>
            Остаток долга после оплаты
        </template>

        <form @submit.prevent="submit">
            <div class="form-group">
                <label>Введите сумму остатка долга</label>
                <numeric-field class="form-control"
                               :class="{'is-invalid': form.errors.amount}"
                               v-model.number="form.amount"/>

                <div v-if="form.errors.amount" class="error invalid-feedback">
                    {{ form.errors.amount }}
                </div>
            </div>

            <input type="submit" class="d-none"/>
        </form>

        <template #footer>
            <button :disabled="form.processing" @click="submit" type="submit" class="btn btn-primary">
                <span v-if="form.processing">
                    <i class="fas fa-spinner fa-spin"></i> Добавление...
                </span>
                <span v-else>
                    Добавить
                </span>
            </button>
        </template>
    </bs-modal>
</template>

<script>
import BsModal from "./BsModal.vue";
import NumericField from "./NumericField.vue";
import {useForm} from "@inertiajs/inertia-vue3";

export default {
    components: {NumericField, BsModal},
    props: {
        orderId: {
            type: Number,
            required: true
        },
        alreadyHasDebt: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            isModalOpen: false,
            form: useForm({
                amount: undefined
            })
        }
    },
    methods: {
        submit() {
            this.form.post(route('orders.do-payment', this.orderId), {
                onSuccess: () => {
                    this.isModalOpen = false;
                    this.form.reset();
                },
                preserveScroll: true,
                preserveState: true
            })
        },
        toggleModal() {
            if (this.alreadyHasDebt) {
                this.submit();
                return;
            }

            this.isModalOpen = true;
        }
    }
}
</script>
