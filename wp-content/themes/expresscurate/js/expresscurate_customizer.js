(function($) {
  wp.customize('expresscurate_post_title_color', function(value) {
    value.bind(function(to) {
      $('h2.entry-title a').css('color', to ? to : '');
    });
  });
  wp.customize('expresscurate_post_background_color', function(value) {
    value.bind(function(to) {
      $('.post').css('background-color', to ? to : '');
    });
  });
  wp.customize('expresscurate_post_footer_background_color', function(value) {
    value.bind(function(to) {
      $('.contactWrapper').css('background-color', to ? to : '');
    });
  });
  wp.customize('expresscurate_post_font_color', function(value) {
    value.bind(function(to) {
      $('.entry-summary').css('color', to ? to : '');
    });
  });
  wp.customize('expresscurate_post_font_size', function(value) {
    value.bind(function(to) {
      $('.entry-summary').css('font-size', to ? to : '');
    });
  });
  wp.customize('expresscurate_site_title_color', function(value) {
    value.bind(function(to) {
      $('#site-title a').css('color', to ? to : '');
    });
  });
  wp.customize('site_title', function(value) {
    value.bind(function(to) {
      $('#site-title').css('color', to ? to : '');
    });
  });
  wp.customize('expresscurate_tagline_color', function(value) {
    value.bind(function(to) {
      $('.headerText p').css('color', to ? to : '');
    });
  });
  wp.customize('expresscurate_logo', function(value) {
    value.bind(function(to) {
      $('#imgDiv img').attr('src', to ? to : 'wp-content/themes/expresscurate/images/logo.png');
    });
  });
  wp.customize('blogname', function(value) {
    value.bind(function(to) {
      $('#site-title a').html(to);
    });
  });
  wp.customize('blogdescription', function(value) {
    value.bind(function(to) {
      $('.headerText p').html(to);
    });
  });
})(jQuery)