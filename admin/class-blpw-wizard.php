<?php

/**
 * Wizard with forms
 */

use WeDevs\ORM\WP\Country as Countries;
use WeDevs\ORM\WP\City as Cities;
use Cmb2Grid\Grid\Cmb2Grid as Grid;

class BLPW_Wizard {

	private $menu_cmb;
	private $country;
	private $allow_html;
	private $template_id;


	function __construct()
	{
		$this->country = BLPW_Options::get_option( 'blpw_country', '', '0', 'wp' );
		//Register POST Actions
		//For autocomplete
		add_action( 'wp_ajax_city_complete', [$this, 'city_complete'] );
		add_action( 'wp_ajax_add_city', [$this, 'add_city'] );
		add_action( 'wp_ajax_get_cities', [$this, 'get_cities'] );
		add_action( 'wp_ajax_remove_city', [$this, 'remove_city'] );
		//Store data
		add_action( 'wp_ajax_select_country', [$this, 'store_selected_country'] );
		add_action( 'wp_ajax_company_info', [$this, 'store_company_info'] );
		add_action( 'wp_ajax_block_0', [$this, 'store_block_0'] );
		add_action( 'wp_ajax_block_1', [$this, 'store_block_1'] );
		add_action( 'wp_ajax_block_2', [$this, 'store_block_2'] );
		add_action( 'wp_ajax_trusted_symbols', [$this, 'store_trusted_symbols'] );
		add_action( 'wp_ajax_last_projects', [$this, 'store_last_projects'] );
		add_action( 'wp_ajax_w2e', [$this, 'store_w2e'] );
		add_action( 'wp_ajax_testimonials', [$this, 'store_testimonials'] );
		//Allowed html tags for sanitization
		$this->allow_html = [
			'a' => [
				'href' => [],
				'title' => []
			],
			'br' => [],
			'em' => [],
			'strong' => [],
			'img' => [],
			'ul' => [],
			'li' => [],
			'code' => [],
			'ol' => [],
			'ins' => [],
			'del' => [],
			'p' => [],
			'blockquote' => []
		];
		//Template id for update company service
	}


	private function next_dialog()
	{
		if( $this->country == '0' ){
			//None country selected
			if( !isset( $_POST['country_id'] ) ){
				//Show country select form
				$this->register_countries_form( $this->menu_cmb );
				return true;
			}
		}
		$this->register_company_info_form( $this->menu_cmb );
		$this->register_block_0_form( $this->menu_cmb );
		$this->register_block_form( $this->menu_cmb, '1' );
		$this->register_block_form( $this->menu_cmb, '2' );
		$this->register_trusted_symbols_form( $this->menu_cmb );
		$this->register_last_projects_form( $this->menu_cmb );
		$this->register_w2e_form( $this->menu_cmb );
		$this->register_testimonials_form( $this->menu_cmb );
		$this->register_locations_form( $this->menu_cmb );
	}


	public function register_menu()
	{
		$this->menu_cmb = new_cmb2_box( [
			'id' => 'blpw_wizard_page',
			'title' => esc_html__( 'Basic Landing Pages Wizard', 'blpw' ),
			'object_types' => ['options-page'],
			'option_key' => 'blpw_wizard',
			'menu_title' => esc_html__( 'Landing Pages Wizard', 'blpw' ),
			'save_fields' => false,
			'save_button' => esc_html__( 'Next', 'blpw' )
		] );
		$this->next_dialog();
	}


	private function register_countries_form( $cmb )
	{
		$raw = Countries::get();
		$countries = [];
		foreach( $raw as $country ){
			$countries[strval( $country->id )] = $country->name;
		}
		//Show select dialog if countries more than one
		//else set country automatic
		if( count( $countries ) == 1 ){
			reset( $countries );
			$country_id = key( $countries );
			update_option( 'blpw_country', $country_id );
			$this->country = intval( $country_id );
			$this->next_dialog();
			return;
		}

		//Hidden data
		$cmb->add_field( [
			'id' => 'blpw_wizard_step',
			'type' => 'hidden',
			'default' => 'country'
		] );

		$admin_url = admin_url( 'admin-ajax.php' );
		$cmb->add_field( [
			'id' => 'blpw_admin_url',
			'type' => 'hidden',
			'default' => $admin_url
		] );
		//--------------------------------------------
		$cmb->add_field( [
			'name' => esc_html__( 'Countries', 'blpw' ),
			'id' => 'blpw_countries_form_title',
			'type' => 'title'
		] );
		//Countries select
		$cmb->add_field( [
			'name' => esc_html__( 'Country', 'blpw' ),
			'desc' => esc_html__( 'Select country first', 'blpw' ),
			'id' => 'blpw_country',
			'type' => 'select',
			'show_option_none' => true,
			'options' => $countries
		] );
	}


