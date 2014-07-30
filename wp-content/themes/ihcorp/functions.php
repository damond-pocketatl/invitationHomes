<?php
/**
 * Twenty Twelve functions and definitions.
 *
 * Sets up the theme and provides some helper functions, which are used
 * in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook.
 *
 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
 *
 * @package WordPress
 * @subpackage IHCorp
 * @since Twenty Twelve 1.0
 */

/**
 * Sets up the content width value based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 625;

/**
 * Sets up theme defaults and registers the various WordPress features that
 * Twenty Twelve supports.
 *
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_editor_style() To add a Visual Editor stylesheet.
 * @uses add_theme_support() To add support for post thumbnails, automatic feed links,
 * 	custom background, and post formats.
 * @uses register_nav_menu() To add support for navigation menus.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_setup() {
	/*
	 * Makes Twenty Twelve available for translation.
	 *
	 * Translations can be added to the /languages/ directory.
	 * If you're building a theme based on Twenty Twelve, use a find and replace
	 * to change 'twentytwelve' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'twentytwelve', get_template_directory() . '/languages' );

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// Adds RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );

	// This theme supports a variety of post formats.
	add_theme_support( 'post-formats', array( 'aside', 'image', 'link', 'quote', 'status' ) );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menu( 'locations', __( 'Locations', 'twentytwelve' ) );
	register_nav_menu( 'applynow', __( 'Apply Now', 'twentytwelve' ) );
	register_nav_menu( 'residentservices', __( 'Resident Services', 'twentytwelve' ) );

	register_nav_menu( 'subnav', __( 'Sub-nav', 'ihcorp' ) );
	register_nav_menu( 'footerservices', __( 'Footer services', 'twentytwelve' ) );
	register_nav_menu( 'footerlocations', __( 'Footer locations', 'twentytwelve' ) );
    register_nav_menu( 'footerseller', __( 'Footer seller', 'twentytwelve' ) );

	/*
	 * This theme supports custom background color and image, and here
	 * we also set up the default background color.
	 */
	add_theme_support( 'custom-background', array(
		'default-color' => 'e6e6e6',
	) );

	// This theme uses a custom image size for featured images, displayed on "standard" posts.
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 624, 9999 ); // Unlimited height, soft crop
}
add_action( 'after_setup_theme', 'twentytwelve_setup' );


add_image_size( 'property-thumb', 440, 225, true );


// WP removed title tag from images, this adds it back
// see http://wordpress.org/plugins/restore-image-title/
/*
function lcb_restore_image_title( $html, $id ) {
	$attachment = get_post($id);
    if (strpos($html, "title=")) {
    	return $html;
    	}
    else {
		$mytitle = esc_attr($attachment->post_title);
		return str_replace('<img', '<img title="' . $mytitle . '" '  , $html);      
	}
}
add_filter( 'media_send_to_editor', 'lcb_restore_image_title', 15, 2 );
*/



/* ------------------------------------------------------------------------------------


	Define custom box for 'featured property'
	
	we have to do it this way instead of using WCK custom fields,
	since those can't be retrieved with get_post_meta and get_posts

*/

add_action( 'add_meta_boxes', 'myplugin_add_custom_box' );

/* Do something with the data entered */
add_action( 'save_post', 'myplugin_save_postdata' );

/* Adds a box to the main column on the Post and Page edit screens */
function myplugin_add_custom_box() {
	add_meta_box(
	    'myplugin_sectionid',
	    'Featured property',
	    'myplugin_inner_custom_box',
	    'property'
	);
}

/* Prints the box content */
function myplugin_inner_custom_box( $post ) {

  // Use nonce for verification
  wp_nonce_field( plugin_basename( __FILE__ ), 'myplugin_noncename' );

  // The actual fields for data entry
  // Use get_post_meta to retrieve an existing value from the database and use the value for the form
  $value = get_post_meta( $post->ID, 'featured-property', true );
  echo "<input type='checkbox' id='featured-property' name='featured-property' ";
	if($value) echo 'checked';
	echo '> This is a featured property<p><i>Marking this checkbox will cause the property to be featured on the home page and the market page</i></p>';
}

