<?php
$wpautop_content = get_option('wpautop_content', 'off');
if ($wpautop_content === 'on'){
	remove_filter( 'the_content', 'wpautop' );
}
$wpautop_excerpt = get_option('wpautop_excerpt', 'off');
if ($wpautop_excerpt === 'on'){
	remove_filter( 'the_excerpt', 'wpautop' );
}
