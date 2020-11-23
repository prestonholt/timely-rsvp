<template>
	<app-layout>
		<template #header>
			<div class="flex justify-between">
    		<div class="my-auto">
          <h2 class="font-semibold text-xl text-gray-800 leading-tight">
						{{ event.name }}
					</h2>
        </div>
        <div>
        	<button type="button" @click="editEvent" class="inline-flex items-center px-3 py-1 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out fduration-150">Edit</button>
        </div>
      </div>

      <!-- Calendar Icon and Text -->
      <div class="mt-2 flex items-center text-sm leading-5 text-gray-500">
        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
        </svg>
        <a class="hover:underline" :href="route('event.calendar', event)">
        <template v-if="!event.end_date">
          {{ dayjs(event.start_date).format('MMMM D, YYYY [at] h:mma') }}
        </template>
        <template v-else-if="dayjs(event.start_date).isSame(dayjs(event.end_date), 'day')">
          {{ dayjs(event.start_date).format('MMMM D, YYYY') }}
          <br>
          {{ dayjs(event.start_date).format('h:mma') }} - {{ dayjs(event.end_date).format('h:mma') }}
        </template>
        <template v-else>
          {{ dayjs(event.start_date).format('MMMM D, YYYY [at] h:mma') }} -
          <br>
          {{ dayjs(event.end_date).format('MMMM D, YYYY [at] h:mma') }}
        </template>
        </a>
        
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

		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			<div class="flex flex-col">
			  <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
			    <div class="py-2 align-middle inline-block min-w-full">
			      <div class="shadow overflow-hidden border-b border-gray-200">
				      <div class="min-w-full divide-y divide-gray-200">
				      	<div class="flex justify-between px-6 py-2 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
				      		<div class="my-auto">
				      			Invites
				      		</div>
				      		<div>
				      			<button type="button" @click="invite" class="inline-flex items-center px-2 py-1  font-semibold text-2xl uppercase tracking-widest hover:text-gray-600 focus:outline-none active:text-gray-900 transition ease-in-out duration-150">+</button>
				      		</div>
				      	</div>
				      	<div class="bg-white divide-y divide-gray-200">

				      		<div v-for="invite in invites" v-bind:key="invite.id" @click="editInvite(invite)" class="cursor-pointer active:bg-gray-100">
							      <div class="px-6 py-4">
							      	<div class="flex justify-between">
								      	<div class="flex flex-wrap overflow-hidden">
								      		
									      	<div class="flex items-center">
									      		<div>
									          	<div class="text-sm leading-5 font-medium text-gray-900">
								              	<a class="hover:underline" @click.stop :href="'sms://' + invite.contact.phone">{{ invite.contact.name }}</a>
								            	</div>
									            <div v-if="dayjs(invite.expiration).isAfter(dayjs(), 'minute') && invite.accepted === null" class="text-sm leading-5 text-gray-500">
									              Expires {{ dayjs(invite.expiration).fromNow() }}
									            </div>
									            <div v-else-if="dayjs(invite.expiration).isAfter(dayjs(), 'minute') && invite.accepted !== null" class="text-sm leading-5 text-gray-500">
									              Locking response {{ dayjs(invite.expiration).fromNow() }}
									            </div>
									            <div v-else-if="!dayjs(invite.expiration).isAfter(dayjs(), 'minute') && invite.accepted === null" class="text-sm leading-5 text-red-500">
									            	Invitaton Expired
									            </div>
									            <div v-else-if="!dayjs(invite.expiration).isAfter(dayjs(), 'minute') && invite.accepted !== null" class="text-sm leading-5 text-gray-500">
									            	Response Locked
									            </div>
									          </div>
									        </div>
									        
									      </div>
									      <div class="my-auto">
									        <span v-if="invite.accepted === true" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
	                  				Accepted
	                				</span>
	                				<span v-if="invite.accepted === false" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
	                  				Declined
	                				</span>
	                				<span v-if="invite.accepted === null" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
	                  				No Response
	                				</span>
	                			</div>
									    </div>
								    </div>
								  </div>
								  <div v-if="!invites.length" class="flex-shrink-0">
								  	<div class="text-sm p-2 pl-6 font-medium text-gray-900">
								  		You have not invited anybody yet
								  	</div>
								  </div>

							  </div>
					  	</div>
					  </div>
					</div>
				</div>
			</div>
		</div>

		<!-- Send Invite -->
    <jet-dialog-modal :show="sendingInvite" @close="sendingInvite = false">
      <template #title>
        Send Invite
      </template>

      <template #content>
        <div class="col-span-6 sm:col-span-4">
	        <jet-label for="name" value="Name" />
	        <jet-input id="name" type="text" class="mt-1 block w-full capitalize" v-model="form.name" @focus.native="showContactOptions=true" @blur.native="showContactOptions=false" ref="name" placeholder="Full Name" autocapitalize="word" autocomplete="off" />
	        <jet-input-error :message="form.error('name')" class="mt-2" />
	        <jet-input-error :message="form.error('duplicate')" class="mt-2" />
        </div>

      	<transition
  				name="custom-classes-transition"
  				enter-active-class="transition ease-in duration-100 opacity-100"
  				leave-active-class="transition ease-in duration-100 opacity-0">
        	<div v-if="showContactOptions && contactOptions.length > 0" class="relative">
		        <div class="absolute mt-1 w-full rounded-md bg-white shadow-lg">
				      <ul tabindex="-1" role="listbox" aria-labelledby="listbox-label" aria-activedescendant="listbox-item-3" class="max-h-56 rounded-md py-1 text-base leading-6 shadow-xs overflow-auto focus:outline-none sm:text-sm sm:leading-5">
		
				        <li v-for="contactOption in contactOptions" v-bind:key="contactOption.id" @mousedown="chooseContact(contactOption)" id="listbox-item-0" role="option" class="cursor-pointer group hover:bg-indigo-600 cursor-default select-none relative py-2 pl-3 pr-3">
				        	<div class="flex justify-between flex-wrap">
				            <div class="text-sm font-semibold text-gray-900 group-hover:text-white">
				              {{ contactOption.name }}
				            </div>

				            <div class="text-sm text-gray-500 group-hover:text-white">
				              {{ prettyNumber(contactOption.phone) }}
				            </div>
				          </div>
				          
				        </li>

				      </ul>
				    </div>
			  	</div>
		  	</transition>

        <div class="col-span-6 sm:col-span-4 pt-4 pb-4">
	        <jet-label for="phone" value="Phone Number" />
	        <jet-phone-input id="phone" class="mt-1 block w-full" v-model="form.phone" ref="phone" placeholder="Phone Number" @keyup.enter.native="sendInvite" />
	        <jet-input-error :message="form.error('phone')" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4 pb-4">
        	<jet-label for="expiration_date" value="Expiration Date and Time" />
        	<div class="flex flex-wrap">
        		<div class="flex flex-auto">
		          <jet-input id="expiration_date" type="date" class="mt-1 block w-full" v-model="form.expiration_date" placeholder="mm/dd/yyyy" />
		        </div>
		        <div class="flex pl-2">
		        	<jet-time-picker id="expiration_time" v-model="form.expiration_time" />
		        </div>
		      </div>
		      <jet-input-error :message="form.error('expiration_date')" class="mt-2" />
		      <jet-input-error :message="form.error('expiration_time')" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
          <div class="flex">
            <div class="flex flex-auto">
              <p class="my-auto text-sm text-gray-600">
                Send Invite Notification
              </p>
            </div>
            <div class="flex">
              <t-toggle v-model="form.send_invite" />
            </div>
          </div>
          <p v-if="!form.send_invite" class="mt-1 text-sm text-gray-800">When this is disabled, you will be given a link to send them in order to respond to your invitation.</p>
        </div>

      </template>

      <template #footer>
        <jet-secondary-button @click.native="sendingInvite = false">
          Cancel
        </jet-secondary-button>

        <jet-button type="submit" class="ml-2" @click.native="sendInvite" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
          Send
        </jet-button>
      </template>
    </jet-dialog-modal>

    <!-- Edit Invite  -->
    <jet-dialog-modal :show="editingInvite" @close="editingInvite = false">
      <template #title>
        Edit Invite
      </template>

      <template #content>
        <div class="col-span-6 sm:col-span-4 pb-4">
	        <jet-label for="edit_name" value="Name" />
	        <jet-input id="edit_name" type="text" class="mt-1 block w-full capitalize" v-model="editInviteForm.name" ref="name" placeholder="Full Name" autocomplete="off" />
	        <jet-input-error :message="editInviteForm.error('name')" class="mt-2" />
	        <jet-input-error :message="editInviteForm.error('duplicate')" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4 pb-4">
	        <jet-label for="edit_phone" value="Phone Number" />
	        <jet-phone-input id="edit_phone" class="mt-1 block w-full bg-gray-200" v-model="editInviteForm.phone" ref="phone" placeholder="Phone Number" disabled />
        </div>

        <div class="col-span-6 sm:col-span-4 pb-4">
        	<jet-label for="edit_expiration_date" value="Expiration Date and Time" />
        	<div class="flex flex-wrap">
        		<div class="flex flex-auto">
		          <jet-input id="edit_expiration_date" type="date" class="mt-1 block w-full" v-model="editInviteForm.expiration_date" placeholder="mm/dd/yyyy" />
		        </div>
		        <div class="flex pl-2">
		        	<jet-time-picker id="edit_expiration_time" v-model="editInviteForm.expiration_time" />
		        </div>
		      </div>
		      <jet-input-error :message="editInviteForm.error('expiration_date')" class="mt-2" />
		      <jet-input-error :message="editInviteForm.error('expiration_time')" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
	        <jet-label value="Delete Invitation" />
	        <jet-danger-button @click.native="deleteInvite"> 
	        	Delete Invitation
	        </jet-danger-button>
	        <div class="max-w-xl text-sm text-gray-600">
              This person will no longer be able to see the event and respond to it. They will not receive a notification.
          </div>
        </div>

      </template>

      <template #footer>
        <jet-secondary-button @click.native="editingInvite = false">
          Cancel
        </jet-secondary-button>

        <jet-button type="submit" class="ml-2" @click.native="updateInvite" :class="{ 'opacity-25': editInviteForm.processing }" :disabled="editInviteForm.processing">
          Update
        </jet-button>
      </template>
    </jet-dialog-modal>

    <!-- Display Invite Link -->
    <jet-dialog-modal :show="displayLink" @close="displayLink = false">
      <template #title>
        Invitation Link
      </template>

      <template #content>
        Unique Invitation for {{ form.name }}:
        <div class="mt-2 mb-2 flex items-center text-sm leading-5 px-2 py-1 border border-gray-300 rounded-md bg-gray-100">
          <div class="text-gray-500">
            {{ invite_url }}
          </div>
        </div>

        <a :href="inviteMessage" class="inline-flex items-center px-3 py-1 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out fduration-150">Compose Message</a>


      </template>

      <template #footer>
        <jet-secondary-button @click.native="displayLink = false">
          Close
        </jet-secondary-button>
      </template>
    </jet-dialog-modal>

    <!-- Edit Event  -->
    <jet-dialog-modal :show="editingEvent" @close="editingEvent = false">
      <template #title>
        Edit Event
      </template>

      <template #content>
        <div class="col-span-6 sm:col-span-4 pb-4">
	        <jet-label for="event_name" value="Event Name" />
	        <jet-input id="event_name" type="text" class="mt-1 block w-full" v-model="editEventForm.name" ref="name" placeholder="Backyard BBQ, Wine Tasting" />
	        <jet-input-error :message="editEventForm.error('name')" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4 pb-4">
        	<jet-label for="start_date" :value="editEventForm.end_toggle ? 'Start Date and Time' : 'Date and Time'" />
        	<div class="flex flex-wrap">
        		<div class="flex flex-auto">
		          <jet-input id="start_date" type="date" class="mt-1 block w-full" v-model="editEventForm.start_date" placeholder="mm/dd/yyyy" />
		        </div>
		        <div class="flex pl-2">
		        	<jet-time-picker id="start_time" v-model="editEventForm.start_time" />
		        </div>
		      </div>
		      <jet-input-error :message="editEventForm.error('start_date')" class="mt-2" />
		      <jet-input-error :message="editEventForm.error('start_time')" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4 pb-4">
        	<div class="flex">
        		<div class="flex flex-auto">
        			<p class="mt-1 text-sm text-gray-600">
        				Add End Date and Time
        			</p>
        		</div>
        		<div class="flex">
        			<t-toggle v-model="editEventForm.end_toggle" />
        		</div>
        	</div>
        </div>

        <div v-if="editEventForm.end_toggle" class="col-span-6 sm:col-span-4 pb-4">
        	<jet-label for="end_date" value="End Date and Time" />
        	<div class="flex flex-wrap">
        		<div class="flex flex-auto">
		          <jet-input id="end_date" type="date" class="mt-1 block w-full" v-model="editEventForm.end_date" placeholder="mm/dd/yyyy" />
		        </div>
		        <div class="flex pl-2">
		        	<jet-time-picker id="end_time" v-model="editEventForm.end_time" />
		        </div>
		      </div>
		      <jet-input-error :message="editEventForm.error('end_date')" class="mt-2" />
		      <jet-input-error :message="editEventForm.error('end_time')" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4 pb-4">
	        <jet-label for="location" value="Location" />
	        <jet-input id="location" type="text" class="mt-1 block w-full" v-model="editEventForm.location" ref="location" placeholder="Home, Restaurant" />
	        <jet-input-error :message="editEventForm.error('location')" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4 pb-4">
	        <jet-label for="description" value="Description" />
	        <textarea id="description" rows="2" class="form-input rounded-md shadow-sm mt-1 block w-full" v-model="editEventForm.description" ref="description" placeholder="Description of event">
	        </textarea>
	        <jet-input-error :message="editEventForm.error('description')" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
	        <jet-label value="Delete Event" />
	        <jet-danger-button @click.native="deleteEvent"> 
	        	Delete Event
	        </jet-danger-button>
	        <div class="max-w-xl text-sm text-gray-600">
              This will delete the entire event. Anyone who has accepted an invitation will be notified of its cancellation.
          </div>
        </div>

      </template>

      <template #footer>
        <jet-secondary-button @click.native="editingEvent = false">
          Cancel
        </jet-secondary-button>

        <jet-button type="submit" class="ml-2" @click.native="updateEvent" :class="{ 'opacity-25': editEventForm.processing }" :disabled="editEventForm.processing">
          Update
        </jet-button>
      </template>
    </jet-dialog-modal>

	</app-layout>
