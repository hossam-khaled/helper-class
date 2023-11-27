<?php
// if( strpos($_SERVER['HTTP_HOST'], 'alkhafji') === false ) {
//  die();
// }
class khafagy_post {

    public $post;
    public $post_ID;
    public $post_class;

    function __construct( $args = array() ) {
      add_filter('img_caption_shortcode', array($this, 'fix_image_margin_cation'), 10, 3);
      $this->init( $args );
    }

    function init( $args = array() ) {
      global $post;
      $this->post = empty( $args['post_ID'] ) ? $post : get_post($args['post_ID']);
      $this->post_ID = $this->post->ID;
      $this->post_class = 'single-post';
      return $this;
    }



    /* call the undifind tags */
    function __call( $name, $args ) {
      global $khafagy_layout;
      $khafagy_layout->html_tag($name, $args[0]);
      return $this;
    }


    /*
      youtube video
    */
    function is_video_post( $args = array() ) {
      $post_id = !empty( $args['post_id'] ) ? $args['post_id'] : get_the_ID();
      $value = get_post_meta($post_id, '_video_url', true);
      if ( empty($value) ) return false;
      return true;
    }

    function extract_video_id( $args = array() ) {
      $post_id = !empty( $args['post_id'] ) ? $args['post_id'] : get_the_ID();
      $meta = get_post_meta($post_id, '_video_url', true);
      if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $meta, $matches)) {
            return  $matches[1];
      }
    }

    function the_video( $args = array() ) {
       echo '<iframe class="player" src="https://www.youtube.com/embed/'.$this->extract_video_id( $args ).'?rel=0&wmode=transparent&autoplay=1" frameborder="0" allowfullscreen></iframe>';
       return $this;
    }

    function get_youtube_video_image( $args = array() ) {
       return 'http://img.youtube.com/vi/'.$this->extract_video_id( $args ).'/maxresdefault.jpg';
    }


    /*
      post link
    */
    function post_div( $args = '' ) {
      if( $args == '/' ) {
        echo '</div>';
      } else {
        echo '<div class="'.$this->post_class.' '.$args.'">';
      }
      return $this;
    }

    function post_link( $args = '' ) {
      if( $args == '/' ) {
        echo '</a>';
      } else {
        echo '<a href="'.get_permalink().'" class="'.$this->post_class.' '.$args.'">';
      }
      return $this;
    }

    function image_src_without_version( $id, $size) {
      $image = wp_get_attachment_image_src( $id, $size );
      return preg_replace( '/\?v=[0-9]+/','',$image );
    }

    /*
      thumbnail
    */
    function get_thumbnail( $args = array( 'width' => 120, 'height' => 120 ) ) {
      global $post;
      global $blog_id;

      $post_id = !empty( $args['post_id'] ) ? $args['post_id'] : get_the_ID();
      if( is_string( $args ) ) {
        $args = explode(',', $args);
        $args['width'] = $args[0];
        $args['height'] = $args[1];
      }
      if( has_post_thumbnail($post->ID) ) {
        $large_image_url = $this->image_src_without_version( get_post_thumbnail_id($post_id), 'large');
      } elseif( $this->is_video_post() ) {
        $large_image_url[0] = $this->get_youtube_video_image();
      } else {
        $attachments = get_children( array(
            'post_parent' => $post_id,
            'post_status' => 'inherit',
            'post_type' => 'attachment',
            'post_mime_type' => 'image',
            'order' => 'ASC',
            'orderby' => 'menu_order ID',
            'posts_per_page' => 1)
        );
        foreach ( $attachments as $thumb_id => $attachment ) {
            $large_image_url = $this->image_src_without_version($thumb_id, 'large');
            break;
        }
      }

      $large_image_url = $large_image_url[0];

      // fix network error
      if( isset( $blog_id ) && $blog_id > 0  ) {  /* && SUBDOMAIN_INSTALL == false */
    		$imageParts = explode('/uploads/' , $large_image_url);
    		if( isset( $imageParts[1] ) ) {
    			$large_image_url = network_site_url() . '/wp-content/uploads/' . $imageParts[1];
    		}
    	}

      // $post_image = get_template_directory_uri().'/timthumb/?src='.$large_image_url.'&w='.$args['width'].'&h='.$args['height'];

      if( $width == '100%' ) return $large_image_url;
      if( !empty( $large_image_url ) ) {
        return $large_image_url;
      }else {
        return $large_image_url = get_template_directory_uri(). '/assets/images/no_image.jpg';
      }
      return false;

    }

    function thumbnail( $args = array() ) {
      echo '<div class="thumbnail"><img alt="'.the_title_attribute(array('echo' => 0)).'" title="'.the_title_attribute(array('echo' => 0)).'" src="'.$this->get_thumbnail( $args ).'" /></div>';
      return $this;
    }


    /*
      title
    */
    function get_title( $args = array() ){
      if( !is_array( $args ) ) $args = array( 'count' => $args );
      $post_id = !empty( $args['post_id'] ) ? $args['post_id'] : get_the_ID();
      $title = get_the_title( $post_id );
      if ( !empty($args['count']) ) $title = $this->string_limit_words( $title, $args['count'] );
      return $title;
    }

    function title( $args = array() ) {
      echo '<h2 class="the-title">'.$this->get_title( $args ).'</h2>';
      return $this;
    }


    /*
      second title
      _second_title
      ترانا dbt_secondary_title

    */
    function get_second_title( $args = array() ){
      $post_id = !empty( $args['post_id'] ) ? $args['post_id'] : get_the_ID();
      return get_post_meta( $post_id , '_second_title', true);
    }

    function the_second_title( $args = array() ){
      echo '<h3 class="small-title">'.$this->get_second_title().'</h3>';
      return $this;
    }

    /*
      third title
    */
    function get_third_title( $args = array() ){
      $post_id = !empty( $args['post_id'] ) ? $args['post_id'] : get_the_ID();
      return get_post_meta( $post_id , '_third_title', true);
    }

    function the_third_title( $args = array() ){
      echo '<h4 class="secont-title">'.$this->get_third_title().'</h4>';
      return $this;
    }


    /*
      First category
    */
    function category( $args = array() ) {
      $post_id = !empty( $args['post_id'] ) ? $args['post_id'] : get_the_ID();
      $categories = get_the_category( $post_id );
      echo '<div class="category">'.$categories[0]->name.'</div>';
      return $this;
    }

    function linked_category( $args = array() ) {
      $post_id = !empty( $args['post_id'] ) ? $args['post_id'] : get_the_ID();
      $categories = get_the_category( $post_id );
      echo '<a class="category" href="'.get_category_link( $categories[0] ).'">'.$categories[0]->name.'</a>';
      return $this;
    }


    /*
      excerpt
    */
    function get_excerpt( $args = array ('count' => 40 ,'allowed_tags' => '' ) ) {
      $post = get_post();
      if( !is_array( $args ) ) $args = array( 'count' => $args );
      extract($args);
      $content = !empty($post->post_excerpt) ? strip_shortcodes($post->post_excerpt) : strip_shortcodes($post->post_content);
      $content = ( $allowed_tags == 'all' ) ? $content : strip_tags($content, $allowed_tags);
      $content = explode(' ', str_replace(array("\r\n", "\r", "\n"), "", $content, $allowed_tags ) );

      $tot = count($content);
      $output = implode(' ', array_slice($content, 0, $args['count']));
      $output = ( $tot > $args['count'] ) ? $output." ..." : $output;

      return force_balance_tags($output);
    }

    function excerpt( $args = array () ) {
      echo '<div class="description">'.force_balance_tags( $this->get_excerpt( $args ) ).'</div>';
      return $this;
    }


    /*
      author
    */
    // function the_author( $args = array() ){
    //   echo '<div class="author">'.get_the_author().'</div>';
    //   return $this;
    // }


    /*
      _views
        ترانا  post_views_count
    */
    function views( $args = array() ){
      $count = $this->get_views( $args );
      // if( current_user_can( 'administrator' ) || current_user_can( 'editor' ) || current_user_can( 'author' ) ) {
        echo '<div class="views">'.$count.'</div>';
      // }

      return $this;
    }

    function get_views( $args = array() ) {
      $post_id = !empty( $args['post_id'] ) ? $args['post_id'] : get_the_ID();
      $count_key = '_views';
      $count = $this->_get( $count_key );
      if(empty($count)){
            $count = 0;
            delete_post_meta($post_id, $count_key);
            add_post_meta($post_id, $count_key, '0');
      }
      return $count;
    }

    function update_views( $args = array() ){
      $post_id = !empty( $args['post_id'] ) ? $args['post_id'] : get_the_ID();
      $count_key = '_views';
      $count = $this->_get( $count_key );
      if(empty($count)){
            $count = 0;
            delete_post_meta($post_id, $count_key);
            add_post_meta($post_id, $count_key, '0');
      }
      $count++;
      update_post_meta($post_id, $count_key, $count);
      return $count;
    }


    /*
      Random element id
    */
    function random_element_id($length = 18) {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $rand_number = rand(0, $charactersLength - 1);
            $randomString .= $characters["$rand_number"];
        }
        return $randomString;
    }


    /*
      Time
      date_i18n('h:i A, d M Y') => now time
    */
    function date() {
       echo '<time class="date" datetime="'.get_the_date( 'c' ).'" pubdate>'.get_the_time('d M Y').'</time>';
       return $this;
    }

    function date_not_year() {
       echo '<time class="date" datetime="'.get_the_date( 'c' ).'" pubdate>'.get_the_time('d M').'</time>';
       return $this;
    }

    function time() {
       echo '<time class="date" datetime="'.get_the_date( 'c' ).'" pubdate>'.get_the_time('h:i a').'</time>';
       return $this;
    }

    function human_time() {
      echo '<time class="date"> نشر منذ '. human_time_diff( get_the_time('U'), current_time('timestamp') ).'</time>';
      return $this;
    }

    function date_time() {
       echo '<time class="date" datetime="'.get_the_date( 'c' ).'" pubdate>'.get_the_time('h:i A, d M Y').'</time>';
       return $this;
    }
    function hajri_date() {
      global $hajri_date;
       echo '<time class="date" datetime="'.get_the_date( 'c' ).'" pubdate>'.$hajri_date->date("jS F Y",get_the_time('U')).' هـ </time>';
       return $this;
    }
    function h_m_date() {
      global $hajri_date;
       echo '<time class="date" datetime="'.get_the_date( 'c' ).'" pubdate>'.$hajri_date->date("jS F Y",get_the_time('U')).' هـ -  '.$hajri_date->date("jS F Y",get_the_time('U'),0).' م</time>';
       return $this;
    }


    /*
      comments count
    */
    function comments_count( $args = array() ) {
       echo '<div class="comments">تعليقات:'.( !empty( $args['label'] ) ? $args['label'].' ' : '' ).get_comments_number( '0', '1', '%' ).'</div>';
       return $this;
    }
    function comments_number( $args = array() ) {
       echo '<div class="comments">'.( !empty( $args['label'] ) ? $args['label'].' ' : '' ).get_comments_number( '0', '1', '%' ).'</div>';
       return $this;
    }



    /*
      get meta
    */
    function _get($key, $post_id = '' ){
      $post_id = !empty( $post_id ) ? $post_id : get_the_ID();
      return get_post_meta($post_id , $key, true);
    }



    /*
      get nember of words
    */
    function string_limit_words($string, $word_limit) {
      $words = explode(' ', $string, ($word_limit + 1));
      if(count($words) > $word_limit) {
        array_pop($words);
        return implode(' ', $words).' ...';
      } else {
        return $string;
      }
    }

    public function fix_image_margin_cation($x=null, $attr, $content){
        $xs = 0;
        extract(shortcode_atts(array(
                'id'    => '',
                'align'    => 'alignnone',
                'width'    => '',
                'caption' => ''
        ), $attr));

        if ( 1 > (int) $width || empty($caption) )
          return $content;

        if ( $id ) $id = 'id="' . $id . '" ';
        return '<div ' . $id . 'class="wp-caption ' . $align . '" style="width: ' . ((int) $width + $this->xs) . 'px">'. $content . '<p class="wp-caption-text">' . $caption . '</p></div>';
    }

}

function register_khafagy_post() {
  global $khafagy_post;
  $khafagy_post = new khafagy_post();
}
register_khafagy_post();
