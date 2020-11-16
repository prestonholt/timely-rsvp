<template>
  <app-layout>
    <template #header>
    	<div class="flex justify-between">
    		<div class="my-auto">
          <h2 class="font-semibold text-xl text-gray-800 leading-tight">
              Events
          </h2>
        </div>
        <div>
          <jet-button-link :href="route('event.create')" >
            New Event
          </jet-button-link>
        </div>
      </div>
    </template>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
	    <div class="flex flex-col">
			  <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
			    <div class="py-2 align-middle inline-block min-w-full">
			      <div class="shadow overflow-hidden border-b border-gray-200">
				      <div class="min-w-full divide-y divide-gray-200">
				      	<div class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">My Events</div>
			      		<div class="bg-white divide-y divide-gray-200">

				      		<inertia-link v-for="event in events" v-bind:key="event.id" :href="route('event.edit', event)" class="block">
				      			<div class="flex justify-between active:bg-gray-100">
							      	<div class="pl-6 py-4">
								      	<div class="flex flex-wrap overflow-hidden">
									      	<div class="flex items-center">
									          <div class="">
									            <div class="text-sm leading-5 font-medium text-gray-900">
									              {{ event.name }}
									            </div>
									            <div class="text-sm leading-5 text-gray-500">
									              {{ dayjs(event.start_date).format('MMMM D, YYYY [at] h:mma') }}
									            </div>
									          </div>
									        </div>
									      </div>
								      </div>
								      <div class="my-auto">
									      <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
													<path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
												</svg>
									    </div>
								    </div>
								  </inertia-link>
								  <div v-if="!events.length" class="flex-shrink-0">
								  	<div class="text-sm p-2 pl-6 font-medium text-gray-900">
								  		You have no events. Create one now!
								  	</div>
									</div> 
								</div>

								<div v-if="invites.length" class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">My Invitations</div>
			      		<div v-if="invites.length" class="bg-white divide-y divide-gray-200">

				      		<inertia-link v-for="invite in invites" v-bind:key="invite.id" :href="route('event.view', invite.event)" class="block">
				      			<div class="flex justify-between active:bg-gray-100">
								      <div class="pl-6 py-4">
									      <div class="flex flex-wrap overflow-hidden">
									      	<div class="flex items-center">
									          <div class="flex-shrink-0 h-10 w-10">
	                    				<img class="h-10 w-10 rounded-full" :src="invite.event.user.profile_photo_url" :alt="invite.event.user.name">
	                  				</div>
									          <div class="ml-4">
									            <div class="text-sm leading-5 font-medium text-gray-900">
									              {{ invite.event.name }}
									            </div>
									            <div class="text-sm leading-5 text-gray-500">
									              {{ dayjs(invite.event.start_date).format('MMMM D, YYYY [at] h:mma') }}
									            </div>
									          </div>
									        </div>
									      </div>
									    </div>
									    <div class="my-auto">
									      <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
													<path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
												</svg>
									    </div>
									  </div>
								  </inertia-link>
								</div>

					  	</div>
					  </div>
					</div>
				</div>
			</div>
		</div>
  </app-layout>
</template>

<script>
  import AppLayout from '@/Layouts/AppLayout'
  import JetButtonLink from '@/Jetstream/ButtonLink'

  export default {
    components: {
      AppLayout,
      JetButtonLink,
    },

    props: {
    	events: Array,
    	invites: Array,
    },

    data: function() {
    	return {
    		dayjs: require('dayjs')
    	}
    },

    beforeMount() {
    	//var customParseFormat = require('dayjs/plugin/customParseFormat');
		//this.dayjs.extend(customParseFormat);
    },

  }
</script>
