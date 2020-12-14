<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
 * @author     Your Name <email@example.com>
 */
class Vuosipaikat {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Plugin_Name_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;
	
	
	/*
	 * Määrittää:
	 * - wp_post tauluun tallentuvan post_type -kentän arvon
	 * - add_meta_box funktiokutsun screen -parametrin arvo
	 */
	protected $screen;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		
		if ( defined( 'PLUGIN_NAME_VERSION' ) ) {
			$this->version = PLUGIN_NAME_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'Vuosipaikat';
		
		$this->screen = 'vuosipaikkalainen';	// wp_posts taulun post-type kentän arvo

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		
		//$this->setupFunctionality();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Plugin_Name_Loader. Orchestrates the hooks of the plugin.
	 * - Plugin_Name_i18n. Defines internationalization functionality.
	 * - Plugin_Name_Admin. Defines all hooks for the admin area.
	 * - Plugin_Name_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-vuosipaikat-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-vuosipaikat-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-vuosipaikat-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-vuosipaikat-public.php';

		$this->loader = new Vuosipaikat_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Plugin_Name_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Vuosipaikat_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Vuosipaikat_Admin( 
				$this->get_plugin_name(), 
				$this->get_version(),
				$this->getScreen());

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		
		$this->loader->add_action( 'init', $plugin_admin, 'initCPT' );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_meta_boxes' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_meta_box' );
		
		/*
		 * sarakeotsikoiden tulostus ja sisällön perusteella tapahtuva lajittelu
		 *
		 * manage_edit-{post_type}_sortable_columns
		 *
		 * manage_{post_type}_posts_columns post_type:n arvoksi valitaan käsiteltävä cpt,
		 * eli tässä tapauksessa aiemmin määritetty: vuosipaikkalainen [this->getScreen()]
		 *
		 * https://code.tutsplus.com/articles/quick-tip-make-your-custom-column-sortable--wp-25095
		 */
		$this->loader->add_action('manage_vuosipaikkalainen_posts_columns', $plugin_admin,'set_custom_columns');
		$this->loader->add_action('manage_vuosipaikkalainen_posts_custom_column', $plugin_admin, 'set_custom_column_data',10,2);
		$this->loader->add_filter('manage_edit-vuosipaikkalainen_sortable_columns', $plugin_admin, 'set_custom_column_sortable');
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Vuosipaikat_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		
		
		
		
		//$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		
		/*
		 * js ja css -tiedostoja ei ladata suoraan: [$this->loader->add_action(.. ],
		 * vaan rekisteröidään skriptit ja otetaan käyttöön kun ajetaan funktiota
		 * joka vastaa shortcode -kutsun suorittamisesta.
		 *
		 * https://wordpress.stackexchange.com/questions/165754/enqueue-scripts-styles-when-shortcode-is-present
		 */
		 /*
		wp_register_style(
			'vuosipaikat-public-css', 
			plugins_url( '../public/css/vuosipaikat-public.css', __FILE__ ),
			array(), 
			array(), 
			'1.0.0', 
			'all' 
		);
		*/
		
		wp_register_script(
			"vuosipaikat-public-js", 
			plugins_url( '../public/js/vuosipaikat-public.js', __FILE__ ), 
			'', 
			'', 
			true
		);
			
		add_shortcode('map_and_list', array($plugin_public, 'display_map_and_list'));

	}
	
	private  function debug($msg){
		
		$filename = plugin_dir_path(__FILE__).'../debug.txt';
		
		$myfile = fopen($filename, "a+") or die("Unable to open file!");
		fwrite($myfile, $msg.chr(13).chr(10));
		fclose($myfile);	
		
	}
	
	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Plugin_Name_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
	
	/**
	 *
	 */
	public function getScreen() {
		return $this->screen;
	}

}
