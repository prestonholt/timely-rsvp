<template>
	<app-layout>
		<template #header>
			<h2 class="font-semibold text-xl text-gray-800 leading-tight">
				New Event
			</h2>
		</template>
		<div class="max-w-7xl mx-auto py-4 sm:px-6 lg:px-8">
			<jet-form-section @submitted="createEvent">
	      <template #title>
	        Event Details
	      </template>

	      <template #description>
	        Give your event a name, add a date and time, and optionally an end time.
	      </template>

	      <template #form>
	        <div class="col-span-6 sm:col-span-4">
		        <jet-label for="name" value="Event Name" />
		        <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" ref="name" placeholder="Backyard BBQ, Wine Tasting" />
		        <jet-input-error :message="form.error('name')" class="mt-2" />
	        </div>

	        <div class="col-span-6 sm:col-span-4">
	        	<jet-label for="start_date" :value="form.end_toggle ? 'Start Date and Time' : 'Date and Time'" />
	        	<div class="flex flex-wrap">
	        		<div class="flex flex-auto">
			          <jet-input id="start_date" type="date" class="mt-1 block w-full" v-model="form.start_date" placeholder="mm/dd/yyyy" />
			        </div>
			        <div class="flex pl-2">
			        	<jet-time-picker id="start_time" v-model="form.start_time" />
			        </div>
			      </div>
			      <jet-input-error :message="form.error('start_date')" class="mt-2" />
			      <jet-input-error :message="form.error('start_time')" class="mt-2" />
	        </div>

	        <div class="col-span-6 sm:col-span-4">
	        	<div class="flex">
	        		<div class="flex flex-auto">
	        			<p class="mt-1 text-sm text-gray-600">
	        				Add End Date and Time
	        			</p>
	        		</div>
	        		<div class="flex">
	        			<t-toggle v-model="form.end_toggle" />
	        		</div>
	        	</div>
	        </div>

	        <div v-if="form.end_toggle" class="col-span-6 sm:col-span-4">
	        	<jet-label for="end_date" value="End Date and Time" />
	        	<div class="flex flex-wrap">
	        		<div class="flex flex-auto">
			          <jet-input id="end_date" type="date" class="mt-1 block w-full" v-model="form.end_date" placeholder="mm/dd/yyyy" />
			        </div>
			        <div class="flex pl-2">
			        	<jet-time-picker id="end_time" v-model="form.end_time" />
			        </div>
			      </div>
			      <jet-input-error :message="form.error('end_date')" class="mt-2" />
			      <jet-input-error :message="form.error('end_time')" class="mt-2" />
	        </div>
	      </template>

	      <template #actions>
	        <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
	          Next
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

  var dayjs = require('dayjs')
  var customParseFormat = require('dayjs/plugin/customParseFormat')
  dayjs.extend(customParseFormat)

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

    data() {
      return {
        form: this.$inertia.form({
          name: '',
          start_date: '',
          start_time: '9:00 AM',
          end_toggle: false,
          end_date: '',
          end_time: ''
        }, {
          bag: 'createEvent',
        }),
      }
    },

    watch: {
    	'form.end_toggle': function (newValue, oldValue) {
    		if (newValue == true) {
    			if (this.form.start_date && this.form.start_time)
    				var date = dayjs(this.form.start_date + ' ' + this.form.start_time, 'YYYY-MM-DD h:mm A');
    			else if (this.form.start_date)
    				var date = dayjs(this.form.start_date, 'YYYY-MM-DD');
    			else if (this.form.start_time) 
    				var date = dayjs(this.form.start_time, 'h:mm A');
    			else
    				return;

    			date = date.add(1, 'hour').add(30, 'minute');

    			if (this.form.start_date)
    				this.form.end_date = date.format('YYYY-MM-DD');
    			this.form.end_time = date.format('h:mm A');

    		} else {
    			this.form.end_date = '';
    			this.form.end_time = '';
    		}
    	},

    	'form.start_date': function (newValue, oldValue) {
    		console.log(this.form.end_date);
    		console.log(this.form.start_date);
    		if ((!oldValue || oldValue == '') || dayjs(this.form.end_date, 'YYYY-MM-DD').isBefore(dayjs(newValue, 'YYYY-MM-DD'), 'day')) {
    			if (this.form.end_toggle)
    				this.form.end_date = newValue;
    		}
    	},
    },

    methods: {
      createEvent() {
        this.form.post(route('event.store'));
      },
    },
  }
</script>
