import React, { useState, useEffect, useRef } from "react";
import { format, isToday, isYesterday, isSameDay } from "date-fns";

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
    const messageChannelRef = useRef(null);
    const conversationIdRef = useRef(conversationId);

    // Update ref when conversationId changes
    useEffect(() => {
        conversationIdRef.current = conversationId;
    }, [conversationId]);

    // Update local state when props change
    useEffect(() => {
        setMessageList(messages);
    }, [messages]);

    // Subscribe to real-time events
    useEffect(() => {
        if (!window.Echo) {
            console.error("Echo is not initialized yet.");
            return;
        }

        console.log("InboxMessageList - Setting up real-time listeners...");

        // Subscribe to the private channel for this user
        const messageChannel = window.Echo.private(`private-chat.${window.userId}`);
        messageChannelRef.current = messageChannel;

        // Listen for new messages
        messageChannel.listen('.message.new', (data) => {
            console.log("InboxMessageList - New message received:", data);

            // Only add message if it's for the current conversation
            if (data.conversation_id !== conversationIdRef.current) {
                console.log("InboxMessageList - Message not for current conversation");
                return;
            }

            setMessageList((prevMessages) => {
                // Check if message already exists
                const messageExists = prevMessages.some(msg => msg.id === data.id);
                if (messageExists) {
                    console.log("InboxMessageList - Message already exists");
                    return prevMessages;
                }

                console.log("InboxMessageList - Adding new message");
                return [
                    ...prevMessages,
                    {
                        id: data.id,
                        conversation_id: data.conversation_id,
                        sender_id: data.sender.id,
                        content: data.content,
                        created_at: data.created_at,
                        sender: {
                            id: data.sender.id,
                            first_name: data.sender.first_name,
                            last_name: data.sender.last_name,
                            email: data.sender.email,
                            photo: data.sender.photo,
                            user_has_photo: data.sender.user_has_photo,
                            user_initials: data.sender.user_initials,
                            slug: data.sender.slug,
                        },
                        reactions: [],
                    },
                ];
            });
        });

        // Listen for new reactions
        messageChannel.listen('.reaction.added', (data) => {
            console.log("InboxMessageList - Reaction added:", data);

            setMessageList((prevMessages) =>
                prevMessages.map((msg) =>
                    msg.id === data.message_id
                        ? {
                              ...msg,
                              reactions: [
                                  ...(msg.reactions || []),
                                  data.reaction,
                              ],
                          }
                        : msg
                )
            );
        });

        // Listen for removed reactions
        messageChannel.listen('.reaction.removed', (data) => {
            console.log("InboxMessageList - Reaction removed:", data);

            setMessageList((prevMessages) =>
                prevMessages.map((msg) =>
                    msg.id === data.message_id
                        ? {
                              ...msg,
                              reactions: (msg.reactions || []).filter(
                                  (r) => r.id !== data.reaction_id
                              ),
                          }
                        : msg
                )
            );
        });

        return () => {
            console.log("InboxMessageList - Cleaning up listeners...");
            // Don't leave the channel as it might be shared
            // The parent component should handle channel cleanup
        };
    }, []); // Empty dependency - only set up once

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
                                {msg.sender?.user_has_photo ? (
                                    <img
                                        src={msg.sender.photo}
                                        alt="Sender"
                                        className="messageSenderPhoto"
                                        onError={(e) => (e.target.src = DEFAULT_AVATAR)}
                                    />
                                ) : (
                                    <div className="avatar-initials messageSenderPhoto">
                                        {msg.sender?.user_initials ||
                                            getInitials(
                                                msg.sender?.first_name,
                                                msg.sender?.last_name
                                            )}
                                    </div>
                                )}
                                <div
                                    className={`messageBoxListItemContent ${
                                        msg.sender_id === window.userId
                                            ? "bg-blue-600 text-black"
                                            : "bg-gray-100 text-gray-900"
                                    }`}
                                >
                                    <div className="messageBoxListItemContentMsg">
                                        {msg.content}

                                        <div className="messageBoxTime">
                                            {format(
                                                new Date(msg.created_at),
                                                "h:mm a"
                                            )}
                                        </div>
                                    </div>

                                    {msg.reactions && msg.reactions.length > 0 && (
                                        <div className="messageReactions">
                                            {msg.reactions.map((reaction) => (
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
                                                    style={{ cursor: 'pointer' }}
                                                >
                                                    {reaction.emoji}
                                                </span>
                                            ))}
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
