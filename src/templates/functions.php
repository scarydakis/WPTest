<?PHP

// load Google libraries
set_include_path(dirname(__FILE__) . PATH_SEPARATOR . get_include_path());
require_once('Google/Client.php');

//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );
include_once(dirname(__FILE__) . '/countries.php');

$post_format_friendly=array(
	'gallery'	=> 'Image Gallery',
	'quote'		=> 'Review',
	'video'		=> 'Video',
	'status'	=> 'Press Clip'
);

$scwd_social_media_pages = Array(
	'facebook' => Array(
		'title' => 'facebook',
		'url' => 'https://www.facebook.com/melbournecitycaravans/'
	),
	/*'twitter' => Array(
		'title' => 'Twitter',
		'url' => 'https://twitter.com/'
	),*/
	'gplus' => Array(
		'title' => 'Google+',
		'url' => 'https://plus.google.com/104181724200700331164'
	),
	'youtube' => Array(
		'title' => 'YouTube',
		'url' => 'https://www.youtube.com/channel/UClgudAeAI16okh7PPn3RmAA'
	),
	'rss' => Array(
		'title' => 'products rss feed',
		'url' => '/feed/?post_type=scwd_product'
	)
);


//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', 'Stephan Carydakis Custom Theme' );
define( 'CHILD_THEME_VERSION', '1.0.0' );


// theme globals
$num_home_widgets=8;
$num_active_widgets=Array(
	'home' => -1,
	'genesis_footer' => -1
);
$scwd_blog_timezone_str=get_option('timezone_string');

//* Add HTML5 markup structure
add_theme_support( 'html5' );

//* Add support for 3-column footer widgets
add_theme_support( 'genesis-footer-widgets', 3 );

// Genesis strucural wraps
add_theme_support( 'genesis-structural-wraps', array( 'header', 'subnav', 'inner', 'footer-widgets', 'footer' ) );

// wrap structural wrap 'header' in a custom element used to 'float' it.
add_filter('genesis_structural_wrap-header','scwd_header_wrap',20,2);
function scwd_header_wrap($content, $original_output)
{
	if ($original_output==='open')
	{
		return '<div class="float-me">' . $content;
	}
	else
	{
		return $content . '</div>';
	}
}

//add_filter('list_cats','scwd_list_cats');
function scwd_list_cats($catname, $catobj)
{
	return $catname;
}

//* Add support for post formats
add_theme_support( 'post-formats', array(
	'aside',
	'audio',
	'chat',
	'gallery',
	'image',
	'link',
	'quote',
	'status',
	'video'
));

// post thumbnails
add_theme_support( 'post-thumbnails' );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );


// Page type attributes to attachments - IE menu order etc...
add_post_type_support( 'attachment', 'page-attributes' );

// add post formats to pages
add_post_type_support( 'page', array('post-formats','excerpt'));

// custom image size for image gallery thumbs
add_image_size('Image Gallery' , 150, 100, false);

// custom image size - panoramic
add_image_size('Panoramic Large' , 1080, 348, array('center','center'));

// custom image size for Facebook share
add_image_size('Social Share' , 1200, 628, array('center','center'));

// run shortcodes in various places
add_filter('wpseo_canonical', 'do_shortcode',2500);
add_filter('wpseo_metadesc', 'do_shortcode',2500);
add_filter('the_title', 'do_shortcode',2500);
add_filter('wp_title', 'do_shortcode',2500);
add_filter('widget_text', 'shortcode_unautop');
add_filter('widget_text', 'do_shortcode',2500);
add_filter('term_description', 'shortcode_unautop');
add_filter('term_description', 'do_shortcode' );
add_filter('genesis_term_intro_text_output', 'do_shortcode' );
add_filter('the_excerpt', 'do_shortcode');
add_filter('get_the_excerpt', 'do_shortcode');

/* begin allow HTML in category descriptions etc */
remove_filter( 'pre_term_description', 'wp_filter_kses' );
remove_filter( 'pre_link_description', 'wp_filter_kses' );
remove_filter( 'pre_link_notes', 'wp_filter_kses' );
remove_filter( 'term_description', 'wp_kses_data' );
/* end allow HTML in category descriptions etc */

// remove Genesis search form. Our template will be used.
remove_filter( 'get_search_form', 'genesis_search_form' );

// remove site description
remove_action( 'genesis_site_description', 'genesis_seo_site_description' );

// enqueue scripts / css
add_action( 'wp_enqueue_scripts', 'scwd_enqueue_scripts' );
function scwd_enqueue_scripts()
{
	if (!is_admin())
	{
		wp_enqueue_style('scwd-fonts', '//fonts.googleapis.com/css?family=Open+Sans:400,600,700,600italic,400italic,700italic,300italic', array('dashicons'), CHILD_THEME_VERSION );
		wp_enqueue_script('jquery');
		wp_enqueue_script('underscore');
		wp_enqueue_script('scwd-plugins', get_bloginfo('stylesheet_directory') . '/js/plugins.min.js', array('jquery'), CHILD_THEME_VERSION, true);
		wp_enqueue_script('scwd-onload', get_bloginfo('stylesheet_directory') . '/js/onload.min.js', array('scwd-plugins'), CHILD_THEME_VERSION, true);
		wp_enqueue_script('scwd-deferred', get_bloginfo('stylesheet_directory') . '/js/deferred.min.js', array('scwd-onload'), CHILD_THEME_VERSION, true);
	}
}

// enqueue admin scripts / css
add_action( 'admin_enqueue_scripts', 'scwd_admin_enqueue_scripts' );
function scwd_admin_enqueue_scripts()
{
	wp_enqueue_style('scwd-admin-custom', get_bloginfo('stylesheet_directory') . '/admin_styles.css', array(), CHILD_THEME_VERSION );
	wp_enqueue_script('scwd-admin-deferred', get_bloginfo('stylesheet_directory') . '/js/admin-deferred.min.js', array(), CHILD_THEME_VERSION, true);
}

