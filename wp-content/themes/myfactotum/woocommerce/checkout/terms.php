<?php
/**
 * Checkout terms and conditions area.
 *
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

if ( apply_filters( 'woocommerce_checkout_show_terms', true ) && function_exists( 'wc_terms_and_conditions_checkbox_enabled' ) ) {
	do_action( 'woocommerce_checkout_before_terms_and_conditions' );

	?>
    <div class="woocommerce-terms-and-conditions-wrapper">
		<?php
		/**
		 * Terms and conditions hook used to inject content.
		 *
		 * @since 3.4.0.
		 * @hooked wc_checkout_privacy_policy_text() Shows custom privacy policy text. Priority 20.
		 * @hooked wc_terms_and_conditions_page_content() Shows t&c page content. Priority 30.
		 */
		do_action( 'woocommerce_checkout_terms_and_conditions' );
		?>

		<?php if ( wc_terms_and_conditions_checkbox_enabled() ) : ?>
            <p class="form-row validate-required checkbox-wrap">
                <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
                    <input type="checkbox"
                           class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox"
                           name="terms" <?php checked( apply_filters( 'woocommerce_terms_is_checked_default', isset( $_POST['terms'] ) ), true ); // WPCS: input var ok, csrf ok. ?>
                           id="terms"/>
                    <span class="checkmark"></span>
                    <span class="woocommerce-terms-and-conditions-checkbox-text"><?php wc_terms_and_conditions_checkbox_text(); ?></span>&nbsp;<span
                            class="required">*</span>
                </label>
                <input type="hidden" name="terms-field" value="1"/>
            </p>
            <p class="form-row validate-required checkbox-wrap custom-checkboxes">
                <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox checkbox general_terms_of_sale">
                    <input type="checkbox"
                           class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox"
                           name="general_terms_of_sale">
                    <span class="checkmark"></span>
					<?php
					$terms_of_sales_page = get_field( "terms_of_sales_page", "option" );
					?>
                    <span class="woocommerce-terms-and-conditions-checkbox-text">
                        <?php _e( "J'ai lu et j'accepte les", "woocommerce" ); ?>
						<?php
						if ( $terms_of_sales_page ) {
							?>
                            <a href="<?php echo esc_url( $terms_of_sales_page ); ?>"><?php _e( "Conditions Générales de Vente", "factotum" ); ?></a>
							<?php
						}
						?>
                    </span>
                    <span
                            class="required">*</span>
                </label>
            </p>
		<?php endif; ?>
    </div>
	<?php

	do_action( 'woocommerce_checkout_after_terms_and_conditions' );
}
