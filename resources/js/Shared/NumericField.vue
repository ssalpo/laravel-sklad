<template>
    <input v-bind="$attrs"
           step="any"
           @keydown="onKeyDown"
           @input="$emit('update:modelValue', $event.target.value)" type="number" :value="modelValue"/>
</template>

<script>
export default {
    name: "numeric-field",
    props: {
        modelValue: [Number, String],
        precision: {
            type: Number,
            default: 3
        },
        onlyInteger: {
            type: Boolean,
            default: false
        }
    },
    methods: {
        onKeyDown(event) {
            let key = event.keyCode || event.charCode;

            let alreadyHasDots = event.target.value.search(/\./) !== -1;

            // allowed left,right and backspace buttons
            let isAllowed = [37, 39, 8, 9, 13, 46].includes(key)

            let values = (event.target.value + "").split(".");

            let isIntegerValue = /^[0-9]*$/.test(event.key);

            if(this.onlyInteger && !isIntegerValue && !isAllowed) event.preventDefault();

            if ((values[1] && values[1].length > this.precision - 1) && ![8, 9, 46, 13].includes(key)) {
                event.preventDefault();
            }

            if (event.key === '.' && alreadyHasDots) return event.preventDefault();

            if (!/^[0-9]*\.?[0-9]*$/.test(event.key) && !isAllowed) return event.preventDefault();
        }
    }
}
</script>
