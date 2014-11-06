<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage ExpressCurate
 * @since ExpressCurate 1.0
 */
get_header();
?>
<div id="container" class="error404">
  <div id="content" role="main">
    <div class="error">
      <span class="firstNum404">4</span>
      <span class="secondNum404">0</span>
      <span class="thirdNum404">4</span>
      <div class="clearBoth"></div>
      <p>
        <?php echo __('Sorry, this page does not exist', 'expresscurate') ?>
      </p>
      <?php get_search_form(true); ?>
    </div>
  </div><!-- #content -->
</div><!-- #container -->
<?php get_footer(); ?>