<template>
	<app-layout>
		<template #header>
			<h2 class="font-semibold text-xl text-gray-800 leading-tight">
				New Event
			</h2>
		</template>
		<div class="max-w-7xl mx-auto py-4 sm:px-6 lg:px-8">
			<jet-form-section @submitted="submitDetails">
	      <template #title>
	        Optional Details
	      </template>

	      <template #description>
	        You may add a location and description for your event.
	      </template>

	      <template #form>
	        <div class="col-span-6 sm:col-span-4">
		        <jet-label for="location" value="Location" />
		        <jet-input id="location" type="text" class="mt-1 block w-full" v-model="form.location" ref="location" placeholder="Home, Restaurant" />
		        <jet-input-error :message="form.error('location')" class="mt-2" />
	        </div>

	        <div class="col-span-6 sm:col-span-4">
		        <jet-label for="description" value="Description" />
		        <textarea id="description" rows="3" class="form-input rounded-md shadow-sm mt-1 block w-full" v-model="form.description" ref="description" placeholder="Description of event">
		        </textarea>
		        <jet-input-error :message="form.error('description')" class="mt-2" />
	        </div>

	      </template>

	      <template #actions>
	      	<inertia-link :href="route('event.edit', event)" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150">Skip</inertia-link>
	        <jet-button type="submit" class="ml-2" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
	          Done
	        </jet-button>
	      </template>
	    </jet-form-section>
	  </div>
	</app-layout>
</template>

<script>
	import AppLayout from '@/Layouts/AppLayout'
	import JetActionMessage from '@/Jetstream/ActionMessage'
  import JetButton from '@/Jetstream/Button'
  import JetFormSection from '@/Jetstream/FormSection'
  import JetInput from '@/Jetstream/Input'
  import JetInputError from '@/Jetstream/InputError'
  import JetLabel from '@/Jetstream/Label'
  import JetTimePicker from '@/Jetstream/TimePicker'

  export default {
    components: {
    	AppLayout,
      JetActionMessage,
      JetButton,
      JetFormSection,
      JetInput,
      JetInputError,
      JetLabel,
      JetTimePicker,
    },

    props: {
    	event: Object,
    },

    data() {
      return {
        form: this.$inertia.form({
          location: this.event.location,
          description: this.event.description,
        }, {
          bag: 'eventDetails',
        }),
      }
    },

    methods: {
      submitDetails() {
        this.form.post(route('event.details.store', this.event));
      },
    },
  }
</script>
