<?php
// die();
class Tracking_visits {
	public $sales_agents;
	public $campaign_message;
	// public $post;
  function __construct()
  {
		$this->referrer_site_array = array(
			'jammelah',
			'khoraafy',
			'google',
			'instagram',
			'snapchat',
			'twitter',
			'facebook',
			'tiktok_ads',
			'snapchat_ads',
		);
		$this->sales_teams_category = get_terms( array(
			'taxonomy' => 'agents_category',
			'hide_empty' => false,
		));
		foreach ($this->sales_teams_category as $key => $value) {
			$this->sales_teams_array[] = $value->slug;
			// var_dump( $value->slug );
		}
		// Domin Name/?referrer=jammelah&team=whitening-ksa&campaign=19844&post_id=19844&utm_campaign=
		// check for referrer
		$this->referrer_site = isset( $_GET['referrer'] ) ? $_GET['referrer'] : 'khoraafy';
		$this->team_name = isset( $_GET['team'] ) ? $_GET['team'] : 'ipl';
		$this->campaign_name = isset( $_GET['campaign'] ) ? $_GET['campaign'] : '';
		$this->post_id = isset( $_GET['post_id'] ) ? $_GET['post_id'] : '';
		$this->utm_campaign = isset( $_GET['utm_campaign'] ) ? $_GET['utm_campaign'] : '';

		$this->sales_agents = $this->khy_filter_sales_agents( );
		$this->campaigns_array = $this->get_campaigns();
		// var_dump($this->sales_agents);
		if ( !in_array( $this->referrer_site, $this->referrer_site_array ) ) return false;

		if ( !in_array( $this->team_name, $this->sales_teams_array ) ) return false;


		// campaign message
		if ( !empty( $_GET['questionnaire'] ) ) {
			$this->campaign_message = $_GET['questionnaire'];

		} elseif ( is_numeric( $this->campaign_name ) ) {
			$the_query = new WP_Query( array( 'p' => $this->campaign_name  ) );
			if ( $the_query->have_posts() ) :
		    while ( $the_query->have_posts() ) : $the_query->the_post();
					$post_message = get_post_meta( $this->campaign_name, '_post_message', true);
					if ( !empty( $post_message ) ) {
						$this->campaign_message = $post_message;
					}else {
						$this->campaign_message = 'السلام عليكم شاهدت مقالة ' . get_the_title() . ' و ارغب معرفة معلومات اخرى و بإنتظار ردك بإقرب وقت';
					}

		    endwhile;
			endif;

		} elseif ( array_key_exists( $this->campaign_name , $this->campaigns_array ) ) {

			 $this->campaign_message = $this->campaigns_array[$this->campaign_name]['campaign_message'];

		} else {
			switch ( $this->referrer_site ) {
				case 'jammelah':
					// $this->campaign_message = 'السلام عليكم, قراءة عنكم في موقع جميلة و ارغب في الاستعلام عن المزيد من المعلومات و بانتظار ردك باقرب وقت';
					$this->campaign_message = 'السلام عليكم, ارغب في الاستعلام عن الليزر المنزلي و بانتظار ردك باقرب وقت';
					break;
				case 'google':
					$this->campaign_message = 'السلام عليكم, قراءة عنكم  و ارغب في الاستعلام عن المزيد من المعلومات و بانتظار ردك باقرب وقت';
					break;
				case 'instagram':
					$this->campaign_message = 'السلام عليكم, شاهدت عروضكم علي أنستجرام و ارغب في الاستعلام عن المزيد من المعلومات و بانتظار ردك باقرب وقت';
					break;
				case 'snapchat':
					$this->campaign_message = 'السلام عليكم, شاهدت عروضكم علي سناب شات و ارغب في الاستعلام عن االمزيد من المعلومات و بانتظار ردك باقرب وقت';
					break;
				case 'twitter':
					$this->campaign_message = 'السلام عليكم, شاهدت عروضكم علي تويتر و ارغب في الاستعلام عن المزيد من المعلومات و بانتظار ردك باقرب وقت';
					break;
				case 'facebook':
					$this->campaign_message = 'السلام عليكم, شاهدت عروضكم علي فيس بوك و ارغب في الاستعلام عن المزيد من المعلومات و بانتظار ردك باقرب وقت';
					break;
				default:
					$this->campaign_message = 'السلام عليكم, شاهدت عروضكم و ارغب في الاستعلام عن المزيد من المعلومات و بانتظار ردك باقرب وقت ';
					break;
			}

		}
		// switch sales link
		$this->total_quotas = 0;
		$this->key = 'khafagy_';
		$this->referrer_database_key = $this->team_name.'_current_sellers_count';
		$this->current_count = get_option( $this->referrer_database_key, true );
		$this->current_count = str_replace( $this->key, '', $this->current_count );
		$this->select_current_seller();
		// echo "<br>";
		// echo "<br>";
		// var_dump($this->sales_agents);
		// echo "<br>";
		// echo "<br>";
		// var_dump($this->select_current_seller());
		// counter database update
		$this->current_date = date( 'Y-m-d', current_time('timestamp') );
		$this->current_referrer_count = $this->get_current_count();

		if( isset( $_GET['referrer'] ) ) {

			if ( $this->current_referrer_count >= 0 && $this->current_referrer_count !== NULL ) {
				$this->count_update_views();
			} else {
				$this->count_insert_views();
			}

			$this->switch_sellers();
			$this->go_to_whatsapp();

		}

  }
	function get_campaigns( ) {
		$args = array(
			'posts_per_page' => -1,
			'ignore_sticky_posts' => 1,
			'no_found_rows' => true,
			'post_type' => 'campaigns',
			'orderby' => 'post__in'
		);

		$the_query = new WP_Query( $args );
		$campaigns=[];
		while ( $the_query->have_posts() ) : $the_query->the_post();
			$campaign_name = get_the_title();
			$campaigns["$campaign_name"] = array(
				'campaign_message' =>  get_post_meta( get_the_ID(), '_campaign_message', true),
			);
		endwhile;
		return $campaigns;
	}

