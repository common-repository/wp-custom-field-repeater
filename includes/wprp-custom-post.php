<?php
add_action( 'admin_init', 'wprp_add_meta_boxes', 2 );
function wprp_add_meta_boxes() {
    add_meta_box( 'wprp-adminfields-group', 'WP REPEATER PLUGIN FIELDS', 'wprp_repeatable_meta_box_display', 'page', 'normal', 'default' );
}

add_action('admin_notices', 'wprp_get_current_post_id');
function wprp_get_current_post_id() {
    global $my_admin_page;
    $screen = get_current_screen();

    if ( is_admin() && ( $screen->id == 'page' ) || ( $screen->id == 'post' ) ) {
        global $post;
        global $postId;
        $postId = $post->ID;
    }
    return $postId;
}

function wprp_repeatable_meta_box_display() {
    global $post;
    wp_nonce_field( 'wprp_repeatable_meta_box_nonce', 'wprp_repeatable_meta_box_nonce' );
    $activePostId = wprp_get_current_post_id();
    $savedFields = get_post_meta( $activePostId, 'wprp_customdata_group', true );

    global $wpdb;
    $metas = $wpdb->get_results(
        $wpdb->prepare( "SELECT meta_value FROM $wpdb->postmeta where meta_key = %s", 'wprp_admin_customdata_group' )
    );

    if ($metas) {
        foreach ($metas as $meta) {
            $metaArray = unserialize($meta->meta_value);

            if ($metaArray[0]['LinkTo'] == $activePostId) {
                $data = $metaArray[0];
            }
        }
    }

    ?>
    
<table id="repeatable-fieldset-one" width="100%">
<tbody>
    <?php
    if ( $savedFields ) {
        $arrKey = array_keys( $savedFields );
        $numericArr = array_values( $savedFields );

        $mainArryCount = count( $numericArr );
        if($numericArr[0]) {
            $subArrCount = count( $numericArr[0] );
            $subArrCount -=1;
        }

        for ($i = 0; $i < $subArrCount; $i++) { ?>
        <tr>
        <?php
            for ($j = 0; $j < $mainArryCount; $j++) {
                $fieldNameArr = explode( "_", $arrKey[$j] );
                $fieldName = $fieldNameArr[0];
                $actualName = $fieldNameArr[1];

                    if ( $fieldName == "text-box" ) { ?>
                        <td>
                            <input  type="text" value="<?php if ($numericArr[$j][$i] != '') {echo esc_attr($numericArr[$j][$i]);}?>"  name="<?php if ($actualName != '') {echo esc_attr("wprp" . $actualName . "[]");}?>" placeholder="<?php if ($actualName != '') {echo esc_attr($actualName);}?>"/>
                        </td>
              <?php }
                    if ( $fieldName == "text-area" ) { ?>
                        <td>
                            <textarea placeholder="<?php if ($actualName != '') {echo esc_attr($actualName);}?>" name="<?php if ($actualName != '') {echo esc_attr("wprp" . $actualName . "[]");}?>" cols="30" rows="4"><?php if ($numericArr[$j][$i] != '') {echo esc_attr($numericArr[$j][$i]);}?></textarea>
                        </td>
              <?php }
                    if ( $fieldName == "image-upload" ) { ?>
                        <td id="wprp-custom-image-upload">
                            <input type="text" readonly class="meta-image regular-text" value="<?php if ($numericArr[$j][$i] != '') {echo esc_attr($numericArr[$j][$i]);}?>"  name="<?php if ($actualName != '') {echo esc_attr("wprp" . $actualName . "[]");}?>" id="  <?php if ($actualName != '') {echo esc_attr("wprp" . $actualName . "[]");}?>" placeholder="<?php if ($actualName != '') {echo esc_attr($actualName);}?>" >
                            <input type="button" class="button image-upload" value="Browse">
                        </td>

            <?php }
            } //j loop close
            ?>
        <td><a class="button remove-row" href="#"><span class="dashicons dashicons-trash"></span></a></td>
        </tr>
        <?php
        } //i loop close

    } // first if close
    elseif ( !( $savedFields ) && ( $data ) ) { ?>
    <tr>
    <?php
        foreach ( $data['Items'] as $field ) {
            switch ( $field['TitleFieldType'] ):
            case "text-box": ?>
            <td>
                <input type="text" placeholder="<?php if ($field['TitleItem'] != '') {echo esc_attr($field['TitleItem']);}?>"  name="<?php if ($field['TitleItem'] != '') {echo esc_attr("wprp" . $field['TitleItem'] . "[]");}?>" />
            </td>
            <?php break;?>
            <?php case "text-area": ?>
            <td>
                <textarea  placeholder="<?php if ($field['TitleItem'] != '') {echo esc_attr($field['TitleItem']);}?>"  name="<?php if ($field['TitleItem'] != '') {echo esc_attr("wprp" . $field['TitleItem'] . "[]");}?>" cols="30" rows="4"></textarea>
            </td>
            <?php break;?>
            <?php case "image-upload": ?>
            <td id="wprp-custom-image-upload">
                <input type="text" readonly placeholder="<?php if ($field['TitleItem'] != '') {echo esc_attr($field['TitleItem']);}?>" class="meta-image regular-text" value=""  name="<?php if ($field['TitleItem'] != '') {echo esc_attr("wprp" . $field['TitleItem'] . "[]");}?>" id="<?php if ($field['TitleItem'] != '') {echo esc_attr("wprp" . $field['TitleItem'] . "[]");}?>" required>
                <input type="button" class="button image-upload" value="Browse">
            </td>
            <br />
            <?php break;?>
            <?php endswitch;
        } ?>
    <td><a class="button remove-row" href="#"><span class="dashicons dashicons-trash"></span></a></td>
    <?php
    } //elseif close
    ?>
    </tr>
    <!-- empty hidden one for jQuery for repeating fields-->
    <tr class="empty-row screen-reader-text">
    <?php
    if ( $data ):
        foreach ( $data['Items'] as $field ) {
            switch ( $field['TitleFieldType'] ):
            case "text-box": ?>
            <td>
                <input  type="text" placeholder="<?php if ($field['TitleItem'] != '') {echo esc_attr($field['TitleItem']);}?>"  name="<?php if ($field['TitleItem'] != '') {echo esc_attr("wprp" . $field['TitleItem'] . "[]");}?>" />
            </td>
	        <?php break;?>
	        <?php case "text-area": ?>
            <td>
                <textarea  placeholder="<?php if ($field['TitleItem'] != '') {echo esc_attr($field['TitleItem']);}?>"  name="<?php if ($field['TitleItem'] != '') {echo esc_attr("wprp" . $field['TitleItem'] . "[]");}?>" cols="30" rows="4"></textarea>
            </td>
	        <?php break;?>
	        <?php case "image-upload": ?>
            <td id="wprp-custom-image-upload">
	           <input type="text" readonly class="meta-image regular-text" value="" name="<?php if ($field['TitleItem'] != '') {echo esc_attr("wprp" . $field['TitleItem'] . "[]");}?>" placeholder="<?php if ($field['TitleItem'] != '') {echo esc_attr($field['TitleItem']);}?>" id="<?php if ($field['TitleItem'] != '') {echo esc_attr("wprp" . $field['TitleItem'] . "[]");}?>" >
                <input type="button" class="button image-upload" value="Browse">
	       </td>
	       <?php break;?>
	       <?php endswitch;
        }
        ?>
	    <td><a class="button remove-row" href="#"><span class="dashicons dashicons-trash"></span></a></td>
	    <?php endif;?>
    </tr>
</tbody>
</table>
<?php if ( $data ): ?>

<p><a id="add-row" class="button" href="#">Add another</a></p>

<?php 
endif;
}

