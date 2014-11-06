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
      <?php if (!is_author()) { ?>
        <div id="imgDiv" class="floatLeft">
          <?php echo expresscurate_get_logo_html(); ?>
        </div>
        <div class="floatLeft headerText">
          <?php $expresscurate_heading_tag = ( is_home() || is_front_page() ) ? 'h1' : 'div'; ?>
          <<?php echo $expresscurate_heading_tag; ?> id="site-title">
          <span>
            <a href="<?php echo esc_url(home_url('/')); ?>" title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>" rel="home"><?php bloginfo('name'); ?></a>
          </span>
          </<?php echo $expresscurate_heading_tag; ?>>
          <p class="openSansLight fontSize16 lineHeight24 gray"><?php bloginfo('description'); ?></p>

        </div>
      <?php } else { ?>
        <?php
        $expresscurate_cur_auth = (get_query_var('author_name')) ? get_user_by('slug', get_query_var('author_name')) : get_userdata(get_query_var('author'));
        $expresscurate_user_id = $expresscurate_cur_auth->ID;
        $expresscurate_author_name = get_the_author_meta('display_name', $expresscurate_user_id);
        ?>
        <div itemprop="author"  itemscope itemtype="http://schema.org/Person">
          <div id="imgDiv" class="floatLeft">
            <a href="<?php esc_url(get_home_url()); ?>"><span itemprop="image"><?php echo get_avatar($expresscurate_user_id); ?></span></a>
          </div>
          <span class="vcard author">
            <span class="fn person-name">
              <a href="<?php echo get_the_author_meta('expresscurate_gplus', $expresscurate_user_id) ? get_the_author_meta('expresscurate_gplus', $expresscurate_user_id) : get_author_posts_url($expresscurate_user_id); ?>" rel="author" target="_blank">
                <h1 itemprop="name">
                  <?php echo $expresscurate_author_name; ?>
                </h1>
              </a>
            </span>
          </span>
          <a href="<?php the_author_meta('user_url', $expresscurate_user_id); ?>" target="_blank"><span><?php the_author_meta('user_url', $expresscurate_user_id); ?></span></a>
          <p class="openSansLight fontSize16 lineHeight24 gray"><?php the_author_meta('description', $expresscurate_user_id); ?> </p>
        </div>
      <?php } ?>
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
        get_template_part('loop', 'index');
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
            <li><a href='<?php echo $expresscurate_tag_link; ?>' title='<?php echo $expresscurate_tag->name; ?> <?php echo __('Tag', 'expresscurate') ?>' class='<?php $expresscurate_tag->slug; ?>'>#<?php echo $expresscurate_tag->name; ?></a></li>
          <?php }
          ?>
          <li class="clearBoth"></li>
        </ul>
        <div class="clearBoth"></div>
        <?php
      }
      ?>
    </div><!-- #container -->
    <?php get_footer(); ?>