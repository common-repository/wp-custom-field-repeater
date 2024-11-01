<?php
add_action( 'admin_init', 'wprp_admin_add_meta_boxes', 3 );
function wprp_admin_add_meta_boxes() {
  add_meta_box( 'wprp-dashboard-fields-group', 'WP REPEATER PLUGIN FIELDS', 'wprp_admin_repeatable_meta_box_display', 'wprp-repeater-fields', 'normal', 'default' );
}

function wprp_admin_repeatable_meta_box_display() {
  global $post;
  $adminSideSavedFields = get_post_meta( $post->ID, 'wprp_admin_customdata_group', true );
  wp_nonce_field( 'wprp_admin_repeatable_meta_box_nonce', 'wprp_admin_repeatable_meta_box_nonce' );
  ?>

  <div id="main-div">
  <div id="second-div">
  <table id="repeatable-fieldset-one" width="100%">
  <tbody>
    <?php
    if ( $adminSideSavedFields ):
        foreach ( $adminSideSavedFields as $fields ) { ?>
	        <div class="container">
	            <div class="row">
	                <div class="col-md-4">
	                  Link this Repeater to :
	                    <select name="LinkTo[]" required>
	                        <option selected="selected" value="<?php if ($fields['LinkTo'] != '') {echo esc_attr($fields['LinkTo']);}?>"><?php if($fields['LinkTo'] != '') {echo esc_attr(get_the_title($fields['LinkTo']));}?>
                            </option>
                             <?php $selected_page = get_option( 'option_key' );
                               $pages = get_pages();
                               foreach ( $pages as $page ) {
                               $option = '<option value="' . $page->ID . '" ';
                               $option .= ($page->ID == $selected_page) ? 'selected="selected"' : '';
                               $option .= '>';
                               $option .= $page->post_title;
                               $option .= '</option>';
                               echo $option;
                             }
                             ?>
	                    </select>
	                </div>
	                <div class="col-md-4">
	                  Repeater Name :
	                  <input type="text"  placeholder="RepeaterName" name="RepeaterName[]" value="<?php if ($fields['RepeaterName'] != '') {echo esc_attr($fields['RepeaterName']);}?>" required/>
	                </div>
	            </div>
	        </div>
	          <?php
            foreach ( $fields['Items'] as $field ) { ?>
            <tr>
	             <td width="40%">
	             Title :
	             <input type="text"  placeholder="Title" name="TitleItem[]" value="<?php if ($field['TitleItem'] != '') {echo esc_attr($field['TitleItem']);}?>" required/>
               </td>
	             <td width ="40%">
	               Field Type:
	               <select name="TitleFieldType[]">
                      <option  value="<?php if ($field['TitleFieldType'] != '') {echo esc_attr($field['TitleFieldType']);}?>">
                        <?php if ($field['TitleFieldType'] != '') {echo esc_attr($field['TitleFieldType']);}?></option>
	                 <?php if ($field['TitleFieldType'] != 'text-box'): ?><option value="text-box">text-box</option> <?php endif?>
                      <?php if ($field['TitleFieldType'] != 'text-area'): ?><option value="text-area">text-area</option><?php endif?>
                      <?php if ($field['TitleFieldType'] != 'image-upload'): ?><option value="image-upload">image-upload</option><?php endif?>
                    </select>
                  </td>
                  <td width="15%"><a class="button remove-row" href="#1">-</a></td>
                    <?php
                } ?>           
            </tr>
    
            <?php  
        } 
    else :
        // show a blank one
        ?>
	    <div class="container">
	        <div class="row">
	          <div class="col-md-4">
	             Link this Repeater to :
	            <select name="LinkTo[]" required>
	              <option selected="selected" disabled="disabled" value=""><?php echo esc_attr(__( 'Select page' )); ?></option>
	              <?php $selected_page = get_option( 'option_key' );
                    $pages = get_pages();
                    foreach ( $pages as $page ) {
                        $option = '<option value="' . $page->ID . '" ';
                        $option .= ($page->ID == $selected_page) ? 'selected="selected"' : '';
                        $option .= '>';
                        $option .= $page->post_title;
                        $option .= '</option>';
                        echo $option;
                     }
                    ?>
	            </select>
	          </div>
	          <div class="col-md-4">
	             Repeater Name :
	            <input type="text"  placeholder="RepeaterName" name="RepeaterName[]" required/>
	          </div>
	        </div>
	    </div>
	    <tr>
	      <td>
	       Title :
	        <input type="text" placeholder="Title" title="Title" name="TitleItem[]" required/></td>
	        <td>
	          Field Type:
	          <select name="TitleFieldType[]" title="TitleFieldType">
	          <option value="text-box">text-box</option>
	          <option value="text-area">text-area</option>
	          <option value="image-upload">image-upload</option>
	          </select>
	        </td>
	      <td><a class="button  cmb-remove-row-button button-disabled" href="#">-</a></td>
	    </tr>
	  <?php endif;?>

    <!-- empty hidden one for jQuery -->
    <tr class="empty-row screen-reader-text">
      <td>
        Title :
        <input class = "empty-input" type="text" placeholder="Title" name="TitleItem[]"/></td>
        <td>
          Field Type:
          <select name="TitleFieldType[]" title="TitleFieldType">
          <option value="text-box">text-box</option>
          <option value="text-area">text-area</option>
          <option value="image-upload">image-upload</option>
          </select>
        </td>
      <td><a class="button remove-row" href="#">-</a></td>
    </tr>
  </tbody>
</table>
<p><a id="add-row" class="button" href="#">+</a></p>
</div>
</div>

<?php
}

