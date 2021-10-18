<?php

namespace Palasthotel\WordPress\Backup;

use Palasthotel\WordPress\Backup\Components\Component;
use Palasthotel\WordPress\Backup\View\BackupsTable;

class ManagementPage extends Component {
	public function onCreate() {
		parent::onCreate();
		add_action( 'admin_menu', function () {
			add_management_page( 'Backups', 'Backups', 'manage_options', 'ph-backups', [ $this, 'render' ] );
		} );
	}

	public function render() {
		$manager = $this->plugin->backupManager;
		$table   = new BackupsTable( $manager );
		$table->prepare_items();
		?>
        <div class="wrap">
            <h2>Backups</h2>
            <p><?php
		        if ( $manager->backupPathExists() ) {
			        printf(
				        __( "System path: %s", Plugin::DOMAIN ),
				        "<code>" . PH_BACKUPS_PATH . "</code>"
			        );
		        } else {
			        printf(
				        __( "System path not available: %s", Plugin::DOMAIN ),
				        "<code>" . PH_BACKUPS_PATH . "</code>"
			        );
		        }
		        ?></p>
            <p><?php
                printf(
                        __("Schedule: %s", Plugin::DOMAIN),
                        "<code>".PH_BACKUPS_SCHEDULE."</code>",
                );
                ?></p>
            <p><?php
		        printf(
			        __("History size: %s", Plugin::DOMAIN),
			        "<code>".PH_BACKUPS_HISTORY_SIZE."</code>",
		        );
		        ?></p>
			<?php $table->display(); ?>
        </div>
        <script>
            const elements = document.getElementsByClassName("ph-backup-delete");
            for (const element of elements) {
                element.addEventListener("click", function (e) {
                    e.preventDefault();
                    const deleteUrl = e.target.getAttribute("href");

                    const yesDeleteIt = confirm("Do you really want to delete this file?");
                console.debug(yesDeleteIt)
                    if(yesDeleteIt){
                        fetch(deleteUrl, {
                            method: "DELETE",
                        })
                            .then(response => response.json())
                            .then(json => {
                                console.debug(json);
                                if(json.success){
                                    window.location.reload();
                                }
                            });
                    }
                });
            }
        </script>
		<?php


	}
}