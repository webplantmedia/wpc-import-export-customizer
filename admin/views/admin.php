<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   WPC_Import_Export_Customizer
 * @author    Chris Baldelomar <chris@webplantmedia.com>
 * @license   GPL-2.0+
 * @link      http://webplantmedia.com
 * @copyright 2014 Chris Baldelomar
 */
?>
<?php
$plugin_prefix = $this->get_plugin_prefix();
?>


<div id="wpc-import-export-customizer-plugin" class="wrap">

	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

	<form method="post" action="">

		<?php if ( isset( $_POST['wpc2_iec_generate_default_options'] ) && $_POST['wpc2_iec_generate_default_options'] ) : ?>

			<div>

				<h3><?php echo __( 'PHP Array of Default Customizer Options', 'wpc-import-export-customizer' ); ?></h3>

				<div class="postbox">

					<?php $this->display_customizer_options(); ?>

				</div>

			</div>
	
		<?php endif; ?>

		<input type="hidden" name="wpc2_iec_generate_default_options" value="1" />

		<?php submit_button( 'Display PHP Array of Default Options' ); ?>

	</form>

	<form method="post" action="">

		<?php if ( isset( $_POST['wpc2_iec_restore_default_options'] ) && $_POST['wpc2_iec_restore_default_options'] ) : ?>

			<div>

				<h3><?php echo __( 'Restoring Default Options', 'wpc-import-export-customizer' ); ?></h3>

				<div class="postbox">

					<?php $this->restore_default_options(); ?>

				</div>

			</div>
	
		<?php endif; ?>

		<input type="hidden" name="wpc2_iec_restore_default_options" value="1" />

		<?php submit_button( 'Restore Default Options' ); ?>

	</form>

</div>
