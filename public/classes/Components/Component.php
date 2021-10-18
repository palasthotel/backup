<?php


namespace Palasthotel\WordPress\Backup\Components;

/**
 * Class Component
 *
 * @property \Palasthotel\WordPress\Backup\Plugin plugin
 *
 * @package Palasthotel\WordPress
 * @version 0.1.2
 */
abstract class Component {
	/**
	 * _Component constructor.
	 *
	 * @param \Palasthotel\WordPress\Backup\Plugin $plugin
	 */
	public function __construct(\Palasthotel\WordPress\Backup\Plugin $plugin) {
		$this->plugin = $plugin;
		$this->onCreate();
	}

	/**
	 * overwrite this method in component implementations
	 */
	public function onCreate(){
		// init your hooks and stuff
	}
}