	/**
	 * Store selected country on country form in wizard
	 * @param  
	 * @return boolean        true/false
	 */
	public function store_selected_country()
	{
		$country_id = intval( $_POST['country_id'] );
		if( $country_id == 0 ){
			echo json_encode( ['success' => false] );
			return false;
		}
		update_option( 'blpw_country', strval( $country_id ) );
		echo json_encode( ['success' => true] );
		return true;
	}


	/**
	 * Register company info form
	 * @param  [object] $cmb CMB2 Handler
	 * @return None
	 */
	private function register_company_info_form( $cmb )
	{
		//------------------ Load data --------------
		$data = get_option( 'blpw_company_info_block', false );
		//Service name
		$service_name = '';
		$tpl_args = [
			'post_type' => 'blpw-template',
			'posts_per_page' => -1,
			'post_status' => 'publish'
		];
		$tpls_obj = new WP_Query( $tpl_args );
		if( $tpls_obj->have_posts() ){
			$tpls_obj->the_post();
			$service_name = $tpls_obj->post->post_title;
			$this->template_id = $tpls_obj->post->ID;
		}
		wp_reset_postdata();
		//Hidden data
		$cmb->add_field( [
			'id' => 'blpw_wizard_step',
			'type' => 'hidden',
			'default' => 'company_info'
		] );

		$admin_url = admin_url( 'admin-ajax.php' );
		$cmb->add_field( [
			'id' => 'blpw_admin_url',
			'type' => 'hidden',
			'default' => $admin_url
		] );
		//--------------------------------------------
		$cmb_group = $cmb->add_field( [
			'id' => "blpw_company_info_group",
			'desc' => esc_html__( 'General information of company', 'blpw' ),
			'repeatable' => false,
			'options' => [
				'group_title' => esc_html__( "Company Info", 'blpw' ),
				'closed' => false
			],
			'type' => 'group'
		] );
		//----------- Form fields --------------------
		$cmb->add_group_field( $cmb_group, [
			'name' => esc_html__( 'Company name', 'blpw' ),
			'id' => 'blpw_company_name',
			'type' => 'text',
			'default' => ($data != false)?esc_attr( $data['company_name'] ):'',
			'attributes' => [
				'required' => 'required'
			]
		] );
		$cmb->add_group_field( $cmb_group, [
			'name' => esc_html__( 'Service name', 'blpw' ),
			'desc' => 'The firts part of the landing page title.<br/>The created pages will remain unchanged!',
			'id' => 'blpw_company_service',
			'type' => 'text',
			'default' => esc_attr( $service_name ),
			'attributes' => [
				'required' => 'required'
			]
		] );
		$cmb->add_group_field( $cmb_group, [
			'name' => esc_html__( 'Company address', 'blpw' ),
			'id' => 'blpw_company_address',
			'type' => 'textarea_small',
			'default' => ($data != false)?esc_textarea( $data['company_address'] ):'',
			'attributes' => [
				'required' => 'required'
			]
		] );
		$cmb->add_group_field( $cmb_group, [
			'name' => esc_html__( 'Company phone', 'blpw' ),
			'id' => 'blpw_company_phone',
			'default' => ($data != false)?esc_attr( $data['company_phone'] ):'',
			'type' => 'text',
			'attributes' => [
				'required' => 'required',
				'placeholder' => '000-000-0000'
			]
		] );
		//Logo and mobile logo
		$cmb->add_group_field( $cmb_group, [
			'name' => esc_html__( 'Company logo', 'blpw' ),
			'id' => 'blpw_company_logo',
			'type' => 'file',
			'options' => [
				'url' => false, // Hide the text input for the url
			],
			'text' => [
				'add_upload_file_text' => esc_html__( 'Add Company Logo Image', 'blpw' ) // Change upload button text. Default: "Add or Upload File"
			],
			'query_args' => [
				'type' => [
					'image/gif',
					'image/jpeg',
					'image/png',
				],
			],
			'preview_size' => 'small', // Image size to use when previewing in the admin.
			'default' => ($data != false)?esc_attr( $data['company_logo'] ):''
		] );

		$cmb->add_group_field( $cmb_group, [
			'name' => esc_html__( 'Company Mobile logo', 'blpw' ),
			'id' => 'blpw_company_mobile_logo',
			'type' => 'file',
			'options' => [
				'url' => false, // Hide the text input for the url
			],
			'text' => [
				'add_upload_file_text' => esc_html__( 'Add Mobile Logo Image', 'blpw' ) // Change upload button text. Default: "Add or Upload File"
			],
			'query_args' => [
				'type' => [
					'image/gif',
					'image/jpeg',
					'image/png',
				],
			],
			'preview_size' => 'small', // Image size to use when previewing in the admin.
			'default' => ($data != false)?esc_attr( $data['company_mobile_logo'] ):''
		] );
		//Social Links
		$cmb->add_group_field( $cmb_group, [
			'name' => esc_html__( 'Social links', 'blpw' ),
			'id' => 'blpw_company_soc_links',
			'type' => 'title'
		] );
		$cmb->add_group_field( $cmb_group, [
			'name' => esc_html__( 'Facebook', 'blpw' ),
			'id' => 'blpw_company_social_facebook',
			'default' => ($data != false)?esc_attr( $data['company_social_facebook'] ):'',
			'type' => 'text'
		] );
		$cmb->add_group_field( $cmb_group, [
			'name' => esc_html__( 'Twitter', 'blpw' ),
			'id' => 'blpw_company_social_twitter',
			'default' => ($data != false)?esc_attr( $data['company_social_twitter'] ):'',
			'type' => 'text'
		] );
		$cmb->add_group_field( $cmb_group, [
			'name' => esc_html__( 'Instagram', 'blpw' ),
			'default' => ($data != false)?esc_attr( $data['company_social_instagram'] ):'',
			'id' => 'blpw_company_social_instagram',
			'type' => 'text'
		] );
		$cmb->add_group_field( $cmb_group, [
			'name' => esc_html__( 'Linkedin', 'blpw' ),
			'default' => ($data != false)?esc_attr( $data['company_social_linkedin'] ):'',
			'id' => 'blpw_company_social_linkedin',
			'type' => 'text'
		] );
		//--------------------------------------------
		//--------------- Some settings --------------
		$cmb->add_group_field( $cmb_group, [
			'name' => esc_html__( 'Sitemaps', 'blpw' ),
			'id' => 'blpw_sitemaps',
			'type' => 'title'
		] );
		$cmb->add_group_field( $cmb_group, [
			'name' => esc_html__( 'XML Sitemap', 'blpw' ),
			'desc' => esc_html__( 'Enamble XML Landing Pages Sitemap', 'blpw' ),
			'id' => 'blpw_xml_sitemap',
			'type' => 'switch',
			'default' => ($data != false && isset( $data['xml_sitemap'] ))?esc_attr( $data['xml_sitemap'] ):'off'
		] );
		$cmb->add_group_field( $cmb_group, [
			'name' => esc_html__( 'HTML Sitemap', 'blpw' ),
			'desc' => esc_html__( 'Enamble HTML Landing Pages Sitemap', 'blpw' ),
			'id' => 'blpw_html_sitemap',
			'type' => 'switch',
			'default' => ($data != false && isset( $data['html_sitemap'] ))?esc_attr( $data['html_sitemap'] ):'off'
		] );
		//--------------------------------------------
	}


