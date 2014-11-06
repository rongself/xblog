<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content
 * after.
 *
 * @author ExpressCurate <support@expresscurate.com>
 * @license GPLv3 or later (http://www.gnu.org/licenses/gpl.html)
 * @package com.expresscurate
 * @subpackage wordpresstheme
 * @since 1.0
 */
?>
</div><!-- .headerWrapper -->

</div><!-- #main -->
<?php
$expresscurate_fixed = 'footer';
if (is_single() || is_page() && isset($expresscurate__GET["s"])) {
  $expresscurate_fixed = '';
}
if (isset($expresscurate__GET["s"])) {
  $expresscurate_fixed = 'footer';
}
?>
<div class="clearBoth"></div>
<div id="footer" class="<?php echo $expresscurate_fixed; ?> footerBg" role="contentinfo">
  <div id="colophon" class="footerWrap">
    <span class="openSansLight fontSize14">
      <?php
      $expresscurate_options = get_option('expresscurate_theme_options');
      $expresscurate_footer_html = $expresscurate_options['expresscurate_theme_footer_html'];
      $expresscurate_footer_html_default = 'Copyright &copy; 2014 MyCompany.com.';
      if(!$expresscurate_footer_html){
        $expresscurate_footer_html = $expresscurate_footer_html_default;
      }
      $expresscurate_footer_html = trim(stripslashes(urldecode(urldecode($expresscurate_options['expresscurate_theme_footer_html']))));
      echo $expresscurate_footer_html;
      ?>
    </span> &nbsp;&nbsp;&nbsp;&nbsp;
    <span class="openSansLight fontSize14">
      <?php echo __('Powered by', 'expresscurate')?> <a href="http://expresscurate.com" target="_blank">ExpressCurate</a>.
    </span>
    <div id="up" class="upButton" ></div>
  </div> <!--#colophon -->

</div><!-- #footer -->
<?php
  wp_footer();
?>
</body>
</html>