	function get_team_members ($default_slug ) {

  	$args = array(
  		'posts_per_page' => -1,
  		'ignore_sticky_posts' => 1,
  		'no_found_rows' => true,
  		'post_type' => 'agents',
  		'tax_query' => array(
  			array(
  				'taxonomy' => 'agents_category',
  				'field'    => 'slug',
  				'terms'    => $default_slug,
  			),
  		),
  		'orderby' => 'post__in'
  	);

  	$the_query = new WP_Query( $args );
		// var_dump($terms );
		$team=[];
		while ( $the_query->have_posts() ) : $the_query->the_post();
			$member_name = get_the_title();
			$team["$member_name"] = array(
				'number' =>  get_post_meta( get_the_ID(), '_agent_number', true),
				'quota'	 =>  get_post_meta( get_the_ID(), '_agent_quota_number', true),
				'work_hours_start'	 =>  get_post_meta( get_the_ID(), '_agent_work_hours_start', true),
				'work_hours_end'	 =>  get_post_meta( get_the_ID(), '_agent_work_hours_end', true),
			);
		endwhile;
  	return $team;
  }

	function khy_filter_sales_agents(){
		$sales_agents_data = $this->get_team_members( $this->team_name );
		// echo "<br>";
		// echo "<br>";
		$time_now = wp_date("H:i",time( ));
		// var_dump($time_now);
		// echo "<br>";
		// echo "<br>";
		$filter_sales_agents = array();
		foreach( $sales_agents_data as $key => $seller ) {
			$seller_start_time = $seller['work_hours_start'];
 		 	$seller_end_time = $seller['work_hours_end'];
			$sales_quota = $seller['quota'];
			// echo "<br>";
			// var_dump($seller);

			if ( $seller_start_time <= $time_now && $seller_end_time >= $time_now && $sales_quota != 0 ) {
				$filter_sales_agents["$key"] = $sales_agents_data["$key"];
			}
		}
		// var_dump($filter_sales_agents);
		// echo "<br>";
		// echo "<br>";
		return ( empty( $filter_sales_agents ) ) ? $sales_agents_data : $filter_sales_agents;
		// return $filter_sales_agents;

	}

	function select_current_seller() {
 	 $selected = false;
	 // $time_now = wp_date("H:i",time( ));
	 // echo "<br>";
	 // var_dump($this->sales_agents);
 	 foreach( $this->sales_agents as $seller_name => $seller_info ) {
 		 @$current_user_max = $this->total_quotas + $seller_info['quota'];
		 // $seller_start_time = $seller_info['work_hours_start'];
		 // $seller_end_time = $seller_info['work_hours_end'];
		 // echo $seller_name . ' ,<br>';
 		 if( $this->current_count <= $current_user_max && !$selected ) {
			 // if ( $seller_start_time <= $time_now && $seller_end_time >= $time_now ) {
	 			 $this->seller_name = $seller_name;
	 			 $this->current_seller_number = $seller_info['number'];
	 			 $selected = true;
			 // }
 		 }
 		 @$this->total_quotas += $seller_info['quota'];
 	 }

 	 wp_cache_delete ( 'alloptions', 'options' );

  }

	function switch_sellers() {
			// $this->select_current_seller();

			$this->current_count++;
			update_option( $this->referrer_database_key, $this->key.$this->current_count, false );

			if( $this->current_count > $this->total_quotas ) {
				update_option( $this->referrer_database_key, $this->key.'1', false );
			}

	}

	function get_current_count() {
		global $wpdb;
		return $wpdb->get_var(
			$wpdb->prepare(
				"SELECT visits
				FROM wp_tracking_views
				WHERE name = %s AND date = %s AND referrer = %s AND campaign = %s AND post_id = %s AND utm_campaign = %s",
				array($this->seller_name, $this->current_date, $this->referrer_site, $this->campaign_name, $this->post_id, $this->utm_campaign) )
			);

	}


