<?php echo validation_errors(); ?>
	
<div class='container'>
<h2><?php echo $titre ?></h2>

<?php if (isset($upload))
	{ 
		echo form_open_multipart('', array('class'=>'control-form form-horizontal'), $hidden);
	}
	else {
		echo form_open('', array('class'=>'control-form form-horizontal'), $hidden);
	} 
?>

<?php foreach ($inputs as $input): ?>
	<div class="form-group">
		<?php echo $input['label'];?>
		<div class="col-md-4">
		<?php echo $input['form']; ?>
		</div>
	</div>
<?php endforeach; ?>

<?php if(isset($upload)) : ?>
	<?php if ($ref_image!='') :?>
	  <div class="panel-body">
	  		<img class="img-responsive  center-block" src="<?php echo base_url('/uploads/'.$ref_image); ?>" height='100'>	    
	  </div>
	<?php endif ?>
	<div class="form-group">
		<label for="userfile" class="col-md-2 control-label">Image</label>
		<div class="col-md-4">
			<input type="file" name="userfile" size="20" class='form-control'/>
		</div>
	</div>
<?php endif ;?>

	<div class="form-group">
	    <div class="col-sm-offset-2 col-md-2">
			<?php 
				echo $submit;
				?>
		</div>
	</div>
</div>

