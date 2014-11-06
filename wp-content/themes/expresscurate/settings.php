<?php
/**
 * The admin settings
 *
 * @author ExpressCurate <support@expresscurate.com>
 * @license GPLv3 or later (http://www.gnu.org/licenses/gpl.html)
 * @package com.expresscurate
 * @subpackage wordpresstheme
 * @since 1.0
 */
$expresscurate_footer_html_default = 'Copyright &copy; 2014 MyCompany.com.';
$expresscurate_defaults = array(
    'expresscurate_theme_footer_html' => $expresscurate_footer_html_default,
    'expresscurate_theme_header_image_url' => '',
    'expresscurate_call_for_comment' => 'Have something to add? Share it in the comments.'
);

if ($_POST) {
  $expresscurate_header_image_url = esc_attr($_POST["expresscurate_theme_header_image_url"]);
  $expresscurate_footer_html = urlencode(($_POST["expresscurate_theme_footer_html"]));
  

  $expresscurate_options = array(
      'expresscurate_theme_footer_html' => urlencode($expresscurate_footer_html),
      'expresscurate_theme_header_image_url' => $expresscurate_header_image_url,
      'expresscurate_call_for_comment' => esc_attr($_POST['expresscurate_call_for_comment'])
  );
  update_option('expresscurate_theme_options', $expresscurate_options);
}
$expresscurate_options = wp_parse_args(get_option('expresscurate_theme_options'), $expresscurate_defaults);
$expresscurate_header_image_url = $expresscurate_options['expresscurate_theme_header_image_url'];
$expresscurate_footer_html = trim(stripslashes(urldecode(urldecode(htmlentities($expresscurate_options['expresscurate_theme_footer_html'])))));
$expresscurate_call_for_comment = $expresscurate_options['expresscurate_call_for_comment'];
?>
<div class="wrap expresscurate">
  <?php screen_icon('themes'); ?> <h2>Advanced options</h2>

  <form method="POST" action="">
    <table class="form-table">
      <tr valign="top">
        <th scope="row">
          <label for="header_image_url">
            <strong><?php echo __('Header image URL', 'expresscurate') ?>:</strong>
          </label>
        </th>
      </tr>
      <tr valign="top">
        <td>
          <input type="text" class="wide-input with-max-width" name="expresscurate_theme_header_image_url" value="<?php echo $expresscurate_header_image_url ?>"/>  <br /><br />
        </td>
      </tr>

      <tr valign="top">
        <th scope="row">
          <label for="header_image_url">
            <strong> <?php echo __('Footer HTML', 'expresscurate') ?>:</strong>  <br/>
            <span class="gray-italic">This is the placeholder to define the privacy policy, terms of use, etc.</span>
          </label>
        </th>
      </tr>
      <tr valign="top">
        <td>
          <textarea type="text" class="with-max-width" name="expresscurate_theme_footer_html"> <?php echo $expresscurate_footer_html ?> </textarea>  <br /><br />
        </td>
      </tr>
      <tr valign="top">
        <th scope="row">
          <label for="expresscurate_call_for_comment">
            <strong> <?php echo __('Call for comment', 'expresscurate') ?>:</strong>  <br/>
            <span class="gray-italic"><?php echo __('Label to call for comments', 'expresscurate') ?></span>
          </label>
        </th>
      </tr>
      <tr valign="top">
        <td>
          <input type="text" id="expresscurate_call_for_comment" class="wide-input with-max-width" name="expresscurate_call_for_comment" value="<?php echo$expresscurate_call_for_comment; ?>"/>  <br /><br />
        </td>
      </tr>
    </table>
    <p>
      <input type="submit" value="Save settings" class="button-primary"/>
    </p>
  </form>
</div>