add_action( 'save_post', 'wprp_custom_repeatable_meta_box_save' );
function wprp_custom_repeatable_meta_box_save( $post_id ) {
    if ( !isset( $_POST['wprp_repeatable_meta_box_nonce'] ) ||
        !wp_verify_nonce( $_POST['wprp_repeatable_meta_box_nonce'], 'wprp_repeatable_meta_box_nonce' ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE) {
        return;
    }

    if ( !current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    global $wpdb;
    $metas = $wpdb->get_results(
        $wpdb->prepare( "SELECT meta_value FROM $wpdb->postmeta where meta_key = %s", 'wprp_admin_customdata_group' )
    );

    $activePostId = wprp_get_current_post_id();

    if ( $metas ) {
        foreach ( $metas as $meta ) {
            $metaArray = unserialize( $meta->meta_value );
            if ( $metaArray[0]['LinkTo'] == $activePostId ) {
                $data = $metaArray[0];
            }
        }
    }

    if ( $data ) {
        foreach ( $data['Items'] as $field ) {
            $fieldType = $field['TitleFieldType'] . "_" . $field['TitleItem'];
            if( $field['TitleFieldType'] == 'text-box') {
                $new[$fieldType] = array_map('sanitize_text_field', $_POST["wprp" . $field['TitleItem']]);
            }
            elseif ( $field['TitleFieldType'] == 'text-area' ) {
                $new[$fieldType] = array_map( 'sanitize_text_field', $_POST["wprp" . $field['TitleItem']] );
            }
            elseif ( $field['TitleFieldType'] == 'image-upload') {
                $new[$fieldType] = array_map( function( $value ){ 
                    return $value = sanitize_option( 'upload_path', $value );
                 }, $_POST["wprp" . $field['TitleItem']]);
            }
        }
    }
    
    $old = get_post_meta( $post_id, 'wprp_customdata_group', true );

    if ( !empty( $new ) && $new != $old ) {
        update_post_meta( $post_id, 'wprp_customdata_group', $new );
    } elseif ( empty( $new ) && $old ) {
        delete_post_meta( $post_id, 'wprp_customdata_group', $old );
    }
}
?>