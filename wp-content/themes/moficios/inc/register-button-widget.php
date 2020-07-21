<?php

if(!class_exists('ButtonWidget')) {
	class ButtonWidget extends WP_Widget {
		public function __construct() {
			$widget_ops = array(
				'classname' => 'button_widget',
				'description' => 'Button Widget',
			);
			parent::__construct('button_widget', 'Button Widget', $widget_ops);
		}

		/**
		 * Outputs the content of the widget
		 *
		 * @param array $args
		 * @param array $instance
		 */
		public function widget($args, $instance) {
			if (!isset( $args['widget_id'])) {
				$args['widget_id'] = $this->id;
			}

			$widget_id = 'widget_' . $args['widget_id'];
			$btn_text = get_field('text', $widget_id );
			$btn_link = get_field('link', $widget_id);
			$btn_color = get_field('color', $widget_id);

			echo $args['before_widget'];
			echo '<a href="' . esc_url($btn_link) . '" class="btn btn-block text-white text-uppercase py-3 my-2" style="background:'. $btn_color .'">' . esc_html($btn_text) . '</a>';
			echo $args['after_widget'];
		}
	}
}

/* Register our Button Widget */
function register_button_widget() {
	register_widget('ButtonWidget');
}

add_action('widgets_init', 'register_button_widget');