add_action('widgets_init', 'scwd_widgets_init');
function scwd_widgets_init()
{
	global $num_home_widgets;

	// home feature widget area
	genesis_register_sidebar(array(
		'name'=>'Home Feature Area',
		'id' => 'home-feature',
		'description' => 'This is the home feature widget area which appears under the header and before the home page content.',
		'before_widget' => '<div id="%1$s" class="widget %2$s">', 'after_widget'  => '</div>',
		'before_title'=>'<h4 class="widget-title">','after_title'=>'</h4>'
	));

	// register homepage widget areas
	$i=0;
	for ($i=1; $i<=$num_home_widgets;$i++)
	{
		$description='This is the ' . scwd_ordinal($i) . ' column of the homepage widget area.';
		genesis_register_sidebar(array(
			'name'=>'Home Widget Area #' . $i,
			'id' => 'hf-' . $i,
			'description' => $description,
			'before_widget' => '<div id="%1$s" class="widget %2$s">', 'after_widget'  => '</div>',
			'before_title'=>'<h4 class="widget-title">','after_title'=>'</h4>'
		));
	}
}

// favicon. change querystring to force refresh in browsers
add_filter('genesis_pre_load_favicon', 'scwd_favicon' );
function scwd_favicon($favicon) {
	$favicon .= get_bloginfo('stylesheet_directory') . '/favicon.png?v=1';
    return $favicon;
}

//add_action('template_redirect','scwd_force_login');
function scwd_force_login()
{
	if (!is_user_logged_in())
	{
		global $wp;
		$requested_url = home_url( $wp->request );
		print $requested_url;

		$url = add_query_arg(
			'redirect_to',
			$requested_url,
			site_url('wp-login.php')
		);
		wp_redirect($url);
		exit;
	}
}
add_filter('wp_seo_get_bc_ancestors', 'test');
function test($ancestors)
{
	exit(0);
	print_r($ancestors);
	return $ancestors;
}
// run shortcodes when on the edit terms admin page (editing categories or terms in custom taxonomies)
add_action('load-edit-tags.php','scwd_maybe_do_shortcodes');
function scwd_maybe_do_shortcodes()
{
	$action=!empty($_REQUEST['action']) ? $_REQUEST['action'] : '';
	if (empty($action))
	{
		add_filter('get_terms','scwd_terms_shortcodes',10,3);
	}
}
function scwd_terms_shortcodes($terms, $taxonomies, $args)
{
	foreach ($terms as $term)
	{
		if (is_object($term))
		{
			$term->description=wpautop(do_shortcode($term->description));
		}
	}
	return $terms;
}

// modify WP's core PHPMailer instance
//add_action( 'phpmailer_init', 'scwd_set_phpm_defaults',1,1);
function scwd_set_phpm_defaults($phpmailer)
{
	$phpmailer->SMTPKeepAlive=true;
	$phpmailer->Hostname='melbournecitycaravans.com.au';
	$phpmailer->debug=1;
	$phpmailer->SMTPDebug=3;
	$phpmailer->Debugoutput='error_log';
	print '<pre>' . print_r($phpmailer,true) . '</pre>';
	exit(0);
}

// Encode email addresses
if (function_exists('eae_encode_emails'))
{
	// The Events Calendar organiser email address
	//add_filter('tribe_get_organizer_email','eae_encode_emails');
}

// remove empty p tags in content. fix for empty p's after shortcodes
add_filter('the_content', 'scwd_remove_empty_p', 10);
function scwd_remove_empty_p($content)
{
	global $post;
	$tags = array (
		'<p>[' => '[',
		']</p>' => ']',
		']<br />' => ']',
		']<br />' => ']'
	);
	$content = strtr($content, $tags);
	return $content;
}

// add 'breadcrumb' item property to breadcrum markup
add_action('genesis_attr_breadcrumb', 'scwd_breadcrumb_microdata');
function scwd_breadcrumb_microdata($attr)
{
	$attr['itemprop'] = 'breadcrumb';
	return $attr;
}

// remove whitespace between tags in content. for using inline-block.
add_filter('the_content', 'scwd_remove_whitespace_in_content', 99999);
function scwd_remove_whitespace_in_content($content)
{
	$content = scwd_remove_whitespace_between_tags($content);
	return $content;
}

// customise primary navigation location / output
remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action('genesis_header_right','genesis_do_nav');
add_filter('genesis_do_nav','scwd_do_nav', 20, 3);
add_filter('genesis_do_subnav','scwd_do_nav', 20, 3);
function scwd_do_nav($nav_output, $nav, $args )
{
	global $SCWD_CUSTOM;
	$pre=$post='';
	if ($args['theme_location']==='primary')
	{
		if ( is_singular($SCWD_CUSTOM->get_product_post_type_name()) || is_tax($SCWD_CUSTOM->get_product_taxonomy_name()) || is_tax($SCWD_CUSTOM->get_product_tag_taxonomy_name()) || is_post_type_archive($SCWD_CUSTOM->get_product_post_type_name()) )
		{
			$nav_output=str_replace('product-master', 'current-menu-ancestor', $nav_output);
		}
		$pre = '<div class="mobile-menu hoverable"><div class="bar"><div class="mobile-menu-logo"><a href="/" title="' . get_bloginfo('name', 'display') . '"></a></div><div class="mobile-menu-icon"></div></div><div class="menu-wrap">';
		$post = '</div></div>';
	}

	if ($args['theme_location']==='secondary')
	{
	}
	return $pre . scwd_remove_whitespace_between_tags($nav_output) . $post;
}

 // First, we remove all the RSS feed links from wp_head using remove_action
  //remove_action( 'wp_head','feed_links', 2 );
