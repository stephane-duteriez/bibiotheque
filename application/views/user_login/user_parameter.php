<?php echo form_open( "user_admin/change_user_parameter", array( 'class' => 'form-horizontal' ) ); ?>
<div class="form-group">
	<label for="username" class="control-label col-sm-2 col-sm-offset-2">Nom d'utilisateur :</label>
	<div class="col-sm-6">
		<input type="text" name="username" id="username" class="form-control" autocomplete="off" maxlength="255" autofocus placeholder="Nom d'utilisateur" value="<?php echo $auth_username?>"/>
	</div>
</div>
<div class="form-group">
	<label for="email" class="control-label col-sm-2 col-sm-offset-2">Adresse email :</label>
	<div class="col-sm-6">
		<input type="text" name="email" id="email" class="form-control" autocomplete="off" maxlength="255" autofocus placeholder="Adresse email" value="<?php echo $auth_email?>"/>
	</div>
</div>
<div class="form-group">
	<div class="col-sm-2 col-sm-offset-5">
		<button class="btn btn-lg btn-primary btn-block" type="submit" name="submit" value="Login" id="submit_button">Modifier</button>
	</div>
</div>
<div class="form-group">
	<div class="col-sm-6 col-sm-offset-3">
		<a href="<?php echo base_url();?>user_admin/change_user_password" class="btn btn-lg btn-warning btn-block" name="change_password" value="Login" id="submit_button">Changer de mot de pass</a>
	</div>
</div>
</form>