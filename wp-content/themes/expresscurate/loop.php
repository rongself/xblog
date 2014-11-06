<?php
/**
 * The loop that displays posts.
 *
 * @author ExpressCurate <support@expresscurate.com>
 * @license GPLv3 or later (http://www.gnu.org/licenses/gpl.html)
 * @package com.expresscurate
 * @subpackage wordpresstheme
 * @since 1.0
 */
?>

<?php /* Display navigation to next/previous pages when applicable */ ?>
<?php /* if ( $wp_query->max_num_pages > 1 ) : ?>
  <div id="nav-above" class="navigation">
  <div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'expresscurate' ) ); ?></div>
  <div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'expresscurate' ) ); ?></div>
  </div><!-- #nav-above -->
  <?php endif; */ ?>

<?php /* If there are no posts to display, such as an empty archive page */ ?>
<?php if (!have_posts()) : ?>
  <div id="post-0" class="post error404 not-found">
    <h1 class="entry-title"><?php _e('Not Found', 'expresscurate'); ?></h1>
    <div class="entry-content">
      <p><?php _e('Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'expresscurate'); ?></p>
    </div><!-- .entry-content -->
  </div><!-- #post-0 -->
<?php endif; ?>
<?php while (have_posts()) : the_post(); ?>
  <div id="post-<?php the_ID(); ?>" <?php post_class('posts'); ?>>
    <?php
    $expresscurate_is_curated = get_post_meta(get_the_ID(), 'is_expresscurate', true);
    $expresscurate_is_curated_old = get_post_meta(get_the_ID(), 'is_wp_curation', true) + get_post_meta(get_the_ID(), 'is_expresscurate', true);
    if ($expresscurate_is_curated == 1 || $expresscurate_is_curated_old > 0) {
      ?>
      <span class="badge"><span></span></span>
      <?php } else {?>
	  <div class="stickyBadge"></div>
	  <?php } ?>
    <div class="entry_featured">
      <?php
      $expresscurate_first_img = "";
      if (has_post_thumbnail()) {
        ?>
        <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" >
          <?php the_post_thumbnail(array(800, 200)); ?>
        </a>
        <?php
      } else {
        $expresscurate_output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $expresscurate_matches);
        if(count($expresscurate_matches)>0 && count($expresscurate_matches[1])>0){
          $expresscurate_first_img = $expresscurate_matches[1][0];
        }
      }
      ?>
    </div>
    <h2 class="entry-title header1Pos"><a href="<?php the_permalink(); ?>" title="<?php printf(esc_attr__('Permalink to %s', 'expresscurate'), the_title_attribute('echo=0')); ?>" rel="bookmark"><?php echo esc_attr(get_the_title()); ?></a></h2>

    <?php if (count(get_the_category())) : ?>
      <div class="buttonStyle">
        <?php echo get_the_category_list(' ') ?><br />
      </div>
    <?php endif; ?>

    <div class="entry-summary ">

		<div class="entrySummaryContent">
		  <?php if ($expresscurate_first_img && exif_imagetype($expresscurate_first_img)) {
			$expresscurate_imgsize = getimagesize($expresscurate_first_img);
			
		  ?>
		  <div class="fimgContainer">
			<a href="<?php the_permalink() ?>"><img src="<?php echo $expresscurate_first_img ?>" alt="<?php echo $post->post_title?>" class="smallImgMargins"/></a>
			</div>
		  <?php }
		  ?>
		  <?php the_excerpt(__('more', 'expresscurate')); ?>
		  <?php wp_link_pages(array('before' => '<div class="page-link">' . __('Pages:', 'expresscurate'), 'after' => '</div>')); ?>
		</div>
      <div class="contactWrapper">
        <div class="contact floatRight hide maxWidth120">
          <?php expresscurate_posted_on(); ?>
        </div>
        <a class="author_avatar" href="<?php echo get_author_posts_url(get_the_author_meta('ID')) ?>"><?php echo get_avatar(get_the_author_meta('ID'), 42); ?></a>
        <div class="clearBoth"></div>
      </div>
      <div class="clearBoth"></div>

    </div><!-- .entry-content -->

    <?php //expresscurate_get_comment(get_the_ID()); ?>

  </div><!-- #post-## -->


<?php endwhile; // End the loop.  ?>

<?php /* Display navigation to next/previous pages when applicable */ ?>
<?php if ($wp_query->max_num_pages > 1) : ?>
  <div id="nav-below" class="navigation clearBoth">
    <div class="nav-previous"><?php next_posts_link(__('<span class="meta-nav">&larr;</span> Older posts', 'expresscurate')); ?></div>
    <div class="nav-next"><?php previous_posts_link(__('Newer posts <span class="meta-nav">&rarr;</span>', 'expresscurate')); ?></div>
  </div><!-- #nav-below -->
<?php endif; ?>
