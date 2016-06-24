<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Community Auth - Login Form View
 *
 * Community Auth is an open source authentication application for CodeIgniter 3
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2016, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */

echo '<div class="container" >';
if( ! isset( $on_hold_message ) )
{
	if( isset( $login_error_mesg ) )
	{
		echo '
			<div style="border:1px solid red;">
				<p>
					Login Error #' . $this->authentication->login_errors_count . '/' . config_item('max_allowed_attempts') . ': Invalid Username, Email Address, or Password.
				</p>
				<p>
					Username, email address and password are all case sensitive.
				</p>
			</div>
		';
	}

	echo form_open( $login_url, array( 'class' => 'form-signin' ) ); 
	if( ! isset( $optional_login ) )
	{
		echo '<h2 class="form-signin-heading">Please sign in</h2>';
	}
?>

		<label for="login_string" class="sr-only">Username or Email</label>
		<input type="text" name="login_string" id="login_string" class="form-control" autocomplete="off" maxlength="255" autofocus placeholder="Adress email ou login"/>
		<label for="login_pass" class="sr-only">password</label>
		<input type="password" name="login_pass" id="login_pass" class="form-control password" maxlength="<?php echo config_item('max_chars_for_password'); ?>" autocomplete="off" readonly="readonly" onfocus="this.removeAttribute('readonly');" placeholder="Mot de pass"/>


		<?php
			if( config_item('allow_remember_me') )
			{
		?>
		<div class="checkbox">
			<label for="remember_me">Permanent
				<input type="checkbox" id="remember_me" name="remember_me" value="yes" />
			</label>

		<?php
			}
		?>

		<p>
			<?php
				$link_protocol = USE_SSL ? 'https' : NULL;
			?>
			<a href="<?php echo site_url('user_admin/recover', $link_protocol); ?>">
				Can't access your account?
			</a>
		</p>


		<button class="btn btn-lg btn-primary btn-block" type="submit" name="submit" value="Login" id="submit_button">S'identifier</button>

	</div>
</form>

<?php

	}
	else
	{
		// EXCESSIVE LOGIN ATTEMPTS ERROR MESSAGE
		echo '
			<div style="border:1px solid red;">
				<p>
					Excessive Login Attempts
				</p>
				<p>
					You have exceeded the maximum number of failed login<br />
					attempts that this website will allow.
				<p>
				<p>
					Your access to login and account recovery has been blocked for ' . ( (int) config_item('seconds_on_hold') / 60 ) . ' minutes.
				</p>
				<p>
					Please use the <a href="/bapteme/recover">Account Recovery</a> after ' . ( (int) config_item('seconds_on_hold') / 60 ) . ' minutes has passed,<br />
					or contact us if you require assistance gaining access to your account.
				</p>
			</div>
		';
	}
	echo '</div>';

/* End of file login_form.php */
/* Location: /community_auth/views/examples/login_form.php */ 