	public function store_company_info()
	{
		//Create or update options
		$data = [
			'company_name' => (isset( $_POST['company_name'] ))?sanitize_text_field( $_POST['company_name'] ) : '',
			'company_phone' => (isset( $_POST['company_phone'] ))?sanitize_text_field( $_POST['company_phone'] ) : '',
			'company_address' => (isset( $_POST['company_address'] ))?sanitize_textarea_field( $_POST['company_address'] ) : '',
			'company_logo' => (isset( $_POST['company_logo'] ))?sanitize_text_field( $_POST['company_logo'] ) : '',
			'company_mobile_logo' => (isset( $_POST['company_mobile'] ))?sanitize_text_field( $_POST['company_mobile'] ) : '',
			'company_social_facebook' => (isset( $_POST['company_social_facebook'] ))?sanitize_text_field( $_POST['company_social_facebook'] ) : '',
			'company_social_twitter' => (isset( $_POST['company_social_twitter'] ))?sanitize_text_field( $_POST['company_social_twitter'] ) : '',
			'company_social_instagram' => (isset( $_POST['company_social_instagram'] ))?sanitize_text_field( $_POST['company_social_instagram'] ) : '',
			'company_social_linkedin' => (isset( $_POST['company_social_linkedin'] ))?sanitize_text_field( $_POST['company_social_linkedin'] ) : '',
			'xml_sitemap' => (isset( $_POST['xml_sitemap'] ))?sanitize_text_field( $_POST['xml_sitemap'] ) : '',
			'html_sitemap' => (isset( $_POST['html_sitemap'] ))?sanitize_text_field( $_POST['html_sitemap'] ) : ''
		];
		update_option( 'blpw_company_info_block', $data );
		//Update template title
		$tpl_args = [
			'post_type' => 'blpw-template',
			'posts_per_page' => -1,
			'post_status' => 'publish',
			'post_title' => (isset( $_POST['company_service'] ))?sanitize_text_field( $_POST['company_service'] ):'Basic Template'
		];
		if( $this->template_id != -1 ){
			$tpl_args['ID'] = $this->template_id;
		}
		wp_update_post( $tpl_args, true );
		echo json_encode( ['success' => true] );
		return true;
	}



