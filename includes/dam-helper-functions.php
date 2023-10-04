<?php
/**
 * The file that defines the functions to used through out the plugin.
 *
 * @link       https://2bytecode.com/
 * @since      1.0.0
 *
 * @package    Digital_Asset_Manager
 * @subpackage Digital_Asset_Manager/includes
 */

if ( ! function_exists( 'dam_settings_default_options' ) ) {

	/**
	 * Default options for setting fields.
	 *
	 * @return array
	 */
	function dam_settings_default_options() {

		return array(
			'asset_post_type_capability'     => 'page',
			'asset_post_type_visibility'     => 'public',
			'asset_post_type_fields'         => array(
				'title'                 => 'title',
				'description'           => 'description',
				'version_number'        => 'version_number',
				'live_url'              => 'live_url',
				'featured_image'        => 'featured_image',
				'associated_asset_type' => 'associated_asset_type',
			),
			'asset_type_taxonomy_visibility' => 'public',
			'asset_tags_taxonomy_visibility' => 'public',
		);

	}
}

if ( ! function_exists( 'list_asset_categories_on_sidebar' ) ) {

	/**
	 * Display Asset Types in the sidebar template.
	 *
	 * @since 2.0.0
	 *
	 * @return mixed
	 */
	function list_asset_categories_on_sidebar() {

		$args = array(
			'taxonomy'   => array( 'asset-type' ),
			'orderby'    => 'name',
			'order'      => 'ASC',
			'hide_empty' => false,
		);

		$asset_categories = get_terms( $args );

		if ( ! empty( $asset_categories ) ) :
			return $asset_categories;
		endif;

		return false;
	}
}

if ( ! function_exists( 'sort_terms_hierarchicaly' ) ) {

	/**
	 * Sort the term in parent child hierarchy recersively.
	 *
	 * @since 2.0.0
	 *
	 * @param array $cats All categories.
	 * @param array $into Sorted categories.
	 * @param int   $parent_id ID of parent term.
	 * @return void
	 */
	function sort_terms_hierarchicaly( array &$cats, array &$into, $parent_id = 0 ) {
		foreach ( $cats as $i => $cat ) {
			if ( $cat->parent === $parent_id ) {
				$into[ $cat->term_id ] = $cat;
				unset( $cats[ $i ] );
			}
		}

		foreach ( $into as $top_cat ) {
			$top_cat->children = array();
			sort_terms_hierarchicaly( $cats, $top_cat->children, $top_cat->term_id );
		}
	}
}

if ( ! function_exists( 'list_asset_categories_on_sidebar_html' ) ) {

	/**
	 * Categories markup to display in the sidebar template.
	 *
	 * @since 2.0.0
	 *
	 * @param array $categories All categories.
	 * @return string
	 */
	function list_asset_categories_on_sidebar_html( $categories ) {
		$html  = '';
		$html .= '<div class="nav-side-menu">';
		$html .= '	<div class="menu-list">';
		$html .= '		<ul id="menu-content" class="menu-content collapse in">';

		$category_hierarchy = array();
		sort_terms_hierarchicaly( $categories, $category_hierarchy );

		foreach ( $category_hierarchy as $level1 ) {
			if ( empty( $level1->children ) ) {
				$html .= "<li class='collapsed' data-slug='$level1->slug'><a href='" . esc_url( get_category_link( $level1->term_id ) ) . "'>$level1->name</a></li>";
			} else {
				$html .= "<li data-toggle='collapse' data-target='#$level1->slug' class='collapsed dam-dropdown' data-slug='$level1->slug'>";
				$html .= "<a href='" . esc_url( get_category_link( $level1->term_id ) ) . "'>$level1->name</a>";
				$html .= '</li>';
				$html .= "<ul class='sub-menu collapse' id='$level1->slug'>";
				foreach ( $level1->children as $level2 ) :
					if ( empty( $level2->children ) ) {
						$html .= "<li data-slug='$level2->slug'><a href='" . esc_url( get_category_link( $level2->term_id ) ) . "'>$level2->name</a></li>";
					} else {
						$html .= "<li data-toggle='collapse' data-target='#$level2->slug' class='collapsed dam-dropdown' data-slug='$level2->slug'>";
						$html .= "<a href='" . esc_url( get_category_link( $level2->term_id ) ) . "'>$level2->name</a>";
						$html .= '</li>';
						$html .= "<ul class='sub-menu collapse' id='$level2->slug'>";
						foreach ( $level2->children as $level3 ) :
							if ( empty( $level3->children ) ) {
								$html .= "<li data-slug='$level3->slug'><a href='" . esc_url( get_category_link( $level3->term_id ) ) . "'>$level3->name</a></li>";
							} else {
								$html .= "<li data-toggle='collapse' data-target='#$level3->slug' class='collapsed dam-dropdown' data-slug='$level3->slug'>";
								$html .= "<a href='" . esc_url( get_category_link( $level3->term_id ) ) . "'>$level3->name</a>";
								$html .= '</li>';
								$html .= "<ul class='sub-menu collapse' id='$level3->slug'>";
								foreach ( $level3->children as $level4 ) :
									if ( empty( $level4->children ) ) {
										$html .= "<li data-slug='$level4->slug'><a href='" . esc_url( get_category_link( $level4->term_id ) ) . "'>$level4->name</a></li>";
									} else {
										$html .= "<li data-toggle='collapse' data-target='#$level4->slug' class='collapsed dam-dropdown' data-slug='$level4->slug'>";
										$html .= "<a href='" . esc_url( get_category_link( $level4->term_id ) ) . "'>$level4->name</a>";
										$html .= '</li>';
										$html .= "<ul class='sub-menu collapse' id='$level4->slug'>";
										foreach ( $level4->children as $level5 ) :
											$html .= "<li data-slug='$level5->slug'><a href='" . esc_url( get_category_link( $level5->term_id ) ) . "'>$level5->name</a></li>";
										endforeach;
										$html .= '</ul>';
									}
								endforeach;
								$html .= '</ul>';
							}
						endforeach;
						$html .= '</ul>';
					}
				endforeach;
				$html .= '</ul>';
			}
		}

		$html .= '		</ul>';
		$html .= '	</div>';
		$html .= '</div>';

		return $html;
	}
}

