<?php
/*
	Template Name: Campaign Page
	@author  Stephan Carydakis
*/

if ( !defined('ABSPATH') ) { die('-1'); }

//remove unwanted page assets
foreach (Array('genesis_footer_widget_areas', 'genesis_do_breadcrumbs', 'genesis_footer_markup_open', 'genesis_footer_markup_close', 'scwd_genesis_footer') as $k)
{
	if (($h=scwd_find_hook_by_function($k,'genesis')) !== false)
	{
		remove_action($h['tag'], $h['function'], $h['priority'], $h['accepted_args']);
	}
}

// force full width layout
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

add_action('genesis_before','scwd_cp_wrap_open');
add_action('genesis_after','scwd_cp_wrap_close');
function scwd_cp_wrap_open()
{
	print '<div class="cp-wrap">';
}
function scwd_cp_wrap_close()
{
	print '</div>';
}

genesis();