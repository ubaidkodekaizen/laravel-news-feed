import React, { useState, useEffect, useRef } from "react";
import { format, isToday, isYesterday, isSameDay } from "date-fns";
import { ref, onChildAdded, onChildRemoved } from "firebase/database";
import { database } from "../firebase.js";
import Avatar from "./Avatar.jsx";

const DEFAULT_AVATAR =
    "https://static.vecteezy.com/system/resources/previews/009/292/244/non_2x/default-avatar-icon-of-social-media-user-vector.jpg";

const InboxMessageList = ({ messages, conversationId }) => {
    const getInitials = (firstName, lastName) => {
        const first = firstName?.charAt(0)?.toUpperCase() || "";
        const last = lastName?.charAt(0)?.toUpperCase() || "";
        return first + last;
    };

    const [messageList, setMessageList] = useState(messages);
    const [showReactBtns, setReactBtns] = useState({});
    const conversationIdRef = useRef(conversationId);

    // Update ref when conversationId changes
    useEffect(() => {
        conversationIdRef.current = conversationId;
    }, [conversationId]);

    // Update local state when props change
    useEffect(() => {
        setMessageList(messages);
    }, [messages]);

    // ✅ ADD THIS ENTIRE BLOCK
    // FIREBASE: Listen to reaction changes
    useEffect(() => {
        if (!conversationId || messageList.length === 0) return;

        const unsubscribers = [];

        messageList.forEach((msg) => {
            // ✅ Skip optimistic/temp messages
            if (msg._optimistic || msg.id.toString().startsWith("temp-")) {
                return;
            }

            const reactionsRef = ref(
                database,
                `messages/${conversationId}/${msg.id}/reactions`
            );

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
    // ✅ END OF NEW BLOCK

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

            // Optimistically update UI (the broadcast will update other users)
            setMessageList((prevMessages) =>
                prevMessages.map((msg) =>
                    msg.id === messageId
                        ? {
                              ...msg,
                              reactions: [
                                  ...(msg.reactions || []),
                                  response.data,
                              ],
                          }
                        : msg
                )
            );

            // Close reaction buttons
            setReactBtns((prev) => ({
                ...prev,
                [messageId]: false,
            }));

            console.log("Reaction added:", response.data);
        } catch (error) {
            console.error("Error adding reaction:", error);
        }
    };

    const handleRemoveReaction = async (messageId, reactionId, emoji) => {
        try {
            const url = removeReactionRoute(messageId);
            const response = await window.axios.delete(url, {
                data: { emoji },
                headers: {
                    Authorization: localStorage.getItem("sanctum-token"),
                },
            });

            // Optimistically update UI (the broadcast will update other users)
            setMessageList((prevMessages) =>
                prevMessages.map((msg) =>
                    msg.id === messageId
                        ? {
                              ...msg,
                              reactions: (msg.reactions || []).filter(
                                  (r) => r.emoji !== emoji
                              ),
                          }
                        : msg
                )
            );

            console.log("Reaction removed:", response.data);
        } catch (error) {
            console.error("Error removing reaction:", error);
        }
    };

    const groupMessagesByDate = (messages) => {
        const groups = [];
        let currentGroup = [];
        let currentDate = null;

        messages.forEach((message) => {
            const messageDate = new Date(message.created_at);

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
                    {group.messages.map((msg, index) => (
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
                                                            key={reaction.id}
                                                            className="reaction"
                                                            onClick={() =>
                                                                handleRemoveReaction(
                                                                    msg.id,
                                                                    reaction.id,
                                                                    reaction.emoji
                                                                )
                                                            }
                                                            title="Click to remove"
                                                            style={{
                                                                cursor: "pointer",
                                                            }}
                                                        >
                                                            {reaction.emoji}
                                                        </span>
                                                    )
                                                )}
                                            </div>
                                        )}

                                    <div
                                        className="messageReactIconBtn"
                                        onClick={() => toggleReactBtns(msg.id)}
                                    >
                                        <i className="fa-regular fa-face-smile"></i>
                                        <div
                                            className={`messageReactionOptions ${
                                                showReactBtns[msg.id]
                                                    ? "messageReactionOptionsShow"
                                                    : "messageReactionOptionsHide"
                                            }`}
                                        >
                                            <button
                                                onClick={(e) => {
                                                    e.stopPropagation();
                                                    handleReact(msg.id, "👍");
                                                }}
                                            >
                                                👍
                                            </button>
                                            <button
                                                onClick={(e) => {
                                                    e.stopPropagation();
                                                    handleReact(msg.id, "❤️");
                                                }}
                                            >
                                                ❤️
                                            </button>
                                            <button
                                                onClick={(e) => {
                                                    e.stopPropagation();
                                                    handleReact(msg.id, "😂");
                                                }}
                                            >
                                                😂
                                            </button>
                                            <button
                                                onClick={(e) => {
                                                    e.stopPropagation();
                                                    handleReact(msg.id, "😮");
                                                }}
                                            >
                                                😮
                                            </button>
                                            <button
                                                onClick={(e) => {
                                                    e.stopPropagation();
                                                    handleReact(msg.id, "😢");
                                                }}
                                            >
                                                😢
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    ))}
                </div>
            ))}
        </div>
    );
};

export default InboxMessageList;
