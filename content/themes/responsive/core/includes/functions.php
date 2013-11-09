<?php

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Theme's Functions and Definitions
 *
 *
 * @file           functions.php
 * @package        Responsive
 * @author         Emil Uzelac
 * @copyright      2003 - 2013 ThemeID
 * @license        license.txt
 * @version        Release: 1.2.1
 * @filesource     wp-content/themes/responsive/includes/functions.php
 * @link           http://codex.wordpress.org/Theme_Development#Functions_File
 * @since          available since Release 1.0
 */
?>
<?php
/*
 * Globalize Theme options
 */
$responsive_options = responsive_get_options();

/**
 * Add plugin automation file
 */
require_once( dirname( __FILE__ ) . '/classes/class-tgm-plugin-activation.php' );

/*
 * Hook options
 */
add_action( 'admin_init', 'responsive_theme_options_init' );
add_action( 'admin_menu', 'responsive_theme_options_add_page' );

/**
 * Retrieve Theme option settings
 */
function responsive_get_options() {
	// Globalize the variable that holds the Theme options
	global $responsive_options;
	// Parse array of option defaults against user-configured Theme options
	$responsive_options = wp_parse_args( get_option( 'responsive_theme_options', array() ), responsive_get_option_defaults() );

	// Return parsed args array
	return $responsive_options;
}

/**
 * Responsive Theme option defaults
 */
function responsive_get_option_defaults() {
	$defaults = array(
		'breadcrumb'                      => false,
		'cta_button'                      => false,
		'front_page'                      => 1,
		'home_headline'                   => null,
		'home_subheadline'                => null,
		'home_content_area'               => null,
		'cta_text'                        => null,
		'cta_url'                         => null,
		'featured_content'                => null,
		'google_site_verification'        => '',
		'bing_site_verification'          => '',
		'yahoo_site_verification'         => '',
		'site_statistics_tracker'         => '',
		'twitter_uid'                     => '',
		'facebook_uid'                    => '',
		'linkedin_uid'                    => '',
		'youtube_uid'                     => '',
		'stumble_uid'                     => '',
		'rss_uid'                         => '',
		'google_plus_uid'                 => '',
		'instagram_uid'                   => '',
		'pinterest_uid'                   => '',
		'yelp_uid'                        => '',
		'vimeo_uid'                       => '',
		'foursquare_uid'                  => '',
		'responsive_inline_css'           => '',
		'responsive_inline_js_head'       => '',
		'responsive_inline_css_js_footer' => '',
		'static_page_layout_default'      => 'content-sidebar-page',
		'single_post_layout_default'      => 'content-sidebar-page',
		'blog_posts_index_layout_default' => 'content-sidebar-page',
	);

	return apply_filters( 'responsive_option_defaults', $defaults );
}

/**
 * Helps file locations in child themes. If the file is not being overwritten by the child theme then
 * return the parent theme location of the file. Great for images.
 *
 * @param $dir string directory
 *
 * @return string complete uri
 */
function responsive_child_uri( $dir ) {
	if( is_child_theme() ) {
		$directory = get_stylesheet_directory() . $dir;
		$test      = is_file( $directory );
		if( is_file( $directory ) ) {
			$file = get_stylesheet_directory_uri() . $dir;
		}
		else {
			$file = get_template_directory_uri() . $dir;
		}
	}
	else {
		$file = get_template_directory_uri() . $dir;
	}

	return $file;
}

/**
 * Fire up the engines boys and girls let's start theme setup.
 */
add_action( 'after_setup_theme', 'responsive_setup' );

