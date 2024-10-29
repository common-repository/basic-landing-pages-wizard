'use strict';

//1. Set the masks to the phone fields

jQuery( function( $ ){

	//Find controls
	let company_phone_control = $( '#blpw_company_info_group_0_blpw_company_phone' );
	let city_phone_control = $( '#blpw_city_phone' );

	//Set events
	company_phone_control.keyup( function( /*evt*/ ){
		let phone = $( this ).val();
		$( this ).val( format_phone_usa( phone, 10 ) );
	} );


	city_phone_control.keyup( function( /*evt*/ ){
		let phone = $( this ).val();
		$( this ).val( format_phone_usa( phone, 10 ) );
	} );


	/**
	 * Set american phone format
	 * @param  {String} input Text from phone input
	 * @return {String}       Formatted string for phone
	 */
	function format_phone_usa( input = '', max_length = 10 )
	{
		if( input.length == 0 ){
			return '';
		}
		//Minimum for american phone is 10 digits
		if( max_length < 10 ){
			return input;
		}
		//Filter for non-digits
		let phone = input.replace( /\D/g, '' );
		//Trim to 10 characters
		phone = phone.substring( 0, max_length );

		let ln = phone.length;
		if( ln < 4 ){
			phone = phone;
		}else if( ln < 7 ){
			phone = phone.substring( 0, 3 ) + '-' + phone.substring( 3, 6 );
		}else{
			phone = phone.substring( 0, 3 ) + '-' + phone.substring( 3, 6 ) + '-' + phone.substring( 6, max_length );
		}

		return phone;
	}
} );