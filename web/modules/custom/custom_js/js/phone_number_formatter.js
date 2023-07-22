(function ($) {
  Drupal.behaviors.phoneNumberFormatter = {
    attach: function (context) {
      // Find the phone number input field by its name attribute.
      var $phoneNumberField = $('input[name="phone_number"]', context);

      // Attach an input event handler to the phone number field.
      $phoneNumberField.on('input', function (event) {
        var phoneNumber = event.target.value.replace(/\D/g, '');

        // Check if the phone number has 10 digits.
        if (phoneNumber.length === 10) {
          // Format the phone number as (xxx) xxx-xxxx.
          var formattedPhoneNumber = '(' + phoneNumber.substring(0, 3) + ') ' + phoneNumber.substring(3, 6) + '-' + phoneNumber.substring(6);
          event.target.value = formattedPhoneNumber;
        }
      });
    }
  };
})(jQuery, Drupal);
