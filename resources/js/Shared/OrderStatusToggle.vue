<template>
    <select @change="toggleStatus($event)"
            class="form-control form-control-sm">
        <option :selected="status == orderStatus" :value="status"
                v-for="(label, status) in $page.props.shared.orderStatusLabels">
            {{ label }}
        </option>
    </select>
</template>

<script>
export default {
    props: {
        orderStatus: {
            type: Number,
            required: true
        },
        orderId: {
            type: Number,
            required: true
        },
    },
    methods: {
        toggleStatus(e) {
            this.$inertia.post(route('orders.toggle-status', this.orderId), {status: e.target.value})
        }
    }
}
</script>