if( !function_exists( 'responsive_setup' ) ):

	function responsive_setup() {

		global $content_width;

		$template_directory = get_template_directory();

		/**
		 * Global content width.
		 */
		if( !isset( $content_width ) ) {
			$content_width = 550;
		}

		/**
		 * Responsive is now available for translations.
		 * The translation files are in the /languages/ directory.
		 * Translations are pulled from the WordPress default lanaguge folder
		 * then from the child theme and then lastly from the parent theme.
		 * @see http://codex.wordpress.org/Function_Reference/load_theme_textdomain
		 */

		$domain = 'responsive';

		load_theme_textdomain( $domain, WP_LANG_DIR . '/responsive/' );
		load_theme_textdomain( $domain, get_stylesheet_directory() . '/languages/' );
		load_theme_textdomain( $domain, get_template_directory() . '/languages/' );

		/**
		 * Add callback for custom TinyMCE editor stylesheets. (editor-style.css)
		 * @see http://codex.wordpress.org/Function_Reference/add_editor_style
		 */
		add_editor_style();

		/**
		 * This feature enables post and comment RSS feed links to head.
		 * @see http://codex.wordpress.org/Function_Reference/add_theme_support#Feed_Links
		 */
		add_theme_support( 'automatic-feed-links' );

		/**
		 * This feature enables post-thumbnail support for a theme.
		 * @see http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
		 */
		add_theme_support( 'post-thumbnails' );

		/**
		 * This feature enables woocommerce support for a theme.
		 * @see http://www.woothemes.com/2013/02/last-call-for-testing-woocommerce-2-0-coming-march-4th/
		 */
		add_theme_support( 'woocommerce' );

		/**
		 * This feature enables custom-menus support for a theme.
		 * @see http://codex.wordpress.org/Function_Reference/register_nav_menus
		 */
		register_nav_menus( array(
								'top-menu'        => __( 'Top Menu', 'responsive' ),
								'header-menu'     => __( 'Header Menu', 'responsive' ),
								'sub-header-menu' => __( 'Sub-Header Menu', 'responsive' ),
								'footer-menu'     => __( 'Footer Menu', 'responsive' )
							)
		);

		if( function_exists( 'get_custom_header' ) ) {

			add_theme_support( 'custom-background' );

		}
		else {

			// < 3.4 Backward Compatibility

			/**
			 * This feature allows users to use custom background for a theme.
			 * @see http://codex.wordpress.org/Function_Reference/add_custom_background
			 */

			add_custom_background();

		}

		// WordPress 3.4 >
		if( function_exists( 'get_custom_header' ) ) {

			add_theme_support( 'custom-header', array(
				// Header image default
				'default-image'       => get_template_directory_uri() . '/core/images/default-logo.png',
				// Header text display default
				'header-text'         => false,
				// Header image flex width
				'flex-width'          => true,
				// Header image width (in pixels)
				'width'               => 300,
				// Header image flex height
				'flex-height'         => true,
				// Header image height (in pixels)
				'height'              => 100,
				// Admin header style callback
				'admin-head-callback' => 'responsive_admin_header_style'
			) );

			// gets included in the admin header
			function responsive_admin_header_style() {
				?>
				<style type="text/css">
					.appearance_page_custom-header #headimg {
						background-repeat: no-repeat;
						border: none;
					}
				</style><?php
			}

		}
		else {

			// Backward Compatibility

			/**
			 * This feature adds a callbacks for image header display.
			 * In our case we are using this to display logo.
			 * @see http://codex.wordpress.org/Function_Reference/add_custom_image_header
			 */
			define( 'HEADER_TEXTCOLOR', '' );
			define( 'HEADER_IMAGE', '%s/core/images/default-logo.png' ); // %s is the template dir uri
			define( 'HEADER_IMAGE_WIDTH', 300 ); // use width and height appropriate for your theme
			define( 'HEADER_IMAGE_HEIGHT', 100 );
			define( 'NO_HEADER_TEXT', true );

			// gets included in the admin header
			function responsive_admin_header_style() {
				?>
				<style type="text/css">
				#headimg {
					background-repeat: no-repeat;
					border: none !important;
					width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
					height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
				}
				</style><?php
			}

			add_custom_image_header( '', 'responsive_admin_header_style' );

		}

		// While upgrading set theme option front page toggle not to affect old setup.
		$responsive_options = get_option( 'responsive_theme_options' );
		if( $responsive_options && isset( $_GET['activated'] ) ) {

			// If front_page is not in theme option previously then set it.
			if( !isset( $responsive_options['front_page'] ) ) {

				// Get template of page which is set as static front page
				$template = get_post_meta( get_option( 'page_on_front' ), '_wp_page_template', true );

				// If static front page template is set to default then set front page toggle of theme option to 1
				if( 'page' == get_option( 'show_on_front' ) && $template == 'default' ) {
					$responsive_options['front_page'] = 1;
				}
				else {
					$responsive_options['front_page'] = 0;
				}
				update_option( 'responsive_theme_options', $responsive_options );
			}
		}
	}

endif;

/**
 * Set a fallback menu that will show a home link.
 */

function responsive_fallback_menu() {
	$args    = array(
		'depth'       => 0,
		'sort_column' => 'menu_order, post_title',
		'menu_class'  => 'menu',
		'include'     => '',
		'exclude'     => '',
		'echo'        => false,
		'show_home'   => true,
		'link_before' => '',
		'link_after'  => ''
	);
	$pages   = wp_page_menu( $args );
	$prepend = '<div class="main-nav">';
	$append  = '</div>';
	$output  = $prepend . $pages . $append;
	echo $output;
}

/**
 * This function removes .menu class from custom menus
 * in widgets only and fallback's on default widget lists
 * and assigns new unique class called .menu-widget
 *
 * Marko Heijnen Contribution
 *
 */
