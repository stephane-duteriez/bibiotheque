<div class="col-md-3" >
	<div class="panel panel-default" >
	  <div class="panel-heading">
	    <h3 class="panel-title" style="cursor: pointer;" onclick="window.location='<?php echo base_url('auteur/form/'. $id_auteur);?>';"><?php echo $nom;?></h3>
		<span type="button" class="btn btn-danger vertical-center" style="cursor: pointer;" onclick="window.location='<?php echo base_url('auteur/delete/'.$id_auteur);?>';">
			  	<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
		</span>
	  </div>
	  <div class="panel-body">
	  <div class="row">
	  	<div class="col-md-12"><p><?php echo $prenom; ?></p></div>		    
	  </div>
	  <div class="row">
	  	<div class="col-md-12"><p><?php echo substr($date_naissance, 0, 10); ?></p></div>		    
	  </div>
	  <div class="row">
	  	<div class="col-md-12"><p><?php echo substr($date_dece, 0, 10); ?></p></div>		    
	  </div>
	  <div class="row">
	  	<div class="col-md-12"><p><?php echo $nationalite; ?></p></div>		    
	  </div>
	  </div>
	</div>
</div>