</template>

<script>
  import ValidatePhoneNumber from '../../Mixins/ValidatePhoneNumber.js'
	import AppLayout from '@/Layouts/AppLayout'
	import JetActionMessage from '@/Jetstream/ActionMessage'
  import JetButton from '@/Jetstream/Button'
  import JetFormSection from '@/Jetstream/FormSection'
  import JetInput from '@/Jetstream/Input'
  import JetPhoneInput from '@/Jetstream/PhoneInput'
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
      JetPhoneInput,
      JetInputError,
      JetLabel,
      JetTimePicker,
      JetDialogModal,
      JetSecondaryButton,
      JetDangerButton,
    },
    mixins: [ValidatePhoneNumber],

    data: function() {
    	return {
    		dayjs: require('dayjs'),

    		sendingInvite: false,
    		contactOptions: [],
    		showContactOptions: false,
    		editingInvite: false,
    		editingEvent: false,
        displayLink: false,

        form: this.$inertia.form({
          name: '',
          phone: '',
          send_invite: true,
          expiration_date: '',
          expiration_time: ''
        }, {
          bag: 'sendInvite',
          resetOnSuccess: false,
        }),

        editInviteForm: this.$inertia.form({
        	id: '',
        	name: '',
        	phone: '',
          expiration_date: '',
          expiration_time: ''
        }, {
          bag: 'editInvite',
          resetOnSuccess: true,
        }),

        editEventForm: this.$inertia.form({
        	id: '',
        	name: '',
        	start_date: '',
          start_time: '',
          end_toggle: false,
         	end_date: '',
          end_time: '',
          location: '',
          description: ''
        }, {
          bag: 'editEvent',
          resetOnSuccess: true,
        }),

    	}
    },

    beforeMount() {
    	var customParseFormat = require('dayjs/plugin/customParseFormat');
    	var relativeTime = require('dayjs/plugin/relativeTime');
			this.dayjs.extend(customParseFormat);
			this.dayjs.extend(relativeTime);
    },

    mounted() {
    	this.form.expiration_date = this.dayjs(this.event.start_date).subtract(1, 'day').format('YYYY-MM-DD');
    	this.form.expiration_time = this.dayjs(this.event.start_date).format('h:mm A');
    },

    props: {
    	event: Object,
    	invites: Array,
      invite_url: {
        default: null
      }
    },

    watch: {
    	'form.name': function (newValue, oldValue) {
    		this.search();
    	},

    	'editEventForm.end_toggle': function (newValue, oldValue) {
    		if (newValue == true) {
    			if (this.editEventForm.start_date && this.editEventForm.start_time)
    				var date = this.dayjs(this.editEventForm.start_date + ' ' + this.editEventForm.start_time);
    			else if (this.editEventForm.start_date)
    				var date = this.dayjs(this.editEventForm.start_date, 'YYYY-MM-DD');
    			else if (this.editEventForm.start_time) 
    				var date = this.dayjs(this.editEventForm.start_time, 'h:mm A');
    			else
    				return;

    			date = date.add(1, 'hour').add(30, 'minute');

    			if (this.editEventForm.start_date)
    				this.editEventForm.end_date = date.format('YYYY-MM-DD');
    			this.editEventForm.end_time = date.format('h:mm A');

    		} else {
    			this.editEventForm.end_date = '';
    			this.editEventForm.end_time = '';
    		}
    	},

    	'editEventForm.start_date': function (newValue, oldValue) {
    		if ((!oldValue || oldValue == '') || this.dayjs(this.editEventForm.end_date, 'YYYY-MM-DD').isBefore(this.dayjs(newValue, 'YYYY-MM-DD'), 'day')) {
    			if (this.editEventForm.end_toggle)
    				this.editEventForm.end_date = newValue;
    		}
    	},
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

      inviteMessage() {
        return 'sms:/' + this.form.phone + '&body=' + this.invite_url;
      },
    },

    methods: {
    	// Send Invite 
      invite() {
	      this.form.name = '';
	      this.form.phone = '';
        this.form.send_invite = true;
	      this.sendingInvite = true;
	      
	      this.contactOptions = [];
	      this.showContactOptions = false;

	      setTimeout(() => {
          this.$refs.name.focus()
	      }, 250)
    	},

	    sendInvite() {
        this.form.post(route('event.invite.send', this.event), {
          preserveScroll: true,
        }).then(response => {
            if (!this.form.hasErrors()) {
              this.sendingInvite = false;

              if (!this.form.send_invite)
                this.displayLink = true;
          }
        })
	    },

	    search: _.debounce(function() {
	      axios.get(route('contacts.search'), { params: { q:this.form.name } })
        .then(response => {
        	this.contactOptions = response.data;
        })
        .catch(function (error) {
          console.log(error);
          //TODO: handle error
        });
	    }, 350),

	    chooseContact(contact) {
	    	this.form.name = contact.name;
	    	this.form.phone = this.prettyNumber(contact.phone);
	    	this.showContactOptions = false;
	    },
    	
	    // Edit Invite
	    editInvite(invite) {
	    	this.editingInvite = true;

	    	this.editInviteForm.id = invite.id;
	    	this.editInviteForm.name = invite.contact.name;
	    	this.editInviteForm.phone = this.prettyNumber(invite.contact.phone);
	    	this.editInviteForm.expiration_date = this.dayjs(invite.expiration).format('YYYY-MM-DD');
	    	this.editInviteForm.expiration_time = this.dayjs(invite.expiration).format('h:mm A');
	    },

	    updateInvite() {
	    	this.editInviteForm.put(route('event.invite.update', { event: this.event.id, invite: this.editInviteForm.id }), {
          preserveScroll: true,
        }).then(response => {
            if (!this.editInviteForm.hasErrors()) {
            this.editingInvite = false;
          }
        })
	    },

	    deleteInvite() {
	    	this.editInviteForm.delete(route('event.invite.delete', { event: this.event.id, invite: this.editInviteForm.id }), {
          preserveScroll: true,
        }).then(response => {
            if (!this.editInviteForm.hasErrors()) {
            this.editingInvite = false;
          }
        })
	    },

	    // Edit Event
	    editEvent() {
	    	this.editingEvent = true;

	    	this.editEventForm.id = this.event.id;
	    	this.editEventForm.name = this.event.name;
	    	this.editEventForm.start_date = this.dayjs(this.event.start_date).format('YYYY-MM-DD');
	    	this.editEventForm.start_time = this.dayjs(this.event.start_date).format('h:mm A');
	    	this.editEventForm.end_toggle = !!this.event.end_date;
	    	if (this.editEventForm.end_toggle) {
	    		this.editEventForm.end_date = this.dayjs(this.event.end_date).format('YYYY-MM-DD');
	    		this.editEventForm.end_time = this.dayjs(this.event.end_date).format('h:mm A');
	    	}
	    	this.editEventForm.location = this.event.location;
	    	this.editEventForm.description = this.event.description;

	    },

	    updateEvent() {
	    	this.editEventForm.put(route('event.update', { event: this.event.id }), {
          preserveScroll: true,
        }).then(response => {
            if (!this.editEventForm.hasErrors()) {
            this.editingEvent = false;
          }
        })
	    },

	    deleteEvent() {
	    	this.editEventForm.delete(route('event.delete', { event: this.event.id }), {
          preserveScroll: true,
        }).then(response => {
            if (!this.editEventForm.hasErrors()) {
            this.editingEvent = false;
          }
        })
	    },

    },
  }
</script>
