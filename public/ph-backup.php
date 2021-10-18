<?php

/**
 * Plugin Name:       PH - Backup
 * Description:       Database backup
 * Version:           0.1.0
 * Requires at least: 5.0
 * Tested up to:      5.8.1
 * Author:            PALASTHOTEL by Edward
 * Author URI:        http://www.palasthotel.de
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ph-backup
 * Domain Path:       /plugin/languages
 */

namespace Palasthotel\WordPress\Backup;

require_once dirname( __FILE__ ) . "/vendor/autoload.php";

if ( ! defined( 'PH_BACKUPS_PATH' ) ) {
	define( 'PH_BACKUPS_PATH', ABSPATH . "../private/backups" );
}

if ( ! defined( 'PH_BACKUPS_SCHEDULE' ) ) {
	define( 'PH_BACKUPS_SCHEDULE', "hourly" );
}

if ( ! defined( 'PH_BACKUPS_HISTORY_SIZE' ) ) {
	define( 'PH_BACKUPS_HISTORY_SIZE', "3" );
}

/**
 * @property Schedule $schedule
 * @property ManagementPage $managementPage
 * @property BackupManager $backupManager
 * @property Notices $notices
 * @property AjaxActions $ajaxActions
 */
class Plugin extends Components\Plugin {

	const DOMAIN = "ph-backup";

	const SCHEDULE_ACTION = "ph_backup_run";

	function onCreate() {

		$this->loadTextdomain(Plugin::DOMAIN, "languages");

		$this->backupManager  = new BackupManager();
		$this->schedule       = new Schedule( $this );
		$this->ajaxActions    = new AjaxActions( $this );
		$this->managementPage = new ManagementPage( $this );
		$this->notices        = new Notices( $this );

	}
}

Plugin::instance();

