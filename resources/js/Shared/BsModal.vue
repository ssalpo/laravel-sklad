<template>
    <Teleport to="body">
        <div class="modal fade" :id="uid">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" v-if="!headerDisable">
                        <h4 class="modal-title">
                            <slot name="header" />
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <slot />
                    </div>

                    <div class="modal-footer text-right" v-if="!footerDisable">
                        <slot name="footer" />
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
import NumericField from "./NumericField.vue";

let uid = 0;

export default {
    emits: ['close'],
    components: {NumericField},
    props: {
        headerDisable: {
            type: Boolean,
            default: false
        },
        footerDisable: {
            type: Boolean,
            default: false
        },
        isOpen: {
            type: Boolean,
            default: false
        }
    },
    data() {
        uid++;

        return {
            uid: `modal-${Date.now()}-${uid}`
        }
    },
    mounted() {
        $(`#${this.uid}`).on('hidden.bs.modal', () => this.$emit('close'))
    },
    watch: {
        isOpen: {
            immediate: true,
            handler(status) {
                $(`#${this.uid}`).modal(status ? 'show' : 'hide')
            }
        }
    }
}
</script>
