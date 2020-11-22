export default {
	methods: {
    	prettyNumber(number) {
    		var stripped = number.replace(/\D/g, '');
    		if (stripped.length > 10 && stripped.charAt(0) == 1)
    			stripped = stripped.substr(1);
    		var x = stripped.match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
        	return !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
    	}
	}
}