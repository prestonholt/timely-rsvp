<template>
    <jet-authentication-card>
        <template #logo>
            <jet-authentication-card-logo />
        </template>

        <jet-validation-errors class="mb-4" />

        <form @submit.prevent="submit">
            <div>
                <jet-label for="name" value="Name" />
                <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus autocapitalize="word" autocomplete="name" />
            </div>

            <div class="mt-4">
                <jet-label for="phone" value="Phone Number" />
                <jet-phone-input id="phone" class="mt-1 block w-full" v-model="form.phone" required autocomplete="username" />
            </div>

            <div class="mt-4">
                <jet-label for="password" value="Password" />
                <jet-input id="password" type="password" class="mt-1 block w-full" v-model="form.password" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <jet-label for="password_confirmation" value="Confirm Password" />
                <jet-input id="password_confirmation" type="password" class="mt-1 block w-full" v-model="form.password_confirmation" required autocomplete="new-password" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <inertia-link :href="route('login')" class="underline text-sm text-gray-600 hover:text-gray-900">
                    Already registered?
                </inertia-link>

                <jet-button class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Register
                </jet-button>
            </div>
        </form>
    </jet-authentication-card>
</template>
<script>
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
        data() {
            return {
                form: this.$inertia.form({
                    name: '',
                    phone: '',
                    password: '',
                    password_confirmation: ''
                })
            }
        },
        methods: {
            submit() {
                this.form.post(this.route('register')).then(() => {
                    this.form.password = ''
                    this.form.password_confirmation = ''
                })
            }
        }
    }
</script>