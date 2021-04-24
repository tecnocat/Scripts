$(document).ready(function() {
  var target = $("input").attr('id');
  var obj = window.parent.document.getElementById(target);
  if (obj.value) {
    var inn = document.getElementById(target);
    inn.value = obj.value;
  }
  $("#click").click(function() {
    var target = $(this).attr('title');
    var obj = window.parent.document.getElementById(target);
    var value = $('#'+target).attr('value');
    obj.value = value;
    window.parent.Lightbox.end('forceClose');
    return false; 
  });
});