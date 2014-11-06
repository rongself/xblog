<?php
/**
 * The single post template file.
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
            <a href="<?php echo esc_url(get_home_url()); ?>">
              <img src="<?php echo expresscurate_get_logo() ?>" class="logo floatLeft">
            </a>
            <div class="topPadding12 siteTitle"><?php bloginfo('name'); ?></div>
            <div class="clearBoth"></div>
          </div>
          <div class="clearBoth"></div>
        </div>
      </div>
      <?php while (have_posts()) : the_post(); ?>
        <div id="post-<?php the_ID(); ?>" <?php post_class('single_post'); ?>>

          <?php
          if (has_post_thumbnail()) {
            ?>
            <div class="topBottomBorder0 ">
              <div>
                <?php echo get_avatar(get_the_author_meta('ID'), 42); ?>
                <div class="contact">
                  <?php expresscurate_posted_on(); ?>
                </div>
                <div class="clearBoth"></div>
              </div>
            </div>
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
              <?php if (count(get_the_category())) : ?>
                <div class="secondBlockButtons smallButtonsMarginNew smallCats">
                  <span class="openSansLight fontSize14 lineHeight14 darkGray"></span>
                  <?php echo get_the_category_list(' ') ?><br />
                </div>
              <?php endif; ?>
              <h1 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf(esc_attr__('Permalink to %s', 'expresscurate'), the_title_attribute('echo=0')); ?>" rel="bookmark"><?php echo esc_attr(get_the_title()); ?></a></h1>
              <div class="entry-content">
                <?php the_content(); ?>
                <?php wp_link_pages(array('before' => '<div class="page-link">' . __('Pages:', 'expresscurate'), 'after' => '</div>')); ?>
              </div><!-- .entry-content -->


            <?php } else {
              ?>
              <div class="blockPadding">
                <span class="badge"><span></span></span>
                <div>
                  <?php echo get_avatar(get_the_author_meta('ID'), 42); ?>
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
        <?php endwhile; ?>

      </div>
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
      $expresscurate_args = array('numberposts' => '5', 'post_status' => 'publish');
      $expresscurate_recent_posts = wp_get_recent_posts($expresscurate_args);
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
        <a href="<?php echo esc_url(get_home_url()); ?>">
          <img src="<?php echo expresscurate_get_logo() ?>" class="logo floatLeft">
        </a>
        <div class="topPadding12 siteTitle"><a href="<?php echo esc_url(get_home_url()); ?>"><?php bloginfo('name'); ?></a></div>
        <div class="clearBoth"></div>

<!--        <a rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?>" href="<?php bloginfo('rss2_url'); ?>" class="rss">RSS</a>-->

      </div>

      <div class="rightSideBlock buttonsBlock">
        <?php
        foreach ($expresscurate_categories as $expresscurate_categorie) {
          ?>
          <a class="buttons" href="<?php echo get_category_link($expresscurate_categorie->term_id) ?>"><?php echo $expresscurate_categorie->name; ?> </a><br />
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
            <li><a href='<?php echo $expresscurate_tag_link; ?>' title='<?php echo $expresscurate_tag->name; ?> <?php echo __('Tag', 'expresscurate') ?>' class='<?php $expresscurate_tag->slug; ?>'>#<?php echo $expresscurate_tag->name; ?></a></li>
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
  </div><!-- #content -->
</div><!-- #container -->
<?php get_footer(); ?>