/* When the post is saved, saves our custom data */
function myplugin_save_postdata( $post_id ) {

  // First we need to check if the current user is authorised to do this action. 
  if ( 'page' == $_POST['post_type'] ) {
    if ( ! current_user_can( 'edit_page', $post_id ) )
        return;
  } else {
    if ( ! current_user_can( 'edit_post', $post_id ) )
        return;
  }

  // Secondly we need to check if the user intended to change this value.
  if ( ! isset( $_POST['myplugin_noncename'] ) || ! wp_verify_nonce( $_POST['myplugin_noncename'], plugin_basename( __FILE__ ) ) )
      return;

  // Thirdly we can save the value to the database

  //if saving in a custom table, get post_ID
  $post_ID = $_POST['post_ID'];
  //sanitize user input
  $mydata = sanitize_text_field( $_POST['featured-property'] );

  // Do something with $mydata 
  // either using 
  add_post_meta($post_ID, 'featured-property', $mydata, true) or
    update_post_meta($post_ID, 'featured-property', $mydata);
}

/* ------------------------------------------------------------------------------------ */

function findwidget($f) {
	$sidebars_widgets = wp_get_sidebars_widgets();

	if ( is_array( $sidebars_widgets ) )
	{
	    // array_search() returns FALSE in case the widget isn't present
	    $index            = array_search( $f, $sidebars_widgets, FALSE );
	    $sidebars_widgets = $sidebars_widgets[ $index ];
	}
	
	return $sidebars_widgets;
}

//HOMEPAGE
// Clean Markup Widget @ http://perishablepress.com/clean-markup-widget/
//Updated on 7/5/2013 by Jeremiah Lewis
//Uses http://wordpress.org/plugins/widget-image-field/ to extend image uploader to Widget
add_action('widgets_init', create_function('', 'register_widget("Home_widget");'));
class Home_widget extends WP_Widget {
        //Adding image field to widget
        var $image_field = 'image';  // the image field ID
	function __construct() {
		parent::WP_Widget('Home_widget', "Home widget", array('description'=>"The contents of the homepage widget"));
	}
	function widget($args, $instance) {
		extract($args);
                $image_id = $instance[$this->image_field];
		$imgurl = $instance['imgurl'];
                $markup = $instance['markup'];
                
                $image = new WidgetImageField( $this, $image_id );
		//echo $before_widget;
		if ($markup) {
                    $widgetdisplay = '';
                    $widgetdisplay .= "<div class='centerbox'>"."\n";
                    if( !empty( $imgurl ) ) :
                    $widgetdisplay .= '<a href="'. $imgurl .'">';
                    endif;
                    if( !empty( $image_id ) ) :
                    $widgetdisplay .= '<img src="'. $image->get_image_src('full') .'" width="100%" />';
                    endif;
                    if( !empty( $imgurl ) ) :
                    $widgetdisplay .= '</a>'."\n";
                    endif;
                    $widgetdisplay .= '<div class="markup">'. $markup .'</div>'."\n";
                    $widgetdisplay .= "</div>"."\n";
                    echo $widgetdisplay;
                }
		//echo $after_widget;
	}
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		
                $instance[$this->image_field] = intval( strip_tags( $new_instance[$this->image_field] ) );
                $instance['imgurl'] = strip_tags( $new_instance['imgurl'] );
                $instance['markup'] = $new_instance['markup'];
		return $instance;
	}
	function form($instance) {
                $image_id = esc_attr( isset( $instance[$this->image_field] ) ? $instance[$this->image_field] : 0 );
                $imgurl = esc_attr( isset( $instance['imgurl'] ) ? $instance['imgurl'] : '' );
                $markup = esc_attr( isset( $instance['markup'] ) ? $instance['markup'] : '' );
                
                $image = new WidgetImageField( $this, $image_id );
                
		if ($instance) $markup = esc_attr($instance['markup']);
		else $markup = __('&lt;p&gt;Clean, well-formatted markup.&lt;/p&gt;', 'markup_widget'); ?>
		<div>
                        <label><?php _e( 'Header Image:' ); ?></label>
                        <?php echo $image->get_widget_field(); ?>
                </div>
                <div>
                        <label for="<?php echo $this->get_field_id( 'imgurl' ); ?>"><?php _e( 'URL for image:' ); ?>
                        <input class="widefat" id="<?php echo $this->get_field_id( 'imgurl' ); ?>" name="<?php echo $this->get_field_name( 'imgurl' ); ?>" type="text" value="<?php echo $imgurl; ?>" />
                        </label>
                </div>
                <div>
			<label for="<?php echo $this->get_field_id('markup'); ?>"><?php _e('Markup/text'); ?></label><br />
			<textarea class="widefat" id="<?php echo $this->get_field_id('markup'); ?>" name="<?php echo $this->get_field_name('markup'); ?>" type="text" rows="16" cols="20" value="<?php echo $markup; ?>"><?php echo $markup; ?></textarea>
		</div>

<?php }
}

