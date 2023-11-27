<?php
/**
 * create portfolio custom meta box
 */
function add_all_meta_box() {

  add_meta_box(
  'post_options', // $id
  'خصائص الخبر', // $title
  'show_custom_meta_box', // $callback
  'post', // $page
  'normal', // $context
  'high'); // $priority
  add_meta_box(
  'articles_options', // $id
  'خصائص الخبر', // $title
  'show_custom_meta_box', // $callback
  'articles', // $page
  'normal', // $context
  'high'); // $priority

  add_meta_box(
  'articles_options', // $id
  'خصائص المنتج ', // $title
  'show_custom_meta_box', // $callback
  'product', // $page
  'normal', // $context
  'high'); // $priority

  add_meta_box(
  'marketers_options', // $id
  'marketers options', // $title
  'marketer_custom_meta_box', // $callback
  'product', // $page
  'normal', // $context
  'high'); // $priority

  add_meta_box(
  'instagram_options', // $id
  'ارقام التفاعل', // $title
  'show_custom_meta_box', // $callback
  'instagrams', // $page
  'normal', // $context
  'high'); // $priority

  add_meta_box(
  'order_options', // $id
  'خصائص الطلب', // $title
  'show_custom_meta_box', // $callback
  'shop_order', // $page
  'normal', // $context
  'high'); // $priority

  add_meta_box(
  'order_options', // $id
  'خصائص الفتورة', // $title
  'show_custom_meta_box', // $callback
  'invoices', // $page
  'normal', // $context
  'high'); // $priority
  $user = wp_get_current_user();
  // var_dump($user->roles);
  add_meta_box(
    'media_buyer_options', // $id
    'media_buyer', // $title
    'marketer_custom_meta_box', // $callback
    'page', // $page
    'normal', // $context
    'high'); // $priority
  $allowed_roles = array( 'media_buyer','author');
  if ( !array_intersect( $allowed_roles, $user->roles ) ) {
    add_meta_box(
      'page_options', // $id
      'shourt_code_options', // $title
      'show_custom_meta_box', // $callback
      'page', // $page
      'normal', // $context
      'high'); // $priority
    // code...
  }


  add_meta_box(
  'questionnaire_options', // $id
  'questions', // $title
  'show_custom_meta_box', // $callback
  'questionnaires', // $page
  'normal', // $context
  'high'); // $priority

  add_meta_box(
  'order_options', // $id
  'sales info', // $title
  'show_custom_meta_box', // $callback
  'agents', // $page
  'normal', // $context
  'high'); // $priority

}
add_action('add_meta_boxes', 'add_all_meta_box');

$GLOBALS['inputs_array'] = array(
   '_post_size',
   '_author_name',
   '_marketer_commission',
   '_marketer_video',
   '_how_to_use',
   '_product_short_dec',
   '_product_description',
   'meta-author-name',
   '_post_name',
   '_views',
   '_agent_number',
   '_agent_quota_number',
   '_agent_work_hours_start',
   '_agent_work_hours_end',
   'post_views_count',
   '_alert_link_type',
   '_second_title',
   '_video_url',
   '_history_post',
   '_alert_word',
   '_alert_color',
   '_tracking_no',
   '_call_done',
   '_invoice_vendor_done',
   '_invoice_agent_done',
   '_invoice_delivery_done',
   '_order_delivery',
   '_order_return_to',
   '_product_vendor',
   '_product_for_media_buyer',
   '_product_commission',
   '_khy_coupon_name',
   '_fake_name',
   '_fake_price',
   '_upload_invoice_1',
   '_any_invoice',
   '_team_selected',
   // '_smsa_attach',
   "_invoice_total",
   "_invoice_vat",
   "_invoice_delivery_cost",
   "_invoice_cod_fess",
   "_invoices_selected",
   "_delivery_method",
   "_product_summary",
   "_product_icon_after_img",
   "_product_icon_after_description",
   "_product_rating",
   '_product_price',
   '_product_whatsapp',
   '_button_title',
   '_button_sub_title',
   '_all_product',
   '_khy_show_post_title',
   '_khy_hide_post_title',
   '_khy_no_padding',
   '_order_agent_name',
   '_order_followup_agent_name',
   '_order_code',
   '_questions_number',
   '_last_answer',
   '_user_answer',
   '_short_name',
   '_products_selected',
   '_order_status',
   '_shipping_tracking',
   '_order_followup',
   '_product_content',
   '_product_page_offer_en',
   '_upload_product_gift_image_en',
   '_product_gift_title_en',
   '_product_gift_desc_en',
   '_product_gift_sub_desc_en',
   '_up_sale_product_image',
   '_up_sale_product_id',
   '_up_sale_product_title',
   '_up_sale_product_price',
   '_up_sale_product_old_price',
   '_fake_content',
   '_product_countriy_view',
   '_fake_title',
   '_product_price_uae',
   '_product_price_iraq',
   '_khy_product_title',
   '_campaign_name',
   '_utm_content',
   '_khy_post_type_adds',
   '_khy_post_comments_adds',
   '_love_number',
   '_comment_number',
   '_upload_instagram_profile',
   '_delivered_and_cancelled',
   '_wc-shipped_date',
   '_telegram_done',
   '_product_order_type',
   '_media_buyer_product_selected',
   '_task_next_action',
);
for( $i = 1; $i <= 10; $i++ ) {
  $GLOBALS['inputs_array'][] = '_question_'.$i;
  $GLOBALS['inputs_array'][] = '_answer_a_'.$i;
  $GLOBALS['inputs_array'][] = '_answer_b_'.$i;
  $GLOBALS['inputs_array'][] = '_answer_c_'.$i;
  $GLOBALS['inputs_array'][] = '_answer_d_'.$i;
}


