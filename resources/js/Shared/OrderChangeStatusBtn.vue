<template>
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
        }
    },
    methods: {
        orderIsNew,
        orderIsSend,
        toggleStatus() {
            if (this.orderIsSend(this.status) && !confirm('Вы уверены что хотите отменить заявку?')) {
                return;
            }

            this.$inertia.post(route( (this.forProfile ? 'my.' : '') + 'orders.mark-as-' + (orderIsNew(this.status) ? 'send' : 'cancel'), this.orderId), {
                preserveState: true,
                preserveScroll: true,
            })
        }
    }
}
</script>