class responsive_widget_menu_class {
	public function __construct() {
		add_action( 'widget_display_callback', array( $this, 'menu_different_class' ), 10, 2 );
	}

	public function menu_different_class( $settings, $widget ) {
		if( $widget instanceof WP_Nav_Menu_Widget ) {
			add_filter( 'wp_nav_menu_args', array( $this, 'wp_nav_menu_args' ) );
		}

		return $settings;
	}

	public function wp_nav_menu_args( $args ) {
		remove_filter( 'wp_nav_menu_args', array( $this, 'wp_nav_menu_args' ) );

		if( 'menu' == $args['menu_class'] ) {
			$args['menu_class'] = 'menu-widget';
		}

		return $args;
	}
}

new responsive_widget_menu_class();

/**
 * Removes div from wp_page_menu() and replace with ul.
 */
function responsive_wp_page_menu( $page_markup ) {
	preg_match( '/^<div class=\"([a-z0-9-_]+)\">/i', $page_markup, $matches );
	$divclass   = $matches[1];
	$replace    = array( '<div class="' . $divclass . '">', '</div>' );
	$new_markup = str_replace( $replace, '', $page_markup );
	$new_markup = preg_replace( '/^<ul>/i', '<ul class="' . $divclass . '">', $new_markup );

	return $new_markup;
}

add_filter( 'wp_page_menu', 'responsive_wp_page_menu' );

/**
 * wp_title() Filter for better SEO.
 *
 * Adopted from Twenty Twelve
 * @see http://codex.wordpress.org/Plugin_API/Filter_Reference/wp_title
 *
 */
if( !function_exists( 'responsive_wp_title' ) && !defined( 'AIOSEOP_VERSION' ) ) :

	function responsive_wp_title( $title, $sep ) {
		global $page, $paged;

		if( is_feed() ) {
			return $title;
		}

		// Add the site name.
		$title .= get_bloginfo( 'name' );

		// Add the site description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if( $site_description && ( is_home() || is_front_page() ) ) {
			$title .= " $sep $site_description";
		}

		// Add a page number if necessary.
		if( $paged >= 2 || $page >= 2 ) {
			$title .= " $sep " . sprintf( __( 'Page %s', 'responsive' ), max( $paged, $page ) );
		}

		return $title;
	}

	add_filter( 'wp_title', 'responsive_wp_title', 10, 2 );

endif;

/**
 * Filter 'get_comments_number'
 *
 * Filter 'get_comments_number' to display correct
 * number of comments (count only comments, not
 * trackbacks/pingbacks)
 *
 * Chip Bennett Contribution
 */
function responsive_comment_count( $count ) {
	if( !is_admin() ) {
		global $id;
		$comments         = get_comments( 'status=approve&post_id=' . $id );
		$comments_by_type = separate_comments( $comments );

		return count( $comments_by_type['comment'] );
	}
	else {
		return $count;
	}
}

add_filter( 'get_comments_number', 'responsive_comment_count', 0 );

/**
 * wp_list_comments() Pings Callback
 *
 * wp_list_comments() Callback function for
 * Pings (Trackbacks/Pingbacks)
 */
function responsive_comment_list_pings( $comment ) {
	$GLOBALS['comment'] = $comment;
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>"><?php echo comment_author_link(); ?></li>
<?php
}

/**
 * Sets the post excerpt length to 40 words.
 * Adopted from Coraline
 */
function responsive_excerpt_length( $length ) {
	return 40;
}

add_filter( 'excerpt_length', 'responsive_excerpt_length' );

/**
 * Returns a "Read more" link for excerpts
 */
function responsive_read_more() {
	return '<div class="read-more"><a href="' . get_permalink() . '">' . __( 'Read more &#8250;', 'responsive' ) . '</a></div><!-- end of .read-more -->';
}

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and responsive_read_more_link().
 */
function responsive_auto_excerpt_more( $more ) {
	return '<span class="ellipsis">&hellip;</span>' . responsive_read_more();
}

add_filter( 'excerpt_more', 'responsive_auto_excerpt_more' );

/**
 * Adds a pretty "Read more" link to custom post excerpts.
 */
function responsive_custom_excerpt_more( $output ) {
	if( has_excerpt() && !is_attachment() ) {
		$output .= responsive_read_more();
	}

	return $output;
}

add_filter( 'get_the_excerpt', 'responsive_custom_excerpt_more' );

/**
 * This function removes inline styles set by WordPress gallery.
 */
function responsive_remove_gallery_css( $css ) {
	return preg_replace( "#<style type='text/css'>(.*?)</style>#s", '', $css );
}

add_filter( 'gallery_style', 'responsive_remove_gallery_css' );

/**
 * This function removes default styles set by WordPress recent comments widget.
 */
