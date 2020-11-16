<template>
    <input class="form-input rounded-md shadow-sm" type="tel" :value="value" @input="onInput" ref="input">
</template>

<script>
	import ValidatePhoneNumber from '../Mixins/ValidatePhoneNumber.js'
    export default {
    	mixins: [ValidatePhoneNumber],
        props: ['value'],

        methods: {
            focus() {
                this.$refs.input.focus()
            },

            onInput(event) {
            	this.$emit('input', this.prettyNumber(event.target.value));
                this.$forceUpdate();
            },
        },

        mounted() {
        	this.$emit('input', this.prettyNumber(this.value));
        }
    }
</script>

