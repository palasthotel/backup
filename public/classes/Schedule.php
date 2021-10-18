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
		if ( ! wp_next_scheduled( Plugin::SCHEDULE_ACTION ) ) {
			wp_schedule_event( time(), PH_BACKUPS_SCHEDULE, Plugin::SCHEDULE_ACTION );
		}
	}

	function run() {
		$this->plugin->backupManager->doBackup();
		$this->plugin->backupManager->cleanup();
	}

}