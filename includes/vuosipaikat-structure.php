<?php
class VuosipaikatStructure {
	
	function __construct(){}
	
	public function initCPT(){

		$labels = array(
			'name' => 'Vuosipaikka',
			'singular_name' => 'Vuosipaikka',
			'add_new' => 'Lisää uusi vuosipaikka',
			'all_items' => 'Kaikki vuosipaikat',
			'add_new_item' => 'Lisää uusi',
			'edit_item' => 'Editoi tietoja',
			'new_item' => 'Uusi vuosipaikka',
			'view_item' => 'View Item',
			'search_item' => 'Search Portfolio',
			'not_found' => 'Nou items found',
			'not_found_in_trash' => 'No items found in trash',
			'parent_item_colon' => 'Parent Item'		
		);
	
		$args = array(
			'labels' => $labels,
			'public' => true,
			'has_archive' => false,
			'menu_icon' => 'dashicons-testimonial',
			'exclude_from_search' => true,
			'publicly_queryable' => false,
			'supports' => array(
				'title',
				'editor',
			),
			'menu_position' => 5
		);
		
		register_post_type('vuosipaikka',$args);
	}
}
?>