function responsive_remove_recent_comments_style() {
	global $wp_widget_factory;
	if( isset( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'] ) ) {
		remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
	}
}

add_action( 'widgets_init', 'responsive_remove_recent_comments_style' );

/**
 * This function prints post meta data.
 *
 * Ulrich Pogson Contribution
 *
 */
if( !function_exists( 'responsive_post_meta_data' ) ) :

	function responsive_post_meta_data() {
		printf( __( '<span class="%1$s">Posted on </span>%2$s<span class="%3$s"> by </span>%4$s', 'responsive' ),
				'meta-prep meta-prep-author posted',
				sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><span class="timestamp updated">%3$s</span></a>',
						 esc_url( get_permalink() ),
						 esc_attr( get_the_time() ),
						 esc_html( get_the_date() )
				),
				'byline',
				sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
						 get_author_posts_url( get_the_author_meta( 'ID' ) ),
						 sprintf( esc_attr__( 'View all posts by %s', 'responsive' ), get_the_author() ),
						 esc_attr( get_the_author() )
				)
		);
	}
endif;

/**
 * This function removes WordPress generated category and tag atributes.
 * For W3C validation purposes only.
 *
 */
function responsive_category_rel_removal( $output ) {
	$output = str_replace( ' rel="category tag"', '', $output );

	return $output;
}

add_filter( 'wp_list_categories', 'responsive_category_rel_removal' );
add_filter( 'the_category', 'responsive_category_rel_removal' );

/**
 * Breadcrumb Lists
 * Allows visitors to quickly navigate back to a previous section or the root page.
 *
 * Adopted from Dimox
 *
 */
