/******************************************************************************
* form.js handles the logic for the form on index.html
******************************************************************************/
var FormStuff = {

  init: function() {
    // kick it off once, in case the radio is already checked when the page loads
    this.applyConditionalRequired();
    this.bindUIActions();
  },

  bindUIActions: function() {
    // when a radio or checkbox changes value, click or otherwise
    $("input[type='radio'], input[type='checkbox']").on("change", this.applyConditionalRequired);
  },

  // set when certain fields are required
  applyConditionalRequired: function() {

    // certain fields will be required when the associated radio or checkbox are checked
    $(".require-if-active").each(function() {
      var require_element = $(this);
      // find the pairing radio or checkbox
      if ($(require_element.data("require-pair")).is(":checked")) {
        // if its checked, the field should be required
        require_element.prop("required", true);
    } else {
        // otherwise it should not
        require_element.prop("required", false);
      }
    });

    //certain fields will be required when the associated radio or checkbox are
    //not checked
    $(".require-if-inactive").each(function() {
        var hide_element = $(this);
        // find the pairing radio or checkbox
        if ($(hide_element.data("require-pair")).is(":checked")) {
            // if it is checked, the field should not be required
            hide_element.prop("required", false);
        } else {
            // otherwise it should be.
            hide_element.prop("required", true);
        }
    });
  }
};

FormStuff.init();
