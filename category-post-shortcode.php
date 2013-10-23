<?php
/*
Plugin Name: category post shortcode
Plugin URI: http://ibnuyahya.com/wordpress-plugins/category-post-shortcode/
Description: To display post by category in your page/post
Author: ibnuyahya
Author URI: http://ibnuyahya.com/
Version: 2.5

Contributors
Ben McFadden - https://github.com/mcfadden

*/

/*
 *
 * How to use
 * =============================================================================
 * just put this shortcode in your post or pages
 *
 *    [cat totalposts="3" category="1,3" thumbnail="true" excerpt="true" ]
 *
 * totalposts - your total number of post to display. default is -1
 * category   - category id. use comma , for multiple id
 * thumbnail  - set true if you want to display thumbnail. default is false
 * thumbnail_type - set to 'meta' to use custom thumbnail-url meta field, set to 'featured_image' to use the featured image. default is 'meta'
 * thumbnail_height - image size for the thumbnail. default is 130
 * thumbnail_width - image size for the thumbnail. default is 130
 * excerpt    - set true if you want to display excerpt. (ignored is full_post is true) default is true
 * full_post  - set true if you want to display entire post. (overrides excerpt setting) default is false
 * date       - set true if you want to display post date. default is false
 * orderby    - your post will order by . default post_date . check http://codex.wordpress.org/Template_Tags/get_posts for detail
 * order      - asc | desc
 *
 * thumbnail
 * =============================================================================
 * create custom field key as thumbnail-url and put your thumbnail url in the value area or set thumbnail_type to 'featured_image' and set a featured image
 *
 * style at your own
 * =============================================================================
 * you need to style your category-post-shortcode plugin in your style.css example

.cat-post{
    width:100%;
}
.cat-post-list{
    display: block;
    margin-bottom: 20px;
    position: relative;

}
.cat-post-images{
    float:left;
    width:140px;
    display:block;
}

.cat-content{
    width:350px;
    float:right;
}
.cat-post-title{
    display: block;
    width:100%;
}
.cat-post-date
    display: block;
    width:100%;
}
.cat-clear{
    clear:both;
}

 */

function cat_func($atts) {
    extract(shortcode_atts(array(
            'class_name'    => 'cat-post',
            'totalposts'    => '-1',
            'category'      => '',
            'thumbnail'     => 'false',
            'thumbnail_type' => 'meta',
            'thumbnail_height' => '130',
            'thumbnail_width' => '130',
            'date'          => 'false',
            'excerpt'       => 'true',
            'full_post'     => 'false',
            'orderby'       => 'post_date',
            'order'         => 'desc'
            ), $atts));

    $output = '<div class="'.$class_name.'">';
    global $post;
$tmp_post = $post;
    $myposts = get_posts("numberposts=$totalposts&category=$category&orderby=$orderby&order=$order");

    foreach($myposts as $post) {
        setup_postdata($post);
        $output .= '<div class="cat-post-list">';
        if($thumbnail == 'true') {
            if ("featured_image" == $thumbnail_type){
                $thumb_image = get_the_post_thumbnail($post->ID);
                preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $thumb_image, $matches);
                $thumb_image = $matches [1] [0];
            }else{
                $thumb_image = get_post_meta($post->ID, 'thumbnail-url',true);
            }
            if(empty($thumb_image)){
                 preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
                 $thumb_image = $matches [1] [0];
            }
            if(empty($thumb_image)){
                 $thumb_image ='';
            }

            $output .= '<div class="cat-post-images"><img height="'.$thumbnail_height.'" width="'.$thumbnail_width.'" src="'.$thumb_image.'" /></div>';
        }
        $output .= '<div class="cat-content"><span class="cat-post-title"><a href="'.get_permalink().'">'.get_the_title().'</a></span>';
        if ($date == 'true') {
            $output .= '<span class="cat-post-date">'.get_the_date().'</span>';
        }
        if ($full_post == 'true'){
          $output .= '<span class="cat-post-content">'.get_the_content().'</span>';
        }elseif ($excerpt == 'true') {
            $output .= '<span class="cat-post-excerpt">'.get_the_excerpt().'</span>';
        }
        $output .= '</div>
            <div class="cat-clear"></div>
        </div>';
    };
    $output .= '</div>';
    $post = $tmp_post;
    wp_reset_query();
    return $output;
}
add_shortcode('cat', 'cat_func');

?>