if( !function_exists( 'responsive_breadcrumb_lists' ) ) :

	function responsive_breadcrumb_lists() {

		/* === OPTIONS === */
		$text['home']     = __( 'Home', 'responsive' ); // text for the 'Home' link
		$text['category'] = __( 'Archive for %s', 'responsive' ); // text for a category page
		$text['search']   = __( 'Search results for: %s', 'responsive' ); // text for a search results page
		$text['tag']      = __( 'Posts tagged %s', 'responsive' ); // text for a tag page
		$text['author']   = __( 'View all posts by %s', 'responsive' ); // text for an author page
		$text['404']      = __( 'Error 404', 'responsive' ); // text for the 404 page

		$showCurrent = 1; // 1 - show current post/page title in breadcrumbs, 0 - don't show
		$showOnHome  = 0; // 1 - show breadcrumbs on the homepage, 0 - don't show
		$delimiter   = ' <span class="chevron">&#8250;</span> '; // delimiter between crumbs
		$before      = '<span class="breadcrumb-current">'; // tag before the current crumb
		$after       = '</span>'; // tag after the current crumb
		/* === END OF OPTIONS === */

		global $post, $paged, $page;
		$homeLink   = home_url( '/' );
		$linkBefore = '<span class="breadcrumb" typeof="v:Breadcrumb">';
		$linkAfter  = '</span>';
		$linkAttr   = ' rel="v:url" property="v:title"';
		$link       = $linkBefore . '<a' . $linkAttr . ' href="%1$s">%2$s</a>' . $linkAfter;

		if( is_front_page() ) {

			if( $showOnHome == 1 ) {
				echo '<div class="breadcrumb-list"><a href="' . $homeLink . '">' . $text['home'] . '</a></div>';
			}

		}
		else {

			echo '<div class="breadcrumb-list" xmlns:v="http://rdf.data-vocabulary.org/#">' . sprintf( $link, $homeLink, $text['home'] ) . $delimiter;

			if( is_home() ) {
				if( $showCurrent == 1 ) {
					echo $before . get_the_title( get_option( 'page_for_posts', true ) ) . $after;
				}

			}
			elseif( is_category() ) {
				$thisCat = get_category( get_query_var( 'cat' ), false );
				if( $thisCat->parent != 0 ) {
					$cats = get_category_parents( $thisCat->parent, true, $delimiter );
					$cats = str_replace( '<a', $linkBefore . '<a' . $linkAttr, $cats );
					$cats = str_replace( '</a>', '</a>' . $linkAfter, $cats );
					echo $cats;
				}
				echo $before . sprintf( $text['category'], single_cat_title( '', false ) ) . $after;

			}
			elseif( is_search() ) {
				echo $before . sprintf( $text['search'], get_search_query() ) . $after;

			}
			elseif( is_day() ) {
				echo sprintf( $link, get_year_link( get_the_time( 'Y' ) ), get_the_time( 'Y' ) ) . $delimiter;
				echo sprintf( $link, get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ), get_the_time( 'F' ) ) . $delimiter;
				echo $before . get_the_time( 'd' ) . $after;

			}
			elseif( is_month() ) {
				echo sprintf( $link, get_year_link( get_the_time( 'Y' ) ), get_the_time( 'Y' ) ) . $delimiter;
				echo $before . get_the_time( 'F' ) . $after;

			}
			elseif( is_year() ) {
				echo $before . get_the_time( 'Y' ) . $after;

			}
			elseif( is_single() && !is_attachment() ) {
				if( get_post_type() != 'post' ) {
					$post_type = get_post_type_object( get_post_type() );
					$slug      = $post_type->rewrite;
					printf( $link, $homeLink . '/' . $slug['slug'] . '/', $post_type->labels->singular_name );
					if( $showCurrent == 1 ) {
						echo $delimiter . $before . get_the_title() . $after;
					}
				}
				else {
					$cat  = get_the_category();
					$cat  = $cat[0];
					$cats = get_category_parents( $cat, true, $delimiter );
					if( $showCurrent == 0 ) {
						$cats = preg_replace( "#^(.+)$delimiter$#", "$1", $cats );
					}
					$cats = str_replace( '<a', $linkBefore . '<a' . $linkAttr, $cats );
					$cats = str_replace( '</a>', '</a>' . $linkAfter, $cats );
					echo $cats;
					if( $showCurrent == 1 ) {
						echo $before . get_the_title() . $after;
					}
				}

			}
			elseif( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
				$post_type = get_post_type_object( get_post_type() );
				echo $before . $post_type->labels->singular_name . $after;

			}
			elseif( is_attachment() ) {
				$parent = get_post( $post->post_parent );
				$cat    = get_the_category( $parent->ID );

				if( isset( $cat[0] ) ) {
					$cat = $cat[0];
				}

				if( $cat ) {
					$cats = get_category_parents( $cat, true, $delimiter );
					$cats = str_replace( '<a', $linkBefore . '<a' . $linkAttr, $cats );
					$cats = str_replace( '</a>', '</a>' . $linkAfter, $cats );
					echo $cats;
				}

				printf( $link, get_permalink( $parent ), $parent->post_title );
				if( $showCurrent == 1 ) {
					echo $delimiter . $before . get_the_title() . $after;
				}

			}
			elseif( is_page() && !$post->post_parent ) {
				if( $showCurrent == 1 ) {
					echo $before . get_the_title() . $after;
				}

			}
			elseif( is_page() && $post->post_parent ) {
				$parent_id   = $post->post_parent;
				$breadcrumbs = array();
				while( $parent_id ) {
					$page_child    = get_page( $parent_id );
					$breadcrumbs[] = sprintf( $link, get_permalink( $page_child->ID ), get_the_title( $page_child->ID ) );
					$parent_id     = $page_child->post_parent;
				}
				$breadcrumbs = array_reverse( $breadcrumbs );
				for( $i = 0; $i < count( $breadcrumbs ); $i++ ) {
					echo $breadcrumbs[$i];
					if( $i != count( $breadcrumbs ) - 1 ) {
						echo $delimiter;
					}
				}
				if( $showCurrent == 1 ) {
					echo $delimiter . $before . get_the_title() . $after;
				}

			}
			elseif( is_tag() ) {
				echo $before . sprintf( $text['tag'], single_tag_title( '', false ) ) . $after;

			}
			elseif( is_author() ) {
				global $author;
				$userdata = get_userdata( $author );
				echo $before . sprintf( $text['author'], $userdata->display_name ) . $after;

			}
			elseif( is_404() ) {
				echo $before . $text['404'] . $after;

			}
			if( get_query_var( 'paged' ) ) {
				if( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) {
					echo ' (';
				}
				echo $delimiter . sprintf( __( 'Page %s', 'responsive' ), max( $paged, $page ) );
				if( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) {
					echo ')';
				}
			}

			echo '</div>';

		}
	} // end responsive_breadcrumb_lists

endif;

/**
 * A safe way of adding stylesheets to a WordPress generated page.
 */
if( !is_admin() ) {
	add_action( 'wp_enqueue_scripts', 'responsive_css' );
}

if( !function_exists( 'responsive_css' ) ) {

	function responsive_css() {
		wp_enqueue_style( 'responsive-style', get_template_directory_uri() . '/style.css', false, '1.9.3.4' );
		wp_enqueue_style( 'responsive-media-queries', get_template_directory_uri() . '/core/css/style.css', false, '1.9.3.4' );
		if( is_rtl() ) {
			wp_enqueue_style( 'responsive-rtl-style', get_template_directory_uri() . '/rtl.css', false, '1.9.3.4' );
		}
		if( is_child_theme() ) {
			wp_enqueue_style( 'responsive-child-style', get_stylesheet_uri(), false, '1.9.3.4' );
		}
	}

}

/**
 * A safe way of adding JavaScripts to a WordPress generated page.
 */
