(function() {

'use strict';

// Load configuration from checkout.json
var checkoutConfig = null;

// Try to load config from window.checkoutConfig (set by PHP) or fetch from JSON
if (window.checkoutConfig) {
  checkoutConfig = window.checkoutConfig;
  initializeCheckout();
} else {
  fetch('checkout.json')
    .then(function(response) { return response.json(); })
    .then(function(config) {
      checkoutConfig = config.checkout;
      initializeCheckout();
    })
    .catch(function(error) {
      console.error('Failed to load checkout config:', error);
      showError('Failed to load checkout configuration.');
    });
}

function initializeCheckout() {
  // Set Omise public key from config
  Omise.setPublicKey(checkoutConfig.omise.publicKey);

  var checkoutForm = document.getElementById('checkout-form');
  if (!checkoutForm) return;

  // Set form attributes from config
  checkoutForm.action = checkoutConfig.form.action;
  checkoutForm.method = checkoutConfig.form.method;

  // Bind submit handler
  checkoutForm.addEventListener('submit', submitHandler, false);
}

// Submit handler for checkout form.
function submitHandler(event) {
  event.preventDefault();

  if (!checkoutConfig) {
    showError('Configuration not loaded.');
    return;
  }

  var cardConfig = checkoutConfig.card;

  /*
  NOTE: Using `data-name` to prevent sending credit card information fields to the backend server via HTTP Post
  (according to the security best practice https://www.omise.co/security-best-practices#never-send-card-data-through-your-servers).
  */
  var cardInformation = {
    name:             document.querySelector('[data-name="' + cardConfig.nameOnCard.dataName + '"]').value,
    number:           document.querySelector('[data-name="' + cardConfig.number.dataName + '"]').value,
    expiration_month: document.querySelector('[data-name="' + cardConfig.expiry.month.dataName + '"]').value,
    expiration_year:  document.querySelector('[data-name="' + cardConfig.expiry.year.dataName + '"]').value,
    security_code:    document.querySelector('[data-name="' + cardConfig.securityCode.dataName + '"]').value
  };

  // Basic client-side validation (prevents sending empty values to Omise).
  if (!cardInformation.name || !cardInformation.number || !cardInformation.expiration_month || !cardInformation.expiration_year || !cardInformation.security_code) {
    showError('Please fill in all card fields.');
    return;
  }

  Omise.createToken('card', cardInformation, function(statusCode, response) {
    if (statusCode === 200) {
      // Success: send back the TOKEN_ID to your server. The TOKEN_ID can be
      // found in `response.id`.
      var tokenField = document.querySelector('input[name="' + checkoutConfig.omise.tokenFieldName + '"]');
      if (tokenField) {
        tokenField.value = response.id;
      }

      event.target.submit();
    }
    else {
      // Error: display an error message. Note that `response.message` contains
      // a preformatted error message. Also note that `response.code` will be
      // "invalid_card" in case of validation error on the card.
      console.error('Omise tokenization error', statusCode, response);
      showError((response && response.message) ? response.message : 'Unable to create token.');
    }
  });
}

function showError(message) {
  var el = document.getElementById('error-message');
  if (el) el.textContent = message;
  else alert(message);
}

})();
