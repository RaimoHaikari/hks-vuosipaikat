<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/public/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<?php


// https://wordpress.stackexchange.com/questions/109849/order-by-desc-asc-in-custom-wp-query
// https://wordpress.stackexchange.com/questions/216741/advanced-wp-query-with-meta-query-orderby

// s:8:"approved";i:1;s:8:"featured";i:1;
$argsBak= array(
	'post_type' => 'vuosipaikkalainen',
	'post_status' => 'publish'
);

$args = array(
	'post_type' => 'vuosipaikkalainen',
	'post_status' => 'publish',
    'meta_key' => '_hks_vuosipaikka_paikka',
    'orderby' => 'meta_value',
    'order' => 'ASC'	
);

$query = new WP_Query($args);

?>

<h3 class="pb-4 mb-4 font-italic border-bottom"><?php wp_title(''); ?></h3>

<div class="row">

	<div class="col-md-8 blog-main">
	
		<div class="blog-post">

<?php

$file = plugin_dir_path(__FILE__).'../images/torikartta_2019-ilman.svg';
echo file_get_contents($file);

//echo "<h3>".$pathToFile."</h3>";
?>				
			
		</div><!-- /.blog-post -->
	</div><!-- /.blog-main -->

	<aside class="col-md-4 blog-sidebar">
		<div class="p-4 mb-3 bg-light rounded">
		
			<ul class="list-group vp_list">
			
			<?php 
				if($query->have_posts()):
					while($query->have_posts()) : $query->the_post(); 
/*					
$data = get_post_meta(get_the_ID(), '_hks_vuosipaikka_key', true) ?? '';
						
$name = $data['nimi'];
$place = $data['paikka'];
*/


$name = get_post_meta(get_the_ID(), '_hks_vuosipaikka_nimi', true);
$place = get_post_meta(get_the_ID(), '_hks_vuosipaikka_paikka', true);

			?>
				<li class="list-group-item">
					<span class="vp_<?php echo $place;?>">
						<?php echo $place.'&nbsp;'.$name; ?>
					</span>
				</li>
			<?php
					endwhile; 
				endif;
				
				wp_reset_postdata();			
			?>
			
			</ul>

			
		</div>
	</aside><!-- /.blog-sidebar -->

</div><!-- /.row -->