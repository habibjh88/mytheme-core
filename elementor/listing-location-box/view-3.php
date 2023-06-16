<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\MyTheme_Core;

$count_html = sprintf( _nx( '%s Property', '%s Properties', $data['count'], 'Number of Properties', 'mytheme-core' ), number_format_i18n( $data['count'] ) );

$link_start = $data['enable_link'] ? '<a href="' . $data['permalink'] . '">' : '';
$link_end   = $data['enable_link'] ? '</a>' : '';

$class  = $data['display_count'] ? 'rtin-has-count' : '';
$class  .= empty( $data['location_image']['id'] ) ? ' rtin-no-image' : '';
$radius = '';
if ( $data['disable_border_radius'] ) {
	$radius = 'no-border-radius';
}
?>
<div class="rt-el-listing-location-box category-browse category-cities style2 <?php echo esc_attr( $data['layout'] . ' ' . $radius ) ?>">
    <div class="category-box <?php echo esc_attr( $class ); ?>">

        <div class="img-wrap">
            <div class="item-img">
                <div class="overlay"></div>
            </div>
        </div>
        <div class="item-content">
			<?php if ( $data['display_count'] ): ?>
                <div class="item-count <?php echo esc_attr( $data['show_dots'] ) ?>"><?php echo esc_html( $count_html < 10 ? '0' . ltrim( $count_html, "0" )
						: $count_html ); ?></div>
			<?php endif; ?>
            <h3 class="item-title">
				<?php echo wp_kses_post( $link_start ); ?>
				<?php echo esc_html( $data['title'] ); ?>
				<?php echo wp_kses_post( $link_end ); ?>
            </h3>
			<?php if ( $data['show_arrow_icon'] == 'yes' ) : ?>
				<?php echo wp_kses_post( $link_start ); ?>
                <i class="fas fa-arrow-right link-icon"></i>
				<?php echo wp_kses_post( $link_end ); ?>
			<?php endif; ?>
        </div>

    </div>
</div>