if( !is_admin() ) {
	add_action( 'wp_enqueue_scripts', 'responsive_js' );
}

if( !function_exists( 'responsive_js' ) ) {

	function responsive_js() {
		$template_directory_uri = get_template_directory_uri();

		// JS at the bottom for fast page loading.
		// except for Modernizr which enables HTML5 elements & feature detects.
		wp_enqueue_script( 'modernizr', $template_directory_uri . '/core/js/responsive-modernizr.js', array( 'jquery' ), '2.6.1', false );
		wp_enqueue_script( 'responsive-scripts', $template_directory_uri . '/core/js/responsive-scripts.js', array( 'jquery' ), '1.2.4', true );
	}

}

/**
 * A comment reply.
 */
function responsive_enqueue_comment_reply() {
	if( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}

add_action( 'wp_enqueue_scripts', 'responsive_enqueue_comment_reply' );

/**
 * Theme options upgrade bar
 */
function responsive_upgrade_bar() {
	?>

	<div class="upgrade-callout">
		<p><img src="<?php echo get_template_directory_uri(); ?>/core/includes/images/chimp.png" alt="CyberChimps"/>
			<?php printf( __( 'Welcome to %1$s! Upgrade to %2$s today.', 'responsive' ),
						  'Responsive',
						  ' <a href="http://cyberchimps.com/store/responsivepro/" target="_blank" title="Responsive Pro">Responsive Pro</a> '
			); ?>
		</p>

		<div class="social-container">
			<div class="social">
				<a href="https://twitter.com/cyberchimps" class="twitter-follow-button" data-show-count="false" data-size="small">Follow @cyberchimps</a>
				<script>!function (d, s, id) {
						var js, fjs = d.getElementsByTagName(s)[0];
						if (!d.getElementById(id)) {
							js = d.createElement(s);
							js.id = id;
							js.src = "//platform.twitter.com/widgets.js";
							fjs.parentNode.insertBefore(js, fjs);
						}
					}(document, "script", "twitter-wjs");</script>
			</div>
			<div class="social">
				<iframe
					src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fcyberchimps.com%2F&amp;send=false&amp;layout=button_count&amp;width=200&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21"
					scrolling="no" frameborder="0"
					style="border:none; overflow:hidden; width:200px; height:21px;" allowTransparency="true"></iframe>
			</div>
		</div>
	</div>

<?php
}

add_action( 'responsive_theme_options', 'responsive_upgrade_bar', 1 );

/**
 * Theme Options Support and Information
 */
function responsive_theme_support() {
	?>

	<div id="info-box-wrapper" class="grid col-940">
		<div class="info-box notice">

			<a class="button" href="<?php echo esc_url( 'http://cyberchimps.com/guides/r-free/' ); ?>" title="<?php esc_attr_e( 'Instructions', 'responsive' ); ?>" target="_blank">
				<?php _e( 'Instructions', 'responsive' ); ?></a>

			<a class="button button-primary" href="<?php echo esc_url( 'http://cyberchimps.com/forum/free/responsive/' ); ?>" title="<?php esc_attr_e( 'Help', 'responsive' ); ?>" target="_blank">
				<?php _e( 'Help', 'responsive' ); ?></a>

			<a class="button" href="<?php echo esc_url( 'https://webtranslateit.com/en/projects/3598-Responsive-Theme' ); ?>" title="<?php esc_attr_e( 'Translate',
																																					   'responsive' ); ?>" target="_blank">
				<?php _e( 'Translate', 'responsive' ); ?></a>

			<a class="button" href="<?php echo esc_url( 'http://cyberchimps.com/showcase/' ); ?>" title="<?php esc_attr_e( 'Showcase', 'responsive' ); ?>" target="_blank">
				<?php _e( 'Showcase', 'responsive' ); ?></a>

			<a class="button" href="<?php echo esc_url( 'http://cyberchimps.com/store/' ); ?>" title="<?php esc_attr_e( 'More Themes', 'responsive' ); ?>" target="_blank">
				<?php _e( 'More Themes', 'responsive' ); ?></a>

		</div>
	</div>

<?php
}

add_action( 'responsive_theme_options', 'responsive_theme_support', 2 );

/**
 * WordPress Widgets start right here.
 */
