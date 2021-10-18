<?php

namespace Palasthotel\WordPress\Backup;

use Palasthotel\WordPress\Backup\Components\Component;

class Schedule extends Component {

	function onCreate() {
		parent::onCreate();
		add_action( 'admin_init', [ $this, 'init' ] );
		add_action( Plugin::SCHEDULE_ACTION, [ $this, 'run' ] );
	}

	function init() {
		if ( PH_BACKUP_SCHEDULE == "off" ) {
			$this->unschedule();
		} else {
			$this->schedule();
		}
	}

	function schedule() {
		if ( ! wp_next_scheduled( Plugin::SCHEDULE_ACTION ) ) {
			wp_schedule_event( time(), PH_BACKUPS_SCHEDULE, Plugin::SCHEDULE_ACTION );
		}
	}

	function unschedule() {
		if ( wp_next_scheduled( Plugin::SCHEDULE_ACTION ) ) {
			wp_clear_scheduled_hook( Plugin::SCHEDULE_ACTION );
		}
	}

	function run() {
		$this->plugin->backupManager->createBackup();
		$this->plugin->backupManager->cleanup();
	}

}