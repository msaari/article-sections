<?php
/**
 * Article Sections.
 *
 * @package articlesections
 * @author Mikko Saari
 * @version 1.0
 */

/*
Plugin Name: Article Sections
Plugin URI: https://github.com/msaari/article-sections
Description: Create and insert article sections by a different author.
Author: Mikko Saari
Version: 1.0
Author URI: https://www.mikkosaari.fi/
*/

require_once 'acf-codifier.php';

add_action( 'init', 'msaari_as_register_post_type', 0 );
add_action( 'acf/init', 'msaari_as_block_init' );
add_action( 'pre_get_posts', 'msaari_as_post_types_author_archives' );
add_action( 'wp_insert_post', 'msaari_as_link_posts', 99, 2 );
add_filter( 'post_thumbnail_html', 'msaari_as_thumbnail', 10, 5 );
add_filter( 'post_type_link', 'msaari_as_permalink', 10, 2 );
add_filter( 'get_the_excerpt', 'msaari_as_excerpt', 10, 2 );

/**
 * Registers the article section post type.
 *
 * @return void
 */
function msaari_as_register_post_type() {
	$labels = array(
		'name'                  => _x( 'Sections', 'Post Type General Name', 'ms_article_section' ),
		'singular_name'         => _x( 'Section', 'Post Type Singular Name', 'ms_article_section' ),
		'menu_name'             => __( 'Article Sections', 'ms_article_section' ),
		'name_admin_bar'        => __( 'Section', 'ms_article_section' ),
		'archives'              => __( 'Section Archives', 'ms_article_section' ),
		'attributes'            => __( 'Section Attributes', 'ms_article_section' ),
		'parent_item_colon'     => __( 'Parent Section:', 'ms_article_section' ),
		'all_items'             => __( 'All Sections', 'ms_article_section' ),
		'add_new_item'          => __( 'Add New Section', 'ms_article_section' ),
		'add_new'               => __( 'Add New', 'ms_article_section' ),
		'new_item'              => __( 'New Section', 'ms_article_section' ),
		'edit_item'             => __( 'Edit Section', 'ms_article_section' ),
		'update_item'           => __( 'Update Section', 'ms_article_section' ),
		'view_item'             => __( 'View Section', 'ms_article_section' ),
		'view_items'            => __( 'View Sections', 'ms_article_section' ),
		'search_items'          => __( 'Search Section', 'ms_article_section' ),
		'not_found'             => __( 'Not found', 'ms_article_section' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'ms_article_section' ),
		'featured_image'        => __( 'Featured Image', 'ms_article_section' ),
		'set_featured_image'    => __( 'Set featured image', 'ms_article_section' ),
		'remove_featured_image' => __( 'Remove featured image', 'ms_article_section' ),
		'use_featured_image'    => __( 'Use as featured image', 'ms_article_section' ),
		'insert_into_item'      => __( 'Insert into section', 'ms_article_section' ),
		'uploaded_to_this_item' => __( 'Uploaded to this section', 'ms_article_section' ),
		'items_list'            => __( 'Sections list', 'ms_article_section' ),
		'items_list_navigation' => __( 'Sections list navigation', 'ms_article_section' ),
		'filter_items_list'     => __( 'Filter sections list', 'ms_article_section' ),
	);
	$args   = array(
		'label'               => __( 'Section', 'ms_article_section' ),
		'description'         => __( 'Article section', 'ms_article_section' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'author', 'custom-fields', 'excerpt' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => false,
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => false,
		'rewrite'             => false,
		'capability_type'     => 'page',
		'show_in_rest'        => true,
	);
	register_post_type( 'ms_article_section', $args );
}

/**
 * Initializes the section block.
 *
 * @return void
 */
function msaari_as_block_init() {
	if ( ! function_exists( 'acf_register_block' ) ) {
		return;
	}

	acf_register_block(
		array(
			'name'            => 'ms_section',
			'title'           => __( 'Article section', 'ms_article_section' ),
			'description'     => __( 'An article section.', 'ms_article_section' ),
			'render_callback' => 'msaari_as_render_callback',
			'category'        => 'embed',
			'icon'            => 'media-default',
			'keywords'        => array( 'section' ),
		)
	);
}

/**
 * Renders the article section block.
 *
 * @param array $block The block data.
 * @return void
 */
function msaari_as_render_callback( array $block ) {
	$post        = get_field( 'section_post' );
	$show_title  = get_field( 'show_title' );
	$title_tag   = get_field( 'title_tag' );
	$show_author = get_field( 'show_author' );
	$show_date   = get_field( 'show_date' );

	$title = '';
	if ( $show_title ) {
		$close_tag = '</' . $title_tag . '>';
		$title_tag = '<' . $title_tag . '>';
		$title     = $title_tag . $post->post_title . $close_tag;
	}

	$byline = '<span class="section_byline">';
	if ( $show_author ) {
		$author  = get_user_by( 'ID', $post->post_author );
		$byline .= '— ' . $author->display_name;
	}
	if ( $show_date ) {
		$byline .= $show_author ? ' (' : '';
		$date    = get_the_date( apply_filters( 'msaari_as_date_format', 'j.n.Y' ), $post );
		$byline .= $date;
		$byline .= $show_author ? ')' : '';
	}

	if ( '<span class="section_byline">' === $byline ) {
		$byline = '';
	} else {
		$byline .= '</span>';
	}

	$id = 'section-' . sanitize_title( $post->post_title );
	if ( ! empty( $block['anchor'] ) ) {
		$id = $block['anchor'];
	}

	$class = 'article_section';
	if ( ! empty( $block['className'] ) ) {
		$class .= ' ' . $block['className'];
	}
	if ( ! empty( $block['align'] ) ) {
		$class .= ' align' . $block['align'];
	}

	?>
	<div id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $class ); ?>">
		<?php echo $title; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php echo $byline; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php echo $post->post_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>
	<?php
}

