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

	/**
	 * @var false|int
	 */
	private $fileSize;


	public function __construct(string $filepath) {
		$this->filepath = $filepath;
	}

	public function getFilename(): string {
		return basename($this->filepath);
	}

	public function getFilepath(): string {
		return $this->filepath;
	}

	public function getFileTime(): int {
		if($this->fileTime == null){
			$this->fileTime = filesize($this->filepath);
		}
		return $this->fileTime;
	}

	public function getFileSizeInByte(){
		if($this->fileSize == null){
			$this->fileSize = filesize($this->filepath);
		}
		return $this->fileSize;
	}

	public function getFileSizeInMB(){

		return $this->getFileSizeInByte() / pow(1024, 2);
	}

	public function exists(): bool {
		return file_exists($this->filepath);
	}
}