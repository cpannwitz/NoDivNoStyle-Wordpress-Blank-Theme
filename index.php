<?php get_header(); ?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
		
			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'content', get_post_format() ); ?>
			<?php endwhile; ?>

			<?php TEMPLATENAME_paging_nav(); ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_footer(); ?>