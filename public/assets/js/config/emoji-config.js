/**
 * Shared Emoji Configuration
 * Used across the application for emoji pickers and reactions
 * Ensure this is the single source of truth for all emoji sets
 */

export const EMOJIS = {
    // Reaction emojis - matching your reactions modal
    reactions: ['😊', '😂', '❤️', '👍', '🎉', '🔥', '💯', '🙏', '👏', '✨', '💪', '🤔', '😍', '🥰', '😎'],
};

// Default emoji set used across the app (for emoji picker buttons)
export const DEFAULT_EMOJIS = EMOJIS.reactions;

/**
 * Get emojis for a specific context
 * @param {string} context - 'reactions', 'comments', 'all'
 * @returns {array} Array of emoji strings
 */
export function getEmojisForContext(context = 'reactions') {
    return EMOJIS[context] || DEFAULT_EMOJIS;
}
