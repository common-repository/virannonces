<?php
/*
Plugin Name: Virannonces
Description: Ajoute des annonces avec affiliation viralyser dans les articles de votre blog.
Author: Sylvain Deaure
Plugin URI: http://AffiliationTotale.com/plugin-virannonces.html
Version: 1.0.3
Author URI: http://www.blog-expert.fr
*/


$eva_source = 'aHR0cDovL0FmZmlsaWF0aW9uVG90YWxlLmNvbS8/cnVicmlxdWU9';
$va_categorie = 'vitrine';


/*
 * @brief adds options to WP database
 */
function virannonces_install() {
	global $wpdb, $virannonces_options;
	if (!get_option('virannonces_options')) {
		add_option('virannonces_options', array (
			'widget' => '1', // Activate widget ?
			'title_widget' => 'Annonce', // Title of the widget
			'type_widget' => 'defaut', // template différent
			'top' => '1', // afficher annonce en haut des billets ?
			'bottom' => '0', // en bas des billets ?
			'lienaffilie' => '1', // Add affiliate link to footer
			'id_affilie' => 'Sylvain',
			'rubrique'=>'vitrine',
			'annonces' => array(),
			'nb_annonces' => 0,
			'nb_maxi' =>1,
			'last_annonce' =>0, // index de la dernière annonce affichée
			'last_check'=>0, // timestamp du dernier check en ligne des annonces
			'version' => '0' // Version of the db
		));
		$virannonces_options = get_option('virannonces_options');
	}
}

function virannonces_uninstall() {
	// delete_option('virannonces_options');
	// Do not delete, else we loose the version number
	// TODO: add an ininstall link ? Not a big deal since we added very little overhead
}

function virannonces_getnext() {
	global $virannonces_options;
	$cur=$virannonces_options['last_annonce'];
	$cur++;
	if ($cur>$virannonces_options['nb_annonces']) { $cur=1; }
	if ($cur==0) { $cur++; }
	$virannonces_options['last_annonce']=$cur;
	update_option('virannonces_options', $virannonces_options);
	$annonce= @ $virannonces_options['annonces'][$cur];
	$annonce = virannonces_spin($annonce);
	return $annonce;
}

function virannonces_init() {
	global $virannonces_options;
	global $virannonces_restant;
	$virannonces_restant=$virannonces_options['nb_maxi'];
	virannonces_check_annonces();
	//virannonces_admin_warnings();
}

function virannonces_check_annonces($force=false) {
	global $virannonces_options;
	global $va_sourceurl;
	global $va_categorie;
	$ts = $virannonces_options['last_check'];
	$now = time();
	//echo(" ts " .$ts);
	// shotcode
	// footer
	// widget
	if ($force or (($now - $ts) > 3600)) { // cache 1h
		// update
		if (!class_exists('WP_Http')) {
			include_once (ABSPATH . WPINC . '/class-http.php');
		}
		$request = new WP_Http;
		$liste_annonces='';
		$url=$va_sourceurl.$virannonces_options['rubrique'];
		//echo("url: $url ");
		$result = $request->request($url);
		if (is_array($result)) {
			$rcode = (array) $result['response'];
			$rcode=$rcode['code'];
			$liste_annonces = $result['body'];
		} else {
			$liste_annonces ='';
		}
		if (strpos($liste_annonces,'<!--viraflux-->')<=0) {
			//$liste_annonces = ' <!--viraflux--> (1)<!--viraflux--> (2)<!--viraflux--> (3)<!--viraflux--> (4)';
			$liste_annonces = '';
		}
		if ($rcode <> 200) {
			$liste_annonces = '';
			//$liste_annonces = ' <!--viraflux--> (1)<!--viraflux--> (2)<!--viraflux--> (3)<!--viraflux--> (4)';
		};
		$liste_annonces = str_replace ('rviraflux', $virannonces_options['id_affilie'], $liste_annonces);
		$annonces = explode("<!--viraflux-->", $liste_annonces);
		$nb_annonces = count ($annonces) - 1;
		$virannonces_options['annonces'] = $annonces;
		$virannonces_options['nb_annonces'] = $nb_annonces;
		$virannonces_options['last_check'] = time();
		update_option('virannonces_options', $virannonces_options);
	}
	//print_r($annonces);
}

