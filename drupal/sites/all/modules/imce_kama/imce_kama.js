// $Id: imce_kama.js,v 1.1 2010/07/09 13:35:34 mcrittenden Exp $

// Allow for expanding and contracting of the sidebar navigation
Drupal.behaviors.imceKama = function(context) {
  $('.navigation-text').append('<a class="imce-close-nav" title="Close Navigation" href="#">X</a>');
  $('.imce-close-nav').click(function(){
    $('#navigation-wrapper').css('width','0');
  });
}
