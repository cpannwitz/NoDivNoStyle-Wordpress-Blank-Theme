<?php ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.ico" />

  	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>	
	<div id="page" class="container">
		<header id="pagehead" class="site-header" role="banner">
			<div class="meta-info">
				<h1 class="site-title">
					<a class="home-link" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
						<?php bloginfo( 'name' ); ?>
					</a>
				</h1>
				<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
			</div>

			<div id="main-navbar" class="navbar">
				<div class="header-navigation" role="navigation">
					<?php wp_nav_menu( array(
						 'theme_location' => 'header_location',					 
					) ); ?>					
				</div><!-- .header-navigation -->
			</div><!-- #main-navbar -->
		</header><!-- #pagehead -->

		<div class="content-section">
			<div class="breadcrumbs">
				<?php the_breadcrumb(); ?>
			</div><!-- .breadcrumbs -->