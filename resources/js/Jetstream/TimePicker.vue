<template>
    <div class="form-input rounded-md shadow-sm mt-1 block w-full">
	    <select class="bg-transparent appearance-none outline-none" v-model="selectedOption" @input="$emit('input', $event.target.value)">
	    	<option v-for="option in options" :value="option"> {{ option }}</option>
	    </select>
	    
	  </div>
	</div>
</template>

<script>
    export default {
        props: ['value'],

        data() {
        	return {
        		selectedOption: null
        	}
        },

        methods: {
            focus() {
                this.$refs.input.focus()
            }
        },

        watch: {
        	value: function (newValue) {
        		this.selectedOption = newValue;
        	}
        },

        computed: {
        	options() {
        		var times = [];
				var periods = ['AM', 'PM'];
					for (var period = 0; period < periods.length; period++) {
				  	var reset = false;
						for (var hour = 12; hour <= 12; hour++) {
				    	if (hour == 12 && reset) {
				      	break;
				      }
				    	for (var minute = 0; minute <= 45; minute = minute + 15) {
				      	times.push(hour + ':' + ((minute == 0) ? '00' : minute) + ' ' + periods[period]);
				      }
				    	if (hour == 12 && !reset) {
				      	reset = true;
				        hour = 0;
				      }
				    }
				}
				return times;
        	}
        },

        mounted() {
        	this.selectedOption = this.value;
        },
    }
</script>

