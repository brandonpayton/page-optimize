<?php

add_filter( 'page_optimize_debug', '__return_true' );

$page_optimize_done_style_items = array(
	'head' => array(),
	'footer' => array(),
);

add_action(
	'page_optimize_doing_style_items', function ( $handles, $group ) {
		global $page_optimize_done_style_items;
		$group_key = 1 === $group ? 'footer' : 'head';
		$page_optimize_done_style_items[ $group_key ][] = array_values( $handles );
	},
	10,
	2
);

add_action(
	'wp_footer',
	function () {
		global $page_optimize_done_style_items;

		$json = wp_json_encode(
			$page_optimize_done_style_items,
			JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
		);
		?>
		<script id="page-optimize-style-items" type="application/json">
			<?php echo esc_html( $json ); ?>
		</script>
		<?php
	},
	999
);

add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style( 'test-1', plugins_url( 'resources/t0.css', __FILE__ ) );
	wp_enqueue_style( 'test-2', plugins_url( 'resources/t1.css', __FILE__ ) );
	wp_enqueue_style( 'test-3-with-inline', plugins_url( 'resources/t2.css', __FILE__ ) );
	wp_add_inline_style( 'test-3-with-inline', '/* inline style */' );
	wp_enqueue_style( 'test-4', plugins_url( 'resources/t3.css', __FILE__ ) );
	wp_enqueue_style( 'test-5-with-inline', plugins_url( 'resources/t4.css', __FILE__ ) );
	wp_add_inline_style( 'test-5-with-inline', '/* inline style */' );
	wp_enqueue_style( 'test-6', plugins_url( 'resources/t5.css', __FILE__ ) );
} );

if ( ! empty( $_GET['exclude_from_css_concat'] ) ) {
	add_filter( 'pre_option_page_optimize-css-exclude', function () {
		return $_GET['exclude_from_css_concat'];
	} );
}