	private function register_trusted_symbols_form( $cmb )
	{
		//Read options
		$data = get_option( 'blpw_trusted_symbols', false );
		//--------------------------------------------
		$cmb_group = $cmb->add_field( [
			'id' => "blpw_ts_group",
			'desc' => esc_html__( "Trusted symbols", 'blpw' ),
			'repeatable' => false,
			'options' => [
				'group_title' => esc_html__( "Trusted symbols", 'blpw' ),
				'closed' => false
			],
			'type' => 'group'
		] );
		//----------- Form fields --------------------
		//----------Trusted symbols ------------------
		for( $i = 1; $i <= 5; $i++ ){
			$cmb->add_group_field( $cmb_group, [
				'name' => esc_html__( "Symbol {$i}", 'blpw' ),
				'id' => "blpw_block_0_ts_{$i}",
				'type' => 'file',
				'options' => [
					'url' => false, // Hide the text input for the url
				],
				'text' => [
					'add_upload_file_text' => esc_html__( 'Add Image', 'blpw' ) // Change upload button text. Default: "Add or Upload File"
				],
				'query_args' => [
					'type' => [
						'image/gif',
						'image/jpeg',
						'image/png',
					],
				],
				'preview_size' => 'small', // Image size to use when previewing in the admin.
				'default' => ($data != false)?esc_attr( $data["ts{$i}"] ):''
			] );
		}
		//---------------------------------------------
	}



	private function register_block_0_form( $cmb )
	{
		//Read options
		$data = get_option( 'blpw_block_0', false );
		//--------------------------------------------
		$cmb_group = $cmb->add_field( [
			'id' => "blpw_block_0_group",
			'desc' => esc_html__( "Slider block", 'blpw' ),
			'repeatable' => false,
			'options' => [
				'group_title' => esc_html__( "Slider", 'blpw' ),
				'closed' => false
			],
			'type' => 'group'
		] );
		//----------- Form fields --------------------
		//------------ Headline ----------------------
		$cmb->add_group_field( $cmb_group, [
			'id' => "blpw_block_0_headline",
			'name' => esc_html__( 'Headline variants', 'blpw' ),
			'type' => 'title'
		] );
		$cmb->add_group_field( $cmb_group, [
			'name' => esc_html__( '1', 'blpw' ),
			'id' => "blpw_block_0_headline_1",
			'default' => ($data != false)?esc_attr( $data['headline_1'] ):'',
			'type' => 'text'
		] );
		$cmb->add_group_field( $cmb_group, [
			'name' => esc_html__( '2', 'blpw' ),
			'id' => "blpw_block_0_headline_2",
			'default' => ($data != false)?esc_attr( $data['headline_2'] ):'',
			'type' => 'text'
		] );
		//---------------------------------------------
		//-------------- Background -------------------
		$cmb->add_group_field( $cmb_group, [
			'name' => esc_html__( 'Slider background', 'blpw' ),
			'id' => 'blpw_block_0_background',
			'type' => 'file',
			'options' => [
				'url' => false, // Hide the text input for the url
			],
			'text' => [
				'add_upload_file_text' => esc_html__( 'Add Image', 'blpw' ) // Change upload button text. Default: "Add or Upload File"
			],
			'query_args' => [
				'type' => [
					'image/gif',
					'image/jpeg',
					'image/png',
				],
			],
			'preview_size' => 'small', // Image size to use when previewing in the admin.
			'default' => ($data != false)?esc_attr( $data['background'] ):''
		] );
		//---------------------------------------------
		//-------------- Bullet points ----------------
		$cmb->add_group_field( $cmb_group, [
			'id' => "blpw_block_0_bp",
			'name' => esc_html__( 'Bullet points', 'blpw' ),
			'type' => 'title'
		] );
		for( $i = 1; $i <= 5; $i++ ){
			$cmb->add_group_field( $cmb_group, [
				'name' => esc_html__( "{$i}", 'blpw' ),
				'id' => "blpw_block_0_bp_{$i}",
				'default' => ($data != false && isset( $data["bp{$i}"] ))?esc_html( $data["bp{$i}"] ):'',
				'type' => 'text'
			] );
		}
		//---------------------------------------------
	}


