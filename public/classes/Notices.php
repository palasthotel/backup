<?php

namespace Palasthotel\WordPress\Backup;

use Palasthotel\WordPress\Backup\Components\Component;

class Notices extends Component {
	public function onCreate() {
		parent::onCreate();
		add_action('admin_notices', [$this, 'notices']);
	}

	public function notices(){
        if(!$this->plugin->backupManager->backupPathExists()){
			?>
			<div class="notice notice-warning">
				<p><?php _e( 'PH Backup cannot access backup path!', Plugin::DOMAIN ); ?></p>
			</div>
			<?php
		}
	}
}