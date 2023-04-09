<template>
    <div v-if="isAlreadyConnected">
        Уведомления включены.

        <button
            class="btn btn-xs btn-link text-danger"
            type="button"
            :disabled="toggleStart"
            @click="toggleConnection"
        >


            {{ toggleStart ? 'Отключение...' : 'Отключить?' }}
        </button>
    </div>
    <div v-else>
        <button
            class="btn btn-xs btn-link text-success"
            type="button"
            :disabled="toggleStart"
            @click="toggleConnection"
        >
            {{ toggleStart ? 'Прикрепление...' : 'Прикрепить' }}
        </button>
    </div>
</template>

<script>
import {Link} from "@inertiajs/inertia-vue3";

export default {
    name: "telegram-notification-toggle",
    props: {
        userId: {
            type: Number,
            required: true
        },
        isAlreadyConnected: {
            type: Boolean,
            default: false
        },
    },
    components: {Link},
    data: () => ({
        toggleStart: false
    }),
    methods: {
        toggleConnection() {
            this.toggleStart = true;

            this.$inertia.post(
                route('users.telegram-notifications.toggle', this.userId),
                {
                    preserveState: true,
                    preserveScroll: true
                },
                {
                    onFinish: () => this.toggleStart = false,
                }
            )
        }
    }
}
</script>
