<?php /*

**************************************************************************

Plugin Name:  Simple Ad Embed
Plugin URI:   
Description:  A widget that you can easily embed your ads in
Version:      1.0
Author:       Unscripted Labs
Author URI:   http://unscriptedlabs.com
License:      GPLv2 or later

**************************************************************************/

class mdg_simple_ad_widget extends WP_Widget {
	function mdg_simple_ad_widget() {
		$widget_ops = array('classname' => 'mdg_simple_ad_widget', 'description' => __('Easily embed your ad code', 'roots'));
		$this->WP_Widget('mdg_simple_ad_widget', __('Simple Ad Embed', 'roots'), $widget_ops);
		$this->alt_option_name = 'mdg_simple_ad_widget';

		add_action('save_post', array(&$this, 'flush_widget_cache'));
		add_action('deleted_post', array(&$this, 'flush_widget_cache'));
		add_action('switch_theme', array(&$this, 'flush_widget_cache'));
	}

	function widget($args, $instance) {
		$cache = wp_cache_get('mdg_simple_ad_widget', 'widget');

		if (!is_array($cache)) {
			$cache = array();
		}

		if (!isset($args['widget_id'])) {
			$args['widget_id'] = null;
		}

		if (isset($cache[$args['widget_id']])) {
			echo $cache[$args['widget_id']];
			return;
		}

		ob_start();
		extract($args, EXTR_SKIP);

		// initialize array
		$title = apply_filters('widget_title', empty($instance['title']) ? __('Feature') : $instance['title'], $instance, $this->id_base);
		if (!isset($instance['content'])) 	{ $instance['content'] 	= ''; }
		if (!isset($instance['ad_text'])) 	{ $instance['ad_text'] = ''; }

		//echo $before_widget;

		if ($title) {
			//echo $before_title;
			//echo $title;
			//echo $after_title;
		}

	// register styles	
	public function wp_enqueue_scripts() {
		wp_enqueue_style( 'mdg-ad',   plugins_url() . '/css/mdg-ad.css',   array(), $this->version );
	?>
<!-- =================	custom ad widget output start	==================== -->

<section id="" class="widget">
	<div class="mdg-ad">
	<?php
		if($instance['ad_text']){
			// ad_text was checked
			echo 'Advertisement';
		} else {
			// ad_text was not checked
		}
	?>
	<p><?php echo $instance['content']; ?></p>
	</div>
</section>

<!-- =================	custom ad widget output end ==================== -->

	<?php
		//echo $after_widget;

		$widget_id = !isset($args['widget_id']) ? '' :$args['widget_id'];
		$widget_id = ob_get_flush();
		wp_cache_set('mdg_simple_ad_widget', $cache, 'widget');
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] 			= strip_tags($new_instance['title']);
		$instance['content'] 		= $new_instance['content'];
		$instance['ad_text'] 	= strip_tags($new_instance['ad_text']);

		$this->flush_widget_cache();

		$alloptions = wp_cache_get('alloptions', 'options');
		if (isset($alloptions['mdg_simple_ad_widget'])) {
			delete_option('mdg_simple_ad_widget');
		}

		return $instance;
	}

	function flush_widget_cache() {
		wp_cache_delete('mdg_simple_ad_widget', 'widget');
	}

	function form($instance) {
		$title 			= isset($instance['title']) 		? esc_attr($instance['title']) : '';
		$content 		= isset($instance['content']) 	? esc_attr($instance['content']) : '';
		$ad_text 	= isset($instance['ad_text']) 	? esc_attr($instance['ad_text']) : '';

	?>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title:'); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>
		<p>
				<label for="<?php echo $this->get_field_id('content'); ?>"><?php _e('AdSense Code:'); ?></label>
		<textarea	class="widefat" rows="16" cols="20" id="<?php echo esc_attr($this->get_field_id('content')); ?>" name="<?php echo esc_attr($this->get_field_name('content')); ?>" ><?php echo esc_attr($content); ?></textarea>

		</p>
		<p>
			<?php
			//if( )
			?>
			<?php if($ad_text){ $checked = 'checked=true'; } else { $checked = ''; } ?>
				<label for="<?php echo $this->get_field_id('ad_text'); ?>"><?php _e('Include the "advertisement" text?'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id('ad_text')); ?>" name="<?php echo esc_attr($this->get_field_name('ad_text')); ?>" type="checkbox" value="1" <?php echo $checked; ?> />
		</p>

	<?php
	}
}

?>
