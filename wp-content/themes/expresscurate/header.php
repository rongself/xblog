<?php
/**
 * The Header.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @author ExpressCurate <support@expresscurate.com>
 * @license GPLv3 or later (http://www.gnu.org/licenses/gpl.html)
 * @package com.expresscurate
 * @subpackage wordpresstheme
 * @since 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
  <head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php wp_title( '|', true, 'right' ); ?></title>

    <link rel="stylesheet" type="text/css" media="all" href="<?php echo get_stylesheet_uri(); ?>" />
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    <?php if (is_single()) { ?>
      <?php rewind_posts(); ?>
    <?php } ?>
    <?php
    if (is_singular() && get_option('thread_comments')) {
      wp_enqueue_script('comment-reply');
    }
    wp_head();
    ?>
  </head>
  <body <?php body_class(); ?>>
    <div class="menuPartBg">
      <?php
      $expresscurate_menuContainer = 'menuPartWrap';
      if (is_single() || is_page()) {
        $expresscurate_menuContainer = 'menuPartWrapSingle';
      }
      ?>
      <div class="<?php echo $expresscurate_menuContainer ?>">
        <div id="largeMenu" class="largeMenu">
          <?php wp_nav_menu(array('theme_location' => 'primary', 'menu_class' => 'nav-menu')); ?>
          <div class="topRss floatRight">
            <a type="application/rss+xml" title="<?php bloginfo('name'); ?>" href="<?php bloginfo('rss2_url'); ?>" class="rss"></a>
          </div>
          <?php if (!is_search()) { ?>
			<div id="searchInLargeHeader" class="searchInLargeHeader">
            <?php get_search_form(true); ?>
			</div>
          <?php } ?>
          <div class="clearBoth"></div>
        </div>
        <div class="smallMenu">
          <div class="topHeader">
            <a href="<?php echo esc_url(home_url('/')); ?>">
              <img src="<?php echo expresscurate_get_logo() ?>" class="logo floatLeft">
              <span class="headerInMenu"><?php echo esc_attr(get_bloginfo('name', 'display')); ?></span>
              <div class="clearBoth"></div>
            </a>
          </div>
          <div id="smallMenuIcon" class="floatRight menuIcon">
          </div>
          <?php if (!is_search()) { ?>
            <div id="findIconInSmall" class="findIconInMenu">
            </div>
          <?php } ?>
          <div class="topRss floatRight">
            <a type="application/rss+xml" title="<?php bloginfo('name'); ?>" href="<?php bloginfo('rss2_url'); ?>" class="rss"></a>
          </div>
          <div class="clearBoth"></div>
          <?php if (!is_search()) { ?>
            <div id="searchInSmallHeader" class="searchWrapper">
				<div class="searchInSmallHeader">
					<?php get_search_form(true); ?>
				</div>
            </div>
          <?php } ?>
          <div id="smallMenuItems" class="displayNone">
            <?php wp_nav_menu(array('theme_location' => 'primary')); ?>
          </div>
        </div>
        <div id="searchInSmallHeader" class="searchWrapper">
          <?php get_search_form(true); ?>
        </div>
        <div id="smallMenuItems" class="displayNone">
          <?php wp_nav_menu(array('theme_location' => 'primary')); ?>
        </div>
      </div>
      <div class="clearBoth"></div>
      <div class="menuSideBarWrapper displayNone">
        <div id="menuSideBar" >
          <?php wp_nav_menu(array('theme_location' => 'primary')); ?>
        </div>
      </div>
    </div>
  </div>
</div>
<ul class="topLine">
  <li class="green"></li>
  <li class="yellow"></li>
  <li class="orange"></li>
  <li class="red"></li>
  <li class="lightGreen"></li>
  <li class="clearBoth"></li>
</ul>