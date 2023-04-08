<template>
    <button
        v-if="orderIsCancel(status) && rollbackBtn === true"
        @click="backStatusToSend"
        type="button"
        :class="[size]" class="btn btn-success mr-1"
        title="Вернуть статус отправлено"
    >
        <span class="fa fa-reply-all"></span>
    </button>

    <button
        @click="toggleStatus"
        type="button"
        v-if="orderIsNew(status) || orderIsSend(status)"
        class="btn"
        :class="['btn-' + (orderIsNew(status) ? 'success' : 'danger'), size]"
    >
        <i class="fa" :class="[orderIsNew(status) ? 'fa-paper-plane' : 'fa-times']"></i>

        <span class="d-none d-sm-inline-block ml-1">
            {{ orderIsNew(status) ? 'Заказ отправлен' : 'Отменить заказ' }}
        </span>
    </button>
</template>

<script>
import {Link} from "@inertiajs/inertia-vue3";

import {
    orderIsCancel,
    orderIsNew,
    orderIsSend
} from "../Constants/order";

export default {
    name: "order-change-status-btn",
    components: {Link},
    props: {
        size: {
            type: String,
            default: 'btn-sm'
        },
        orderId: Number,
        status: Number,
        forProfile: {
            type: Boolean,
            default: false
        },
        rollbackBtn: {
            type: Boolean,
            default: false
        }
    },
    computed: {
        url() {
            let statusAction = 'cancel';

            let userTypeAction = this.forProfile ? 'my.' : '';

            if (this.orderIsNew(this.status) || this.orderIsCancel(this.status)) {
                statusAction = 'send'
            }

            return route(userTypeAction + 'orders.mark-as-' + statusAction, this.orderId);
        }
    },
    methods: {
        orderIsCancel,
        orderIsNew,
        orderIsSend,
        toggleStatus() {
            if (this.orderIsSend(this.status) && !confirm('Вы уверены что хотите отменить заявку?')) {
                return;
            }

            this.$inertia.post(this.url, {preserveState: true, preserveScroll: true})
        },
        backStatusToSend() {
            if (this.orderIsCancel(this.status) && !confirm('Вы уверены что хотите изменить статус на отправлено?')) {
                return;
            }

            this.$inertia.post(this.url, {rollback: true}, {preserveState: true, preserveScroll: true,})
        }
    }
}
</script>
