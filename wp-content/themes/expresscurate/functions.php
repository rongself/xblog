<?php
/**
 * ExpressCurate functions and definitions
 *
 * @author ExpressCurate <support@expresscurate.com>
 * @license GPLv3 or later (http://www.gnu.org/licenses/gpl.html)
 * @package com.expresscurate
 * @subpackage wordpresstheme
 * @since 1.0
 * @version 1.1
 */
/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * Used to set the width of images and content. Should be equal to the width the theme
 * is designed for, generally via the style.css stylesheet.
 */
/** Tell WordPress to run expresscurate_setup() when the 'after_setup_theme' hook is run. */
add_action('after_setup_theme', 'expresscurate_setup');

if (!function_exists('expresscurate_setup')):

  /**
   * Sets up theme defaults and registers support for various WordPress features.
   *
   * Note that this function is hooked into the after_setup_theme hook, which runs
   * before the init hook. The init hook is too late for some features, such as indicating
   * support post thumbnails.
   *
   * To override expresscurate_setup() in a child theme, add your own expresscurate_setup to your child theme's
   * functions.php file.
   *
   * @uses add_theme_support() To add support for post thumbnails and automatic feed links.
   * @uses register_nav_menus() To add support for navigation menus.
   * @uses add_theme_support('custom-background', $args); To add support for a custom background.
   * @uses load_theme_textdomain() For translation/localization support.
   * @uses add_theme_support('custom-header', $args); To add support for a custom header.
   * @uses register_default_headers() To register the default custom header images provided with the theme.
   * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
   * @uses add_theme_pages() To set a custom post thumbnail size.
   *
   * @since ExpressCurate 1.0
   */
  function expresscurate_setup() {
    if (!isset($content_width)) {
      $content_width = 1180; //pixels
    }
    add_theme_support('post-thumbnails');

    add_editor_style('editorStyle.css');

    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Make theme available for translation
    // Translations can be filed in the /languages/ directory
    load_theme_textdomain('expresscurate', get_template_directory() . '/languages');
    $locale = get_locale();
    $locale_file = get_template_directory() . "/languages/$locale.php";
    if (is_readable($locale_file))
      require_once( $locale_file );

    // This theme uses wp_nav_menu() in one location.
    register_nav_menus(array(
        'primary' => __('Primary Navigation', 'expresscurate'),
    ));

    // This theme allows users to set a custom background
    $background_args = array(
        'default-color' => '',
        'default-image' => '',
        'wp-head-callback' => '_custom_background_cb',
        'admin-head-callback' => '',
        'admin-preview-callback' => ''
    );
    add_theme_support('custom-background', $background_args);

    // Your changeable header business starts here
    if (!defined('HEADER_TEXTCOLOR'))
      define('HEADER_TEXTCOLOR', '');

    // No CSS, just IMG call. The %s is a placeholder for the theme template directory URI.
    if (!defined('HEADER_IMAGE'))
      define('HEADER_IMAGE', '%s/images/top_banner.png');

    // The height and width of your custom header. You can hook into the theme's own filters to change these values.
    // Add a filter to expresscurate_header_image_width and expresscurate_header_image_height to change these values.
    define('HEADER_IMAGE_WIDTH', apply_filters('expresscurate_header_image_width', 1000));
    define('HEADER_IMAGE_HEIGHT', apply_filters('expresscurate_header_image_height', 237));

    // We'll be using post thumbnails for custom header images on posts and pages.
    // We want them to be 940 pixels wide by 198 pixels tall.
    // Larger images will be auto-cropped to fit, smaller ones will be ignored. See header.php.
    set_post_thumbnail_size(HEADER_IMAGE_WIDTH, HEADER_IMAGE_HEIGHT, true);

    // Don't support text inside the header image.
    if (!defined('NO_HEADER_TEXT'))
      define('NO_HEADER_TEXT', true);

    // Add a way for the custom header to be styled in the admin panel that controls
    // custom headers. See expresscurate_admin_header_style(), below.
    //add_custom_image_header('', 'expresscurate_admin_header_style');
    $args = array(
        'default-image' => '',
        'random-default' => false,
        'width' => 0,
        'height' => 0,
        'flex-height' => false,
        'flex-width' => false,
        'default-text-color' => '',
        'header-text' => true,
        'uploads' => true,
        'wp-head-callback' => '',
        'admin-head-callback' => '',
        'admin-preview-callback' => '',
    );
    add_theme_support('custom-header', $args);

    // ... and thus ends the changeable header business.
    // Default custom headers packaged with the theme. %s is a placeholder for the theme template directory URI.
    register_default_headers(array(
        'top1' => array(
            'url' => '%s/images/top_banner.png',
            'thumbnail_url' => '%s/images/top_banner.png',
            /* translators: header image description */
            'description' => __('Top banner', 'expresscurate')
        ),
        'top2' => array(
            'url' => '%s/images/top_banner1.png',
            'thumbnail_url' => '%s/images/top_banner1.png',
            /* translators: header image description */
            'description' => __('Top banner 2', 'expresscurate')
        )
    ));
    //Save default options
    $expresscurate_options = get_option('expresscurate_theme_options', '');
    if (!$expresscurate_options) {
      $footer_html_default = 'Copyright &copy; 2014 MyCompany.com.';
      $default_options = array(
          'expresscurate_theme_footer_html' => urlencode($footer_html_default),
          'expresscurate_theme_header_image_url' => '',
          'expresscurate_call_for_comment' => 'Have something to add? Share it in the comments.'
      );
      update_option('expresscurate_theme_options', $default_options);
    }
  }

