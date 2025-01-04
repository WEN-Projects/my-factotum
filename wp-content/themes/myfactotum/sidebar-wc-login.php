<?php
if ( ! is_user_logged_in() ) {

	?>
    <div id="login-register-slider" class="my-account-login">
        <a href="#" class="close-overlay">
            <span></span>
            <span></span>
        </a>

		<?php
		if ( isset( $_GET["password-reset-success"] ) && wp_get_referer() ) { //if password reset is completed, popup the login slider and display the message
			?>
            <div class="woocommerce-notices-wrapper">
                <ul class="woocommerce-message" role="alert">
                    <li>
						<?php
						_e( "Votre mot de passe a bien été modifié. Vous pouvez vous connecter avec ce nouveau mot de passe." )
						?>
                    </li>
                </ul>
            </div>
            <script>
                jQuery('.my-account-login').addClass('my-account-active');
                set_slider_form_session(1);
            </script>
			<?php
		}

		if ( isset( $_SESSION['register_message'] ) ) { //if registration error occures, popup the register slider and display the message
			?>
            <div class="woocommerce-notices-wrapper">
                <ul class="woocommerce-error" role="alert">
                    <li>
						<?php echo $_SESSION['register_message']; ?>
                    </li>
                </ul>
            </div>
		<?php
		unset( $_SESSION['register_message'] );
		?>
            <script>
                jQuery('.my-account-login').addClass('my-account-active');
                set_slider_form_session(2);
            </script>
			<?php
		}
		//	    do_action( 'factotum_wc_notices' );
		if ( isset( $_SESSION['login_message'] ) ) { //if login error occures, popup the login slider and display the message
		if ( is_array( $_SESSION['login_message'] ) && ! empty( $_SESSION['login_message'] ) ) {
		foreach ( $_SESSION['login_message'] as $message ) {
			?>

            <div class="woocommerce-notices-wrapper">
                <ul class="woocommerce-error" role="alert">
                    <li>
						<?php echo $message[0]; ?>
                    </li>
                </ul>
            </div>
		<?php
		}
		}
		unset( $_SESSION['login_message'] );
		?>
            <script>
                jQuery('.my-account-login').addClass('my-account-active');
                set_slider_form_session(1);
            </script>
			<?php
		}
		?>
        <div id="slider-login-form" class="form-inner">
            <h4><?php _e( "Mon compte", "factotum" ); ?></h4>
            <form class="woocommerce-form woocommerce-form-login login" method="post">

				<?php do_action( 'woocommerce_login_form_start' ); ?>

                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                    <label for="username"><?php esc_html_e( 'Adresse mail', 'woocommerce' ); ?>
                        &nbsp;<span
                                class="required">*</span></label>
                    <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username"
                           id="username" autocomplete="username" required
                           value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>"/><?php // @codingStandardsIgnoreLine
					?>
                </p>
                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                    <label for="password"><?php esc_html_e( 'Mot de passe', 'woocommerce' ); ?>&nbsp;<span
                                class="required">*</span></label>
                    <input class="woocommerce-Input woocommerce-Input--text input-text" type="password"
                           name="password" required
                           id="password" autocomplete="current-password"/>
                </p>
                <p class="woocommerce-LostPassword lost_password">Vous avez perdu votre mot de passe ?
                    <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Cliquez ici', 'woocommerce' ); ?></a>
                </p>

				<?php do_action( 'woocommerce_login_form' ); ?>

                <p class="form-row">
                    <!--					<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">-->
                    <!--						<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme"-->
                    <!--						       type="checkbox" id="rememberme" value="forever"/>-->
                    <!--						<span>-->
					<?php //esc_html_e( 'Remember me', 'woocommerce' ); ?><!--</span>-->
                    <!--					</label>-->
					<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
                    <span class="btn-wrap">
                    <button type="submit"
                            class="woocommerce-button button woocommerce-form-login__submit btn transparent"
                            name="login"
                            value="<?php esc_attr_e( 'Log in', 'woocommerce' ); ?>"><?php esc_html_e( 'Se connecter', 'woocommerce' ); ?></button>
                    <button id="slider-register-btn" type="button"
                            class="woocommerce-button button  btn transparent dark"
                            name="login"><?php esc_html_e( 'Créez un compte gratuit', 'woocommerce' ); ?></button>
                </span><!-- .btn-wrap -->
                </p>

				<?php do_action( 'woocommerce_login_form_end' ); ?>

            </form>
        </div><!-- .form-inner -->
        <div id="slider-register-form" class="form-inner">
            <h4><?php _e( "Créer un compte", "factotum" ); ?></h4>
            <form method="post"
                  class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?> >

				<?php do_action( 'woocommerce_register_form_start' ); ?>

				<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label for="reg_username"><?php esc_html_e( 'Username', 'woocommerce' ); ?>&nbsp;<span
                                    class="required">*</span></label>
                        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username"
                               id="reg_username" autocomplete="username"
                               value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>"/><?php // @codingStandardsIgnoreLine ?>
                    </p>

				<?php endif; ?>

                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                    <label for="reg_email"><?php esc_html_e( 'Email address', 'woocommerce' ); ?>&nbsp;<span
                                class="required">*</span></label>
                    <input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email"
                           id="reg_email" autocomplete="email"
                           value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>"/><?php // @codingStandardsIgnoreLine ?>
                </p>

				<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label for="reg_password"><?php esc_html_e( 'Password', 'woocommerce' ); ?>&nbsp;<span
                                    class="required">*</span></label>
                        <input type="password" class="woocommerce-Input woocommerce-Input--text input-text"
                               name="password" id="reg_password" autocomplete="new-password"
                               value="<?php if ( ! empty( $_POST['password'] ) ) {
							       echo esc_attr( $_POST['password'] );
						       } ?>"/>
                    </p>
                    <p class="form-row form-row-wide">
                        <label for="reg_password2"><?php esc_html_e( 'Répéter le mot de passe', 'woocommerce' ); ?> <span
                                    class="required">*</span></label>
                        <input type="password" class="input-text" name="password2" id="reg_password2"
                               value="<?php if ( ! empty( $_POST['password2'] ) ) {
							       echo esc_attr( $_POST['password2'] );
						       } ?>"/>
                    </p>

				<?php else : ?>

                    <p><?php esc_html_e( 'A password will be sent to your email address.', 'woocommerce' ); ?></p>

				<?php endif; ?>
                <label class="checkbox-wrap">
                    <input type="checkbox" class="input-text" name="accept_terms_policy"/>
                    <span class="checkmark"></span>
					<?php
					wc_privacy_policy_text( 'registration' );
					?>
                </label>
                <p class="woocommerce-form-row form-row">
					<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
                    <span class="btn-wrap">
                    <button type="submit"
                            class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit btn transparent"
                            name="factotum_register"
                            value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>"><?php esc_html_e( 'Créer le compte', 'woocommerce' ); ?></button>
                    <button id="slider-login-btn" type="button"
                            class="woocommerce-button button btn transparent dark"><?php esc_html_e( 'Déjà un compte', 'woocommerce' ); ?></button>
                        </span><!-- .btn-wrap -->
                </p>

				<?php do_action( 'woocommerce_register_form_end' ); ?>

            </form>
        </div><!-- .form-inner -->
    </div><!-- .my-account-login -->

	<?php
	if ( is_checkout() ) { // if user is in checkout page and not logged in then popup the login form
		?>
<!--        <script>-->
<!--            jQuery('.my-account-login').addClass('my-account-active');-->
<!--            set_slider_form_session(1);-->
<!--        </script>-->
		<?php
	}
}


?>
