<?php /* Template Name: Home */ ?>
<?php get_header(); ?>

<?php // echo adrotate_group(1); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	<div class="left">
		<?php the_content() ?>
	</div>
	<div class="main-image"><?php the_post_thumbnail( 'full' ); ?></div>
<?php endwhile; else: endif; ?>

<?php wp_reset_query(); ?>

<div class="featured-event">
	<h2 class="red">Featured Event</h2>
	<?php $featured_query = new WP_Query();
	$featured_query->query(array(
		'post_type' => 'tribe_events',
		'posts_per_page' => 1,
		'orderby' => 'rand',
		'tax_query' => array(
			array(
				'taxonomy' => 'tribe_events_cat',
				'field' => 'slug',
				'terms' => 'featured',
				'operator' => 'IN'
			),
		),
		'meta_query' => array(
			array(
				'key' => '_EventStartDate',
				'value' => date(TribeDateUtils::DBDATETIMEFORMAT),
				'compare' => '>',
			),
		),
	));

	// die(var_dump($featured_query));
	if ($featured_query->have_posts()) :
		while ( $featured_query->have_posts() ) : $featured_query->the_post(); ?>

			<div <?php post_class()?>>
				<h2 class="entry-title"><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h2>
				<div class="entry-content">
					<?php if ( has_post_thumbnail() ) {?><?php the_post_thumbnail('thumbnail'); ?><?php } ?>
					<p><?php echo TribeEvents::truncate(get_the_content(), 60); ?></p>
				</div>

				<div class="feature-list-meta">
					<dl class="meta">
						<!-- Where -->
						<?php $venue = tribe_get_venue(); if ( !empty( $venue ) ) : ?>
							<dt class="tribe-events-event-meta-desc"><?php _e('Where:', 'tribe-events-calendar') ?></dt>
							<dd class="tribe-events-event-meta-value" itemprop="name"><?php echo tribe_get_venue( get_the_ID() ) ?></dd>
						<?php endif; ?>
						<!-- When-->
						<?php if (tribe_is_multiday()): ?>
							<dt class="tribe-events-event-meta-desc"><?php _e('When:', 'tribe-events-calendar') ?></td>
							<dd class="tribe-events-event-meta-value" itemprop="date"><?php echo tribe_get_start_date(null,FALSE,'F jS'); ?> - <?php echo tribe_get_end_date(null,FALSE,'F jS'); ?>, <?php echo tribe_get_start_date(null,FALSE,'g:ia'); ?> - <?php echo tribe_get_end_date(null,FALSE,'g:ia'); ?></dd>
						<?php else: ?>
							<dt class="tribe-events-event-meta-desc"><?php _e('When:', 'tribe-events-calendar') ?></dt>
							<dd class="tribe-events-event-meta-value" itemprop="date"><?php echo tribe_get_start_date( null,FALSE,'F jS, g:ia' ); ?> - <?php echo tribe_get_end_date( null,FALSE,'g:ia' ); ?></dd>
						<?php endif; ?>
					</dl>
				</div>

			</div>
		<?php endwhile; ?>
	<?php endif; ?>
	<?php wp_reset_query();?>

</div>

<?php // echo adrotate_group(3); ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
