<template>
    <div class="datetimepicker-inline" v-if="config.inline"></div>
    <input type="text"
           class="form-control datetimepicker-input"
           :id="id"
           data-toggle="datetimepicker"
           :data-target="`#${id}`" v-else>
</template>

<script>
// implemented from https://github.com/ankurk91/vue-bootstrap-datetimepicker/tree/tempusdominus-bs-4
const events = ['hide', 'show', 'change', 'error', 'update'];
const eventNameSpace = 'datetimepicker';

export default {
    props: {
        value: {
            default: null,
            validator(value) {
                return value === null || value instanceof Date || typeof value === 'string' || value instanceof String || value instanceof moment
            }
        },
        config: {
            type: Object,
            default: () => ({})
        },
        id: {
            type: String,
            default: () => {
                return 'input-' + Math.random().toString(36).substr(2, 9)
            }
        },
    },
    data() {
        return {
            dp: null,
            elem: null
        };
    },
    mounted() {
        // Return early if date-picker is already loaded
        /* istanbul ignore if */
        if (this.dp) return;
        // Handle wrapped input
        this.elem = jQuery(this.wrap ? this.$el.parentNode : this.$el);
        // Init date-picker
        this.elem.datetimepicker(this.config);
        // Store data control
        this.dp = this.elem.data('datetimepicker');
        // Set initial value
        this.dp.date(moment(this.value, "DD.MM.YYYY HH:mm"));
        // Watch for changes
        this.elem.on('change.datetimepicker', this.onChange);
        // Register remaining events
        this.registerEvents();
    },
    watch: {
        value(newValue) {
            this.dp && this.dp.date(moment(newValue, "DD.MM.YYYY HH:mm") || null)
        },
        config: {
            deep: true,
            handler(newConfig) {
                this.dp && this.dp.options(newConfig);
            }
        }
    },
    methods: {
        onChange(event) {
            let formattedDate = event.date ? event.date.format(this.dp.format()) : null;

            this.$emit('update:modelValue', formattedDate);
        },
        registerEvents() {
            events.forEach((name) => {
                this.elem.on(`${name}.${eventNameSpace}`, (...args) => {
                    this.$emit(`on-${name}`, ...args);
                });
            })
        }
    },
    beforeDestroy() {
        if (this.dp) {
            this.dp.destroy();
            this.dp = null;
            this.elem = null;
        }
    },
}
</script>
