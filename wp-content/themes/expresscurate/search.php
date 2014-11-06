<?php
/**
 * The search template file.
 * 
 * @author ExpressCurate <support@expresscurate.com>
 * @license GPLv3 or later (http://www.gnu.org/licenses/gpl.html)
 * @package com.expresscurate
 * @subpackage wordpresstheme
 * @since 1.0
 */
get_header();
?> 
<div class="contentWrapper searchContentWrapper" id="container">
  <?php get_search_form(true); ?>
  <div class="content" id="content" role="main">
 
    <?php
    if (isset($_GET["s"])) {
      get_template_part('loop', 'none');
    }else{
	?>
	 <div class="displayNone">
    <?php
      get_template_part('loop', 'none');
    ?>
	</div>
	<?php } ?>
  </div><!-- #content -->
</div><!-- #container -->
<?php get_footer(); ?>