endif;

add_filter('wp_title', 'expresscurate_the_title');
add_filter('the_content', 'expresscurate_the_content');
add_filter('the_excerpt', 'expresscurate_the_excerpt');
add_filter('get_the_excerpt', 'expresscurate_the_excerpt');

function expresscurate_the_title($title) {
  global $page, $paged;

  if (is_feed()) {
    return $title;
  }
  $site_description = get_bloginfo('description');
  $filtered_title = $title . get_bloginfo('name');
  $filtered_title .= (!empty($site_description) && ( is_home() || is_front_page() ) ) ? ' | ' . $site_description : '';
  $filtered_title .= ( 2 <= $paged || 2 <= $page ) ? ' | ' . sprintf(__('Page %s', 'expresscurate'), max($paged, $page)) : '';

  return $filtered_title;
}

function expresscurate_the_content($content) {
  $content .= '<div class="expresscurate_clear"></div>';
  return $content;
}

function expresscurate_the_excerpt($content) {
  $content .= '<div class="expresscurate_clear"></div>';
  return $content;
}

function expresscurate_header_image() {
  $header_image_url = get_option("header_image_url");
  if (!$header_image_url) {
    $header_image_url = header_image();
  }
  //echo $header_image_url;
  return $header_image_url;
}

function expresscurate_get_logo_html() {
  $logo_url = get_theme_mod('expresscurate_logo') ? get_theme_mod('expresscurate_logo') : get_template_directory_uri() . '/images/logo.png';
  if (!$logo_url) {
    return;
  }
  $logo_html = '<a href = "' . esc_url(get_home_url()) . '"><img src="' . $logo_url . '" /></a>';
  return $logo_html;
}

function expresscurate_get_logo() {
  $logo_url = get_theme_mod('expresscurate_logo') ? get_theme_mod('expresscurate_logo') : get_template_directory_uri() . '/images/logo.png';
  if (!$logo_url) {
    return;
  }
  return $logo_url;
}

if (!function_exists('expresscurate_admin_header_style')) :

  /**
   * Styles the header image displayed on the Appearance > Header admin panel.
   *
   * Referenced via add_custom_image_header() in expresscurate_setup().
   *
   * @since ExpressCurate 1.0
   */
  function expresscurate_admin_header_style() {
    ?>
    <style type="text/css">
      /* Shows the same border as on front end */
      #headimg {
        border-bottom: 1px solid #000;
        border-top: 4px solid #000;
      }
      /* If NO_HEADER_TEXT is false, you would style the text with these selectors:
        #headimg #name { }
        #headimg #desc { }
      */
    </style>
    <?php
  }

endif;

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * To override this in a child theme, remove the filter and optionally add
 * your own function tied to the wp_page_menu_args filter hook.
 *
 * @since ExpressCurate 1.0
 */
function expresscurate_page_menu_args($args) {
  $args['show_home'] = true;
  return $args;
}

add_filter('wp_page_menu_args', 'expresscurate_page_menu_args');

/**
 * Sets the post excerpt length to 40 characters.
 *
 * To override this length in a child theme, remove the filter and add your own
 * function tied to the excerpt_length filter hook.
 *
 * @since ExpressCurate 1.0
 * @return int
 */
function expresscurate_excerpt_length($length) {
  return 50;
}

add_filter('excerpt_length', 'expresscurate_excerpt_length');

/**
 * Returns a "Continue Reading" link for excerpts
 *
 * @since ExpressCurate 1.0
 * @return string "Continue Reading" link
 */
