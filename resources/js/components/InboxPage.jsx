// resources/js/components/InboxPage.jsx
import React, { useState, useEffect, useRef } from "react";
import { Search, MoreVertical } from "lucide-react";
import InboxMessageList from "./InboxMessageList.jsx";
import InboxMessageInput from "./InboxMessageInput.jsx";
import { format } from "date-fns";

const InboxPage = () => {
    const [conversations, setConversations] = useState([]);
    const [messages, setMessages] = useState([]);
    const [activeConversation, setActiveConversation] = useState(null);
    const [activeUser, setActiveUser] = useState(null);
    const [search, setSearch] = useState("");
    const [loading, setLoading] = useState(true);
    const [typingUser, setTypingUser] = useState(null);
    const messagesEndRef = useRef(null);
    const messageChannelRef = useRef(null);
    const typingChannelRef = useRef(null);
    const token = localStorage.getItem("sanctum-token");
    const activeConversationRef = useRef(null);
    const DEFAULT_AVATAR =
        "https://static.vecteezy.com/system/resources/previews/009/292/244/non_2x/default-avatar-icon-of-social-media-user-vector.jpg";

    const scrollToBottom = () => {
        messagesEndRef.current?.scrollIntoView({ behavior: "smooth" });
    };

    // Update the ref whenever activeConversation changes
    useEffect(() => {
        activeConversationRef.current = activeConversation;
    }, [activeConversation]);

    // Initial setup - subscribe to main message channel
    useEffect(() => {
        if (!window.Echo) {
            console.error("Echo is not initialized yet.");
            return;
        }

        fetchConversations();

        // Subscribe to the private channel for new messages
        console.log("Inbox - Setting up main message channel...");
        const messageChannel = window.Echo.private(
            `private-chat.${window.userId}`
        );
        messageChannelRef.current = messageChannel;

        messageChannel.listen(".message.new", async (message) => {
            console.log("Inbox - New message received:", message);

            // Use ref to get current value
            if (message.conversation_id !== activeConversationRef.current) {
                console.log(
                    "Message is not for active conversation, skipping message update"
                );
                fetchConversations(); // Still update conversation list
                return;
            }

            // Update messages if this message belongs to the active conversation
            setMessages((prevMessages) => {
                // Check if message already exists to avoid duplicates
                const messageExists = prevMessages.some(
                    (msg) => msg.id === message.id
                );
                if (messageExists) {
                    console.log("Message already exists, skipping...");
                    return prevMessages;
                }

                console.log("Adding new message to active conversation");
                return [
                    ...prevMessages,
                    {
                        id: message.id,
                        conversation_id: message.conversation_id,
                        sender_id: message.sender_id,
                        content: message.content,
                        created_at: message.created_at,
                        sender: {
                            id: message.sender.id,
                            first_name: message.sender.first_name,
                            last_name: message.sender.last_name,
                            email: message.sender.email,
                            photo: message.sender.photo,
                            user_has_photo: message.sender.user_has_photo,
                            user_initials: message.sender.user_initials,
                            slug: message.sender.slug,
                        },
                        reactions: [],
                    },
                ];
            });

            // Always refresh conversations to update last message and unread count
            console.log("Refreshing conversations list...");
            fetchConversations();
        });

        return () => {
            console.log(`Inbox - Cleaning up main message channel...`);
            if (messageChannelRef.current) {
                window.Echo.leave(`private-chat.${window.userId}`);
            }
        };
    }, []); // Empty dependency array - only run once on mount

    // Separate effect for typing indicators based on active conversation
    useEffect(() => {
        if (!window.Echo || !activeConversation) {
            return;
        }

        console.log(
            `Inbox - Setting up typing channel for conversation ${activeConversation}...`
        );

        // Clean up previous typing channel if it exists
        if (typingChannelRef.current) {
            console.log("Inbox - Cleaning up previous typing channel...");
            window.Echo.leave(`user-activity.${typingChannelRef.current}`);
        }

        // Subscribe to new typing channel
        const typingChannel = window.Echo.private(
            `user-activity.${activeConversation}`
        );
        typingChannelRef.current = activeConversation;

        typingChannel.listen(".user.typing", (e) => {
            console.log("Inbox - Typing event received:", e);
            if (
                e.conversation_id === activeConversation &&
                e.user.id !== window.userId
            ) {
                showTypingIndicator(e.user);
            }
        });

        return () => {
            if (typingChannelRef.current) {
                console.log(
                    `Inbox - Cleaning up typing channel ${typingChannelRef.current}...`
                );
                window.Echo.leave(`user-activity.${typingChannelRef.current}`);
            }
        };
    }, [activeConversation]);

    useEffect(() => {
        const timer = setTimeout(() => {
            scrollToBottom();
        }, 100);
        return () => clearTimeout(timer);
    }, [messages]);

    const fetchConversations = async () => {
        try {
            const response = await window.axios.get(getConversations, {
                headers: { Authorization: token },
            });
            console.log("Inbox - Conversations fetched:", response.data);
            setConversations(response.data);
            setLoading(false);
        } catch (error) {
            console.error("Inbox - Error fetching conversations:", error);
            setLoading(false);
        }
    };

    const fetchMessages = async (conversationId) => {
        try {
            const url = getMessageRoute(conversationId);
            const response = await window.axios.get(url, {
                headers: { Authorization: token },
            });
            console.log("Inbox - Messages fetched:", response.data);
            setMessages(response.data);
        } catch (error) {
            console.error("Inbox - Error fetching messages:", error);
        }
    };

    const fetchUserDetails = async (conversationId) => {
        try {
            const url = getUserConversationRoute(conversationId);
            const response = await window.axios.get(url, {
                headers: { Authorization: token },
            });
            console.log("Inbox - User details fetched:", response.data);
            setActiveUser(response.data);
        } catch (error) {
            console.error("Inbox - Error fetching user details:", error);
        }
    };

    const handleConversationClick = async (conversation) => {
        console.log("Inbox - Conversation clicked:", conversation.id);
        setActiveConversation(conversation.id);
        await fetchMessages(conversation.id);
        await fetchUserDetails(conversation.id);
    };

    const sendMessage = async (messageContent) => {
        console.log("Inbox - Sending message:", messageContent);

        if (!messageContent.trim() || !activeConversation) return;

        const activeUserData = conversations.find(
            (convo) => convo.id === activeConversation
        )?.user;
        if (!activeUserData) return;

        try {
            const response = await window.axios.post(
                sendMsg,
                {
                    content: messageContent,
                    receiver_id: activeUserData.id,
                },
                {
                    headers: { Authorization: token },
                }
            );

            console.log("Inbox - Message sent successfully:", response.data);

            // Add message to the list immediately (optimistic update)
            setMessages((prev) => {
                // Check if message already exists
                const messageExists = prev.some(
                    (msg) => msg.id === response.data.id
                );
                if (messageExists) {
                    return prev;
                }
                return [...prev, response.data];
            });

            // Refresh conversations to update last message
            await fetchConversations();
        } catch (error) {
            console.error("Inbox - Error sending message:", error);
        }
    };

    const handleTyping = async () => {
        if (!activeConversation) return;

        try {
            await window.axios.post(
                userIsTyping,
                {
                    conversation_id: activeConversation,
                },
                {
                    headers: { Authorization: token },
                }
            );
        } catch (error) {
            console.error("Inbox - Error sending typing event:", error);
        }
    };

    let typingTimeout;
    const showTypingIndicator = (user) => {
        setTypingUser(user);
        clearTimeout(typingTimeout);
        typingTimeout = setTimeout(() => setTypingUser(null), 3000);
    };

    const filteredConversations = conversations.filter((conversation) => {
        const fullName = `${conversation.user?.first_name ?? ""} ${
            conversation.user?.last_name ?? ""
        }`.toLowerCase();
        return fullName.includes(search.toLowerCase());
    });

    const formatLastMessageTime = (date) => {
        if (!date) return "";
        const messageDate = new Date(date);
        const today = new Date();
        const yesterday = new Date(today);
        yesterday.setDate(yesterday.getDate() - 1);

        if (messageDate.toDateString() === today.toDateString()) {
            return format(messageDate, "h:mm a");
        } else if (messageDate.toDateString() === yesterday.toDateString()) {
            return "Yesterday";
        } else {
            return format(messageDate, "MMM d");
        }
    };

    if (loading) {
        return (
            <div className="inbox-loading">
                <div className="spinner-border text-primary" role="status">
                    <span className="visually-hidden">Loading...</span>
                </div>
            </div>
        );
    }

    return (
        <div className="inbox-container">
            <div className="inbox-sidebar">
                <div className="inbox-sidebar-header">
                    <h2>Messages</h2>
                </div>

                <div className="inbox-search">
                    <Search className="inbox-search-icon" />
                    <input
                        type="text"
                        placeholder="Search conversations..."
                        value={search}
                        onChange={(e) => setSearch(e.target.value)}
                        className="inbox-search-input"
                    />
                </div>

                <div className="inbox-conversations-list">
                    {filteredConversations.length === 0 ? (
                        <div className="inbox-no-conversations">
                            <p>No conversations found</p>
                        </div>
                    ) : (
                        filteredConversations.map((conversation) => (
                            <div
                                key={conversation.id}
                                onClick={() =>
                                    handleConversationClick(conversation)
                                }
                                className={`inbox-conversation-item ${
                                    activeConversation === conversation.id
                                        ? "active"
                                        : ""
                                }`}
                            >
                                <div className="inbox-conversation-avatar">
                                    {conversation.user?.user_has_photo ? (
                                        <img
                                            src={conversation.user.photo}
                                            alt="User Profile"
                                            onError={(e) =>
                                                (e.target.src = DEFAULT_AVATAR)
                                            }
                                        />
                                    ) : (
                                        <div className="avatar-initials">
                                            {conversation.user?.user_initials ||
                                                "?"}
                                        </div>
                                    )}
                                    {conversation.unread_count > 0 && (
                                        <span className="inbox-unread-badge">
                                            {conversation.unread_count}
                                        </span>
                                    )}
                                </div>

                                <div className="inbox-conversation-details">
                                    <div className="inbox-conversation-header">
                                        <h4 className="inbox-conversation-name">
                                            {conversation.user?.first_name ??
                                                ""}{" "}
                                            {conversation.user?.last_name ?? ""}
                                        </h4>
                                        <span className="inbox-conversation-time">
                                            {formatLastMessageTime(
                                                conversation.last_message
                                                    ?.created_at
                                            )}
                                        </span>
                                    </div>
                                    <div className="inbox-conversation-preview">
                                        <p
                                            className={
                                                conversation.unread_count > 0
                                                    ? "unread"
                                                    : ""
                                            }
                                        >
                                            {conversation.last_message
                                                ?.content ?? "No messages yet"}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        ))
                    )}
                </div>
            </div>

            <div className="inbox-main">
                {activeConversation && activeUser ? (
                    <>
                        <div className="inbox-main-header">
                            <div className="inbox-main-header-user">
                                {activeUser.user_has_photo ? (
                                    <img
                                        src={activeUser.photo}
                                        alt="User"
                                        className="inbox-main-header-avatar"
                                        onError={(e) =>
                                            (e.target.src = DEFAULT_AVATAR)
                                        }
                                    />
                                ) : (
                                    <div className="avatar-initials inbox-main-header-avatar">
                                        {activeUser.user_initials || "?"}
                                    </div>
                                )}
                                <div className="inbox-main-header-info">
                                    <a
                                        href={`/user/profile/${activeUser.slug}`}
                                        className="inbox-main-header-name"
                                    >
                                        {activeUser.first_name ?? "Unknown"}{" "}
                                        {activeUser.last_name ?? "User"}
                                    </a>
                                    {typingUser && (
                                        <span className="inbox-typing-indicator">
                                            {typingUser.first_name} is typing...
                                        </span>
                                    )}
                                </div>
                            </div>

                            <div className="inbox-main-header-actions">
                                {/* Optional actions */}
                            </div>
                        </div>

                        <div className="inbox-messages-container">
                            {messages.length === 0 ? (
                                <div className="inbox-no-messages">
                                    <p>
                                        No messages yet. Start the conversation!
                                    </p>
                                </div>
                            ) : (
                                <InboxMessageList
                                    messages={messages}
                                    conversationId={activeConversation}
                                />
                            )}
                            <div ref={messagesEndRef}></div>
                        </div>

                        <InboxMessageInput
                            onSendMessage={sendMessage}
                            onTyping={handleTyping}
                        />
                    </>
                ) : (
                    <div className="inbox-no-selection">
                        <div className="inbox-no-selection-content">
                            <svg
                                width="200"
                                height="200"
                                viewBox="0 0 200 200"
                                fill="none"
                            >
                                <circle
                                    cx="100"
                                    cy="100"
                                    r="80"
                                    stroke="#e0e0e0"
                                    strokeWidth="2"
                                />
                                <path
                                    d="M70 90 L100 110 L130 90"
                                    stroke="#e0e0e0"
                                    strokeWidth="2"
                                    fill="none"
                                />
                                <path
                                    d="M70 90 L70 130 L130 130 L130 90"
                                    stroke="#e0e0e0"
                                    strokeWidth="2"
                                    fill="none"
                                />
                            </svg>
                            <h3>Select a conversation</h3>
                            <p>
                                Choose a conversation from the sidebar to start
                                messaging
                            </p>
                        </div>
                    </div>
                )}
            </div>
        </div>
    );
};

export default InboxPage;
