import { Picker } from "https://esm.sh/emoji-picker-element@1.18.2";

/**
 * Flexible Emoji Picker Manager
 * Can be used with any button/input combination
 */
class EmojiPickerManager {
    constructor() {
        this.picker = null;
        this.currentTarget = null;
        this.initializedButtons = new Set();
        this.reactionShowTimeout = null;
        this.reactionHideTimeout = null;
        this.currentPanel = null;
        this.isHoveringButton = false;
        this.isHoveringPanel = false;

        // LinkedIn-inspired reactions matching backend
        this.linkedInReactions = [
            { emoji: "👍", label: "Appreciate", type: "appreciate" },
            { emoji: "🎉", label: "Cheers", type: "cheers" },
            { emoji: "💪", label: "Support", type: "support" },
            { emoji: "💡", label: "Insight", type: "insight" },
            { emoji: "🤔", label: "Curious", type: "curious" },
            { emoji: "😊", label: "Smile", type: "smile" }
        ];

        this.init();
    }

    init() {
        // Create picker once
        this.picker = new Picker({ locale: "en" });
        this.picker.style.position = "absolute";
        this.picker.style.zIndex = "9999";
        this.picker.style.display = "none";
        document.body.appendChild(this.picker);

        // Listen for emoji selection
        this.picker.addEventListener("emoji-click", (event) => {
            if (this.currentTarget) {
                this.insertEmoji(this.currentTarget, event.detail.unicode);
                this.hide();
            }
        });

        // Close picker when clicking outside
        document.addEventListener("click", (e) => {
            if (
                !e.target.closest(".emoji-picker-element") &&
                !e.target.closest("[data-emoji-trigger]") &&
                !e.target.closest(".linkedin-reactions-panel")
            ) {
                this.hide();
                this.hideLinkedInReactions();
            }
        });

        // Initialize all emoji triggers on page
        this.initializeTriggers();
        this.initializeLinkedInReactions();
    }

    initializeTriggers() {
        document.querySelectorAll("[data-emoji-trigger]").forEach((button) => {
            if (button.dataset.emojiInitialized) return;

            button.addEventListener("click", (e) => {
                e.preventDefault();
                e.stopPropagation();

                const targetSelector = button.getAttribute("data-emoji-trigger");
                const target = targetSelector
                    ? document.querySelector(targetSelector)
                    : button
                          .closest(".comment-input-container, .post-composer")
                          ?.querySelector("input, textarea");

                if (target) {
                    this.show(button, target);
                }
            });

            button.dataset.emojiInitialized = "true";
        });
    }

    initializeLinkedInReactions() {
        document.querySelectorAll("[data-linkedin-reactions]").forEach((button) => {
            if (button.dataset.reactionInitialized) return;

            const buttonElement = button.closest(".action-btn") || button;

            // Mouse enter on button
            buttonElement.addEventListener("mouseenter", () => {
                this.isHoveringButton = true;

                // Clear any hide timeout
                clearTimeout(this.reactionHideTimeout);

                // Show panel after 500ms delay
                this.reactionShowTimeout = setTimeout(() => {
                    if (this.isHoveringButton) {
                        const postId = button.getAttribute("data-post-id");
                        const commentId = button.getAttribute("data-comment-id");
                        this.showLinkedInReactions(button, postId, commentId);
                    }
                }, 500); // Show after 500ms hover
            });

            // Mouse leave from button
            buttonElement.addEventListener("mouseleave", () => {
                this.isHoveringButton = false;

                // Clear show timeout if we leave before panel shows
                clearTimeout(this.reactionShowTimeout);

                // Start hide countdown (3 seconds)
                this.scheduleHidePanel();
            });

            button.dataset.reactionInitialized = "true";
        });
    }