function responsive_widgets_init() {

	register_sidebar( array(
						  'name'          => __( 'Main Sidebar', 'responsive' ),
						  'description'   => __( 'Area 1 - sidebar.php - Displays on Default, Blog, Blog Excerpt page templates', 'responsive' ),
						  'id'            => 'main-sidebar',
						  'before_title'  => '<div class="widget-title"><h3>',
						  'after_title'   => '</h3></div>',
						  'before_widget' => '<div id="%1$s" class="widget-wrapper %2$s">',
						  'after_widget'  => '</div>'
					  ) );

	register_sidebar( array(
						  'name'          => __( 'Right Sidebar', 'responsive' ),
						  'description'   => __( 'Area 2 - sidebar-right.php - Displays on Content/Sidebar page templates', 'responsive' ),
						  'id'            => 'right-sidebar',
						  'before_title'  => '<div class="widget-title"><h3>',
						  'after_title'   => '</h3></div>',
						  'before_widget' => '<div id="%1$s" class="widget-wrapper %2$s">',
						  'after_widget'  => '</div>'
					  ) );

	register_sidebar( array(
						  'name'          => __( 'Left Sidebar', 'responsive' ),
						  'description'   => __( 'Area 3 - sidebar-left.php - Displays on Sidebar/Content page templates', 'responsive' ),
						  'id'            => 'left-sidebar',
						  'before_title'  => '<div class="widget-title"><h3>',
						  'after_title'   => '</h3></div>',
						  'before_widget' => '<div id="%1$s" class="widget-wrapper %2$s">',
						  'after_widget'  => '</div>'
					  ) );

	register_sidebar( array(
						  'name'          => __( 'Left Sidebar Half Page', 'responsive' ),
						  'description'   => __( 'Area 4 - sidebar-left-half.php - Displays on Sidebar Half Page/Content page templates', 'responsive' ),
						  'id'            => 'left-sidebar-half',
						  'before_title'  => '<div class="widget-title"><h3>',
						  'after_title'   => '</h3></div>',
						  'before_widget' => '<div id="%1$s" class="widget-wrapper %2$s">',
						  'after_widget'  => '</div>'
					  ) );

	register_sidebar( array(
						  'name'          => __( 'Right Sidebar Half Page', 'responsive' ),
						  'description'   => __( 'Area 5 - sidebar-right-half.php - Displays on Content/Sidebar Half Page page templates', 'responsive' ),
						  'id'            => 'right-sidebar-half',
						  'before_title'  => '<div class="widget-title"><h3>',
						  'after_title'   => '</h3></div>',
						  'before_widget' => '<div id="%1$s" class="widget-wrapper %2$s">',
						  'after_widget'  => '</div>'
					  ) );

	register_sidebar( array(
						  'name'          => __( 'Home Widget 1', 'responsive' ),
						  'description'   => __( 'Area 6 - sidebar-home.php - Displays on the Home Page', 'responsive' ),
						  'id'            => 'home-widget-1',
						  'before_title'  => '<div id="widget-title-one" class="widget-title-home"><h3>',
						  'after_title'   => '</h3></div>',
						  'before_widget' => '<div id="%1$s" class="widget-wrapper %2$s">',
						  'after_widget'  => '</div>'
					  ) );

	register_sidebar( array(
						  'name'          => __( 'Home Widget 2', 'responsive' ),
						  'description'   => __( 'Area 7 - sidebar-home.php - Displays on the Home Page', 'responsive' ),
						  'id'            => 'home-widget-2',
						  'before_title'  => '<div id="widget-title-two" class="widget-title-home"><h3>',
						  'after_title'   => '</h3></div>',
						  'before_widget' => '<div id="%1$s" class="widget-wrapper %2$s">',
						  'after_widget'  => '</div>'
					  ) );

	register_sidebar( array(
						  'name'          => __( 'Home Widget 3', 'responsive' ),
						  'description'   => __( 'Area 8 - sidebar-home.php - Displays on the Home Page', 'responsive' ),
						  'id'            => 'home-widget-3',
						  'before_title'  => '<div id="widget-title-three" class="widget-title-home"><h3>',
						  'after_title'   => '</h3></div>',
						  'before_widget' => '<div id="%1$s" class="widget-wrapper %2$s">',
						  'after_widget'  => '</div>'
					  ) );

	register_sidebar( array(
						  'name'          => __( 'Gallery Sidebar', 'responsive' ),
						  'description'   => __( 'Area 9 - sidebar-gallery.php - Displays on the page after an image has been clicked in a Gallery', 'responsive' ),
						  'id'            => 'gallery-widget',
						  'before_title'  => '<div class="widget-title"><h3>',
						  'after_title'   => '</h3></div>',
						  'before_widget' => '<div id="%1$s" class="widget-wrapper %2$s">',
						  'after_widget'  => '</div>'
					  ) );

	register_sidebar( array(
						  'name'          => __( 'Colophon Widget', 'responsive' ),
						  'description'   => __( 'Area 10 - sidebar-colophon.php, 100% width Footer widgets', 'responsive' ),
						  'id'            => 'colophon-widget',
						  'before_title'  => '<div class="widget-title"><h3>',
						  'after_title'   => '</h3></div>',
						  'before_widget' => '<div id="%1$s" class="colophon-widget widget-wrapper %2$s">',
						  'after_widget'  => '</div>'
					  ) );

	register_sidebar( array(
						  'name'          => __( 'Top Widget', 'responsive' ),
						  'description'   => __( 'Area 11 - sidebar-top.php - Displays on the right of the header', 'responsive' ),
						  'id'            => 'top-widget',
						  'before_title'  => '<div class="widget-title"><h3>',
						  'after_title'   => '</h3></div>',
						  'before_widget' => '<div id="%1$s" class="%2$s">',
						  'after_widget'  => '</div>'
					  ) );
					  
	register_sidebar( array(
						  'name'          => __( 'Footer Widget', 'responsive' ),
						  'description'   => __( 'Area 12 - sidebar-footer.php, use a maximum of 3 widgets', 'responsive' ),
						  'id'            => 'footer-widget',
						  'before_title'  => '<div class="widget-title"><h3>',
						  'after_title'   => '</h3></div>',
						  'before_widget' => '<div id="%1$s" class="grid col-300 %2$s"><div class="widget-wrapper">',
						  'after_widget'  => '</div></div>'
					  ) );
}

