<?php

	$search_text=apply_filters( 'the_search_query', get_search_query() );
	$button_text = esc_attr(apply_filters( 'genesis_search_button_text', esc_attr__( 'Search', 'genesis' ) ));
	$onfocus = "onfocus=\"if ('" . esc_js( $search_text ) . "' === this.value) {this.value = '';}\"";
	$onblur  = "onblur=\"if ('' === this.value) {this.value = '" . esc_js( $search_text ) . "';}\"";
	$onchange  = "onchange=\"this.form.elements['tribe-bar-search'].value=this.value;\"";

?>
<form role="search" method="get" class="search-form" id="searchform" action="<?php echo home_url( '/' ); ?>">
    <div>
    	<!--//<label class="screen-reader-text" for="s">find: </label>//-->
		<div style="position:relative; margin-bottom:4px;"><input type="hidden" name="tribe-bar-search" value="" /><input type="text" value="<?php print esc_attr($search_text); ?>" name="s" class="s" placeholder="Search for..." <?php print $onchange; ?>/><input name="submit" type="submit" class="searchsubmit" value="Search" /></div>
		<!--//<label class="screen-reader-text" for="post_type">in: </label>//-->
		<select id="post_type" name="post_type">
			<option value="tribe_events" <?php if (isset($_GET['post_type']) && $_GET['post_type']=='tribe_events') print'selected'; ?>>in Events</option>
			<option value="media" <?php if (isset($_GET['post_type']) && $_GET['post_type']=='media') print'selected'; ?>>in Media</option>
			<option value="page" <?php if (isset($_GET['post_type']) && $_GET['post_type']=='page') print'selected'; ?>>in Website</option>
			<option value="any" <?php if (isset($_GET['post_type']) && $_GET['post_type']=='any') print'selected'; ?>>Everywhere</option>
		</select><br/>
    </div>
</form>