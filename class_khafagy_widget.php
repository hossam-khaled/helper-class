<?php
/**
 * Khafagy Widget Class
 */
 // if( strpos($_SERVER['HTTP_HOST'], 'alkhafji') === false ) {
 //  die();
 // }
class khafagy_widget extends WP_Widget {



  public $class_name;
  public $widget_name;
  public $theme_name;

  function __construct() {

    $args = $this->backend();

    $defaults = array(
      'widget_name' => 'Please Set name',
      'description' => '',
      'extra_class' => '',
      'options' => array(),
    );

    $this->args = wp_parse_args( $args, $defaults );
    $theme_info = wp_get_theme();
    $this->theme_name = $theme_info->get('Name');
    $this->class_name = get_class($this);

    $widget_args = array(
      'classname' => 'widget_'.$this->class_name. '  ' .$this->args['extra_class'],
      'description' => $this->args['description'],
    );

    ;

    parent::__construct( $this->class_name, $this->theme_name.' - '.$this->args['widget_name'], $widget_args );

  }


  function form( $instance ) {

    $defaults = array();
    foreach ( $this->args['options'] as $option ) {
      $option_name = $option['name'];
      $defaults["$option_name"] = '';
    }
    ?>
    <div class="widget-options">
      <?php
      $instance = wp_parse_args( (array) $instance, $defaults );
      $image_path = get_stylesheet_directory_uri(). '/images/admin/';
      //var_dump($instance);
      foreach( $this->args['options'] as $option ) {
        $option_name = $option['name'];

        // create upload thumbnail
        if( ( $option['type'] == 'upload' && !empty( $instance["$option_name"] ) ) || ( $option['type'] == 'background' && !empty( $instance["$option_name"]["image"] ) )  ) {
          $value = ( $option['type'] == 'upload' ) ? $instance["$option_name"] : $instance["$option_name"]["image"];
          $attachment_url = wp_get_attachment_url( $value );
          $attachment_filename =  basename( $attachment_url );
          if( file_has_image_extension( $attachment_filename ) ) {
            $file_holder = '<span class="uploaded-media"><a target="_blank" href="' . $attachment_url . '"><img src="' . $attachment_url . '"></a></span>';
            $file_holder .= '<span id="remove-image" class="button">حذف</span>';
          } else {
            $file_holder = '<span class="uploaded-media"><a target="_blank" href="' . $attachment_url . '">'. $attachment_filename .'</a></span>';
            $file_holder .= '<span id="remove-image" class="button">حذف</span>';
          }
        }

        // empty typography fields
        if( $option['type'] == 'typography' && empty( $instance["$option_name"] ) ) {
          $typography_elements = array(
            'font_size',
            'line_height',
            'bold',
            'color'
          );
          foreach( $typography_elements as $key ) {
            $instance["$option_name"]["$key"] = '';
          }
        } elseif( $option['type'] == 'background' && empty( $instance["$option_name"] ) ) {
          $backgound_elements = array(
            'color',
            'image',
            'position',
            'size',
            'repeat'
          );
          foreach( $backgound_elements as $key ) {
            $instance["$option_name"]["$key"] = '';
          }
        }
        //var_dump( );
        //$feat_image_url = wp_get_attachment_url( get_post_thumbnail_id() );
        ?>
        <div class="option_<?php echo $option_name;?> single-option">
          <label for="<?php echo $this->get_field_id( $option_name ); ?>"><?php echo $option['label'];?></label>
          <div class="the-input">
            <?php if( $option['type'] == 'input' ) { ?>
                <input type="text" class="widefat" id="<?php echo $this->get_field_id( $option_name ); ?>" name="<?php echo $this->get_field_name( $option_name ); ?>" value="<?php echo $instance["$option_name"] ?>">
            <?php } elseif( $option['type'] == 'textarea' ) { ?>
                <textarea class="widefat" id="<?php echo $this->get_field_id( $option_name ); ?>" name="<?php echo $this->get_field_name( $option_name ); ?>"><?php echo $instance["$option_name"] ?></textarea>
            <?php } elseif( $option['type'] == 'editor' ) { ?>
                <?php wp_editor( $instance["$option_name"], $this->get_field_id( $option_name ), array( 'media_buttons' => false, 'textarea_name' => $this->get_field_name( $option_name ) ) ); ?>
            <?php } elseif( $option['type'] == 'select' ) { ?>
                <select class="widefat" id="<?php echo $this->get_field_id( $option_name ); ?>" name="<?php echo $this->get_field_name( $option_name ); ?>">
                  <?php foreach( $option['values'] as $value ) { ?>
                    <option <?php selected( $instance["$option_name"], $value['name'] ); ?> value="<?php echo $value['name']; ?>"><?php echo $value['label']; ?></option>
                  <?php } ?>
                </select>
            <?php } elseif( $option['type'] == 'radio' ) { ?>
                <span class="radios">
                  <?php foreach( $option['values'] as $value ) { ?>
                    <span class="radio"><input type="radio" <?php checked( $instance["$option_name"], $value['name'] ); ?> name="<?php echo $this->get_field_name( $option_name ); ?>" value="<?php echo $value['name'] ?>" > <?php echo $value['label']; ?> </span>
                  <?php } ?>
                </span>
            <?php } elseif( $option['type'] == 'checkbox' ) { ?>
                <span class="checkboxs">
                  <?php foreach( $option['values'] as $value ) { ?>
                    <span class="checkbox"><input type="checkbox" <?php if( in_array( $value['name'], (array) $instance["$option_name"] ) ) echo 'checked="checked"'; ?> name="<?php echo $this->get_field_name( $option_name ); ?>[]" value="<?php echo $value['name'] ?>" ><?php echo $value['label']; ?></span>
                  <?php } ?>
                </span>
            <?php } elseif( $option['type'] == 'background' ) { ?>
                <?php if( !empty( $instance["$option_name"]['image'] ) ) echo $file_holder; ?>
                <?php submit_button( 'رفع الملف', 'secondary', '', false, array( 'id' => 'khafagy-upload-button' ) ); ?>

                <input type="hidden" class="khafagy-upload-input" id="<?php echo $this->get_field_id( $option_name ); ?>[image]" name="<?php echo $this->get_field_name( $option_name ); ?>[image]" value="<?php echo $instance["$option_name"]["image"]; ?>">
                <input type="text" class="color-picker-field" id="<?php echo $this->get_field_id( $option_name ); ?>[color]" name="<?php echo $this->get_field_name( $option_name ); ?>[color]" value="<?php echo (string) $instance["$option_name"]["color"]; ?>">
                <?php
                $background_repeats = array(
                  'no-repeat' => 'no-repeat',
                  'repeat-x' => 'repeat-x',
                  'repeat-y' => 'repeat-y',
                );
                ?>
                <select class="small" id="<?php echo $this->get_field_id( $option_name ); ?>[repeat]" name="<?php echo $this->get_field_name( $option_name ); ?>[repeat]">
                  <?php foreach( $background_repeats as $key => $value ) { ?>
                    <option <?php selected( $instance["$option_name"][repeat], $key ); ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
                  <?php } ?>
                </select>
                <input type="text" class="small" id="<?php echo $this->get_field_id( $option_name ); ?>[position]" name="<?php echo $this->get_field_name( $option_name ); ?>[position]" value="<?php echo $instance["$option_name"]["position"]; ?>" placeholder="Position">
                <input type="text" class="small" id="<?php echo $this->get_field_id( $option_name ); ?>[size]" name="<?php echo $this->get_field_name( $option_name ); ?>[size]" value="<?php echo $instance["$option_name"]["size"]; ?>" placeholder="size">

            <?php } elseif( $option['type'] == 'typography' ) { ?>
                <input type="number" class="small" placeholder="حجم الخط" id="<?php echo $this->get_field_id( $option_name ); ?>[font_size]" name="<?php echo $this->get_field_name( $option_name ); ?>[font_size]" value="<?php echo (string) $instance["$option_name"]["font_size"]; ?>">
                <input type="number" class="small" placeholder="طول السطر" id="<?php echo $this->get_field_id( $option_name ); ?>[line_height]" name="<?php echo $this->get_field_name( $option_name ); ?>[line_height]" value="<?php echo (string) $instance["$option_name"]["line_height"]; ?>">
                <?php
                $font_weights = array(
                  'normal' => 'خط عادى',
                  'bold' => 'خط عريض'
                );
                ?>
                <select class="small" id="<?php echo $this->get_field_id( $option_name ); ?>[bold]" name="<?php echo $this->get_field_name( $option_name ); ?>[bold]">
                  <?php foreach( $font_weights as $key => $value ) { ?>
                    <option <?php selected( $instance["$option_name"][bold], $key ); ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
                  <?php } ?>
                </select>
                <?php
                $font_families = array(
                  'default' => 'الأفتراضى',
                  'arial' => 'Arial',
                  'tahoma' => 'Tahoma',
                );
                ?>
                <select class="small" id="<?php echo $this->get_field_id( $option_name ); ?>[family]" name="<?php echo $this->get_field_name( $option_name ); ?>[family]">
                  <?php foreach( $font_families as $key => $value ) { ?>
                    <option <?php selected( $instance["$option_name"][family], $key ); ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
                  <?php } ?>
                </select>
                <input type="text" class="color-picker-field" id="<?php echo $this->get_field_id( $option_name ); ?>[color]" name="<?php echo $this->get_field_name( $option_name ); ?>[color]" value="<?php echo $instance["$option_name"][color]; ?>">
            <?php } elseif( $option['type'] == 'color-picker' ) { ?>
                <input type="text" class="color-picker-field" id="<?php echo $this->get_field_id( $option_name ); ?>" name="<?php echo $this->get_field_name( $option_name ); ?>" value="<?php echo $instance["$option_name"] ?>">
            <?php } elseif( $option['type'] == 'upload' ) { ?>
                <?php if( !empty( $instance["$option_name"] ) ) echo $file_holder; ?>
                <?php submit_button( 'رفع الملف', 'secondary', '', false, array( 'id' => 'khafagy-upload-button' ) ); ?>
                <input type="hidden" class="khafagy-upload-input" id="<?php echo $this->get_field_id( $option_name ); ?>" name="<?php echo $this->get_field_name( $option_name ); ?>" value="<?php echo $instance["$option_name"] ?>">
            <?php } elseif( $option['type'] == 'image-selector' ) { ?>
                <span class="image-selector clearfix">
                  <?php foreach( $option['values'] as $value ) { ?>
                      <span class="<?php echo $style; ?> <?php if( $instance["$option_name"] == $value['name'] ) echo 'active'; ?>" data-option="<?php echo $value['name']; ?>" style="background-image:url(<?php echo $image_path . $value['name'] . '.png' ?>)"></span>
                  <?php } ?>
                  <input type="hidden" class="selected_image" id="<?php echo $this->get_field_id( $option_name ); ?>" name="<?php echo $this->get_field_name( $option_name ); ?>" value="<?php echo $instance["$option_name"] ?>">
                </span>
            <?php } ?>
          </div>
        </div>
        <?php
      }
      ?>
    </div>
    <?php
  }



