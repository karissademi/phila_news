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
			__('Phila.gov News List'),	//title
			$widget_ops
		);
	}
	public function form($instance){
		//probably always define defaults
		$widget_defaults = array(
			'title'			=> 'Recent News',
			'number_items'	=> 5
		);
					//sweet utility function 
								  //typecast $instance as array
		$instance = wp_parse_args((array)$instance, $widget_defaults);
		
		?>
		<div>
							<?php //this method belongs to the WP_Widget class // _e for translation  ?>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'uep' ); ?></label>
    <input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" class="widefat" value="<?php echo esc_attr( $instance['title'] ); ?>">
		</div>
		<div>
			<label for="<?php echo $this->get_field_id( 'number_items' ); ?>"><?php _e( 'Number of news items to show:' ); ?></label>
			<select id="<?php echo $this->get_field_id( 'number_items' ); ?>" name="<?php echo $this->get_field_name( 'number_items' ); ?>" class="widefat">
				<?php for ( $i = 1; $i <= 10; $i++ ): ?>
															<?php //compares $i amd $instance[number_items] ?>
				<option value="<?php echo $i; ?>" <?php selected( $i, $instance['number_items'], true ); ?>><?php echo $i; ?></option>

				<?php endfor; ?>
			</select>
		</div>
		<?php
		
	}
	public function update($new_instance, $old_instance){
		$instance = $old_instance;
		
		$instance['title'] = $new_instance['title'];
		$instance['number_items'] = $new_instance['number_items'];
		
		return $instance;
	
	}
	public function widget($args, $instance){
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);
		
		$meta_query_args = array(
			'relation'		=> 'AND',
			array(
				'key'		=> 'news-end-date',
				'value'		=> time(),//current time stamp
				'compare'	=> '>='//greater than or equal to
			)
		);
		$query_args = array(
			'post_type'			=> 'phila_news',
			'posts_per_page'	=> $instance['number_items'],
			'post_status'		=> 'publish',
			'ignore_sticky_posts'	=> 'true',
			'meta_key'			=> 'news-end-date',
			'orderby'			=> 'meta_value_num', //need _num to compare values instad of strings
			'order'				=> 'DESC'
		);
		
		$news_list = new WP_Query($query_args);
		
		echo $before_widget;
		if('title'){
			echo $before_title . $title . $after_title;		
		}
		
		if ($news_list->have_posts()){
			?>
			<ul class="phila_news_entries">
				<?php	
					while($news_list->have_posts() ) : $news_list ->the_post();
					$news_start_date = get_post_meta( get_the_ID(), 'news-start-date', true);
					$news_end_date = get_post_meta(get_the_ID(), 'news-end-date', true);
			?>
			<li class="phila_news_entry">
				<time class="phila_news_date"><?php echo date( 'F d, Y', $news_start_date ); ?> 
					<?php //echo date( 'F d, Y', $news_end_date ); ?>
				</time>
				<h4><a href="<?php the_permalink(); ?>" class="phila_news_title"><?php the_title(); ?></a></h4>
				<?php the_excerpt(); ?>
			
			</li>
			<?php endwhile; ?>
		</ul>
		<a href="<?php echo get_post_type_archive_link('phila_news');?>">View all news</a>

		<?php
		wp_reset_query();
		echo $after_widget;


		}else{
			?><p>There is no recent news!</p><?php
		}
	}
}

function phila_register_widget(){
	register_widget('Phila_News_List');
}
add_action('widgets_init','phila_register_widget');