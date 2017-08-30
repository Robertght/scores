<?php
get_header();
$args = array(
	'post_type' => 'badges',
	'numberposts' => -1,
);
$posts_array = get_posts( $args );
?>
<ul class="badge-list"><?php
	foreach ( $posts_array as $badge ) { ?>
		<li>
			<a href="<?php echo get_permalink( $badge->ID ); ?>">
				<?php echo get_the_post_thumbnail( $badge->ID ); ?>
				<h1><?php echo get_the_title( $badge->ID ); ?></h1>
			</a>
		</li>
	<?php } ?>
</ul>

<?php get_footer(); ?>