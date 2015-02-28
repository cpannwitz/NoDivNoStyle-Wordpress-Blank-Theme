<?php
/*
1. Basic theme features
2. Include scripts and styles
3. Register Sidebars
4. Paging functions
5. Extended theme functions
*/


///////////////////////////////////////
// 1. Basic Theme Features
///////////////////////////////////////
function TEMPLATENAME_theme_setup() {
	register_nav_menus(array(
		'header_location' => __('Header Navigation Menu'),
	));
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 250, 250, true ); // sets the default size for the thumbnails
	add_theme_support( 'automatic-feed-links' );
	add_filter('widget_text', 'do_shortcode'); // Use Shortcodes in Text Widgets
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );
	add_theme_support( 'post-formats', array(
		'image', 'video', 'link', 'gallery' , 'status', 'audio', 'chat', 'aside', 'quote'
	) );
}
add_action('init', 'TEMPLATENAME_theme_setup');

///////////////////////////////////////
// 2. Including scripts and styles
///////////////////////////////////////
function TEMPLATENAME_scripts_styles() {
	// Activate Threaded Comments
	//if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
	//	wp_enqueue_script( 'comment-reply' );

	// activate jQuery implementation
	//wp_enqueue_script( 'jQuery');

	// Loads JavaScript file
	wp_enqueue_script( 'template-script', get_template_directory_uri() . '/js/functions.js', array(), '1.0.0' );

	// Loads the info stylesheet.
	wp_enqueue_style( 'template-info', get_stylesheet_uri(), array(), '1.0.0' );

	// Loads our main stylesheets.
	wp_enqueue_style( 'style-css', get_template_directory_uri() . '/css/style.css', array(), '1.0.0' );
}
add_action( 'wp_enqueue_scripts', 'TEMPLATENAME_scripts_styles' );

///////////////////////////////////////
// 3. Register Siderbars for Widgets
///////////////////////////////////////
function TEMPLATENAME_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Main Widget Area', 'TEMPLATENAME' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Appears in the sidebar section of the site.', 'TEMPLATENAME' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'TEMPLATENAME_widgets_init' );

///////////////////////////////////////
// 4. Paging functions
///////////////////////////////////////

if ( ! function_exists( 'TEMPLATENAME_comment_nav' ) ) :
// Comment Navigation
function TEMPLATENAME_comment_nav() {
	// Are there comments to navigate through?
	if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
	?>
	<nav class="navigation comment-navigation" role="navigation">
		<h2 class="screen-reader-text"><?php _e( 'Comment navigation', 'TEMPLATENAME' ); ?></h2>
		<div class="nav-links">
			<?php
				if ( $prev_link = get_previous_comments_link( __( 'Older Comments', 'TEMPLATENAME' ) ) ) :
					printf( '<div class="nav-previous">%s</div>', $prev_link );
				endif;
				if ( $next_link = get_next_comments_link( __( 'Newer Comments', 'TEMPLATENAME' ) ) ) :
					printf( '<div class="nav-next">%s</div>', $next_link );
				endif;	?>
		</div><!-- .nav-links -->
	</nav><!-- .comment-navigation -->
	<?php
	endif;
}
endif;

// Default Post Navigation with Older / Newer Links
/*
if ( ! function_exists( 'TEMPLATENAME_paging_nav' ) ) :
// Paging Navigation
function OUTDATET_TEMPLATENAME_paging_nav() {
	global $wp_query;

	// Don't print empty markup if there's only one page.
	if ( $wp_query->max_num_pages < 2 )
		return;
	?>
	<nav class="navigation paging-navigation" role="navigation">
		<!-- <h1 class="screen-reader-text"><?php _e( 'Posts navigation', 'TEMPLATENAME' ); ?></h1> -->
		<div class="nav-links">

			<?php if ( get_next_posts_link() ) : ?>
			<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'TEMPLATENAME' ) ); ?></div>
			<?php endif; ?>

			<?php if ( get_previous_posts_link() ) : ?>
			<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'TEMPLATENAME' ) ); ?></div>
			<?php endif; ?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;
*/


