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
				<div class="lockdown-wp__option-checkwrap">
					<input type="checkbox" name="lockdown_wp[<?php echo esc_attr( $lockdown_option ); ?>]" value="1"
					       class="lockdown-wp__option-check"
					       id="lockdown-wp__option-<?php echo esc_attr( $lockdown_option ); ?>"
						<?php echo ! empty( $lockdown_settings['enabled'] ) ? ' checked' : ''; ?>>
					<label for="lockdown-wp__option-<?php echo esc_attr( $lockdown_option ); ?>"
					       class="lockdown-wp__option-labelcheck">
						<svg viewBox="0,0,50,50">
							<path d="M5 30 L 20 45 L 45 5"></path>
						</svg>
					</label>
				</div>
				<label class="lockdown-wp__option-labeltext"
				       for="lockdown-wp__option-<?php echo esc_attr( $lockdown_option ); ?>">
					<?php echo esc_html( $lockdown_settings['title'] ); ?>
				</label>
				<div class="lockdown-wp__option-labeldescription">
					<p><?php echo esc_html( $lockdown_settings['description'] ); ?></p>
				</div>
				<div class="lockdown-wp__option-toggle">
					<p>
						<label for="lockdown-wp__option-meta-<?php echo esc_attr( $lockdown_option ); ?>"
						       class="lockdown-wp__option-toggle-label">
							Pro's &amp; Con's &raquo;
						</label>
					</p>
				</div>
			</div>
			<input type="checkbox" name="toggler" value="1"
			       class="lockdown-wp__option-toggler"
			       id="lockdown-wp__option-meta-<?php echo esc_attr( $lockdown_option ); ?>">
			<div class="lockdown-wp__option-meta">
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

	<input type="submit" name="upload" value="<?php esc_attr_e( 'Save Settings', 'lockdown-wp' ); ?>"
	       class="button button-primary">
</form>