//------------------------------
//SIDEBAR
// Clean Markup Widget @ http://perishablepress.com/clean-markup-widget/
//Updated on 7/5/2013 by Jeremiah Lewis
//Uses http://wordpress.org/plugins/widget-image-field/ to extend image uploader to Widget
add_action('widgets_init', create_function('', 'register_widget("Sidebar_widget");'));
class Sidebar_widget extends WP_Widget {
        //Adding image field to widget
        var $image_field = 'image';  // the image field ID
	function __construct() {
		parent::WP_Widget('Sidebar_widget', "Sidebar widget", array('description'=>"The contents of the sidebar widget"));
	}
	function widget($args, $instance) {
		extract($args);
                $widget_id = $instance['widget_id'];
                $image_id = $instance[$this->image_field];
		$imgurl = $instance['imgurl'];
                $markup = $instance['markup'];
                
                if( !empty( $widget_id ) ) : $wid = ' id="'.$widget_id.'"'; endif;
                
                $image = new WidgetImageField( $this, $image_id );
		//echo $before_widget;
		if ($markup || $image_id) {
                    $widgetdisplay = '';
                    $widgetdisplay .= "<div class='box sidebar'".$wid.">"."\n";
                    if( !empty( $imgurl ) ) :
                    $widgetdisplay .= '<a href="'. $imgurl .'">';
                    endif;
                    if( !empty( $image_id ) ) :
                    $widgetdisplay .= '<img src="'. $image->get_image_src('full') .'" width="100%" />';
                    endif;
                    if( !empty( $imgurl ) ) :
                    $widgetdisplay .= '</a>'."\n";
                    endif;
                    if( !empty( $markup ) ) :
                    $widgetdisplay .= '<div class="markup">'. $markup .'</div>'."\n";
                    endif;
                    $widgetdisplay .= "</div>"."\n";
                    echo $widgetdisplay;
                }
		//echo $after_widget;
	}
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		
                $instance[$this->image_field] = intval( strip_tags( $new_instance[$this->image_field] ) );
                $instance['imgurl'] = strip_tags( $new_instance['imgurl'] );
                $instance['markup'] = $new_instance['markup'];
                $instance['widget_id'] = $new_instance['widget_id'];
		return $instance;
	}
	function form($instance) {
                $image_id = esc_attr( isset( $instance[$this->image_field] ) ? $instance[$this->image_field] : 0 );
                $imgurl = esc_attr( isset( $instance['imgurl'] ) ? $instance['imgurl'] : '' );
                $markup = esc_attr( isset( $instance['markup'] ) ? $instance['markup'] : '' );
                $widget_id = esc_attr( isset( $instance['widget_id'] ) ? $instance['widget_id'] : '' );
                
                $image = new WidgetImageField( $this, $image_id );
                
		if ($instance) $markup = esc_attr($instance['markup']);
		else $markup = __('&lt;p&gt;Clean, well-formatted markup.&lt;/p&gt;', 'markup_widget'); ?>
		<div>
                        <label><?php _e( 'Header Image:' ); ?></label>
                        <?php echo $image->get_widget_field(); ?>
                </div>
                <div>
                        <label for="<?php echo $this->get_field_id( 'imgurl' ); ?>"><?php _e( 'URL for image:' ); ?>
                        <input class="widefat" id="<?php echo $this->get_field_id( 'imgurl' ); ?>" name="<?php echo $this->get_field_name( 'imgurl' ); ?>" type="text" value="<?php echo $imgurl; ?>" />
                        </label>
                </div>
                <div>
                        <label for="<?php echo $this->get_field_id( 'widget_id' ); ?>"><?php _e( 'Custom Widget ID:' ); ?>
                        <input class="widefat" id="<?php echo $this->get_field_id( 'widget_id' ); ?>" name="<?php echo $this->get_field_name( 'widget_id' ); ?>" type="text" value="<?php echo $widget_id; ?>" />
                        </label>
                </div>
                <div>
			<label for="<?php echo $this->get_field_id('markup'); ?>"><?php _e('Markup/text'); ?></label><br />
			<textarea class="widefat" id="<?php echo $this->get_field_id('markup'); ?>" name="<?php echo $this->get_field_name('markup'); ?>" type="text" rows="16" cols="20" value="<?php echo $markup; ?>"><?php echo $markup; ?></textarea>
		</div>

<?php }
}