remove_action( 'wp_head','feed_links_extra', 3 );

// We then need to reinsert the main RSS feed by using add_action to call our function
//add_action( 'wp_head', 'reinsert_rss_feed', 1 );
// This function will reinsert the main RSS feed *after* the others have been removed
//function reinsert_rss_feed() {
//    echo '<link rel="alternate" type="application/rss+xml" title="' . get_bloginfo('sitename') . ' &raquo; RSS Feed" href="' . get_bloginfo('rss2_url') . '" />';
//}

// Force archive pages to use the title and description if a custom one is not defined.
add_filter('genesis_term_meta', 'scwd_term_meta', 20, 2);
function scwd_term_meta($term_meta, $term)
{

	if (!is_admin() && !is_singular())
	{
		if (empty($term->meta['headline']))
		{
			$term_meta['headline']=$term->name;
		}
		if (empty($term->meta['intro_text']))
		{
			$term_meta['intro_text']=$term->description;
		}
	}
	return $term_meta;
}

// add tax image to term
add_filter('genesis_term_intro_text_output','scwd_term_image');
function scwd_term_image($str='')
{
	$arrImageArgs=array(
		'image_size'	=> 'Panoramic Large',
		'before'		=> '<div class="tax-image">',
		'after'			=> '</div>'
	);

	$str = apply_filters( 'taxonomy-images-queried-term-image', '', $arrImageArgs ) . $str;
	return $str;
}

// add tax image to single
add_action('genesis_entry_header','scwd_single_do_tax_image',11);
function scwd_single_do_tax_image($title)
{
	global $post, $SCWD_CUSTOM;
	if (is_single())
	{
		global $post;
		// to do: get tax dynamically
		$tax='category';
		$image_size='Panoramic Large';

		if (false !== ($image_id=$SCWD_CUSTOM->acf_get_field('_feature_image')))
		{
			print '<div class="tax-image">' . get_image_tag($image_id, $post->post_title, $post->post_title, '', $image_size) . '</div>';
		}
		else
		{
			print apply_filters( 'taxonomy-images-list-the-terms', '', array(
				'before'       => '',
				'after'        => '',
				'before_image' => '<div class="tax-image">',
				'after_image'  => '</div>',
				'image_size'   => $image_size,
				'taxonomy'     => $tax
			));
		}
	}
}

// previews return a 404 when the post_format parameter is on the qs so tata, see-you, bye...
add_filter( 'preview_post_link', 'scwd_remove_preview_post_format_parameter', 9999 );
function scwd_remove_preview_post_format_parameter($url)
{
	return remove_query_arg( 'post_format', $url );
}

// do various stuff on the 'wp' hook
add_action('wp', 'scwd_wp');
function scwd_wp()
{

}

// set comments and trackbacks for new posts
add_filter('wp_insert_post_data', 'scwd_comments_trackbacks_for_new',99999,2);
function scwd_comments_trackbacks_for_new($data, $postarr)
{
	if ($data['post_status']==='auto-draft')
	{
		if ($data['post_type']==='page')
		{
			$data['comment_status'] = 'closed';
			$data['ping_status'] = 'open';
		}
	}
	return $data;
}

// custom Genesis footer
remove_action( 'genesis_footer', 'genesis_do_footer' );
add_action( 'genesis_footer', 'scwd_genesis_footer' );
function scwd_genesis_footer($strFooter)
{
	$creds_text =  do_shortcode('[scwd-smp]') . '<div class="copyright">Copyright [footer_copyright] ' . get_bloginfo('sitename') . '</div>';
	print do_shortcode($creds_text);
}

// Gravity Forms init scripts in footer
add_filter("gform_init_scripts_footer", "init_scripts");
function init_scripts()
{
	return true;
}

// Gravity Forms stop auto scroll after submission
add_filter("gform_confirmation_anchor", create_function("","return false;"));

// custom the_content_limit wrapper. uses scwd_trim_excerpt.
add_filter('get_the_content_limit','scwd_get_the_content_limit',20,4);
function scwd_get_the_content_limit($output, $content, $link, $max_characters)
{
	return scwd_trim_excerpt('', $max_characters);
}

// custom excerpt trimmer. if text if empty, will use post content as excerpt
remove_filter('get_the_excerpt', 'wp_trim_excerpt');
add_filter('get_the_excerpt', 'scwd_trim_excerpt');
add_filter('get_the_excerpt', 'scwd_more_link',40);
function scwd_trim_excerpt($text='', $length=0)
{
	global $post;
	//printr_f($post);
	if ($length===0) $length=apply_filters('scwd_excerpt_length',(int)genesis_get_option('content_archive_limit'));

	if ( '' === $text )
	{
		$text = get_the_content();
		$text = apply_filters('the_content', $text);
	}

	$excerpt_length = $length;
	$text = str_replace('\]\]\>', ']]&gt;', $text);
	$text = preg_replace('@<script[^>]*?>.*?</script>@si', '', $text);
	$text = strip_tags($text, apply_filters('scwd_excerpt_allowed_tags', '<p><span><div><a><br></br>'));
	$text_length=mb_strlen($text);
	$text = genesis_truncate_phrase( $text, $excerpt_length );
	if ($text)
	{
		if  ($text_length > $excerpt_length)
		{
			$text .= '...';
		}
	}
	return $text;
}
function scwd_more_link($content='')
{
	$more_link_text=apply_filters('scwd_read_more_text', '[read more]');
	$more_link_title_text=apply_filters('scwd_read_more_title_text', 'Read more about: ');
	$more_link='<span class="scwd-read-more"><a href="' . get_permalink() . '" title="' . esc_attr($more_link_title_text) . esc_attr(get_the_title()) . '">' . $more_link_text . '</a></span>';
	$more_link=apply_filters('scwd_excerpt_more', $more_link, $more_link_text, $more_link_title_text);
	return $content . $more_link;
}

