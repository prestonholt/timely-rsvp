<template>
    <jet-authentication-card>
        <template #logo>
            <jet-authentication-card-logo />
        </template>

        <div class="mb-4 text-sm text-gray-600">
            Thanks for signing up! Before getting started, enter the verification code sent to your phone number. If you didn't receive the text message, we will gladly send you another.
        </div>

        <div class="mb-4 font-medium text-sm text-green-600" v-if="verificationLinkSent" >
            A new verification code has been sent to the phone number you provided during registration.
        </div>

        <form @submit.prevent="verificationFormSubmit">
            <div class="mt-4 pb-2">
                <jet-label for="verification_code" value="Verification Code" />
                <jet-input id="verification_code" type="text" class="mt-1 block w-full capitalize" v-model="verificationForm.verification_code" required autofocus inputmode="numeric" pattern="[0-9]*" autocomplete="one-time-code" />
                <jet-input-error :message="verificationForm.error('verification_code')" class="mt-2" />
                <div class="flex pt-2 justify-end">
                    <jet-button type="submit" :class="{ 'opacity-25': verificationForm.processing }" :disabled="verificationForm.processing">
                    Submit
                    </jet-button>
                </div>
            </div>
        </form>

        <form @submit.prevent="submit">
            <div class="mt-4 flex items-center justify-between">
                <jet-secondary-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Resend Code
                </jet-secondary-button>

                <inertia-link :href="route('logout')" method="post" class="underline text-sm text-gray-600 hover:text-gray-900">Logout</inertia-link>
            </div>
        </form>
    </jet-authentication-card>
</template>

<script>
    import JetAuthenticationCard from '@/Jetstream/AuthenticationCard'
    import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo'
    import JetButton from '@/Jetstream/Button'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton'
    import JetLabel from '@/Jetstream/Label'
    import JetInput from '@/Jetstream/Input'
    import JetInputError from '@/Jetstream/InputError'
    export default {
        components: {
            JetAuthenticationCard,
            JetAuthenticationCardLogo,
            JetButton,
            JetSecondaryButton,
            JetLabel,
            JetInput,
            JetInputError,
        },
        props: {
            status: String
        },
        data() {
            return {
                form: this.$inertia.form(),
                verificationForm: this.$inertia.form({
                    verification_code: '',
                }, {
                    bag: 'verification'
                })
            }
        },
        methods: {
            submit() {
                this.form.post(this.route('verification.send'))
            },

            verificationFormSubmit() {
                this.verificationForm.post(this.route('verification.verify'));
            }
        },
        computed: {
            verificationLinkSent() {
                return this.status === 'verification-link-sent';
            }
        }
    }
</script>