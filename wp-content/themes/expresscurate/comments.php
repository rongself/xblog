<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains comments and the comment form.
 *
 * @author ExpressCurate <support@expresscurate.com>
 * @license GPLv3 or later (http://www.gnu.org/licenses/gpl.html)
 * @package com.expresscurate
 * @subpackage wordpresstheme
 * @since 1.0
 */
if (post_password_required())
  return;
$expresscurate_options = wp_parse_args(get_option('expresscurate_theme_options'));
$expresscurate_call_for_comment = $expresscurate_options['expresscurate_call_for_comment'] ? $expresscurate_options['expresscurate_call_for_comment'] : 'Have something to add? Share it in the comments';
$expresscurate_commenter = wp_get_current_commenter();
$expresscurate_req = get_option('require_name_email');
$expresscurate_aria_req = ( $expresscurate_req ? " aria-required='true'" : '' );
$expresscurate_user = wp_get_current_user();
$expresscurate_user_identity = $expresscurate_user->exists() ? $expresscurate_user->display_name : '';
if ($expresscurate_user_identity) {
  $expresscurate_fields = array(
      'comment_field' => '<div class="writeComWrap"><div class="arrowUp"></div>
     <textarea class="comment writeComment" id="comment" name="comment" placeholder="Write your comment here" ' . $expresscurate_aria_req . '></textarea></div>',
      'author' => '<div class="postInfo"><div class="namePlaceholder">
     <input id="author" name="author" type="text" value="' . esc_attr($expresscurate_commenter ['comment_author']) . '" placeholder="' . __("Name", "expresscurate") . ( $expresscurate_req ? '*' : '' ) . '" ' . $expresscurate_aria_req . ' /></div>',
      'email' => '<div class="emailPlaceholder">
     <input id="email" name="email" type="email" placeholder="' . __("Email", "expresscurate") . ( $expresscurate_req ? '*' : '' ) . '" value="' . esc_attr($expresscurate_commenter['comment_author_email']) . '" ' . $expresscurate_aria_req . '></div></div>'
  );
  $expresscurate_comments_args = array(
  'fields' => $expresscurate_fields,
  'comment_field' => '<div class="writeComWrap"><div class="arrowUp"></div>
     <textarea class="comment writeComment" id="comment" name="comment" placeholder="' . __("Write your comment here", "expresscurate") . '" ' . $expresscurate_aria_req . '></textarea></div>',
  'title_reply' => '<div><p class="margin0 openSansLight fontWeightNormal fontSize18 lineHeight24 lightGray">' . __($expresscurate_call_for_comment, 'expresscurate') . '</p></div>',
  'comment_notes_after' => ''
  );
} else {
  $expresscurate_fields = array(
      'comment_field' => '<div class="writeComWrap"><div class="arrowUp"></div>
     <textarea class="comment writeComment" id="comment" name="comment" placeholder="' . __("Write your comment here", "expresscurate") . '" ' . $expresscurate_aria_req . '></textarea></div>',
      'author' => '<div class="postInfo"><div class="namePlaceholder">
     <input id="author" name="author" type="text" value="' . esc_attr($expresscurate_commenter ['comment_author']) . '" placeholder="' . __("Name", "expresscurate") . ( $expresscurate_req ? '*' : '' ) . '" ' . $expresscurate_aria_req . ' /></div>',
      'email' => '<div class="emailPlaceholder">
     <input id="email" name="email" type="email" placeholder="' . __("Email", "expresscurate") . ( $expresscurate_req ? '*' : '' ) . '" value="' . esc_attr($expresscurate_commenter['comment_author_email']) . '" ' . $expresscurate_aria_req . '></div></div>'
  );
  $expresscurate_comments_args = array(
      'fields' => $expresscurate_fields,
      'comment_field' => '',
      'title_reply' => '<div><p class="margin0 openSansLight fontWeightNormal fontSize18 lineHeight24 lightGray">' . __($expresscurate_call_for_comment, "expresscurate", 'expresscurate') . '</p></div>',
      'comment_notes_after' => ''
  );
}
?>

<?php
comment_form($expresscurate_comments_args);
?>
<br /><br />
<div class="clearBoth"></div>
<?php if (have_comments()) : ?>
  <?php
  wp_list_comments('type=comment');
  paginate_comments_links(array('number' => 2, 'prev_text' => '&laquo;', 'next_text' => '&raquo;'));
  ?>
<?php endif; // have_comments()
?>