<template>
    <jet-authentication-card>
        <template #logo>
            <jet-authentication-card-logo />
        </template>

        <jet-validation-errors class="mb-4" />

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <h2 class="text-center text-xl leading-9 font-extrabold text-gray-900">Don't have an account?</h2>
        <p class="text-center leading-3 pb-3">
            <a :href="route('register')" class="underline text-sm text-gray-600 hover:text-gray-900">
              Register with your phone number
            </a>
        </p>

        <form @submit.prevent="submit">
            <div>
                <jet-label for="phone" value="Phone Number" />
                <jet-phone-input id="phone" class="mt-1 block w-full" v-model="form.phone" required autofocus />
            </div>

            <div class="mt-4">
                <jet-label for="password" value="Password" />
                <jet-input id="password" type="password" class="mt-1 block w-full" v-model="form.password" required autocomplete="current-password" />
            </div>

            <div class="block mt-4">
                <label class="flex items-center">
                    <input type="checkbox" class="form-checkbox" name="remember" v-model="remember">
                    <span class="ml-2 text-sm text-gray-600">Remember me</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                <inertia-link v-if="canResetPassword" :href="route('password.request')" class="underline text-sm text-gray-600 hover:text-gray-900">
                    Forgot your password?
                </inertia-link>

                <jet-button class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Login
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
        props: {
            canResetPassword: Boolean,
            status: String
        },
        data() {
            return {
                remember: false,
                form: this.$inertia.form({
                    phone: '',
                    password: '',
                    remember: ''
                })
            }
        },
        watch: {
            remember(value) {
                this.form.remember = value ? 'on' : ''
            }
        },
        methods: {
            submit() {
                this.form.post(this.route('login')).then(() => {
                    this.remember = false
                })
            }
        }
    }
</script>