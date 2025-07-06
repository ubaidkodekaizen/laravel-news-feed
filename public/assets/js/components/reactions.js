let hideTimeout = null;

function showReactions(el) {
    const wrapper = el.closest('.reaction-wrapper');
    const panel = wrapper.querySelector('.reaction-panel');

    panel.querySelectorAll('.reaction-emoji').forEach(emoji => {
        emoji.style.animation = 'none';
        emoji.offsetHeight;
        emoji.style.animation = '';
    });

    panel.classList.remove('d-none');
    clearTimeout(hideTimeout);
}

function hideReactions(el) {
    hideTimeout = setTimeout(() => {
        const panel = el.querySelector('.reaction-panel');
        if (panel) panel.classList.add('d-none');
    }, 250);
}

function cancelHide() {
    clearTimeout(hideTimeout);
}

function applyReaction(span, emoji, label) {
    const wrapper = span.closest('.reaction-wrapper');
    const iconWrapper = wrapper.querySelector('.reaction-icon');
    const labelEl = wrapper.querySelector('.reaction-label');

    if (iconWrapper) iconWrapper.textContent = emoji;
    if (labelEl) labelEl.textContent = label;

    wrapper.querySelector('.reaction-panel').classList.add('d-none');
}
