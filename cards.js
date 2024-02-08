var cards = document.querySelectorAll('.card');

document.addEventListener('click', function(event) {
  var clickedElement = event.target;

  // Check if the clicked element has the class 'servicep'
  if (clickedElement.classList.contains('serviceinfo')) {
    // Find the closest ancestor element with the class 'card'
    var card = clickedElement.closest('.card');

    // If a card is found, toggle the 'is-flipped' class
    if (card) {
      card.classList.toggle('is-flipped');
    }
  }
});
