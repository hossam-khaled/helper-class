<?php
/**
 * Plugin Name: Demograpghic
 * Author: Hossam khaled
 * Version: 1.0.0
 */

 function khy_get_avialable_counties() {
   return array(
     'EG' => 'egypt',
     'AE' => 'uae',
     'SA' => 'ksa',
     'IQ' => 'iraq',
   );
 }

 class control_demographic {
   public $countries;
   public $languages;
   public $avialable_codes;
   public $current_post_id;

   function is_uae_request() {

   }

   function clear_country() {

   }

   function is_english_request() {

   }

   function __construct() {
     // return false;

     $lang = explode('/', $_SERVER['REQUEST_URI']);
     // // return false;
     // // session_start();
     // // var_dump($_SESSION["current_language_code"]);
     // // var_dump($_SESSION["current_country_code"]);
     // // die(); || customSearch('wp-admin', $lang)
     //
     // if( customSearch('khoraafy_order', $lang) || customSearch('imooie_orders', $lang) || customSearch('order-received', $lang)|| customSearch('admin', $lang)  ) {
     //   // session_destroy();
     //   return false;
     // }
     // var_dump(is_page_template( 'invoice/template-get-orders.php' ));
     // var_dump('down');
     // var_dump(get_page_by_path( 'order-for-agents' ));

     add_filter( 'parse_query', array( $this, 'prefix_parse_filter' ) );
     add_action('restrict_manage_posts', array( $this, 'add_extra_tablenav' ));

     $this->current_post_id = '';
     $this->countries = khy_get_avialable_counties();
     $this->languages = array(
       'ar' => 'arabic',
       'en' => 'english'
     );
     if ( is_admin() ) return false;
     $this->site_url = site_url();
     //$this->update_avialable_codes();
     $this->set_current_post_ID();
     $this->update_current_code();
     $this->change_site_language();


     add_filter( 'woocommerce_currency_symbol', array( $this, 'change_currency_symbol' ), 10, 2 );
     add_filter( 'woocommerce_currency', array( $this, 'change_currency_on_site' ) );
     // add_filter( 'the_title',  array( $this, 'khy_custom_product_title' ), 10, 4 );
     // add_filter( 'the_content',  array( $this, 'suppress_if_blurb1' ), 10, 4 );
     // add_filter( 'woocommerce_cart_item_name',  array( $this, 'khy_cart_item_name' ), 10, 3 );
     // add_action( 'pre_get_posts', array( $this, 'custom_pre_get_posts_query' ) );

     //add_filter( 'rewrite_rules_array', array( $this, 'add_iso_codes_to_url' ) );
     // add_filter( 'site_url', array( $this, 'filter_url' ), 10 );
     //add_filter( 'the_permalink', array( $this, 'filter_url' ) );
     // add_filter('woocommerce_product_get_price', array( $this, 'custom_price' ), 99, 2 );
     // add_filter( 'woocommerce_get_cart_item_from_session', array( $this, 'set_custom_cart_item_prices_from_session' ), 20, 3 );
     // add_filter( 'woocommerce_add_cart_item', array( $this, 'set_custom_cart_item_prices' ), 20, 2 );
     // add_action( 'woocommerce_product_query', array( $this, 'custom_pre_get_posts_query' ) );
     // add_filter( 'site_url', array( $this, 'add_language_to_url' ), 10 );
     // add_filter( 'page_link', array( $this, 'filter_url' ), 10, 3 );
     // add_filter( 'category_link', array( $this, 'filter_url' ), 10, 3 );
     // add_filter( 'post_link_category', array( $this, 'filter_url' ), 10, 3 );
     // add_filter( 'woocommerce_cart_item_permalink', array( $this, 'filter_url' ), 10, 3  );
     // add_filter( 'woocommerce_product_add_to_cart_url', array( $this, 'filter_url' ), 10, 3   );
     // add_filter( 'woocommerce_get_cart_url', array( $this, 'filter_url' ), 10, 3   );
     // add_filter( 'woocommerce_loop_add_to_cart_link', array( $this, 'filter_url' ), 10, 3   );
     // add_filter( 'home_url', array( $this, 'filter_url' ) );
   }

   function set_current_post_ID() {
      global $wp;
      $actual_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
      $post_id = url_to_postid($actual_url);
      $this->current_post_id = $post_id;
      //$this->current_post_id = $post_id;
   }

   function update_current_code() {
     session_start();
     global $wp;
     $cloudflare_country_code = isset( $_SERVER["HTTP_CF_IPCOUNTRY"] );
     $requested_path = isset( $_SERVER['REQUEST_URI'] );
     $_SESSION["current_language_code"] = $_SESSION["current_language_code"] ? $_SESSION["current_language_code"] : 'arabic';
     $_SESSION["current_country_code"] = $_SESSION["current_country_code"] ? $_SESSION["current_country_code"] : 'ksa';

     if( get_post_type( $this->current_post_id ) == 'product' ) {
       $product_country = get_post_meta( $this->current_post_id, '_product_countriy_view', true );
       $_SESSION["current_country_code"] = $product_country;
       // var_dump( $product_country );
     } elseif( isset( $_GET['country'] ) == 'uae' ) {
       $_SESSION["current_country_code"] = 'uae';
     } elseif( isset( $_GET['country'] ) == 'ksa' ){
       $_SESSION["current_country_code"] = 'ksa';
     }

     if( isset( $_GET['lang'] ) ) {
       if ($_GET['lang'] == 'en') {
         $_SESSION["current_language_code"] = 'english';
       }else {
         $_SESSION["current_language_code"] = 'arabic';
       }
       // $_SESSION["current_language_code"] = $_GET['lang'] == 'en' ? 'en' : 'ar';
     }

     $this->current_language = $_SESSION["current_language_code"];
     $this->current_country = $_SESSION["current_country_code"];

     // // update current code
     // foreach ( $this->avialable_codes as $code ){
     //   if ( str_replace( $code , '', $requested_path ) != $requested_path ) {
     //     $this->current_code = $code;
     //   }
     // }

     //get country and language
     // $current_code_array = explode( '-', $this->current_code );
     // $current_language_code = $this->current_code;
     // $current_country_code = $current_code_array[1];
     // $this->current_language = $this->languages["$current_language_code"];
     // $this->current_country = $this->countries["$current_country_code"];
     // var_dump($this->current_country );

     // $current_language_code = $current_code_array[0] ? $current_code_array[0] : ( $_SESSION['current_language_code'] ? $_SESSION['current_language_code'] : 'ar');
     // $current_country_code = $current_code_array[1] ? $current_code_array[1] :  ( $_SESSION['current_country_code'] ? $_SESSION['current_country_code'] : 'SA');

     // $_SESSION["current_language_code"] = $current_language_code;
     // $_SESSION["current_country_code"] = $current_country_code;
     // $this->current_code = "$current_language_code-$current_country_code";
     // $this->current_language = $this->languages["$current_language_code"];
     // $this->current_country = $this->countries["$current_country_code"];
     //var_dump( $_SESSION["current_language_code"] );
   }

   function change_site_language() {

     if( $this->current_language == 'english'  ) {
       switch_to_locale( 'en_US' );
     }elseif ($this->current_language == 'arabic') {
       switch_to_locale( 'ar' );
     }
     // var_dump($this->current_language);
     // var_dump(get_locale());
   }


    function  prefix_parse_filter($query) {
       global $pagenow;
       //var_dump($pagenow);
       $current_page = isset( $_GET['post_type'] ) ? $_GET['post_type'] : '';

       if ( is_admin() &&
         'product' == $current_page &&
         'edit.php' == $pagenow &&
          isset( $_GET['_product_countriy_view'] ) &&
          $_GET['_product_countriy_view'] != '' ) {

        $_product_countriy_view            = $_GET['_product_countriy_view'];
        $query->query_vars['meta_key']     = '_product_countriy_view';
        $query->query_vars['meta_value']   = $_product_countriy_view;
        $query->query_vars['meta_compare'] = '=';
      }
    }



    function add_extra_tablenav($post_type){

        /** Ensure this is the correct Post Type*/
        if($post_type !== 'product')
            return;

        // get selected option if there is one selected
        if (isset( $_GET['_product_countriy_view'] ) && $_GET['_product_countriy_view'] != '') {
            $selectedName = $_GET['_product_countriy_view'];
        } else {
            $selectedName = -1;
        }

        /** Grab all of the options that should be shown */
        $options[] = sprintf('<option value="">%1$s</option>', __('All Countries', 'your-text-domain'));
        foreach($this->countries as $result) :
            if ($result == $selectedName) {
                $options[] = sprintf('<option value="%1$s" selected>%2$s</option>', esc_attr($result), $result);
            } else {
                $options[] = sprintf('<option value="%1$s">%2$s</option>', esc_attr($result), $result);
            }
        endforeach;

        /** Output the dropdown menu */
        echo '<select class="" id="_product_countriy_view" name="_product_countriy_view">';
        echo join("\n", $options);
        echo '</select>';

    }


    // function custom_pre_get_posts_query( $q ) {
    //   $country_code = isset( $_SERVER["HTTP_CF_IPCOUNTRY"] );
    //   if ( !isset( $q ) ) return false;
    //   // if ( is_admin() ) return false;
    //   // $this->countries = array(
    //   //   'EG' => 'egypt',
    //   //   'AE' => 'uae',
    //   //   'SA' => 'ksa',
    //   //   'IQ' => 'iraq',
    //   // );
    //   // if ( ! $q->is_main_query() ) return;
    //   // if ( ! $q->is_post_type_archive() ) return;
    //   // if( empty($this->current_country) ) $this->current_country = $this->countries["$country_code"];
    //   if( empty($this->current_country) ) $this->current_country = 'ksa';
    //   if( isset( $q->query_vars['post_type'] ) != 'product' ) return;
    //   //if(  )
    //   $args = array(
    //     array(
    //       'key'     => '_product_countriy_view',
    //       'value'   => $this->current_country,
    //     )
    //   );
    //
    //   $q->set( 'meta_query', $args );
    //   // if ( ! is_admin() && (is_blog() || is_archive()) ) {
    //
    //   // }
    // }



   function change_currency_on_site( $currency ) {

         // var_dump( $this->current_country );
         // $lang = explode('/', $_SERVER['REQUEST_URI']);
         if ( $this->current_country == 'uae' ){
           return 'AED';
         }elseif ( $this->current_country == 'iraq' ) {
           return 'IQD';
         }
      return $currency;
    }

   function change_currency_symbol( $symbols, $currency ) {
       // $country_code = $_SERVER["HTTP_CF_IPCOUNTRY"];
       // $lang = explode('/', $_SERVER['REQUEST_URI']);
       if ( $this->current_language == 'english' && $this->current_country == 'uae' ){
         return 'AED ';
       }elseif ( $this->current_language == 'arabic' && $this->current_country == 'uae') {
         return 'د.إ ';
       }elseif ( $this->current_language == 'english' && $this->current_country == 'ksa') {
         return 'SAR ';
       }elseif ( $this->current_language == 'arabic' && $this->current_country == 'ksa') {
         return 'ر.س ';
       }elseif ( $this->current_language == 'english' && $this->current_country == 'iraq') {
         return 'IQD ';
       }elseif ( $this->current_language == 'arabic' && $this->current_country == 'iraq') {
         return 'د.ع ';
       }
   	return $symbols;
   }

   // function khy_custom_product_title( $title, $id = null ) {
   //     if ( !is_main_query() ) return $title;
   //     $english_title =  get_post_meta( $id,'_khy_product_title', true) ;
   //     if( $this->current_language == 'english' ){
   //       $title = !empty( $english_title ) ? $english_title : $title;
   // 		}
   //     return $title;
   // }
   //
   // function khy_cart_item_name( $item_name,  $cart_item,  $cart_item_key ) {
   //   $english_name = get_post_meta( $cart_item['product_id'] ,'_khy_product_title', true);
   // 		if( $this->current_language == 'english' ){
   // 			$item_name =  !empty( $english_name ) ? $english_name : $item_name;
   // 		}
   //
   // 		return $item_name;
   // }
   //
   // function suppress_if_blurb1( $content ) {
   //   $english_content =  get_post_meta( get_the_ID(),'_product_content', true);
   //     if( $this->current_language == 'english' ){
   //       $content =  !empty( $english_content ) ? $english_content : $content;
   //     }
   //
   //     return $content;
   // }

   /*
   function custom_price( $price, $product ) {
     $country_code = $_SERVER["HTTP_CF_IPCOUNTRY"];
       // var_dump($country_code);
       // $country_code = ip_info($_SERVER['REMOTE_ADDR'], "Country Code");.
       $price_uae = get_post_meta(  get_the_ID() , '_product_price_'.$this->current_country ,true);
       // var_dump($price_uae);
       if (empty($price_uae)) {
         return $price;
       }else {
         return $price = $price_uae;
       }
       return $price;
   }

   function set_custom_cart_item_prices( $cart_data, $cart_item_key ) {
      $country_code = $_SERVER["HTTP_CF_IPCOUNTRY"];
       // Price calculation
       // var_dump($cart_data['data']->get_id());
       // $new_price = $cart_data['data']->get_price() * 2;
       $price_uae = get_post_meta( $cart_data['data']->get_id() , '_product_price_'.$this->current_country ,true);
       // var_dump($price_uae);
       if (empty($price_uae)) {
         return $cart_data;
       }else {
         $new_price = $price_uae;
       }

       $cart_data['data']->set_price( $new_price );
       $cart_data['new_price'] = $new_price;

       return $cart_data;
   }

   function set_custom_cart_item_prices_from_session( $session_data, $values, $key ) {
      // $lang = explode('/', $_SERVER['REQUEST_URI']);
       if ( ! isset( $session_data['new_price'] ) || empty ( $session_data['new_price'] ) ) return $session_data;
       // if ( customSearch('en', $lang) || isset( $session_data['new_product_title'] ) || !empty ( $session_data['new_product_title'] ) ) {
       //   $session_data['data']->set_name( $session_data['new_product_title'] );;
       // }
       // var_dump($session_data);
       $session_data['data']->set_price( $session_data['new_price'] );
       return $session_data;
   }
   */


   // function update_avialable_codes() {
   //   foreach ( $this->countries as $country_code => $country) {
   //     foreach( $this->languages as $language_code => $language ) {
   //       $this->avialable_codes[] = $language_code.'-'.$country_code;
   //     }
   //   }
   //   // foreach( $this->languages as $language_code => $language ) {
   //   //    $this->avialable_codes[] = $language_code;
   //   // }
   // }



   // function filter_url( $url ) {
   //   // var_dump(get_bloginfo());
   //
   //   if( str_replace( array( 'wp-content', 'wp-includes', 'wp-admin', 'wp-cron', 'wp-json' ) , '', $url ) != $url ) {
   //     return $url;
   //   }
   //   // echo home_url();
   //   // die();
   //   // $this->current_code
   //   // if( str_replace('khoraafy_order','',$url) != $url ) die($url);
   //   $new_url = $this->site_url . "/". $this->current_code;
   //   $url = str_replace( $this->site_url, $new_url, $url );
   //
   //
   //   return $url;
   // }


   // https://wordpress.stackexchange.com/questions/272305/rewrite-sub-folder-dynamically-with-country-code-in-wordpress-using-php
   // add country code to url
   // function add_iso_codes_to_url( $rules ) {
   //
   //     $new_rules = [];
   //     $codes = implode( '/|', $this->avialable_codes );
   //     // var_dump($codes);
   //     $iso_codes = '(?:'.$codes.'/)?';
   //     // var_dump($iso_codes);
   //     // $iso_codes = '(?:ar-eg/|en-eg/)?';
   //     // home page
   //     $new_rules[$iso_codes . '/?$'] = 'index.php';
   //     // Change other rules
   //     foreach ( $rules as $key => $rule ) {
   //         if ( substr( $key, 0, 1 ) === '^' ) {
   //             $new_rules[ $iso_codes . substr( $key, 1 ) ] = $rule;
   //         } else {
   //             $new_rules[ $iso_codes . $key ] = $rule;
   //         }
   //     }
   // 		// var_dump($new_rules);
   //
   //     return $new_rules;
   // }



      // add_action( 'save_post_shop_order', 'update_order_currency_on_creation', 1000 );
      // function update_order_currency_on_creation( $order_id ){
      //   $country_code = $_SERVER["HTTP_CF_IPCOUNTRY"];
      //
      //
      //     // Ensure that this is a manual new order
      //     // if( $created = get_post_meta( $order_id, '_created_via', true ) ) {
      //     //     return $order_id;
      //     // }
      //
      //     // Checking that is not an autosave  (not sure that this is really needed on Woocommerce orders)
      //     if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
      //         return $order_id;
      //     }
      //
      //     // Check the user’s permissions (for 'shop_manager' and 'administrator' user roles)
      //     // if ( ! current_user_can( 'edit_shop_order', $order_id ) ) {
      //     //     return $order_id;
      //     // }
      //
      //     ## ---- Updating order currency ---- ##
      //
      //     // Get the WC_Order object
      //     $order  = wc_get_order($order_id);
      //     // $price_uae = get_post_meta( get_the_ID() , '_product_price_uae' ,true);
      //     // HERE below the Booster meta key for Order currency
      //     if ( $country_code == 'AE' ){
      //       $order->set_currency( 'AED' );
      //       $order->save(); // Save order data
      //     }
      //     // If Booster currency is already in database (in case of, to be sure)
      //     // if ( $value = $order->get_meta($meta_key) ) {
      //     //
      //     // }
      //     // If not, we get the posted Booster currency value (else)
      //     // elseif ( isset($_POST[$meta_key]) && ( $value = esc_attr($_POST[$meta_key]) ) ) {
      //     //     $order->set_currency( esc_attr($_POST[$meta_key]) );
      //     //     $order->save(); // Save order data
      //     // }
      // }


 }
  function run_demographic() {

    global $demographic;
    $demographic = new control_demographic();

  }
  add_action( 'init', 'run_demographic', 1 );


 // add_filter( 'determine_current_user', 'return_admin' );
 //
 // function return_admin() {
 //   return '1';
 // }
 //
 // function kh_api() {
 //
 //   // $request = new WP_REST_Request( 'GET', '/wp/v2/posts' );
 //   // $request->set_query_params( [ 'per_page' => 12 ] );
 //   // $response = rest_do_request( $request );
 //   // $server = rest_get_server();
 //   // $data = $server->response_to_data( $response, false );
 //   // //$json = wp_json_encode( $data );
 //   //
 //   // var_dump($data);
 //
 // }
 // kh_api();
