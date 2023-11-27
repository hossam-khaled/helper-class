<?php
class khafagy_form {

    public $elements;
    public $errors;
    public $ajax_errors;
    public $is_submit;
    private $mailhide_pubkey;
    private $mailhide_privkey;
    private $is_multiple;



    function __construct($form_name,$elements) {
      $this->form_name = $form_name;
      $this->elements = $elements;
      $this->is_submit = false;
      $this->manual_values = false;
      $this->ajax_errors = array();
      $this->is_multiple = $multiple;
      $this->recapatcha_pubkey = '6LeyPs0SAAAAACaLBST1RZXW2yqO0RtIbYgqNY9M';
      $this->recapatcha_privkey = '6LeyPs0SAAAAAMYYAABqG-zyybLSPmFUPl0t-ZjU';
      //require_once (get_template_directory() . '/inc/recaptcha/recaptchalib.php');

      if ( $_POST["$form_name"] ) {
        $this->check_errors();
        $this->is_submit = true;
      }

    }

    function clear_form() {
      foreach( $_POST as $key => $value ) {
       unset( $_POST["$key"] );
      }
    }

    function set_elements($elements) {
      $this->elements = $elements;
    }



    public function add_error($key,$value) {
      $this->errors["$key"] = $value;
    }

    function check_errors() {

      // $resp = recaptcha_check_answer ($this->recapatcha_privkey, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
      //
      // if ( array_key_exists ( 'recapatcha',  $this->elements ) && $resp->is_valid == false )
      //     $this->errors["capatcha"] =  __( "خطأ بكود التاكيد", 'khafagy' );

      foreach( $this->elements as $key => $value ) {
        $accept_file_formats = array(
      		'image/jpg',
      		'image/jpeg',
      		'image/png',
      		'image/gif',
      		'text/rtf', //.rtf file
      		'text/plain', //.txt file
      		'application/msword', //.doc file
      		'application/vnd.openxmlformats-officedocument.wordprocessingml.document', //.docx file
      		'application/pdf' //.pdf file
      	);

        if( in_array( 'required',(array) $value['validation'] ) && ( empty( $_POST["$key"] ) && empty( $_FILES["$key"]['name'] ) ) ) {
          if( in_array( 'one_exists',(array) $value['validation'] ) && !empty( $_POST["{$value['validation']['pair']}"] ) ) continue;
          $this->errors["$key"] =  sprintf( __( "الحقل '%s' مطلوب", 'khafagy' ) ,$value['label'] );
        } elseif( in_array( 'required',(array) $value['validation'] ) && in_array( 'none', (array) $_POST["$key"] ) ) {
          $this->errors["$key"] =  sprintf( __( "the field '%s' contains invalid date", 'khafagy' ) ,$value['label'] );
        } elseif( in_array( 'email',(array) $value['validation'] ) && !is_email( $_POST["$key"] ) && !empty(  $_POST["$key"] ) ) {
          $this->errors["$key"] = __( 'البريد الالكترونى خطأ', 'khafagy' );
        } elseif( in_array( 'phone_number',(array) $value['validation'] ) && !preg_match("/^\+[0-9]+$/", $_POST["$key"]) && !empty( $_POST["$key"] )  ) {
          $this->errors["$key"] =  sprintf( __( "%s Phone number must be in international format", 'khafagy' ) ,$value['label'] );
        } elseif( isset( $value['validation']['type'] ) && $value['validation']['type'] == 'number' && !is_numeric( $_POST["$key"] ) && !empty(  $_POST["$key"] )  ) {
          $this->errors["$key"] = sprintf( __( "the field '%s' must contains numbers only", 'khafagy' ) ,$value['label'] );
        } elseif( isset( $value['match'] ) &&  $_POST["$key"] != $_POST["{$value['match']}"]  ) {
          $this->errors["$key"] = sprintf( __( "the field '%s' and '%s' should match", 'khafagy' ) ,$value['label'], $this->elements["{$value['match']}"]['label']  );
        }  elseif( $value['type'] == 'file' && !empty($_FILES["$key"]["name"]) && ( !in_array($_FILES["$key"]['type'], $accept_file_formats) || $_FILES["$key"]["size"] > (40 * 1000000) )  ) {
          $this->add_error( $name, __('رجاء اختيار صور او ملفات نصية بحد اقصى 40 ميجا', 'khafagy') );
        } elseif( $value['type'] == 'file' && !empty($_FILES["$key"]["name"]) && $_FILES["$key"]["error"] < 0  ) {
          $this->add_error( $name, __('خطأ برفع الملف', 'khafagy') );
        } elseif(false && $value['type'] == 'select' && !empty( $_POST["$key"] ) &&  !array_key_exists($_POST["$key"], $value['options'] )  ) {
          // var_dump($key);
          $this->add_error( $name, __('Invalid old trick', 'khafagy') );
        }
        //
      }
      return $this->errors;
    }