// Advanced Prev / 1 / 2 / 3 Next Post-Page Navigation
function TEMPLATENAME_paging_nav() {

	if( is_singular() )
		return;

	global $wp_query;

	/** Stop execution if there's only 1 page */
	if( $wp_query->max_num_pages <= 1 )
		return;

	$paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
	$max   = intval( $wp_query->max_num_pages );

	/**	Add current page to the array */
	if ( $paged >= 1 )
		$links[] = $paged;

	/**	Add the pages around the current page to the array */
	if ( $paged >= 3 ) {
		$links[] = $paged - 1;
		$links[] = $paged - 2;
	}

	if ( ( $paged + 2 ) <= $max ) {
		$links[] = $paged + 2;
		$links[] = $paged + 1;
	}

	echo '<div class="page-navigation"><ul>' . "\n";

	/**	Previous Post Link */
	if ( get_previous_posts_link() )
		printf( '<li>%s</li>' . "\n", get_previous_posts_link() );

	/**	Link to first page, plus ellipses if necessary */
	if ( ! in_array( 1, $links ) ) {
		$class = 1 == $paged ? ' class="active"' : '';

		printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( 1 ) ), '1' );

		if ( ! in_array( 2, $links ) )
			echo '<li>…</li>';
	}

	/**	Link to current page, plus 2 pages in either direction if necessary */
	sort( $links );
	foreach ( (array) $links as $link ) {
		$class = $paged == $link ? ' class="active"' : '';
		printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $link ) ), $link );
	}

	/**	Link to last page, plus ellipses if necessary */
	if ( ! in_array( $max, $links ) ) {
		if ( ! in_array( $max - 1, $links ) )
			echo '<li>…</li>' . "\n";

		$class = $paged == $max ? ' class="active"' : '';
		printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $max ) ), $max );
	}

	/**	Next Post Link */
	if ( get_next_posts_link() )
		printf( '<li>%s</li>' . "\n", get_next_posts_link() );

	echo '</ul></div>' . "\n";

}

///////////////////////////////////////
// 5. Extended theme functions
///////////////////////////////////////
if ( ! function_exists( 'TEMPLATENAME_entry_meta' ) ) :
// The Meta data for every post
function TEMPLATENAME_entry_meta() {
	if ( is_sticky() && is_home() && ! is_paged() )
		echo '<span class="featured-post">' . __( 'Sticky', 'TEMPLATENAME' ) . '</span>';

	TEMPLATENAME_entry_date();

	$categories_list = get_the_category_list( __( ', ', 'TEMPLATENAME' ) );
	if ( $categories_list ) {
		echo '<span class="categories-links">' . $categories_list . '</span>';
	}

	// Translators: used between list items, there is a space after the comma.
	$tag_list = get_the_tag_list( '', __( ', ', 'TEMPLATENAME' ) );
	if ( $tag_list ) {
		echo '<span class="tags-links"><i class="fa fa-tags"></i>' . $tag_list . '</span>';
	}

	// Post author
	if ( 'post' == get_post_type() ) {
		printf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_attr( sprintf( __( 'View all posts by %s', 'TEMPLATENAME' ), get_the_author() ) ),
			get_the_author()
		);
	}
}
endif;

if ( ! function_exists( 'TEMPLATENAME_entry_date' ) ) :
// The Date function for Meta data
function TEMPLATENAME_entry_date( $echo = true ) {
	if ( has_post_format( array( 'chat', 'status' ) ) )
		$format_prefix = _x( '%1$s on %2$s', '1: post format name. 2: date', 'TEMPLATENAME' );
	else
		$format_prefix = '%2$s';
	$date = sprintf( '<span class="date"><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a></span>',
		esc_url( get_permalink() ),
		esc_attr( sprintf( __( 'Permalink to %s', 'TEMPLATENAME' ), the_title_attribute( 'echo=0' ) ) ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( sprintf( $format_prefix, get_post_format_string( get_post_format() ), get_the_date() ) )
	);
	if ( $echo )
		echo $date;
	return $date;
}
endif;

// Breadcrumbs function 
function the_breadcrumb() {
	if (!is_home()) {
		echo '<a href="';
		echo get_option('home');
		echo '">';
		echo 'Home';
		echo "</a> » ";
		if (is_category() || is_single()) {
			the_category('title_li=');
			if (is_single()) {
				echo " » ";
				the_title();
			}
		} elseif (is_page()) {
			echo the_title();
		}
	}
}

// Remove the <p> tags from images in the content
/*
function filter_ptags_on_images($content){
   return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
}
add_filter('the_content', 'filter_ptags_on_images');
*/

?>