add_action('widgets_init', create_function('', 'register_widget("AdBanner_widget");'));
class AdBanner_widget extends WP_Widget {
	function __construct() {
		parent::WP_Widget('AdBanner_widget', "Ad banner widget", array('description'=>"Use HTML to put ad server code in here."));
	}
	function widget($args, $instance) {
		extract($args);
		$markup = $instance['markup'];
		//echo $before_widget;
		if ($markup) $markup;
		//echo $after_widget;
	}
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['markup'] = $new_instance['markup'];
		return $instance;
	}
	function form($instance) {
		if ($instance) $markup = esc_attr($instance['markup']);
		else $markup = __('Replace this with the HTML code from the ad server that will display the ad.', 'markup_widget'); ?>
		<p>
			<label for="<?php echo $this->get_field_id('markup'); ?>"><?php _e('Markup/text'); ?></label><br />
			<textarea class="widefat" id="<?php echo $this->get_field_id('markup'); ?>" name="<?php echo $this->get_field_name('markup'); ?>" type="text" rows="16" cols="20" value="<?php echo $markup; ?>"><?php echo $markup; ?></textarea>
		</p>

<?php }
}


/*


function include_post_types_in_search($query) {
	if(is_search()) {
		$post_types = get_post_types(array('public' => true, 'exclude_from_search' => false), 'objects');
		$searchable_types = array();
		if($post_types) {
			foreach( $post_types as $type) {
				$searchable_types[] = $type->name;
			}
		}
		$query->set('post_type', $searchable_types);
	}
	return $query;
}
add_action('pre_get_posts', 'include_post_types_in_search');





function filter_search($query) {
    if ($query->is_search) {
	$query->set('post_type', array('post', 'property'));
    };
    return $query;
};
add_action('pre_get_posts', 'filter_search');

*/





/**
 * Adds support for a custom header image.
 */
require( get_template_directory() . '/inc/custom-header.php' );

/**
 * Enqueues scripts and styles for front-end.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_scripts_styles() {
	global $wp_styles;

	/*
	 * Adds JavaScript to pages with the comment form to support
	 * sites with threaded comments (when in use).
	 */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	/*
	 * Adds JavaScript for handling the navigation menu hide-and-show behavior.
	 */
	wp_enqueue_script( 'twentytwelve-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '1.0', true );

	/*
	 * Loads our special font CSS file.
	 *
	 * The use of Open Sans by default is localized. For languages that use
	 * characters not supported by the font, the font can be disabled.
	 *
	 * To disable in a child theme, use wp_dequeue_style()
	 * function mytheme_dequeue_fonts() {
	 *     wp_dequeue_style( 'twentytwelve-fonts' );
	 * }
	 * add_action( 'wp_enqueue_scripts', 'mytheme_dequeue_fonts', 11 );
	 */

	/* translators: If there are characters in your language that are not supported
	   by Open Sans, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Open Sans font: on or off', 'twentytwelve' ) ) {
		$subsets = 'latin,latin-ext';

		/* translators: To add an additional Open Sans character subset specific to your language, translate
		   this to 'greek', 'cyrillic' or 'vietnamese'. Do not translate into your own language. */
		$subset = _x( 'no-subset', 'Open Sans font: add new subset (greek, cyrillic, vietnamese)', 'twentytwelve' );

		if ( 'cyrillic' == $subset )
			$subsets .= ',cyrillic,cyrillic-ext';
		elseif ( 'greek' == $subset )
			$subsets .= ',greek,greek-ext';
		elseif ( 'vietnamese' == $subset )
			$subsets .= ',vietnamese';

		$protocol = is_ssl() ? 'https' : 'http';
		$query_args = array(
			'family' => 'Open+Sans:400italic,700italic,400,700',
			'subset' => $subsets,
		);
		wp_enqueue_style( 'twentytwelve-fonts', add_query_arg( $query_args, "$protocol://fonts.googleapis.com/css" ), array(), null );
	}

	/*
	 * Loads our main stylesheet.
	 */
	wp_enqueue_style( 'twentytwelve-style', get_stylesheet_uri() );

	/*
	 * Loads the Internet Explorer specific stylesheet.
	 */
	wp_enqueue_style( 'twentytwelve-ie', get_template_directory_uri() . '/css/ie.css', array( 'twentytwelve-style' ), '20121010' );
	$wp_styles->add_data( 'twentytwelve-ie', 'conditional', 'lt IE 9' );
}
add_action( 'wp_enqueue_scripts', 'twentytwelve_scripts_styles' );

