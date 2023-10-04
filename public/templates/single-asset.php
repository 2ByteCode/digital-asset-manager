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

?>

<section class="container">
	<div class="row">
		<div class="col-sm-5 col-md-3 dam-sidebar">
			<?php require_once DAM_PUBLIC_PATH . 'templates/sidebar-asset.php'; ?>
		</div>

		<div class="col-sm-7 col-md-9 dam-content">
			<?php
			if ( have_posts() ) :
				while ( have_posts() ) :
					the_post();

					$query_obj       = get_queried_object();
					$archive_page_of = 'post';
					$current_term    = '';

					$asset_id        = $query_obj->ID;
					$asset_permalink = get_post_permalink( $asset_id );
					$asset_title     = $query_obj->post_title;
					$asset_desc      = $query_obj->post_content;
					$publish_date    = get_the_date( 'M d, y', $asset_id );
					$update_date     = get_the_modified_date( 'M d, y', $asset_id );
					$th_comments     = $query_obj->comment_count;
					$version         = get_post_meta( $asset_id, 'asset_version', true );
					$views           = get_post_meta( $asset_id, 'total_view', true );
					$downloads       = get_post_meta( $asset_id, 'total_download', true );
					$live_url        = get_post_meta( $asset_id, 'live_url', true );

					$views++;
					update_post_meta( $asset_id, 'total_view', $views );

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
					<div class="row single-dam">
						<div class="col-sm-12">

							<div class="alert alert-danger" role="alert">
								<h2>Disclaimer!</h2>
								<p>We are not reselling, redistributing, or releasing license keys. All plugins and themes are 100% authentic and not modified in any sense. Some plugins or themes may require keys to work. Feel free to use for learning and testing purposes. If you want to use it on a live domain, we suggest you buy the license from their authors.</p>
								<p><a href="#product-inquiry" class="" title="Configure <?php echo esc_attr( $asset_title ); ?> " data-lightbox="inline-dyn" itemprop="url"><strong>Contact us</strong></a> to install and configure it on your live site <strong>only in $49</strong>.</p>
							</div>

							<div class="dam-asset-grid">
								<div class="dam-asset-image">

										<div class="dam-asset-image-alt">
											<img class="asset-image img-fluid" src="<?php echo esc_url( get_the_post_thumbnail_url( $asset_id, 'full' ) ); ?>" alt="<?php echo esc_attr( $alt ); ?>" />
										</div>

									<div class="dam-asset-types">
										<?php echo wp_kses_post( $post_cats_output ); ?>
									</div>

									<ul class="dam-asset-detail">
										<li><i class="fa-solid fa-eye fa-fw"></i> <?php echo esc_html( $views ); ?></li>
										<li><i class="fa-solid fa-cloud-arrow-down fa-fw"></i> <?php echo esc_html( $downloads ); ?></li>
										<li><i class="fa-solid fa-comments fa-fw"></i> <?php echo esc_html( $th_comments ); ?></li>
									</ul>

									<a class="dam-asset-download dam-single-download" data-assetid="<?php echo esc_attr( $asset_id ); ?>" href="#!" title="Download <?php echo esc_attr( $asset_title ); ?>">
										Download
									</a>
								</div>
								<div class="dam-asset-content">
									<p class="dam-asset-meta">
										<span><i class="fa-solid fa-code-fork fa-fw"></i><?php echo esc_html( $version ); ?></span>
										<span class="dam-seperator"></span>
										<span><i class="fa-regular fa-clock fa-fw"></i><?php echo esc_html( $publish_date ); ?></span>
										<span class="dam-seperator"></span>
										<span><i class="fa-regular fa-pen-to-square fa-fw"></i><?php echo esc_html( $update_date ); ?></span>
									</p>
								</div>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="dam-asset-grid-single">
								<div class="single-dam-title">
									<h2 class="asset-title"><?php echo esc_html( $asset_title ); ?></h2>
								</div>

								<div class="single-dam-meta">
									<ul class="single-dam-list">
										<li><strong>Published On:</strong> <span><?php echo esc_html( $publish_date ); ?></span> </li>
										<li><strong>Last Updated:</strong> <span><?php echo esc_html( $update_date ); ?></span></li>
										<li><strong>Version:</strong> <span><?php echo esc_html( $version ); ?></span></li>
										<li><strong>Views:</strong> <span><?php echo esc_html( $views ); ?></span></li>
										<li><strong>Download:</strong> <span><?php echo esc_html( $downloads ); ?></span></li>
										<li><strong>Comments:</strong> <span><?php echo esc_html( $th_comments ); ?></span></li>
										<li><strong>Category:</strong> <span><?php echo wp_kses_post( $post_cats_output ); ?></span></li>
										<?php
										if ( ! empty( $post_tags_output ) ) {
											?>
											<li><strong>Tags:</strong> <span><?php echo wp_kses_post( $post_tags_output ); ?></span></li>
											<?php
										}
										?>
									</ul>
								</div>
								<div class="single-dam-share">
									<?php dam_share_social_links( $asset_permalink, $asset_title ); ?>
								</div>

								<div class="single-dam-actions">
									<div class="widget widget-subscribe book-download-link">
										<h3>Download / Live Preview</h3>
										<div id="book-info" data-bookname="<?php echo esc_attr( $asset_title ); ?>"
											data-bookurl="<?php echo esc_attr( $asset_permalink ); ?>"
											data-bookimg="<?php echo esc_url( get_the_post_thumbnail_url( $asset_id, 'full' ) ); ?>"
											data-bookid="<?php echo esc_attr( $asset_id ); ?>"></div>
										<div class="share-tounlock">
											<button type="button" class="dam-asset-actions dam-single-download" data-assetid="<?php echo esc_attr( $asset_id ); ?>" title="Download <?php echo esc_attr( $asset_title ); ?>">Download</button>
											<a class="dam-asset-actions" href="<?php echo esc_url( $live_url ); ?>" title="<?php echo esc_attr( $asset_title ); ?>" target="_blank" >Live Preview</a>
										</div>
										<div class="share-tounlock">
										</div>
									</div>
								</div>

							</div>
						</div>
					</div>

					<div class="row single-dam">
						<div class="col-sm-12">
							<div class="alert alert-danger" role="alert">
								<p>Comment down to get the updated version if the version of this file doesn't match with the vendor version.</p>
							</div>
						</div>
					</div>

					<div class="row single-dam">
						<div class="col-sm-12">
							<div class="single-dam-content">
								<h3>Description</h3>
								<?php echo wp_kses_post( $asset_desc ); ?>
							</div>
						</div>
					</div>

					<div class="row single-dam-pagination">
							<?php $prev_post = get_adjacent_post( true, '', true, 'asset-type' ); ?>
							<?php $next_post = get_adjacent_post( true, '', false, 'asset-type' ); ?>

						<div class="col">
							<?php if ( is_a( $prev_post, 'WP_Post' ) ) { ?>
								<div class="dam-prev">
									<a href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>" class="dam-single-nav-link"><i class="fas fa-hand-point-left fa-fw"></i> Previous</a>
								</div>
							<?php } ?>
						</div>

						<div class="col">
							<?php if ( is_a( $next_post, 'WP_Post' ) ) { ?>
								<div class="dam-next">
									<a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>" class="dam-single-nav-link right-float">Next<i class="fas fa-hand-point-right fa-fw"></i></a>
								</div>
							<?php } ?>
						</div>
					</div>

					<div class="row">
						<div class="col">
							<div class="dam-single-space"></div>
						</div>
					</div>

					<div class="row">
						<div class="col-sm-12">
							<?php
							// If comments are open or we have at least one comment, load up the comment template.
							if ( comments_open() || get_comments_number() ) :
								comments_template();
							endif;
							?>
						</div>
					</div>
				<?php endwhile; ?>
			<?php endif; ?>
		</div>
	</div>
</section>

<?php get_footer(); ?>


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title dam-modal-title">File is beign download!</h3>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body dam-modal-body">
				<div class="begin-countdown">
					<div class="text-center">
						<p> The archive file will be downloading in <span id="pageBeginCountdownText">10 </span> seconds. If downloading does not start, please click <a href="#" id="drive-link">here</a>.</p>
						<progress value="10" max="10" id="pageBeginCountdown"></progress>

						<div class="single-dam-share">
							<?php dam_share_social_links( $asset_permalink, $asset_title, 'Help us to grow by sharing in your circles.' ); ?>
						</div>
						<a href="#" id="download-link" class="download-link" download="download">Download Link</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Error Modal -->
<div class="modal fade" id="myErrorModal" role="dialog" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="pb-3 dam-modal-title">Something went wrong!</h3>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<p id="error-msg">It seems that something went wrong. Please try again. Thanks!</p>
			</div>
		</div>

	</div>
</div>