	private function register_block_form( $cmb, $block = '1' )
	{
		//Read options
		$data = get_option( "blpw_block_{$block}", false );
		//--------------------------------------------
		$cmb_group = $cmb->add_field( [
			'id' => "blpw_block_{$block}_group",
			'desc' => esc_html__( "Information block {$block}", 'blpw' ),
			'repeatable' => false,
			'options' => [
				'group_title' => esc_html__( "Block {$block}", 'blpw' ),
				'closed' => false
			],
			'type' => 'group'
		] );
		//----------- Form fields --------------------
		//------------ Headline ----------------------
		$cmb->add_group_field( $cmb_group, [
			'id' => "blpw_block_{$block}_headline",
			'name' => esc_html__( 'Headline variants', 'blpw' ),
			'type' => 'title'
		] );
		$cmb->add_group_field( $cmb_group, [
			'name' => esc_html__( '1', 'blpw' ),
			'id' => "blpw_block_{$block}_headline_1",
			'default' => ( $data != false )?esc_html( $data['headline_1'] ):'',
			'type' => 'text'
		] );
		$cmb->add_group_field( $cmb_group, [
			'name' => esc_html__( '2', 'blpw' ),
			'id' => "blpw_block_{$block}_headline_2",
			'default' => ( $data != false )?esc_html( $data['headline_2'] ):'',
			'type' => 'text'
		] );
		//---------------------------------------------
		//------------ Text variants ------------------
		$cmb->add_group_field( $cmb_group, [
			'id' => "blpw_block_{$block}_text",
			'name' => esc_html__( 'Text variants', 'blpw' ),
			'type' => 'title'
		] );
		$cmb->add_group_field( $cmb_group, [
			'name' => esc_html__( '1', 'blpw' ),
			'id' => "blpw_block_{$block}_text_1",
			'default' => ( $data != false )?esc_textarea( $data['text_1'] ):'',
			'type' => 'textarea_small'
		] );
		$cmb->add_group_field( $cmb_group, [
			'name' => esc_html__( '2', 'blpw' ),
			'id' => "blpw_block_{$block}_text_2",
			'default' => ( $data != false )?esc_textarea( $data['text_2'] ):'',
			'type' => 'textarea_small'
		] );
		//---------------------------------------------
	}


