<template>
	<app-layout>
		<template #header>
      <div class="flex justify-between">
        <div class="my-auto">
          <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ event.name }}
          </h2>
        </div>
      </div>

      <!-- Calendar Icon and Text -->
      <div class="mt-2 flex items-center text-sm leading-5 text-gray-500">
        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
        </svg>
        <template v-if="!event.end_date">
          {{ dayjs(event.start_date, 'YYYY-MM-DD H:mm:ss').format('MMMM D, YYYY [at] h:mma') }}
        </template>
        <template v-else-if="dayjs(event.start_date, 'YYYY-MM-DD H:mm:ss').isSame(dayjs(event.end_date, 'YYYY-MM-DD H:mm:ss'), 'day')">
          {{ dayjs(event.start_date, 'YYYY-MM-DD H:mm:ss').format('MMMM D, YYYY') }}
          <br>
          {{ dayjs(event.start_date, 'YYYY-MM-DD H:mm:ss').format('h:mma') }} - {{ dayjs(event.end_date, 'YYYY-MM-DD H:mm:ss').format('h:mma') }}
        </template>
        <template v-else>
          {{ dayjs(event.start_date, 'YYYY-MM-DD H:mm:ss').format('MMMM D, YYYY [at] h:mma') }} -
          <br>
          {{ dayjs(event.end_date, 'YYYY-MM-DD H:mm:ss').format('MMMM D, YYYY [at] h:mma') }}
        </template>
        
      </div>

      <!-- Host Icon and Text -->
      <div class="mt-2 flex items-center text-sm leading-5 text-gray-500">
        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" />
        </svg>
        <a class="hover:underline" :href="'sms://' + event.user.phone">{{ event.user.name }}</a>
      </div>

      <!-- Location Icon and Text -->
      <div v-if="event.location" class="mt-2 flex items-center text-sm leading-5 text-gray-500">
        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
        </svg>
        {{ event.location }}
      </div>

      <!-- Responses Icon and Text -->
      <div v-if="numberPending || numberAccepted || numberDeclined" class="mt-2 flex flex-wrap items-center text-sm leading-5 text-gray-500">
        <template v-if="numberPending">
          <svg class="flex-shrink-0 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
          </svg>
          {{ numberPending }} Pending
        </template>
        <template v-if="numberAccepted">
          <svg class="flex-shrink-0 h-5 w-5 text-gray-400" :class="{ 'ml-1.5': numberPending }" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
          </svg>
          {{ numberAccepted }} Accepted
        </template>
        <template v-if="numberDeclined">
          <svg class="flex-shrink-0 h-5 w-5 text-gray-400" :class="{ 'ml-1.5': numberAccepted || numberPending }" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
          </svg>
          {{ numberDeclined }} Declined
        </template>
      </div>

      <div v-if="event.description" class="mt-2 flex items-center text-sm leading-5 px-2 py-1 border border-gray-300 rounded-md bg-gray-100">
        <div class="text-gray-500">
          {{ event.description }}
        </div>
      </div>
    </template>
		

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
  import JetDialogModal from '@/Jetstream/DialogModal'
  import JetSecondaryButton from '@/Jetstream/SecondaryButton'
  import JetDangerButton from '@/Jetstream/DangerButton'

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
      JetDialogModal,
      JetSecondaryButton,
      JetDangerButton,
    },

    data: function() {
    	return {
    		dayjs: require('dayjs'),

    		
    	}
    },

    beforeMount() {
    	var customParseFormat = require('dayjs/plugin/customParseFormat');
    	var relativeTime = require('dayjs/plugin/relativeTime');
			this.dayjs.extend(customParseFormat);
			this.dayjs.extend(relativeTime);
    },

    props: {
      event: Object,
      invites: Array,
    },

    computed: {
      numberPending() {
        return this.invites.filter(function(invite) {
          return invite.accepted === null;
        }).length;
      },

      numberAccepted() {
        return this.invites.filter(function(invite) {
          return invite.accepted === true;
        }).length;
      },

      numberDeclined() {
        return this.invites.filter(function(invite) {
          return invite.accepted === false;
        }).length;
      },
    },

  }
</script>
