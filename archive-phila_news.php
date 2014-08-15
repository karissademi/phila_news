<?php get_header(); ?>
			
			<div id="content" class="clearfix row">
			
				<div id="main" class="col-sm-16 clearfix archive" role="main">
					<div class="page-header">
						<?php
							if (is_post_type_archive('phila_news')) { ?>
								<h1><?php post_type_archive_title(); ?></h1>
						<?php } ?>						
					</div>
					
					<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					
					<?php 
						//look for URL and output this instead of the full news story
						$url = get_post_meta($post->ID, 'phila_url', true );
						if (strpos($url, 'http://') !==false) : ?>
						<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article">
						<h3 class="h2"><a href="<?php echo get_post_meta($post->ID, 'phila_url', true ) ?>"><?php echo the_title() ?> (link)							</a></h3>						
							<p class="meta">
								<?php the_category(', '); ?> - 
								<time datetime="<?php echo get_the_date(); ?>" pubdate><?php echo get_the_date(); ?></time>
							</p>
						
						<?php if ($post->post_excerpt) {
							?><section class="post_excerpt"><?php the_excerpt(); ?></section>
						<?php } ?>
						
						</article>
					
					<?php else : ?>

					<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article">
						<?php the_post_thumbnail( 'wpbs-featured' ); ?>
						<header>
							<h3 class="h2"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
							<p class="meta"><?php the_category(', '); ?> - <time datetime="<?php echo get_the_date(); ?>" pubdate><?php echo get_the_date(); ?></time>  </p>
						<?php /*
							<p><?php echo 'start:' . date("m.d.y", get_post_meta($post->ID, 'news-start-date', true )); ?></p>
							<p><?php echo 'end:' . date("m.d.y", get_post_meta($post->ID, 'news-end-date', true )); ?></p>
							<p><?php echo 'no expire:' .  get_post_meta($post->ID, 'news-no-expire', true ); ?></p>
						
							*/?>
								
						</header> <!-- end article header -->
					
						<section class="post_excerpt">

							<?php the_excerpt(); ?>
					
						</section> <!-- end article section -->
						
						<footer>
							
						</footer> <!-- end article footer -->
					
					</article> <!-- end article -->
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
    			
				
				<?php 
/*
					function seoUrl($string) {
						//Lower case everything
						$string = strtolower($string);
						//Convert whitespaces and underscore to dash
						$string = preg_replace("/[\s_]/", "-", $string);
						return $string;
					}

					$args = array( 'hide_empty=0' );

					$terms = get_terms('topics', $args);
					if ( !empty( $terms ) && !is_wp_error( $terms ) ) {
						$count = count($terms);
						$i=0;
						$term_list = '<p class="my_term-archive">';
						foreach ($terms as $term) {
							$i++;
							$term_list .= '<a href="?topics=' . seoUrl($term->name) . '" title="' . sprintf(__('View all post filed under %s', 'phila'), $term->name) . '">' . $term->name . '</a>';
							if ($count != $i) {
								$term_list .= ' &middot; ';
							}
							else {
								$term_list .= '</p>';
							}
						}
						echo $term_list;
					}
				 */
				?>
				<?php get_sidebar(); ?>
				
				
    
			</div> <!-- end something? -->
        </div><!-- end #content -->

<?php get_footer(); ?>