	private function register_last_projects_form( $cmb )
	{
		//Read options
		$data = get_option( 'blpw_last_projects', false );
		//--------------------------------------------
		$cmb_group = $cmb->add_field( [
			'id' => "blpw_last_projects_group",
			'desc' => esc_html__( "Information about last projects", 'blpw' ),
			'repeatable' => false,
			'options' => [
				'group_title' => esc_html__( "Last Projects", 'blpw' ),
				'closed' => false
			],
			'type' => 'group'
		] );
		//----------- Form fields --------------------
		//------------ Headline ----------------------
		$cmb->add_group_field( $cmb_group, [
			'name' => esc_html__( 'Headline', 'blpw' ),
			'id' => "blpw_block_last_projects_headline",
			'default' => ( $data != false )?esc_html( $data['headline'] ):'',
			'type' => 'text'
		] );
		//---------------------------------------------
		//------------ Pictures -----------------------
		for( $i = 1; $i <= 5; $i++ ){
			$cmb->add_group_field( $cmb_group, [
				'name' => esc_html__( "Project {$i}", 'blpw' ),
				'id' => "blpw_last_projects_pic_{$i}",
				'type' => 'file',
				'options' => [
					'url' => false, // Hide the text input for the url
				],
				'text' => [
					'add_upload_file_text' => esc_html__( 'Add Image', 'blpw' ) // Change upload button text. Default: "Add or Upload File"
				],
				'query_args' => [
					'type' => [
						'image/gif',
						'image/jpeg',
						'image/png',
					],
				],
				'preview_size' => 'small', // Image size to use when previewing in the admin.
				'default' => ( $data != false )?esc_html( $data["pic{$i}"] ):''
			] );
		}
		//---------------------------------------------
	}



	private function register_w2e_form( $cmb )
	{
		//Read options
		$data = get_option( 'blpw_w2e', false );
		//--------------------------------------------
		$cmb_group = $cmb->add_field( [
			'id' => "blpw_w2e_group",
			'desc' => esc_html__( "Information about What to Expect", 'blpw' ),
			'repeatable' => false,
			'options' => [
				'group_title' => esc_html__( "What to Expect", 'blpw' ),
				'closed' => false
			],
			'type' => 'group'
		] );
		//----------- Form fields --------------------
		//----------- Photo --------------------------
		$cmb->add_group_field( $cmb_group, [
			'name' => esc_html__( 'Photo', 'blpw' ),
			'id' => 'blpw_w2e_photo',
			'type' => 'file',
			'options' => [
				'url' => false, // Hide the text input for the url
			],
			'text' => [
				'add_upload_file_text' => esc_html__( 'Add Photo', 'blpw' ) // Change upload button text. Default: "Add or Upload File"
			],
			'query_args' => [
				'type' => [
					'image/gif',
					'image/jpeg',
					'image/png',
				],
			],
			'preview_size' => 'small', // Image size to use when previewing in the admin.
			'default' => ( $data != false )?esc_html( $data['photo'] ):''
		] );
		//--------------------------------------------
		//--------------- Bullet points --------------
		$cmb->add_group_field( $cmb_group, [
			'id' => "blpw_w2e_bp",
			'name' => esc_html__( 'Bullet points', 'blpw' ),
			'type' => 'title'
		] );
		for( $i = 1; $i <= 5; $i++ ){
			$cmb->add_group_field( $cmb_group, [
				'name' => esc_html__( "{$i}", 'blpw' ),
				'id' => "blpw_w2e_bp_{$i}",
				'default' => ( $data != false && isset( $data["bp{$i}"] ) )?esc_html( $data["bp{$i}"] ):'',
				'type' => 'text'
			] );
		}
		//--------------------------------------------
	}


	private function register_testimonials_form( $cmb )
	{
		//Read options
		$data = get_option( 'blpw_testimonials', false );
		//--------------------------------------------
		$cmb_group = $cmb->add_field( [
			'id' => "blpw_testimonials_group",
			'desc' => esc_html__( "Information about Testimonials", 'blpw' ),
			'repeatable' => false,
			'options' => [
				'group_title' => esc_html__( "Testimonials", 'blpw' ),
				'closed' => false
			],
			'type' => 'group'
		] );
		//----------- Form fields --------------------
		//--------------- Testimonials- --------------
		for( $i = 1; $i <= 10; $i++ ){
			$cmb->add_group_field( $cmb_group, [
				'name' => esc_html__( "{$i}", 'blpw' ),
				'id' => "blpw_testimonials_{$i}",
				'default' => ( $data != false )?$data["testimonial{$i}"]:'',
				'type' => 'wysiwyg',
				'options' => [
					'media_buttons' => false,
					'wautop' => false,
					'teeny' => true,
					'textarea_name' => "blpw_testimonials_textarea_{$i}"
				]
			] );
		}
		//--------------------------------------------
	}


