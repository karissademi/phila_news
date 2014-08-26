<?php get_header(); ?>

			
			<div id="content" class="clearfix row">
			<div class="breadcrumbs">
						<?php if(function_exists('bcn_display')){bcn_display();}?>
					</div>
				<div id="main" class="col-sm-18 clearfix archive" role="main">
					<div class="page-header">
						<?php
							if (is_post_type_archive('phila_news')) { ?>
								<h1><?php post_type_archive_title(); ?> 
									<?php $term = get_term_by( 'slug', 
															  //search for this
															  get_query_var('term'), 'topics'); 
								if (isset($term->name)) {
									echo '| ' .$term->name; 
								}?>
						</h1>
						<?php } ?>						
					</div>
					
					<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	
					<?php 
						//look for URL and output this instead of the full news story
						$url = get_post_meta($post->ID, 'phila_url', true );
						if (strpos($url, 'http://') !==false) : ?>
						<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix row'); ?> role="article">
							<div class="col-sm-24">
								<h3><a href="<?php echo get_post_meta($post->ID, 'phila_url', true ) ?>"><?php echo the_title() ?> (link)							<span class="glyphicon glyphicon-link"></span></a></h3>						
									<p class="meta">
										<span class="glyphicon glyphicon-calendar"></span>
										<time datetime="<?php echo get_the_date(); ?>" pubdate><?php echo get_the_date(); ?></time> | 
										<?php the_category(', '); ?> 
										<?php $terms = wp_get_post_terms( $post->ID, array( 'topics' ) ); ?>
											<?php foreach ( $terms as $term ) : ?>
											<?php echo ' | ' . $term->name; ?> 
											<?php endforeach; ?>
									</p>

								<?php if ($post->post_excerpt) {
									?><section class="post_excerpt">
									<p><?php the_excerpt_max_charlength(160);?></p>
										<a href="<?php echo get_post_meta($post->ID, 'phila_url', true ) ?>"> 
											<button class="btn btn-primary">Visit Website</button> 
										</a>
									</section>
								<?php } ?>
						</div>
						</article>
					<hr>
					
					<?php else : ?>

					<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix row'); ?> role="article">
						<?php if ('' != get_the_post_thumbnail()){ ?>
							<div class="col-md-10">
								<?php the_post_thumbnail( 'wpbs-featured' ); ?>
							</div>
							<div class="col-md-12">
						<?php } else {?>
							<div class="col-md-24">
						<?php } ?>
										
						<header>
							<h3><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
							<p class="meta">
								<span class="glyphicon glyphicon-calendar"></span>
								<time datetime="<?php echo get_the_date(); ?>" pubdate><?php echo get_the_date(); ?></time> | <?php the_category(', '); ?> 				
									<?php $terms = wp_get_post_terms( $post->ID, array( 'topics' ) ); ?>
									<?php foreach ( $terms as $term ) : ?>
									<?php echo ' | ' . $term->name; ?> 
									<?php endforeach; ?>
						<?php /*
							<p><?php echo 'start:' . date("m.d.y", get_post_meta($post->ID, 'news-start-date', true )); ?></p>
							<p><?php echo 'end:' . date("m.d.y", get_post_meta($post->ID, 'news-end-date', true )); ?></p>
							<p><?php echo 'no expire:' .  get_post_meta($post->ID, 'news-no-expire', true ); ?></p>
						
							*/?>
								
						</header> <!-- end article header -->
					
						<?php //if ($post->post_excerpt) {
							?><section class="post_excerpt">
								<p><?php the_excerpt_max_charlength(160);?></p>
								<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"> 
									<button class="btn btn-primary">Read More</button> 
								</a>
							</section>
						<?php// } ?>
							

	
						</div><!-- end col  -->
						<footer>
							
						</footer> <!-- end article footer -->
					
					</article> <!-- end article -->
						<hr>
					<?php endif; //end the display bits?>
					
					<?php endwhile; //ends the loop?>
					
					<?php if (function_exists('page_navi')) { // if expirimental feature is active ?>
						
						<?php page_navi(); // use the page navi function ?>

					<?php } else { // if it is disabled, display regular wp prev & next links ?>
						<nav class="wp-prev-next">
							<ul class="pager">
								<li class="previous"><?php next_posts_link(_e('&laquo; Older Entries', "wpbootstrap")) ?></li>
								<li class="next"><?php previous_posts_link(_e('Newer Entries &raquo;', "wpbootstrap")) ?></li>
							</ul>
						</nav>
					<?php } ?>
								
					
					<?php else : ?>
					
					<article id="post-not-found">
					    <header>
					    	<h1><?php _e("No Posts Yet", "wpbootstrap"); ?></h1>
					    </header>
					    <section class="post_content">
					    	<p><?php _e("Sorry, What you were looking for is not here.", "wpbootstrap"); ?></p>
					    </section>
					    <footer>
					    </footer>
					</article>
					
					<?php endif; ?>
			
				</div> <!-- end #main -->
    		
				<?php dynamic_sidebar('phila_news_sidebar'); ?>
				
				
    
			</div> <!-- end something? -->
        </div><!-- end #content -->

<?php get_footer(); ?>