// custom site title
add_filter('genesis_seo_title','scwd_custom_site_title', 10, 3);
function scwd_custom_site_title($title, $inside, $wrap)
{
	$displayText=get_bloginfo('name') . '<br/><span class="description">' . get_bloginfo('description') . '</span>';
	$titleText=get_bloginfo('name');

	//* Set what goes inside the wrapping tags
	$inside = sprintf( '<a href="%s" title="%s">%s</a>', trailingslashit( home_url() ),  esc_attr($titleText), $displayText );
	$title  = genesis_html5() ? sprintf( "<{$wrap} %s>", genesis_attr( 'site-title' ) ) : sprintf( '<%s id="title">%s</%s>', $wrap, $inside, $wrap );
	$title .= genesis_html5() ? "{$inside}</{$wrap}>" : '';
	return $title;
}

// add title attr to attachment images
add_filter( 'wp_get_attachment_image_attributes', 'scwd_add_title_to_attachment_image', 10, 2);
function scwd_add_title_to_attachment_image($attr, $attachment)
{
    $attr['title'] = esc_attr($attachment->post_title);
    return $attr;
}

// adds custom image sizes to media uploader chooser
add_filter('image_size_names_choose', 'scwd_display_image_size_names_muploader', 11, 1);
function scwd_display_image_size_names_muploader( $sizes )
{
	$new_sizes = array();

	$added_sizes = get_intermediate_image_sizes();

	// $added_sizes is an indexed array, therefore need to convert it
	// to associative array, using $value for $key and $value
	foreach($added_sizes as $key => $value) {
		$new_sizes[$value] = $value;
	}

	// This preserves the labels in $sizes, and merges the two arrays
	$new_sizes = array_merge( $new_sizes, $sizes );

	return $new_sizes;
}

// add a human readable css class representing the number of active genesis footer widgets
add_filter('genesis_attr_footer-widgets','scwd_genesis_footer_widgets_css_class',20,2);
function scwd_genesis_footer_widgets_css_class($attrs, $context)
{
	global $num_active_widgets;
	$footer_widgets=get_theme_support('genesis-footer-widgets');
	if (is_array($footer_widgets) && isset($footer_widgets[0]) && is_numeric($footer_widgets[0]))
	{
		$str_num_active=scwd_readNumber(scwd_num_active_widgets('footer-', $footer_widgets[0], $num_active_widgets['genesis_footer']));
		if (!empty($attrs['class']))
		{
			$attrs['class'] .= ' ' . $str_num_active;
		}
		else
		{
			$attrs['class']=$str_num_active;
		}

	}
	return $attrs;
}

// maybe remove post meta
//add_action('genesis_before_entry','scwd_maybe_remove_post_meta');
function scwd_maybe_remove_post_meta()
{
	global $post;

	remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
	if ('gallery' === get_post_format($post->ID))
	{
		remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
	}
	else
	{
		add_action( 'genesis_entry_footer', 'genesis_post_meta' );
	}
}

remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );


// custom post info
add_filter('genesis_post_info','scwd_custom_post_info');
function scwd_custom_post_info($postinfo)
{
	global $post, $post_format_friendly;

	$post_format=get_post_format($post->ID);
	if (false !== $post_format && array_key_exists($post_format, $post_format_friendly))
	{
		$postinfo=do_shortcode('[scwd-custom-postinfo]');
	}
	return $postinfo;
}

// custom post info shortcode
add_shortcode('scwd-custom-postinfo','scwd_shortcode_custom_postinfo');
function scwd_shortcode_custom_postinfo($attr)
{
	global $post, $post_format_friendly;

	extract(shortcode_atts(array(
		'date'      => 'true',
	), $attr));

	$postinfo='';
	$post_format=get_post_format($post->ID);
	if (false !== $post_format && array_key_exists($post_format, $post_format_friendly))
	{
		$friendlyName=$post_format_friendly[$post_format];
		switch ($friendlyName)
		{
			case 'Image Gallery':
				$postinfo = do_shortcode('[scwd-gallery-postinfo date="' . $date . '"]');
				break;
			case 'Review':
				$postinfo = do_shortcode('[scwd-review-postinfo date="' . $date . '"]');
				break;
			case 'Video':
				$postinfo = do_shortcode('[scwd-video-postinfo date="' . $date . '"]');
				break;
			case 'Press Clip':
				$postinfo = do_shortcode('[scwd-pressclip-postinfo date="' . $date . '"]');
				break;
		}
	}
	return $postinfo;
}


// shortcode to produce post info for 'gallery' format posts
add_shortcode('scwd-gallery-postinfo','scwd_shortcode_gallery_postinfo');
function scwd_shortcode_gallery_postinfo($attr)
{
	global $post;

	extract(shortcode_atts(array(
		'date'      => 'true',
	), $attr));

	$retstr='';
	$image_gallery = scwd_get_associated_media_library();
	if ($image_gallery !== false)
	{
		$retstr = 'Image Gallery with ' . $image_gallery->count . ' images' . ($date !== 'false' ? ' - ' . do_shortcode('[post_date format="l F j, Y"]') : '.');
	}
	return $retstr;
}

