<?php
/*
	* Plugin Name: Custom News Post
	* Plugin URI:  phila.gov
	* Description: Creation of News custom post type for the City of Philadelphia News.
	* Author: Karissa Demi
	* Author URL: http://karissademi.com
	* Version:     .1

*/
//where all mah stuff is
	define( 'ROOT', plugins_url( '', __FILE__ ) );
	define( 'IMAGES', ROOT . '/img/' );
	define( 'STYLES', ROOT . '/css/' );
	define( 'SCRIPTS', ROOT . '/js/' );

add_action( 'init', 'phila_create_post_type' );
/**
 * Register news post type.
 *
 */

function phila_create_post_type() {
    $labels = array(
        'name'				=> 	__( 'News' ),
        'add_new_item' 		=>	__( 'Add News Item' ),
		'edit_item' 		=>	__( 'Edit News Item' ),
		'new_item' 			=>	__( 'New Post' ),
		'view_item' 		=>	__( 'View News Item' ),
		'search_items' 		=>	__('Search News Items'),
		'not_found' 		=>	__('No News Items Found'),
		'not_found_in_trash' => __('No News Found In Trash')  
      );
	
	$supports =  array('title','editor','revisions','thumbnail', 'excerpt');
	
	$args = array(
		'labels' => $labels,
		'description' => 'News Items for the City of Philadelphia',
		'rewrite' => array('slug' => 'news'),
		'public' => true,
		'has_archive' => true,
		'menu_position' => 20,
		'menu_icon' => 'dashicons-rss',
		//'capability_type' => 'news', not sure about these
		//'capabilities' => '',
		'supports' => $supports,
		'taxonomies' => array('category','post_tag'), //TO DO - add audience once I know it's good
		'has_archive'=> 'true'
			//TO DO see if we need to change slug type
  );	
	register_post_type( 'phila_news', $args );	
}

//add the custom fields picker 
function phila_add_news_info_metabox(){
	add_meta_box(
		'phila-news-info-metabox',
		__('News Info', 'phila'),
		'phila_render_news_info_metabox',//callback
		'phila_news',//post_type
		'side',
		'core'
	);
}

add_action('add_meta_boxes','phila_add_news_info_metabox');

function phila_render_news_info_metabox( $post ) {
 
    // generate a nonce field
    wp_nonce_field( basename( __FILE__ ), 'phila-news-info-nonce' );
 
    // get previously saved meta values (if any)
    $news_start_date = get_post_meta( $post->ID, 'news-start-date', true );
    $news_end_date = get_post_meta( $post->ID, 'news-end-date', true );
 
    // if there is previously saved value then retrieve it, else set it to the current time
    $news_start_date = ! empty( $news_start_date ) ? $news_start_date : time();
 
    //we assume that if the end date is not present, news ends on the same day
    $news_end_date = ! empty( $news_end_date ) ? $news_end_date : $news_start_date;
 
    ?>
	<label for="phila-news-start-date"><?php _e( 'News Item Start Date:', 'uep' ); ?></label>
			<input class="widefat phila-news-date-input" id="phila-news-start-date" type="text" name="phila-news-start-date" placeholder="Format: February 18, 2014" value="<?php echo date( 'F d, Y', $news_start_date ); ?>" />

	<label for="phila-news-end-date"><?php _e( 'News Item End Date:', 'uep' ); ?></label>
			<input class="widefat phila-news-date-input" id="phila-news-end-date" type="text" name="phila-news-end-date" placeholder="Format: February 18, 2014" value="<?php echo date( 'F d, Y', $news_end_date ); ?>" />
  
<?php }//end phila_render_news_info_metabox

function phila_admin_script_style($hook){
	global $post_type; //super important global var!!
	if ('post.php' == $hook || 'post-new.php' == $hook && ('phila_news' == $post_type)){
		wp_enqueue_script(
			'news',
			SCRIPTS . 'script.js',
			array('jquery', 'jquery-ui-datepicker')
		);
		wp_enqueue_style(
			'jquery-ui-calendar',
			STYLES . 'jquery-ui-datepicker.css'
		);
	}
}
add_action('admin_enqueue_scripts','phila_admin_script_style');

function phila_save_news_info( $post_id ) {
	if (isset($_POST['post_type'])) {
		'phila_news' != $_POST['post_type'];
		return;
	}
	//check for save status
	$is_autosave = wp_is_post_autosave($post_id);
	$is_revision = wp_is_post_revision($post_id);
	$is_valid_nonce = (isset( $_POST['phila-news-info-nonce'] ) && ( wp_verify_nonce( $_POST['phila-news-info-nonce'], basename( __FILE__ ) ) ) ) ? true : false;
	
	// exit depending on the save status or if the nonce is not valid
	 if ( $is_autosave || $is_revision || ! $is_valid_nonce ) {
        return;
    }
	
	// checking for the values and performing necessary actions
    if ( isset( $_POST['phila-news-start-date'] ) ) {
		//this is what does the magic
		//args are $post_id, $meta_key, $meta_value, $prev_value
		//strtotime converts to UNIX timestamp
        update_post_meta( $post_id, 'news-start-date', strtotime( $_POST['phila-news-start-date'] ) );
    }
 
    if ( isset( $_POST['phila-news-end-date'] ) ) {
        update_post_meta( $post_id, 'news-end-date', strtotime( $_POST['phila-news-end-date'] ) );
    }
 
}
add_action( 'save_post', 'phila_save_news_info' );

//now make wordpress do our bidding
function phila_custom_columns_head($defaults){
	unset($defaults['date']);
	
	$defaults['news_start_date']= __('Start Date');
	$defaults['news_end_date']=__('End Date');
	
	return $defaults;
}
		  //manage_edit-$post_columns
add_filter('manage_edit-phila_news_columns', 'phila_custom_columns_head', 10);//why do we put 10 here?


function phila_custom_columns_content($column_name, $post_id){
	if('news_start_date' == $column_name){
		$start_date = get_post_meta($post_id, 'news-start-date', true);
		echo date('F d, Y', $start_date);
	}
	if('news_end_date' == $column_name ){
        $end_date = get_post_meta( $post_id, 'news-end-date', true );
        echo date( 'F d, Y', $end_date );
    }

}

			//manage_$post_posts_custom_column
add_action( 'manage_phila_news_posts_custom_column', 'phila_custom_columns_content', 10, 2 );//still not sure about 10, but the 2 is number of args being passed to this function