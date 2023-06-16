<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\MyTheme_Core;

use radiustheme\MyTheme\Helper;
use radiustheme\MyTheme\Listing_Functions;
use Rtcl\Helpers\Functions;
use RtclPro\Helpers\Fns;
use RtclPro\Controllers\Hooks\TemplateHooks;

$size = $data['thumbnail_size'] ? $data['thumbnail_size'] : 'rtcl-thumbnail';
// Post type
$args = [
	'post_type'           => 'rtcl_listing',
	'post_status'         => 'publish',
	'ignore_sticky_posts' => true,
	'posts_per_page'      => $data['number'],
];

// Ordering
if ( $data['random'] ) {
	$args['orderby'] = 'rand';
} else {
	$args['orderby'] = $data['orderby'];
	if ( $data['orderby'] == 'title' ) {
		$args['order'] = 'ASC';
	}
}


if ( ! empty( $data['cat'] ) ) {
	$args['tax_query'] = [
		[
			'taxonomy' => 'rtcl_category',
			'field'    => 'term_id',
			'terms'    => $data['cat'],
		],
	];
}

if ( $data['type'] ) {
	$args['meta_query'][] = [
		'key'     => 'ad_type',
		'value'   => $data['type'],
		'compare' => 'in',
	];
}

if ( $data['promotions_product'] ) {
	$args['meta_query'][] = [
		'key'     => $data['promotions_product'],
		'value'   => '1',
		'compare' => 'in',
	];
}

if ( $data['exclude'] ) :
	$excluded_ids         = explode( ',', $data['exclude'] );
	$args['post__not_in'] = $excluded_ids;
endif;


if ( $data['offset'] ) {
	$args['offset'] = $data['offset'];
}

$query = new \WP_Query( $args );

