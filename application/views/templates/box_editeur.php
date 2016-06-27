<div class="col-md-3" >
	<div class="panel panel-default" >
		<div class="panel-heading">
		    <span type="button" class="btn btn-danger vertical-center" style="cursor: pointer;" onclick="window.location='<?php echo base_url('editeur/delete/'.$ref_editeur);?>'">
				  	<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
			</span>
			    <h3 class="panel-title" style="cursor: pointer;" onclick="window.location='<?php echo base_url('editeur/form/'.$ref_editeur);?>'"><?php echo $intitule;?></h3>
		</div>
	</div>
</div>