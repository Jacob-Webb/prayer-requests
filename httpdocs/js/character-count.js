var char_element;
var max_chars = 300;

char_element = el = document.getElementById('prayer-request');
char_element.addEventListener('keyup', countCharacters, false);

function countCharacters(e) {
    var textEntered, countRemaining, counter;
    // get the number of characters in the tweet box
    textEntered = document.getElementById('prayer-request').value;
    // number left = number of characters - our maximum (140)
    counter = (max_chars - (textEntered.length));
    // access the div for characters remaining
    countRemaining = document.getElementById('characters-remaining');
    // put the number of characters left into that div!
    countRemaining.textContent = counter;
}
