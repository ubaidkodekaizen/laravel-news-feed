export function togglePostText(postId) {
    const textBlock = document.getElementById(`postTextBlock-${postId}`);
    const toggleBtn = textBlock?.nextElementSibling;

    if (!textBlock || !toggleBtn) return;

    const fullContent = textBlock.dataset.fullContent;
    const isExpanded = textBlock.classList.contains('expanded');

    if (isExpanded) {
        // Collapse
        const truncated = fullContent.substring(0, 300);
        textBlock.innerHTML = escapeAndFormat(truncated);
        textBlock.classList.remove('expanded');
        toggleBtn.textContent = '...see more';
    } else {
        // Expand
        textBlock.innerHTML = escapeAndFormat(fullContent);
        textBlock.classList.add('expanded');
        toggleBtn.textContent = 'see less';
    }
}

function escapeAndFormat(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML.replace(/\n/g, '<br>');
}