    function has_errors(){
      if ( !empty( $this->errors ) ) return true;
      return false;
    }

    function display_errors(){
      foreach ($this->errors as $key => $value) {
        echo '<div class="single-error updated below-h2 error">'.$value.'</div>';
      }
    }

    function ajax_validate( $array = array() ) {
      if ( count($array) < 1 || empty( $array ) ) return true;
      foreach ( $array as $form ) {
        parse_str ( $form, $args );
        foreach( $args as $key => $value ) {
          $_POST["$key"] = $value;
        }
        $this->check_errors();
        $this->ajax_errors[] = $this->errors;
        $this->errors = array();
      }
      return $this->ajax_errors;
    }

    function has_ajax_errors(){
      foreach( $this->ajax_errors as $error ) {
        if( count($error) > 0) return true;
      }
      return false;
    }

    function get_uploaded_file_name($name) {
      $file_name = $_FILES["$name"]['name']; //Get the readable file name
  		$file_base = dirname($_FILES["$name"]['tmp_name']); //Get The Temp Directory defined by PHP
  		$file_tmp_name = basename($_FILES["$name"]['tmp_name']); //Get PHP's version of the temporary file name
  		rename($file_base.'/'.$file_tmp_name, $file_base.'/'.$file_name); //Rename the temporary file to a readable one
  		$file_attachment_url = $file_base.'/'.$file_name;
  		return $file_attachment_url; //store the file in an array we will send with the email later.
    }

    function save_uploaded_file($name) {
      if ( ! function_exists( 'wp_handle_upload' ) ) require_once( ABSPATH . 'wp-admin/includes/file.php' );
      $uploadedfile = $_FILES["$name"];
      $upload_overrides = array( 'test_form' => false );
      $movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
      if ( $movefile['url'] ) {
          return $movefile['url'];
      }
      return false;
    }


    function proccess_date($name) {
      return $_POST["$name"]['day'].'/'.$_POST["$name"]['month'].'/'.$_POST["$name"]['year'];
    }

    function set_html_content_type() {
    	return 'text/html';
    }

    function email_form($title, $recipients){

        if( !$this->is_submit || $this->has_errors() ) return false ;

        $attached_files = array();

        $message = '<table style="width:100%">';
          foreach( $this->elements as $key => $value ) {
            $posted_value = $_POST["$key"];

            if( $key == 'recapatcha' || $value['type'] == 'submit' )
              continue;

            if( $value['type'] == 'file' && !empty($_FILES["$key"]['name']) ) {
              $attached_files[] = $this->get_uploaded_file_name($key);
              continue;
            }

            if( $value['type'] == 'jdate' || $value['type'] == 'hdate' )
              $posted_value = $this->proccess_date($key);

            $message .= '<tr><td style="width:30%">'.$value['label'].':</td><td style="width:70%">'.$posted_value.'</td></tr>';
          }
        $message .= '</table>';



        add_filter( 'wp_mail_content_type', array( $this, 'set_html_content_type') );
        wp_mail( $recipients, $title , $message, '', $attached_files );
        remove_filter( 'wp_mail_content_type', array( $this, 'set_html_content_type') );

        //$form->clear_form();

    }

    function draw_hidden_fields() {
      foreach ( $hidden_field as $key => $value ) {
        echo '<div class="title form-label">'.$this->elements["$key"].'</div><div class="field form-control">'.$value.'</div>';
      }
    }

    function add_form_values($manual_values = array() ) {
      foreach ($this->elements as $key => $value) {
        if ( array_key_exists($key, $manual_values) ) {
          $this->elements["$key"]['default'] = isset($manual_values->$key) ? $manual_values->$key : $manual_values["$key"];
        }
      }
    }

