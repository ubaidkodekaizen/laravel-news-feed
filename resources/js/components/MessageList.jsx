import React, { useState, useEffect } from "react";
import { format, isToday, isYesterday, isSameDay } from "date-fns";
import { ref, onChildAdded, onChildRemoved } from "firebase/database";
import { database } from "../firebase.js";
import Avatar from "./Avatar.jsx"; // ✅ Add this

const DEFAULT_AVATAR =
    "https://static.vecteezy.com/system/resources/previews/009/292/244/non_2x/default-avatar-icon-of-social-media-user-vector.jpg";

// ✅ ADD conversationId as a prop
const MessageList = ({ messages, conversationId }) => {
    const getInitials = (firstName, lastName) => {
        const first = firstName?.charAt(0)?.toUpperCase() || "";
        const last = lastName?.charAt(0)?.toUpperCase() || "";
        return first + last;
    };

    const [selectedMessageId, setSelectedMessageId] = useState(null);
    const [messageList, setMessageList] = useState(messages);
    const [showReactBtns, setReactBtns] = useState({});

    // ✅ Filter and validate messages
    useEffect(() => {
        const validMessages = messages
            .filter(
                (msg) =>
                    msg &&
                    msg.id &&
                    msg.content &&
                    msg.created_at &&
                    msg.sender_id
            )
            .map((msg) => {
                // ✅ Ensure reactions is always an array
                if (
                    msg.reactions &&
                    typeof msg.reactions === "object" &&
                    !Array.isArray(msg.reactions)
                ) {
                    msg.reactions = Object.values(msg.reactions);
                } else if (!msg.reactions) {
                    msg.reactions = [];
                }
                return msg;
            });

        setMessageList(validMessages);
    }, [messages]);

    // FIREBASE: Listen to reaction changes for all messages
    useEffect(() => {
        if (!conversationId || messageList.length === 0) return;

        const unsubscribers = [];

        messageList.forEach((msg) => {
            // ✅ Skip if no valid ID
            if (!msg.id) return;

            // ✅ Skip optimistic/temp messages
            if (msg._optimistic || String(msg.id).startsWith("temp-")) {
                return;
            }

            const reactionsRef = ref(
                database,
                `messages/${conversationId}/${msg.id}/reactions`
            );

            // Listen for new reactions
            const unsubAdd = onChildAdded(reactionsRef, (snapshot) => {
                const newReaction = snapshot.val();

                if (newReaction) {
                    setMessageList((prevMessages) =>
                        prevMessages.map((message) =>
                            message.id === msg.id
                                ? {
                                      ...message,
                                      reactions: [
                                          ...(message.reactions || []).filter(
                                              (r) => r.id !== newReaction.id
                                          ),
                                          newReaction,
                                      ],
                                  }
                                : message
                        )
                    );
                }
            });

            // Listen for removed reactions
            const unsubRemove = onChildRemoved(reactionsRef, (snapshot) => {
                const removedReaction = snapshot.val();

                if (removedReaction) {
                    setMessageList((prevMessages) =>
                        prevMessages.map((message) =>
                            message.id === msg.id
                                ? {
                                      ...message,
                                      reactions: (
                                          message.reactions || []
                                      ).filter(
                                          (r) => r.id !== removedReaction.id
                                      ),
                                  }
                                : message
                        )
                    );
                }
            });

            unsubscribers.push(unsubAdd, unsubRemove);
        });

        return () => {
            unsubscribers.forEach((unsub) => unsub());
        };
    }, [conversationId, messageList.length]);

    const toggleReactBtns = (messageId) => {
        setReactBtns((prev) => ({
            ...prev,
            [messageId]: !prev[messageId],
        }));
    };

    const handleReact = async (messageId, emoji) => {
        try {
            const url = addReactionRoute(messageId);
            const response = await window.axios.post(
                url,
                { emoji },
                {
                    headers: {
                        Authorization: localStorage.getItem("sanctum-token"),
                    },
                }
            );

            // Close reaction buttons
            setReactBtns((prev) => ({
                ...prev,
                [messageId]: false,
            }));
        } catch (error) {
            console.error("Error adding reaction:", error);
        }
    };

    const handleRemoveReaction = async (messageId, emoji) => {
        try {
            const url = removeReactionRoute(messageId);
            const response = await window.axios.delete(url, {
                data: { emoji },
                headers: {
                    Authorization: localStorage.getItem("sanctum-token"),
                },
            });
        } catch (error) {
            console.error("Error removing reaction:", error);
        }
    };

    const groupMessagesByDate = (messages) => {
        const groups = [];
        let currentGroup = [];
        let currentDate = null;

        messages.forEach((message) => {
            // ✅ Check if created_at exists and is valid
            if (!message.created_at) {
                console.warn("Message missing created_at:", message);
                return;
            }

            const messageDate = new Date(message.created_at);

            // ✅ Check if date is valid
            if (isNaN(messageDate.getTime())) {
                console.warn("Invalid date for message:", message);
                return;
            }

            if (!currentDate || !isSameDay(currentDate, messageDate)) {
                if (currentGroup.length > 0) {
                    groups.push({
                        date: currentDate,
                        messages: currentGroup,
                    });
                }
                currentDate = messageDate;
                currentGroup = [message];
            } else {
                currentGroup.push(message);
            }
        });

        if (currentGroup.length > 0) {
            groups.push({
                date: currentDate,
                messages: currentGroup,
            });
        }

        return groups;
    };

    const formatDateHeader = (date) => {
        if (isToday(date)) {
            return "Today";
        } else if (isYesterday(date)) {
            return "Yesterday";
        } else {
            return format(date, "MMM d, yyyy");
        }
    };

    const messageGroups = groupMessagesByDate(messageList);

    return (
        <div className="messageBoxListContent">
            {messageGroups.map((group, groupIndex) => (
                <div key={groupIndex} className="messageGroup">
                    <div className="messageDateHeaderContainer">
                        <div className="messageDateHeader">
                            {formatDateHeader(group.date)}
                        </div>
                    </div>
                    {group.messages.map((msg, index) => {
                        // ✅ Skip messages with invalid dates
                        if (!msg.created_at) return null;

                        const messageDate = new Date(msg.created_at);
                        if (isNaN(messageDate.getTime())) return null;

                        return (
                            <div
                                key={msg.id || `msg-${index}`}
                                className={`messageBoxListItem ${
                                    msg.sender_id === window.userId
                                        ? "messageRight"
                                        : "messageLeft"
                                }`}
                            >
                                <div className="messageBoxListItemInner">
                                    <Avatar
                                        user={msg.sender}
                                        className="messageSenderPhoto"
                                    />
                                    <div
                                        className={`messageBoxListItemContent ${
                                            msg.sender_id === window.userId
                                                ? "bg-blue-600 text-black"
                                                : "bg-gray-100 text-gray-900"
                                        }`}
                                    >
                                        <div className="messageBoxListItemContentMsg">
                                            {msg.content}

                                            {/* ✅ Show sending indicator for optimistic messages */}
                                            {msg._optimistic && (
                                                <span
                                                    className="messageSending"
                                                    style={{
                                                        fontSize: "0.75rem",
                                                        color: "#999",
                                                        marginLeft: "8px",
                                                    }}
                                                >
                                                    ⏳ Sending...
                                                </span>
                                            )}

                                            <div className="messageBoxTime">
                                                {format(
                                                    new Date(msg.created_at),
                                                    "h:mm a"
                                                )}
                                            </div>
                                        </div>

                                        {msg.reactions &&
                                            msg.reactions.length > 0 && (
                                                <div className="messageReactions">
                                                    {msg.reactions.map(
                                                        (reaction) => (
                                                            <span
                                                                key={
                                                                    reaction.id
                                                                }
                                                                className="reaction"
                                                                onClick={() =>
                                                                    handleRemoveReaction(
                                                                        msg.id,
                                                                        reaction.emoji
                                                                    )
                                                                }
                                                                style={{
                                                                    cursor: "pointer",
                                                                }}
                                                                title="Click to remove"
                                                            >
                                                                {reaction.emoji}
                                                            </span>
                                                        )
                                                    )}
                                                </div>
                                            )}

                                        {/* Only show reaction button for non-optimistic messages */}
                                        {!msg._optimistic &&
                                            msg.id &&
                                            !String(msg.id).startsWith(
                                                "temp-"
                                            ) && (
                                                <div
                                                    className="messageReactIconBtn"
                                                    onClick={() =>
                                                        toggleReactBtns(msg.id)
                                                    }
                                                >
                                                    <i className="fa-regular fa-face-smile"></i>
                                                    <div
                                                        className={`messageReactionOptions ${
                                                            showReactBtns[
                                                                msg.id
                                                            ]
                                                                ? "messageReactionOptionsShow"
                                                                : "messageReactionOptionsHide"
                                                        }`}
                                                    >
                                                        <button
                                                            onClick={(e) => {
                                                                e.stopPropagation();
                                                                handleReact(
                                                                    msg.id,
                                                                    "👍"
                                                                );
                                                            }}
                                                        >
                                                            👍
                                                        </button>
                                                        <button
                                                            onClick={(e) => {
                                                                e.stopPropagation();
                                                                handleReact(
                                                                    msg.id,
                                                                    "❤️"
                                                                );
                                                            }}
                                                        >
                                                            ❤️
                                                        </button>
                                                        <button
                                                            onClick={(e) => {
                                                                e.stopPropagation();
                                                                handleReact(
                                                                    msg.id,
                                                                    "😂"
                                                                );
                                                            }}
                                                        >
                                                            😂
                                                        </button>
                                                        <button
                                                            onClick={(e) => {
                                                                e.stopPropagation();
                                                                handleReact(
                                                                    msg.id,
                                                                    "😮"
                                                                );
                                                            }}
                                                        >
                                                            😮
                                                        </button>
                                                        <button
                                                            onClick={(e) => {
                                                                e.stopPropagation();
                                                                handleReact(
                                                                    msg.id,
                                                                    "😢"
                                                                );
                                                            }}
                                                        >
                                                            😢
                                                        </button>
                                                    </div>
                                                </div>
                                            )}
                                    </div>
                                </div>
                            </div>
                        );
                    })}
                </div>
            ))}
        </div>
    );
};

export default MessageList;