  public function update( $new_instance, $old_instance ) {
    $instance = $old_instance;
    foreach( $this->args['options'] as $option ) {
      $option_name = $option['name'];
      $instance["$option_name"] = $new_instance["$option_name"];
    }
    return $instance;
  }


  public function widget( $args, $instance ) {
    foreach( $this->args['options'] as $option ) {
      $option_name = $option['name'];
      $instance["$option_name"] = !empty( $instance["$option_name"] ) ? $instance["$option_name"] : ( $option['default'] ? $option['default'] : '' );
    }
    $this->frontend( $args, $instance );
  }

}


class test_widget extends khafagy_widget {

  function backend() {

    // widget settings
    $args['widget_name'] = 'أختبار';
    $args['description'] = '';
    $args['extra_class'] = '';

    //widget options
    $args['options'][] = array(
      'label' => 'الخلفية',
      'name' => 'divbackground',
      'type' => 'background',
      'default' => ''
    );
    $args['options'][] = array(
      'label' => 'العنوان',
      'name' => 'title',
      'type' => 'input',
      'default' => 'محمد'
    );
    //widget options
    $args['options'][] = array(
      'label' => 'العنوان',
      'name' => 'section_content',
      'type' => 'editor',
      'default' => 'محمد'
    );
    //widget options
    $args['options'][] = array(
      'label' => 'الصورة',
      'name' => 'myimage',
      'type' => 'upload'
    );
    $args['options'][] = array(
      'label' => 'اللون',
      'name' => 'color',
      'type' => 'color-picker',
      'default' => '#000'
    );
    $args['options'][] = array(
      'label' => 'اللون',
      'name' => 'header_font2',
      'type' => 'typography',
      'default' => '#000'
    );
    $args['options'][] = array(
      'label' => 'الوصف',
      'name' => 'description',
      'type' => 'checkbox',
      'values' => array(
        array(
          'name' => '1',
          'label' => 'الأول'
        ),
        array(
          'name' => '2',
          'label' => 'الثانى'
        ),
      )
    );
    $args['options'][] = array(
      'label' => 'الشكل',
      'name' => 'style',
      'type' => 'image-selector',
      'values' => array(
        array(
          'name' => 'third',
          'label' => 'الأول'
        ),
        array(
          'name' => 'fourth',
          'label' => 'الثانى',
        ),
      ),

    );

    /*
    to do
    - header
    - sorter
    - info
    - image
    */

    return $args;


  }

  public function frontend( $args, $instance ) {
    extract($args, EXTR_SKIP);
    echo $before_widget;
     var_dump( $instance['description'] );
    echo $after_widget;
  }

}


add_action( 'widgets_init', create_function('', 'return register_widget("test_widget");') );