	private function register_locations_form( $cmb )
	{
		//----------- Form fields --------------------
		$cmb->add_field( [
			'name' => esc_html__( 'Locations', 'blpw' ),
			'id' => 'blpw_locations_group',
			'type' => 'title'
		] );
		$city = $cmb->add_field( [
			'name' => esc_html__( 'Select City', 'blpw' ),
			'id' => 'blpw_city',
			'type' => 'text'
		] );
		$phone = $cmb->add_field( [
			'name' => esc_html__( 'Phone', 'blpw' ),
			'id' => 'blpw_city_phone',
			'type' => 'text',
			'attributes' => [
				'placeholder' => '000-000-0000'
			]
		] );
		$add_button = $cmb->add_field( [
			'name' => esc_html__( 'Add city', 'blpw' ),
			'id' => 'blpw_add_city_btn',
			'title' => esc_html__( 'Add city', 'blpw' ),
			'type' => 'button',
			'attributes' => [
				'class' => 'button'
			]
		] );
		//Push fields to grid
		$grid = new Grid( $cmb );
		$row = $grid->addRow();
		$row->addColumns( [
			[$city, 'class' => 'col-md-5'],
			[$phone, 'class' => 'col-md-5'],
			[$add_button, 'class' => 'col-md-2']
		] );
	}


	public function store_block_0()
	{
		$data = [
			'headline_1' => (isset( $_POST['headline_1'] ))?sanitize_text_field( $_POST['headline_1'] ):'',
			'headline_2' => (isset( $_POST['headline_2'] ))?sanitize_text_field( $_POST['headline_2'] ):'',
			'background' => (isset( $_POST['background'] ))?sanitize_text_field( $_POST['background'] ):''
		];
		for( $i = 1; $i <= 5; $i++ ){
			$data["bp{$i}"] = (isset( $_POST["bp{$i}"] ))?sanitize_text_field( $_POST["bp{$i}"] ):'';
		}
		update_option( 'blpw_block_0', $data );
		echo json_encode( ['success' => true] );
		return true;
	}


	public function store_block_1()
	{
		$data = [
			'headline_1' => (isset( $_POST['headline_1'] ))?sanitize_text_field( $_POST['headline_1'] ):'',
			'headline_2' => (isset( $_POST['headline_2'] ))?sanitize_text_field( $_POST['headline_2'] ):'',
			'text_1' => (isset( $_POST['text_1'] ))?sanitize_textarea_field( $_POST['text_1'] ):'',
			'text_2' => (isset( $_POST['text_2'] ))?sanitize_textarea_field( $_POST['text_2'] ):''
		];
		update_option( 'blpw_block_1', $data );
		echo json_encode( ['success' => true] );
		return true;
	}


	public function store_block_2()
	{
		$data = [
			'headline_1' => (isset( $_POST['headline_1'] ))?sanitize_text_field( $_POST['headline_1'] ):'',
			'headline_2' => (isset( $_POST['headline_2'] ))?sanitize_text_field( $_POST['headline_2'] ):'',
			'text_1' => (isset( $_POST['text_1'] ))?sanitize_textarea_field( $_POST['text_1'] ):'',
			'text_2' => (isset( $_POST['text_2'] ))?sanitize_textarea_field( $_POST['text_2'] ):''
		];
		update_option( 'blpw_block_2', $data );
		echo json_encode( ['success' => true] );
		return true;
	}


	public function store_trusted_symbols()
	{
		$data = [];
		for( $i = 1; $i <= 5; $i++ ){
			$data["ts{$i}"] = (isset( $_POST["ts{$i}"] ))?sanitize_text_field( $_POST["ts{$i}"] ):'';
		}
		update_option( 'blpw_trusted_symbols', $data );
		echo json_encode( ['success' => true] );
		return true;
	}


	public function store_last_projects()
	{
		$data = [
			'headline' => (isset( $_POST['headline'] ))?sanitize_text_field( $_POST['headline'] ):''
		];
		for( $i = 1; $i <= 5; $i++ ){
			$data["pic{$i}"] = (isset( $_POST["pic{$i}"] ))?sanitize_text_field( $_POST["pic{$i}"] ):'';
		}
		update_option( 'blpw_last_projects', $data );
		echo json_encode( ['success' => true] );
		return true;
	}


	public function store_w2e()
	{
		$data = [
			'photo' => (isset( $_POST['photo'] ))?sanitize_text_field( $_POST['photo'] ):''
		];
		for( $i = 1; $i <= 5; $i++ ){
			$data["bp{$i}"] = (isset( $_POST["bp{$i}"] ))?sanitize_text_field( $_POST["bp{$i}"] ):'';
		}
		update_option( 'blpw_w2e', $data );
		echo json_encode( ['success' => true] );
		return true;
	}