function show_custom_meta_box() {
  global $post,$inputs_array;
  global $agents_class;

  foreach((array) $inputs_array as $value){
    $$value = get_post_meta($post->ID, $value, true);
  }

  ?>


  <input type="hidden" name="custom_meta_box_nonce" value="<?php echo wp_create_nonce(basename(__FILE__)) ?>" />
  <input type="hidden" name="gamageer_saved_before" value="1" />
  <?php
  // post
  // articles
  ?>
  <!--
   *** post option
   *** page option
  -->
  <?php if ($post->post_type == 'page' || $post->post_type == 'post'):
    $args = array(
      'post_type'      => 'product',
      // 'posts_per_page' => -1,
      'posts_per_page' => -1,
      'ignore_sticky_posts' => 1,
      'no_found_rows' => true,
    );

    $loop = new WP_Query( $args );?>
    <div class="field-container">
      <label>اظهار عنوان الصفحه</label>
      <div class="field">
        <select name="_khy_show_post_title">
          <option>اخفاء العنوان</option>
          <option <?php selected($_khy_post_type_adds,'yes'); ?> value="yes">yes</option>
          <option <?php selected($_khy_post_type_adds,'no'); ?> value="no">no</option>
        </select>
      </div>
    </div>
      <div class="field-container">
        <label>اختار المنتج</label>
        <div class="field">
          <select name="_all_product">
            <option value="0">اختار المنتج</option>
            <?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
                <option <?php selected($_all_product, get_the_ID()); ?> value="<?php echo get_the_ID();?>"><?php echo get_the_title();?></option>
                <?php
            endwhile;

            wp_reset_query();
            ?>
          </select>
        </div>
      </div>

      <div class="field-container">
        <label>النص الرائيسي للزرار</label>
        <div class="field">
          <input type="text" name="_button_title" value="<?php echo $_button_title; ?>" >
        </div>
      </div>
      <div class="field-container">
        <label>النص الفرعي للزرار</label>
        <div class="field">
          <input type="text" name="_button_sub_title" value="<?php echo $_button_sub_title; ?>" >
        </div>
      </div>


      <div class="field-container">
        <label>هل هذه المقاله للاعلانات</label>
        <div class="field">
          <select name="_khy_post_type_adds">
            <option value="0">اختر نوع المقاله</option>
            <option <?php selected($_khy_post_type_adds,'yes'); ?> value="yes">yes</option>
            <option <?php selected($_khy_post_type_adds,'no'); ?> value="no">no</option>
          </select>
        </div>
      </div>

      <div class="field-container">
        <label>هل تريد ايقاف التعليقات</label>
        <div class="field">
          <select name="_khy_post_comments_adds">
            <option value="0">اختر نوع المقاله</option>
            <option <?php selected($_khy_post_comments_adds,'yes'); ?> value="yes">yes</option>
            <option <?php selected($_khy_post_comments_adds,'no'); ?> value="no">no</option>
          </select>
        </div>
      </div>

      <div class="field-container">
        <label>اخفاء الوتس</label>
        <div class="field">
          <select name="_product_whatsapp">
            <option value="0">هل تريد اخفاء هذا القسم ؟</option>
            <option <?php selected($_product_whatsapp,'yes'); ?> value="yes">yes</option>
            <option <?php selected($_product_whatsapp,'no'); ?> value="no">no</option>
          </select>
        </div>
      </div>
      <!--
       *** questionnaires option
      -->
      <div class="field-container">
        <label>product title</label>
        <div class="field">
          <input type="text" name="_khy_product_title" value="<?php echo $_khy_product_title; ?>" >
        </div>
      </div>
      <div class="field-container">
        <label>product content</label>
        <div class="field">
          <?php wp_editor( $_product_content, '_product_content'); ?>
        </div>
      </div>


      <div class="field-container">
        <label>fake title</label>
        <div class="field">
          <input type="text" name="_fake_title" value="<?php echo $_fake_title; ?>" >
        </div>
      </div>
      <div class="field-container">
        <label>fake content</label>
        <div class="field">
          <?php wp_editor( $_fake_content, '_fake_content'); ?>
        </div>
      </div>

      <?php $i = 1;

      for( $i = 1; $i <= 10; $i++ ) {
        $question = '_question_'.$i;
        $answer_a = '_answer_a_'.$i;
        $answer_b = '_answer_b_'.$i;
        $answer_c = '_answer_c_'.$i;
        $answer_d = '_answer_d_'.$i;
        ?>

        <div class="questionnaires">
          <div class="field-container question">
            <label>السؤال رقم <?php echo $i; ?> ؟</label>
            <div class="field">
              <input type="text" name="<?php echo $question; ?>" value="<?php echo $$question; ?>" >
            </div>
          </div>
          <div class="field-container answer">
            <label>ادخل الاجابات الخاصه بالسوال <?php echo $i; ?> :- </label>
            <div class="field">
              <input type="text" name="<?php echo $answer_a; ?>" value="<?php echo $$answer_a; ?>" >
            </div>
            <div class="field">
              <input type="text" name="<?php echo $answer_b; ?>" value="<?php echo $$answer_b; ?>" >
            </div>
            <div class="field">
              <input type="text" name="<?php echo $answer_c; ?>" value="<?php echo $$answer_c; ?>" >
            </div>
            <div class="field">
              <input type="text" name="<?php echo $answer_d; ?>" value="<?php echo $$answer_d; ?>" >
            </div>
          </div>
        </div>
      <?php } ?>
      <div class="field-container">
        <label>اختار المنتج</label>
        <div class="field">
          <select name="_questions_number">
            <option value="0">اختر السوال الذي تريد استخدام الاجابه الخاصه به</option>
            <?php
            for( $i = 1; $i <= 10; $i++ ) {
                $question = '_question_'.$i;
                ?>
                <option <?php selected($_questions_number, $question); ?> value="<?php echo $question;?>">السؤال رقم <?php echo $i;?></option>
                <?php
              }
            ?>
          </select>
        </div>
      </div>
      <?php
        $terms = get_terms( array(
          'taxonomy' => 'agents_category',
          'hide_empty' => false,
        ) );
       ?>
      <div class="field-container">
        <label>Select Your team</label>
        <div class="field">
          <select name="_team_selected" >
            <option value="team-a">choose team</option>
            <?php foreach ($terms as $key => $value): ?>
              <option <?php selected($_team_selected, $value->slug ); ?> value="<?php echo $value->slug; ?>"><?php echo $value->name; ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="field-container">
        <label>الجمله التي تظهر قبل اجابه العميل</label>
        <div class="field">
          <input type="text" name="_user_answer" value="<?php echo $_user_answer; ?>" >
        </div>
      </div>
      <div class="field-container">
        <label>ادخل الاجابه التي تظهر في النهايه</label>
        <div class="field">
          <textarea name="_last_answer" rows="8" cols="80" ><?php echo $_last_answer; ?> </textarea>
        </div>
      </div>
    <?php elseif( $post->post_type == 'questionnaires' ):
      $i = 1;
      ?>
      <?php for( $i = 1; $i <= 10; $i++ ) {
        $question = '_question_'.$i;
        $answer_a = '_answer_a_'.$i;
        $answer_b = '_answer_b_'.$i;
        $answer_c = '_answer_c_'.$i;
        $answer_d = '_answer_d_'.$i;
        ?>

        <div class="questionnaires">
          <div class="field-container question">
            <label>السؤال رقم <?php echo $i; ?> ؟</label>
            <div class="field">
              <input type="text" name="<?php echo $question; ?>" value="<?php echo $$question; ?>" >
            </div>
          </div>
          <div class="field-container answer">
            <label>ادخل الاجابات الخاصه بالسوال <?php echo $i; ?> :- </label>
            <div class="field">
              <input type="text" name="<?php echo $answer_a; ?>" value="<?php echo $$answer_a; ?>" >
            </div>
            <div class="field">
              <input type="text" name="<?php echo $answer_b; ?>" value="<?php echo $$answer_b; ?>" >
            </div>
            <div class="field">
              <input type="text" name="<?php echo $answer_c; ?>" value="<?php echo $$answer_c; ?>" >
            </div>
            <div class="field">
              <input type="text" name="<?php echo $answer_d; ?>" value="<?php echo $$answer_d; ?>" >
            </div>
          </div>
        </div>
      <?php } ?>
      <div class="field-container">
        <label>اختار المنتج</label>
        <div class="field">
          <select name="_questions_number">
            <option value="0">اختر السوال الذي تريد استخدام الاجابه الخاصه به</option>
            <?php
            for( $i = 1; $i <= 10; $i++ ) {
                $question = '_question_'.$i;
                ?>
                <option <?php selected($_questions_number, $question); ?> value="<?php echo $question;?>">السؤال رقم <?php echo $i;?></option>
                <?php
              }
            ?>
          </select>
        </div>
      </div>
      <?php
        $terms = get_terms( array(
          'taxonomy' => 'agents_category',
          'hide_empty' => false,
        ) );
       ?>
      <div class="field-container">
        <label>Select Your team</label>
        <div class="field">
          <select name="_team_selected" >
            <option value="team-a">choose team</option>
            <?php foreach ($terms as $key => $value): ?>
              <option <?php selected($_team_selected, $value->slug ); ?> value="<?php echo $value->slug; ?>"><?php echo $value->name; ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="field-container">
        <label>الجمله التي تظهر قبل اجابه العميل</label>
        <div class="field">
          <input type="text" name="_user_answer" value="<?php echo $_user_answer; ?>" >
        </div>
      </div>
      <div class="field-container">
        <label>ادخل الاجابه التي تظهر في النهايه</label>
        <div class="field">
          <textarea name="_last_answer" rows="8" cols="80" ><?php echo $_last_answer; ?> </textarea>
        </div>
      </div>
      <!--
       *** product option
      -->
   <?php elseif($post->post_type == 'instagrams'): ?>
     <div class="field-container">
       <label>love number</label>
       <div class="field">
         <input type="number" name="_love_number" value="<?php echo $_love_number; ?>" >
       </div>
     </div>
     <div class="field-container">
       <label> comment number</label>
       <div class="field">
         <input type="number" name="_comment_number" value="<?php echo $_comment_number; ?>" >
       </div>
     </div>
     <div class="field-container">
       <label>صوره البروفيل</label>
       <div class="field">
         <input type="text" class="upload-input" name="_upload_instagram_profile" value="<?php echo $_upload_instagram_profile; ?>" >
         <input type="submit" class="upload_image_button" value="upload" >
       </div>
     </div>
   <?php elseif($post->post_type == 'product'):

      // $meta_value = get_post_meta( get_the_ID(), '_product_summary', true );
    //  $all_vendors = get_users( array( 'role__in' => array( 'marketer') ) );
      $marketer_users = get_users( array( 'role__in' => array( 'vendor' ) ) );

      ?>
      <?php
        $args = array(
          'posts_per_page' => -1,
          'ignore_sticky_posts' => 1,
          'no_found_rows' => true,
          'post_type' => 'product',
          'orderby' => 'post__in'
        );
        $the_query = new WP_Query( $args );
       ?>

      <div class="field-container">
        <label>Select Product </label>
        <div class="field">
          <select name="_up_sale_product_id" >
            <option value="">choose product</option>
            <?php
              while ( $the_query->have_posts() ) : $the_query->the_post();
                $product_name = get_the_title();
                $product_id = get_the_ID();
                echo '<option' . selected($_up_sale_product_id, $product_id ) . ' value="' . $product_id . '">' . $product_name . '</option>';
              endwhile;
              wp_reset_postdata();
              unset($the_query);
             ?>
          </select>
        </div>
        <p>Select Your Product for add to order for up Sale order</p>
      </div>
            <div class="field-container">
              <label>صوره المنتج الاضافي</label>
              <div class="field">
                <input type="text" class="upload-input" name="_up_sale_product_image" value="<?php echo $_up_sale_product_image; ?>" >
                <input type="submit" class="upload_image_button" value="upload" >
              </div>
            </div>
            <div class="field-container">
              <label>عنوان المنتج الاضافي </label>
              <div class="field">
                <input type="text" name="_up_sale_product_title" value="<?php echo $_up_sale_product_title; ?>" >
              </div>
            </div>
            <div class="field-container">
              <label>السعر الخاص بالمنتج الاضافي </label>
              <div class="field">
                <input type="text" name="_up_sale_product_price" value="<?php echo $_up_sale_product_price; ?>" >
              </div>
            </div>
            <div class="field-container">
              <label>السعر قبل الخصم للمنتج الاضافي </label>
              <div class="field">
                <input type="text" name="_up_sale_product_old_price" value="<?php echo $_up_sale_product_old_price; ?>" >
              </div>
            </div>

      <div class="field-container">
        <label> هل مسموح للمسوقين ؟</label>
        <div class="field">
          <select name="_product_for_media_buyer">
            <option value="no">برجاء اختيار نوع المنتج</option>
            <option <?php selected($_product_for_media_buyer,'yes'); ?> value="yes">yes</option>
            <option <?php selected($_product_for_media_buyer,'no'); ?> value="no">no</option>
          </select>
        </div>
      </div>
      <div class="field-container">
        <label>اختار صاحب المنتج</label>
        <div class="field">
          <select name="_product_vendor">
            <option value="">اختر صاحب المنتج</option>
            <?php
            foreach ( $marketer_users as $user ) {
              // echo '<div>' . var_dump($user) .'<br>'. esc_html( $user->display_name .' == .'.$user->ID) . '</div>';
              echo  '<option  '.selected($_product_vendor, $user->ID).'  value="'. $user->ID.'"> '. $user->display_name . '</option>';
            }
             ?>
          </select>
        </div>
      </div>

      <div class="field-container">
        <label>برجاء اختيار نوع المنتج </label>
        <div class="field">
          <select name="_product_order_type">
            <option value="laser">برجاء اختيار نوع المنتج</option>
            <option <?php selected($_product_order_type,'laser'); ?> value="laser">laser</option>
            <option <?php selected($_product_order_type,'cosmetics'); ?> value="cosmetics">cosmetics</option>
          </select>
        </div>
      </div>

      <div class="field-container">
        <label>coupon name</label>
        <div class="field">
          <input type="text" name="_khy_coupon_name" value="<?php echo $_khy_coupon_name; ?>" >
        </div>
        <p></p>
      </div>
      <div class="field-container">
        <label>fake name</label>
        <div class="field">
          <input type="text" name="_fake_name" value="<?php echo $_fake_name; ?>" >
        </div>
        <p></p>
      </div>
      <div class="field-container">
        <label>fake price</label>
        <div class="field">
          <input type="text" name="_fake_price" value="<?php echo $_fake_price; ?>" >
        </div>
        <p></p>
      </div>

      <div class="field-container">
        <label>short name</label>
        <div class="field">
          <input type="text" name="_short_name" value="<?php echo $_short_name; ?>" >
        </div>
      </div>

      <?php
        $args = array(
          'posts_per_page' => -1,
          'ignore_sticky_posts' => 1,
          'no_found_rows' => true,
          'post_type' => 'product',
          'orderby' => 'post__in'
        );
        $the_query = new WP_Query( $args );
       ?>

      <div class="field-container">
        <label>Select Product</label>
        <div class="field">
          <select name="_products_selected" >
            <option value="">choose product</option>
            <?php
              while ( $the_query->have_posts() ) : $the_query->the_post();
                $product_name = get_the_title();
                $product_id = get_the_ID();
                echo '<option' . selected($_products_selected, $product_id ) . ' value="' . $product_id . '">' . $product_name . '</option>';
              endwhile;
              wp_reset_postdata();
              unset($the_query);
             ?>
          </select>
        </div>
        <p>Select Your Product for add to order for free</p>
      </div>

      <div class="field-container">
        <label>اخفاء القسم الخاص باسم المنتج </label>
        <div class="field">
          <select name="_product_summary">
            <option value="0">هل تريد اخفاء هذا القسم ؟</option>
            <option <?php selected($_product_summary,'yes'); ?> value="yes">yes</option>
            <option <?php selected($_product_summary,'no'); ?> value="no">no</option>
          </select>
        </div>
      </div>
      <div class="field-container">
        <label>اخفاء القسم الخاص بالايكون اسفل صوره المنتج </label>
        <div class="field">
          <select name="_product_icon_after_img">
            <option value="0">هل تريد اخفاء هذا القسم ؟</option>
            <option <?php selected($_product_icon_after_img,'yes'); ?> value="yes">yes</option>
            <option <?php selected($_product_icon_after_img,'no'); ?> value="no">no</option>
          </select>
        </div>
      </div>
      <div class="field-container">
        <label>اخفاء القسم الخاص بالتقيم </label>
        <div class="field">
          <select name="_product_rating">
            <option value="0">هل تريد اخفاء هذا القسم ؟</option>
            <option <?php selected($_product_rating,'yes'); ?> value="yes">yes</option>
            <option <?php selected($_product_rating,'no'); ?> value="no">no</option>
          </select>
        </div>
      </div>
      <div class="field-container">
        <label>اخفاء السعر</label>
        <div class="field">
          <select name="_product_price">
            <option value="0">هل تريد اخفاء هذا القسم ؟</option>
            <option <?php selected($_product_price,'yes'); ?> value="yes">yes</option>
            <option <?php selected($_product_price,'no'); ?> value="no">no</option>
          </select>
        </div>
      </div>
      <div class="field-container">
        <label>اخفاء الوتس</label>
        <div class="field">
          <select name="_product_whatsapp">
            <option value="0">هل تريد اخفاء هذا القسم ؟</option>
            <option <?php selected($_product_whatsapp,'yes'); ?> value="yes">yes</option>
            <option <?php selected($_product_whatsapp,'no'); ?> value="no">no</option>
          </select>
        </div>
      </div>
      <div class="field-container">
        <label>اخفاء القسم الخاص بالايكون اسفل المنتج</label>
        <div class="field">
          <select name="_product_icon_after_description">
            <option value="0">هل تريد اخفاء هذا القسم ؟</option>
            <option <?php selected($_product_icon_after_description,'yes'); ?> value="yes">yes</option>
            <option <?php selected($_product_icon_after_description,'no'); ?> value="no">no</option>
          </select>
        </div>
      </div>
      <div class="field-container">
        <label>product title</label>
        <div class="field">
          <input type="text" name="_khy_product_title" value="<?php echo $_khy_product_title; ?>" >
        </div>
      </div>
      <div class="field-container">
        <label>product content</label>
        <div class="field">
          <?php
          // var_dump($_product_content);
          ?>
          <?php wp_editor( $_product_content, '_product_content'); ?>
        </div>
      </div>
      <div class="field-container">
        <label>العرض الخاص بالمنتج بدايه الصفحة english</label>
        <div class="field">
          <input type="text" name="_product_page_offer_en" value="<?php echo $_product_page_offer_en; ?>" >
        </div>
      </div>
      <div class="field-container">
        <label>صوره الهدية english</label>
        <div class="field">
          <input type="text" class="upload-input" name="_upload_product_gift_image_en" value="<?php echo $_upload_product_gift_image_en; ?>" >
          <input type="submit" class="upload_image_button" value="upload" >
        </div>
      </div>
      <div class="field-container">
        <label>عنوان الهدية english</label>
        <div class="field">
          <input type="text" name="_product_gift_title_en" value="<?php echo $_product_gift_title_en; ?>" >
        </div>
      </div>
      <div class="field-container">
        <label>وصف الهدية english</label>
        <div class="field">
          <input type="text" name="_product_gift_desc_en" value="<?php echo $_product_gift_desc_en; ?>" >
        </div>
      </div>
      <div class="field-container">
        <label>وصف اضافي للهدية english</label>
        <div class="field">
          <input type="text" name="_product_gift_sub_desc_en" value="<?php echo $_product_gift_sub_desc_en; ?>" >
        </div>
      </div>


      <div class="field-container">
        <label>اختر الدوله الخاصه بالمنتج</label>
        <div class="field">
          <select name="_product_countriy_view">
            <option value="0">اختر دوله</option>
            <?php $avialable_counties = khy_get_avialable_counties(); ?>
            <?php foreach ($avialable_counties as $key => $value): ?>
              <option <?php selected($_product_countriy_view, $value); ?> value="<?php echo $value; ?>"><?php echo $value; ?></option>

            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="field-container">
        <label>fake title</label>
        <div class="field">
          <input type="text" name="_fake_title" value="<?php echo $_fake_title; ?>" >
        </div>
      </div>
      <div class="field-container">
        <label>fake content</label>
        <div class="field">
          <?php wp_editor( $_fake_content, '_fake_content'); ?>
        </div>
      </div>

      <!-- <div class="field-container">
        <label>سعر المنتج بالدرهم الاماراتي</label>
        <div class="field">
          <input type="number" name="_product_price_uae" value="<?php echo $_product_price_uae; ?>" >
        </div>
      </div>
      <div class="field-container">
        <label>سعر المنتج بالدينار العراقي</label>
        <div class="field">
          <input type="number" name="_product_price_iraq" value="<?php echo $_product_price_iraq; ?>" >
        </div>
      </div> -->
      <!--
      <div class="field-container">
        <label>ادخل العمله الخاصه بصاحب المنتج</label>
        <div class="field">
          <input type="number" name="_product_commission" value="<?php echo $_product_commission; ?>" >
        </div>
        <p>تغير العموله الخاصه بصاحب المنتج لهذا المنتج فقط</p>
      </div> -->

      <!--
       *** invoices option
      -->
    <?php elseif($post->post_type == 'invoices'): ?>
      <div class="field-container">
        <label> invoice total</label>
        <div class="field">
          <input type="number" name="_invoice_total" value="<?php echo $_invoice_total; ?>" >
        </div>
        <p>سعر الفاتوره اللى احنا ضربنها مش سعر الطلب</p>
      </div>
      <div class="field-container">
        <label>COD Fess</label>
        <div class="field">
          <input type="number" name="_invoice_cod_fess" value="<?php echo $_invoice_cod_fess; ?>" >
        </div>
        <p>ضريبه التحصيل على لطبات الدفع عند الاستلام بتكون 18 ريال ثابته </p>
      </div>
      <div class="field-container">
        <label>delivery Cost</label>
        <div class="field">
          <input type="number" name="_invoice_delivery_cost" value="<?php echo $_invoice_delivery_cost; ?>" >
        </div>
        <p>تكلفه الشحنه على حسب الميزان</p>
      </div>
      <div class="field-container">
        <label>vat 14 %</label>
        <div class="field">
          <input type="text" name="_invoice_vat" value="<?php echo $_invoice_vat; ?>" >
        </div>
        <p>ضريبه ثابته على كل  طلب داخل السعوديه</p>
      </div>

      <!--
       *** shop order option
      -->
    <?php elseif($post->post_type == 'shop_order'): ?>
      <div class="field-container">
        <label>Tracking No</label>
        <div class="field">
          <input type="number" name="_tracking_no" value="<?php echo $_tracking_no; ?>" >
        </div>
        <p></p>
      </div>

      <div class="field-container">
        <label>_task_next_action</label>
        <div class="field">
          <!-- readonly -->
          <input type="text" name="_task_next_action" value="<?php echo $_task_next_action; ?>" readonly>
        </div>
        <p></p>
      </div>
      <?php
        $args = array(
          'posts_per_page' => -1,
          'ignore_sticky_posts' => 1,
          'no_found_rows' => true,
          'post_type' => 'invoices',
          'orderby' => 'post__in'
        );
        $the_query = new WP_Query( $args );
       ?>
      <div class="field-container">
        <label>Select Your invoice</label>
        <div class="field">
          <select name="_invoices_selected" >
            <option value="">choose invoice</option>
            <?php
              while ( $the_query->have_posts() ) : $the_query->the_post();
                $invoice_name = get_the_title();
                echo '<option' . selected($_invoices_selected, $invoice_name ) . ' value="' . $invoice_name . '">' . $invoice_name . '</option>';
              endwhile;
              wp_reset_postdata();
              unset($the_query);
             ?>
          </select>
        </div>
      </div>
      <div class="field-container">
        <label>Delivery Method</label>
        <div class="field">
          <select name="_delivery_method" >
            <option value="">choose delivery method</option>
            <?php
              echo '<option' . selected($_delivery_method, 'store' ) . ' value="store">Store</option>';
              echo '<option' . selected($_delivery_method, 'home' ) . ' value="home">Home</option>';
             ?>
          </select>
        </div>
      </div>

      <div class="field-container">
        <label>smsa attach</label>
        <div class="field">
          <input type="text" class="upload-input" name="_upload_invoice_1" value="<?php echo $_upload_invoice_1; ?>" >
          <input type="submit" class="upload_image_button" value="upload" >
        </div>
      </div>
      <div class="field-container">
        <label>any invoice</label>
        <div class="field">
          <input type="text" class="upload-input" name="_any_invoice" value="<?php echo $_any_invoice; ?>" >
          <input type="submit" class="upload_image_button" value="upload" >
        </div>
      </div>

      <!-- <div class="field-container">
        <label>Call Done</label>
        <select class="form-control" name="_call_done">
          <option value="">Call Done</option>
          <option <?php selected('yes', $_call_done );?> value="yes" >yes</option>
          <option <?php selected('no', $_call_done );?> value="no" >no</option>
        </select>
      </div> -->

      <?php $sales_agents = $agents_class->sales_agents(); ?>
      <div class="field-container">
        <label>sales name</label>
        <select class="form-control" name="_order_agent_name">
          <option value="">sales name</option>
          <?php foreach( $sales_agents as $ID => $agent ) { ?>
            <option <?php selected($ID, $_order_agent_name );?> value="<?php echo $ID; ?>" ><?php echo $agent['name']; ?></option>
          <?php } ?>
        </select>
      </div>
      <?php $followup_agents = $agents_class->followup_agents(); ?>
      <div class="field-container">
        <label>followup agent name</label>
        <select class="form-control" name="_order_followup_agent_name">
          <option value="">followup agent name</option>
          <?php foreach( $followup_agents as $ID => $agent ) { ?>
            <option <?php selected($ID, $_order_followup_agent_name );?> value="<?php echo $ID; ?>" ><?php echo $agent['name']; ?></option>
          <?php } ?>
        </select>
      </div>
      <div class="field-container">
        <label>Order code</label>
        <div class="field">
          <input type="text" name="_order_code" value="<?php echo $_order_code; ?>" >
        </div>
        <p></p>
      </div>

      <div class="field-container">
        <label>shipping track</label>
        <?php
        $shipping_tracking =  $agents_class->shipping_tracking();
        ?>
        <select class="form-control" name="_shipping_tracking">
          <option value="">select shipping track</option>
          <?php foreach( $shipping_tracking as $tracking_key => $tracking_value ) { ?>
            <option value="<?php echo $tracking_key; ?>" <?php selected(  $tracking_key, $_shipping_tracking );?>><?php echo $tracking_value; ?></option>
          <?php } ?>
        </select>
      </div>
      <div class="field-container">
        <label>order followUp</label>
        <?php
        $order_followup =  $agents_class->order_followup();
        ?>
        <select class="form-control" name="_order_followup">
          <option value="">select followUp</option>
          <?php foreach( $order_followup as $followup_key => $followup_value ) { ?>
            <option value="<?php echo $followup_key; ?>" <?php selected(  $followup_key, $_order_followup );?>><?php echo $followup_value; ?></option>
          <?php } ?>
        </select>
      </div>
      <div class="field-container">
        <label>اختر جهت التوصيل</label>
        <?php
        $delivery_company =  $agents_class->delivery_company();
        ?>
        <div class="field">
          <select name="_order_delivery">
            <option value="">اختر جهت التوصيل</option>
            <?php foreach( $delivery_company as $key => $value ): ?>
              <option <?php selected($key,$_order_delivery); ?> value="<?php echo $key?>"><?php echo $value; ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="field-container">
        <label>اختار المكان الذي سيتم الارجاع الية</label>
        <?php
        $delivery_company =  $agents_class->delivery_company();
        ?>
        <div class="field">
          <select name="_order_return_to">
            <option value="">اختر جهة الارجاع</option>
            <?php foreach( $delivery_company as $key => $value ): ?>
              <option <?php selected($key,$_order_return_to); ?> value="<?php echo $key?>"><?php echo $value; ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <!--
      <div class="field-container">
        <label>_invoice_delivery_done</label>
        <div class="field">
          <input type="text" name="_invoice_delivery_done" value="<?php echo $_invoice_delivery_done; ?>" readonly>
        </div>
        <p></p>
      </div>
      <div class="field-container">
        <label>_invoice_agent_done</label>
        <div class="field">
          <input type="text" name="_invoice_agent_done" value="<?php echo $_invoice_agent_done; ?>" readonly>
        </div>
        <p></p>
      </div>
      <div class="field-container">
        <label>_invoice_vendor_done</label>
        <div class="field">
          <input type="text" name="_invoice_vendor_done" value="<?php echo $_invoice_vendor_done; ?>" readonly>
        </div>
        <p></p>
      </div> -->
      <!-- agents option -->
      <div class="field-container">
        <label>campaign name</label>
        <div class="field">
          <input type="text" name="_campaign_name" value="<?php echo $_campaign_name; ?>" >
        </div>
        <p></p>
      </div>
      <div class="field-container">
        <label>utm content</label>
        <div class="field">
          <input type="text" name="_utm_content" value="<?php echo $_utm_content; ?>" >
        </div>
        <p></p>
      </div>
      <!-- <div class="field-container">
        <label>Shipped date</label>
        <div class="field">
          <input type="datetime-local" name="_wc-shipped_date" value="" >
        </div>
        <p></p>
      </div>
      <div class="field-container">
        <label>delivered and cancelled date</label>
        <div class="field">
          <input type="datetime-local" name="_delivered_and_cancelled" value="<?php echo $_delivered_and_cancelled; ?>" >
        </div>
        <p></p>
      </div> -->
      <div class="field-container">
        <label>Telegram Done</label>
        <div class="field">
          <input type="text" name="_telegram_done" value="<?php echo $_telegram_done; ?>" readonly>
        </div>
        <p></p>
      </div>
    <?php elseif($post->post_type == 'agents'): ?>
      <div class="field-container">
        <label>agent number</label>
        <div class="field">
          <input type="number" name="_agent_number" value="<?php echo $_agent_number; ?>" >
        </div>
        <p></p>
      </div>
      <div class="field-container">
        <label>agent quota number</label>
        <div class="field">
          <input type="number" name="_agent_quota_number" value="<?php echo $_agent_quota_number; ?>" >
        </div>
      </div>
      <div class="field-container">
        <label> agent work hours start</label>
        <div class="field">
          <input type="time" name="_agent_work_hours_start" value="<?php echo $_agent_work_hours_start; ?>" >
        </div>
      </div>
      <div class="field-container">
        <label> agent work hours end</label>
        <div class="field">
          <input type="time" name="_agent_work_hours_end" value="<?php echo $_agent_work_hours_end; ?>" >
        </div>
      </div>

    <?php //elseif($post->post_type == 'post'): ?>



    <?php endif;
}

