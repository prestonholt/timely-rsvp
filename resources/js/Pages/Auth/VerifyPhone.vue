<template>
    <jet-authentication-card>
        <template #logo>
            <jet-authentication-card-logo />
        </template>

        <div class="mb-4 text-sm text-gray-600">
            Thanks for signing up! Before getting started, enter the verification code sent to your phone number. If you didn\'t receive the text message, we will gladly send you another.
        </div>

        <div v-if="verificationLinkSent" class="mb-4 font-medium text-sm text-green-600">
            A new verification code has been sent to the phone number you provided during registration.
        </div>

        <form @submit.prevent="submit">
            <div class="mt-4 flex items-center justify-between">
                <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Resend Verification Code
                </jet-button>

                <a :href="route('logout')" @click.prevent="logout" class="underline text-sm text-gray-600 hover:text-gray-900">Logout</a>
            </div>
        </form>
    </jet-authentication-card>
</template>
<script>
    import JetAuthenticationCard from '../../Jetstream/AuthenticationCard'
    import JetAuthenticationCardLogo from '../../Jetstream/AuthenticationCardLogo'
    import JetButton from '../../Jetstream/Button'
    export default {
        components: {
            JetAuthenticationCard,
            JetAuthenticationCardLogo,
            JetButton,
        },
        props: {
            status: String
        },
        data() {
            return {
                form: this.$inertia.form({
                    phone: '',
                    password: '',
                    remember: false,
                })
            }
        },
        methods: {
            submit() {
                this.form.post(this.route('verification.send'))
            },
            logout() {
                axios.post(this.route('logout')).then(response => {
                    window.location = '/';
                })
            }
        },
        computed: {
            verificationLinkSent() {
                return this.status === 'verification-link-sent';
            }
        }
    }
</script>