// shortcode to produce post info for 'review' format posts - use for reviews
add_shortcode('scwd-review-postinfo','scwd_shortcode_review_postinfo');
function scwd_shortcode_review_postinfo($attr)
{
	global $post, $SCWD_CUSTOM;
	extract(shortcode_atts(array(
		'date'      => 'true',
	), $attr));

	$retstr='';
	$date = DateTime::createFromFormat('Ymd', $SCWD_CUSTOM->acf_get_field('review_date'));
	$name = $SCWD_CUSTOM->acf_get_field('reviewer_name');
	$link = $SCWD_CUSTOM->acf_get_field('reviewer_link');
	if ($link)
	{
		$name = '<a href="' . $link . '" target="_blank">' . $name . '</a>';
	}
	$retstr = 'Reviewed by ' . $name . ' on ' . $date->format('l F j, Y') . '.';
	return $retstr;
}

// shortcode to produce post info for 'video' format posts
add_shortcode('scwd-video-postinfo','scwd_shortcode_video_postinfo');
function scwd_shortcode_video_postinfo($attr)
{
	global $post;

	extract(shortcode_atts(array(
		'date'      => 'true',
	), $attr));

	$video_data=get_post_meta($post->ID, '_scwd_video_data', true);
	$retstr = 'Video posted on ' . ($date !== 'false' ? do_shortcode('[post_date format="l F j, Y"]') : '') . ".";
	if (!empty($video_data))
	{
		$dt = new DateTime();
		$dt->add(new DateInterval($video_data['duration']));
		$interval = $dt->diff(new DateTime());
		$retstr .= ' Format: ' . strtoupper($video_data['definition']);
		$retstr .= ', Runtime: ' . $interval->format('%Hh %Im %Ss');
		//printr_f($video_data);
	}
	return $retstr;
}


// shortcode to produce post info for 'status' format posts - used for Press Clips
add_shortcode('scwd-pressclip-postinfo','scwd_shortcode_pressclip_postinfo');
function scwd_shortcode_pressclip_postinfo($attr)
{
	global $post, $SCWD_CUSTOM;
	extract(shortcode_atts(array(
		'date'      => 'true',
	), $attr));

	$retstr='';
	$date = DateTime::createFromFormat('Ymd',  $SCWD_CUSTOM->acf_get_field('_press_clip_date'));
	$name = $SCWD_CUSTOM->acf_get_field('_press_clip_publication_name');
	$link = $SCWD_CUSTOM->acf_get_field('_press_clip_link');
	if ($link)
	{
		$name = '<a href="' . $link . '" target="_blank">' . $name . '</a>';
	}
	$retstr = 'Press Clip as published in &#8216;' . $name . '&#8217; on ' . $date->format('l F j, Y') . '.';
	return $retstr;
}

// Get YouTube video data on save_post action and save to post
//add_action('save_post', 'scwd_save_video_data',1,1000);
function scwd_save_video_data($postid)
{
	if (function_exists('get_field') && !wp_is_post_revision($postid) && get_post_format($postid) === 'video')
	{
		$pm='';
		$ytid=scwd_get_youtube_details($postid);
		if (is_array($ytid))
		{
			if ($ytid['saved'] !==  $ytid['posted'])
			{
				if (!empty($ytid['posted']))
				{
					require_once('Google/Service/YouTube.php');
					$client = new Google_Client();
					$client->setDeveloperKey('AIzaSyC1vzJnDvMhjxIhptA46AuGsXmRRzAKHow');
					$service = new Google_Service_YouTube($client);
					$video=$service->videos->listVideos('contentDetails,statistics',Array('id'=> $ytid['posted']));
					$pm=array_merge(get_object_vars($video->items[0]['contentDetails']), get_object_vars($video->items[0]['statistics']));
				}
				//printr_f($ytid);
				//printr_f($pm);
				//exit(0);
				update_post_meta($postid, '_scwd_video_data', $pm);
			}
		}
	}
}

// shortcode to embed youtube video
add_shortcode('scwd-youtube', 'scwd_shortcode_youtube');
function scwd_shortcode_youtube($atts)
{
	static $numIframes=0;
	extract(shortcode_atts(array(
		'id'		=> '',
		'width'		=> '794',
		'height'	=> '447',
		'type'		=> 'text/html'
	), $atts));


	if (empty($id)) return '[scwd-youtube] !Empty id parameter.';

	$width=(int)$width;
	$height=(int)$height;

	if ($width>0 && $height>0)
	{
		$aspectRatio=($height/$width)*100;
	}
	else
	{
		return '[scwd-youtube] !Invalid width/height parameters.';
	}
	$numIframes++;
	$src="https://www.youtube.com/embed/" . $id;
	$src=scwd_build_querystring($src,Array('wmode'=>'transparent', 'rel'=>'0'));
	return '<div class="video-wrap-outer"><div class="video-wrap" style="max-width:' . $width . 'px;"><div class="aspect" style="padding-top:' . $aspectRatio . '%"><iframe id="scwdyt-' . $numIframes . '" type="' . $type . '" allowfullscreen frameborder="0"  src="' . $src . '" width="' . $width . '" height="' . $height . '"></iframe></div></div></div>';
}

// social media pages shortcode
add_shortcode('scwd-smp', 'scwd_shortcode_social_media_pages');
function scwd_shortcode_social_media_pages($atts)
{
	global $scwd_social_media_pages;
	extract(shortcode_atts(array(
		'cssclass' => '',
		'align' => 'align-center',
		), $atts));

	if (!empty($cssclass))
	{
		$cssclass = ' ' . $cssclass;
	}
	$strRet = '<div class="smp ' . $align . '"><div class="inner">';
	foreach ($scwd_social_media_pages as $k => $v)
	{
		$title=$v['title'];
		$url=$v['url'];
		$strRet .= '<div class="icon ' . $k . $cssclass . '"><a title="' . $title . '" href="' . $url . '" target="_new"><span class="underlay"></span><span class="overlay"></span></a></div>';
	}
	$strRet .= '</div></div>';
	return $strRet;
}

