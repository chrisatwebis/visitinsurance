/**
 * Update the image generation profile preview in realtime on the page
 */
 
Drupal.behaviors.imageapi_text = function (context) {
  $('input.font-selection').change(function () {
    // any time the form changes, collate the form values and update the image with those parameters
    update_text_preview();
  });
  $('textarea.font-style').change(function () {
    update_text_preview();
  });
};

//
// Any time values in the form change, bundle up the form values and GET 
// a new header image by changing the src of the image according to the new values.
// This is pretty rough, as I don't know what the form will end up looking like yet, 
// so I just iterate and send everything.
function update_text_preview() {
  // Grab all the interesting values out of the current form.
  var args = new Array();
  $('form input').each(function() { 
    // Different drupal forms may give my elements different names.
    // Strip it back - from "edit[data][fontfile]" to just "fontfile"
    var shortname = this.name.split('[').pop().split(']').shift();
    if (this.type == 'radio' || this.type == 'checkbox') {
      if (this.checked) args[shortname] = this.value;
    }
    else if ((this.type != 'hidden') && (this.type != 'submit')){
      args[shortname] = this.value;
    }
  })
  $('form textarea').each(function() { 
    var shortname = this.name.split('[').pop().split(']').shift();
    args[shortname] = this.value;
  })
  
  // Construct a new dynamic image generation URL
  var argstring = "?";
  for(arg in args) {
    argstring += arg + "=" + encodeURIComponent(args[arg]) + "&";
  }
  
  // Swap out the image with the new src request.
  // An image request URL is {CALLBACK}/{STRING}?{STYLE}
  // Rather than set the string each time, we set it to what it was before.
  var oldsrc = $('#imageapi-text-sample').attr('src');
  var srcparts = oldsrc.split('?');
  var newsrc = srcparts[0] + argstring;
  $('#imageapi-text-sample').attr('src', newsrc);    
}