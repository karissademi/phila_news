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
		'rewrite' => array('slug' => 'news', 'with_front'=>true),
		'public' => true,
		'menu_position' => 20,
		'menu_icon' => 'dashicons-media-document',
		'supports' => $supports,
		'taxonomies' => array('category','post_tag'), //TO DO - add audience once I know it's good
		'has_archive'=> 'news'
  );	
	register_post_type( 'phila_news', $args );	
}

//add the custom fields picker 
function phila_add_news_info_metabox(){
	add_meta_box(
		'phila-news-info-metabox',
		__('News Item Start & End Dates', 'phila'),
		'phila_render_news_info_metabox',//callback
		'phila_news',//post_type
		'side',
		'high'
	);
}

add_action('add_meta_boxes','phila_add_news_info_metabox');

function phila_render_news_info_metabox( $post ) {
 
    // generate a nonce field
    wp_nonce_field( basename( __FILE__ ), 'phila-news-info-nonce' );
 
    // get previously saved meta values (if any)
    $news_start_date = get_post_meta( $post->ID, 'news-start-date', true );
    $news_end_date = get_post_meta( $post->ID, 'news-end-date', true );
	$news_no_expire = get_post_meta($post->ID, 'news-no-expire', true);
	
 
    // if there is previously saved value then retrieve it, else set it to the current time
    $news_start_date = ! empty( $news_start_date ) ? $news_start_date : time();
 
    //we assume that if the end date is not present, news ends on the same day
    $news_end_date = ! empty( $news_end_date ) ? $news_end_date : $news_start_date;
 
    ?>
<p>
	<label for="phila-news-start-date"><?php _e( 'News Item Start Date:', 'uep' ); ?></label>
			<input class="widefat phila-news-date-input" id="phila-news-start-date" type="text" name="phila-news-start-date" placeholder="Format: February 18, 2014" value="<?php echo date( 'F d, Y', $news_start_date ); ?>" />
</p>
<p>
	<label for="phila-news-end-date"><?php _e( 'News Item End Date:', 'uep' ); ?></label>
			<input class="widefat phila-news-date-input" id="phila-news-end-date" type="text" name="phila-news-end-date" placeholder="Format: February 18, 2014" value="<?php echo date( 'F d, Y', $news_end_date ); ?>" />
	</p>
<p>
	<input type="checkbox" name="phila-news-no-expire" <?php if( $news_no_expire == true ) { ?>checked="checked"<?php } ?> id="phila-news-no-expire"> <label for="phila-news-no-expire"><?php _e( 'story does not expire (stays at top of list).', 'uep' ); ?></label>
	</p>
  
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

function phila_widget_style() {
    if ( is_active_widget( '', '', 'phila_news_list', true ) ) {
        wp_enqueue_style(
            'news_list',
            STYLES . 'style.css',
            false,
            '1.0',
            'all'
        );
    }
}
add_action( 'wp_enqueue_scripts', 'phila_widget_style' );

add_action('admin_enqueue_scripts','phila_admin_script_style');

function phila_save_news_info( $post_id ) {
	if (isset($_POST['post_type'])) { 
		if('phila_news' != $_POST['post_type']){
		return;
	}
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
	 if ( isset( $_POST['phila-news-no-expire'] ) ) {
        update_post_meta( $post_id, 'news-no-expire', $_POST['phila-news-end-date']);
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
        echo date('F d, Y', $end_date);
    }

}

			//manage_$post_posts_custom_column
add_action( 'manage_phila_news_posts_custom_column', 'phila_custom_columns_content', 10, 2 );//still not sure about 10, but the 2 is number of args being passed to this function

/* Filter the single_template with our custom function*/
function get_custom_post_type_template($single_template) {
     global $post;

     if ($post->post_type == 'phila_news') {
          $single_template = dirname( __FILE__ ) . '/single-phila_news.php';
     }
     return $single_template;
}
add_filter( 'single_template', 'get_custom_post_type_template' );

//filter the archive page
function get_custom_post_type_archive_template($archive_template) {
     global $post;

     if ($post->post_type == 'phila_news') {
          $archive_template = dirname( __FILE__ ) . '/archive-phila_news.php';
     }
     return $archive_template;
}
add_filter( 'archive_template', 'get_custom_post_type_archive_template' );




include('inc/widget-news-list.php');






//OTHER WAY TO ADD CUSTOM META BOXES DWBI

add_filter( 'rwmb_meta_boxes', 'phila_register_meta_boxes' );
function phila_register_meta_boxes( $meta_boxes ){
    $prefix = 'phila_';

    // 2nd meta box
    $meta_boxes[] = array(
		'id'       => $prefix . 'news-link',
        'title'    => 'News Link URL',
        'pages'    => array( 'phila_news' ),
        'fields' => array(
            array(
				'desc'  => 'Paste the external URL of the News Item here.',
                'id'   => $prefix . 'url',
                'type' => 'text',
				'std'   => 'http://',
            ),
        )
    );
	
    return $meta_boxes;
	
}

