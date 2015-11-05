<?php
/*
	Template Name: Home Page
*/

// remove title on home page
//add_action('get_header', 'scwd_remove_page_titles',100);
function scwd_remove_page_titles()
{
	if (is_front_page())
	{
		remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
		remove_action('genesis_entry_header', 'genesis_do_post_title');
		remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );
	}
}

// remove Genesis footers widgets on home page
remove_action( 'genesis_before_footer', 'genesis_footer_widget_areas' );

add_action('genesis_before_footer','scwd_home_widgets',1);
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );
function custom_excerpt_length()
{
	return 10;
}

add_filter('genesis_attr_home-feature', 'scwd_attr_home_feature');
function scwd_attr_home_feature($attributes)
{
	$attributes['itemscope']='itemscope';
	$attributes['itemtype']='http://schema.org/CreativeWork';
	return $attributes;
}

remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_after_header', 'scwd_home_feature' ,1);
// home feature
function scwd_home_feature()
{
	global $post, $SCWD_CUSTOM;
	genesis_markup( array(
		'html5'   => '<article id="home-feature" %s>',
		'xhtml'   => '<div id="home-feature">',
		'context' => 'home-feature'
	) );

	if ( ($home_slides=$SCWD_CUSTOM->acf_get_field('_scwd_slides')) !== false)
	{
		$i=0;
		print '<div class="home-slides">';
		foreach ($home_slides as $value)
		{
			$att_data='';
			if ($i===0)
			{
				$attr_data=' style="background-image:url(\'' . $value['sizes']['large'] . '\');"';
			}
			else
			{
				$attr_data=' data-imgurl="' . $value['sizes']['large'] . '"';
			}
			print '<div class="slide"' . $attr_data . '></div>';
			$i++;
		}
		print '</div>';
	}

	print '<div class="wrap"><div class="inner">';
	print '<div class="left"></div><div class="right"><div class="pad">';
	//print the_title('<h1>','</h1>');
	print the_content();
	print '</div></div>';
	print '</div></div>';

	genesis_markup( array(
		'html5' => '</article>',
		'xhtml' => '</div>'
	) );
}
genesis();