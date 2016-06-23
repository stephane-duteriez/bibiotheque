<div class="container">
	<h2 class='text-center'>Recherche Livre</h2>
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
			console.log(option);
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
</script>