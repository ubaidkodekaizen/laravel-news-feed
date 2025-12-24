import React, { useState, useEffect } from "react";
import { format, isToday, isYesterday, isSameDay } from "date-fns";

const DEFAULT_AVATAR =
    "https://static.vecteezy.com/system/resources/previews/009/292/244/non_2x/default-avatar-icon-of-social-media-user-vector.jpg";

const MessageList = ({ messages }) => {
    const getInitials = (firstName, lastName) => {
        const first = firstName?.charAt(0)?.toUpperCase() || "";
        const last = lastName?.charAt(0)?.toUpperCase() || "";
        return first + last;
    };
    const [selectedMessageId, setSelectedMessageId] = useState(null);
    const [messageList, setMessageList] = useState(messages);
    const [showReactBtns, setReactBtns] = useState({});

    useEffect(() => {
        setMessageList(messages);
    }, [messages]);

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
                {
                    emoji,
                },
                {
                    headers: {
                        Authorization: localStorage.getItem("sanctum-token"),
                    },
                }
            );
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
            console.log("Reaction added:", response.data);
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

            setMessageList((prevMessages) =>
                prevMessages.map((msg) =>
                    msg.id === messageId
                        ? {
                              ...msg,
                              reactions: msg.reactions.filter(
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
                                    <div className="messageBoxListItemContentUsername">
                                        <a
                                            href={`/user/profile/${
                                                msg.sender?.slug ?? ""
                                            }`}
                                        >
                                            {msg.sender?.first_name ??
                                                "Unknown"}{" "}
                                            {msg.sender?.last_name ?? "User"}
                                        </a>
                                        <div className="messageBoxTime">
                                            {format(
                                                new Date(msg.created_at),
                                                "h:mm a"
                                            )}
                                        </div>
                                    </div>
                                    <div className="messageBoxListItemContentMsg">
                                        {msg.content}
                                    </div>

                                    <div className="messageReactions">
                                        {msg.reactions?.map((reaction) => (
                                            <span
                                                key={reaction.id}
                                                className="reaction"
                                                onClick={() =>
                                                    handleRemoveReaction(
                                                        msg.id,
                                                        reaction.emoji
                                                    )
                                                }
                                            >
                                                {reaction.emoji}
                                            </span>
                                        ))}
                                    </div>
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
                                                onClick={() =>
                                                    handleReact(msg.id, "👍")
                                                }
                                            >
                                                👍
                                            </button>
                                            <button
                                                onClick={() =>
                                                    handleReact(msg.id, "❤️")
                                                }
                                            >
                                                ❤️
                                            </button>
                                            <button
                                                onClick={() =>
                                                    handleReact(msg.id, "😂")
                                                }
                                            >
                                                😂
                                            </button>
                                            <button
                                                onClick={() =>
                                                    handleReact(msg.id, "😮")
                                                }
                                            >
                                                😮
                                            </button>
                                            <button
                                                onClick={() =>
                                                    handleReact(msg.id, "😢")
                                                }
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

export default MessageList;
