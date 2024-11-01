<?PHP
 /*
 * VirannoncesImageWidget Class
 */
class VirannoncesImageWidget extends WP_Widget {
	/** constructor */
	function VirannoncesImageWidget() {
		parent::WP_Widget(false, $name = 'Virannonces Widget Image');
	}

	/** @see WP_Widget::widget */
	function widget($args, $instance) {
		extract( $args );
		global $virannoncest_options;
		$texte="";
		$texte.=virannonces_getnext();
		//echo $texte;
//		$match=preg_match('/<\/td><td[^>]*>(.*)<\/td>/',$texte,$matches);
		$match=preg_match('/<td[^>]*>(.*)<\/td>.*<td[^>]*>.*<\/td>/msi',$texte,$matches);
		//print_r($matches);
		$texte=$matches[1];
		echo $before_widget;
		if ( $title ) {
			echo $before_title . $title . $after_title;
		}
		echo $texte;
		echo $after_widget;

	}

	/** @see WP_Widget::update */
	function update($new_instance, $old_instance) {
		return $new_instance;
	}

	/** @see WP_Widget::form */
	function form($instance) {
		$title = esc_attr($instance['title']);
		?>
			<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
		<?php
	}

} // class VirannoncesImageWidget

?>