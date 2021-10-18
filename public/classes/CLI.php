<?php

namespace Palasthotel\WordPress\Backup;


/**
 * @property BackupManager $manager
 */
class CLI {

	public function __construct() {
		$this->manager = Plugin::instance()->backupManager;
	}

	/**
	 * Create a new backup
	 *
	 * ## EXAMPLES
	 *
	 *     wp ph-backup create
	 *
	 * @when after_wp_load
	 */
	public function create($args, $assoc_args){
		;
		if($this->manager->isCreatingBackup()){
			\WP_CLI::error("Backup creation is already running!");
			exit;
		}

		\WP_CLI::line( "Start creating backup..." );
		$this->manager->createBackup();

		\WP_CLI::success( "Backup created!" );
	}

	/**
	 * List all available backups
	 *
	 * ## EXAMPLES
	 *
	 *     wp ph-backup ls
	 *
	 * @when after_wp_load
	 */
	public function ls($args, $assoc_args){
		$backups = $this->manager->getBackups();

		$items = array_map(function($backup){
			return [
				"filename" => $backup->getFilename(),
				"created" => Formatter::readableTimestamp($backup->getFileTime()),
			];
		}, $backups);

		\WP_CLI\Utils\format_items( 'table', $items, array( 'filename', 'created' ) );
	}

}