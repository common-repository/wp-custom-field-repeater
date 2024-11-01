<?php
function wprp_individual_short_code($atts)
{
    global $post;
    $data = get_post_meta(get_the_ID(), 'wprp_customdata_group', true);
    $fieldName = $atts[0];
    $fieldLoc = $atts[1];

    if(is_numeric($fieldLoc))
    {
        $fieldLoc -= 1;
        foreach ($data as $key => $value) {
            $temp = explode("_", $key);
            if ($fieldName == $temp[1]) {
                return $value[$fieldLoc];
            }
        }
    }
}
add_shortcode('wprp', 'wprp_individual_short_code');

function wprp_all_short_code($atts)
{
    if(is_numeric($atts[0]))
    {
          global $post;
          $data = get_post_meta(get_the_ID(), 'wprp_customdata_group', true);
          $row = $atts[0];
          $row -= 1;
          $i = 0;
        
          foreach ($data as $key) {
              $value[$i] ='<p>'.$key[$row].'</p>'.',';
              $i += 1;
          }
          $str = implode(',', $value);
          $data = str_replace(',', '', $str);
          return $data;
    }
}
add_shortcode('wprp-row', 'wprp_all_short_code');
?>