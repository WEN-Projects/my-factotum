<?php
/**
 * Edit account form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_edit_account_form' ); ?>

<h2><?php echo _e( 'Détails du compte', 'factotum' ) ?></h2>

<form class="woocommerce-EditAccountForm edit-account" action=""
      method="post" <?php do_action( 'woocommerce_edit_account_form_tag' ); ?>>
    <div class="form-row">
		<?php do_action( 'woocommerce_edit_account_form_start' ); ?>
        <div class="form-col half first">
            <p class="woocommerce-form-row woocommerce-form-row--first editable">
                <a href="javascript:void(0);" class="edit-option"><?php echo _e( 'Modifier', 'factotum' ); ?></a>
                <input type="text" class="woocommerce-Input woocommerce-Input--text input-text"
                       placeholder="<?php _e( 'Nom', 'factotum' ); ?>" name="account_first_name"
                       id="account_first_name" autocomplete="given-name"
                       value="<?php echo esc_attr( $user->first_name ); ?>"/>
            </p>
        </div>
        <div class="form-col half last">
            <p class="woocommerce-form-row woocommerce-form-row--last editable">
                <a href="javascript:void(0);" class="edit-option"><?php echo _e( 'Modifier', 'factotum' ); ?></a>
                <input type="text" class="woocommerce-Input woocommerce-Input--text input-text"
                       placeholder="<?php _e( 'Prénom', 'factotum' ); ?>" name="account_last_name"
                       id="account_last_name" autocomplete="family-name"
                       value="<?php echo esc_attr( $user->last_name ); ?>"/>
            </p>
        </div>
        <div class="form-col half first">
            <p class="woocommerce-form-row woocommerce-form-row--wide">
                <input type="email" class="woocommerce-Input woocommerce-Input--email input-text"
                       placeholder="<?php _e( 'Mail', 'factotum' ); ?>" name="account_email"
                       id="account_email" autocomplete="email" value="<?php echo esc_attr( $user->user_email ); ?>"/>
            </p>
        </div>
        <div class="form-col half last">
            <label class="checkbox-wrap">
                <!--checkbox to subscribe or unsubscribe the user's mail to send in blue-->
                <input type="checkbox" class="woocommerce-Input woocommerce-Input--text input-text"
                       name="is_sendinblue_subscribed"
                       id="is_sendinblue_subscribed" <?php if ( $user->is_sendinblue_subscribed ) {
					echo "checked";
				} ?> /><?php _e( "Envoyez-moi la", "factotum" ); ?>
                newsletter à cette adresse
                <span class="checkmark"></span>
            </label>
        </div>
        <div style="width: 100%; clear: both;">
            <div class="form-col half first">
                <fieldset>
                    <legend><?php esc_html_e( 'Mot de passe', 'woocommerce' ); ?></legend>

                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row-wide editable hide-show-trigger">
                        <a href="javascript:void(0);"
                           class="edit-option show-option"><?php echo _e( 'Modifier', 'factotum' ); ?></a>
                        <input type="password" placeholder="<?php _e( 'Votre ancien mot de passe', 'factotum' ); ?>"
                               class="woocommerce-Input woocommerce-Input--password input-text" name="password_current"
                               id="password_current" autocomplete="off"/>
                    </p>
                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row-wide hidden-field">
                        <input type="password" placeholder="<?php _e( 'Votre nouveau mot de passe', 'factotum' ); ?>"
                               class="woocommerce-Input woocommerce-Input--password input-text" name="password_1"
                               id="password_1" autocomplete="off"/>
                    </p>
                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row-wide hidden-field">
                        <input type="password"
                               placeholder="<?php _e( 'Confirmer nouveau mot de passe', 'factotum' ); ?>"
                               class="woocommerce-Input woocommerce-Input--password input-text" name="password_2"
                               id="password_2" autocomplete="off"/>
                    </p>
                </fieldset>
            </div>
        </div>
        <div class="form-col full btn-wrap">
			<?php do_action( 'woocommerce_edit_account_form' ); ?>
            <p>
				<?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
                <button type="submit" class="woocommerce-Button button" name="save_account_details"
                        value="<?php esc_attr_e( 'Enregistrer', 'woocommerce' ); ?>"><?php esc_html_e( 'Enregistrer', 'woocommerce' ); ?></button>
                <input type="hidden" name="action" value="save_account_details"/>
            </p>
			<?php do_action( 'woocommerce_edit_account_form_end' ); ?>

        </div>
    </div>
</form>

<?php do_action( 'woocommerce_after_edit_account_form' ); ?>
