<div class="container" >
	<div class="center-block"><h1><?php echo $livre->titre;?></h1></div>
	<div class="row">
		<div class="col-md-6">
			<div class="panel panel-default">
			  <div class="panel-body row">	
			  	<div class="col-sm-6"><p>Auteur :</p></div>
			  	<div class="col-sm-6"><p><?php echo $livre->nom . " " . $livre->prenom; ?></p></div>		    
			  </div>
			  <div class="panel-body row">
			  	<div class="col-sm-6"><p>ISBN :</p></div>
			  	<div class="col-sm-6"><p><?php echo $livre->ISBN ; ?></p></div>		    
			  </div>
			  <div class="panel-body row">
			  	<div class="col-sm-6"><p>date publication :</p></div>
			  	<div class="col-sm-6"><p><?php echo $livre->publication; ?></p></div>		    
			  </div>
			  <div class="panel-body row">
			  	<div class="col-sm-12"><p>Resumé :</p></div>
			  	<div class="col-sm-12 well"><p><?php echo $livre->resume; ?></p></div>		    
			  </div>
			  <div class="panel-body row">
			    <div class="col-sm-offset-5 col-sm-2">
			      <button class="btn btn-default" onclick="window.location='<?php echo base_url('/livre/form/'.$livre->ref_livre);?>';" id="valider">Modifier</button>
			    </div>
		  	</div>	
			</div>
		</div>
		<div class="col-md-6">
			<?php foreach ($list_exemplaire as $exemplaire): ?>

			<div class="panel panel-default">
				<div class="panel-heading">
				  	<button type="button" class="btn btn-danger pull-right" aria-label="Left Align" style="cursor: pointer;" onclick="window.location='<?php echo base_url('/exemplaire/delete/'.$exemplaire['ref_exemplaire']);?>';">
					  	<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
					</button>
				  <div class="row" style="cursor: pointer;" onclick="window.location='<?php echo base_url('/exemplaire/form/'.$exemplaire['ref_exemplaire']);?>';">
			  		<div class="col-sm-6"><p>Reference :</p></div>
				  	<div class="col-sm-4"><p><?php echo $exemplaire['reference']; ?></p></div>
				  </div>
				</div>
			  <div class="panel-body">
				  <div class="row">
				  <?php $function = is_null($exemplaire['date_rendu']) && isset($exemplaire['date_emprunt']) ? 'rendre' : 'sortire';?>
				  	<div class="col-sm-offset-4 col-sm-4">
				  		<button class="btn" 
				  			onclick="<?php echo $function ;?>(<?php echo ($function==='rendre') ?  $exemplaire['id_emprunt'] : $exemplaire['id_exemplaire'] ;?>)">
				  					 <?php echo $function ;?>
				  		</button>
				  	</div>
				  </div>	    
			  </div>
			</div>
			<?php endforeach; ?>
			<div class="panel-body row">
			    <div class="col-sm-offset-5 col-sm-2">
			      <button class="btn btn-default" onclick="window.location='<?php echo base_url('/exemplaire/form?ref_livre='.$livre->ref_livre);?>';" id="valider">Ajouter</button>
			    </div>
		  	</div>	
		</div>
	</div>
</div>
<div class="centered">
	<div class="hidden panel panel-default form-horizontal" id="id_exemplaire_form">
			<div class="panel-body form-group">
				<label for="id_emprunteur" class="col-sm-6 control-label">Identifiant Emprunteur</label>
				<div class="col-sm-6">
					<input type="text" class="form-control" id="id_emprunteur" name='id_emprunteur' placeholder="000000"/>
				</div>
			</div>
			<div class="panel-body">
			    <div class="text-center">
			      <button class="btn btn-default" onclick="valider_utilisateur()">Verifier</button>
			    </div>
		  	</div>	
		  	<div class="panel-body row">
		  		<div class="hidden col-sm-offset-4 col-sm-4 text-center alert " id="nom_emprunteur"></div>
		  	</div>
			<input type="hidden" id="control_id_emprunteur" name='control_id_emprunteur'/>
			<input type="hidden" id="id_exemplaire" name='id_exemplaire'/>
		  	<div class="panel-body">
			    <div class="text-center">
			      <button class="btn btn-default" onclick="sortir_livre()" id="valider">EMPRUNTER</button>
			    </div>
		  	</div>	
	</div>
</div>
<script type="text/javascript">
	function rendre(id_emprunt)
	{
		$.ajax({url: "<?php echo base_url('/exemplaire/rendre/');?>" + '/' + id_emprunt, success: function(result){
        	$("#" + id_emprunt).remove();
        	console.log(result);
        	location.reload();
    }});
	}
	function sortire(id_exemplaire)
	{
		console.log('function sortire, id_exemplaire:' + id_exemplaire);
		$('#id_exemplaire_form').removeClass('hidden');
		$('#id_exemplaire').val(id_exemplaire);
	}
	function valider_utilisateur()
	{
		test_id=$('#id_emprunteur').val();
		$.ajax({url: "<?php echo base_url('/emprunteur/get/');?>" + '/' + test_id, success: function(json_result){
			result=JSON.parse(json_result);
			if(result['id_emprunteur'])
			{
				$('#nom_emprunteur').html('<p>' + result['nom'] + '</p>');
				$('#control_id_emprunteur').val(result['id_emprunteur']);
				$('#nom_emprunteur').removeClass('hidden');
				$('#nom_emprunteur').addClass('alert-success');
				$('#nom_emprunteur').removeClass('alert-danger');
				$('#valider').prop('disabled', false);
			} else 
			{
				$('#valider').prop('disabled', true);
				$('#control_id_emprunteur').val('');
				$('#nom_emprunteur').removeClass('hidden');
				$('#nom_emprunteur').addClass('alert-danger');
				$('#nom_emprunteur').removeClass('alert-success');
				$('#nom_emprunteur').html('<p>identifiant inconue</p>');
			}
    }});
	}
	function sortir_livre()
	{
		id_emprunteur=$('#control_id_emprunteur').val();
		id_exemplaire=$('#id_exemplaire').val();
		$.ajax({url: "<?php echo base_url('/exemplaire/emprunter/') ?>" + '/' + id_exemplaire + "/" + id_emprunteur, success: function(result){
			$('#id_exemplaire_form').addClass('hidden');
			location.reload();
    }});
	}
</script>
</html>