<?php

namespace Palasthotel\WordPress\Backup;

use Palasthotel\WordPress\Backup\Model\BackupEntity;

class BackupManager {

	/**
	 * @var null|array
	 */
	private $backupsCache;
	/**
	 * @var string
	 */
	private $doingBackupFile;

	public function __construct() {
		$this->backupsCache = null;

		if ( ! $this->backupPathExists() ) {
			mkdir( PH_BACKUPS_PATH, 0777, true );
		}

		$this->doingBackupFile = PH_BACKUPS_PATH . "/doing-backup.info";
	}

	public function backupPathExists(): bool {
		if ( ! is_dir( PH_BACKUPS_PATH ) || ! is_writable( PH_BACKUPS_PATH ) ) {
			return false;
		}

		return true;
	}

	/**
	 * @return BackupEntity[]
	 */
	public function getBackups(): array {
		if ( ! $this->backupPathExists() ) {
			return [];
		}

		if ( is_array( $this->backupsCache ) ) {
			return $this->backupsCache;
		}

		$handle = opendir( PH_BACKUPS_PATH );
		if ( ! $handle ) {
			return [];
		}

		/**
		 * @var BackupEntity[] $backups
		 */
		$backups = [];

		while ( false !== ( $file = readdir( $handle ) ) ) {
			if ( substr( $file, - 7 ) === ".sql.gz" ) {
				$backups[] = new BackupEntity( PH_BACKUPS_PATH . "/$file" );
			}
		}

		closedir( $handle );

		usort( $backups, function ( BackupEntity $b1, BackupEntity $b2 ) {
			return $b2->getFileTime() - $b1->getFileTime();
		} );

		$this->backupsCache = $backups;

		return $backups;
	}

	/**
	 * @param string $filename
	 *
	 * @return BackupEntity|null
	 */
	public function getBackupByFilename( string $filename ) {
		$backups = $this->getBackups();
		foreach ( $backups as $backup ) {
			if ( $backup->getFilename() === $filename ) {
				return $backup;
			}
		}

		return null;
	}

	public function deleteBackup( BackupEntity $backup ): bool {
		$this->backupsCache = null;

		return unlink( $backup->getFilepath() );
	}

	public function isDoingBackup(): bool {
		return file_exists( $this->doingBackupFile );
	}

	public function setDoingBackup( bool $is ): bool {
		if ( ! $this->backupPathExists() ) {
			return false;
		}
		if ( $is && ! file_exists( $this->doingBackupFile ) ) {
			$file = fopen( $this->doingBackupFile, "w" );
			fwrite( $file, "true" );
			fclose( $file );
		} else if ( ! $is && file_exists( $this->doingBackupFile ) ) {
			unlink( $this->doingBackupFile );
		}

		return true;
	}

	public function doBackup(): bool {
		if ( ! $this->setDoingBackup( true ) ) {
			return false;
		}

		$name = DB_NAME;
		$host = DB_HOST;
		$user = DB_USER;
		$pw   = DB_PASSWORD;

		$filename = date( "Y-m-d__H-i-s" );

		$dest = PH_BACKUPS_PATH . "/$filename.sql.gz";

		$success = exec( "mysqldump --user=$user --password=$pw --host=$host $name | gzip -c > $dest" ) !== false;

		$this->setDoingBackup( false );

		return $success;
	}

	public function cleanup() {
		$keepHistorySize = intval( PH_BACKUPS_HISTORY_SIZE );
		if ( $keepHistorySize <= 0 ) {
			return;
		}

		$backups = $this->getBackups();
		if ( count( $backups ) <= $keepHistorySize ) {
			return;
		}

		for ($i = $keepHistorySize; $i < count($backups); $i++){
			$this->deleteBackup($backups[$i]);
		}
	}

}