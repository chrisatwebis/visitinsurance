$(document).ready(function() {
  $('form.webform-client-form').each(function() {
    $('input#edit-previous', this)
      .click(function(event) {
        webformPrev(event, this.form.id);
      });
    $('input#edit-next', this)
      .click(function(event) {
        webformNext(event, this.form.id);
      });
  });
});

function webformNext(event, formId) {
  event.preventDefault();

  var postData = $("#" + formId).serialize();
  var nodeId = formId.substring(20);

  $.ajax({
    url: drupalBasePath + '?q=webform_ajax/' + nodeId + '/next',
    fid:formId,
    type: 'POST',
    data: postData,
    success: function(ajaxData) {
      $("#" + this.fid + "-errors").remove();
      $("#" + this.fid).replaceWith(ajaxData);
        $("#" + this.fid + ' input#edit-previous')
          .click(function(event) {
            webformPrev(event, this.form.id);
          });
        $("#" + this.fid + ' input#edit-next')
          .click(function(event) {
            webformNext(event, this.form.id);
          });
      }
  });
}

function webformPrev(event, formId) {
  event.preventDefault();

  var postData = $("#" + formId).serialize();
  var nodeId = formId.substring(20);

  $.ajax({
    url: drupalBasePath + '?q=webform_ajax/' + nodeId + '/previous',
    fid:formId,
    type: 'POST',
    data: postData,
    success: function(ajaxData) {
      $("#" + this.fid + "-errors").remove();
      $("#" + this.fid).replaceWith(ajaxData);
        $("#" + this.fid + ' input#edit-previous')
          .click(function(event) {
            webformPrev(event, this.form.id);
          });
        $("#" + this.fid + ' input#edit-next')
          .click(function(event) {
            webformNext(event, this.form.id);
          });
      }
  });
}
