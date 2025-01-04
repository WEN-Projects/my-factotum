<?php
/**
 * Lost password reset form.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-reset-password.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.5
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="section-without-banner">
    <div class="container">
		<?php
		do_action( 'woocommerce_before_reset_password_form' );
		?>
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <form method="post" class="woocommerce-ResetPassword lost_reset_password">

                    <h4><?php echo apply_filters( 'woocommerce_reset_password_message', esc_html__( 'Enter a new password below.', 'woocommerce' ) ); ?></h4><?php // @codingStandardsIgnoreLine ?>

                    <p class="woocommerce-form-row woocommerce-form-row--first form-row">
                        <label for="password_1" style="width: 100%"><?php esc_html_e( 'New password', 'woocommerce' ); ?>
                            &nbsp;<span class="required">*</span></label><br>
                        <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password_1" id="password_1" autocomplete="new-password"/>
                    </p>
                    <p class="woocommerce-form-row woocommerce-form-row--last form-row">
                        <label for="password_2" style="width: 100%"><?php esc_html_e( 'Re-enter new password', 'woocommerce' ); ?>
                            &nbsp;<span class="required">*</span></label><br>
                        <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password_2" id="password_2" autocomplete="new-password"/>
                    </p>

                    <input type="hidden" name="reset_key" value="<?php echo esc_attr( $args['key'] ); ?>"/>
                    <input type="hidden" name="reset_login" value="<?php echo esc_attr( $args['login'] ); ?>"/>

                    <div class="clear"></div>

					<?php do_action( 'woocommerce_resetpassword_form' ); ?>

                    <p class="woocommerce-form-row form-row">
                        <input type="hidden" name="wc_reset_password" value="true"/>
                        <button type="submit" class="woocommerce-Button button" value="<?php esc_attr_e( 'Save', 'woocommerce' ); ?>"><?php esc_html_e( 'Save', 'woocommerce' ); ?></button>
                    </p>

					<?php wp_nonce_field( 'reset_password', 'woocommerce-reset-password-nonce' ); ?>

                </form>
            </div>
        </div>
		<?php
		do_action( 'woocommerce_after_reset_password_form' ); ?>
    </div>
</section>
