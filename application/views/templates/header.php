<html>
<head>
	<link href=<?php echo base_url("assets/bootstrap/css/bootstrap.min.css")?> rel="stylesheet">
	<style type="text/css">
		.navbar-right {margin-right: 10px;}
		.centered {
			  position: fixed;
			  top: 50%;
			  left: 50%;
			  /* bring your own prefixes */
			  transform: translate(-50%, -50%);
			}
		.vertical-center {
			position: absolute;
			  top: 50%;
			  right: 0px;
			  margin-right: 0.5em;
			  /* bring your own prefixes */
			  transform: translate(0, -50%);
		}
		.panel-heading {
			position: relative;
		}
		.resultat {
			margin-top: 1em;
		}
		.row.is-flex {
    display: flex;
    flex-wrap: wrap;
}
.row.is-flex > [class*='col-'] {
    display: flex;
    flex-direction: column;
}
	</style>
</head>
<body>
<nav class="navbar navbar-default">
	<a class="navbar-brand" href=<?php echo base_url("")?>>Bibliotheque</a>
	<ul class="nav navbar-nav">
	<li class='dropdown'>
		  <a href="#" role="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		    Liste <span class="caret"></span>
		  </a>
		  <ul class="dropdown-menu">
		    <li><a href="<?php echo base_url('/auteur/liste')?>">Auteur</a></li>
		    <li><a href="<?php echo base_url('/editeur/liste')?>">Editeur</a></li>
		    <li><a href="<?php echo base_url('/categorie/liste')?>">Catégorie</a></li>
		    <li><a href="<?php echo base_url('/livre/liste')?>">Livre</a></li>
		    <li><a href="<?php echo base_url('/emprunteur/liste')?>">Emprunteur</a></li>
		  </ul>
		</li>
		<li><a href="<?php echo base_url('/main/liste_tout')?>">Liste Données</a></li>
	</ul>
	<ul class="nav navbar-nav navbar-right">
		<li class='dropdown'>
		  <a href="#" role="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		    Ajouter <span class="caret"></span>
		  </a>
		  <ul class="dropdown-menu">
		    <li><a href="/auteur/form">Auteur</a></li>
		    <li><a href="/editeur/form">Editeur</a></li>
		    <li><a href="/categorie/form">Catégorie</a></li>
		    <li><a href="/livre/form">Livre</a></li>
		    <li><a href="/exemplaire/form">Exemplaire</a></li>
		    <li><a href="/emprunteur/form">Emprunteur</a></li>
		  </ul>
		</li>
		<li>
		<?php 
			$link_protocol = USE_SSL ? 'https' : NULL;
			if( isset($auth_user_id) )
				{
				    echo anchor( site_url('user_admin/logout', $link_protocol ),'se deconnecter');;
				} else {
					 echo anchor( site_url(LOGIN_PAGE . '?redirect=main', $link_protocol ),'se connecter','id="login-link"');
				}?> 
		</li>	
	</ul>
</nav>