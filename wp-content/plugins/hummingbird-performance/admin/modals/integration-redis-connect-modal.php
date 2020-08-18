<?php
/**
 * Integrations redis connect modal.
 *
 * @since 2.5.0
 * @package Hummingbird
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wp_object_cache;
$redis_connected = false;
if ( is_object( $wp_object_cache ) && method_exists( $wp_object_cache, 'redis_status' ) ) {
	$redis_connected = $wp_object_cache->redis_status();
}

?>

<div class="sui-modal sui-modal-sm">

	<div
		role="dialog"
		id="redis-connect"
		class="sui-modal-content"
		aria-modal="true"
		aria-labelledby="redis-connect-title"
		aria-describedby="redis-connect-desc"
	>

		<div class="sui-box">
			<div class="sui-box-header sui-flatten sui-content-center sui-spacing-top--60 sui-spacing-sides--100">
				<figure class="sui-box-logo" aria-hidden="true">
					<img src="<?php echo esc_url( WPHB_DIR_URL . 'admin/assets/image/integrations/icon-redis-large.png' ); ?>" alt="<?php esc_attr_e( 'Connect Redis', 'wphb' ); ?>"
						srcset="<?php echo esc_url( WPHB_DIR_URL . 'admin/assets/image/integrations/icon-redis-large.png' ); ?> 1x, <?php echo esc_url( WPHB_DIR_URL . 'admin/assets/image/integrations/icon-redis-large@2x.png' ); ?> 2x">
				</figure>

				<button class="sui-button-icon sui-button-float--right" data-modal-close="">
					<i class="sui-icon-close sui-md" aria-hidden="true"></i>
					<span class="sui-screen-reader-text"><?php esc_attr_e( 'Close this modal', 'wphb' ); ?></span>
				</button>

				<h3 id="redis-connect-title" class="sui-box-title sui-lg">
					<?php $redis_connected ? esc_html_e( 'Configure Redis', 'wphb' ) : esc_html_e( 'Connect Redis', 'wphb' ); ?>
				</h3>

				<p id="redis-connect-desc" class="sui-description">
					<?php
					$redis_connected ?
						esc_html_e( 'Your account is authenticated.', 'wphb' ) :
						esc_html_e( 'Enter your credentials below to connect your account.', 'wphb' );
					?>
				</p>
			</div>
			<div class="sui-box-header sui-flatten sui-spacing-sides--30">
				<div class="sui-notice sui-hidden sui-notice-error">
					<p id="redis-connect-notice-on-modal"><!-- error message --></p>
				</div>
			</div>	

			<form id="redis-settings-form">
				<input type="hidden" id="redis-connected" value="<?php echo (int) $redis_connected ?>">
				<div class="sui-box-body">
					<div class="sui-form-field">
						<label for="redis-host" id="label-redis-host" class="sui-label">
							<?php esc_html_e( 'Host', 'wphb' ); ?>
						</label>
						<input type="text"
							placeholder="<?php esc_attr_e( 'E.g. 127.0.0.1', 'wphb' ); ?>"
							value="<?php echo defined( 'WPHB_REDIS_HOST' ) ? esc_html( constant( 'WPHB_REDIS_HOST' ) ) : ''; ?>"
							id="redis-host"
							name="redis-host"
							class="sui-form-control"
							aria-labelledby="label-redis-host" required>
					</div>

					<div class="sui-form-field">
						<label for="redis-port" id="label-redis-port" class="sui-label">
							<?php esc_html_e( 'Port', 'wphb' ); ?>
						</label>
						<input type="number"
							placeholder="<?php esc_attr_e( 'E.g. 6379', 'wphb' ); ?>"
							value="<?php echo defined( 'WPHB_REDIS_PORT' ) ? absint( constant( 'WPHB_REDIS_PORT' ) ) : ''; ?>"
							id="redis-port"
							name="redis-port"
							class="sui-form-control"
							aria-labelledby="label-redis-port" required>
					</div>

					<div class="sui-form-field">
						<label for="redis-password" id="label-redis-password" class="sui-label">
							<?php esc_html_e( 'Password', 'wphb' ); ?>
						</label>
						<input type="password"
							placeholder="<?php esc_attr_e( 'Enter password here', 'wphb' ); ?>"
							value="<?php echo defined( 'WPHB_REDIS_PASSWORD' ) ? esc_html( constant( 'WPHB_REDIS_PASSWORD' ) ) : ''; ?>"
							id="redis-password"
							name="redis-password"
							class="sui-form-control"
							aria-labelledby="label-redis-password">
					</div>
				</div>

				<div class="sui-box-footer sui-flatten sui-content-<?php echo defined( 'WPHB_REDIS_HOST' ) && $redis_connected ? 'separated' : 'right'; ?>">
					<?php if ( defined( 'WPHB_REDIS_HOST' ) && $redis_connected ) : ?>
						<button role="button" class="sui-button sui-button-ghost" id="redis-disconnect">
							<?php esc_html_e( 'Disconnect', 'wphb' ); ?>
						</button>
					<?php endif; ?>
					<button role="button" type="submit" class="sui-button sui-button-blue" id="redis-connect-save">
						<?php
						$redis_connected ?
							esc_html_e( 'Save changes', 'wphb' ) :
							esc_html_e( 'Connect', 'wphb' );
						?>
					</button>
				</div>
			</form>
		</div>
	</div>

</div>
