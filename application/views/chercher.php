<div class="container">
	<h2 class='text-center'>Recherche Livre <span><button class='btn btn-default' onclick="change_recherche()">Recherche avance</button></span></h2>
	<div id='recherche_simple' >
		<div class="row">
			<div class='input-group col-xs-4 col-xs-offset-4'>
				<input id='indice' class='form-control'/>
				<span class="input-group-btn">
		        	<button class="btn btn-default" type="button" onclick="chercher();">rechercher</button>
		      	</span>
			</div>
		</div>
			<div class='text-center'>
			<?php $list_option = array(
				1=>'titre',
				2=>'resume',
				4=>'auteur',
				8=>'serie'
				);
			foreach ($list_option as $val => $option) : ?>
				<div class="checkbox-inline">
				  <label>
				    <input class="option" type="checkbox" value="<?php echo $val ?>"><?php echo $option ?>
				    	
				  </label>
				</div>
			<?php endforeach ;?>
			</div>
	</div>
	<div id='recherche_avance' class='hidden'>
		<?php foreach ($list_option as $val => $option) : ?>
			<div class='input-group col-xs-4 col-xs-offset-4'>
				<input id='indice_<?php echo $val ?>' placeholder='<?php echo $option ?>' class='form-control'/>
				<span class="input-group-addon">
		        	<input class="option_adv" type="checkbox" value="<?php echo $val ?>">
		      	</span>
			</div>
		<?php endforeach ;?>
		<div class='text-center'>
			<button class="btn btn-default" type="button" onclick="cherche_avance();">rechercher</button>
		</div>
	</div>
	<div class='row resultat' id='resultat'>
	</div>
</div>
<script type="text/javascript">
	function chercher()
	{
		indice=$('#indice').val();
		if (indice.length >3)
		{
			option=0;
			$('.option:checked').each(function(){
				if (this)
				option+=parseInt($(this).val());
			})
			$.ajax({url: "<?php echo base_url('/livre/seek/');?>" + '/' + indice + '/' + option, success: function(result){
				if(result!='error')
				{
					$('#resultat').html(result);
				} else 
				{
					$('#resultat').html('');
				}
	    	}});
			
		}
	}

	function cherche_avance()
	{
		option=0;
		url_options='';
		console.log('cherche_avance');
		$('.option_adv:checked').each(function(){
			if (this && $('#indice_' + $(this).val()).val().length > 3)
				option=parseInt($(this).val());
				url_options+=option + '=' + $('#indice_' + $(this).val()).val() + '&';
		})
		if (option>0)
		{
			$.ajax({url: "<?php echo base_url('/livre/seek');?>?" + url_options, success: function(result){
				if(result!='error')
				{
					$('#resultat').html(result);
				} else 
				{
					$('#resultat').html('');
				}
	    	}});
		}
	}

	recherche_avance = false;
	function change_recherche()
	{
		if (recherche_avance)
		{
			recherche_avance=false;
			$('#recherche_simple').removeClass('hidden');
			$('#recherche_avance').addClass('hidden');
		} else
		{
			recherche_avance=true;
			$('#recherche_simple').addClass('hidden');
			$('#recherche_avance').removeClass('hidden');
		}
	}

</script>