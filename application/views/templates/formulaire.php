<?php echo validation_errors(); ?>
	
<div class='container'>
<h2><?php echo $titre ?></h2>

<?php echo form_open('', array('class'=>'control-form form-horizontal'), $hidden); ?>

<?php foreach ($inputs as $input): ?>
	<div class="form-group">
		<?php echo $input['label'];?>
		<div class="col-md-4">
		<?php echo $input['form']; ?>
		</div>
	</div>
<?php endforeach; ?>

	<div class="form-group">
	    <div class="col-sm-offset-2 col-md-2">
			<?php 
				echo $submit;
				?>
		</div>
	</div>
</div>

