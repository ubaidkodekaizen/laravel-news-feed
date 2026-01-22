import { Picker } from "https://esm.sh/emoji-picker-element@1.18.2";

/**
 * Flexible Emoji Picker Manager
 * Can be used with any button/input combination
 */
class EmojiPickerManager {
    constructor() {
        this.picker = null;
        this.currentTarget = null;

        // LinkedIn-style reactions
        this.linkedInReactions = [
            { emoji: "👍", label: "Like", type: "like" },
            { emoji: "❤️", label: "Love", type: "love" },
            { emoji: "👏", label: "Celebrate", type: "celebrate" },
            { emoji: "💪", label: "Support", type: "support" },
            { emoji: "💡", label: "Insightful", type: "insightful" },
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
        // Find all buttons with data-emoji-trigger attribute
        document.querySelectorAll("[data-emoji-trigger]").forEach((button) => {
            button.addEventListener("click", (e) => {
                e.preventDefault();
                e.stopPropagation();

                const targetSelector =
                    button.getAttribute("data-emoji-trigger");
                const target = targetSelector
                    ? document.querySelector(targetSelector)
                    : button
                          .closest(".comment-input-container, .post-composer")
                          ?.querySelector("input, textarea");

                if (target) {
                    this.show(button, target);
                }
            });
        });
    }

    initializeLinkedInReactions() {
        // For post/comment reactions (LinkedIn style)
        document
            .querySelectorAll("[data-linkedin-reactions]")
            .forEach((button) => {
                // Remove existing listeners to prevent duplicates
                const newButton = button.cloneNode(true);
                button.parentNode.replaceChild(newButton, button);

                newButton.addEventListener("mouseenter", (e) => {
                    const postId = newButton.getAttribute("data-post-id");
                    const commentId = newButton.getAttribute("data-comment-id");
                    this.showLinkedInReactions(newButton, postId, commentId);
                });
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
                        reaction.type,
                    );
                }
                this.hideLinkedInReactions();
            };
            panel.appendChild(reactionBtn);
        });

        // Position the panel FIXED to viewport
        const rect = button.getBoundingClientRect();
        const panelHeight = 80; // Approximate panel height

        panel.style.position = "fixed"; // Changed from absolute to fixed
        panel.style.bottom = `${window.innerHeight - rect.top + 8}px`; // Position above button
        panel.style.left = `${rect.left}px`;
        panel.style.zIndex = "10000";

        document.body.appendChild(panel);

        // Store the button reference to handle mouse leave properly
        let hideTimeout;
        const buttonElement = button.closest(".action-btn") || button;

        // Hide on mouse leave from both panel and button
        const handleMouseLeave = () => {
            hideTimeout = setTimeout(() => {
                this.hideLinkedInReactions();
            }, 300);
        };

        const handleMouseEnter = () => {
            clearTimeout(hideTimeout);
        };

        panel.addEventListener("mouseenter", handleMouseEnter);
        panel.addEventListener("mouseleave", handleMouseLeave);
        buttonElement.addEventListener("mouseleave", handleMouseLeave);

        // Clean up event listener when panel is removed
        panel.addEventListener("remove", () => {
            buttonElement.removeEventListener("mouseleave", handleMouseLeave);
        });
    }

    hideLinkedInReactions() {
        document
            .querySelectorAll(".linkedin-reactions-panel")
            .forEach((panel) => {
                panel.remove();
            });
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

const observer = new MutationObserver(() => {
    emojiPickerManager.refresh();
});

observer.observe(document.body, {
    childList: true,
    subtree: true,
});
