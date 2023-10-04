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
 * @subpackage Digital_Asset_Manager/public/tempaltes/sidebar-asset
 */

/**
 * Add the file for markup.
 */
global $query_obj;
global $archive_page_of;
global $current_term;

// Asset Types.
$asset_types = list_asset_categories_on_sidebar();

if ( $asset_types ) :
	ob_start();
	?>
	<div class="dam-widget">
		<div class="dam-recent-posts">
			<div class="dam-widget-title">
				<h2>Asset Types</h2>
			</div>
			<div class="dam-widget-content">
				<?php echo wp_kses_post( list_asset_categories_on_sidebar_html( $asset_types ) ); ?>
			</div>
		</div>
	</div>

	<?php
		$asset_types_html = ob_get_clean();
		echo wp_kses_post( $asset_types_html );
endif;

// Most Downloaded.
$most_downloaded = latest_asset_on_sidebar( $archive_page_of, $current_term, 'meta_value_num' );
if ( $most_downloaded ) :
	echo wp_kses_post( display_assets_list_on_sidebar( $most_downloaded, 'Most Downloaded' ) );
endif;

// Asset Tags Cloud.
$asset_tags = list_asset_tags_on_sidebar();
if ( $asset_tags ) :
	ob_start();
	?>
<div class="dam-widget">
	<div class="dam-tag-cloud">
		<div class="dam-widget-title">
			<h2>Tags Cloud</h2>
		</div>
		<div class="dam-widget-content">
			<p class="dam-widget-item-tags">

				<?php foreach ( $asset_tags as $asset_tag ) : ?>
					<a href="<?php echo esc_url( get_term_link( $asset_tag ) ); ?>" target="_blank" ><?php echo esc_html( $asset_tag->name ) . ' (' . esc_html( $asset_tag->count ) . ')'; ?></a>
				<?php endforeach; ?>

			</p>
		</div>
	</div>
</div>
	<?php
		$asset_tags_html = ob_get_clean();
		echo wp_kses_post( $asset_tags_html );
endif;

// Recent assets.
$recent_assets = latest_asset_on_sidebar( $archive_page_of, $current_term, 'date' );
if ( $recent_assets ) :
	echo wp_kses_post( display_assets_list_on_sidebar( $recent_assets, 'Recent assets' ) );
endif;
