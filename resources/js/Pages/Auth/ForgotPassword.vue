<template>
    <jet-authentication-card>
        <template #logo>
            <jet-authentication-card-logo />
        </template>

        <div class="mb-4 text-sm text-gray-600">
            Forgot your password? No problem. Just enter your phone number and we will text you a password reset link that will allow you to choose a new one.
        </div>

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <jet-validation-errors class="mb-4" />

        <form @submit.prevent="submit">
            <div>
                <jet-label for="phone" value="Phone" />
                <jet-phone-input id="phone" class="mt-1 block w-full" v-model="form.phone" required autofocus />
            </div>

            <div class="flex items-center justify-end mt-4">
                <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Send Password Reset Link
                </jet-button>
            </div>
        </form>
    </jet-authentication-card>
</template>
<script>
	import ValidatePhoneNumber from '../../Mixins/ValidatePhoneNumber.js'
    import JetAuthenticationCard from '../../Jetstream/AuthenticationCard'
    import JetAuthenticationCardLogo from '../../Jetstream/AuthenticationCardLogo'
    import JetButton from '../../Jetstream/Button'
    import JetInput from './../../Jetstream/Input'
    import JetPhoneInput from './../../Jetstream/PhoneInput'
    import JetLabel from './../../Jetstream/Label'
    import JetValidationErrors from '../../Jetstream/ValidationErrors'
    export default {
        components: {
            JetAuthenticationCard,
            JetAuthenticationCardLogo,
            JetButton,
            JetInput,
            JetPhoneInput,
            JetLabel,
            JetValidationErrors
        },
        props: {
            status: String
        },

        data() {
            return {
                form: this.$inertia.form({
                    phone: ''
                })
            }
        },
        methods: {
            submit() {
                this.form.post(this.route('password.phone'))
            }
        }
    }
</script>