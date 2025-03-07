<?php
/**
 * @package Post Embeds
 * @since 1.0.0
 */

if ( ! headers_sent() ) {
    header( 'X-WP-embed: true' );
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <title><?php esc_html_e( wp_get_document_title() ); ?></title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<?php do_action( 'embed_head' ); ?>
</head>
<body <?php body_class(); ?>>
    <div class="pe-embed">

        <div class="pe-title">
            <?php the_embed_site_title(); ?>
            <div>
                <h4>
                    <a href="<?php the_permalink(); ?>" target="_top">
                        <?php the_title(); ?>
                    </a>
                </h4>
                <?php do_action( 'postembeds_author' ); // .pe-author ?>
            </div>
        </div>
        <?php
        // $thumbnail_id, $shape and $image_size values comes from image.php
        include_once 'parts/image.php';
        ?>

        <div class="pe-excerpt-image<?php echo isset( $shape ) ? ' pe-image-shape-' . esc_html( $shape ) : '' ?>">
            <a href="<?php the_permalink(); ?>" target="_top"></a>
            <div class="pe-excerpt">
                <?php
                the_excerpt_embed();
                do_action( 'embed_content' );
                ?>
            </div>

            <?php
            if ( $thumbnail_id ) :
                $image_url = wp_get_attachment_image_url( $thumbnail_id, $image_size );
                ?>
                <div class="pe-image" style="background-image:url(<?php echo esc_url( $image_url ) ?>);">
                    <?php echo wp_get_attachment_image( $thumbnail_id, $image_size ); ?>
                </div>
                <?php
            endif;
            ?>
        </div>

        <?php do_action( 'postembeds_datetime' ); // .pe-date ?>

        <?php do_action( 'postembeds_readmore' ); // .pe-readmore ?>

        <div class="pe-footer">
            <div class="pe-embed-meta">
        		<?php do_action( 'embed_content_meta' ); ?>
        	</div>
        </div>

    </div>
    <?php do_action( 'embed_footer' ); ?>
</body>
</html>
