<?php

namespace Palasthotel\WordPress\Backup;

use Palasthotel\WordPress\Backup\Components\Component;
use Palasthotel\WordPress\Backup\Model\BackupEntity;

class AjaxActions extends Component {

	public function onCreate() {
		parent::onCreate();
		add_action( 'wp_ajax_ph_backups_download', [ $this, 'download' ] );
		add_action( 'wp_ajax_ph_backups_delete', [ $this, 'delete' ] );
	}

	public function getDownloadUrl( BackupEntity $item ) {
		return admin_url( "admin-ajax.php?action=ph_backups_download&filename=" . $item->getFilename() );
	}

	public function download() {
		$this->securityCheck();
		if ( $_SERVER["REQUEST_METHOD"] !== "GET" ) {
			wp_send_json_error( [ "message" => __( "You are not allowed to do that!", Plugin::DOMAIN ) ], 405 );
			exit;
		}

		$backup = $this->plugin->backupManager->getBackupByFilename(
			sanitize_text_field($_GET["filename"])
		);

		if(!($backup instanceof BackupEntity)){
			wp_send_json_error( [ "message" => __( "Backup not found!", Plugin::DOMAIN ) ], 404 );
			exit;
		}

		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="'.$backup->getFilename().'"');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($backup->getFilepath()));
		readfile($backup->getFilepath());
		exit;

	}

	public function getDeleteUrl( BackupEntity $item ) {
		return admin_url( "admin-ajax.php?action=ph_backups_delete&filename=" . $item->getFilename() );
	}

	public function delete() {
		$this->securityCheck();
		if ( $_SERVER["REQUEST_METHOD"] !== "DELETE" ) {
			wp_send_json_error( [ "message" => __( "You are not allowed to do that!", Plugin::DOMAIN ) ], 405);
			exit;
		}

		$backup = $this->plugin->backupManager->getBackupByFilename(
			sanitize_text_field($_GET["filename"])
		);

		if(!($backup instanceof BackupEntity)){
			wp_send_json_error( [ "message" => __( "Backup not found!", Plugin::DOMAIN ) ], 404 );
			exit;
		}

		$success = $this->plugin->backupManager->deleteBackup($backup);

		if($success){
			wp_send_json_success();
		} else {
			wp_send_json_error( [ "message" => __( "Could not delete backup!", Plugin::DOMAIN ) ], 500 );
		}
	}

	private function securityCheck() {
		if ( ! current_user_can( "manage_options" ) ) {
			wp_send_json_error( [ "message" => __( "You are not allowed to do that!", Plugin::DOMAIN ) ], 403 );
		}
	}

}