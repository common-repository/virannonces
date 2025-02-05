<?php

/**
 * Adds the RSS feed into the WordPress dashboard
 */

if (!function_exists('sd_text_limit2')) {
	function sd_text_limit2( $text, $limit, $finish = ' [&hellip;]') {
		if( strlen( $text ) > $limit ) {
			$text = substr( $text, 0, $limit );
			$text = substr( $text, 0, - ( strlen( strrchr( $text,' ') ) ) );
			$text .= $finish;
		}
		return $text;
	}
}
if (!function_exists('sd_rss_widget')) {
	function sd_rss_widget($args) {
		require_once(ABSPATH.WPINC.'/rss.php');
		if (is_array($args)) { 	extract( $args, EXTR_SKIP ); }
		if ($num==0) { $num=3; }
		// Use feedburner
		if ( $rss = fetch_rss( 'http://www.blog-expert.fr/feed/' ) ) {
			echo '<div class="rss-widget">';

			echo '<ul>';
			$rss->items = array_slice( $rss->items, 0, $num );

			foreach ( (array) $rss->items as $item ) {
				echo '<li>';
				echo '<a class="rsswidget" href="'.clean_url( $item['link'], $protocolls=null, 'display' ).'">'. htmlentities(utf8_decode($item['title'])) .'</a> ';
				if ($showdate)
					echo '<span class="rss-date">'. date('F j, Y', strtotime($item['pubdate'])) .'</span>';
				echo '<div class="rssSummary">'. sd_text_limit2($item['summary'],200) .'</div>';
				echo '</li>';
			}
			echo '</ul>';
			echo '<div style="border-top: 1px solid #ddd; padding-top: 10px; text-align:center;">';
			echo '<a href="http://www.blog-expert.fr/feed/"><img src="'.get_bloginfo('wpurl').'/wp-includes/images/rss.png" alt=""/>&nbsp;'.__('S\'abonner au flux RSS','wp-longtail').'</a>';
			if ($image == 'normal') {
				echo ' &nbsp; &nbsp; &nbsp; ';
			} else {
				echo '<br/>';
			}
			echo '</div>';
			echo '</div>';
		}
	}

	function sd_widget_setup() {
		wp_add_dashboard_widget( 'sd_rss_widget' , __('En direct de Blog Expert...','virannonces') , 'sd_rss_widget');
	}

	add_action('wp_dashboard_setup', 'sd_widget_setup');
}