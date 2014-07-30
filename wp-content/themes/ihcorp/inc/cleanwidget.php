<?php // Clean Markup Widget @ http://perishablepress.com/clean-markup-widget/
add_action('widgets_init', create_function('', 'register_widget("clean_markup_widget");'));
class Clean_Markup_Widget extends WP_Widget {
	function __construct() {
		parent::WP_Widget('clean_markup_widget', 'Clean markup widget', array('description'=>'Simple widget for well-formatted markup &amp; text'));
	}
	function widget($args, $instance) {
		extract($args);
		$markup = $instance['markup'];
		//echo $before_widget;
		if ($markup) echo $markup;
		//echo $after_widget;
	}
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['markup'] = $new_instance['markup'];
		return $instance;
	}
	function form($instance) {
		if ($instance) $markup = esc_attr($instance['markup']);
		else $markup = __('&lt;p&gt;Clean, well-formatted markup.&lt;/p&gt;', 'markup_widget'); ?>
		<p>
			<label for="<?php echo $this->get_field_id('markup'); ?>"><?php _e('Markup/text'); ?></label><br />
			<textarea class="widefat" id="<?php echo $this->get_field_id('markup'); ?>" name="<?php echo $this->get_field_name('markup'); ?>" type="text" rows="16" cols="20" value="<?php echo $markup; ?>"><?php echo $markup; ?></textarea>
		</p>
<?php }
} ?>