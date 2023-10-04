<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://2bytecode.com/
 * @since      1.0.0
 *
 * @package    Digital_Asset_Manager
 * @subpackage Digital_Asset_Manager/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Digital_Asset_Manager
 * @subpackage Digital_Asset_Manager/public
 * @author     2ByteCode <support@2bytecode.com>
 */
class Digital_Asset_Manager_Public {

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

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		add_action( 'wp_ajax_nopriv_dam_retrieve_asset_download_url', array( $this, 'dam_retrieve_asset_download_url' ) );
		add_action( 'wp_ajax_dam_retrieve_asset_download_url', array( $this, 'dam_retrieve_asset_download_url' ) );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Digital_Asset_Manager_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Digital_Asset_Manager_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_register_style( $this->plugin_name . '-bootstrap', plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css', array(), $this->version, 'all' );
		wp_register_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/digital-asset-manager-public.css', array( $this->plugin_name . '-bootstrap' ), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Digital_Asset_Manager_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Digital_Asset_Manager_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_register_script( $this->plugin_name . 'font-awesome', 'https://kit.fontawesome.com/4da38f8016.js', array(), $this->version, false );
		wp_register_script( $this->plugin_name . 'bootstrap', plugin_dir_url( __FILE__ ) . 'js/bootstrap.bundle.min.js', array( 'jquery' ), $this->version, false );
		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/digital-asset-manager-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script(
			$this->plugin_name,
			'dam_ajax',
			array(
				'ajaxurl'   => admin_url( 'admin-ajax.php' ),
				'dam_nonce' => wp_create_nonce( 'dam_ajax_public' ),
			)
		);
	}

	/**
	 * Fetch the download URL of the asset.
	 *
	 * @since    2.0.0
	 */
	public function dam_retrieve_asset_download_url() {

		// check nonce.
		check_ajax_referer( 'dam_ajax_public', 'dam_nonce' );

		if ( ! empty( $_REQUEST['assetid'] ) ) {
			$assetid = sanitize_text_field( wp_unslash( $_REQUEST['assetid'] ) );

			if ( empty( $assetid ) ) {
				$response = array(
					'status'  => 'error',
					'message' => 'You play with hidden field and remove it!',
				);
				echo wp_json_encode( $response );
				wp_die();
			} else {

				$drive_link     = get_post_meta( $assetid, 'drive_url', true );
				$downloads_link = get_post_meta( $assetid, 'download_url', true );
				$total_download = get_post_meta( $assetid, 'total_download', true );

				$total_download = (int) $total_download;
				$total_download++;
				update_post_meta( $assetid, 'total_download', $total_download );

				$response = array(
					'status'         => 'success',
					'downloads_link' => $downloads_link,
					'drive_link'     => $drive_link,
				);

				echo wp_json_encode( $response );
				wp_die();
			}
		}
	}

}
