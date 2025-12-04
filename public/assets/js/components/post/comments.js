export function toggleComments(postId) {
    const commentSection = document.getElementById(`commentSection-${postId}`);
    commentSection.style.display = commentSection.style.display === 'none' ? 'block' : 'none';
}

export function toggleCommentButton(input) {
    const postButton = input.closest('.comment-input-container').querySelector('.post-comment-btn');
    postButton.disabled = input.value.trim() === '';
    postButton.classList.toggle('enabled', input.value.trim() !== '');
}

export function postComment(postId) {
    const input = document.querySelector(`#commentSection-${postId} .comment-input`);
    const commentText = input.value.trim();

    if (commentText) {
        console.log(`Posting comment to post ${postId}: ${commentText}`);
        input.value = '';
        input.dispatchEvent(new Event('input'));
    }
}

export function toggleReplyInput(commentId) {
    const replyWrapper = document.getElementById(`replyInput-${commentId}`);
    replyWrapper.style.display = replyWrapper.style.display === 'none' ? 'flex' : 'none';
}

export function toggleReplyButton(input) {
    const postButton = input.closest('.comment-input-container').querySelector('.post-reply-btn');
    postButton.disabled = input.value.trim() === '';
    postButton.classList.toggle('enabled', input.value.trim() !== '');
}

export function postReply(commentId) {
    const input = document.querySelector(`#replyInput-${commentId} .reply-input`);
    const replyText = input.value.trim();

    if (replyText) {
        console.log(`Posting reply to comment ${commentId}: ${replyText}`);
        input.value = '';
        input.dispatchEvent(new Event('input'));
        document.getElementById(`replyInput-${commentId}`).style.display = 'none';
    }
}

export function loadMoreComments(postId) {
    console.log(`Loading more comments for post ${postId}`);
    document.querySelector(`#commentSection-${postId} .load-more-btn`).style.display = 'none';
}

export function likeComment(commentId) {
    console.log(`Liking comment ${commentId}`);
}
