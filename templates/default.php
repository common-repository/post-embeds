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
 	<?php
 	/**
 	 * Prints scripts or data in the embed template head tag.
 	 *
 	 * @since 4.4.0
 	 */
 	do_action( 'embed_head' );
 	?>
 </head>
 <body <?php body_class(); ?>>
	<div <?php post_class( 'wp-embed' ); ?>>
		<?php
		$thumbnail_id = 0;

		if ( has_post_thumbnail() ) {
			$thumbnail_id = get_post_thumbnail_id();
		}

		if ( 'attachment' === get_post_type() && wp_attachment_is_image() ) {
			$thumbnail_id = get_the_ID();
		}

		/**
		 * Filters the thumbnail image ID for use in the embed template.
		 *
		 * @since 4.9.0
		 *
		 * @param int|false $thumbnail_id Attachment ID, or false if there is none.
		 */
		$thumbnail_id = apply_filters( 'embed_thumbnail_id', $thumbnail_id );

		if ( $thumbnail_id ) {
			$aspect_ratio = 1;
			$measurements = array( 1, 1 );
			$image_size   = 'full'; // Fallback.

			$meta = wp_get_attachment_metadata( $thumbnail_id );
			if ( ! empty( $meta['sizes'] ) ) {
				foreach ( $meta['sizes'] as $size => $data ) {
					if ( $data['height'] > 0 && $data['width'] / $data['height'] > $aspect_ratio ) {
						$aspect_ratio = $data['width'] / $data['height'];
						$measurements = array( $data['width'], $data['height'] );
						$image_size   = $size;
					}
				}
			}

			/**
			 * Filters the thumbnail image size for use in the embed template.
			 *
			 * @since 4.4.0
			 * @since 4.5.0 Added `$thumbnail_id` parameter.
			 *
			 * @param string $image_size   Thumbnail image size.
			 * @param int    $thumbnail_id Attachment ID.
			 */
			$image_size = apply_filters( 'embed_thumbnail_image_size', $image_size, $thumbnail_id );

			$shape = $measurements[0] / $measurements[1] >= 1.75 ? 'rectangular' : 'square';

			/**
			 * Filters the thumbnail shape for use in the embed template.
			 *
			 * Rectangular images are shown above the title while square images
			 * are shown next to the content.
			 *
			 * @since 4.4.0
			 * @since 4.5.0 Added `$thumbnail_id` parameter.
			 *
			 * @param string $shape        Thumbnail image shape. Either 'rectangular' or 'square'.
			 * @param int    $thumbnail_id Attachment ID.
			 */
			$shape = apply_filters( 'embed_thumbnail_image_shape', $shape, $thumbnail_id );
		}

		if ( $thumbnail_id && 'rectangular' === $shape ) :
			?>
			<div class="wp-embed-featured-image rectangular">
				<a href="<?php the_permalink(); ?>" target="_top">
					<?php echo wp_get_attachment_image( $thumbnail_id, $image_size ); ?>
				</a>
			</div>
		<?php endif; ?>

		<p class="wp-embed-heading">
			<a href="<?php the_permalink(); ?>" target="_top">
				<?php the_title(); ?>
			</a>
		</p>

        <?php if ( $thumbnail_id && 'square' === $shape ) : ?>
			<div class="wp-embed-featured-image square">
				<a href="<?php the_permalink(); ?>" target="_top">
					<?php echo wp_get_attachment_image( $thumbnail_id, $image_size ); ?>
				</a>
			</div>
		<?php endif; ?>

        <?php do_action( 'postembeds_author' ); // .pe-author ?>

        <?php do_action( 'postembeds_datetime' ); // .pe-date ?>

		<div class="wp-embed-excerpt"><?php the_excerpt_embed(); ?></div>

        <?php do_action( 'postembeds_readmore' ); // .pe-readmore ?>

		<?php
		/**
		 * Prints additional content after the embed excerpt.
		 *
		 * @since 4.4.0
		 */
		do_action( 'embed_content' );
		?>

		<div class="wp-embed-footer">
			<?php the_embed_site_title(); ?>

			<div class="wp-embed-meta">
				<?php
				/**
				 * Prints additional meta content in the embed template.
				 *
				 * @since 4.4.0
				 */
				do_action( 'embed_content_meta' );
				?>
			</div>
		</div>
	</div>
<?php
/**
 * Prints scripts or data before the closing body tag in the embed template.
 *
 * @since 4.4.0
 */
do_action( 'embed_footer' );
?>
</body>
</html>
