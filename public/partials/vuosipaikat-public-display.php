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
    'order' => 'ASC',
	'posts_per_page' => -1
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
			<div id="accordion">
			
			<?php 
				if($query->have_posts()):
					while($query->have_posts()) : $query->the_post(); 
					
					$name =  esc_html( get_the_title());
					
					$the_content = apply_filters('the_content', get_the_content());
			
					$link = get_post_meta(get_the_ID(), '_hks_vuosipaikka_linkki', true);
					$place = get_post_meta(get_the_ID(), '_hks_vuosipaikka_paikka', true);

			?>
				<div class="card">

					<div  class="card-header hks_vp_card-header" id="vp_<?php echo $place; ?>_heading">
						<h5 class="mb-0">
							<button 
								class="btn btn-link collapsed hks_vp_collapse_btn-link<?php echo empty($the_content)?'':' hks_vp_collapse_btn-link-has-content';?>" 
								data-toggle="collapse"
								data-target="#vp_<?php echo $place; ?>_collapse" 
								aria-expanded="false" 
								aria-controls="vp_<?php echo $place; ?>_collapse">
							 <?php echo $place.'&nbsp;'.$name; ?>
							 </button>
						</h5>
					</div>
					
					<div 
					id="vp_<?php echo $place; ?>_collapse" 
					class="collapse" 
					aria-labelledby="vp_<?php echo $place; ?>_heading" 
					data-parent="#accordion">
						<div class="card-body">
						
							<?php 
							
								if(empty($the_content))
									$the_content = '<div class="alert alert-info">Ei ilmoitettuja tapahtumia</div>';
							 
								echo $the_content;						
							?>
							
							<?php
								if(!empty($link)):
							?>
								<p class="hks_vuosipaikka_link_to_cust-pages">
									<a href="<?php echo $link; ?>" class="card-link" target="_blank">Myyjän sivuille</a>
								</p>
							<?php
								endif;
							?>
						
						</div>
					</div>
				
				</div><!-- /.card -->
				
			<?php
					endwhile; 
				endif;
				
				wp_reset_postdata();			
			?>
				
			</div><!-- /#accordion -->
			
		</div><!-- /.rounded -->

	</aside><!-- /.blog-sidebar -->

</div><!-- /.row -->