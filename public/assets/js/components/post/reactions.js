let hideTimeout = null;

export function showReactions(el) {
    const wrapper = el.closest('.reaction-wrapper');
    const panel = wrapper.querySelector('.reaction-panel');

    // Reset animations
    panel.querySelectorAll('.reaction-emoji').forEach(emoji => {
        emoji.style.animation = 'none';
        emoji.offsetHeight;
        emoji.style.animation = '';
    });

    panel.classList.remove('d-none');
    clearTimeout(hideTimeout);
}

export function hideReactions(el) {
    hideTimeout = setTimeout(() => {
        const panel = el.querySelector('.reaction-panel');
        if (panel) panel.classList.add('d-none');
    }, 250);
}

export function cancelHide() {
    clearTimeout(hideTimeout);
}

export function applyReaction(span, emoji, label) {
    const wrapper = span.closest('.reaction-wrapper');
    const iconWrapper = wrapper.querySelector('.reaction-icon');
    const labelEl = wrapper.querySelector('.reaction-label');

    if (iconWrapper) iconWrapper.innerHTML = emoji;
    if (labelEl) labelEl.textContent = label;

    wrapper.querySelector('.reaction-panel').classList.add('d-none');
    const postId = wrapper.closest('.post-container').dataset.postId;
    console.log(`Reacted with ${emoji} to post ${postId}`);
}
