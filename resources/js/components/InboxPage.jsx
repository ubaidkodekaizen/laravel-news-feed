// resources/js/components/InboxPage.jsx
import React, { useState, useEffect, useRef } from "react";
import { Search, MoreVertical, ArrowLeft } from "lucide-react";
import {
    ref,
    onChildAdded,
    onValue,
    set,
    query,
    orderByChild,
} from "firebase/database";
import { database } from "../firebase.js";
import InboxMessageList from "./InboxMessageList.jsx";
import InboxMessageInput from "./InboxMessageInput.jsx";

import { format } from "date-fns";

const InboxPage = () => {
    const isMobile = () => window.innerWidth <= 768;
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
    const [sidebarActive, setSidebarActive] = useState(isMobile());
    const token = localStorage.getItem("sanctum-token");
    const activeConversationRef = useRef(null);
    const firebaseUnsubscribers = useRef([]);
    const typingTimeoutRef = useRef(null);
    const notificationSound = useRef(null);
    const DEFAULT_AVATAR =
        "https://static.vecteezy.com/system/resources/previews/009/292/244/non_2x/default-avatar-icon-of-social-media-user-vector.jpg";

    // Initialize audio
    useEffect(() => {
        notificationSound.current = new Audio(
            "/assets/sounds/message-notification.mp3"
        );
        notificationSound.current.volume = 0.5;
    }, []);

    // Play notification sound function
    const playNotificationSound = () => {
        if (notificationSound.current) {
            notificationSound.current.currentTime = 0;
            notificationSound.current.play().catch((error) => {
                console.warn("Could not play notification sound:", error);
            });
        }
    };
    const scrollToBottom = () => {
        messagesEndRef.current?.scrollIntoView({ behavior: "smooth" });
    };

    // Handle window resize to reset sidebar state
    useEffect(() => {
        const handleResize = () => {
            if (!isMobile()) {
                setSidebarActive(false); // Reset on desktop
            } else if (!activeConversation) {
                setSidebarActive(true); // Show sidebar on mobile if no active conversation
            }
        };

        window.addEventListener("resize", handleResize);
        return () => window.removeEventListener("resize", handleResize);
    }, [activeConversation]);

    // Update the ref whenever activeConversation changes
    useEffect(() => {
        activeConversationRef.current = activeConversation;
    }, [activeConversation]);

    // Initial setup
    useEffect(() => {
        fetchConversations();
    }, []);

    // ✅ FIREBASE: Listen to new messages with validation
    useEffect(() => {
        if (!activeConversation) return;

        const messagesRef = ref(database, `messages/${activeConversation}`);
        const messagesQuery = query(messagesRef, orderByChild("created_at"));

        setMessages([]);
        let isInitialLoad = true;

        const unsubscribe = onChildAdded(messagesQuery, (snapshot) => {
            const newMessage = snapshot.val();

            // ✅ Validate that this is actually a message
            if (
                !newMessage ||
                !newMessage.id ||
                !newMessage.content ||
                !newMessage.created_at ||
                !newMessage.sender_id
            ) {
                console.warn("Invalid message data received:", newMessage);
                return;
            }

            // ✅ Ensure reactions is an array
            if (
                newMessage.reactions &&
                typeof newMessage.reactions === "object" &&
                !Array.isArray(newMessage.reactions)
            ) {
                newMessage.reactions = Object.values(newMessage.reactions);
            } else if (!newMessage.reactions) {
                newMessage.reactions = [];
            }

            setMessages((prevMessages) => {
                if (prevMessages.some((msg) => msg.id === newMessage.id)) {
                    return prevMessages;
                }

                const optimisticIndex = prevMessages.findIndex(
                    (msg) =>
                        msg._optimistic &&
                        msg.content.trim() === newMessage.content.trim() &&
                        msg.sender_id === newMessage.sender_id &&
                        msg.receiver_id === newMessage.receiver_id
                );

                if (optimisticIndex !== -1) {
                    const updated = [...prevMessages];
                    updated[optimisticIndex] = {
                        ...newMessage,
                        _optimistic: false,
                    };
                    return updated.sort(
                        (a, b) =>
                            new Date(a.created_at) - new Date(b.created_at)
                    );
                }

                // ✅ Play sound for incoming messages
                if (!isInitialLoad && newMessage.sender_id !== window.userId) {
                    playNotificationSound();
                }

                return [...prevMessages, newMessage].sort(
                    (a, b) => new Date(a.created_at) - new Date(b.created_at)
                );
            });
        });

        const timer = setTimeout(() => {
            isInitialLoad = false;
        }, 1000);

        return () => {
            unsubscribe();
            clearTimeout(timer);
        };
    }, [activeConversation]);

    // ✅ FIREBASE: Listen to typing indicators
    useEffect(() => {
        if (!activeConversation) return;

        const typingRef = ref(database, `typing/${activeConversation}`);

        const unsubscribe = onValue(typingRef, (snapshot) => {
            const typingData = snapshot.val();

            if (!typingData) {
                setTypingUser(null);
                return;
            }

            const now = Date.now();
            const typingUsers = Object.entries(typingData)
                .filter(([uid, data]) => {
                    const userId = parseInt(uid);
                    const timestamp = data.timestamp * 1000;
                    const timeDiff = now - timestamp;

                    console.log("Typing check:", {
                        userId,
                        currentUserId: window.userId,
                        timestamp,
                        now,
                        timeDiff,
                        isValid: userId !== window.userId && timeDiff < 3000,
                    });

                    return userId !== window.userId && timeDiff < 3000;
                })
                .map(([uid, data]) => ({
                    id: parseInt(uid),
                    first_name: data.first_name,
                    last_name: data.last_name,
                }));

            console.log("Typing users:", typingUsers);
            setTypingUser(typingUsers[0] || null);
        });

        // ✅ Cleanup stale typing indicators every second
        const interval = setInterval(() => {
            const typingRef = ref(database, `typing/${activeConversation}`);
            onValue(
                typingRef,
                (snapshot) => {
                    const typingData = snapshot.val();

                    if (!typingData) {
                        setTypingUser(null);
                        return;
                    }

                    const now = Date.now();

                    Object.entries(typingData).forEach(async ([uid, data]) => {
                        const timestamp = data.timestamp * 1000;
                        const timeDiff = now - timestamp;

                        if (timeDiff >= 3000) {
                            console.log(
                                "Removing stale typing indicator for user:",
                                uid
                            );
                            const staleRef = ref(
                                database,
                                `typing/${activeConversation}/${uid}`
                            );
                            try {
                                await set(staleRef, null);
                            } catch (e) {
                                console.warn(
                                    "Failed to remove stale typing:",
                                    e
                                );
                            }
                        }
                    });
                },
                { onlyOnce: true }
            );
        }, 1000);

        return () => {
            unsubscribe();
            clearInterval(interval);
        };
    }, [activeConversation]);

    // ✅ Clear typing indicator on window blur/close
    useEffect(() => {
        if (!activeConversation) return;

        const clearTyping = async () => {
            try {
                const typingRef = ref(
                    database,
                    `typing/${activeConversation}/${window.userId}`
                );
                await set(typingRef, null);
                console.log("Cleared typing indicator");
            } catch (e) {
                console.warn("Failed to clear typing:", e);
            }
        };

        // Clear on window events
        window.addEventListener("beforeunload", clearTyping);
        window.addEventListener("blur", clearTyping);

        return () => {
            window.removeEventListener("beforeunload", clearTyping);
            window.removeEventListener("blur", clearTyping);
            clearTyping();
        };
    }, [activeConversation]);

    useEffect(() => {
        const timer = setTimeout(() => {
            scrollToBottom();
        }, 100);
        return () => clearTimeout(timer);
    }, [messages]);

    // useEffect(() => {
    //     console.log("📨 Messages state:", messages);
    //     messages.forEach((msg, i) => {
    //         if (!msg.created_at || isNaN(new Date(msg.created_at).getTime())) {
    //             console.error(`❌ Invalid message ${i}:`, msg);
    //         }
    //     });
    // }, [messages]);

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
        if (isMobile()) {
            setSidebarActive(false);
        }
        await fetchMessages(conversation.id);
        await fetchUserDetails(conversation.id);
    };

    const handleBackClick = () => {
        setSidebarActive(true);
        setActiveConversation(null);
        setActiveUser(null);
    };

    const sendMessage = async (messageContent) => {
        console.log("Sending message:", messageContent);

        if (!messageContent.trim() || !activeConversation) return;

        // ✅ Clear typing indicator immediately when sending message
        try {
            const typingRef = ref(
                database,
                `typing/${activeConversation}/${window.userId}`
            );
            await set(typingRef, null);
        } catch (e) {
            console.warn("Failed to clear typing on send:", e);
        }

        const activeUserData = conversations.find(
            (convo) => convo.id === activeConversation
        )?.user;
        if (!activeUserData) return;

        // ✅ CREATE OPTIMISTIC MESSAGE with Firebase-safe ID
        const tempId = `temp-${Date.now()}-${Math.floor(
            Math.random() * 1000000
        )}`;

        // ✅ CREATE OPTIMISTIC MESSAGE
        const optimisticMessage = {
            id: tempId,
            conversation_id: activeConversation,
            sender_id: window.userId,
            receiver_id: activeUserData.id,
            content: messageContent,
            created_at: new Date().toISOString(),
            sender: {
                id: window.userId,
                first_name: window.userFirstName || "",
                last_name: window.userLastName || "",
                email: window.userEmail || "",
                photo: window.userPhoto || "",
                slug: window.userSlug || "",
                user_has_photo: !!window.userPhoto,
                user_initials: window.userInitials || "",
            },
            reactions: [],
            _optimistic: true,
        };

        // ✅ ADD MESSAGE IMMEDIATELY
        setMessages((prev) => [...prev, optimisticMessage]);

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

            console.log("Message sent successfully:", response.data);
            // Firebase listener will replace optimistic message automatically

            await fetchConversations();
        } catch (error) {
            console.error("Error sending message:", error);

            // ✅ REMOVE OPTIMISTIC MESSAGE ON ERROR
            setMessages((prev) =>
                prev.filter((msg) => msg.id !== optimisticMessage.id)
            );

            alert("Failed to send message. Please try again.");
        }
    };

    const handleTyping = async () => {
        if (!activeConversation) return;

        // Clear previous timeout
        if (typingTimeoutRef.current) {
            clearTimeout(typingTimeoutRef.current);
        }

        try {
            const typingRef = ref(
                database,
                `typing/${activeConversation}/${window.userId}`
            );

            // ✅ Set typing status with current timestamp
            await set(typingRef, {
                user_id: window.userId,
                first_name: window.userFirstName || "",
                last_name: window.userLastName || "",
                timestamp: Math.floor(Date.now() / 1000),
            });

            // ✅ Debounce API call (reduce server load)
            typingTimeoutRef.current = setTimeout(async () => {
                try {
                    await axios.post(
                        userIsTyping,
                        { conversation_id: activeConversation },
                        { headers: { Authorization: token } }
                    );
                } catch (e) {
                    console.warn("API typing call failed:", e);
                }
            }, 500);

            // ✅ Auto-remove after 3 seconds
            setTimeout(async () => {
                try {
                    await set(typingRef, null);
                } catch (e) {
                    console.warn("Failed to clear typing indicator:", e);
                }
            }, 3000);
        } catch (error) {
            console.error("Error sending typing event:", error);
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

        try {
            const messageDate = new Date(date);

            // Check if date is valid
            if (isNaN(messageDate.getTime())) {
                return "";
            }

            const today = new Date();
            const yesterday = new Date(today);
            yesterday.setDate(yesterday.getDate() - 1);

            if (messageDate.toDateString() === today.toDateString()) {
                return format(messageDate, "h:mm a");
            } else if (
                messageDate.toDateString() === yesterday.toDateString()
            ) {
                return "Yesterday";
            } else {
                return format(messageDate, "MMM d");
            }
        } catch (error) {
            console.error("Error formatting date:", error, date);
            return "";
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
            <div className={`inbox-sidebar ${sidebarActive ? "active" : ""}`}>
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
                                <button
                                    onClick={handleBackClick}
                                    className="back-button"
                                >
                                    <ArrowLeft size={20} />
                                    <span>Back</span>
                                </button>
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
