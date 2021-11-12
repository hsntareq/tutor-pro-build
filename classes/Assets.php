<?php
namespace TUTOR_PRO;

if ( ! defined( 'ABSPATH' ) )
	exit;

class Assets {
	public function __construct() {
		add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));
	}

	public function admin_scripts() {
		wp_enqueue_style('tutor-pro-admin', tutor_pro()->url.'assets/css/admin.css', array(), tutor_pro()->version);
		wp_enqueue_script('tutor-pro-admin', tutor_pro()->url.'assets/js/admin.js', array('jquery'), tutor_pro()->version, true);

		$tutor_pro_localize_data = array(
			'is_zoom_sync' => get_option('tutor_zoom_sync')
		);
		wp_localize_script('tutor-pro-admin', '_tutor_pro_object', $tutor_pro_localize_data);
	}
}