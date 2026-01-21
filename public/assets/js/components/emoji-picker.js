import { Picker } from 'https://esm.sh/emoji-picker-element@1.18.2';

/**
 * Flexible Emoji Picker Manager
 * Can be used with any button/input combination
 */
class EmojiPickerManager {
    constructor() {
        this.picker = null;
        this.currentTarget = null;
        this.init();
    }

    init() {
        // Create picker once
        this.picker = new Picker({ locale: 'en' });
        this.picker.style.position = 'absolute';
        this.picker.style.zIndex = '9999';
        this.picker.style.display = 'none';
        document.body.appendChild(this.picker);

        // Listen for emoji selection
        this.picker.addEventListener('emoji-click', (event) => {
            if (this.currentTarget) {
                this.insertEmoji(this.currentTarget, event.detail.unicode);
                this.hide();
            }
        });

        // Close picker when clicking outside
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.emoji-picker-element') &&
                !e.target.closest('[data-emoji-trigger]')) {
                this.hide();
            }
        });

        // Initialize all emoji triggers on page
        this.initializeTriggers();
    }

    initializeTriggers() {
        // Find all buttons with data-emoji-trigger attribute
        document.querySelectorAll('[data-emoji-trigger]').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();

                const targetSelector = button.getAttribute('data-emoji-trigger');
                const target = targetSelector ?
                    document.querySelector(targetSelector) :
                    button.closest('.comment-input-container, .post-composer')?.querySelector('input, textarea');

                if (target) {
                    this.show(button, target);
                }
            });
        });
    }

    show(button, targetInput) {
        this.currentTarget = targetInput;

        const rect = button.getBoundingClientRect();
        const pickerHeight = 350; // Approximate picker height
        const spaceBelow = window.innerHeight - rect.bottom;
        const spaceAbove = rect.top;

        // Position picker below or above button based on available space
        if (spaceBelow >= pickerHeight || spaceBelow > spaceAbove) {
            // Show below
            this.picker.style.top = `${rect.bottom + window.scrollY + 5}px`;
        } else {
            // Show above
            this.picker.style.top = `${rect.top + window.scrollY - pickerHeight - 5}px`;
        }

        this.picker.style.left = `${rect.left + window.scrollX}px`;
        this.picker.style.display = 'block';
    }

    hide() {
        this.picker.style.display = 'none';
        this.currentTarget = null;
    }

    insertEmoji(input, emoji) {
        const start = input.selectionStart || 0;
        const end = input.selectionEnd || 0;
        const text = input.value;

        // Insert emoji at cursor position
        const newText = text.substring(0, start) + emoji + text.substring(end);
        input.value = newText;

        // Move cursor after emoji
        const newCursorPos = start + emoji.length;
        input.setSelectionRange(newCursorPos, newCursorPos);

        // Focus back on input
        input.focus();

        // Trigger input event for any listeners (like character count, button enable)
        input.dispatchEvent(new Event('input', { bubbles: true }));
        input.dispatchEvent(new Event('change', { bubbles: true }));
    }

    // Method to reinitialize triggers after dynamic content loads
    refresh() {
        this.initializeTriggers();
    }
}

// Create global instance
const emojiPickerManager = new EmojiPickerManager();

// Export for manual refresh after dynamic content
window.emojiPickerManager = emojiPickerManager;
export default emojiPickerManager;

// Hide picker when modals close
document.addEventListener('hidden.bs.modal', () => {
    emojiPickerManager.hide();
});

// Refresh triggers when new content is added (for infinite scroll, etc.)
const observer = new MutationObserver(() => {
    emojiPickerManager.refresh();
});

observer.observe(document.body, {
    childList: true,
    subtree: true
});
