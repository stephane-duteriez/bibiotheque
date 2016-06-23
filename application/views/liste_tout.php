<div class="container">
	<h2>Liste des Livres</h2>
	<div class="row">
		<?php echo $livres; ?>
	</div>
	<h2>Liste des Auteurs</h2>
	<div class="row">
	<?php
		foreach ($auteurs as $auteur) :
	?>
		
		<div class="col-md-3" >
			<div class="panel panel-default" >
			  <div class="panel-heading">
			    <h3 class="panel-title" style="cursor: pointer;" onclick="window.location='<?php echo base_url('auteur/form/'. $auteur['id_auteur']);?>';"><?php echo $auteur['nom'];?></h3>
				<span type="button" class="btn btn-danger vertical-center" style="cursor: pointer;" onclick="window.location='<?php echo base_url('auteur/delete/'.$auteur['id_auteur']);?>';">
					  	<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
				</span>
			  </div>
			  <div class="panel-body row">
			  	<div class="col-md-12"><p><?php echo $auteur['prenom']; ?></p></div>		    
			  </div>
			  <div class="panel-body row">
			  	<div class="col-md-12"><p><?php echo substr($auteur['date_naissance'], 0, 10); ?></p></div>		    
			  </div>
			</div>
		</div>
	<?php endforeach ;?>
	</div>
	<h2>Liste des Éditeurs</h2>
	<div class="row">
	<?php
		foreach ($editeurs as $editeur) :
	?>
		<div class="col-md-3" >
			<div class="panel panel-default" >
				<div class="panel-heading">
				    <span type="button" class="btn btn-danger vertical-center" style="cursor: pointer;" onclick="window.location='<?php echo base_url('editeur/delete/'.$editeur['id_editeur']);?>'">
						  	<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
					</span>
					    <h3 class="panel-title" style="cursor: pointer;" onclick="window.location='<?php echo base_url('editeur/form/'.$editeur['id_editeur']);?>'"><?php echo $editeur['intitule'];?></h3>
				</div>
			</div>
		</div>
	<?php endforeach ;?>
	</div>
	<h2>Liste des Catégories</h2>
	<div class="row">
	<?php
		foreach ($categories as $categorie) :
	?>
		<div class="col-md-3" >		
			<div class="panel panel-default">	
				<div class="panel-heading">
			    	<h3 class="panel-title" style="cursor: pointer;" onclick="window.location='<?php echo base_url('categorie/form/'. $categorie['id_categorie']);?>';"><?php echo $categorie['intitule'];?></h3>
					<div type="button" class="btn btn-danger vertical-center" style="cursor: pointer;" onclick="window.location='<?php echo base_url('/categorie/delete/'.$categorie['id_categorie']);?>';">
						  	<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
					</div>

			  	</div>
			</div>
		</div>
	<?php endforeach ;?>
	</div>
	<h2>Liste des Emprunteurs</h2>
	<div class="row">
	<?php
		foreach ($emprunteurs as $emprunteur) :
	?>
		<div class="col-md-3" >
			<div class="panel panel-default">
			  <div class="panel-heading">
			    <h3 class="panel-title" style="cursor: pointer;" onclick="window.location='<?php echo base_url('/emprunteur/form/'. $emprunteur['id_emprunteur']);?>';"><?php echo $emprunteur['nom']. ' '. $emprunteur['prenom'];?></h3>
			    <div type="button" class="btn btn-danger vertical-center" style="cursor: pointer;" onclick="window.location='<?php echo base_url('/emprunteur/delete/'. $emprunteur['id_emprunteur']);?>';">
						  	<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
				</div>
			  </div>
			</div>
		</div>
	<?php endforeach ;?>
	</div>