function marketer_custom_meta_box() {
  global $post,$inputs_array;
  global $agents_class;
  foreach((array) $inputs_array as $value){
    $$value = get_post_meta($post->ID, $value, true);
  }

  ?>


  <input type="hidden" name="custom_meta_box_nonce" value="<?php echo wp_create_nonce(basename(__FILE__)) ?>" />
  <input type="hidden" name="gamageer_saved_before" value="1" />
  <?php

if(false /*$post->post_type == 'product'*/){   ?>
<!--
    <div class="field-container">
      <label>العموله الخاصه بالمسوق</label>
      <div class="field">
        <input type="number" name="_marketer_commission" value="<?php echo $_marketer_commission; ?>" >
      </div>
    </div>

    <div class="field-container">
      <label>شرح المنتج</label>
      <div class="field">
        <textarea name="_product_short_dec" rows="8" cols="80"> <?php echo $_product_short_dec; ?> </textarea>
      </div>
    </div>
    <div class="field-container">
      <label>وصف المنتج</label>
      <div class="field">
        <textarea name="_product_description" rows="8" cols="80"> <?php echo $_product_description; ?> </textarea>
      </div>
    </div>
    <div class="field-container">
      <label>كيفيه الاستخدام</label>
      <div class="field">
        <textarea name="_how_to_use" rows="8" cols="80"><?php echo $_how_to_use; ?></textarea>
      </div>
    </div>
    <div class="field-container">
      <label>الفيديو الخاص بالمنتج</label>
      <div class="field">
        <input type="text" class="upload-input" name="_marketer_video" value="<?php echo $_marketer_video; ?>" >
        <input type="submit" class="upload_image_button" value="upload" >
      </div>
    </div> -->

 <?php } elseif($post->post_type == 'page'){
  ?>
   <div class="field-container">
     <label>اظهار عنوان الصفحه</label>
     <div class="field">
       <select name="_khy_hide_post_title">
         <option>اخفاء العنوان</option>
         <option <?php selected($_khy_hide_post_title,'yes'); ?> value="yes">yes</option>
         <option <?php selected($_khy_hide_post_title,'no'); ?> value="no">no</option>
       </select>
     </div>
   </div>

   <div class="field-container">
     <label>اخفاء فراغات الصفحة</label>
     <div class="field">
       <select name="_khy_no_padding">
         <option>اخفاء الفراغات</option>
         <option <?php selected($_khy_no_padding,'yes'); ?> value="yes">yes</option>
         <option <?php selected($_khy_no_padding,'no'); ?> value="no">no</option>
       </select>
     </div>
   </div>

   <div class="field-container">
     <label>اختار المنتج</label>
     <div class="field">
       <select id="media_buyer_product" name="_media_buyer_product_selected">
         <option value="0">اختار المنتج</option>
         <?php
         global $khy_product_for_media_buyer;
         // var_dump($khy_product_for_media_buyer);
         // echo "<option >".var_dump($khy_product_for_media_buyer)."</option>";
         foreach ($khy_product_for_media_buyer as $key => $value) {
           ?>
           <option <?php selected($_media_buyer_product_selected, $value['id']); ?> value="<?php echo $value['id'];?>" data-country='<?php echo $value['country'];?>' data-price='<?php echo $value['price']; ?>' data-image='<?php echo $value['image']; ?>'><?php echo $value['title'] .' - '.$value['price']. ' - '.$value['country'];?></option>
           <?php
         }
          // echo khy_product_for_media_buyer();
         ?>
       </select>
       <div class="fake-select">
         <?php
         $i=1;
          foreach ($khy_product_for_media_buyer as $key => $value) { ?>
            <?php $selected =  ( $_media_buyer_product_selected == $value['id'] && !empty($_media_buyer_product_selected) ) ? 'selected' : ''; ?>
           <?php $first_load =  ( $i == 1 &&  empty($_media_buyer_product_selected) ) ? 'selected' : ''; ?>

           <div class="fake-option <?php echo $selected . $first_load; ?> " data-id="<?php echo $value['id'];?>">
             <div class="thumbnail">
               <img class="image" src="<?php echo $value['image']; ?>" alt="<?php echo $value['title'] ?>">
             </div>
             <div class="text">
               <div class="title">
                 <?php echo $value['title'] ?>
               </div>
               <div class="detal">
                 <span class="country">country : <?php echo $value['country'];?></span>
                 <span class="price">price : <?php echo $value['price'];?></span>
                 <span class="product_id">product id : <?php echo $value['id'];?></span>
               </div>
             </div>
           </div>
         <?php $i++; }  ?>
       </div>
       <!-- <div class="product_info_div">
         <div class="info"></div>
         <img class="image" src="" alt="" width="120" height="120">
       </div> -->
     </div>
   </div>

     <div class="field-container">
       <label>fake title</label>
       <div class="field">
         <input type="text" name="_fake_title" value="<?php echo $_fake_title; ?>" >
       </div>
     </div>
     <div class="field-container">
       <label>fake content</label>
       <div class="field">
         <?php wp_editor( $_fake_content, '_fake_content'); ?>
       </div>
     </div>

 <?php }
}