/**
 * Adds the article sections to author archives.
 *
 * @param WP_Query $query WP_Query object.
 * @return void
 */
function msaari_as_post_types_author_archives( WP_Query $query ) {
	if ( $query->is_author ) {
		$query->set( 'post_type', array( 'ms_article_section', 'post' ) );
	}
}

/**
 * Changes the permalink to point to the parent post.
 *
 * @param string  $permalink The permalink.
 * @param WP_Post $post      The post object.
 * @return string Modified permalink.
 */
function msaari_as_permalink( string $permalink, WP_Post $post ) : string {
	if ( 'ms_article_section' === $post->post_type && $post->post_parent ) {
		$permalink  = get_permalink( $post->post_parent );
		$permalink .= '#section-' . sanitize_title( $post->post_title );
		return $permalink;
	}
	return $permalink;
}

/**
 * Replaces the thumbnail with the parent post thumbnail.
 *
 * @param string       $thumbnail The original thumbnail HTML.
 * @param integer      $post_id   The post ID.
 * @param integer      $thumb_id  The thumbnail ID (not used).
 * @param string|int   $size      The thumbnail size (passed on).
 * @param string|array $attr      The thumbnail attributes (passed on).
 *
 * @uses get_the_post_thumbnail()
 *
 * @return string The modified thumbnail.
 */
function msaari_as_thumbnail( string $thumbnail, int $post_id, int $thumb_id, $size, $attr ) : string {
	$_post = get_post( $post_id );
	if ( 'ms_article_section' === $_post->post_type ) {
		return get_the_post_thumbnail( $_post->post_parent, $size, $attr );
	}
	return $thumbnail;
}

/**
 * When post is saved, makes the post the parent for all the sections in it.
 *
 * @param integer $post_id The post ID.
 * @param WP_Post $post    The post object.
 * @return void
 */
function msaari_as_link_posts( int $post_id, WP_Post $post ) {
	if ( 'ms_article_section' === $post->post_type ) {
		return;
	}
	if ( 'publish' !== $post->post_status ) {
		return;
	}
	$block_count = preg_match_all( '#wp:acf/ms-section.*?>#ims', $post->post_content, $matches );
	if ( $block_count > 0 ) {
		foreach ( $matches[0] as $block ) {
			$hits = preg_match( '/section_post": (\d+)/', $block, $post );
			if ( $hits ) {
				$section_post_id = $post[1];
				$post_array      = array(
					'ID'          => $section_post_id,
					'post_parent' => $post_id,
				);
				wp_update_post( $post_array );
			}
		}
	}
}

/**
 * Generates a 20-word excerpt for the sections, removing the possible headings
 * first.
 *
 * @param string  $post_excerpt The excerpt.
 * @param WP_Post $post         The post object.
 * @return string The modified excerpt.
 */
function msaari_as_excerpt( string $post_excerpt, WP_Post $post ) : string {
	if ( 'ms_article_section' !== $post->post_type ) {
		return $post_excerpt;
	}

	return wp_trim_words( preg_replace( '#<h.*</h.>#', '', $post->post_content ), 20, '&hellip;' );
}