function expresscurate_continue_reading_link() {
  return ' <a href="' . get_permalink() . '">' . __('more', 'expresscurate') . '</a>';
}

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and expresscurate_continue_reading_link().
 *
 * To override this in a child theme, remove the filter and add your own
 * function tied to the excerpt_more filter hook.
 *
 * @since ExpressCurate 1.0
 * @return string An ellipsis
 */
function expresscurate_auto_excerpt_more($more) {
  return ' &hellip;' . expresscurate_continue_reading_link();
}

add_filter('excerpt_more', 'expresscurate_auto_excerpt_more');

/**
 * Adds a pretty "Continue Reading" link to custom post excerpts.
 *
 * To override this link in a child theme, remove the filter and add your own
 * function tied to the get_the_excerpt filter hook.
 *
 * @since ExpressCurate 1.0
 * @return string Excerpt with a pretty "Continue Reading" link
 */
function expresscurate_custom_excerpt_more($output) {
  if (has_excerpt()) {
    $output .= expresscurate_continue_reading_link();
  }

  return $output;
}

function expresscurate_truncateHtml($text, $length = 100, $ending = '...', $exact = false, $considerHtml = true) {
  if ($considerHtml) {
    // if the plain text is shorter than the maximum length, return the whole text
    if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
      return $text;
    }
    //remove all images
    $text = preg_replace('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', '', $text);
    // splits all html-tags to scanable lines
    preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);

    $total_length = strlen($ending);
    $open_tags = array();
    $truncate = '';
    foreach ($lines as $line_matchings) {
      // if there is any html-tag in this line, handle it and add it (uncounted) to the output
      if (!empty($line_matchings[1])) {
        // if it's an "empty element" with or without xhtml-conform closing slash
        if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
          // do nothing
          // if tag is a closing tag
        } else if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
          // delete tag from $open_tags list
          $pos = array_search($tag_matchings[1], $open_tags);
          if ($pos !== false) {
            unset($open_tags[$pos]);
          }
          // if tag is an opening tag
        } else if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
          // add tag to the beginning of $open_tags list
          array_unshift($open_tags, strtolower($tag_matchings[1]));
        }
        // add html-tag to $truncate'd text
        $truncate .= $line_matchings[1];
      }
      // calculate the length of the plain text part of the line; handle entities as one character
      $content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
      if ($total_length + $content_length > $length) {
        // the number of characters which are left
        $left = $length - $total_length;
        $entities_length = 0;
        // search for html entities
        if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
          // calculate the real length of all entities in the legal range
          foreach ($entities[0] as $entity) {
            if ($entity[1] + 1 - $entities_length <= $left) {
              $left--;
              $entities_length += strlen($entity[0]);
            } else {
              // no more characters left
              break;
            }
          }
        }
        $truncate .= substr($line_matchings[2], 0, $left + $entities_length);
        // maximum lenght is reached, so get off the loop
        break;
      } else {
        $truncate .= $line_matchings[2];
        $total_length += $content_length;
      }
      // if the maximum length is reached, get off the loop
      if ($total_length >= $length) {
        break;
      }
    }
  } else {
    if (strlen($text) <= $length) {
      return $text;
    } else {
      $truncate = substr($text, 0, $length - strlen($ending));
    }
  }
  // if the words shouldn't be cut in the middle...
  if (!$exact) {
    // ...search the last occurance of a space...
    $spacepos = strrpos($truncate, ' ');
    if (isset($spacepos)) {
      // ...and cut the text in this position
      $truncate = substr($truncate, 0, $spacepos);
    }
  }
  // add the defined ending to the text
  $truncate .= $ending;
  if ($considerHtml) {
    // close all unclosed html-tags
    foreach ($open_tags as $tag) {
      $truncate .= '</' . $tag . '>';
    }
  }
  return $truncate;
}

function expresscurate_improved_trim_excerpt($text) {
  global $post;
  $text = get_the_content('');
  remove_shortcode('gallery');
  $text = strip_shortcodes($text);
  $text = preg_replace('/\[gallery ids=[^\]]+\]/', '', $text);
  $text = apply_filters('the_content', $text);
  $text = preg_replace('@<script[^>]*?>.*?</script>@si', '', $text);
  $excerpt_length = 300;
  if (strlen($text) > $excerpt_length) {
    $text = expresscurate_truncateHtml($text, $excerpt_length, '... ' . expresscurate_continue_reading_link());
  }
  return $text;
}

//add_filter('get_the_excerpt', 'expresscurate_custom_excerpt_more');
remove_filter('get_the_excerpt', 'wp_trim_excerpt');
add_filter('get_the_excerpt', 'expresscurate_improved_trim_excerpt');

/**
 * Remove inline styles printed when the gallery shortcode is used.
 *
 * Galleries are styled by the theme in ExpressCurate's style.css. This is just
 * a simple filter call that tells WordPress to not use the default styles.
 *
 * @since ExpressCurate 1.2
 */
