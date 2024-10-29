'use strict';

jQuery( function( $ ){

	let vm = this;
	let form = $( '#blpw_wizard_page' );
	let step = $( '#blpw_wizard_step' ).val();
	if( !form ){
		console.log( 'Form not initialized!' );
		return false;
	}
	this.curr_city = null;
	register_autocomplete_widget( form );
	create_city_table( form );
	get_cities();
	//Register add city event click
	form.find( '#blpw_add_city_btn' ).click( function( ev ){
		let admin_url = $( '#blpw_admin_url' ).val();
		let data = {
			action: 'add_city'
			,city: {
				id: vm.curr_city.id
				,name: vm.curr_city.name
				,phone: $( '#blpw_city_phone' ).val()
				,state_id: vm.curr_city.state_id
				,county_id: vm.curr_city.county_id
			}
		};
		$.ajax( {
			url: admin_url
			,type: 'POST'
			,data: data
		} )
		.done( function( response ){
			if( typeof( response ) == 'string' ){
				response = JSON.parse( response );
				if( response.success ){
					add_city_to_table( response );
				}else{
					if( 'message' in response ){
						alert( response.message );
					}else{
						alert( 'Error fill countries tables!' );
					}
				}
			}
			$( '#blpw_city_phone' ).val( '' );
			$( '#blpw_city' ).val( '' );
		} )
		.fail( function( jqXHR, textStatus ){
			console.log( textStatus );
		} );
	} );
	//-------------------------------------
	$( '#submit-cmb' ).click( next_click );
	if( step == 'company_info' ){
		show_block( 1 );
	}else{
		show_block( 0 );
	}

	/**
	 * Set next button action
	 */
	function next_click( ev )
	{
		ev.preventDefault();
		if( !step ){
			console.log( 'Not in wizard!' );
			return false;
		}
		switch( step ){
			case 'country':
				store_country();
				break;
			case 'company_info':
				store_company_info();
				break;
			case 'trusted_symbols':
				store_trusted_symbols();
				break;
			case 'block_0':
				store_block0();
				break;
			case 'block_1':
				store_block1();
				break;
			case 'block_2':
				store_block2();
				break;
			case 'last_projects':
				store_last_projects();
				break;
			case 'w2e':
				store_w2e();
				break;
			case 'testimonials':
				store_testimonials();
				break;
			case 'locations':
				store_locations();
				break;
		}
		//console.log( step );
	}


	/**
	 * Set country next
	 */
	function store_country()
	{
		let form = $( '#blpw_wizard_page' );
		let country_id = $( form, '#blpw_country' ).find( ':selected' ).val();
		let admin_url = $( '#blpw_admin_url' ).val();

		$.ajax( {
			url: admin_url
			,type: 'POST'
			,data: 'action=select_country&country_id=' + country_id
		} )
		.done( function( response ){
			if( typeof( response ) == 'string' ){
				if( response.slice( -1 ) != '}' ){
					response = response.slice( 0, -1 );
				}
				response = JSON.parse( response );
				if( response.success ){
					window.location.reload();
				}else{
					alert( 'Error fill countries tables!' );
				}
			}
		} )
		.fail( function( jqXHR, textStatus ){
			console.log( textStatus );
		} );
	}


	/**
	 * Parse and store company info
	 */
	function store_company_info()
	{
		let form = $( '#blpw_wizard_page' );
		let admin_url = $( '#blpw_admin_url' ).val();
		let data = {
			action: 'company_info'
			,company_name: ''
			,company_service: ''
			,company_phone: ''
			,company_address: ''
			,company_logo: ''
			,company_mobile: ''
			,company_social_facebook: ''
			,company_social_twitter: ''
			,company_social_instagram: ''
			,company_social_linkedin: ''
			,xml_sitemap: 'off'
			,html_sitemap: 'off'
		};
		data.company_name = $( form ).find( '#blpw_company_info_group_0_blpw_company_name' ).val();
		data.company_service = $( form ).find( '#blpw_company_info_group_0_blpw_company_service' ).val();
		data.company_phone = $( form ).find( '#blpw_company_info_group_0_blpw_company_phone' ).val();
		data.company_address = $( form ).find( '#blpw_company_info_group_0_blpw_company_address' ).val();

		data.company_logo = $( form ).find( '#blpw_company_info_group_0_blpw_company_logo' ).val();
		data.company_mobile = $( form ).find( '#blpw_company_info_group_0_blpw_company_mobile_logo' ).val();

		data.company_social_facebook = $( form ).find( '#blpw_company_info_group_0_blpw_company_social_facebook' ).val();
		data.company_social_twitter = $( form ).find( '#blpw_company_info_group_0_blpw_company_social_twitter' ).val();
		data.company_social_instagram = $( form ).find( '#blpw_company_info_group_0_blpw_company_social_instagram' ).val();
		data.company_social_linkedin = $( form ).find( '#blpw_company_info_group_0_blpw_company_social_linkedin' ).val();
		data.xml_sitemap = ( document.getElementById( 'blpw_company_info_group[0][blpw_xml_sitemap]' ).checked )?'on':'off';
		data.html_sitemap = ( document.getElementById( 'blpw_company_info_group[0][blpw_html_sitemap]' ).checked )?'on':'off';

		//Send data
		$.ajax( {
			url: admin_url
			,type: 'POST'
			,data: data
		} )
		.done( function( response ){
			if( typeof( response ) == 'string' ){
				if( response.slice( -1 ) != '}' ){
					response = response.slice( 0, -1 );
				}
				response = JSON.parse( response );
				if( response.success ){
					//window.location.reload();
					step = 'block_0';
					show_block( 2 );
				}else{
					alert( 'Error fill countries tables!' );
				}
			}
		} )
		.fail( function( jqXHR, textStatus ){
			console.log( textStatus );
		} );
	}


	function store_block0()
	{
		let form = $( '#blpw_wizard_page' );
		let admin_url = $( '#blpw_admin_url' ).val();
		let data = {
			action: 'block_0'
			,headline_1: ''
			,headline_2: ''
			,background: ''
			,bp1: ''
			,bp2: ''
			,bp3: ''
			,bp4: ''
			,bp5: ''
		};
		data.headline_1 = $( form ).find( '#blpw_block_0_group_0_blpw_block_0_headline_1' ).val();
		data.headline_2 = $( form ).find( '#blpw_block_0_group_0_blpw_block_0_headline_2' ).val();

		data.background = $( form ).find( '#blpw_block_0_group_0_blpw_block_0_background' ).val();

		for( let i = 1; i <= 5; i++ ){
			let key = 'bp' + ( i + '' );
			let val = form.find( '#blpw_block_0_group_0_blpw_block_0_bp_' + ( i + '' ) ).val();
			data[key] = val;
		}

		//Send data
		$.ajax( {
			url: admin_url
			,type: 'POST'
			,data: data
		} )
		.done( function( response ){
			if( typeof( response ) == 'string' ){
				if( response.slice( -1 ) != '}' ){
					response = response.slice( 0, -1 );
				}
				response = JSON.parse( response );
				if( response.success ){
					step = 'block_1';
					show_block( 3 );
				}else{
					alert( 'Error fill countries tables!' );
				}
			}
		} )
		.fail( function( jqXHR, textStatus ){
			console.log( textStatus );
		} );
	}


	function store_block1()
	{
		let form = $( '#blpw_wizard_page' );
		let admin_url = $( '#blpw_admin_url' ).val();
		let data = {
			action: 'block_1'
			,headline_1: ''
			,headline_2: ''
			,text_1: ''
			,text_2: ''
		};
		data.headline_1 = $( form ).find( '#blpw_block_1_group_0_blpw_block_1_headline_1' ).val();
		data.headline_2 = $( form ).find( '#blpw_block_1_group_0_blpw_block_1_headline_2' ).val();

		data.text_1 = $( form ).find( '#blpw_block_1_group_0_blpw_block_1_text_1' ).val();
		data.text_2 = $( form ).find( '#blpw_block_1_group_0_blpw_block_1_text_2' ).val();

		//Send data
		$.ajax( {
			url: admin_url
			,type: 'POST'
			,data: data
		} )
		.done( function( response ){
			if( typeof( response ) == 'string' ){
				if( response.slice( -1 ) != '}' ){
					response = response.slice( 0, -1 );
				}
				response = JSON.parse( response );
				if( response.success ){
					step = 'block_2';
					show_block( 4 );
				}else{
					alert( 'Error fill countries tables!' );
				}
			}
		} )
		.fail( function( jqXHR, textStatus ){
			console.log( textStatus );
		} );
	}


	function store_block2()
	{
		let form = $( '#blpw_wizard_page' );
		let admin_url = $( '#blpw_admin_url' ).val();
		let data = {
			action: 'block_2'
			,headline_1: ''
			,headline_2: ''
			,text_1: ''
			,text_2: ''
		};
		data.headline_1 = $( form ).find( '#blpw_block_2_group_0_blpw_block_2_headline_1' ).val();
		data.headline_2 = $( form ).find( '#blpw_block_2_group_0_blpw_block_2_headline_2' ).val();

		data.text_1 = $( form ).find( '#blpw_block_2_group_0_blpw_block_2_text_1' ).val();
		data.text_2 = $( form ).find( '#blpw_block_2_group_0_blpw_block_2_text_2' ).val();

		//Send data
		$.ajax( {
			url: admin_url
			,type: 'POST'
			,data: data
		} )
		.done( function( response ){
			if( typeof( response ) == 'string' ){
				if( response.slice( -1 ) != '}' ){
					response = response.slice( 0, -1 );
				}
				response = JSON.parse( response );
				if( response.success ){
					step = 'trusted_symbols';
					show_block( 5 );
				}else{
					alert( 'Error fill countries tables!' );
				}
			}
		} )
		.fail( function( jqXHR, textStatus ){
			console.log( textStatus );
		} );
	}


	function store_trusted_symbols()
	{
		let form = $( '#blpw_wizard_page' );
		let admin_url = $( '#blpw_admin_url' ).val();
		let data = {
			action: 'trusted_symbols'
			,ts1: ''
			,ts2: ''
			,ts3: ''
			,ts4: ''
			,ts5: ''
		};

		for( let i = 1; i <= 5; i++ ){
			let key = 'ts' + ( i + '' );
			let val = form.find( '#blpw_ts_group_0_blpw_block_0_ts_' + ( i + '' ) ).val();
			data[key] = val;
		}

		//Send data
		$.ajax( {
			url: admin_url
			,type: 'POST'
			,data: data
		} )
		.done( function( response ){
			if( typeof( response ) == 'string' ){
				if( response.slice( -1 ) != '}' ){
					response = response.slice( 0, -1 );
				}
				response = JSON.parse( response );
				if( response.success ){
					step = 'last_projects';
					show_block( 6 );
				}else{
					alert( 'Error fill countries tables!' );
				}
			}
		} )
		.fail( function( jqXHR, textStatus ){
			console.log( textStatus );
		} );
	}


	function store_last_projects()
	{
		let form = $( '#blpw_wizard_page' );
		let admin_url = $( '#blpw_admin_url' ).val();
		let data = {
			action: 'last_projects'
			,headline: ''
			,pic1: ''
			,pic2: ''
			,pic3: ''
			,pic4: ''
			,pic5: ''
		};

		data.headline = form.find( '#blpw_last_projects_group_0_blpw_block_last_projects_headline' ).val();

		for( let i = 1; i <= 5; i++ ){
			let key = 'pic' + ( i + '' );
			let val = form.find( '#blpw_last_projects_group_0_blpw_last_projects_pic_' + ( i + '' ) ).val();
			data[key] = val;
		}

		//Send data
		$.ajax( {
			url: admin_url
			,type: 'POST'
			,data: data
		} )
		.done( function( response ){
			if( typeof( response ) == 'string' ){
				if( response.slice( -1 ) != '}' ){
					response = response.slice( 0, -1 );
				}
				response = JSON.parse( response );
				if( response.success ){
					step = 'w2e';
					show_block( 7 );
				}else{
					alert( 'Error fill countries tables!' );
				}
			}
		} )
		.fail( function( jqXHR, textStatus ){
			console.log( textStatus );
		} );
	}


	function store_w2e()
	{
		let form = $( '#blpw_wizard_page' );
		let admin_url = $( '#blpw_admin_url' ).val();
		let data = {
			action: 'w2e'
			,photo: ''
			,bp1: ''
			,bp2: ''
			,bp3: ''
			,bp4: ''
			,bp5: ''
		};

		data.photo = form.find( '#blpw_w2e_group_0_blpw_w2e_photo' ).val();

		for( let i = 1; i <= 5; i++ ){
			let key = 'bp' + ( i + '' );
			let val = form.find( '#blpw_w2e_group_0_blpw_w2e_bp_' + ( i + '' ) ).val();
			data[key] = val;
		}

		//Send data
		$.ajax( {
			url: admin_url
			,type: 'POST'
			,data: data
		} )
		.done( function( response ){
			if( typeof( response ) == 'string' ){
				if( response.slice( -1 ) != '}' ){
					response = response.slice( 0, -1 );
				}
				response = JSON.parse( response );
				if( response.success ){
					step = 'testimonials';
					show_block( 8 );
				}else{
					alert( 'Error fill countries tables!' );
				}
			}
		} )
		.fail( function( jqXHR, textStatus ){
			console.log( textStatus );
		} );
	}


	function store_testimonials()
	{
		let form = $( '#blpw_wizard_page' );
		let admin_url = $( '#blpw_admin_url' ).val();
		let data = {
			action: 'testimonials'
			,testimonial1: ''
			,testimonial2: ''
			,testimonial3: ''
			,testimonial4: ''
			,testimonial5: ''
			,testimonial6: ''
			,testimonial7: ''
			,testimonial8: ''
			,testimonial9: ''
			,testimonial10: ''
		};
		console.log( tinymce, tinyMCE );
		for( let i = 1; i <= 10; i++ ){
			let key = 'testimonial' + ( i + '' );
			let val = tinymce.get( 'blpw_testimonials_group_0_blpw_testimonials_' + ( i + '' ) ).getContent();
			data[key] = val;
		}

		//Send data
		$.ajax( {
			url: admin_url
			,type: 'POST'
			,data: data
		} )
		.done( function( response ){
			if( typeof( response ) == 'string' ){
				if( response.slice( -1 ) != '}' ){
					response = response.slice( 0, -1 );
				}
				response = JSON.parse( response );
				if( response.success ){
					step = 'locations';
					show_block( 9 );
				}else{
					alert( 'Error fill countries tables!' );
				}
			}
		} )
		.fail( function( jqXHR, textStatus ){
			console.log( textStatus );
		} );
	}


	function store_locations()
	{
		let form = $( '#blpw_wizard_page' );
		let admin_url = $( '#blpw_admin_url' ).val();
		let data = {
			action: 'locations'
		};

		//Send data
		$.ajax( {
			url: admin_url
			,type: 'POST'
			,data: data
		} )
		.done( function( response ){
			if( typeof( response ) == 'string' ){
				if( response.slice( -1 ) != '}' ){
					response = response.slice( 0, -1 );
				}
				response = JSON.parse( response );
				if( response.success ){
					step = 'stop';
					show_block( 0 );
				}else{
					if( 'message' in response ){
						alert( response.message );
					}else{
						alert( 'Error fill countries tables!' );
					}
				}
			}
		} )
		.fail( function( jqXHR, textStatus ){
			console.log( textStatus );
		} );
	}


	/**
	 * Show block by number and hide other
	 * @param  {Number} block Number of the wizard's block
	 * @return {None}
	 */
	function show_block( block = 0 )
	{
		for( let i = 1; i <= 9; i++ ){
			if( i == block ){
				change_visible_block( i, true );
				continue;
			}
			change_visible_block( i, false );
		}
	}


	/**
	 * Change visible of wigard's block
	 * @param  {Number}  block   Number of the wizard's block
	 * @param  {Boolean} visible Show or hide block
	 * @return {None}
	 */
	function change_visible_block( block = 0, visible = false )
	{
		let form = $( '#blpw_wizard_page' );
		//cmb2-id-blpw-block-1-group
		switch( block ){
			case 1: //Company info
				if( visible ){
					form.find( '.cmb2-id-blpw-company-info-group' ).show();
				}else{
					form.find( '.cmb2-id-blpw-company-info-group' ).hide();
				}
				break;
			case 2: //Block 0
				if( visible ){
					form.find( '.cmb2-id-blpw-block-0-group' ).show();
				}else{
					form.find( '.cmb2-id-blpw-block-0-group' ).hide();
				}
				break;
			case 3: //Block 1
				if( visible ){
					form.find( '.cmb2-id-blpw-block-1-group' ).show();
				}else{
					form.find( '.cmb2-id-blpw-block-1-group' ).hide();
				}
				break;
			case 4: //Block 2
				if( visible ){
					form.find( '.cmb2-id-blpw-block-2-group' ).show();
				}else{
					form.find( '.cmb2-id-blpw-block-2-group' ).hide();
				}
				break;
			case 5: //Trusted symbols
				if( visible ){
					form.find( '.cmb2-id-blpw-ts-group' ).show();
				}else{
					form.find( '.cmb2-id-blpw-ts-group' ).hide();
				}
				break;
			case 6: //Last projects
				if( visible ){
					form.find( '.cmb2-id-blpw-last-projects-group' ).show();
				}else{
					form.find( '.cmb2-id-blpw-last-projects-group' ).hide();
				}
				break;
			case 7: //W2E
				if( visible ){
					form.find( '.cmb2-id-blpw-w2e-group' ).show();
				}else{
					form.find( '.cmb2-id-blpw-w2e-group' ).hide();
				}
				break;
			case 8: //Testimonials
				if( visible ){
					form.find( '.cmb2-id-blpw-testimonials-group' ).show();
				}else{
					form.find( '.cmb2-id-blpw-testimonials-group' ).hide();
				}
				break;
			case 9: //Locations
				if( visible ){
					form.find( '.cmb2-id-blpw-locations-group' ).show();
					form.find( '.row.cmb2GridRow' ).show();
					form.find( '#blpw-table-cities' ).show();
					//Hide submit
					form.find( '.submit' ).hide();
				}else{
					form.find( '.cmb2-id-blpw-locations-group' ).hide();
					form.find( '.row.cmb2GridRow' ).hide();
					form.find( '#blpw-table-cities' ).hide();
					//show submit
					form.find( '.submit' ).show();
				}
				break;
		}
	}


	function register_autocomplete_widget( form )
	{
		let input = form.find( '#blpw_city' );
		let admin_url = $( '#blpw_admin_url' ).val();

		input.easyAutocomplete( {
			url: function(){
				return admin_url;
			}
			,getValue: function( el ){
				return el.name + ' (' + el.state_name + ',' + el.county_name + ')';
			}
			,ajaxSettings: {
				dataType: 'json'
				,method: 'POST'
				,data: {
					action: 'city_complete'
				}
			}
			,preparePostData: function( data ){
				data.phrase = input.val();
				return data;
			}
			,list: {
				onChooseEvent: function(){
					let city = input.getSelectedItemData();
					if( city && 'phone' in city ){
						$( '#blpw_city_phone' ).val( city.phone );
					}
					vm.curr_city = city;
				}
			}
			,requestDelay: 500
			,minCharNumber: 3
		} );
		//Set some correction of style
		input.css( 'height', '30px' );
		input.css( 'border-color', '#7e8993' );
	}


	function create_city_table( form )
	{
		let form_wrapper = form.find( '.form-table' );
		let table = $( '<table/>', {
			width: '100%'
			,id: 'blpw-table-cities'
			,class: 'phone-table'
		} ).appendTo( form_wrapper );
		let thead = $( '<thead/>' );
		let tr = $( '<tr/>' );
		$( '<th/>', {
			html: 'City'
		} ).appendTo( tr );
		$( '<th/>', {
			html: 'Phone'
		} ).appendTo( tr );
		$( '<th/>', {
			html: 'State'
		} ).appendTo( tr );
		$( '<th/>', {
			html: 'County'
		} ).appendTo( tr );
		$( '<th/>', {
			html: '&nbsp;'
		} ).appendTo( tr );
		tr.appendTo( thead );
		thead.appendTo( table );
		$( '<tbody/>' ).appendTo( table );
	}


	function add_city_to_table( data = {} )
	{
		let table = $( '#blpw-table-cities' );
		if( !table ) return;
		let tbody = table.children( 'tbody' );
		let tr = $( '<tr/>' );
		let td_one = $( '<td />', {
			html: data.name
		} );
		$( '<input />', {
			type: 'hidden'
			,name: 'identifier'
			,value: data.id
		} ).appendTo( td_one );
		td_one.appendTo( tr );
		$( '<td />', {
			html: data.phone
		} ).appendTo( tr );
		$( '<td />', {
			html: data.state
		} ).appendTo( tr );
		$( '<td />', {
			html: data.county
		} ).appendTo( tr );
		$( '<button />', {
			type: 'button'
			,class: 'button button-danger'
			,html: 'Remove'
			,click: function( ev ){
				let tr = $( this ).parent();
				let id = tr.find( 'input[name="identifier"]' ).val();
				let admin_url = $( '#blpw_admin_url' ).val();
				let data = {
					action: 'remove_city'
					,id: id
				};
				$.ajax( {
					url: admin_url
					,type: 'POST'
					,data: data
				} )
				.done( function( response ){
					if( typeof( response ) == 'string' ){
						response = JSON.parse( response );
						if( response.success ){
							tr.remove();
						}else{
							if( 'message' in response ){
								alert( response.message );
							}else{
								alert( 'Error fill countries tables!' );
							}
						}
					}
				} )
				.fail( function( jqXHR, textStatus ){
					console.log( textStatus );
				} );
			}
		} ).appendTo( tr );
		tr.appendTo( tbody );
	}


	/**
	 * Get selected cities and render them
	 */
	function get_cities()
	{
		let admin_url = $( '#blpw_admin_url' ).val();
		let data = {action: 'get_cities'};
		$.ajax( {
			url: admin_url
			,type: 'POST'
			,data: data
		} )
		.done( function( response ){
			if( typeof( response ) == 'string' ){
				response = JSON.parse( response );
				if( response.success ){
					let cities = response.cities;
					cities.forEach( function( city ){
						add_city_to_table( city );
					} );
				}else{
					if( 'message' in response ){
						alert( response.message );
					}else{
						alert( 'Error fill countries tables!' );
					}
				}
			}
		} )
		.fail( function( jqXHR, textStatus ){
			console.log( textStatus );
		} );
	}
} );