function my_admin_init() {
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_style('jquery.ui.theme', get_template_directory_uri() . '/assets/css/jquery-ui-1.10.2.custom.min.css');
}
add_action('admin_init', 'my_admin_init');
add_action('admin_enqueue_scripts', function() { wp_enqueue_media(); });
function my_admin_footer() {
	?>
	<script type="text/javascript">

  jQuery(document).ready(function($){



  // Uploading files
  let file_frame;

  jQuery('.upload_image_button').on('click', function( event ){

    let currentInput = $(this);

    event.preventDefault();

    // If the media frame already exists, reopen it.
    if ( file_frame ) {
      file_frame.open();
      return;
    }

    // Create the media frame.
    file_frame = wp.media.frames.file_frame = wp.media({
      title: jQuery( this ).data( 'uploader_title' ),
      button: {
        text: jQuery( this ).data( 'uploader_button_text' ),
      },
      multiple: false  // Set to true to allow multiple files to be selected
    });

    // When an image is selected, run a callback.
    file_frame.on( 'select', function() {
      // We set multiple to false so only get one image from the uploader
      attachment = file_frame.state().get('selection').first().toJSON();
      currentInput.prev('.upload-input').attr('value',attachment.url );
      // Do something with attachment.id and/or attachment.url here
    });

    // Finally, open the modal
    file_frame.open();
  });


	});
	</script>
	<?php
}
add_action('admin_footer', 'my_admin_footer');


