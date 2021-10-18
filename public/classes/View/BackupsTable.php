<?php

namespace Palasthotel\WordPress\Backup\View;

use Palasthotel\WordPress\Backup\BackupManager;
use Palasthotel\WordPress\Backup\Model\BackupEntity;
use Palasthotel\WordPress\Backup\Plugin;
use WP_List_Table;

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * @property BackupManager $manager
 */
class BackupsTable extends WP_List_Table {

	private function manager() {
		return Plugin::instance()->backupManager;
	}

	private function ajaxActions() {
		return Plugin::instance()->ajaxActions;
	}

	/**
	 * Prepare the items for the table to process
	 *
	 * @return Void
	 */
	public function prepare_items() {
		$columns  = $this->get_columns();
		$hidden   = $this->get_hidden_columns();
		$sortable = $this->get_sortable_columns();

		$data = $this->table_data();

		$this->_column_headers = array( $columns, $hidden, $sortable );
		$this->items           = $data;
	}

	/**
	 * Get the table data
	 *
	 * @return array
	 */
	private function table_data() {
		return $this->manager()->getBackups();
	}

	/**
	 * Override the parent columns method. Defines the columns to use in your listing table
	 *
	 * @return string[]
	 */
	public function get_columns(): array {

		return array(
			'filename' => 'Filename',
			'created'  => 'Created',
			'actions'  => 'Actions',
		);
	}

	/**
	 * Define what data to show on each column of the table
	 *
	 * @param BackupEntity $item Data
	 * @param String $column_name - Current column name
	 *
	 * @return mixed
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'filename':
				return $item->getFilename();
			case 'created':
				$dateFormat = get_option('date_format');
				$timeFormat = get_option('time_format');
				return date_i18n("$dateFormat $timeFormat", $item->getFileTime());
			case 'actions':
				$downloadUrl = $this->ajaxActions()->getDownloadUrl($item);
				$deleteUrl = $this->ajaxActions()->getDeleteUrl($item);
				$filename = $item->getFilename();
				return "<a href='$downloadUrl' target='_blank'>Download</a> | <a class='ph-backup-delete' href='$deleteUrl'>Delete</a>";
			default:
				return print_r( $item, true );
		}
	}

	/**
	 * Define which columns are hidden
	 *
	 * @return string[]
	 */
	public function get_hidden_columns(): array {
		return array();
	}
}