<?php
/*
Plugin Name: FlexSlider for WordPress by CasePress
Description: Awesome slider http://flexslider.woothemes.com/ for WordPress
Version: 0.1
Author: CasePress
Author URI: http://casepress.org
License: MIT License
Text Domain: flexslider-cp
Domain Path: languages
*/

//Определяем константу и помещаем в нее путь до папки с плагином. Чтобы затем использовать ее.
define ("CP_FLEXSLIDER_PLUGIN_DIR_URL", plugin_dir_url(__FILE__));

/*
Добавляем шорткод, который будет выводить галлерею.
*/
add_shortcode( 'gallery_cp', 'add_shortcode_gallery_cp' );
function add_shortcode_gallery_cp( $atts ) {

	// Attributes
	extract( shortcode_atts(
		array(
			'ids' => '',
			'id_slider' => 'flexslider-slider',
			'id_carousel' => 'flexslider-carousel',
			), $atts )
	);
	
	$ids=explode(',', $ids);
	
	$arg = array(
		'post_type' => 'attachment', 
		'post_status' => 'any', 
		'posts_per_page'   => -1,
		'post__in' => $ids
		);
	$images = get_posts($arg);
	
	ob_start();
	?>
	<div class="gallery_cp flexslider">
		<div id="<?php echo $id_slider; ?>" class="flexslider">
			<ul class="slides">
			<?php foreach($images as $image): ?>
				<li>
				  <?php echo wp_get_attachment_image( $image->ID, $size = array(800,600)); ?> 
				</li>
			<?php endforeach; ?>
			</ul>
		</div>
		<div id="<?php echo $id_carousel; ?>" class="flexslider">
		  <ul class="slides">
			<?php foreach($images as $image): ?>
				<li>
				  <?php echo wp_get_attachment_image( $image->ID, $size = array(50,50)); ?> 
				</li>
			<?php endforeach; ?>
			</ul>
		</div>
		<script type="text/javascript">
			(function ($) {
			   $(window).load(function() {
				  $('#<?php echo $id_carousel; ?>').flexslider({
					animation: "slide",
					controlNav: false,
					animationLoop: false,
					slideshow: false,
					itemWidth: 210,
					itemMargin: 5,
					asNavFor: '#<?php echo $id_slider; ?>'
				  });
				   
				  $('#<?php echo $id_slider; ?>').flexslider({
					animation: "slide",
					controlNav: false,
					animationLoop: false,
					slideshow: false,
					sync: "#<?php echo $id_carousel; ?>"
				  });			   
			   });
			}(jQuery));

			//jQuery(document).ready(function ($) {
			  // The slider being synced must be initialized first

			//});
		</script>
	</div>
	<?php 
	$content = ob_get_contents();
    ob_end_clean();
	
	return $content;
}

add_action( 'wp_enqueue_scripts', 'gallery_cp_shortcode_scripts');
function gallery_cp_shortcode_scripts() {
	
	wp_register_script( $handle = 'flexslider', $src = CP_FLEXSLIDER_PLUGIN_DIR_URL.'includes/flexslider/jquery.flexslider.js', array('jquery'));
	wp_register_style( $handle = 'flexslider.css', $src = CP_FLEXSLIDER_PLUGIN_DIR_URL.'includes/flexslider/flexslider.css');
	
	wp_enqueue_script( 'flexslider');
	wp_enqueue_style( 'flexslider.css' );
}