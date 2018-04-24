<?php
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function adm_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed at WordPress.org. See: https://translate.wordpress.org/projects/wp-themes/twentyseventeen
	 * If you're building a theme based on Twenty Seventeen, use a find and replace
	 * to change 'twentyseventeen' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'dietmeapp' );


	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );


// 	// This theme uses wp_nav_menu() in two locations.
// 	register_nav_menus( array(
// 		'top'    => __( 'Top Menu', 'twentyseventeen' ),
// 		'social' => __( 'Social Links Menu', 'twentyseventeen' ),
// 	) );

}
add_action( 'after_setup_theme', 'adm_setup' );

/**
 * Enqueue scripts and styles.
 */
function adm_scripts() {
	// Add custom fonts, used in the main stylesheet.
	wp_enqueue_style( 'adm-bootstrap', get_theme_file_uri( '/assets/bower_components/bootstrap/dist/css/bootstrap.min.css' ), array(), null );
	wp_enqueue_style( 'adm-font', get_theme_file_uri( '/assets/bower_components/font-awesome/css/font-awesome.min.css' ), array(), null );
	wp_enqueue_style( 'adm-ionicons', get_theme_file_uri( '/assets/bower_components/Ionicons/css/ionicons.min.css' ), array(), null );
	wp_enqueue_style( 'adm-datatable', get_theme_file_uri( '/assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css' ), array(), null );
	wp_enqueue_style( 'adm-adminlte', get_theme_file_uri( '/assets/css/AdminLTE.min.css' ), array(), null );
	wp_enqueue_style( 'adm-sking', get_theme_file_uri( '/assets/css/skins/_all-skins.min.css' ), array(), null );
	wp_enqueue_style( 'adm-wysihtml5', get_theme_file_uri( '/assets/bower_components/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css' ), array(), null );

	wp_enqueue_script( 'adm-bootstrap', get_theme_file_uri( '/assets/bower_components/bootstrap/dist/js/bootstrap.min.js' ), array( 'jquery' ), '1.0', true );
	wp_enqueue_script( 'adm-datatable', get_theme_file_uri( '/assets/bower_components/datatables.net/js/jquery.dataTables.min.js' ), array( 'jquery' ), '1.0', true );
	wp_enqueue_script( 'adm-datatable-boot', get_theme_file_uri( '/assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js' ), array( 'jquery' ), '1.0', true );
	wp_enqueue_script( 'adm-slimscroll', get_theme_file_uri( '/assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js' ), array( 'jquery' ), '1.0', true );
	wp_enqueue_script( 'adm-fastclick', get_theme_file_uri( '/assets/bower_components/fastclick/lib/fastclick.js' ), array( 'jquery' ), '1.0', true );
	wp_enqueue_script( 'adm-adminlte', get_theme_file_uri( '/assets/js/adminlte.min.js' ), array( 'jquery' ), '1.0', true );
	wp_enqueue_script( 'adm-wysihtml5', get_theme_file_uri( '/assets/bower_components/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js' ), array( 'jquery' ), '1.0', true );
	
// 	wp_enqueue_script( 'adm-bootstrap', get_theme_file_uri( '/assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js' ), array( 'jquery' ), '1.0', true );

}
add_action( 'wp_enqueue_scripts', 'adm_scripts' );

// add_action( 'init', 'redirect_non_logged_users_to_specific_page' );

function redirect_non_logged_users_to_specific_page() {
// 	print_r($GLOBALS['pagenow']);
// 	exit;
	if ( !is_user_logged_in() && $GLOBALS['pagenow'] !=='wp-login.php' && $_SERVER['PHP_SELF'] != '/wp-admin/admin-ajax.php' ) {
		header('Location: ' . wp_login_url());
		exit;
	}
	
	if(is_user_logged_in()){
		
	}
	
}




function adm_current_user( $query ) {
	$current_user = wp_get_current_user();
	if ( !is_admin() && $query->is_main_query() ) {
	$query->set( 'author', $current_user->ID);
	}
}
// add_action( 'pre_get_posts', 'adm_current_user' );


