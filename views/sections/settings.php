<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


$lockdown         = Lockdown_WP\Lockdown::get_instance();
$lockdown_options = $lockdown->get_lockdown_options();

?>

<form action="<?php echo admin_url( 'admin.php' ); ?>" method="post" enctype="multipart/form-data">
	<input type="hidden" name="action" value="lockdown_wp_options"/>
	<?php wp_nonce_field( 'lockdown_wp_options', 'lockdown_wp_options' ); ?>

	<?php foreach ( $lockdown_options as $lockdown_option => $lockdown_settings ) { ?>
		<div class="lockdown-wp__option">
			<div class="lockdown-wp__option-setting">
				<input type="checkbox" name="lockdown_wp[<?php echo esc_attr( $lockdown_option ); ?>]" value="1"
				       class="lockdown-wp__option-check"
				       id="lockdown-wp__option-<?php echo esc_attr( $lockdown_option ); ?>"
					<?php echo ! empty( $lockdown_settings['enabled'] ) ? ' checked' : ''; ?>>
				<label for="lockdown-wp__option-<?php echo esc_attr( $lockdown_option ); ?>"
				       class="lockdown-wp__option-label">
					<?php echo esc_html( $lockdown_settings['title'] ); ?>
				</label>
			</div>
			<div class="lockdown-wp__option-meta">
				<div class="lockdown-wp__option-description">
					<p><?php echo esc_html( $lockdown_settings['description'] ); ?></p>
				</div>
				<div class="lockdown-wp__option-feature">
					<div class="lockdown-wp__option-feature--pro">
						<h4><?php esc_html_e( 'Pros:', 'lockdown-wp' ); ?></h4>
						<p><?php echo esc_html( $lockdown_settings['pro'] ); ?></p>
					</div>
					<div class="lockdown-wp__option-feature--con">
						<h4><?php esc_html_e( 'Cons:', 'lockdown-wp' ); ?></h4>
						<p><?php echo esc_html( $lockdown_settings['con'] ); ?></p>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>

	<input type="submit" name="upload" value="<?php esc_attr_e( 'Save Settings', 'lockdown-wp' ); ?>">
</form>