add_filter('use_default_gallery_style', '__return_false');

/**
 * Deprecated way to remove inline styles printed when the gallery shortcode is used.
 *
 * This function is no longer needed or used. Use the use_default_gallery_style
 * filter instead, as seen above.
 *
 * @since ExpressCurate 1.0
 * @deprecated Deprecated in ExpressCurate 1.2 for WordPress 3.1
 *
 * @return string The gallery style filter, with the styles themselves removed.
 */
function expresscurate_remove_gallery_css($css) {
  return preg_replace("#<style type='text/css'>(.*?)</style>#s", '', $css);
}

if (!function_exists('expresscurate_comment')) :

  /**
   * Template for comments and pingbacks.
   *
   * To override this walker in a child theme without modifying the comments template
   * simply create your own expresscurate_comment(), and that function will be used instead.
   *
   * Used as a callback by wp_list_comments() for displaying the comments.
   *
   * @since ExpressCurate 1.0
   */
  function expresscurate_remove_comment_fields($fields) {
    unset($fields['url']);
    return $fields;
  }

  function expresscurate_comment($comment, $args, $depth) {
    $args = array(
        'style' => 'div',
        'short_ping' => true,
        'avatar_size' => '',
        'reply_text' => '',
    );
    $GLOBALS['comment'] = $comment;
    switch ($comment->comment_type) :
      case '' :
        ?>
        <div id="comment-<?php comment_ID(); ?>">
          <div class="commentInfo">
            <span class="floatLeft commenter">
              <div class="arrowUp arrowUpNew"></div>
              <?php printf(__('%s <span class="openSansLight">says:</span>', 'expresscurate'), sprintf('<cite class="fn">%s</cite>', get_comment_author_link())); ?>
            </span>
            <span class="floatRight commentDate"><a href="<?php echo esc_url(get_comment_link($comment->comment_ID)); ?>">
                <?php
                /* translators: 1: date, 2: time */
                printf(__('%1$s at %2$s', 'expresscurate'), get_comment_date(), get_comment_time());
                ?></a><?php edit_comment_link(__('(Edit)', 'expresscurate'), ' '); ?></span>
            <div class="clearBoth"></div>
          </div>
          <div class="commentWrapper margin0">
            <?php if ($comment->comment_approved == '0') : ?>
              <em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.', 'expresscurate'); ?></em>
              <br />
            <?php endif; ?>
            <div class="comment">

              <?php comment_text(); ?>
            </div>
          </div>
        </div>
        <!-- #comment-##  -->
        <?php
        break;
      case 'pingback' :
      case 'trackback' :
        ?>
        <li class="post pingback">
          <p><?php _e('Pingback:', 'expresscurate'); ?> <?php comment_author_link(); ?><?php edit_comment_link(__('(Edit)', 'expresscurate'), ' '); ?></p>
          <?php
          break;
      endswitch;
    }

  endif;

  /**
   * Removes the default styles that are packaged with the Recent Comments widget.
   *
   * To override this in a child theme, remove the filter and optionally add your own
   * function tied to the widgets_init action hook.
   *
   * This function uses a filter (show_recent_comments_widget_style) new in WordPress 3.1
   * to remove the default style. Using ExpressCurate 1.2 in WordPress 3.0 will show the styles,
   * but they won't have any effect on the widget in default ExpressCurate styling.
   *
   * @since ExpressCurate 1.0
   */
  function expresscurate_remove_recent_comments_style() {
    add_filter('show_recent_comments_widget_style', '__return_false');
  }

  add_action('widgets_init', 'expresscurate_remove_recent_comments_style');

  if (!function_exists('expresscurate_posted_on')) :

    /**
     * Prints HTML with meta information for the current post-date/time and author.
     *
     * @since ExpressCurate 1.0
     */
    function expresscurate_posted_on() {
      $is_curated = get_post_meta(get_the_ID(), 'is_expresscurate', true);
      $is_curated_old = get_post_meta(get_the_ID(), 'is_wp_curation', true) + get_post_meta(get_the_ID(), 'is_expresscurate', true);
      if ($is_curated == 1 || $is_curated_old > 0) {
        printf(__('<p class="%1$s">Curated by  %2$s </p> %3$s', 'expresscurate'), 'name margin0', sprintf('<span class="vcard author"><span class="fn person-name"><a class="author" rel="author" href="%1$s"> %2$s </a></span></span>', get_author_posts_url(get_the_author_meta('ID')), get_the_author()), sprintf('<div class="date updated margin0">%3$s</div>', get_author_posts_url(get_the_author_meta('ID')), esc_attr(sprintf(__('View all posts by %s', 'expresscurate'), get_the_author())), get_the_date()
                )
        );
      } else {
        printf(__('<p class="%1$s">Posted by  %2$s </p> %3$s', 'expresscurate'), 'name margin0', sprintf('<span class="vcard author"><span class="fn person-name"><a class="author" rel="author" href="%1$s"> %2$s </a></span></span>', get_author_posts_url(get_the_author_meta('ID')), get_the_author()), sprintf('<div class="date updated margin0">%3$s</div>', get_author_posts_url(get_the_author_meta('ID')), esc_attr(sprintf(__('View all posts by %s', 'expresscurate'), get_the_author())), get_the_date()
                )
        );
      }
    }

  endif;

  if (!function_exists('expresscurate_posted_on_recent')) :

    /**
     * Prints HTML with meta information for the current post-date/time and author.
     *
     * @since ExpressCurate 1.0
     */
    function expresscurate_posted_on_recent($post_id) {
      $is_curated = get_post_meta($post_id, 'is_expresscurate', true);
      $is_curated_old = get_post_meta($post_id, 'is_wp_curation', true);
      if ($is_curated == 1 || $is_curated_old == 1) {
        printf(__('<span class="%1$s">Curated by  %2$s </span>', 'expresscurate'), 'curated_by', sprintf('<a class="blue" rel="author" href="%1$s" title="%2$s">%3$s</a>', get_author_posts_url(get_the_author_meta('ID')), esc_attr(sprintf(__('View all posts by %s', 'expresscurate'), get_the_author())), get_the_author()
                )
        );
      } else {
        printf(__('<span class="%1$s">Posted by  %2$s </span>', 'expresscurate'), 'curated_by', sprintf('<a class="blue" rel="author" href="%1$s" title="%2$s">%3$s</a>', get_author_posts_url(get_the_author_meta('ID')), esc_attr(sprintf(__('View all posts by %s', 'expresscurate'), get_the_author())), get_the_author()
                )
        );
      }
    }

  endif;

  if (!function_exists('expresscurate_posted_in')) :

    /**
     * Prints HTML with meta information for the current post (category, tags and permalink).
     *
     * @since ExpressCurate 1.0
     */
    function expresscurate_posted_in() {
      // Retrieves tag list of current post, separated by commas.
      $tag_list = get_the_tag_list('', ', ');
      if ($tag_list) {
        $posted_in = __('This entry was posted in %1$s and tagged %2$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'expresscurate');
      } elseif (is_object_in_taxonomy(get_post_type(), 'category')) {
        $posted_in = __('This entry was posted in %1$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'expresscurate');
      } else {
        $posted_in = __('Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'expresscurate');
      }
      // Prints the string, replacing the placeholders.
      printf(
              $posted_in, get_the_category_list(', '), $tag_list, get_permalink(), the_title_attribute('echo=0')
      );
    }

  endif;

  if (!function_exists('expresscurate_get_comment')) :

    function expresscurate_get_comment($post_id) {
      $comments = get_comments(array('post_id' => $post_id));
      if (count($comments)) {
        printf(__('<div class="commentWrapper"><img src="%2$s/images/upArrow.png" class="upArrow"/>
						<p class="comment"><img class="openingQuote" src="%2$s/images/open.png">
						%1$s
						<img class="doubleQuote" src="%2$s/images/close.png"></p>
					</div>', 'expresscurate'), $comments[0]->comment_content, get_template_directory_uri());
      }
    }

  endif;

  if (!function_exists('expresscurate_comment')) :

    function expresscurate_comment() {
      echo '
        <div>
          <p class="margin0 openSansLight fontSize18 lineHeight24 lightGray">Leave your comment</p>
          <p class="info openSansLight">Your email address will not be published. When you start writing, required fields will appear*</p>
        </div>
        <div class="writeComWrap">
          <img src="' . get_template_directory_uri() . '/images/upArrow.png" class="upArrow"/>
          <textarea class="comment writeComment" placeholder="Write your comment here"></textarea>
        </div>
        <div class="postInfo">
          <div class="half floatLeft">
            <div class="namePlaceholder">
              <input type="text" placeholder="Name*">
            </div>
          </div>
          <div class="half floatRight">
            <div class="emailPlaceholder">
              <input type="text" placeholder="Email*">
            </div>
          </div>
          <div class="clearBoth"></div>
          <a href="#" class="postButton floatRight">Post Comment</a>
        </div>
        ';
    }

  endif;

  function expresscurate_scripts_method() {
    wp_enqueue_script(
            'expresscurate_infinity', get_template_directory_uri() . '/js/expresscurate_infinity.js', array('jquery')
    );
    wp_enqueue_script('masonry');

    wp_enqueue_script(
            'expresscurate_throttle_debounce', get_template_directory_uri() . '/js/throttle_debounce.js', array('jquery')
    );
    wp_enqueue_script(
            'expresscurate_slide', get_template_directory_uri() . '/js/expresscurate_slide.js', array('jquery')
    );
    wp_enqueue_script(
            'popup_slider', get_template_directory_uri() . '/js/expresscurate_popup_slider.js', array('jquery')
    );
    wp_enqueue_script(
            'imagesloaded.pkgd.min', get_template_directory_uri() . '/js/imagesloaded.pkgd.min.js', array('jquery')
    );
    wp_enqueue_script(
            'expresscurate', get_template_directory_uri() . '/js/expresscurate.js', array('jquery')
    );

    wp_enqueue_script('comment-reply');

    //for ajax validation
    wp_register_script('validation', 'http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js');
    wp_enqueue_script(
            'validation', array('jquery')
    );
  }

  add_action('wp_enqueue_scripts', 'expresscurate_scripts_method');

  function expresscurate_add_editor_styles() {
    add_editor_style('custom-editor-style.css');
  }

  add_action('init', 'expresscurate_add_editor_styles');
  add_action("admin_menu", "expresscurate_setup_theme_admin_menus");

  function expresscurate_setup_theme_admin_menus() {
    add_theme_page('ExpressCurate', 'ExpressCurate', 'manage_options', 'expresscurate_theme_settings', 'expresscurate_theme_settings_page');
    //dd_submenu_page('expresscurate', 'Settings', 'Settings', '0', 'expresscurate_settings', array(&$this, 'plugin_settings_page'), '');
  }

  function expresscurate_theme_settings_page() {
    include(sprintf("%s/settings.php", dirname(__FILE__)));
  }

  /**
   * Contains methods for customizing the theme customization screen.
   *
   * @link http://codex.wordpress.org/Theme_Customization_API
   * @since ExpressCurate 1.0
   */
  class ExpressCurate_Customize {

    /**
     * @see add_action('customize_register',$func)
     * @param \WP_Customize_Manager $wp_customize
     * @link http://ottopress.com/2012/how-to-leverage-the-theme-customizer-in-your-own-themes/
     * @since ExpressCurate 1.0
     */
    //1. Define a new section (if desired) to the Theme Customizer
    //2. Register new settings to the WP database...
    //3. Define the control itself (which links a setting to a section and renders the HTML controls)...
    public static function register($wp_customize) {
      $wp_customize->add_section('expresscurate_options', array(
          'title' => __('ExpressCurate Options', 'expresscurate'), //Visible title of section
          'priority' => 1, //Determines what order this appears in
          'capability' => 'edit_theme_options', //Capability needed to tweak
          'description' => __('Allows you to customize some example settings for ExpressCurate.', 'expresscurate'), //Descriptive tooltip
              )
      );
      // Links color
      $wp_customize->add_setting('expresscurate_link_textcolor', array(
          'default' => '#2BA6CB',
          'sanitize_callback' => 'sanitize_hex_color',
          'type' => 'theme_mod',
          'capability' => 'edit_theme_options',
          'transport' => 'postMessage',
              )
      );
      $wp_customize->add_control(new WP_Customize_Color_Control(
              $wp_customize, 'expresscurate_link_textcolor', array(
          'label' => __('Link Color', 'expresscurate'),
          'section' => 'expresscurate_options',
          'settings' => 'expresscurate_link_textcolor',
          'priority' => 1,
              )
      ));
      //Site title color
      $wp_customize->add_setting('expresscurate_site_title_color', array(
          'default' => '#000000',
          'sanitize_callback' => 'sanitize_hex_color',
          'type' => 'theme_mod',
          'capability' => 'edit_theme_options',
          'transport' => 'postMessage',
              )
      );
      $wp_customize->add_control(new WP_Customize_Color_Control(
              $wp_customize, 'expresscurate_site_title_color', array(
          'label' => __('Site Title Color', 'expresscurate'),
          'section' => 'expresscurate_options',
          'settings' => 'expresscurate_site_title_color',
          'priority' => 2,
              )
      ));
      //Tagline color
      $wp_customize->add_setting('expresscurate_tagline_color', array(
          'default' => '#343434',
          'sanitize_callback' => 'sanitize_hex_color',
          'type' => 'theme_mod',
          'capability' => 'edit_theme_options',
          'transport' => 'postMessage',
              )
      );
      $wp_customize->add_control(new WP_Customize_Color_Control(
              $wp_customize, 'expresscurate_tagline_color', array(
          'label' => __('Tagline Color', 'expresscurate'),
          'section' => 'expresscurate_options',
          'settings' => 'expresscurate_tagline_color',
          'priority' => 3,
              )
      ));
      //POST
      //Post color
      $wp_customize->add_setting('expresscurate_post_title_color', array(
          'default' => '#343434',
          'sanitize_callback' => 'sanitize_hex_color',
          'type' => 'theme_mod',
          'capability' => 'edit_theme_options',
          'transport' => 'postMessage',
              )
      );
      $wp_customize->add_control(new WP_Customize_Color_Control(
              $wp_customize, 'expresscurate_post_title_color', array(
          'label' => __('Post Title Color', 'expresscurate'),
          'section' => 'expresscurate_options',
          'settings' => 'expresscurate_post_title_color',
          'priority' => 4,
              )
      ));
      //Post background color
      $wp_customize->add_setting('expresscurate_post_background_color', array(
          'default' => '#FEFEFE',
          'sanitize_callback' => 'sanitize_hex_color',
          'type' => 'theme_mod',
          'capability' => 'edit_theme_options',
          'transport' => 'postMessage',
              )
      );
      $wp_customize->add_control(new WP_Customize_Color_Control(
              $wp_customize, 'expresscurate_post_background_color', array(
          'label' => __('Post background Color', 'expresscurate'),
          'section' => 'expresscurate_options',
          'settings' => 'expresscurate_post_background_color',
          'priority' => 5,
              )
      ));
      //Post footer background color
      $wp_customize->add_setting('expresscurate_post_footer_background_color', array(
          'default' => '#F0F0F0',
          'sanitize_callback' => 'sanitize_hex_color',
          'type' => 'theme_mod',
          'capability' => 'edit_theme_options',
          'transport' => 'postMessage',
              )
      );
      $wp_customize->add_control(new WP_Customize_Color_Control(
              $wp_customize, 'expresscurate_post_footer_background_color', array(
          'label' => __('Post footer background Color', 'expresscurate'),
          'section' => 'expresscurate_options',
          'settings' => 'expresscurate_post_footer_background_color',
          'priority' => 6,
              )
      ));
      //Post font color
      $wp_customize->add_setting('expresscurate_post_font_color', array(
          'default' => '#181818',
          'sanitize_callback' => 'sanitize_hex_color',
          'type' => 'theme_mod',
          'capability' => 'edit_theme_options',
          'transport' => 'postMessage',
              )
      );
      $wp_customize->add_control(new WP_Customize_Color_Control(
              $wp_customize, 'expresscurate_post_font_color', array(
          'label' => __('Post font color', 'expresscurate'),
          'section' => 'expresscurate_options',
          'settings' => 'expresscurate_post_font_color',
          'priority' => 7,
              )
      ));
      //Post font size
      $wp_customize->add_setting('expresscurate_post_font_size', array(
          'default' => '16px',
          'sanitize_callback' => 'sanitize_text_field',
          'type' => 'theme_mod',
          'capability' => 'edit_theme_options',
          'transport' => 'postMessage',
              )
      );
      $wp_customize->add_control(new WP_Customize_Control(
              $wp_customize, 'expresscurate_post_font_size', array(
          'label' => __('Post font size', 'expresscurate'),
          'section' => 'expresscurate_options',
          'settings' => 'expresscurate_post_font_size',
          'priority' => 8,
              )
      ));

      //Logo
      $wp_customize->add_setting('expresscurate_logo', array(
          'default' => get_template_directory_uri() . '/images/logo.png',
          'sanitize_callback' => 'sanitize_url',
          'type' => 'theme_mod',
          'capability' => 'edit_theme_options',
          'transport' => 'postMessage',
              )
      );
      $wp_customize->add_control(new WP_Customize_Image_Control(
              $wp_customize, 'expresscurate_logo', array(
          'label' => __('Upload a Logo', 'expresscurate'),
          'section' => 'expresscurate_options',
          'settings' => 'expresscurate_logo',
          'priority' => 9,
              )
      ));
    }

    /**
     * This will output the custom WordPress settings to the live theme's WP head.
     *
     * Used by hook: 'wp_head'
     *
     * @see add_action('wp_head',$func)
     * @since MyTheme 1.0
     */
    public static function header_output() {
      ?>
      <!--Customizer CSS-->
      <style type="text/css">
    <?php //self::generate_css('#site-title a', 'color', 'header_textcolor', '#');                                                              ?>
    <?php self::generate_css('body', 'background-color', 'background_color', '#'); ?>
    <?php self::generate_css('a', 'color', 'expresscurate_link_textcolor'); ?>
    <?php self::generate_css('h2.entry-title a', 'color', 'expresscurate_post_title_color'); ?>
    <?php self::generate_css('#site-title a', 'color', 'expresscurate_site_title_color'); ?>
    <?php self::generate_css('.headerText p', 'color', 'expresscurate_tagline_color'); ?>
    <?php self::generate_css('.post', 'background-color', 'expresscurate_post_background_color'); ?>
    <?php self::generate_css('.contactWrapper', 'background-color', 'expresscurate_post_footer_background_color'); ?>
    <?php self::generate_css('.entry-summary', 'font-size', 'expresscurate_post_font_size'); ?>
    <?php self::generate_css('.entry-summary', 'color', 'expresscurate_post_font_color'); ?>
      </style>
      <!--/Customizer CSS-->
      <?php
    }

    /**
     * This outputs the javascript needed to automate the live settings preview.
     * Also keep in mind that this function isn't necessary unless your settings
     * are using 'transport'=>'postMessage' instead of the default 'transport'
     * => 'refresh'
     *
     * Used by hook: 'customize_preview_init'
     *
     * @see add_action('customize_preview_init',$func)
     * @since ExpressCurate 1.0
     */
    public static function live_preview() {
      wp_enqueue_script(
              'expresscurate-themecustomizer', // Give the script a unique ID
              get_template_directory_uri() . '/js/expresscurate_customizer.js', // Define the path to the JS file
              array('jquery', 'customize-preview'), // Define dependencies
              '', // Define a version (optional)
              true // Specify whether to put in footer (leave this true)
      );
    }

    /**
     * This will generate a line of CSS for use in header output. If the setting
     * ($mod_name) has no defined value, the CSS will not be output.
     *
     * @uses get_theme_mod()
     * @param string $selector CSS selector
     * @param string $style The name of the CSS *property* to modify
     * @param string $mod_name The name of the 'theme_mod' option to fetch
     * @param string $prefix Optional. Anything that needs to be output before the CSS property
     * @param string $postfix Optional. Anything that needs to be output after the CSS property
     * @param bool $echo Optional. Whether to print directly to the page (default: true).
     * @return string Returns a single line of CSS with selectors and a property.
     * @since ExpressCurate 1.0
     */
    public static function generate_css($selector, $style, $mod_name, $prefix = '', $postfix = '', $echo = true) {
      $return = '';
      $mod = get_theme_mod($mod_name);
      if (!empty($mod)) {
        $return = sprintf('%s { %s:%s; }', $selector, $style, $prefix . $mod . $postfix
        );
        if ($echo) {
          echo $return;
        }
      }
      return $return;
    }

  }

// Setup the Theme Customizer settings and controls...
  add_action('customize_register', array('ExpressCurate_Customize', 'register'));
// Output custom CSS to live site
  add_action('wp_head', array('ExpressCurate_Customize', 'header_output'));
// Enqueue live preview javascript in Theme Customizer admin screen
  add_action('customize_preview_init', array('ExpressCurate_Customize', 'live_preview'));

//Ajax comments form
  function expresscurate_validation_init() {
    if (is_singular() && comments_open()) {
      ?>
      <script type="text/javascript">
        jQuery(document).ready(function($) {
          $('#commentform').validate({
            rules: {
              author: {
                required: true,
                minlength: 2,
                maxlength: 50
              },
              email: {
                required: true,
                maxlength: 50,
                email: true
              },
              comment: {
                required: true,
                minlength: 2
              }
            },
            messages: {
              author: {
                required: "<?php _e('Name is required', 'expresscurate') ?>",
                minlength: "<?php _e('Only one symbol has been entered', 'expresscurate') ?>",
                maxlength: "<?php _e('Email must be mix 50 symbols', 'expresscurate') ?>",
              },
              email: {
                required: "<?php _e('Email address is required', 'expresscurate') ?>",
                email: "<?php _e('Please enter a valid email address', 'expresscurate') ?>",
                minlength: "<?php _e('Email address must contain min 6 symbols', 'expresscurate') ?>",
                maxlength: "<?php _e('Email address must contain mix 50 symbols', 'expresscurate') ?>",
              },
              comment: {
                required: "<?php _e('Comment text is required', 'expresscurate') ?>",
                minlength: "<?php _e('What do you want to say?', 'expresscurate') ?>",
              }
            },
            errorElement: "span",
            errorPlacement: function(error, element) {
              element.parent().after(error);
            },
            success: function(element) {
            }
          });
        });
      </script>
      <?php
    }
  }

  add_action('wp_footer', 'expresscurate_validation_init');