	 function count_update_views() {
		 global $wpdb;
		 $visits = $this->current_referrer_count + 1;
		 $data = array( 'visits' => $visits );
		 $where = array(
			 'name' => $this->seller_name,
			 'referrer' => $this->referrer_site,
			 'team_name' => $this->team_name,
			 'campaign' => $this->campaign_name ,
       'post_id' => $this->post_id,
       'utm_campaign' => $this->utm_campaign,
			 'date' => $this->current_date
		 );
		 $wpdb->update( 'wp_tracking_views', $data, $where );
	 }

	 function count_insert_views() {
		global $wpdb;
		$data = array(
			'name' => $this->seller_name,
			'referrer' => $this->referrer_site ,
			'team_name' => $this->team_name,
			'campaign' => $this->campaign_name ,
			'post_id' => $this->post_id,
			'utm_campaign' => $this->utm_campaign,
			'date' =>$this->current_date,
			'visits' =>  1,
		);

		$wpdb->insert( 'wp_tracking_views', $data );
	 }
	 function get_sales_link()  {
		// return 'https://api.whatsapp.com/send?text='.urlencode( $this->campaign_message ).'&phone=' . $this->current_seller_number;
		return 'whatsapp://send?text='.urlencode( $this->campaign_message ).'&phone=' . $this->current_seller_number;
	 }

	 function go_to_whatsapp() {

		 // $this->get_sales_link();
		header("Location: " . $this->get_sales_link() );
		exit();
	 }

}


function call_teams() {
  global $tracking_visits;


	$tracking_visits = new Tracking_visits();
	// var_dump( $tracking_visits->sales_teams_array);
	// echo "<br><br><br><br>";
	// var_dump( $tracking_visits->campaign_message);
	// echo "<br><br><br><br>";
	// var_dump($tracking_visits->sales_agents);
}

add_action('init', 'call_teams', 10 ,1);



function get_post_tracking (){
	global $post;
	// var_dump($post);
	if ( !isset($post) ) return;
	$team_name = get_post_meta( $post->ID, '_team_selected', true);
	$post_message = get_post_meta( $post->ID, '_post_message', true);
	$campaign_name = get_post_meta( $post->ID, '_campaign_selected', true);
	$referrer = $_GET['utm_source'] ? $_GET['utm_source'] : 'khoraafy';
	$find_lazer = get_the_content();

	$post_tracking_data = array(
		'team_name' => ( !empty($team_name) ) ? $team_name  : 'team-a' ,
		'post_id' => $post->ID ,
		'post_message' => ( !empty($post_message) ) ? $post_message  : get_the_title(),
		'utm_campaign' => $_GET["utm_campaign"],
		'utm_source' => $referrer
	);
	if ( !empty( $campaign_name ) ) {
		$post_tracking_data['campaign_data'] = $campaign_name;
	} elseif ( !empty( $post_message  ) ) {
		$post_tracking_data['campaign_data'] = $post->ID;
	}else {
		$post_tracking_data['campaign_data'] = stripos(	$find_lazer , 'ليزر') ? 'khoraafy_blog_message'  : $post->ID ;
	}

	return $post_tracking_data;
}
add_action( 'init', 'get_post_tracking', 0, 99 );
// advertising link
function whatsapp_box_with_button ( $args, $value  ) {
	global $tracking_visits;
	$_get_post_tracking = get_post_tracking();

	return "<div class='whatsapp-div'><div class='text'>$value</div><a id='khy-sales-whatsapp' onclick=' khafagyGoogleAnalytics(\"$tracking_visits->seller_name\") ' href='" . get_bloginfo('url') ."/?referrer=". $_get_post_tracking['utm_source'] ."&team=". $_get_post_tracking['team_name'] ."&campaign=". $_get_post_tracking['campaign_data'] ."&post_id=". $_get_post_tracking['post_id'] ."&utm_campaign=".$_get_post_tracking['utm_campaign']."'>".$args['button-text']."</a></div>";;
}
function register_whatsapp_box_with_button () {
	add_shortcode( 'whatsapp_button', 'whatsapp_box_with_button' );
}
add_action( 'init', 'register_whatsapp_box_with_button', 0, 100 );

// advertising link
function whatsapp_small_link ( $args, $value ) {
	global $tracking_visits;
	$_get_post_tracking = get_post_tracking();
	// return '<div>asdasdadadasdasd</div>';
	return "<a id='khy-sales-whatsapp' class='whatsapp-button' onclick=' khafagyGoogleAnalytics(\"$tracking_visits->seller_name\") ' href='" . get_bloginfo('url') ."/?referrer=". $_get_post_tracking['utm_source'] ."&team=". $_get_post_tracking['team_name'] ."&campaign=". $_get_post_tracking['campaign_data'] ."&post_id=". $_get_post_tracking['post_id'] . "&utm_campaign=".$_get_post_tracking['utm_campaign']."'>".$value."</a>";
}
function register_whatsapp_small_link () {
    add_shortcode( 'whatsapp_link', 'whatsapp_small_link' );
}
add_action( 'init', 'register_whatsapp_small_link', 10, 100 );