/*------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	BEGIN: Custom Gallery Shortcode. Copied Whollus Bollus from Wordpress and customised
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
add_filter('post_gallery','scwd_gallery_shortcode',999,2);
/**
 * The Gallery shortcode.
 *
 * This implements the functionality of the Gallery Shortcode for displaying
 * WordPress images on a post.
 *
 * @since 2.5.0
 *
 * @param array $attr Attributes of the shortcode.
 * @return string HTML content to display gallery.
 */
function scwd_gallery_shortcode($strEmpty, $attr) {
	global $post;

	static $instance = 0;
	$instance++;

	// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
	if ( isset( $attr['orderby'] ) ) {
		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
		if ( !$attr['orderby'] )
			unset( $attr['orderby'] );
	}

	extract(shortcode_atts(array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post->ID,
		'galleryid'  => '',
		'itemtag'    => 'dl',
		'icontag'    => 'dt',
		'captiontag' => 'dd',
		'columns'    => 3,
		'size'       => 'thumbnail',
		'include'    => '',
		'exclude'    => ''
	), $attr));

	//SC: customisation: override size for thumbnail
	if ($size=='thumbnail') $size='Image Gallery';

	$id = intval($id);
	if ( 'RAND' == $order )
		$orderby = 'none';

	if (!empty($galleryid))
	{
		if ($galleryid==='associated')
		{
			$assocated_gallery=scwd_get_associated_media_library();
			if ($assocated_gallery!==false)
			{
				$galleryid=$assocated_gallery->term_id;
			}
			else
			{
				$galleryid=0;
			}
		}

		$args = array(
		    'post_status' => 'inherit',
			'posts_per_page' => -1,
			'post_mime_type' => 'image',
			'orderby' => $orderby,
			'order' => $order,
			'post_type' => 'attachment',
			'tax_query' => array(
				array(
				    'taxonomy' => 'media_category',
				    'terms' => array($galleryid),
				    'field' => 'id'
				)
			)
		);
		$_attachments = get_posts( $args );
		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif ( !empty($include) ) {
		$include = preg_replace( '/[^0-9,]+/', '', $include );
		$args=array(
			'include' => $include,
			'post_status' => 'inherit',
			'post_type' => 'attachment',
			'post_mime_type' => 'image',
			'order' => $order,
			'orderby' => $orderby
		);
		if ($orderby==='none')
		{
			$args['orderby']='post__in';
			$args['post__in']=$include;
		}
		$_attachments = get_posts($args);

		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif ( !empty($exclude) ) {
		$exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
		$attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	} else {
		$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	}

	if ( empty($attachments) )
		return '';

	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $att_id => $attachment )
			$output .= wp_get_attachment_link($att_id, $size, true) . "\n";
		return $output;
	}

	$itemtag = tag_escape($itemtag);
	$captiontag = tag_escape($captiontag);
	$columns = intval($columns);
	$itemwidth = $columns > 0 ? floor(100/$columns) : 100;
	$float = is_rtl() ? 'right' : 'left';

	$selector = "gallery-{$instance}";

	$gallery_style = $gallery_div = '';
	if ( apply_filters( 'use_default_gallery_style', true ) )
		$gallery_style = "
		<style type='text/css'>
			#{$selector} {
				margin: auto;
			}
			#{$selector} .gallery-item {
				float: {$float};
				margin-top: 10px;
				text-align: center;
				width: {$itemwidth}%;
			}
			#{$selector} img {
				border: 2px solid #cfcfcf;
			}
			#{$selector} .gallery-caption {
				margin-left: 0;
			}
		</style>
		<!-- see gallery_shortcode() in wp-includes/media.php -->";
	$size_class = sanitize_html_class( $size );
	$gallery_div = "<div id='$selector' class='gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class}'>";
	$output = apply_filters( 'gallery_style', $gallery_style . "\n\t\t" . $gallery_div );

	$i = 0;
	foreach ( $attachments as $id => $attachment ) {
		$link = isset($attr['link']) && 'file' == $attr['link'] ? wp_get_attachment_link($id, $size, false, false) : wp_get_attachment_link($id, $size, true, false);

		$output .= "<{$itemtag} class='gallery-item'>";
		$output .= "
			<{$icontag} class='gallery-icon'>
				$link
			</{$icontag}>";
		if ( $captiontag && trim($attachment->post_excerpt) ) {
			// SC: customisation: added htmlentities() around caption so fotorama shows html in captions. only uses htmlentities() when fotorama is active
			$caption=wptexturize($attachment->post_excerpt);
			if (function_exists('fotorama_gallery_shortcode') && !(array_key_exists('fotorama', $attr) && $attr['fotorama'] == 'false'))
			{
				$caption=htmlentities($attachment->post_excerpt);
			}
			$output .= "<{$captiontag} class='wp-caption-text gallery-caption'>{$caption}</{$captiontag}>";
		}
		$output .= "</{$itemtag}>";
		if ( $columns > 0 && ++$i % $columns == 0 )
			$output .= '<br style="clear: both" />';
	}

	$output .= "
			<br style='clear: both;' />
		</div>\n";

	return $output;
}
/*------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	END: Custom Gallery Shortcode. Copied Whollus Bollus from Wordpress and customised
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

// adds querystring paramaters from $params to the url $url
function scwd_build_querystring($url='', $params=Array())
{
	if (empty($url) || empty($params)) return '';

	$concat = "?";
	if (strpos($url, "?") !== false)
	{
	    $concat = "&amp;";
	}
	$url .= $concat . http_build_query($params, '', '&amp;');
	return $url;
}

// shortcode wrapper for WP get_bloginfo() function
add_shortcode('scwd-bloginfo', 'scwd_shortcode_bloginfo');
function scwd_shortcode_bloginfo($atts)
{
	extract(shortcode_atts(array(
			'show' => '',
			'attr' => '',
			'extra' => '',
			'filter' => 'raw'
		), $atts
	));

	if (empty($show)) return '';

	$strRet='';
	$allowedFilters=Array('raw', 'display');
	if (!in_array($filter,$allowedFilters))
	{
		$filter='raw';
	}
	$strRet .= get_bloginfo($show,$filter);
	if (!empty($attr))
	{
		$strRet = $attr . '="' . htmlentities($strRet);
		if (!empty($extra))
		{
			$strRet .= htmlentities($extra);
		}
		$strRet .= '"';
	}
	return $strRet;
}

// shortcode to get a post field
add_shortcode('scwd-post-field', 'scwd_shortcode_get_post_field');
function scwd_shortcode_get_post_field($atts)
{
	global $post;

	extract(shortcode_atts(array(
		'field' => 'post_content',
		'postid' => $post->ID,
		'context' => 'display'
	),$atts));

	$strRet=get_post_field($field, $postid, $context);
	if (is_wp_error($strRet))
	{
		$strRet='';
	}

	return $strRet;
}

/*------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	BEGIN: Helper functions
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
// gets the associated media gallery object
function scwd_get_associated_media_library()
{
	global $post, $SCWD_CUSTOM;
	return $SCWD_CUSTOM->acf_get_field('associated_media_library');
}

// helper. get the Buffer publishing rule for given post or global $post if not passed to function.
function scwd_get_buffer_action($p=NULL)
{
	global $post;
	if (!function_exists('get_field')) return '';
	if (is_null($p)) $p=$post;
	$fieldId='field_542e1e289e8c7';
	if ('campaign-page-template.php' === get_page_template_slug($pid))
	{
		return 'never';
	}
	else
	{
		return ( (isset($_POST['fields']) && isset($_POST['fields'][$fieldId]) ) ? $_POST['fields'][$fieldId] : get_field($fieldId, $p->ID));
	}
}

// helper. get the YouTube video details from given post or global $post if not passed to function.
function scwd_get_youtube_details($p=NULL)
{
	global $post;

	$arrRet=array(
		'saved' => '',
		'posted' => ''
	);

	if (!function_exists('get_field')) return $arrRet;

	if (is_null($p))
	{
		$p=$post;
	}
	elseif (is_int($p))
	{
		$p=get_post($p);
	}

	if (get_post_format($p->ID) !== 'video') return $arrRet;

	$fieldId='field_5432315a063e0';
	$arrRet['saved']=get_field($fieldId, $p->ID);
	$arrRet['posted'] = isset($_POST['fields']) && isset($_POST['fields'][$fieldId]) ? $_POST['fields'][$fieldId] : '';
	return $arrRet;
}

// make the home widget area
function scwd_home_widgets()
{
	global $num_home_widgets, $num_active_widgets;
	$cssClassNameNumberWidgets=scwd_readNumber(scwd_num_active_widgets('hf-', $num_home_widgets, $num_active_widgets['home']));

	if ($num_active_widgets['home'] < 1) return;

	print '<div id="scwd-home-widgets" class="' . $cssClassNameNumberWidgets . '"><div class="wrap clearfix">';
	for ($i=0; $i < $num_home_widgets; $i++)
	{
		if (is_active_sidebar('hf-' . ($i+1)))
		{
			print '<div class="hf-widgets-' . ($i+1) . ' widget-area">';
			dynamic_sidebar('hf-' . ($i+1));
			print '</div>';
		}
	}
	print '</div></div>';
}
// get the number of active widgets
function scwd_num_active_widgets($prefix='', $numAvailable=0, &$properyNumberActive, $unsetTestVal=-1)
{
	$numAvailable=(int)$numAvailable;
	if ($properyNumberActive===$unsetTestVal && !empty($prefix) && $numAvailable > 0)
	{
		$numActive=0;
		for ($i=0; $i < $numAvailable; $i++)
		{
			$numActive=$numActive + ( is_active_sidebar($prefix . ($i+1)) ? 1 : 0 );
		}
		$properyNumberActive=$numActive;
	}
	return $properyNumberActive;
}
// Gets the ordinal for a number
function scwd_ordinal($number=false, $return_number=true)
{
	if ($number===false) return false;
    $ends = array('th','st','nd','rd','th','th','th','th','th','th');
    $mod100 = $number % 100;
    return ($return_number ? $number : '') . ($mod100 >= 11 && $mod100 <= 13 ? 'th' :  $ends[$number % 10]);
}
/**
 Converts an integer to its textual representation.
 @param num the number to convert to a textual representation
 @param depth the number of times this has been recursed
*/
function scwd_readNumber($num, $depth=0)
{
    $num = (int)$num;
    $retval ="";
    if ($num < 0) // if it's any other negative, just flip it and call again
        return "negative " + scwd_readNumber(-$num, 0);
    if ($num > 99) // 100 and above
    {
        if ($num > 999) // 1000 and higher
            $retval .= scwd_readNumber($num/1000, $depth+3);

        $num %= 1000; // now we just need the last three digits
        if ($num > 99) // as long as the first digit is not zero
            $retval .= scwd_readNumber($num/100, 2)." hundred\n";
        $retval .= scwd_readNumber($num%100, 1); // our last two digits
    }
    else // from 0 to 99
    {
        $mod = floor($num / 10);
        if ($mod == 0) // ones place
        {
            if ($num == 1) $retval.="one";
            else if ($num == 2) $retval.="two";
            else if ($num == 3) $retval.="three";
            else if ($num == 4) $retval.="four";
            else if ($num == 5) $retval.="five";
            else if ($num == 6) $retval.="six";
            else if ($num == 7) $retval.="seven";
            else if ($num == 8) $retval.="eight";
            else if ($num == 9) $retval.="nine";
        }
        else if ($mod == 1) // if there's a one in the ten's place
        {
            if ($num == 10) $retval.="ten";
            else if ($num == 11) $retval.="eleven";
            else if ($num == 12) $retval.="twelve";
            else if ($num == 13) $retval.="thirteen";
            else if ($num == 14) $retval.="fourteen";
            else if ($num == 15) $retval.="fifteen";
            else if ($num == 16) $retval.="sixteen";
            else if ($num == 17) $retval.="seventeen";
            else if ($num == 18) $retval.="eighteen";
            else if ($num == 19) $retval.="nineteen";
        }
        else // if there's a different number in the ten's place
        {
            if ($mod == 2) $retval.="twenty ";
            else if ($mod == 3) $retval.="thirty ";
            else if ($mod == 4) $retval.="forty ";
            else if ($mod == 5) $retval.="fifty ";
            else if ($mod == 6) $retval.="sixty ";
            else if ($mod == 7) $retval.="seventy ";
            else if ($mod == 8) $retval.="eighty ";
            else if ($mod == 9) $retval.="ninety ";
            if (($num % 10) != 0)
            {
                $retval = rtrim($retval); //get rid of space at end
                $retval .= "-";
            }
            $retval .= scwd_readNumber($num % 10, 0);
        }
    }

    if ($num != 0)
    {
        if ($depth == 3)
            $retval.=" thousand\n";
        else if ($depth == 6)
            $retval.=" million\n";
        if ($depth == 9)
            $retval.=" billion\n";
    }
    return $retval;
}

