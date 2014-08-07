jQuery(document).ready(function($) {
	$("#phila-news-start-date").datepicker({
		dateFormat: 'MM dd, yy',
		setDate: '',
		onClose: function(selectedDate){
			$("#phila-news-end-date").datepicker('option','minDate',selectedDate);
			console.log('booyah');
		}
	});
	$( "#phila-news-end-date" ).datepicker({
        dateFormat: 'MM dd, yy',
		setDate: '',
        onClose: function( selectedDate ){
            $( "#phila-news-start-date" ).datepicker( 'option', 'maxDate', selectedDate );
        }
    });
});