    function draw_form() {
        echo '<input type="hidden" name="'.$this->form_name.'" value="1" >';
        foreach ( $this->elements as $key => $value) {
          // var_dump($value);
          if ( $value['type'] == 'protected'  ) continue;
          $field_value = ( !$_POST["$key"] && $value['default'] ) ? $value['default'] : ( $_POST["$key"] ? $_POST["$key"] : '' );
          $disabled = in_array( 'disabled',(array) $value ) ?  'disabled="disabled"' : '';
          $required = in_array( 'required',(array) $value['validation'] ) ?  'required' : '';
          $required .= array_key_exists('min',(array) $value['validation']) ? ' minlength="'.$value['validation']['min'].'"' : '' ;
          $required .= array_key_exists('max',(array) $value['validation']) ? ' maxlength="'.$value['validation']['max'].'"' : '' ;
          $extraclass = array_key_exists('type',(array) $value['validation']) ? $value['validation']['type'] : '' ;
          //var_dump( $value['validation'] );
          if ( $value['type'] == 'hidden' ) {
            echo '<input type="hidden" name="'.$key.'" class="'.$key.'" value="'.$field_value.'" >';
            continue;
          }
          echo '<div class=" clearfix '.( $value['type'] == 'submit' ? 'submit-field col-md-12' : 'single-field col-md-12' ).'">';
            if( $value['type'] != 'submit' ) echo '<div class="title form-label">'.$value['label'].': </div>';
            echo '<div class="the-field">';
              if ( $value['type'] == 'recapatcha'  ) {
                echo '<div class="recapatcha">'.recaptcha_get_html ($this->recapatcha_pubkey, $this->recapatcha_privkey, "mohammedkafagy@gmail.com").'</div>';
              } elseif ( $value['type'] == 'file'  ) {
                echo '<input type="file" name="'.$key.'" placeholder="'.$key.'" value="'.$field_value.'" class="form-control '.$key.'">';
              } elseif ( $value['type'] == 'input'  ) {
                echo '<input type="text" name="'.$key.'" placeholder="'.$key.'"  value="'.$field_value.'" '.$disabled.' class="form-control '.$key.' '.$extraclass.'" '.$required.'>';
              } elseif ( $value['type'] == 'number'  ) {
                echo '<input type="text" name="'.$key.'" placeholder="'.$key.'"  value="'.$field_value.'" '.$disabled.' class="form-control khy-number '.$key.' '.$extraclass.'" '.$required.'>';
              } elseif ( $value['type'] == 'date'  ) {
                // datetime-local
                if ($field_value) {
                  // code...
                  $date = date("Y-m-d\TH:i:s", strtotime($field_value));
                }
                echo '<input type="datetime-local" name="'.$key.'" placeholder="'.$key.'"  value="'.$date.'" '.$disabled.' class="form-control '.$key.' '.$extraclass.'" '.$required.'>';
              } elseif ( $value['type'] == 'password'  ) {
                echo '<input type="password" name="'.$key.'" placeholder="'.$key.'"  value="" class="form-control '.$key.'" '.$required.'>';
              } elseif ( $value['type'] == 'datepicker'  ) {
                echo '<input type="text" name="'.$key.'" placeholder="'.$key.'"  value="'.( $field_value ? date( "d-m-Y",strtotime($field_value) ) : '' ).'" class="form-control '.$key.' datepicker" '.$required.'>';
              } elseif ( $value['type'] == 'select'  ) {
                echo '<select name="'.$key.'" placeholder="'.$key.'"  class="form-select custom-select '.$key.' '.$extraclass.'" '.$required.'>';
                  echo '<option value="">اختر '.$key.'</option>';
                  foreach ( $value['options'] as $option_key => $option_value  ) {
                    echo '<option '.( $field_value == $option_key ? 'selected="selected"' : '' ).' value="'.$option_key.'">'.$option_value.'</option>';
                  }
                echo '</select>';
              } elseif ( $value['type'] == 'select-search'  ) {
                echo '<select name="'.$key.'" data-show-subtext="true" data-live-search="true" class="selectpicker form-control '.$key.' '.$extraclass.'" '.$required.'>';
                  echo '<option value="">اختر '.$key.'</option>';
                  foreach ( $value['options'] as $option_key => $option_value  ) {
                    $name = isset( $option_value['name'] ) ? $option_value['name'] : $option_value;
                    $price = isset( $option_value['price'] ) ? ' | '.$option_value['price'] : '';
                    echo '<option '.( $field_value == $option_key ? 'selected="selected"' : '' ).' value="'.$option_key.'" data-tokens="'.$name.'" >'.$name.$price.'</option>';
                  }
                echo '</select>';
              } elseif ( $value['type'] == 'multiselect'  ) {
                // if ( $field_value ) var_dump( $field_value );
                echo '<select name="'.$key.'[]" class="multiselect '.$key.' multiple"  multiple '.$required.'>';
                  // echo '<option value="">اختر '.$key.'</option>';
                  foreach ( $value['options'] as $option_key => $option_value  ) {
                    echo '<option '.( in_array( $option_key ,(array) $field_value ) ? 'selected="selected"' : '' ).' value="'.$option_key.'">'.$option_value.'</option>';
                  }
                echo '</select>';
              } elseif ( $value['type'] == 'textarea'  ) {
                echo '<textarea name="'.$key.'" class="form-control '.$key.'" '.$required.'>'.$field_value.'</textarea>';
              } elseif ( $value['type'] == 'jdate'  ) {
                $julian_months = array( 1 => __("January", "khafagy") ,2 => __("February", "khafagy") ,3 => __("March", "khafagy") ,4 => __("April", "khafagy") ,5 => __("May", "khafagy") ,6 => __("June", "khafagy") ,7 => __("July", "khafagy") ,8 => __("August", "khafagy") ,9 => __("September", "khafagy") ,10 => __("October", "khafagy") ,11 => __("November", "khafagy") ,12 => __("December", "khafagy") );
                //var_dump( $field_value );
                echo '<div class="clearfix date-row">';
                  echo '<select name="'.$key.'[day]'.( $value['multiple'] ? '[]' : '' ).'" class="'.$key.'">';
                    echo '<option value="none">'.__("Day", "khafagy").'</option>';
                    for ( $i = 1; $i <= 31; $i++  ) {
                      echo '<option '.( $field_value['day'] == $i ? 'selected="selected"' : '' ).' value="'.$i.'">'.$i.'</option>';
                    }
                  echo '</select>';
                  echo '<select name="'.$key.'[month]'.( $value['multiple'] ? '[]' : '' ).'" class="'.$key.'">';
                    echo '<option value="none">'.__("Month", "khafagy").'</option>';
                    foreach ( $julian_months as $option_key => $option_value  ) {
                      echo '<option '.( $field_value['month'] == $option_key ? 'selected="selected"' : '' ).' value="'.$option_key.'">'.$option_value.'</option>';
                    }
                  echo '</select>';
                  echo '<select name="'.$key.'[year]'.( $value['multiple'] ? '[]' : '' ).'" class="'.$key.'">';
                    echo '<option value="none">'.__("Year", "khafagy").'</option>';
                    for ( $i = date('Y') ; $i >= 1999 ; $i--  ) {
                      echo '<option '.( $field_value['year'] == $i ? 'selected="selected"' : '' ).' value="'.$i.'">'.$i.'</option>';
                    }
                  echo '</select>';
                echo '</div>';
              } elseif ( $value['type'] == 'hdate' ) {
                if ( !class_exists('uCal') ) {
                  include_once( get_template_directory() . '/inc/uCal.class.php');
                }
                 $d = new uCal;
                $d->setLang("ar");
                $hajri_year = $d->date("Y", current_time('timestamp') );

                $islamic_months = array( 1=> __("Muharram", "khafagy"), 2=> __("Safar", "khafagy"), 31=> __("Rabi' I", "khafagy"), 4=> __("Rabi' II", "khafagy"), 5=> __("Jumada I", "khafagy"), 6=> __("Jumada II", "khafagy"), 7=> __("Rajab", "khafagy"), 8=> __("Sha'aban", "khafagy"), 9=> __("Ramadan", "khafagy"), 10=> __("Shawwal", "khafagy"), 11=> __("Dhu al-Qi'dah", "khafagy"), 12=> __("Dhu al-Hijjah", "khafagy") );
                //var_dump( $field_value );
                echo '<div class="clearfix date-row">';
                  echo '<select name="'.$key.'[day]'.( $value['multiple'] ? '[]' : '' ).'" class="'.$key.'" '.$required.'>';
                    echo '<option value="none">'.__("Day", "khafagy").'</option>';
                    for ( $i = 1; $i <= 31; $i++  ) {
                      echo '<option '.( $field_value['day'] == $i ? 'selected="selected"' : '' ).' value="'.$i.'">'.$i.'</option>';
                    }
                  echo '</select>';
                  echo '<select name="'.$key.'[month]'.( $value['multiple'] ? '[]' : '' ).'" class="'.$key.'" '.$required.'>';
                    echo '<option value="none">'.__("Month", "khafagy").'</option>';
                    foreach ( $islamic_months as $option_key => $option_value  ) {
                      echo '<option '.( $field_value['month'] == $option_key ? 'selected="selected"' : '' ).' value="'.$option_key.'">'.$option_value.'</option>';
                    }
                  echo '</select>';
                  echo '<select name="'.$key.'[year]'.( $value['multiple'] ? '[]' : '' ).'" class="'.$key.'" '.$required.'>';
                    echo '<option value="none">'.__("Year", "khafagy").'</option>';
                    for ( $i = $hajri_year ; $i >= 1420 ; $i--  ) {
                      echo '<option '.( $field_value['year'] == $i ? 'selected="selected"' : '' ).' value="'.$i.'">'.$i.'</option>';
                    }
                  echo '</select>';
                echo '</div>';
              } elseif ( $value['type'] == 'submit'  ) {
                echo "<div class='d-grid gap-2 col-6 mx-auto'>";
                  echo '<input type="submit" class="btn btn-primary mt-3 btn-block '.$key.' button-primary" name="submit" value="'.$value['label'].'" >';
                echo "</div>";
              }

            echo '</div>';
          echo '</div>';
        }


    }


}