/**
 * Creates a nicely formatted and more specific title element text
 * for output in head of document, based on current view.
 *
 * @since Twenty Twelve 1.0
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string Filtered title.
 */
function twentytwelve_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() )
		return $title;

	// Add the site name.
	$title .= get_bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 )
		$title = "$title $sep " . sprintf( __( 'Page %s', 'twentytwelve' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'twentytwelve_wp_title', 10, 2 );

/**
 * Makes our wp_nav_menu() fallback -- wp_page_menu() -- show a home link.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_page_menu_args( $args ) {
	if ( ! isset( $args['show_home'] ) )
		$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'twentytwelve_page_menu_args' );

/**
 * Registers our main widget area and the front page widget areas.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_widgets_init() {
/*	register_sidebar( array(
		'name' => __( 'Main Sidebar', 'twentytwelve' ),
		'id' => 'sidebar-1',
		'description' => __( 'Appears on posts and pages except the optional Front Page template, which has its own widgets', 'twentytwelve' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
*/
	register_sidebar( array(
		'name' => __( 'Footer Terms Area', 'twentytwelve' ),
		'id' => 'footerterms',
		'description' => __( 'Drop in footer for Terms', 'twentytwelve' ),
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '',
		'after_title' => '',
	) );


	register_sidebar( array(
		'name' => __( 'Homepage Widget Area', 'twentytwelve' ),
		'id' => 'home-3',
		'description' => __( 'Homepage widgets', 'twentytwelve' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );


	register_sidebar( array(
		'name' => __( 'Content page right column', 'twentytwelve' ),
		'id' => 'contentpage-1',
		'description' => __( 'Content page widgets appear on the right side of all content pages (about, etc.).', 'twentytwelve' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
        
    register_sidebar( array(
		'name' => __( 'Apply Now Page', 'twentytwelve' ),
		'id' => 'applynow-1',
		'description' => __( 'Content page widgets appear on the right side of all content pages (about, etc.).', 'twentytwelve' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	
	register_sidebar( array(
		'name' => __( 'Market Page', 'twentytwelve' ),
		'id' => 'marketpage-1',
		'description' => __( 'Content page widgets appear on the right side of all content pages (about, etc.).', 'twentytwelve' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}
add_action( 'widgets_init', 'twentytwelve_widgets_init' );
require_once('inc/cleanwidget.php');

if ( ! function_exists( 'twentytwelve_content_nav' ) ) :
/**
 * Displays navigation to next/previous pages when applicable.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_content_nav( $html_id ) {
	global $wp_query;

	$html_id = esc_attr( $html_id );

	if ( $wp_query->max_num_pages > 1 ) : ?>
		<nav id="<?php echo $html_id; ?>" class="navigation" role="navigation">
			<h3 class="assistive-text"><?php _e( 'Post navigation', 'twentytwelve' ); ?></h3>
			<div class="nav-previous alignleft"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'twentytwelve' ) ); ?></div>
			<div class="nav-next alignright"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'twentytwelve' ) ); ?></div>
		</nav><!-- #<?php echo $html_id; ?> .navigation -->
	<?php endif;
}
endif;

if ( ! function_exists( 'twentytwelve_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own twentytwelve_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
		// Display trackbacks differently than normal comments.
	?>
	<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
		<p><?php _e( 'Pingback:', 'twentytwelve' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( '(Edit)', 'twentytwelve' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
		// Proceed with normal comments.
		global $post;
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<header class="comment-meta comment-author vcard">
				<?php
					echo get_avatar( $comment, 44 );
					printf( '<cite class="fn">%1$s %2$s</cite>',
						get_comment_author_link(),
						// If current post author is also comment author, make it known visually.
						( $comment->user_id === $post->post_author ) ? '<span> ' . __( 'Post author', 'twentytwelve' ) . '</span>' : ''
					);
					printf( '<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
						esc_url( get_comment_link( $comment->comment_ID ) ),
						get_comment_time( 'c' ),
						/* translators: 1: date, 2: time */
						sprintf( __( '%1$s at %2$s', 'twentytwelve' ), get_comment_date(), get_comment_time() )
					);
				?>
			</header><!-- .comment-meta -->

			<?php if ( '0' == $comment->comment_approved ) : ?>
				<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'twentytwelve' ); ?></p>
			<?php endif; ?>

			<section class="comment-content comment">
				<?php comment_text(); ?>
				<?php edit_comment_link( __( 'Edit', 'twentytwelve' ), '<p class="edit-link">', '</p>' ); ?>
			</section><!-- .comment-content -->

			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'twentytwelve' ), 'after' => ' <span>&darr;</span>', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
		</article><!-- #comment-## -->
	<?php
		break;
	endswitch; // end comment_type check
}
endif;

if ( ! function_exists( 'twentytwelve_entry_meta' ) ) :
/**
 * Prints HTML with meta information for current post: categories, tags, permalink, author, and date.
 *
 * Create your own twentytwelve_entry_meta() to override in a child theme.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_entry_meta() {
	// Translators: used between list items, there is a space after the comma.
	$categories_list = get_the_category_list( __( ', ', 'twentytwelve' ) );

	// Translators: used between list items, there is a space after the comma.
	$tag_list = get_the_tag_list( '', __( ', ', 'twentytwelve' ) );

	$date = sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a>',
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);

	$author = sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'twentytwelve' ), get_the_author() ) ),
		get_the_author()
	);

	// Translators: 1 is category, 2 is tag, 3 is the date and 4 is the author's name.
	if ( $tag_list ) {
		$utility_text = __( 'This entry was posted in %1$s and tagged %2$s on %3$s<span class="by-author"> by %4$s</span>.', 'twentytwelve' );
	} elseif ( $categories_list ) {
		$utility_text = __( 'This entry was posted in %1$s on %3$s<span class="by-author"> by %4$s</span>.', 'twentytwelve' );
	} else {
		$utility_text = __( 'This entry was posted on %3$s<span class="by-author"> by %4$s</span>.', 'twentytwelve' );
	}

	printf(
		$utility_text,
		$categories_list,
		$tag_list,
		$date,
		$author
	);
}
endif;

/**
 * Extends the default WordPress body class to denote:
 * 1. Using a full-width layout, when no active widgets in the sidebar
 *    or full-width template.
 * 2. Front Page template: thumbnail in use and number of sidebars for
 *    widget areas.
 * 3. White or empty background color to change the layout and spacing.
 * 4. Custom fonts enabled.
 * 5. Single or multiple authors.
 *
 * @since Twenty Twelve 1.0
 *
 * @param array Existing class values.
 * @return array Filtered class values.
 */
function twentytwelve_body_class( $classes ) {
	$background_color = get_background_color();

	if ( ! is_active_sidebar( 'sidebar-1' ) || is_page_template( 'page-templates/full-width.php' ) )
		$classes[] = 'full-width';

	if ( is_page_template( 'page-templates/front-page.php' ) ) {
		$classes[] = 'template-front-page';
		if ( has_post_thumbnail() )
			$classes[] = 'has-post-thumbnail';
		if ( is_active_sidebar( 'sidebar-2' ) && is_active_sidebar( 'sidebar-3' ) )
			$classes[] = 'two-sidebars';
	}

	if ( empty( $background_color ) )
		$classes[] = 'custom-background-empty';
	elseif ( in_array( $background_color, array( 'fff', 'ffffff' ) ) )
		$classes[] = 'custom-background-white';

	// Enable custom font class only if the font CSS is queued to load.
	if ( wp_style_is( 'twentytwelve-fonts', 'queue' ) )
		$classes[] = 'custom-font-enabled';

	if ( ! is_multi_author() )
		$classes[] = 'single-author';

	return $classes;
}
add_filter( 'body_class', 'twentytwelve_body_class' );

/**
 * Adjusts content_width value for full-width and single image attachment
 * templates, and when there are no active widgets in the sidebar.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_content_width() {
	if ( is_page_template( 'page-templates/full-width.php' ) || is_attachment() || ! is_active_sidebar( 'sidebar-1' ) ) {
		global $content_width;
		$content_width = 960;
	}
}
add_action( 'template_redirect', 'twentytwelve_content_width' );

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @since Twenty Twelve 1.0
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 * @return void
 */
function twentytwelve_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
}
add_action( 'customize_register', 'twentytwelve_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_customize_preview_js() {
	wp_enqueue_script( 'twentytwelve-customizer', get_template_directory_uri() . '/js/theme-customizer.js', array( 'customize-preview' ), '20120827', true );
}
add_action( 'customize_preview_init', 'twentytwelve_customize_preview_js' );




/*
* WCK custom fields
* $meta_value - array of arrays with wck meta box fields
* [set_wc_field(array(array("zip","11111","street_address","123 main street")))]
* Added 6-27-2013 by Jeremiah
*/
function set_wc_field($meta_value = array()){

	$fields_to_serialize = array();
	if (!empty($meta_value)){
		foreach ($meta_value as $key => $fields) {
			if (!empty($fields)){
				$field_to_serialize = array();
				foreach ($fields as $k => $val) {
					if ($k % 2 == 0){
						if (!empty($fields[$k+1])){
							$field_to_serialize[$val] = $fields[$k+1];								
						}
						else{
							$field_to_serialize[$val] = "";
						}
					}
				}
				$fields_to_serialize[] = $field_to_serialize;
			}
		}
	}	
	return serialize($fields_to_serialize);

}
add_action("pmxi_update_post_meta", "update_wc_field", 10, 3);
function update_wc_field($pid, $key, $value){
	if ($key == "propdetails" and "" != $value){		
		$new_value = unserialize($value);		
		if (is_array($new_value)) update_post_meta($pid, $key, $new_value);
	}
}

function pull_meta($x) {
	global $post;
	
	$s = get_post_meta( $post->ID, $x, true );
	$s2 = empty($s) ? array() : array_pop($s);
	return $s2;
}



//SHORTCODES FOR PULLQUOTE AND IMAGE INSERT
//Added 7/3/2013 by Jeremiah Lewis
function pullQuote() {
    $pullquotetxt = pull_meta('pullquote');
    $pullquote_text = $pullquotetxt['pullquote-text'];
    $pullquote_text = '<blockquote class="pullquote"><span class="lq"><img src="'.get_option('siteurl').'/wp-content/themes/ihcorp/img/leftquotes.png" /></span><p>'.$pullquote_text.'</p><span class="rq"><img src="'.get_option('siteurl').'/wp-content/themes/ihcorp/img/rightquotes.png" /></span></blockquote>';
    return $pullquote_text;
}
add_shortcode('pullquote', 'pullQuote');

function insertImage() {
    $pullquotetxt = pull_meta('pullquote');
    if($pullquotetxt['image-insert']) {
        $src = wp_get_attachment_image( $pullquotetxt['image-insert'], 'full' );
    }
    return '<div class="insertimg">'.$src.'</div>';
}
add_shortcode('insert_image', 'insertImage'); 


//Custom sortable columns for Properties
//Added 7/2/2013 by Jeremiah Lewis
//Add sorting column to Properties display based on Market
add_filter( 'manage_edit-property_columns', 'my_edit_property_columns' ) ;
function my_edit_property_columns( $columns ) {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Property Address' ),
		'market' => __( 'Market' ),
                'featured' => __( 'Featured' ),
		'date' => __( 'Date' )
	);
	return $columns;
}
//Pull in data to market column
add_action( 'manage_property_posts_custom_column', 'my_manage_property_columns', 10, 2 );
function my_manage_property_columns( $column, $post_id ) {
	global $post;
	switch( $column ) {

		/* If displaying the 'market' column. */
		case 'market' :
			/* Get the market(s) for the post. */
			$terms = get_the_terms( $post_id, 'market' );

			/* If terms were found. */
			if ( !empty( $terms ) ) {

				$out = array();

				/* Loop through each term, linking to the 'edit posts' page for the specific term. */
				foreach ( $terms as $term ) {
					$out[] = sprintf( '<a href="%s">%s</a>',
						esc_url( add_query_arg( array( 'post_type' => $post->post_type, 'market' => $term->slug ), 'edit.php' ) ),
						esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'market', 'display' ) )
					);
				}

				/* Join the terms, separating them with a comma. */
				echo join( ', ', $out );
			}

			/* If no terms were found, output a default message. */
			else {
				_e( 'No Markets' );
			}

			break;
                    
                case 'featured' :
                        $isfeatured = get_post_meta($post_id, "featured-property", true);
                        if($isfeatured == 'on') {
			  echo 'Yes';
		        } else {
			  echo 'No';
		        }
                    
                        break;

		/* Just break out of the switch statement for everything else. */
		default :
			break;
	}
}
//Make column sortable
add_filter( 'manage_edit-property_sortable_columns', 'my_property_sortable_columns' );
function my_property_sortable_columns( $columns ) {
	$columns['market'] = 'market';
        $columns['featured'] = 'featured';
	return $columns;
}

//Only use if on edit screen in admin
add_action( 'load-edit.php', 'my_edit_property_load' );
function my_edit_property_load() {
	add_filter( 'request', 'my_sort_properties' );
}
//Sorts the Properties
function my_sort_properties( $vars ) {
	// Check if we're viewing the 'property' post type. */
	if ( isset( $vars['post_type'] ) && 'property' == $vars['post_type'] ) {

		/* Check if 'orderby' is set to 'market'. */
		if ( isset( $vars['orderby'] ) && 'market' == $vars['orderby'] ) {

			/* Merge the query vars with our custom variables. */
			$vars = array_merge(
				$vars,
				array(
					'term_id' => 'market',
					'orderby' => 'asc'
				)
			);
		}
	} 
	return $vars;
}


//include_once('inc/taxonomy_filter.php');
//new Tax_CTP_Filter(array('property' => array('market')));

add_action('restrict_manage_posts','restrict_listings_by_market');
function restrict_listings_by_market() {
    global $typenow;
    global $wp_query;
    if ($typenow=='property') {
    $taxonomy = 'market';
    $term = isset($wp_query->query['market']) ? $wp_query->query['market'] :'';
    $market_taxonomy = get_taxonomy($taxonomy);
        wp_dropdown_categories(array(
            'show_option_all' =>  __("Show All"),
            'taxonomy'        =>  $taxonomy,
            'name'            =>  'market',
            'orderby'         =>  'name',
            'selected'        =>  $term,
            'hierarchical'    =>  true,
            'depth'           =>  3,
            'show_count'      =>  true, // Show # listings in ()
            'hide_empty'      =>  true, // Don't show markets w/o listings
        ));
    }
}

add_filter('parse_query','convert_market_id_to_taxonomy_term_in_query');
function convert_market_id_to_taxonomy_term_in_query($query) {
    global $pagenow;
    $qv = $query->query_vars;
    if ($pagenow=='edit.php' && isset($qv['market']) && is_numeric($qv['market'])) {
        $term = get_term_by('id',$qv['market'],'market');
        $qv['market'] = ($term ? $term->slug : '');
    }
}




//Filter by Featured
function restrict_articles_by_meta() {
    global $wpdb;
    global $typenow;
    if(isset($typenow) && $typenow != "" && $typenow == "property") {
    $meta_values = $wpdb->get_col("
        SELECT DISTINCT meta_value
        FROM ". $wpdb->postmeta ."
        WHERE meta_key = 'featured-property'
        ORDER BY meta_value
    ");
    ?>
    <select name="featured-property" id="featured">
        <option value="">All Featured</option>
        <?php foreach ($meta_values as $meta_value) { ?>
        <option value="<?php echo esc_attr( $meta_value ); ?>" <?php if(isset($_GET['featured-property']) && !empty($_GET['featured-property']) ) selected($_GET['featured-property'], $meta_value); ?>>
        <?php
        if($meta_value == 'on') {
          echo 'Featured';
        } else if ($meta_value == '') {
          echo 'Not Featured';  
        }
        ?>
        </option>
        <?php } ?>
    </select>
    <?php
}
}
add_action('restrict_manage_posts','restrict_articles_by_meta');



function posts_where_metavalue( $where ) {
    if( is_admin() ) {
        global $wpdb;       
        if ( isset( $_GET['featured-property'] ) && !empty( $_GET['featured-property'] )) {
            $meta_number = $_GET['featured-property'];

            $where .= " AND ID IN (SELECT post_id FROM " . $wpdb->postmeta ." 
WHERE meta_key='featured-property' AND meta_value='$meta_number' )";
        }
    }   
    return $where;
}
add_filter( 'posts_where' , 'posts_where_metavalue' );