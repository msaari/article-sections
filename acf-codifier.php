<?php
/**
 * Article Sections.
 *
 * @package articlesections
 * @author Mikko Saari
 */

namespace Geniem\ACF;

if ( class_exists( 'Geniem\ACF\Group' ) ) {
	$section_block = new Group( 'Article Section', 'article_section' );

	$section_rule_group = new RuleGroup();
	$section_rule_group->add_rule( 'block', '==', 'acf/ms-section' );
	$section_block->add_rule_group( $section_rule_group );
	$section_block->register();

	$section_post = new Field\PostObject( 'Section', 'section_post', 'section_post' );
	$section_post->add_post_type( 'ms_article_section' );
	$section_block->add_field( $section_post );

	$show_title = new Field\TrueFalse( 'Show title', 'show_title', 'show_title' );
	$show_title->set_message( 'Check this box to display the section title' );
	$section_block->add_field( $show_title );

	$title_tag = new Field\Select( 'Title tag', 'title_tag', 'title_tag' );
	$title_tag->add_choice( 'H1', 'h1' );
	$title_tag->add_choice( 'H2', 'h2' );
	$title_tag->add_choice( 'H3', 'h3' );
	$title_tag->add_choice( 'H4', 'h4' );
	$section_block->add_field( $title_tag );

	$show_author = new Field\TrueFalse( 'Show author', 'show_author', 'show_author' );
	$show_author->set_message( 'Check this box to display the section author' );
	$section_block->add_field( $show_author );

	$show_date = new Field\TrueFalse( 'Show date', 'show_date', 'show_date' );
	$show_date->set_message( 'Check this box to display the section date' );
	$section_block->add_field( $show_date );
}