function checkbox_proccess($list){
   if(!empty($list)){
        if(is_array($list) && count($list) >= 1){
          $list = implode(",",$list);
        }
    }
    return $list;
}

// Save the Data
function save_custom_meta($post_id) {
	global $inputs_array;
	// verify nonce
	if(empty($_POST['custom_meta_box_nonce'])){
    $_POST['custom_meta_box_nonce'] = '';
  }
  if (!wp_verify_nonce($_POST['custom_meta_box_nonce'], basename(__FILE__)))
		return $post_id;

	// check autosave
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		return $post_id;

	// check permissions
	if ('page' == $_POST['post_type']) {
		  if (!current_user_can('edit_page', $post_id))
			return $post_id;
	} elseif (!current_user_can('edit_post', $post_id)) {
			return $post_id;
	}

  // loop through fields and save the data
	foreach ($inputs_array as $value) {

    $old = get_post_meta($post_id, $value, true);

    if(!empty($_POST[$value])){
      $new = trim(checkbox_proccess($_POST[$value]));
    } else {
      $new ='';
    }

		if ($new && $new != $old) {
			update_post_meta($post_id, $value, $new);
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $value, $old);
		}
	} // enf foreach

	// save taxonomies
	//$post = get_post($post_id);
	//$category = $_POST['category'];
	//wp_set_object_terms( $post_id, $category, 'category' );
}
add_action('save_post', 'save_custom_meta');