add_action( 'widgets_init', 'responsive_widgets_init' );

/**
 * Front Page function starts here. The Front page overides WP's show_on_front option. So when show_on_front option changes it sets the themes front_page to 0 therefore displaying the new option
 */

function responsive_front_page_override( $new, $orig ) {
	global $responsive_options;

	if( $orig !== $new ) {
		$responsive_options['front_page'] = 0;

		update_option( 'responsive_theme_options', $responsive_options );
	}

	return $new;
}

add_filter( 'pre_update_option_show_on_front', 'responsive_front_page_override', 10, 2 );

/**
 * Funtion to add CSS class to body
 */
function responsive_add_class( $classes ) {

	// Get Responsive theme option.
	global $responsive_options;
	if( $responsive_options['front_page'] == 1 && is_front_page() ) {
		$classes[] = 'front-page';
	}

	return $classes;
}

add_filter( 'body_class', 'responsive_add_class' );

function responsive_install_plugins() {
	$plugins = array(

		// This is an example of how to include a plugin pre-packaged with a theme
		array(
			'name'     => 'Responsive Add Ons', // The plugin name
			'slug'     => 'responsive-add-ons', // The plugin slug (typically the folder name)
			'required' => false
		)
	);

	// Change this to your theme text domain, used for internationalising strings
	$theme_text_domain = 'responsive';

	/**
	 * Array of configuration settings. Amend each line as needed.
	 * If you want the default strings to be available under your own theme domain,
	 * leave the strings uncommented.
	 * Some of the strings are added into a sprintf, so see the comments at the
	 * end of each line for what each argument will be.
	 */

	$config = array(
		'domain'           => $theme_text_domain, // Text domain - likely want to be the same as your theme.
		'default_path'     => '', // Default absolute path to pre-packaged plugins
		'parent_menu_slug' => 'themes.php', // Default parent menu slug
		'parent_url_slug'  => 'themes.php', // Default parent URL slug
		'menu'             => 'install-responsive-addons', // Menu slug
		'has_notices'      => true, // Show admin notices or not
		'is_automatic'     => true, // Automatically activate plugins after installation or not
		'message'          => '', // Message to output right before the plugins table
		'strings'          => array(
			'page_title'                      => __( 'Responsive Add Features', $theme_text_domain ),
			'menu_title'                      => __( 'Activate Add Ons', $theme_text_domain ),
			'installing'                      => __( 'Installing Plugin: %s', $theme_text_domain ), // %1$s = plugin name
			'oops'                            => __( 'Something went wrong with the plugin API.', $theme_text_domain ),
			'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ), // %1$s = plugin name(s)
			'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ), // %1$s = plugin name(s)
			'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ), // %1$s = plugin name(s)
			'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
			'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
			'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ), // %1$s = plugin name(s)
			'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ), // %1$s = plugin name(s)
			'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ), // %1$s = plugin name(s)
			'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
			'activate_link'                   => _n_noop( 'Activate installed plugin', 'Activate installed plugins' ),
			'return'                          => __( 'Return to Required Plugins Installer', $theme_text_domain ),
			'plugin_activated'                => __( 'Plugin activated successfully.', $theme_text_domain ),
			'complete'                        => __( 'All plugins installed and activated successfully. %s', $theme_text_domain ) // %1$s = dashboard link
		)
	);

	tgmpa( $plugins, $config );

}

// Add plugin notification only if the current user is admin.
if( current_user_can( 'manage_options' ) ) {
	add_action( 'tgmpa_register', 'responsive_install_plugins' );
}
?>
