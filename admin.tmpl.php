<div class="wrap">
<div id="icon-options-general" class="icon32"><br /></div>
<h2>Configuration de Virannonces</h2>
<iframe src="http://lienviral.fr/virannonces" width="700" height="100" frameborder="0" scrolling="no"></iframe>
<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
<div style="border:1px solid#faa; padding:10px; float:right; width:150px;">
   Ne cliquez pas sur ce lien. Mes <a href="http://www.blog-expert.fr/secrets.html" target="_blank">secrets de marketing</a> ne sont sans doute pas pour vous.
   </div>
<h3>G&eacute;n&eacute;ral</h3>
<p>Options de configuration g&eacute;n&eacute;rales</p>

<p>Indiquez votre identifiant affili&eacute; Affiliation Totale</p>
 <table class="form-table">	
		<tr>
		  <th scope="row">
			Identifiant affili&eacute; :
		  </th>
		  <td>
			<input size="30" type="text" name="id_affilie" value="<?php echo htmlspecialchars($virannonces_options['id_affilie']); ?>" />
			<br />Pas d'identifiant ? <a href="http://affiliationtotale.com/a-propos/toucher-commissions-sans-site/comment-ca-marche-1.html" target="_blank">cr&eacute;ez gratuitement votre identifiant</a> sur Affiliation Totale.
		  </td>
		  <td>

		  </td>
		</tr>
		<tr>
		  <th scope="row">
			Votre lien affil&eacute; vers l'accueil de affiliation totale est :
		  </th>
		  <td colspan="2">
			<input readonly size="70" type="text" name="lienpaypal" value="http://AffiliationTotale.com/#V_<?php echo htmlspecialchars($virannonces_options['id_affilie']); ?>" />
		 <br />
			Vous pouvez bien entendu utiliser ce lien ailleurs (attention, interdiction absolue de spammer!!!)
		  </td>
		</tr>

		<tr>
		  <th scope="row">
			Lien affili&eacute; :
		  </th>
		  <td>
			<select name="lienaffilie">
			<option value="0" <?php if ($virannonces_options['lienaffilie'] == '0'): ?>selected="selected"<?php endif; ?>>Non</option>
			<option value="1" <?php if ($virannonces_options['lienaffilie'] == '1'): ?>selected="selected"<?php endif; ?>>Oui</option>
			</select>
		  <td>
			Ceci affiche un lien avec votre code d'affiliation dans votre pied de page.
		  </td>
		</tr>
		<tr><td colspan="2">&nbsp;</td>
		</tr>
		</table>

<h3>Affichage des annonces</h3>
 <table class="form-table">	
		<tr>
		  <th scope="row">
			Afficher en haut des billets ?
		  </th>
		  <td>
			<select name="top">
			<option value="0" <?php if ($virannonces_options['top'] == '0'): ?>selected="selected"<?php endif; ?>>Non</option>
			<option value="1" <?php if ($virannonces_options['top'] == '1'): ?>selected="selected"<?php endif; ?>>Oui</option>
			</select>
		  </td>
		  <td>
			Si oui, affichera une annonce au dessus de chaque article.  </td>
		</tr>
<tr>
		  <th scope="row">
			Afficher en bas des billets ?
		  </th>
		  <td>
			<select name="bottom">
			<option value="0" <?php if ($virannonces_options['bottom'] == '0'): ?>selected="selected"<?php endif; ?>>Non</option>
			<option value="1" <?php if ($virannonces_options['bottom'] == '1'): ?>selected="selected"<?php endif; ?>>Oui</option>
			</select>
		  </td>
		  <td>
			Si oui, affichera une annonce en dessous de chaque article.  </td>
		</tr>
<tr>
		  <th scope="row">
			Nombre d'annonces maxi par page
		  </th>
		  <td>
			<input size="50" type="text" name="nb_maxi" value="<?php echo htmlspecialchars($virannonces_options['nb_maxi']); ?>">
		  </td>
		  <td>
			Pour limiter les annonces sur les pages "liste" de billets (home et archives)  </td>
		</tr>
<tr>
		  <th scope="row">
			Affichage en widget dans la sidebar
		  </th>
		  <td>
			Un widget "Virannonces", en version image et texte, est disponible dans le menu "apparence" - "Widgets". Glissez le sur votre barre lat&eacute;rale.
		  </td>
		  <td>
			&nbsp </td>
		</tr>
<tr>
		  <th scope="row">
			Affichage dans un billet
		  </th>
		  <td>
			Vous pouvez utiliser le shortcode <code>[virannonce]</code> n'importe o&ugrave; dans un billet pour afficher une annonce au milieu du texte.
		  </td>
		  <td>
			&nbsp </td>
		</tr>

</table>

<br />
			 <?php wp_nonce_field('virannonces'); ?>
			<span class="submit"><input name="save" value="<?php _e('Save Changes'); ?>" type="submit" /></span>

	</form>
   <br />&nbsp;
<br />
Annonces en cours :<br />
<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
			 <?php wp_nonce_field('virannonces'); ?>
			 <input type="hidden" name="force" value="1">
			<span class="submit"><input name="save" value="<?php _e('Forcer mise a jour'); ?>" type="submit" /></span>
<div style="height:250px; overflow:auto;">
<?php
$i=1;
while($i<=$virannonces_options['nb_annonces']) {
	echo $virannonces_options['annonces'][$i];
	$i++;
}
?>
</div>



 </div>
