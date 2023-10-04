<?php
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://2bytecode.com/
 * @since      1.0.0
 *
 * @package    Digital_Asset_Manager
 * @subpackage Digital_Asset_Manager/public/partials
 */

get_header();

$query_obj = get_queried_object();

if ( $query_obj instanceof WP_Post_Type ) {

	$archive_page_of = 'post';
	$current_term    = '';
	global $wp_query;
	$loop = $wp_query;

} elseif ( $query_obj instanceof WP_Term ) {

	$archive_page_of = 'term';
	$current_term    = $query_obj;

	// Custom Query for Assets Tags.
	$paged_no = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	$args     = array(
		'post_type'      => 'dam-asset', // Your post type name.
		'posts_per_page' => get_option( 'posts_per_page' ), // phpcs:ignore
		'post_status'    => 'publish',
		'orderby'        => 'date',
		'order'          => 'DESC',
		'paged'          => $paged_no,
	);

	if ( 'term' === $archive_page_of ) :

		$args['tax_query'] = array( // phpcs:ignore
			array(
				'taxonomy' => $query_obj->taxonomy,
				'field'    => 'term_id',
				'terms'    => $query_obj->term_id,
			),
		);

	endif;

	$loop = new WP_Query( $args );
}

?>

<section class="container">
	<div class="row">

		<div class="col-sm-5 col-md-3 dam-sidebar">
			<?php require_once DAM_PUBLIC_PATH . 'templates/sidebar-asset.php'; ?>
		</div>

		<div class="col-sm-7 col-md-9 dam-content">

			<?php

			if ( $loop->have_posts() ) {
				ob_start();
				?>

				<div class="row">
					<div class="row-sm-12">
						<div class="alert alert-danger" role="alert">
							<h2>Disclaimer!</h2>
							<p>We are not reselling, redistributing, or releasing license keys. All plugins and themes are 100% authentic and not modified in any sense. Some plugins or themes may require keys to work. Feel free to use for learning and testing purposes. If you want to use it on a live domain, we suggest you buy the license from their authors.</p>
							<p><a href="#hire-me" class="" data-lightbox="inline" itemprop="url"><strong>Contact us</strong></a> to install and configure it on your live site <strong>only in $49</strong>.</p>
						</div>
					</div>

					<?php
					while ( $loop->have_posts() ) :
						$loop->the_post();
						$asset_id        = get_the_ID();
						$asset_permalink = get_post_permalink( $asset_id );
						$asset_title     = get_the_title();
						$asset_desc      = get_the_content();
						$publish_date    = get_the_date( 'M d, y' );
						$update_date     = get_the_modified_date( 'M d, y' );
						$th_comments     = get_comments_number();
						$version         = get_post_meta( $asset_id, 'asset_version', true );
						$views           = get_post_meta( $asset_id, 'total_view', true );
						$downloads       = get_post_meta( $asset_id, 'total_download', true );

						$post_cats        = get_the_terms( $asset_id, 'asset-type' );
						$post_cats_output = '';
						if ( ! empty( $post_cats ) ) {
							foreach ( $post_cats as $post_cat ) {
								$post_cats_output .= '<a class="dam-asset-cat-link" href="' . esc_url( get_category_link( $post_cat->term_id ) ) . '" target="_self" title="' . $post_cat->name . '"><span class="dam-asset-type theme">' . $post_cat->name . '</span></a>';
							}
						}

						$post_tags        = get_the_terms( $asset_id, 'asset-tags' );
						$post_tags_output = '';
						$counts           = is_array( $post_tags ) ? count( $post_tags ) : 1;

						if ( ! empty( $post_tags ) ) {
							foreach ( $post_tags as $post_tag ) {
								$post_tags_output .= '<a class="dam-asset-tag-link" href="' . esc_url( get_category_link( $post_tag->term_id ) ) . '"  target="_self" title="' . $post_tag->name . '"><span class="dam-asset-type theme">' . $post_tag->name . '</span></a>';
								if ( $counts > 1 ) {
									$post_tags_output .= ', ';
								}
							}
						}

						$thumbnail_id = get_post_thumbnail_id( $asset_id );
						$alt          = get_post_meta( $thumbnail_id, '_wp_attachment_image_alt', true );

						?>
						<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

							<div class="dam-asset-grid">
								<div class="dam-asset-image">
									<a class="dam-asset-link" href="<?php echo esc_url( $asset_permalink ); ?>" title="<?php echo esc_attr( $asset_title ); ?>" target="_self" >
										<div class="dam-asset-image-alt" style="background-image: url('<?php echo esc_url( get_the_post_thumbnail_url( $asset_id, 'full' ) ); ?>')">
										</div>
										<img class="asset-image img-fluid" src="<?php echo esc_url( get_the_post_thumbnail_url( $asset_id, 'full' ) ); ?>" alt="<?php echo esc_attr( $asset_title ); ?>" />
									</a>
									<div class="dam-asset-types">
										<?php echo wp_kses_post( $post_cats_output ); ?>
									</div>
									<ul class="dam-asset-detail">
										<li><i class="fa-solid fa-eye"></i> <?php echo esc_html( $views ); ?></li>
										<li><i class="fa-solid fa-cloud-arrow-down"></i> <?php echo esc_html( $downloads ); ?></li>
										<li><i class="fa-solid fa-comments"></i> <?php echo esc_html( $th_comments ); ?></li>
									</ul>
									<a class="dam-asset-download" href="<?php echo esc_url( $asset_permalink ); ?>" title="<?php echo esc_attr( $asset_title ); ?>" target="_self">
										Download
									</a>
								</div>
								<div class="dam-asset-content">
									<p class="dam-asset-meta">
										<span><i class="fa-solid fa-code-fork"></i><?php echo esc_html( $version ); ?></span>
										<span class="dam-seperator"></span>
										<span><i class="fa-regular fa-clock"></i><?php echo esc_html( $publish_date ); ?></span>
										<span class="dam-seperator"></span>
										<span><i class="fa-regular fa-pen-to-square"></i><?php echo esc_html( $update_date ); ?></span>
									</p>
									<h2 class="asset-title"><a href="<?php echo esc_url( $asset_permalink ); ?>"><?php echo esc_html( $asset_title ); ?></a></h2>

									<?php if ( ! empty( $post_tags_output ) ) : ?>
										<p class="dam-asset-tags">
											<i class="fa-solid fa-tags"></i>
											<?php echo wp_kses_post( $post_tags_output ); ?>
										</p>
									<?php endif; ?>

								</div>
							</div>
						</div>
					<?php endwhile; ?>
				</div>

				<div class="row dam-pagination">
					<div class="col">

						<nav class="pagination-outer" aria-label="Page navigation">
							<?php
							$total_pages = $loop->max_num_pages;
							if ( $total_pages > 1 ) :

								echo wp_kses_post(
									paginate_links(
										array(
											'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
											'total'        => $total_pages,
											'current'      => max( 1, get_query_var( 'paged' ) ),
											'format'       => '?page=%#%',
											'show_all'     => false,
											'type'         => 'plain',
											'end_size'     => 1,
											'mid_size'     => 1,
											'prev_next'    => true,
											'prev_text'    => sprintf( '<span aria-hidden="true"> %1$s <span>', '«' ),
											'next_text'    => sprintf( '<span aria-hidden="true"> %1$s <span>', '»' ),
											'type'         => 'list',
											'add_args'     => false,
											'add_fragment' => '',
										)
									)
								);

							endif;
							?>
						</nav>
					</div>
				</div>
				<?php
				$query_content_html = ob_get_clean();
				echo wp_kses_post( $query_content_html );
			}
			?>

		</div>
	</div>
</section>

<?php

get_footer();
