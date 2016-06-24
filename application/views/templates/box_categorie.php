<div class="col-md-3" >		
	<div class="panel panel-default">	
		<div class="panel-heading">
	    	<h3 class="panel-title" style="cursor: pointer;" onclick="window.location='<?php echo base_url('categorie/form/'. $id_categorie);?>';"><?php echo $intitule;?></h3>
			<div type="button" class="btn btn-danger vertical-center" style="cursor: pointer;" onclick="window.location='<?php echo base_url('/categorie/delete/'.$id_categorie);?>';">
				  	<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
			</div>
	  	</div>
	    <div class="panel-body">
		  	<div class='row'>
			  	<div class="col-sm-12"><p><?php echo $description; ?></p></div>
			</div>
		</div>
	</div>
</div>