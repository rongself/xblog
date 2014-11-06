<?php
/**
 * The single post template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @author ExpressCurate <support@expresscurate.com>
 * @license GPLv3 or later (http://www.gnu.org/licenses/gpl.html)
 * @package com.expresscurate
 * @subpackage wordpresstheme
 * @since 1.0
 */
get_header();
?>
<div class="largeContentWrap" id="container">
  <div class="largeContent" id="content" role="main">
    <div class="largeTextBlock floatLeft">
      <div class="topBlock">
        <div class="rightSideBlock topBorder0 topNewsWrap topNews">
          <div class="floatLeft width100">
            <?php echo expresscurate_get_logo_html(); ?>
            <div class="topPadding12 siteTitle"><?php bloginfo('name'); ?></div>
            <div class="clearBoth"></div>
          </div>
          <div class="clearBoth"></div>
        </div>
      </div>
      <?php while (have_posts()) : the_post(); ?>
        <div id="post-<?php the_ID(); ?>" <?php post_class('single_post'); ?>>
          <div class="topBottomBorder0 relativePos largeImageContainer entry_featured">
            <?php the_post_thumbnail(); ?>
            <?php
            $expresscurate_is_curated = get_post_meta(get_the_ID(), 'is_expresscurate', true);
            $expresscurate_is_curated_old = get_post_meta(get_the_ID(), 'is_wp_curation', true) + get_post_meta(get_the_ID(), 'is_expresscurate', true);
            if ($expresscurate_is_curated == 1 || $expresscurate_is_curated_old > 0) {
              ?>
              <span class="badge"><span></span></span>
            <?php } ?>
          </div>
          <div class="blockPadding">
            <?php
            if (has_post_thumbnail()) {
              ?>
              <div class="topBottomBorder0">
                <div itemprop="author" class="vcard" itemscope itemtype="http://schema.org/Person">
                  <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')) ?>"><span itemprop="image"><?php echo get_avatar(get_the_author_meta('ID'), 42); ?></span></a>
                  <div class="contact">
                    <?php expresscurate_posted_on(); ?>
                  </div>
                  <div class="clearBoth"></div>
                </div>
              </div>

              <h1 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf(esc_attr__('Permalink to %s', 'expresscurate'), the_title_attribute('echo=0')); ?>" rel="bookmark"><?php echo esc_attr(get_the_title()); ?></a></h1>
              <?php if (count(get_the_category())) : ?>
                <div class="buttonStyle leftMargin0">
                  <?php echo get_the_category_list(' ') ?><br />
                </div>
              <?php endif; ?>
              <div class="entry-content">
                <?php the_content(); ?>
                <?php wp_link_pages(array('before' => '<div class="page-link">' . __('Pages:', 'expresscurate'), 'after' => '</div>')); ?>
              </div><!-- .entry-content -->

            <?php } else {
              ?>
              <span class="badge" ><span></span></span>
              <div itemprop="author"  itemscope itemtype="http://schema.org/Person">
                <span itemprop="author"><?php echo get_avatar(get_the_author_meta('ID'), 42); ?></span>
                <div class="contact">
                  <?php expresscurate_posted_on(); ?>
                </div>
                <div class="clearBoth"></div>
              </div>

              <div class="entry_featured">
                <?php
                if (has_post_thumbnail()) {
                  the_post_thumbnail();
                }
                ?>
              </div>
              <h1 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf(esc_attr__('Permalink to %s', 'expresscurate'), the_title_attribute('echo=0')); ?>" rel="bookmark"><?php echo esc_attr(get_the_title()); ?></a></h1>

              <div class="topBottomBorder0 relativePos largeImageContainer entry_featured">
                <?php the_post_thumbnail(); ?>
                <span class="badge"><span></span></span>
              </div>

              <?php if (count(get_the_category())) : ?>
                <div class="buttonStyle leftMargin0">
                  <?php echo get_the_category_list(' ') ?><br />
                </div>
              <?php endif; ?>

              <div class="entry-content">
                <?php the_content(); ?>
                <?php wp_link_pages(array('before' => '<div class="page-link">' . __('Pages:', 'expresscurate'), 'after' => '</div>')); ?>
              </div><!-- .entry-content -->

            <?php } ?>
            <?php
            if ($post->comment_status == 'open') {
              comments_template(); // Get comments.php template
            } else {
              ?><p class="margin0 openSansLight fontWeightNormal fontSize18 lineHeight24 lightGray"><?php echo __('Comments are disabled', 'expresscurate'); ?></p><?php
            }
            ?>

          </div>
        </div><!-- #post-## -->
        <div class="clearBoth"></div>

      <?php endwhile; ?>
      <?php wp_reset_query(); ?>
      <?php
      $expresscurate_next_post = get_next_post();
      if (!empty($expresscurate_next_post)) {
        ?>
        <a  href="<?php echo get_permalink($expresscurate_next_post->ID); ?>" class="nextPost">
          <?php
          if (has_post_thumbnail($expresscurate_next_post->ID)) {
            $expresscurate_next_post_img = get_the_post_thumbnail($expresscurate_next_post->ID);
            echo $expresscurate_next_post_img;
          } else {
            preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $expresscurate_next_post->post_content, $expresscurate_matches);
            if (count($expresscurate_matches) > 0 && count($expresscurate_matches[1]) > 0) {
              $expresscurate_first_img = $expresscurate_matches[1][0];
              if ($expresscurate_first_img && exif_imagetype($expresscurate_first_img)) {
                ?>
                <img src="<?php echo $expresscurate_first_img ?>" alt="<?php echo esc_attr($expresscurate_next_post->post_title) ?>" />
                <?php
              }
            }
          }
          ?>
          <span class="nextPostLabel"><?php echo __('Up Next', 'expresscurate', 'expresscurate'); ?></span>
          <span class="infoBack">
            <span class="nextPostTitle">
              <?php echo esc_attr($expresscurate_next_post->post_title); ?>
            </span>
          </span>
        </a>
      <?php } ?>
    </div>
    <div class="clearBoth bottomPart"></div>
    <div class="bottomPart">
      <div class="rightSideBlock buttonsBlock bottomButtonsBlock">
        <?php
        $expresscurate_categories = get_categories(array('number' => 10, 'orderby' => 'count', 'order' => 'DESC'));
        foreach ($expresscurate_categories as $expresscurate_category) {
          ?>
          <a class="buttons" href="<?php echo get_category_link($expresscurate_category->term_id) ?>"><?php echo $expresscurate_category->name; ?> </a>
          <?php
        }
        ?>
      </div>
      <?php
      $expresscurate_tags = wp_get_post_tags($post->ID);
      if (count($expresscurate_tags) > 0) {
        ?>
        <ul class="twit rightSideBlock bottomTwit">
          <?php
          foreach ($expresscurate_tags as $expresscurate_tag) {
            $expresscurate_tag_link = get_tag_link($expresscurate_tag->term_id);
            ?>
            <li><a href='<?php echo $expresscurate_tag_link; ?>' title='<?php echo $expresscurate_tag->name; ?> <?php echo __('Tag', 'expresscurate') ?>' class='<?php $expresscurate_tag->slug; ?>'>#<?php echo $expresscurate_tag->name; ?></a></li>
          <?php }
          ?>
        </ul>
      <?php } ?>

      <?php
      $args = array('numberposts' => '5', 'post_status' => 'publish');
      $expresscurate_recent_posts = wp_get_recent_posts($args);
      if (count($expresscurate_recent_posts)) {
        ?>
        <div class="rightSideBlock marginTop13">
          <div class="recent_posts"><?php _e('Recent Posts', 'expresscurate'); ?></div>
        </div>
        <?php
        foreach ($expresscurate_recent_posts as $expresscurate_recent) {
          ?><div class="rightSideBlock margin0 topBorder0">
            <a href="<?php echo get_permalink($expresscurate_recent["ID"]); ?>" title="<?php echo esc_attr($expresscurate_recent["post_title"]); ?>" ><h2><?php echo esc_attr($expresscurate_recent["post_title"]); ?></h2></a>
            <?php expresscurate_posted_on_recent($expresscurate_recent["ID"]); ?>
          </div>
          <?php
        }
        ?>

      <?php } ?>
    </div>
    <div class="floatRight rightSide">
      <div class="rightSideBlock topBorder0" id="rightNews">
        <?php echo expresscurate_get_logo_html(); ?>
        <div class="topPadding12 siteTitle"><a href="<?php echo esc_url(get_home_url()); ?>"><?php bloginfo('name'); ?></a></div>
        <div class="clearBoth"></div>
      </div>
      <div class="rightSideBlock buttonsBlock">
        <?php
        foreach ($expresscurate_categories as $expresscurate_category) {
          ?>
          <a class="buttons" href="<?php echo get_category_link($expresscurate_category->term_id) ?>"><?php echo $expresscurate_category->name; ?> </a><br />
          <?php
        }
        ?>
      </div>
      <?php
      if (count($expresscurate_tags) > 0) {
        ?>
        <ul class="twit rightSideBlock">
          <?php
          foreach ($expresscurate_tags as $expresscurate_tag) {
            $expresscurate_tag_link = get_tag_link($expresscurate_tag->term_id);
            ?>
            <li><a href='<?php echo $expresscurate_tag_link; ?>' title='<?php echo $expresscurate_tag->name; ?> Tag' class='<?php $expresscurate_tag->slug; ?>'>#<?php echo $expresscurate_tag->name; ?></a></li>
          <?php }
          ?>
        </ul>
      <?php } ?>
      <?php
      if (count($expresscurate_recent_posts)) {
        ?>
        <div class="rightSideBlock">
          <div class="recent_posts"><?php _e('Recent Posts', 'expresscurate'); ?></div>
        </div>

        <?php
        foreach ($expresscurate_recent_posts as $expresscurate_recent) {
          ?><div class="rightSideBlock topBorder0">
            <a href="<?php echo get_permalink($expresscurate_recent["ID"]); ?>" title="<?php echo esc_attr($expresscurate_recent["post_title"]); ?>" ><h2><?php echo esc_attr($expresscurate_recent["post_title"]); ?></h2></a>
            <?php expresscurate_posted_on_recent($expresscurate_recent["ID"]); ?>
          </div>
          <?php
        }
        ?>
      <?php } ?>
    </div>
    <div class="clearBoth rightSideBlock"></div>
  </div><!-- #content -->
  <?php get_footer(); ?>