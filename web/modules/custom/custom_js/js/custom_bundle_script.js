// Custom JavaScript for a specific bundle or node.
(function ($) {
  Drupal.behaviors.customBundleScript = {
    attach: function (context, settings) {
      // Your custom JS code here.
      console.log('Hello, this is JavaScript for a specific bundle or node.');
    }
  };
})(jQuery);
