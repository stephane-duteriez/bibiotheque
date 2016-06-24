<div class="col-md-3" >
	<div class="panel panel-default">
		 <div class="panel-heading">
		    <h3 class="panel-title" style="cursor: pointer;" onclick="window.location='<?php echo base_url('/emprunteur/form/'. $id_emprunteur);?>';"><?php echo $nom. ' '. $prenom;?></h3>
		    <div type="button" class="btn btn-danger vertical-center" style="cursor: pointer;" onclick="window.location='<?php echo base_url('/emprunteur/delete/'. $id_emprunteur);?>';">
					  	<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
			</div>
		</div>
	  	<div class="panel-body">
		  	<div class='row'>
			  	<div class="col-sm-12"><p><?php echo $mail; ?></p></div>
			</div>
			<div class="row">
				<div class="col-sm-6"><p>date naissance</p></div>
		  		<div class="col-sm-6"><p><?php echo substr($naissance, 0, 10); ?></p></div>		    
		  	</div>
		  	<div class='row'>
		  		<div class="col-sm-6"><p>téléphone</p></div>
				<div class="col-sm-6"><p><?php echo $phone; ?></p></div>
			</div>
		</div>
	</div>
</div>
