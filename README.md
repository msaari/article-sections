# article-sections

- Developer: [msaari](https://github.com/msaari/)
- Tags: wordpress, acf, gutenberg, blocks

## Description

Article Sections is a WordPress plugin for creating article sections with a different author. This plugin adds a new custom post type (`ms_article_section`) and a Gutenberg block ("Article section") that can be used to include those posts inside other posts. This way you can have a section in an article that is counted for a different author.

## Requirements

- [Advanced Custom Fields Pro](https://www.advancedcustomfields.com/) is used to create the block.
- [ACF Codifier](https://github.com/devgeniem/acf-codifier) is used to define the fields (the fields can be defined in other ways, too, but this is the best).

## Usage

To add a section, first create a new Article Section post. Give the post a descriptive title (this title may appear on the post page, and will appear in the author archives) and fill up the content, all blocks should work fine. Set the author.

Then, in another post, add an Article Section block and choose the post from the post selection field. Now the section will be included on the page. You can choose whether the section title is displayed, the heading tag used for the title, and whether there's a byline with the author display name and the date.

The section will be placed inside a `section` tag.

## Filters

- `msaari_as_date_format` can be used to change the byline date format from the default `j.n.Y`.
- `msaari_as_section_class` can be used to change the `class` attribute for the `section` tag.

## Notes

The section post type is public, but doesn't have archives. The plugin modifies the author archives to include the sections. The section post permalinks are changed to point to the parent post. The link will have an anchor that points to the element itself. The anchor is the sanitized title of the post.

The featured image also uses the parent post featured image.

The parent post is set as the `post_parent` for the section. This assumes each section is only used in one post. In case the section appears in multiple posts, the links will point to the parent post that has been saved last.

If you are using [Relevanssi](https://wordpress.org/plugins/relevanssi/), the contents of the sections will be automatically indexed for the parent post. Do not set Relevanssi to index the section post type directly. However, if you want phrase matching to find sections, have Relevanssi index the sections. The results will be processed so that duplicate results are not returned.

## Version history

- 1.1.5 - Fix a bug with thumbnails.
- 1.1.4 – Search result deduplication and better thumbnail support.
- 1.1.3 – Correct fix for the thumbnail loop, fixed parent post matching.
- 1.1.2 – Removes an infinite loop caused by a thumbnail filter.
- 1.1.1 – Fixes PHP errors in AJAX response.
- 1.1 – Bug fixing, changes the display from a div to a section.
- 1.0 – Initial release.
