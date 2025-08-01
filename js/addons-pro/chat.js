const $myForm = $('#myForm');

$myForm.toggleClass('slim');

$('#chat').on('click', function () {
  console.log('click');
  if ($myForm.hasClass('slim') || !$myForm.is(':visible')) {

    $myForm.css('display', 'block');
    $myForm.removeClass('slim');
  };
})



$("#toggle").on('click', function () {

  $myForm.toggleClass('slim');
});