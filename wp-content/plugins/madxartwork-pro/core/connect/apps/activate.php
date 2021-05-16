<?php
namespace madxartworkPro\Core\Connect\Apps;

use madxartwork\Core\Common\Modules\Connect\Apps\Common_App;
use madxartworkPro\License;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Activate extends Common_App {

	protected function get_slug() {
		return 'activate';
	}

	protected function after_connect() {
		$this->action_activate_license();
	}

	public function render_admin_widget() {}

	public function action_activate_pro() {
		$this->action_activate_license();
	}

	public function action_switch_license() {
		$this->disconnect();
		$this->action_authorize();
	}

	public function action_deactivate() {
		License\Admin::deactivate();
		$this->disconnect();
		wp_safe_redirect( License\Admin::get_url() );
		die;
	}

	public function action_activate_license() {
		if ( ! $this->is_connected() ) {
			$this->add_notice( __( 'Please connect to madxartwork in order to activate license.', 'madxartwork-pro' ), 'error' );

			$this->redirect_to_admin_page();
		}

		$license = $this->request( 'get_connected_license' );

		if ( empty( $license ) ) {
			// TODO: add suggestions how to check/resolve.
			wp_die( 'License not found for user ' . $this->get( 'user' )->email, __( 'madxartwork Pro', 'madxartwork-pro' ), [
				'back_link' => true,
			] );
		}

		if ( is_wp_error( $license ) ) {
			wp_die( $license, __( 'madxartwork Pro', 'madxartwork-pro' ), [
				'back_link' => true,
			] );
		}

		$license_key = trim( $license->key );

		if ( empty( $license_key ) ) {
			wp_die( __( 'License key is missing.', 'madxartwork-pro' ), __( 'madxartwork Pro', 'madxartwork-pro' ), [
				'back_link' => true,
			] );
		}

		$data = License\API::activate_license( $license_key );

		if ( is_wp_error( $data ) ) {
			wp_die( sprintf( '%s (%s) ', $data->get_error_message(), $data->get_error_code() ), __( 'madxartwork Pro', 'madxartwork-pro' ), [
				'back_link' => true,
			] );
		}

		if ( License\API::STATUS_VALID !== $data['license'] ) {
			$error_msg = License\API::get_error_message( $data['error'] );

			wp_die( $error_msg, __( 'madxartwork Pro', 'madxartwork-pro' ), [
				'back_link' => true,
			] );
		}

		License\Admin::set_license_key( $license_key );

		License\API::set_license_data( $data );

		$this->request( 'set_site_owner' );

		$this->add_notice( __( 'License has been activated successfully.', 'madxartwork-pro' ) );

		$this->redirect_to_admin_page( License\Admin::get_url() );
		die;
	}
}
