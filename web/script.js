$(document).ready(function(){
  // Focus is placed on the URL field
  $('#url').focus(function(){
    $(this).addClass('focused');
    $('#explain').show();
  });
  
  // Focus is removed from the URL field
  $('#url').blur(function() {
    $(this).removeClass('focused');
    $('#explain').hide();
    if ($(this).val() == '') {
      $(this).val('http://');
    }
  });
  
  $('.answer').click(function() {
    $('.answer').hide();
    $('#ask').show();
    $('#url').focus();
    return false;
  });
});