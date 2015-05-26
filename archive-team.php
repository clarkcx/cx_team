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

			<header class="page-header container">
				<h1 class="col-xs-12 col-md-7 col-lg-push-1">We work best collaboratively</h1>
				<p class="col-xs-12 col-md-7 col-lg-push-1"><?php echo $team_description; ?></p>
			</header><!-- .page-header -->


			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class('col-md-3'); ?>>

				<?php 
				if ( has_post_thumbnail() ) { ?>
					<figure>
					<figcaption><?php the_title(); ?></figcaption>
					<a href="<?php echo get_permalink(); ?>">
					<?php echo the_post_thumbnail(); ?>
					</a>
					</figure>
				<?php }
				?>
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

		</main><!-- #main -->
	</div><!-- #primary -->
	<!-- <div class="aw-photofeed" id="instagram"></div> -->

</div><!-- .container -->

<?php get_footer(); ?>