add_action('save_post', 'wprp_admin_custom_repeatable_meta_box_save');
function wprp_admin_custom_repeatable_meta_box_save( $post_id ) {
    if (!isset( $_POST['wprp_admin_repeatable_meta_box_nonce'] ) ||
        !wp_verify_nonce( $_POST['wprp_admin_repeatable_meta_box_nonce'], 'wprp_admin_repeatable_meta_box_nonce') ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( !current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    $old = get_post_meta( $post_id, 'wprp_admin_customdata_group', true );
    $new = array();

    if ( isset( $_POST['RepeaterName'] ) ) {
      $repeaterName = array_map( 'sanitize_text_field', $_POST['RepeaterName'] );
    }
     if ( isset( $_POST['LinkTo'] ) ) {
      $linkTo = array_map( 'sanitize_text_field', $_POST['LinkTo'] );
    }
     if ( isset( $_POST['TitleItem'] ) ) {
      $titleItem = array_map( 'sanitize_text_field', $_POST['TitleItem'] );
    }
     if ( isset( $_POST['TitleFieldType'] ) ) {
      $titleFieldType = array_map( 'sanitize_text_field', $_POST['TitleFieldType'] );
    }
    
    $count = count( $titleItem );
    $rCount = count( $repeaterName );

    if( count( $titleItem ) == count( array_unique( $titleItem ) ) ) //checking duplicated entry in fields
    {
        for ( $j = 0; $j < $rCount; $j++ ) {
            $new[$j]['RepeaterName'] = $repeaterName[$j];
            $new[$j]['LinkTo'] = $linkTo[$j];

            for ( $i = 0; $i < $count; $i++ ) {
                if ( $titleItem[$i] != '' ):
                    $new[$j]['Items'][$i]['TitleItem'] = $titleItem[$i];
                    $new[$j]['Items'][$i]['TitleFieldType'] = $titleFieldType[$i]; 
                endif;
            }
        }

        if ( !empty( $new ) && $new != $old ) {
            update_post_meta( $post_id, 'wprp_admin_customdata_group', $new );
        } elseif ( empty( $new ) && $old ) {
            delete_post_meta( $post_id, 'wprp_admin_customdata_group', $old );
        }
  }
}
?>