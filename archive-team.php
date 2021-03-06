<?php
/**
 * The template for displaying archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Able WP
 */

get_header(); ?>

<div class="container">

	<div id="primary" class="content-area col-md-8">
		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<?php 
				$cpt_team = get_post_type_object( 'profiles' );
				$team_description = $cpt_team->description;
			?>

			<div class="row">
			<header class="page-header">
				<h1 class="col-xs-12 col-md-10 col-md-push-1 no-pad-l no-pad-r">We work best collaboratively</h1>
				<p class="col-xs-12 col-md-10 col-md-push-1 lead no-pad-l no-pad-r"><?php echo $team_description; ?></p>
			</header><!-- .page-header -->

		</div><!-- .row -->

			<div class="row no-pad-l no-pad-r">
			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>
			<?php 
				$custom = get_post_custom($post->ID);
				$job_title = $custom["_job_title"][0];

			?>

			<article id="post-<?php the_ID(); ?>" <?php post_class('col-md-3'); ?>>

				<?php 
				if ( has_post_thumbnail() ) { ?>
					<figure class="profile-photo">
					<a href="<?php echo get_permalink(); ?>">
					<?php echo the_post_thumbnail(); ?>
					</a>
					</figure>
				<?php }
				?>
				<header>
					<h1 class="h2"><?php the_title(); ?></h1>
					<h2 class="h3"><?php echo $job_title; ?></h2>
				</header>
				<div class="short-bio"><?php echo the_excerpt(); ?>
				<a class="btn wide" href="<?php echo get_permalink(); ?>">
					Contact <?php 
						$full_name = get_the_title();
						$pieces = explode(" ", $full_name);
						$first_name = $pieces[0];
						echo $first_name; 
					?>
				</a>
				</div><!-- .short-bio -->

			</article><!-- #post-## -->

			<?php endwhile; ?>

			<?php the_posts_navigation(); ?>

		<?php else : ?>

			<?php get_template_part( 'content', 'none' ); ?>

		<?php endif; ?>

		</div><!-- .row -->

		</main><!-- #main -->
	</div><!-- #primary -->
	<!-- <div class="aw-photofeed" id="instagram"></div> -->

</div><!-- .container -->

<?php get_footer(); ?>
