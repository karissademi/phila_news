<?php get_header(); ?>
</div> <!-- end header.php container --> 
			<header class="fluid-container page-title">
				<div class="container">
						<?php
							if (is_post_type_archive('phila_news')) { ?>
								<h1 class="page-header"><?php post_type_archive_title(); ?> 
									<?php $term = get_term_by( 'slug', 
															  //search for this
															  get_query_var('term'), 'topics'); 
								if (isset($term->name)) {
									echo '| ' .$term->name; 
								}?>
						</h1>
						<?php } ?>						
					<div class="breadcrumbs">
						<?php if ( function_exists('yoast_breadcrumb') ) {
						yoast_breadcrumb('<p id="breadcrumbs">','</p>');
						} ?>
					</div>
				</div>
				</header>
			
	<div class="container marg-top">
			<div id="content" class="clearfix row">
				<div id="main" class="col-sm-18 clearfix archive" role="main">
				
					<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					
					<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">
					
							<?php the_post_thumbnail( 'wpbs-featured', array('class'=>'img-responsive') ); ?>

						<section class="post_content clearfix" itemprop="articleBody">
						<?php $url = get_post_meta($post->ID, 'phila_url', true );
						if (strpos($url, 'http://') !==false) : ?>
							
							<?php echo the_excerpt();?>
							<a href="<?php echo get_post_meta($post->ID, 'phila_url', true ) ?>"> 
								<button class="btn btn-primary">Visit Website</button> 
							</a>
							
							<?php else : ?>
								<?php the_content(); ?>
							<?php endif; //end the display bits?>
							<?php wp_link_pages(); ?>
					
						</section> <!-- end article section -->
						
						<footer>
			
							<?php the_tags('<p class="tags"><span class="tags-title">' . __("Tags","wpbootstrap") . ':</span> ', ' ', '</p>'); ?>
							
							<?php 
							// only show edit button if user has permission to edit posts
							if( $user_level > 0 ) { 
							?>
							<a href="<?php echo get_edit_post_link(); ?>" class="btn btn-success edit-post"><i class="icon-pencil icon-white"></i> <?php _e("Edit post","wpbootstrap"); ?></a>
							<?php } ?>
							
						</footer> <!-- end article footer -->
					
					</article> <!-- end article -->
					
					<?php //comments_template('',true); ?>
					
					<?php endwhile; ?>			
					
					<?php else : ?>
					
					<article id="post-not-found">
					    <header>
					    	<h1><?php _e("Not Found", "wpbootstrap"); ?></h1>
					    </header>
					    <section class="post_content">
					    	<p><?php _e("Sorry, but the requested resource was not found on this site.", "wpbootstrap"); ?></p>
					    </section>
					    <footer>
					    </footer>
					</article>
					
					<?php endif; ?>
			
				</div> <!-- end #main -->
				
				
				<?php dynamic_sidebar('phila_news_sidebar'); // sidebar 1 ?>
				
    
			</div> <!-- end something? -->
        </div><!-- end #content -->
<?php get_footer(); ?>