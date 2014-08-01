<?php
class Phila_News_List extends WP_Widget { 
	//aw yeah method
	public function __construct(){
		$widget_ops = array(
			'class'			=>	'phila_news_list',
			'description'	=>	__('A widget to display a list of news items')
		);
		//passes this info to main constructor guy
		parent::__construct(
			'phila_news_list', //base ID
			__('News List'),	//title
			$widget_ops
		);
	}
	public function form($instance){
		//probably always define defaults
		$widgets_defaults = array(
			'title'			=> 'Recent News',
			'number_items'	=> 5
		);
					//sweet utility function 
								  //typecast $instance as array
		$instance = wp_parse_args((array)$instance, $widget_defaults);
	}
	public function update($new_instance, $old_instance){
	
	}
	public function widget($args, $instance){
	
	}
	
}

function phila_register_widget(){
	register_widget('Phila_News_List');
}
add_action('widgets_init','phila_register_widget');