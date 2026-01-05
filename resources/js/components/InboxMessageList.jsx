import React, { useState, useEffect, useRef } from "react";
import { format, isToday, isYesterday, isSameDay } from "date-fns";
import {
    ref,
    onChildAdded,
    onChildRemoved,
    onChildChanged,
} from "firebase/database";
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

    const [messageList, setMessageList] = useState([]);
    const [showReactBtns, setReactBtns] = useState({});
    const [editingMessageId, setEditingMessageId] = useState(null);
    const [editContent, setEditContent] = useState("");
    const [showMessageMenu, setShowMessageMenu] = useState({});
    const conversationIdRef = useRef(conversationId);
    const editTextareaRef = useRef(null);

    // Update ref when conversationId changes
    useEffect(() => {
        conversationIdRef.current = conversationId;
    }, [conversationId]);

    // ✅ Filter and validate messages before setting state
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

    // Focus textarea when editing starts
    useEffect(() => {
        if (editingMessageId && editTextareaRef.current) {
            editTextareaRef.current.focus();
            editTextareaRef.current.setSelectionRange(
                editTextareaRef.current.value.length,
                editTextareaRef.current.value.length
            );
        }
    }, [editingMessageId]);

    // FIREBASE: Listen to reaction changes AND message updates
    useEffect(() => {
        if (!conversationId || messageList.length === 0) return;

        const unsubscribers = [];

        messageList.forEach((msg) => {
            if (
                !msg.id ||
                msg._optimistic ||
                String(msg.id).startsWith("temp-")
            ) {
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
                                          ...(Array.isArray(message.reactions)
                                              ? message.reactions
                                              : []
                                          ).filter(
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
                                      reactions: (Array.isArray(
                                          message.reactions
                                      )
                                          ? message.reactions
                                          : []
                                      ).filter(
                                          (r) => r.id !== removedReaction.id
                                      ),
                                  }
                                : message
                        )
                    );
                }
            });

            // ✅ Listen for message content updates (edits)
            const messageRef = ref(
                database,
                `messages/${conversationId}/${msg.id}`
            );
            const unsubChange = onChildChanged(messageRef, (snapshot) => {
                if (
                    snapshot.key === "content" ||
                    snapshot.key === "edited_at"
                ) {
                    const updatedValue = snapshot.val();
                    setMessageList((prevMessages) =>
                        prevMessages.map((message) =>
                            message.id === msg.id
                                ? {
                                      ...message,
                                      [snapshot.key]: updatedValue,
                                  }
                                : message
                        )
                    );
                }
            });

            unsubscribers.push(unsubAdd, unsubRemove, unsubChange);
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

    const toggleMessageMenu = (messageId) => {
        setShowMessageMenu((prev) => ({
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

    const startEdit = (message) => {
        setEditingMessageId(message.id);
        setEditContent(message.content);
        setShowMessageMenu({});
    };

    const cancelEdit = () => {
        setEditingMessageId(null);
        setEditContent("");
    };

    const handleEdit = async (messageId) => {
        if (!editContent.trim()) {
            alert("Message cannot be empty");
            return;
        }

        if (
            editContent === messageList.find((m) => m.id === messageId)?.content
        ) {
            cancelEdit();
            return;
        }

        try {
            const url = updateMessageRoute(messageId);
            await window.axios.put(
                url,
                { content: editContent },
                {
                    headers: {
                        Authorization: localStorage.getItem("sanctum-token"),
                    },
                }
            );

            // Optimistically update UI
            setMessageList((prevMessages) =>
                prevMessages.map((msg) =>
                    msg.id === messageId
                        ? {
                              ...msg,
                              content: editContent,
                              edited_at: new Date().toISOString(),
                          }
                        : msg
                )
            );

            cancelEdit();
        } catch (error) {
            console.error("Error editing message:", error);
            alert("Failed to edit message");
        }
    };

    const handleDelete = async (messageId) => {
        if (!confirm("Are you sure you want to delete this message?")) {
            return;
        }

        try {
            const url = deleteMessageRoute(messageId);
            await window.axios.delete(url, {
                headers: {
                    Authorization: localStorage.getItem("sanctum-token"),
                },
            });

            // Optimistically remove from UI
            setMessageList((prevMessages) =>
                prevMessages.filter((msg) => msg.id !== messageId)
            );

            setShowMessageMenu({});
        } catch (error) {
            console.error("Error deleting message:", error);
            alert("Failed to delete message");
        }
    };

    const handleEditKeyPress = (e, messageId) => {
        if (e.key === "Enter" && !e.shiftKey) {
            e.preventDefault();
            handleEdit(messageId);
        } else if (e.key === "Escape") {
            cancelEdit();
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

                        const isOwnMessage = msg.sender_id === window.userId;
                        const isEditing = editingMessageId === msg.id;

                        return (
                            <div
                                key={msg.id || `msg-${index}`}
                                className={`messageBoxListItem ${
                                    isOwnMessage
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
                                            isOwnMessage
                                                ? "bg-blue-600 text-black"
                                                : "bg-gray-100 text-gray-900"
                                        }`}
                                    >
                                        {isEditing ? (
                                            <div className="messageEditBox">
                                                <textarea
                                                    ref={editTextareaRef}
                                                    value={editContent}
                                                    onChange={(e) =>
                                                        setEditContent(
                                                            e.target.value
                                                        )
                                                    }
                                                    onKeyDown={(e) =>
                                                        handleEditKeyPress(
                                                            e,
                                                            msg.id
                                                        )
                                                    }
                                                    className="messageEditInput"
                                                    rows="3"
                                                />
                                                <div className="messageEditActions">
                                                    <button
                                                        onClick={() =>
                                                            handleEdit(msg.id)
                                                        }
                                                        className="messageEditSaveBtn"
                                                    >
                                                        Save
                                                    </button>
                                                    <button
                                                        onClick={cancelEdit}
                                                        className="messageEditCancelBtn"
                                                    >
                                                        Cancel
                                                    </button>
                                                </div>
                                            </div>
                                        ) : (
                                            <>
                                                <div className="messageBoxListItemContentMsg">
                                                    {msg.content}

                                                    {/* ✅ Show sending indicator for optimistic messages */}
                                                    {msg._optimistic && (
                                                        <span
                                                            className="messageSending"
                                                            style={{
                                                                fontSize:
                                                                    "0.75rem",
                                                                color: "#999",
                                                                marginLeft:
                                                                    "8px",
                                                            }}
                                                        >
                                                            ⏳ Sending...
                                                        </span>
                                                    )}

                                                    <div className="messageBoxTime">
                                                        {format(
                                                            messageDate,
                                                            "h:mm a"
                                                        )}
                                                        {msg.edited_at && (
                                                            <span
                                                                className="messageEdited"
                                                                style={{
                                                                    marginLeft:
                                                                        "4px",
                                                                    fontSize:
                                                                        "0.75rem",
                                                                    color: "#666",
                                                                }}
                                                            >
                                                                (edited)
                                                            </span>
                                                        )}
                                                    </div>
                                                </div>

                                                {msg.reactions &&
                                                    msg.reactions.length >
                                                        0 && (
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
                                                                                reaction.id,
                                                                                reaction.emoji
                                                                            )
                                                                        }
                                                                        title="Click to remove"
                                                                        style={{
                                                                            cursor: "pointer",
                                                                        }}
                                                                    >
                                                                        {
                                                                            reaction.emoji
                                                                        }
                                                                    </span>
                                                                )
                                                            )}
                                                        </div>
                                                    )}

                                                <div className="messageActionBtns">
                                                    {/* Only show reaction button for non-optimistic messages */}
                                                    {!msg._optimistic &&
                                                        msg.id &&
                                                        !String(
                                                            msg.id
                                                        ).startsWith(
                                                            "temp-"
                                                        ) && (
                                                            <>
                                                                <div
                                                                    className="messageReactIconBtn"
                                                                    onClick={() =>
                                                                        toggleReactBtns(
                                                                            msg.id
                                                                        )
                                                                    }
                                                                >
                                                                    <i className="fa-regular fa-face-smile"></i>
                                                                    <div
                                                                        className={`messageReactionOptions ${
                                                                            showReactBtns[
                                                                                msg
                                                                                    .id
                                                                            ]
                                                                                ? "messageReactionOptionsShow"
                                                                                : "messageReactionOptionsHide"
                                                                        }`}
                                                                    >
                                                                        <button
                                                                            onClick={(
                                                                                e
                                                                            ) => {
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
                                                                            onClick={(
                                                                                e
                                                                            ) => {
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
                                                                            onClick={(
                                                                                e
                                                                            ) => {
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
                                                                            onClick={(
                                                                                e
                                                                            ) => {
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
                                                                            onClick={(
                                                                                e
                                                                            ) => {
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

                                                                {/* ✅ Edit/Delete menu for own messages */}
                                                                {isOwnMessage &&
                                                                    !isEditing && (
                                                                        <div className="messageMenuBtn">
                                                                            <button
                                                                                onClick={() =>
                                                                                    toggleMessageMenu(
                                                                                        msg.id
                                                                                    )
                                                                                }
                                                                            >
                                                                                <i className="fa-solid fa-ellipsis-vertical"></i>
                                                                            </button>
                                                                            <div
                                                                                className={`messageMenu ${
                                                                                    showMessageMenu[
                                                                                        msg
                                                                                            .id
                                                                                    ]
                                                                                        ? "messageMenuShow"
                                                                                        : "messageMenuHide"
                                                                                }`}
                                                                            >
                                                                                <button
                                                                                    onClick={() =>
                                                                                        startEdit(
                                                                                            msg
                                                                                        )
                                                                                    }
                                                                                >
                                                                                    <i className="fa-solid fa-pen"></i>{" "}
                                                                                    Edit
                                                                                </button>
                                                                                <button
                                                                                    onClick={() =>
                                                                                        handleDelete(
                                                                                            msg.id
                                                                                        )
                                                                                    }
                                                                                    style={{
                                                                                        color: "red",
                                                                                    }}
                                                                                >
                                                                                    <i className="fa-solid fa-trash"></i>{" "}
                                                                                    Delete
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    )}
                                                            </>
                                                        )}
                                                </div>
                                            </>
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

export default InboxMessageList;
