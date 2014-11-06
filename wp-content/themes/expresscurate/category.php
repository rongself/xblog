<?php
/**
 * The main template file
 * 
 * @author ExpressCurate <support@expresscurate.com>
 * @license GPLv3 or later (http://www.gnu.org/licenses/gpl.html)
 * @package com.expresscurate
 * @subpackage wordpresstheme
 * @since 1.0
 */
get_header();
?>

<div class="headerWrapper">
  <div class="headerBg">
    <?php
    // Check if this is a post or page, if it has a thumbnail, and if it's a big one
    if (is_singular() && current_theme_supports('post-thumbnails') &&
            has_post_thumbnail($post->ID) &&
            ( /* $src, $width, $height */ $expresscurate_image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'post-thumbnail') ) &&
            $expresscurate_image[1] >= HEADER_IMAGE_WIDTH) :
      // Houston, we have a new header image!
      echo get_the_post_thumbnail($post->ID);
    elseif (get_header_image()) :
      ?>
      <img class="topHeaderImg" src="<?php expresscurate_header_image(); ?>" width="<?php echo HEADER_IMAGE_WIDTH; ?>" height="<?php echo HEADER_IMAGE_HEIGHT; ?>" alt="" />
    <?php endif; ?>

    <?php
    if (is_singular()) {
      $expresscurate_tags = wp_get_post_tags();
    } else {
      $expresscurate_tags = get_tags(array('orderby' => 'count', 'order' => 'DESC', 'number' => 10));
    }
    ?>

  </div>
  <div class="contentWrapper">
    <div class="headerTextBg">

      <div id="imgDiv" class="floatLeft">
        <?php echo expresscurate_get_logo_html(); ?>
      </div>
      <header class="floatLeft headerText">
        <h1 class="archive-title" id="site-title"><?php printf(__('Category: %s', 'expresscurate'), single_cat_title('', false)); ?></h1>
        <?php
        // Show an optional term description.
        $expresscurate_term_description = term_description();
        if (!empty($expresscurate_term_description)) :
          printf('<div class="openSansLight fontSize16 lineHeight24 gray catDescription">%s</div>', $expresscurate_term_description);
        endif;
        ?>
      </header><!-- .archive-header -->
    </div>
    <div class="clearBoth"></div>
  </div>
  <?php
  if (count($expresscurate_tags) > 0) {
    ?>
    <ul class="topTwit">
      <?php
      foreach ($expresscurate_tags as $expresscurate_tag) {
        $expresscurate_tag_link = get_tag_link($expresscurate_tag->term_id);
        ?>
        <li><a href='<?php echo $expresscurate_tag_link; ?>' title='<?php echo $expresscurate_tag->name; ?> <?php echo __('Tag', 'expresscurate') ?>' class='<?php $expresscurate_tag->slug; ?>'>#<?php echo $expresscurate_tag->name; ?></a></li>
      <?php }
      ?>
      <li class="clearBoth"></li>
    </ul>
    <div class="clearBoth"></div>
    <?php
  }
  ?>
  <div class="clearBoth"></div>
  <div  id="container">
    <div class="content" id="content" role="main">
      <?php
      get_template_part('loop', get_post_format());
      ?>
    </div><!-- #content -->

    <?php
    if (count($expresscurate_tags) > 0) {
      ?>
      <ul class="rightTwit">
        <?php
        foreach ($expresscurate_tags as $expresscurate_tag) {
          $expresscurate_tag_link = get_tag_link($expresscurate_tag->term_id);
          ?>
          <li><a href='<?php echo $expresscurate_tag_link; ?>' title='<?php echo $expresscurate_tag->name; ?> <?php echo __('Tag', 'expresscurate')?>' class='<?php $expresscurate_tag->slug; ?>'>#<?php echo $expresscurate_tag->name; ?></a></li>
        <?php }
        ?>
      </ul>

      <?php
    }
    ?>
  </div><!-- #container -->
  <?php get_footer(); ?>