if ( ! function_exists( 'latest_asset_on_sidebar' ) ) {

	/**
	 * Display top 5 assets on sidebar.
	 *
	 * @since 2.0.0
	 *
	 * @param array $archive_page_of Current archive page.
	 * @param array $cur_term All Current custom taerm.
	 * @param array $orderby Orderby clause.
	 * @return mixed
	 */
	function latest_asset_on_sidebar( $archive_page_of, $cur_term, $orderby ) {

		$args = array(
			'post_type'      => 'dam-asset',
			'posts_per_page' => 5,
			'post_status'    => 'publish',
			'orderby'        => $orderby,
			'order'          => 'DESC',
		);

		if ( 'meta_value_num' === $orderby ) {
			$args['meta_key'] = 'total_download'; // phpcs:ignore.
		}

		if ( 'term' === $archive_page_of ) :
			$args['tax_query'] = array( // phpcs:ignore.
				array(
					'taxonomy' => $cur_term->taxonomy,
					'field'    => 'term_id',
					'terms'    => $cur_term->term_id,
				),
			);
		endif;

		$dam_asset = get_posts( $args );

		if ( $dam_asset ) :
			return $dam_asset;
		endif;

		return false;
	}
}

if ( ! function_exists( 'list_asset_tags_on_sidebar' ) ) {

	/**
	 * Display asset tags on sidebar.
	 *
	 * @since 2.0.0
	 *
	 * @return mixed
	 */
	function list_asset_tags_on_sidebar() {

		$args = array(
			'taxonomy'   => array( 'asset-tags' ),
			'orderby'    => 'name',
			'order'      => 'ASC',
			'hide_empty' => true,
		);

		$asset_tags = get_terms( $args );

		// Randomize Term Array.
		shuffle( $asset_tags );

		// Grab Indices 0 - 5, 6 in total.
		$asset_tags = array_slice( $asset_tags, 0, 6 );

		if ( ! empty( $asset_tags ) ) :
			return $asset_tags;
		endif;

		return false;
	}
}

