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



// Toggle comment section
document.querySelectorAll('.comment-trigger').forEach(button => {
    button.addEventListener('click', function() {
        const postContainer = this.closest('.post-container');
        const commentSection = postContainer.querySelector('.comment-section');

        // Toggle visibility
        if (commentSection.style.display === 'none') {
            commentSection.style.display = 'block';
        } else {
            commentSection.style.display = 'none';
        }
    });
});

// Enable/disable comment button based on input
document.querySelectorAll('.comment-input').forEach(input => {
    input.addEventListener('input', function() {
        const postButton = this.closest('.comment-input-container').querySelector('.post-comment-btn');
        postButton.disabled = this.value.trim() === '';
        postButton.classList.toggle('enabled', this.value.trim() !== '');
    });
});

// Enable/disable reply button based on input
document.querySelectorAll('.reply-input').forEach(input => {
    input.addEventListener('input', function() {
        const postButton = this.closest('.comment-input-container').querySelector('.post-reply-btn');
        postButton.disabled = this.value.trim() === '';
        postButton.classList.toggle('enabled', this.value.trim() !== '');
    });
});

// Toggle reply input
document.querySelectorAll('.reply-comment-btn').forEach(button => {
    button.addEventListener('click', function() {
        const replyWrapper = this.closest('.comment-body').querySelector('.reply-input-wrapper');
        replyWrapper.style.display = replyWrapper.style.display === 'none' ? 'flex' : 'none';
    });
});

// Load more comments
document.querySelectorAll('.load-more-btn').forEach(button => {
    button.addEventListener('click', function() {
        // In a real app, this would fetch more comments from the server
        alert('In a real implementation, this would load more comments from the server');
        this.style.display = 'none'; // Hide after loading
    });
});

// Post comment (simulated)
document.querySelectorAll('.post-comment-btn').forEach(button => {
    button.addEventListener('click', function() {
        const input = this.closest('.comment-input-container').querySelector('.comment-input');
        const commentText = input.value.trim();

        if (commentText) {
            alert(`In a real implementation, this would post the comment: "${commentText}"`);
            input.value = '';
            this.disabled = true;
            this.classList.remove('enabled');
        }
    });
});

// Post reply (simulated)
document.querySelectorAll('.post-reply-btn').forEach(button => {
    button.addEventListener('click', function() {
        const input = this.closest('.comment-input-container').querySelector('.reply-input');
        const replyText = input.value.trim();

        if (replyText) {
            alert(`In a real implementation, this would post the reply: "${replyText}"`);
            input.value = '';
            this.disabled = true;
            this.classList.remove('enabled');
            input.closest('.reply-input-wrapper').style.display = 'none';
        }
    });
});
