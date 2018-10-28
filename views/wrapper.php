<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>

<div class="lockdown-wp__wrapper">
	<div class="lockdown-wp__header">
		<div class="lockdown-wp__header-logo">
			<a href="<?php echo esc_url( Lockdown_WP\Plugin::get_instance()->get_url() ); ?>"><img
					src="<?php echo esc_url( LOCKDOWN_WP_URI . 'assets/images/logo.svg' ); ?>" alt="Lockdown WP"></a>
		</div>
	</div>
	<div class="lockdown-wp__content">
		<?php echo $this->content; ?>
	</div>
</div>

<script>
  jQuery(function () {
    window.LockdownWP && window.LockdownWP.pageLoaded();
  });
</script>