/*
 * @brief  options
 */
function virannonces_options() {
	global $wpdb, $virannonces_options;
	global $current_user;
	get_currentuserinfo();
	if (!in_array('administrator', $current_user->roles)) {
		//die('Pas admin');
	}
	if (!empty ($_POST['force'])) {
		virannonces_check_annonces(true);
	} else {
	if (!empty ($_POST['save'])) {
		//check_admin_referer();
		$virannonces_options['top'] = trim($_POST['top']); //
		$virannonces_options['bottom'] = trim($_POST['bottom']); //
		$virannonces_options['lienaffilie'] = trim($_POST['lienaffilie']);
		$virannonces_options['nb_maxi'] = trim($_POST['nb_maxi']);
		$virannonces_options['id_affilie'] = trim($_POST['id_affilie']); //
		// si modif de la cat : forcer maj
		//virannonces_check_annonces(true);
		update_option('virannonces_options', $virannonces_options);
		virannonces_check_annonces(true);
		echo '<div id="message" class="updated fade">
			<p>' . __('R&eacute;glages sauvegard&eacute;s', 'virannonces') . '</p>
			</div>' . "\n";
	}
	}
	$virannonces_options = get_option('virannonces_options');
	include ('admin.tmpl.php');
}
$va_sourceurl = base64_decode($eva_source);


function virannonces_spin($txt) {
	$pattern = '#\{([^{}]*)\}#msi';
	$test = preg_match_all($pattern, $txt, $out);
	if (!$test) {
		return $txt;
	}
	$atrouver = array ();
	$aremplacer = array ();
	foreach ($out[0] as $id => $match) {
		$choisir = explode("|", $out[1][$id]);
		$atrouver[] = trim($match);
		$aremplacer[] = trim($choisir[rand(0, count($choisir) - 1)]);
	}
	$reponse = str_replace($atrouver, $aremplacer, $txt);
	return virannonces_spin($reponse);
}


/*
 * @brief Adds text in Footer
 */
function virannonces_footer() {
	global $virannonces_options;
	//$content = virannonces_spin($virannonces_options['cred']);
	$content='';
	if ($virannonces_options['lienaffilie'] == '1') {
		$content .= '<br><center><small> Annonces via <a href="http://affiliationtotale.com/#V_' . $virannonces_options['id_affilie'] . '" alt="affiliation" target="_blank">Affiliation Totale</a></small></center>';
	}
	echo $content;
}


function virannonces_shortcode($atts,$content=null) {
		global $virannonces_options;
		return('<br />'.virannonces_getnext().'<br />');
}

add_shortcode('virannonce', 'virannonces_shortcode');

function virannonces_content($content)
{
	global $wpdb, $virannonces_options;
	global $virannonces_restant;
	if ($virannonces_restant<=0) {
		return $content;
	}
	if ($virannonces_options['top']==1)
	{
		$content = virannonces_getnext() . $content;
		$virannonces_restant--;
	}
	if ($virannonces_options['bottom']==1)
	{
		$content = $content.virannonces_getnext();
		$virannonces_restant--;
	}
	return $content;
}

if (is_admin()) {
	include(WP_PLUGIN_DIR.'/virannonces/sdrssw.php');
	register_activation_hook(__FILE__,   'virannonces_install');
	register_deactivation_hook(__FILE__, 'virannonces_uninstall');
	add_action('admin_menu', 'virannonces_admin_menu');
}



add_action('init', 'virannonces_init');
add_filter ('the_content', 'virannonces_content');
// Affiliate link
add_action('wp_footer', 'virannonces_footer');
require_once ('wimage.class.php');
add_action('widgets_init', create_function('', 'return register_widget("VirannoncesImageWidget");'));
require_once ('wtexte.class.php');
add_action('widgets_init', create_function('', 'return register_widget("VirannoncesTexteWidget");'));


function virannonces_admin_menu() {
	if (is_admin()) {
		$menu = array (
			'Virannonces',
			'Virannonces',
			8,
			'virannonces/virannonces.php',
			'virannonces_options' ,
			WP_PLUGIN_URL . '/virannonces/virannonces_18.png'
		);
		call_user_func_array('add_menu_page', $menu);
	}
}



$virannonces_options = get_option('virannonces_options');

?>