    showLinkedInReactions(button, postId, commentId) {
        // Remove existing panel if any
        this.hideLinkedInReactions();

        const panel = document.createElement("div");
        panel.className = "linkedin-reactions-panel";
        panel.setAttribute("data-panel", "true");

        this.linkedInReactions.forEach((reaction) => {
            const reactionBtn = document.createElement("button");
            reactionBtn.className = "linkedin-reaction-item";
            reactionBtn.setAttribute("data-type", reaction.type);
            reactionBtn.innerHTML = `
                <span class="reaction-emoji">${reaction.emoji}</span>
                <span class="reaction-label">${reaction.label}</span>
            `;
            reactionBtn.onclick = (e) => {
                e.preventDefault();
                e.stopPropagation();
                if (commentId) {
                    window.applyCommentReaction?.(commentId, reaction.type);
                } else if (postId) {
                    window.applyReaction?.(
                        button,
                        reaction.emoji,
                        reaction.label,
                        reaction.type
                    );
                }
                this.hideLinkedInReactions();
            };
            panel.appendChild(reactionBtn);
        });

        // Position the panel FIXED to viewport
        const rect = button.getBoundingClientRect();

        panel.style.position = "fixed";
        panel.style.bottom = `${window.innerHeight - rect.top + 8}px`;
        panel.style.left = `${rect.left}px`;
        panel.style.zIndex = "10000";

        document.body.appendChild(panel);
        this.currentPanel = panel;

        // Panel hover handlers
        panel.addEventListener("mouseenter", () => {
            this.isHoveringPanel = true;
            clearTimeout(this.reactionHideTimeout);
        });

        panel.addEventListener("mouseleave", () => {
            this.isHoveringPanel = false;
            this.scheduleHidePanel();
        });
    }

    scheduleHidePanel() {
        // Clear any existing hide timeout
        clearTimeout(this.reactionHideTimeout);

        // Wait 3 seconds, then check if still hovering
        this.reactionHideTimeout = setTimeout(() => {
            // Only hide if not hovering over button or panel
            if (!this.isHoveringButton && !this.isHoveringPanel) {
                this.hideLinkedInReactions();
            } else {
                // Still hovering, check again after another 3 seconds
                this.scheduleHidePanel();
            }
        }, 3000); // 3 seconds
    }

    hideLinkedInReactions() {
        clearTimeout(this.reactionShowTimeout);
        clearTimeout(this.reactionHideTimeout);

        document.querySelectorAll(".linkedin-reactions-panel").forEach((panel) => {
            panel.remove();
        });

        this.currentPanel = null;
        this.isHoveringButton = false;
        this.isHoveringPanel = false;
    }

    show(button, targetInput) {
        this.currentTarget = targetInput;

        const rect = button.getBoundingClientRect();
        const pickerHeight = 350;
        const spaceBelow = window.innerHeight - rect.bottom;
        const spaceAbove = rect.top;

        if (spaceBelow >= pickerHeight || spaceBelow > spaceAbove) {
            this.picker.style.top = `${rect.bottom + window.scrollY + 5}px`;
        } else {
            this.picker.style.top = `${rect.top + window.scrollY - pickerHeight - 5}px`;
        }

        this.picker.style.left = `${rect.left + window.scrollX}px`;
        this.picker.style.display = "block";
    }

    hide() {
        this.picker.style.display = "none";
        this.currentTarget = null;
    }

    insertEmoji(input, emoji) {
        const start = input.selectionStart || 0;
        const end = input.selectionEnd || 0;
        const text = input.value;

        const newText = text.substring(0, start) + emoji + text.substring(end);
        input.value = newText;

        const newCursorPos = start + emoji.length;
        input.setSelectionRange(newCursorPos, newCursorPos);

        input.focus();

        input.dispatchEvent(new Event("input", { bubbles: true }));
        input.dispatchEvent(new Event("change", { bubbles: true }));
    }

    refresh() {
        this.initializeTriggers();
        this.initializeLinkedInReactions();
    }
}

const emojiPickerManager = new EmojiPickerManager();

window.emojiPickerManager = emojiPickerManager;
export default emojiPickerManager;

document.addEventListener("hidden.bs.modal", () => {
    emojiPickerManager.hide();
});

// Use a debounced mutation observer to prevent infinite loops
let observerTimeout;
const observer = new MutationObserver(() => {
    clearTimeout(observerTimeout);
    observerTimeout = setTimeout(() => {
        emojiPickerManager.refresh();
    }, 100);
});

observer.observe(document.body, {
    childList: true,
    subtree: true,
});