if ( ! function_exists( 'display_assets_list_on_sidebar' ) ) {

	/**
	 * Display top 5 assets on sidebar.
	 *
	 * @since 2.0.0
	 *
	 * @param array $query_content Query result.
	 * @param array $widget_title Widget title.
	 * @return string
	 */
	function display_assets_list_on_sidebar( $query_content, $widget_title ) {
		ob_start();
		?>
		<div class="dam-widget">
			<div class="dam-recent-posts">
				<div class="dam-widget-title">
					<h2><?php echo esc_html( $widget_title ); ?></h2>
				</div>
				<div class="dam-widget-content">
					<?php
					foreach ( $query_content as $asset ) :
						$asset_id         = $asset->ID;
						$thumb_id         = get_post_thumbnail_id( $asset_id );
						$alt              = get_post_meta( $thumb_id, '_wp_attachment_image_alt', true );
						$version          = get_post_meta( $asset_id, 'asset_version', true );
						$views            = get_post_meta( $asset_id, 'total_view', true );
						$downloads        = get_post_meta( $asset_id, 'total_download', true );
						$asset_link       = get_post_permalink( $asset_id );
						$asset_title      = $asset->post_title;
						$post_cats        = get_the_terms( $asset_id, 'asset-type' );
						$post_cats_output = '';
						$counts           = is_array( $post_cats ) ? count( $post_cats ) : 1;
						if ( ! empty( $post_cats ) ) {
							foreach ( $post_cats as $cat ) {
								$post_cats_output .= '<a href="' . esc_url( get_category_link( $cat->term_id ) ) . '" target="_blank" title="' . $cat->name . '">' . $cat->name . '</a>';
								if ( $counts > 1 ) {
									$post_cats_output .= ', ';
								}
							}
						}
						?>

						<div class="dam-widget-item">
							<div class="dam-widget-item-image">
								<a href="<?php echo esc_url( $asset_link, 'https' ); ?>" target="_blank" title="<?php echo esc_attr( $asset_title ); ?>">
								<img src="<?php echo esc_url( get_the_post_thumbnail_url( $asset_id, 'thumbnail' ), 'https' ); ?>" alt="<?php echo esc_attr( $alt ); ?>" class="img-fluid" title="<?php echo esc_attr( $title ); ?>"/>
								</a>
							</div>
							<div class="dam-widget-item-content">
								<p class="dam-widget-item-cat">
									<?php echo esc_html( $post_cats_output ); ?>
								</p>
								<h3><a href="<?php echo esc_url( $asset_link, 'https' ); ?>"><?php echo esc_html( $asset_title ); ?></a></h3>
								<p class="dam-widget-item-meta">
									<span><i class="fa-solid fa-code-fork"></i><?php echo esc_attr( $version ); ?></span>
									<span><i class="fa-solid fa-eye"></i><?php echo esc_attr( $views ); ?></span>
									<span><i class="fa-solid fa-cloud-arrow-down"></i><?php echo esc_attr( $downloads ); ?></span>
								</p>
							</div>
						</div>
						<?php
					endforeach;
					?>
				</div>
			</div>
		</div>
		<?php
		$query_content_html = ob_get_clean();
		return $query_content_html;
	}
}

if ( ! function_exists( 'dam_share_social_links' ) ) {

	/**
	 * Display share asset markup.
	 *
	 * @since 2.0.0
	 *
	 * @param array $link Link of the asset.
	 * @param array $title Title of the asset.
	 * @param array $widget_title Widget title.
	 * @return void
	 */
	function dam_share_social_links( $link, $title, $widget_title = '' ) {
		?>
		<div class="share-book">
			<h3 class="share-book-h3"><?php echo esc_html( ( ! empty( $widget_title ) ) ? $widget_title : 'Share it with your friends' ); ?></h3>
			<ul class="list-inline social-links">
				<li class="list-inline-item">
					<a href="https://www.facebook.com/share.php?u=<?php echo esc_url( $link ); ?>" class="fb" target="_blank">
						<i class="fa fa-facebook-f"></i>
					</a>
				</li>
				<li class="list-inline-item">
					<a href="https://www.linkedin.com/cws/share?url=<?php echo esc_url( $link ); ?>" class="lin" target="_blank">
						<i class="fa fa-linkedin"></i>
					</a>
				</li>
				<li class="list-inline-item">
					<a href="https://twitter.com/share?text=Get this book '<?php echo esc_html( $title ); ?>' from this URL '&url=<?php echo esc_url( $link ); ?>" class="tw" target="_blank">
						<i class="fa fa-twitter"></i>
					</a>
				</li>
				<li class="list-inline-item">
					<a href="mailto:someone@example.com?subject=Get '<?php echo esc_html( $title ); ?>' free&body=Hi%0A%0AI thought you would need this entitled '<?php echo esc_html( $title ); ?>'%0A%0A Download it from here <?php echo esc_url( $link ); ?>" class="mail" target="_blank">
						<i class="fa fa-envelope"></i>
					</a>
				</li>
			</ul>
		</div>
		<?php
	}
}
