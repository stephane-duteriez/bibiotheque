<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Community Auth - Choose Password Form View
 *
 * Community Auth is an open source authentication application for CodeIgniter 3
 *
 * @package     Community Auth
 * @author      Robert B Gottier
 * @copyright   Copyright (c) 2011 - 2016, Robert B Gottier. (http://brianswebdesign.com/)
 * @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
 * @link        http://community-auth.com
 */
?>

<h1>Changement de mot de passe</h1>

<?php

$showform = 1;

if( isset( $validation_errors ) )
{
	echo '
		<div style="border:1px solid red;">
			<p>
				The following error occurred while changing your password:
			</p>
			<ul>
				' . $validation_errors . '
			</ul>
			<p>
				PASSWORD NOT UPDATED
			</p>
		</div>
	';
}
else
{
	$display_instructions = 1;
}
if( isset( $validation_passed ) )
{
	echo '
		<div style="border:1px solid green;">
			<p>
				You have successfully changed your password.
			</p>
			<p>
				You can now <a href="/' . LOGIN_PAGE . '">login</a>
			</p>
		</div>
	';

	$showform = 0;
}

if( $showform == 1 )
{	
	if( isset( $auth_user_id ) || isset($recovery_code, $user_id))
	{
		if( isset( $display_instructions ) )
		{
			if( isset( $username ) )
			{
				echo '<p>
					Votre nom d\'utilisateur est <i>' . $username . '</i><br />
					Noter le s\'il vous plait, et changer vortre mot de passe.
				</p>';
			}
			else
			{
				echo '<p>Changer vorte mot de passe s\'il vous plais:</p>';
			}
		}

		?>
			<div id="form">
				<?php if(isset($link_uri)) echo form_open($link_uri, array('class' => 'form-horizontal')); 
				else  echo form_open("user_admin/change_user_password", array('class' => 'form-horizontal'));?>
					<fieldset >
						<div class="form-group">

							<?php
								// PASSWORD LABEL AND INPUT ********************************
								echo form_label('Password','passwd',array('class'=>'control-label col-sm-2 col-sm-offset-2'));
							?>
							<div class="col-sm-6">
							<?php
								$input_data = array(
									'name'       => 'passwd',
									'id'         => 'passwd',
									'class'      => 'form-control password',
									'max_length' => config_item('max_chars_for_password')
								);
								echo form_password($input_data);
							?>
							</div>
						</div>
						<div>

							<?php
								// CONFIRM PASSWORD LABEL AND INPUT ******************************
								echo form_label('Confirm Password','passwd_confirm',array('class'=>'control-label col-sm-2 col-sm-offset-2'));
							?>
							<div class="col-sm-6">
							<?php
								$input_data = array(
									'name'       => 'passwd_confirm',
									'id'         => 'passwd_confirm',
									'class'      => 'form-control password',
									'max_length' => config_item('max_chars_for_password')
								);
								echo form_password($input_data);
							?>
							</div>

						</div>
					</fieldset>
					<div class="row form-group">
						<div class="col-sm-2 col-sm-offset-6">

							<?php

								// RECOVERY CODE *****************************************************************
								echo form_hidden('recovery_code',(isset($recovery_code))? $recovery_code : FALSE);

								// USER ID *****************************************************************
								echo form_hidden('user_identification', (isset($auth_user_id)) ? $auth_user_id : $user_id);

								// SUBMIT BUTTON **************************************************************
								$input_data = array(
									'name'  => 'form_submit',
									'id'    => 'submit_button',
									'value' => 'Change Password',
									'class' => 'btn btn-primary'
								);
								echo form_submit($input_data);
							?>

						</div>
					</div>
				</form>
			</div>
		<?php
	}
}