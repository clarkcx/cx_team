<?php
/**
 * The template for displaying all single posts.
 *
 * @package Able WP
 */

get_header(); ?>

		<?php while ( have_posts() ) : the_post(); ?>

			<header class="entry-header container">
				<?php the_title( '<h1 class="entry-title col-xs-12 col-md-10 col-md-offset-1">', '</h1>' ); ?>
			</header><!-- .entry-header -->

			

			<div class="container">

			<div class="col-md-3 col-md-offset-1">
			<?php 
				if ( has_post_thumbnail() ) {
					echo '<figure class="hero">';
					the_post_thumbnail();
					echo '</figure>';
				}
			?>
			</div>

			<div class="col-md-3">
			<?php 
				$full_name = get_the_title();
				$pieces = explode(" ", $full_name);
				$first_name = $pieces[0];

				$custom = get_post_custom($post->ID);
				$job_title = $custom["_job_title"][0];
				$pr_email = $custom["_email"][0];
				$pr_url = $custom["_url"][0];
				$pr_twitter = $custom["_twitter"][0];
				$pr_github = $custom["_github"][0];
				
				echo '<ul>';
				if (isset($pr_url)) {
					echo '<li><a href="' . $pr_url .'" class="">Visit ' . $first_name . '&rsquo;s website</a></li>';
				}
				if (isset($pr_twitter)) {
					echo '<li><a href="http://twitter.com/' . $pr_twitter .'" class="exlink twitter">Follow ' . $first_name . ' on Twitter</a></li>';
				} 
				if (isset($pr_github)) {
					echo '<li><a href="http://github.com/' . $pr_github .'" class="exlink twitter">' . $first_name . ' on Github</a></li>';
				} 
				echo '</ul>';			 

			?>
			</div>

			<div class="skills col-md-3 col-md-offset-1">
		
				<?php 

				$taxonomy     = 'skill';

				// get the term IDs assigned to post.
				$post_terms = wp_get_object_terms( $post->ID, $taxonomy, array( 'fields' => 'ids' ) );
				$term_ids = implode( ',' , $post_terms );

				$orderby      = 'name'; 
				$show_count   = 0;      // 1 for yes, 0 for no
				$pad_counts   = 0;      // 1 for yes, 0 for no
				$hierarchical = 1;      // 1 for yes, 0 for no
				$title        = '';

				$args = array(
				  'taxonomy'     => $taxonomy,
				  'orderby'      => $orderby,
				  'show_count'   => $show_count,
				  'pad_counts'   => $pad_counts,
				  'hierarchical' => $hierarchical,
				  'title_li'     => $title,
				  'include'		 => $term_ids
				);
				?>

				<ul>
				<?php wp_list_categories( $args ); ?>
				</ul>

			</div><!-- .skills -->

					<div class="entry-content col-md-10 col-md-offset-1">
						<?php the_content(); ?>
					</div><!-- .entry-content -->
					
		<?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>
