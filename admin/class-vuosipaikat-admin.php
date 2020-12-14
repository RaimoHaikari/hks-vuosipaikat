<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/admin
 * @author     Your Name <email@example.com>
 */
class Vuosipaikat_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
	
	private $context = 'normal';
	
	private $id = 'vuosipaikka_id';
	
	private $nonceFieldKey = 'hks_testimonial';
	private $nonceFieldVal = 'hks_testimonial_nonce';
	
	/*
	 * Minkä nimiseen tauluun metatiedot tallenneaan.
	 */
	private $postMetaKey = '_hks_vuosipaikka_key';
	
	private $postMetaKeys;

	
	private $priority = 'default';
	
	private $title = 'Tunnistetiedot';
	
	private $screen;
	

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $screen) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->screen = $screen;
		
		$this->postMetaKeys = array(
			'paikka' => '_hks_vuosipaikka_paikka',
			'linkki' =>  '_hks_vuosipaikka_linkki'
		);
		
	}
	
	/**
	 *
	 *
	 */
	public function add_meta_boxes(){
		
		add_meta_box(
		
			$this->getId(),						// id
			$this->getTitle(),					// title
			array($this, 'render_author_box'),	// callback
			$this->getScreen(), 				// screen 
			$this->getContext(), 				// context 
			$this->getPriority()				// priority 
		);
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/vuosipaikat-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/vuosipaikat-admin.js', array( 'jquery' ), $this->version, false );

	}
	
	/*
	 *
	 */
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
			'search_items' => 'Search Portfolio',
			'not_found' => 'Nou items found',
			'not_found_in_trash' => 'No items found in trash',
			'parent_item_colon' => 'Parent Item'		
		);
	
		$args = array(
			'labels' => $labels,
			'public' => true,
			'has_archive' => false,
			'menu_icon' => 'dashicons-carrot',
			'exclude_from_search' => true,
			'publicly_queryable' => false,
			'supports' => array(
				'title',
				'editor'
			),
			'menu_position' => 5
		);
		
		register_post_type($this->getScreen(),$args);
	}	
	
	public function render_author_box($post){
		
		wp_nonce_field(
			$this->getNonceFieldKey(),		// Rekisteröitävä arvo, jonka arvo varmistetaan tarvittaessa
			$this->getNonceFieldValue()
		);
		
		/* 
		 * Päivitetty 22.1.2020
		 
		$data = get_post_meta($post->ID, 
			$this->getPostMetaKey(),  	// LISÄTTÄVÄN KENTÄN NIMI
			true);
		 */
		 
		$data = [];
			
		foreach ($this->postMetaKeys as $key => $val) {
			$data[$key] = get_post_meta($post->ID, $val,true);
		}
		
		$link = isset($data['linkki']) ? $data['linkki'] : '';
		$place = isset($data['paikka']) ? $data['paikka'] :'';
		
		?>	
		
		<p>
			<label for="hks_vuosipaikka_paikka">Paikka</label>
			<input 
				type="text" 
				id="hks_vuosipaikka_paikka"
				name="hks_vuosipaikka_paikka" 
				value="<?php echo esc_attr($place); ?>"
			>
		</p>
		
		<p>
			<label for="hks_vuosipaikka_linkki">Linkki</label>
			<input 
				type="text" 
				id="hks_vuosipaikka_linkki"
				name="hks_vuosipaikka_linkki" 
				value="<?php echo esc_attr($link); ?>"
			>
		</p>	

	<?php
	}
	

	/* 
	 *Lisätiedon tallennus 
	 *
	 * funktiota kutsutaan aina kun käyttäjä tallentaa postiksi tulkittavan objektin,
	 * joten aluessa varmistaan, että todellakin on kyse portfolio -objektista
	 *
	 * @param $post_id automaattinen parametri...
	 */
	public function save_meta_box($post_id){
		
		if(!isset($_POST[$this->getNonceFieldValue()])){
			return $post_id;
		}
		
		$nonce = $_POST[$this->getNonceFieldValue()];
		
		if(!wp_verify_nonce($nonce, $this->getNonceFieldKey())){
			return $post_id;
		}
		
		// Mikäli kyseessä on automaattinen tallennus, ei reagoida
		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE){
			return $post_id;
		}
		
		// Onhan käyttäjällä oikeudet kunnossa...
		if(!current_user_can('edit_post', $post_id)){
			return $post_id;		
		}
		
		$data = array(
			'linkki' => sanitize_text_field($_POST['hks_vuosipaikka_linkki']),
			'paikka' => sanitize_text_field($_POST['hks_vuosipaikka_paikka'])	
		);
		
		foreach ($this->postMetaKeys as $key => $val) {
			
			update_post_meta(
				$post_id,
				$val,		// LISÄTTÄVÄN KENTÄN NIMI!!!!
				$data[$key]
			);
			
		}

		/*
		PÄIVITETTY 22.1.2020
		
		update_post_meta(
			$post_id,
			$this->getPostMetaKey(),		// LISÄTTÄVÄN KENTÄN NIMI!!!!
			$data
		);
		*/
	}
	
	/*
	 * Lisätään (tietyn tyyppiset) postit listaavaan taulukkoon sarakeilla
	 * esitettävät tiedot.
	 *
	 * - Wordpress ajaa funktiota sarake kerrallaan siksi SWITCH
	 * - koska title ja date tulee wordpressin puolesta automaattisesti, niihin ei nyt tarvi puuttua
	 */
	public function set_custom_column_data($column, $post_id){
		
		/*
		 * Päivitetty 22.1.2020
		 *
		 * Koska custom-kentät on tallennettu metatietoihin, 
		 * täytyy ensin hakea postiin liittyvä metatietous,
		 * joka on tallennettu assosiatiiviseen taulukkoon,
		 * josta kenttien arvot ovat saatavissa.
		 *
			$data = get_post_meta($post_id, $this->getPostMetaKey(), true);
		 */
		
		$data = [];
			
		foreach ($this->postMetaKeys as $key => $val) {
			$data[$key] = get_post_meta($post_id, $val,true);
		}
			
		$name = isset($data['nimi']) ? $data['nimi'] : '';
		$place = isset($data['paikka']) ? $data['paikka'] : '';
				
		switch($column){
			/*
			case 'nimi':
				echo $name;
				break;	
			*/				
			case 'paikka':
				echo $place;
				break;
		}
	}
	
	/* 
	 * manage_{$this->screen->id}_sortable_columns
	 * -------------------------------------------
	 * ...in general, for a post type with name 'my-post-type', $screen->id is 'edit-my-post-type'
	 * https://code.tutsplus.com/articles/quick-tip-make-your-custom-column-sortable--wp-25095
	 *
	 *
	 * Rekisteröidään sarakkeet lajitteleviksi
	 * manage_edit-{post_type}_sortable_columns
	 *
	 * @param $columns Wordpress automaattisesti lisää sarakkeet sisältävän tiedon
	 */
	public function set_custom_column_sortable($columns){
		
		$columns['nimi'] = 'nimi';
		$columns['paikka'] = 'paikka';
		
		return $columns;
	}
	
	/* 
	 * manage_{$post_type}_posts_columns
	 * ---------------------------------
	 * Määritellään (tietyn tyyppisten) postien listauksessa esitettävät sarakkeet
	 *
	 * manage_{post_type}_posts_columns post_type:n arvoksi valitaan käsiteltävä cpt,
	 * eli tässä tapauksessa aiemmin määritetty: portfolio
	 */
	 
	// @param $columns automaattinen, sis. wordpress:in tarjomat perusarvot 
	public function set_custom_columns($columns){
			
		$title = $columns['title'];
		$date = $columns['date'];
		
		/* 
		 * Pitää poistaa alkuperäiset, jotta voidaan liittää muokatut tiedot
		 * muokatussa järjestyksessä....
		 */	
		unset($columns['title'],  $columns['date']);
		
		$columns['title'] = 'Nimi';
		//$columns['nimi'] =  'Nimi';
		$columns['paikka'] = 'Paikka';
		$columns['date'] = $date;
		
		return $columns;
	}

	protected function getContext(){
		return $this->context;
	}
	
	protected function getId(){
		return $this->id;
	}
	
	protected function getNonceFieldKey(){
		return $this->nonceFieldKey;
	}

	protected function getNonceFieldValue(){
		return $this->nonceFieldVal;
	}
	
	protected function getPostMetaKey(){
		return $this->postMetaKey;
	}
	
	protected function getPriority(){
		return $this->priority;
	}

	protected function getScreen() {
		return $this->screen;
	}
	
	protected function getTitle(){
		return $this->title;
	}
	
	private  function debug($msg){
		
		$filename = plugin_dir_path(__FILE__).'../debug.txt';
		
		$myfile = fopen($filename, "a+") or die("Unable to open file!");
		fwrite($myfile, $msg."\n");
		fclose($myfile);	
		
	}

}
