<?php

namespace Palasthotel\WordPress\Backup\Model;

class BackupEntity {

	/**
	 * @var string
	 */
	private $filepath;

	/**
	 * @var int
	 */
	private $fileTime;


	public function __construct(string $filepath) {
		$this->filepath = $filepath;
		$this->fileTime = filemtime($filepath);
	}

	public function getFilename(): string {
		return basename($this->filepath);
	}

	public function getFilepath(): string {
		return $this->filepath;
	}

	public function getFileTime(): int {
		return $this->fileTime;
	}

	public function exists(): bool {
		return file_exists($this->filepath);
	}
}