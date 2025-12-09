function togglePostText(btn) {
    const textBlock = btn.previousElementSibling;
    textBlock.classList.toggle('expanded');
    btn.textContent = textBlock.classList.contains('expanded') ? 'See less' : 'See more';
}