// strip html, javascript etc
function html2text($text='', $replaceWith='')
{
	if (empty($text)) return $text;
	$search = array(
		'@<script[^>]*?>.*?</script>@si',  // Strip out javascript
		'@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
		'@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
		'@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments including CDATA
	);
	return preg_replace($search,  $replaceWith, $text);
}

// get an int from a string
function int($s){return(int)preg_replace('/[^\-\d]*(\-?\d*).*/','$1',$s);}

// removes whitespace bewtween tags. Especially usefull when display:inline-block is used
function scwd_remove_whitespace_between_tags($strIn='')
{
	if (!empty($strIn))
	{
		return preg_replace('/>\s+</', "><", $strIn);
	}
	else
	{
		return $strIn;
	}
}

// print filter functions for particular hook
function print_filters_for( $hook = '' ) {
    global $wp_filter;
    if( empty( $hook ) || !isset( $wp_filter[$hook] ) )
        return;

    print '<pre>';
    print_r( $wp_filter[$hook] );
    print '</pre>';
}

// get the first attached image of a post including the featured image.
// default precendence places the featured image last. To change the precedence so the featured image is considered first, pass anything but 'content' as the arg $first
// returns wp_get_attachment_image_src() if found or false if not found.
function scwd_get_post_first_attached_image($pid=0, $imgSize='', $first='content')
{
	global $post;
	if ($pid===0) $pid=$post->ID;
	if (empty($imgSize)) $imgSize=genesis_get_option('image_size');
	$order=($first==='content' ? 'ASC' : 'DESC');
	$thumb=false;

	if ($first==='featured')
	{
		$thumb=wp_get_attachment_image_src(get_post_thumbnail_id($pid), $imgSize, false);
	}
	if ($thumb===false)
	{
		$imageArgs = array(
			'numberposts' => 1,
			'order' => $order,
			'post_mime_type' => 'image',
			'post_parent' => $pid,
			'post_status' => null,
			'post_type' => 'attachment',
		);
		$images = get_children($imageArgs);
		if (count($images) > 0)
		{
			foreach ($images as $img)
			{
				$thumb=wp_get_attachment_image_src($img->ID, $imgSize, false); //'Footer/Home Featured Boxes'
			}
		}
	}
	return $thumb;
}