	public function store_testimonials()
	{
		$data = [];
		for( $i = 1; $i <= 10; $i++ ){
			$data["testimonial{$i}"] = (isset( $_POST["testimonial{$i}"] ))?wp_kses( $_POST["testimonial{$i}"], $this->allow_html ):'';
		}
		update_option( 'blpw_testimonials', $data );
		echo json_encode( ['success' => true] );
		return true;
	}


	public function city_complete()
	{
		$phrase = (isset( $_POST['phrase'] ))?sanitize_text_field( $_POST['phrase'] ):null;
		$ret = [];
		if( $phrase != null ){
			//Get city by phrase template
			$cities = Cities::whereRaw( 'country_id = ? AND selected = 0 AND name like ?', [intval( $this->country ), '%'.$phrase.'%'] )
			->get();
			$ret = [];
			foreach( $cities as $city ){
				$r = [
					'id' => $city->id,
					'name' => $city->name,
					'phone' => $city->phone,
					'state_id' => $city->state_id,
					'county_id' => $city->county_id,
					'state_name' => $city->state->name,
					'county_name' => $city->county->name
				];
				$ret[] = $r;
			}
		}
		echo json_encode( $ret );
		//I don't want to zero on answer
		die();
	}


	public function add_city()
	{
		$city = (isset( $_POST['city'] ))?$_POST['city']:null;
		if( $city == null ){
			echo json_encode( ['success' => false, 'message' => esc_html__( 'Cannot find city!', 'blpw' )] );
			die();
		}
		//Check for limit for this plan
		$cities_active = Cities::where( ['selected' => 1] )->selectRaw( 'count(*) as count_active' )->first();
		if( intval( $cities_active->count_active ) >= BLPW_Utilites::get_max_items() ){
			echo json_encode( ['success' => false, 'message' => esc_html__( 'Active cities limit reached!', 'blpw' )] );
			die();
		}
		//Update city with phone and selected
		$update = [
			'phone' => sanitize_text_field( $city['phone'] ),
			'selected' => 1
		];
		$ct = Cities::where( ['id' => intval( $city['id'] )] )->first();
		$res = $ct->update( $update );
		if( intval( $res ) != 1 ){
			echo json_encode( ['success' => false, 'message' => esc_html__( 'Error add city!', 'blpw' )] );
			die();
		}
		//Create Landing Page
		//Try to untrash page if it was trashed
		BLPW_Landing_Page::restore_city_pages( intval( $city['id'] ) );
		//Then, create or update page
		BLPW_Landing_Page::create_city_pages( $ct->state, $ct->county, $ct );
		//Then, create XML Sitemap if it's enabled
		$XMLS = new BLPW_All_XML_Sitemap();
		$XMLS->create_maps();
		//Make answer
		$ret = [
			'success' => true,
			'id' => intval( $city['id'] ),
			'name' => $ct->name,
			'phone' => sanitize_text_field( $city['phone'] ),
			'county' => $ct->county->name,
			'state' => $ct->state->name
		];
		echo json_encode( $ret );
		die();
	}


	public function get_cities()
	{
		$ret = [
			'success' => true,
			'cities' => []
		];
		$cities = Cities::where( ['selected' => 1, 'country_id' => intval( $this->country )] )->get();
		foreach( $cities as $city ){
			$res = [
				'id' => intval( $city->id ),
				'name' => $city->name,
				'phone' => $city->phone,
				'county' => $city->county->name,
				'state' => $city->state->name
			];
			$ret['cities'][] = $res;
		}
		echo json_encode( $ret );
		die();
	}


	public function remove_city()
	{
		if( !isset( $_POST['id'] ) ){
			echo json_encode( ['success' => false, 'message' => esc_html__( 'Error remove city!', 'blpw' )] );
			die();
		}
		$cn = Cities::where( ['id' => intval( $_POST['id'] )] )->update( ['selected' => 0] );
		//Remove Landibg Page
		BLPW_Landing_Page::remove_city_pages( intval( $_POST['id'] ) );
		//Then, create XML Sitemap if it's enabled
		$XMLS = new BLPW_All_XML_Sitemap();
		$XMLS->create_maps();
		echo json_encode( ['success' => true] );
		die();
	}
}