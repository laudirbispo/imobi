/*
  http://github.com/danpalmer/jquery.complexify.js

  This code is distributed under the WTFPL v2:
*/
(function ($) {

  $.fn.extend({
    complexify: function(options, callback) {

      var MIN_COMPLEXITY = 8; // 12 chars with Upper, Lower and Number
      var MAX_COMPLEXITY = 16; //  25 chars, all charsets
      var CHARSETS = [
        // Commonly Used
        ////////////////////
        [0x0020, 0x0020], // Space
        [0x0030, 0x0039], // Numbers
        [0x0041, 0x005A], // Uppercase
        [0x0061, 0x007A], // Lowercase
        [0x0021, 0x002F], // Punctuation
        [0x003A, 0x0040], // Punctuation
        [0x005B, 0x0060], // Punctuation
        [0x007B, 0x007E], // Punctuation
        // Everything Els
      ];

      var defaults = {
        minimumChars: 8,
        strengthScaleFactor: 1,
        bannedPasswords: window.COMPLEXIFY_BANLIST || [],
        banMode: 'strict' // (strict|loose)
      };

      if($.isFunction(options) && !callback) {
        callback = options;
        options = {};
      }

      options = $.extend(defaults, options);

      function additionalComplexityForCharset(str, charset) {
        for (var i = str.length - 1; i >= 0; i--) {
          if (charset[0] <= str.charCodeAt(i) && str.charCodeAt(i) <= charset[1]) {
            return charset[1] - charset[0] + 1;
          }
        }
        return 0;
      }

      function inBanlist(str) {
        if (options.banMode === 'strict') {
          for (var i = 0; i < options.bannedPasswords.length; i++) {
            if (str.toLowerCase().indexOf(options.bannedPasswords[i].toLowerCase()) !== -1) {
                return true;
            }
          }
          return false;
        } else {
          return $.inArray(str, options.bannedPasswords) > -1 ? true : false;
        }
      }

      function evaluateSecurity() {
        var password = $(this).val();
        var complexity = 0, valid = false;

        // Reset complexity to 0 when banned password is found
        if (!inBanlist(password)) {

          // Add character complexity
          for (var i = CHARSETS.length - 1; i >= 0; i--) {
            complexity += additionalComplexityForCharset(password, CHARSETS[i]);
          }

        } else {
          complexity = 1;
        }

        // Use natural log to produce linear scale
        complexity = Math.log(Math.pow(complexity, password.length)) * (1/options.strengthScaleFactor);

        valid = (complexity > MIN_COMPLEXITY && password.length >= options.minimumChars);

        // Scale to percentage, so it can be used for a progress bar
        complexity = (complexity / MAX_COMPLEXITY) * 100;
        complexity = (complexity > 100) ? 100 : complexity;

        callback.call(this, valid, complexity);
      }

      this.each(function () {
      	if($(this).val()) {
          evaluateSecurity.apply(this);
        }
      });

      return this.each(function () {
        $(this).bind('keyup focus input propertychange mouseup', evaluateSecurity);
      });

    }
  });

})(jQuery);
