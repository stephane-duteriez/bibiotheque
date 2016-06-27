<div class="col-md-4" >
	<div class="panel panel-default">
	  <div class="panel-heading">
	    <h3 class="panel-title" style="cursor: pointer;" onclick="window.location='<?php echo base_url('/livre/afficher/'. $ref_livre);?>';"><?php echo $titre;?></h3>
	    <span type="button" class="btn btn-danger vertical-center" style="cursor: pointer;" onclick="window.location='<?php echo base_url('/livre/delete/' . $ref_livre);?>';">
			  	<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
		</span>
	  </div>
	  <div class="panel-body">
	  	<div class='row'>
		  	<div class="col-sm-6"><p>Auteur :</p></div>
		  	<div class="col-sm-6"><p><?php echo $nom . " " . $prenom; ?></p></div>
		</div>
		<div class='row'>
		  	<div class="col-sm-6"><p>Serie :</p></div>
		  	<div class='col-sm-6'><p><?php echo $serie; ?></p></div>
	  	</div>
		<div class='row'>
		  	<div class="col-sm-6"><p>ISBN :</p></div>
		  	<div class='col-sm-6'><p><?php echo $ISBN; ?></p></div>	    
	  	</div>
	  	<div class='row'>
		  	<div class="col-md-12"><p>resume :</p></div>
		  	<div class='col-md-12 well'><p><?php echo $resume; ?></p></div>	    
	  	</div>
	  </div>
	</div>
</div>