$display   = [
	'cat'    => $data['cat_display'] ? true : false,
	'views'  => $data['views_display'] ? true : false,
	'fields' => $data['field_display'] === 'yes' ? true : false,
	'label'  => $data['label_display'] === 'yes' ? true : false,
	'author' => $data['author_display'] === 'yes' ? true : false,
];
$is_action = '';
if ( $data['listing_action_visibility'] ) {
	$is_action = 'listing-action-visible';
}
$slider_data    = json_encode( $data['slider_data'] );
?>
<div class="rt-el-listing-wrapper product-grid rtcl <?php echo esc_attr( $data['layout'] . ' ' . $data['category_position'] . ' '
                                                                         . $data['hanging_visibility'] . ' ' . $is_action ) ?>">
    <div class="featuredContainer list-slick-carousel swiper" data-slider-settings="<?php echo esc_attr( $slider_data ); ?>">
        <div class="swiper-wrapper main-swiper-wrapper">
			<?php if ( $query->have_posts() ) : ?>
				<?php while ( $query->have_posts() ) : $query->the_post(); ?>
					<?php
					global $listing;
					$listing_type = Listing_Functions::get_listing_type( $listing );
					$thumb_img    = wp_get_attachment_image_src( $listing->get_the_thumbnail_id(), $size );
					$images       = Functions::get_listing_images( $listing->get_id() );
					?>
                    <div class="swiper-slide">
                        <div class="product-box style2">
							<?php if ( $data['listing_thumb_visibility'] === 'yes' ) : ?>
                                <div class="product-thumb <?php echo esc_attr( $display['cat'] ? 'is-cat' : 'no-cat' ) ?>">

									<?php if ( $display['label'] && $data['label_position'] == 'thumb' ):
										$listing->the_badges();
									endif; ?>

									<?php if ( 'slider' !== $data['thumbnail_source'] ) : ?>
                                        <a href="<?php the_permalink(); ?>">
                                            <div class="thumbnail-bg" style="background-image:url(<?php echo esc_url( $thumb_img[0] ) ?>)"></div>
                                        </a>
									<?php else :
										Helper::mytheme_thumb_carousel($listing->get_id(), $size);
									endif; ?>

									<?php $type_hide = ( $data['type_visibility'] != 'yes' || empty( $listing_type ) ) ? 'hide-product-type' : null; ?>
                                    <div class="product-type <?php echo esc_attr( $type_hide ) ?>">
										<?php if ( $data['type_visibility'] && ! empty( $listing_type ) ): ?>
                                            <span class="listing-type-badge">
                                            <?php echo sprintf( "%s %s", apply_filters( 'rtcl_type_prefix', __( 'For', 'mytheme-core' ) ), $listing_type['label'] ); ?>
                                        </span>
										<?php endif; ?>
                                    </div>

									<?php if ( $data['listing_action_visibility'] ) : ?>
                                        <div class="listing-action">
											<?php echo Listing_Functions::get_favourites_link( $listing->get_id() ); ?>
											<?php if ( class_exists('RtclPro') && Fns::is_enable_compare() ) {
												$compare_ids    = ! empty( $_SESSION['rtcl_compare_ids'] ) ? $_SESSION['rtcl_compare_ids'] : [];
												$selected_class = '';
												if ( is_array( $compare_ids ) && in_array( $listing->get_id(), $compare_ids ) ) {
													$selected_class = ' selected';
												}
												?>
                                                <a class="rtcl-compare <?php echo esc_attr( $selected_class ); ?>" href="#" data-toggle="tooltip" data-placement="top" title=""
                                                   data-original-title="<?php esc_html_e( "Compare", "mytheme-core" ) ?>"
                                                   data-listing_id="<?php echo absint( $listing->get_id() ) ?>">
                                                    <i class="flaticon-left-and-right-arrows"></i>
                                                </a>
											<?php } ?>
                                        </div>
									<?php endif; ?>

									<?php if ( $display['author'] ): ?>
                                        <div class="auuthor-img">
											<?php Helper::get_listing_author_iamge( $listing ); ?>
                                        </div>
									<?php endif; ?>

									<?php
									$average_rating = $listing->get_average_rating();
									$rating_count   = $listing->get_rating_count();
									if ( ! empty( $rating_count ) ): ?>
                                        <div class="product-rating">
                                            <div class="item-icon">
												<?php echo Functions::get_rating_html( $average_rating, $rating_count ); ?>
                                            </div>
                                            <div class="item-text"><?php echo apply_filters( 'mytheme_rating_count_format',
													sprintf( __( '(<span>%s</span>) Reviews', 'mytheme-core' ), esc_html( $rating_count ) ) ); ?></div>
                                        </div>
									<?php endif; ?>

                                </div>
							<?php endif; ?>
                            <div class="product-content <?php echo esc_attr( $display['cat'] ? 'is-cat' : 'no-cat' ) ?>">
                                <div class="product-top-content">
									<?php
									if ( $listing->has_category() && $display['cat'] ):
										$category = $listing->get_categories();
										$category = end( $category );
										?>
                                        <div class="product-category">
                                            <a href="<?php echo esc_url( get_term_link( $category->term_id,
												$category->taxonomy ) ); ?>"><?php echo esc_html( $category->name ) ?></a>
                                        </div>
									<?php endif;
									?>
									<?php if ( $listing->get_the_title() ): ?>
                                        <<?php echo esc_attr($data['title_tag']) ?> class="item-title rt-main-title">
                                            <a href="<?php $listing->the_permalink(); ?>">
												<?php $listing->the_title(); ?>
                                            </a>
                                        </<?php echo esc_attr($data['title_tag']) ?>>
									<?php endif; ?>

									<?php if ( $display['label'] && $data['label_position'] == 'below_title' ):
										$listing->the_badges();
									endif; ?>

                                    <ul class="entry-meta">
										<?php if ( $listing->has_location() && $listing->can_show_location() && $data['location_visibility'] ): ?>
                                            <li><i class="fas fa-map-marker-alt"></i><?php $listing->the_locations(); ?></li>
										<?php endif; ?>

										<?php if ( $listing->can_show_date() && $data['date_visibility'] ): ?>
                                            <li class="updated"><i class="far fa-clock"></i><?php $listing->the_time(); ?></li>
										<?php endif; ?>

										<?php if ( $display['views'] ): ?>
                                            <li class="rt-views">
                                                <i class="far fa-eye"></i><?php echo sprintf( _n( "%s view", "%s views", $listing->get_view_counts(), 'mytheme-core' ),
													number_format_i18n( $listing->get_view_counts() ) ); ?>
                                            </li>
										<?php endif; ?>
                                    </ul>

									<?php if ( $data['content_visibility'] ) : ?>
                                        <div class="listing-content">
											<?php
											$content = strip_shortcodes( wp_strip_all_tags( $listing->get_the_content() ) );
											$content = wp_trim_words( $content, $data['content_limit'], '' );
											echo wp_kses_post( $content );
											?>
                                        </div>
									<?php endif; ?>

									<?php if ( $listing->can_show_price() ): ?>
                                        <div class="product-price"><?php echo $listing->get_price_html(); ?></div>
									<?php endif; ?>
                                </div>
                                <div class="product-bottom-content">
									<?php
									if ( class_exists('RtclPro') && $display['fields'] ) { ?>
                                        <div class="list-information <?php echo esc_attr( $data['info_style'] ) ?>">
											<?php TemplateHooks::loop_item_listable_fields(); ?>
                                        </div>
									<?php }
									?>
                                </div>
                            </div>
                        </div>
                    </div>
				<?php endwhile; ?>
			<?php endif; ?>
			<?php wp_reset_postdata(); ?>

        </div>


    </div>
	<?php if ( $data['dots'] ) : ?>
        <div class="swiper-pagination"></div>
	<?php endif; ?>
	<?php if ( $data['arrows'] ) : ?>

        <div class="elementor-swiper-button elementor-swiper-button-prev mytheme-style">
            <i class="eicon-chevron-left" aria-hidden="true"></i>
            <span class="elementor-screen-only"><?php _e( 'Previous', 'mytheme-core' ); ?></span>
        </div>
        <div class="elementor-swiper-button elementor-swiper-button-next mytheme-style">
            <i class="eicon-chevron-right" aria-hidden="true"></i>
            <span class="elementor-screen-only"><?php _e( 'Next', 'mytheme-core' ); ?></span>
        </div>
   
	<?php endif; ?>
</div>