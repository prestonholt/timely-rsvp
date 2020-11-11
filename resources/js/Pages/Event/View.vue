<template>
	<app-layout>
		<template #header>
			<div class="flex justify-between">
    		<div class="my-auto">
          <h2 class="font-semibold text-xl text-gray-800 leading-tight">
						Test
					</h2>
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
    	
    },

  }
</script>
