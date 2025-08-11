export function togglePostText(postId) {
    const textBlock = document.getElementById(`postTextBlock-${postId}`);
    const toggleBtn = textBlock.nextElementSibling;

    textBlock.classList.toggle('expanded');
    toggleBtn.textContent = textBlock.classList.contains('expanded') ? 'See less' : 'See more';
}
