const buttonPill = document.querySelectorAll('.button-pill');

function buttonPillActive(button) {
    button.classList.add('active');
    button.firstElementChild.classList.add('active');

    button.removeEventListener('click', buttonPillActive);
}

buttonPill.forEach(button => {
    button.addEventListener('click', () => buttonPillActive(button));
});