function scwd_find_hook_by_function($functionName='', $filter=false)
{
	global $wp_filter;
	$found=false;
	foreach($wp_filter as $tag => $hook )
	{
	    if ( false === $filter || false !== strpos( $tag, $filter ) )
	    {
			if (($found=in_array_r($functionName, $hook, true)) !== false)
			{
				//print $tag . '<br>' . '<pre>' . print_r($hook,ture) . '</pre><br><br>';
				//printr_f($tag);
				//printr_f($found);
				$found=array_merge(
					Array(
						'tag' => $tag,
						'priority' => $found['key']
					),
					(array)$found['slice'][$functionName]
				);
				break;
			}
		}
	}
	return $found;
}

// recursive in_array()
function in_array_r($needle, $haystack, $strict = false)
{
	if (is_array($haystack))
	{
		foreach ($haystack as $key => $item)
		{
			if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict)))
			{

				return array('key'=>$key, 'slice'=>$item);
			}
		}
	}

	return false;
}

// formatted print_r()
function printr_f($thingIn='', $print=true)
{
	$strRet= '<pre>' . print_r($thingIn, true) . '</pre>';
	if ($print)
	{
		print $strRet;
	}
	else
	{
		return $strRet;
	}
}

// stop wp removing div tags
function ikreativ_tinymce_fix( $in )
{
    // html elements being stripped
    $in['extended_valid_elements'] = '+*[*]';
	/*$in['cleanup_callback'] = '';
	$in['apply_source_formatting'] = false;
	$in['convert_fonts_to_spans'] = false;
	$in['entity_encoding'] = 'raw';
	$in['verify_html'] = false;*/
    return $in;
}
add_filter('tiny_mce_before_init', 'ikreativ_tinymce_fix');