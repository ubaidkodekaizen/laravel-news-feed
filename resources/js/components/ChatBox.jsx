import React, { useState, useEffect, useRef } from "react";
import {
    MessageCircle,
    X,
    Send,
    MinusSquare,
    Square,
    User,
    Search,
} from "lucide-react";
import {
    ref as dbRef,
    onChildAdded,
    onValue,
    set,
    query,
    orderByChild,
} from "firebase/database";
import { database } from "../firebase.js";

import MessageList from "./MessageList.jsx";
import MessageInput from "./MessageInput.jsx";
import OnlineStatus from "./OnlineStatus.jsx";

import { format } from "date-fns";

const ChatBox = () => {
    const [isOpen, setIsOpen] = useState(false);
    const [isMinimized, setIsMinimized] = useState(false);
    const [hideMessageBox, setHideMessageBox] = useState(false);
    const [message, setMessage] = useState("");
    const [messages, setMessages] = useState([]);
    const [conversations, setConversations] = useState([]);
    const [activeConversation, setActiveConversation] = useState(null);
    const [activeUser, setActiveUser] = useState(null); // Store the user data
    const messagesEndRef = useRef(null);
    const typingTimeoutRef = useRef(null);
    const [userId, setUserId] = useState(window.userId);
    const [typingUser, setTypingUser] = useState(null);
    const [search, setSearch] = useState("");
    const [unreadCounts, setUnreadCounts] = useState({});
    const [isBlocked, setIsBlocked] = useState(false);
    const [isBlockedBy, setIsBlockedBy] = useState(false);
    const [showBlockMenu, setShowBlockMenu] = useState(false);
    const token = localStorage.getItem("sanctum-token");
    // ✅ Add audio ref for notification sound
    const notificationSound = useRef(null);

    const DEFAULT_AVATAR =
        "https://static.vecteezy.com/system/resources/previews/009/292/244/non_2x/default-avatar-icon-of-social-media-user-vector.jpg";

    // ✅ Initialize audio on component mount
    useEffect(() => {
        notificationSound.current = new Audio(
            "/assets/sounds/message-notification.mp3"
        );
        notificationSound.current.volume = 0.5; // Set volume (0.0 to 1.0)
    }, []);

    useEffect(() => {
        if (!window.userId) return;

        const unreadRef = dbRef(database, `unread_counts/${window.userId}`);

        const unsubscribe = onValue(unreadRef, (snapshot) => {
            const counts = snapshot.val() || {};
            setUnreadCounts(counts);
        });

        return () => unsubscribe();
    }, [userId]);

    // ✅ Play notification sound
    const playNotificationSound = () => {
        if (notificationSound.current) {
            notificationSound.current.currentTime = 0; // Reset to start
            notificationSound.current.play().catch((error) => {
                console.warn("Could not play notification sound:", error);
            });
        }
    };

    const getInitials = (firstName, lastName) => {
        const first = firstName?.charAt(0)?.toUpperCase() || "";
        const last = lastName?.charAt(0)?.toUpperCase() || "";
        return first + last;
    };
    const scrollToBottom = () => {
        messagesEndRef.current?.scrollIntoView({ behavior: "smooth" });
    };

    useEffect(() => {
        // console.log("User changed, resetting chat state...");
        setConversations([]); // Clear conversations
        setMessages([]); // Clear messages
        setActiveConversation(null); // Reset active conversation
        setActiveUser(null); // Reset active user
        fetchConversations(); // Fetch new conversations for the logged-in user
    }, [userId]); // Run this effect whenever userId changes

    // ✅ FIREBASE: Listen to new messages with better validation
    useEffect(() => {
        if (!activeConversation) return;

        const messagesRef = dbRef(database, `messages/${activeConversation}`);
        const messagesQuery = query(messagesRef, orderByChild("created_at"));

        setMessages([]);
        let isInitialLoad = true;

        const unsubscribe = onChildAdded(messagesQuery, (snapshot) => {
            const newMessage = snapshot.val();

            // ✅ Validate that this is actually a message, not a reaction or metadata
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

                // ✅ Play sound only for incoming messages
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

        const typingRef = dbRef(database, `typing/${activeConversation}`);

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
                    const timestamp = data.timestamp * 1000; // Convert to milliseconds
                    const timeDiff = now - timestamp;

                    // Debug log
                    console.log("Typing check:", {
                        userId,
                        currentUserId: window.userId,
                        timestamp,
                        now,
                        timeDiff,
                        isValid: userId !== window.userId && timeDiff < 3000,
                    });

                    // Only show if: not current user AND within last 3 seconds
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

        // ✅ Check every second and clear stale typing indicators
        const interval = setInterval(() => {
            const typingRef = dbRef(database, `typing/${activeConversation}`);
            onValue(
                typingRef,
                (snapshot) => {
                    const typingData = snapshot.val();

                    if (!typingData) {
                        setTypingUser(null);
                        return;
                    }

                    const now = Date.now();

                    // Check each typing entry and remove if stale
                    Object.entries(typingData).forEach(async ([uid, data]) => {
                        const timestamp = data.timestamp * 1000;
                        const timeDiff = now - timestamp;

                        // If older than 3 seconds, remove from Firebase
                        if (timeDiff >= 3000) {
                            console.log(
                                "Removing stale typing indicator for user:",
                                uid
                            );
                            const staleRef = dbRef(
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
            ); // Only read once per interval
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
                const typingRef = dbRef(
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
            clearTyping(); // Also clear when component unmounts or conversation changes
        };
    }, [activeConversation]);

    useEffect(() => {
        // Scroll to the bottom when the component is first loaded or when messages change
        const timer = setTimeout(() => {
            scrollToBottom();
        }, 100); // Give React time to update the DOM

        return () => clearTimeout(timer); // Cleanup the timeout
    }, [messages, activeConversation, conversations, activeUser]);

    const filteredConversations = conversations
        .filter((conversation) => {
            const fullName = `${conversation.user?.first_name ?? ""} ${
                conversation.user?.last_name ?? ""
            }`.toLowerCase();
            return fullName.includes(search.toLowerCase());
        })
        .sort((a, b) => {
            // Sort by last message timestamp (newest first)
            const dateA = a.last_message?.created_at
                ? new Date(a.last_message.created_at)
                : new Date(0);
            const dateB = b.last_message?.created_at
                ? new Date(b.last_message.created_at)
                : new Date(0);
            return dateB - dateA; // Descending order (newest first)
        });

    const fetchConversations = async () => {
        const token = localStorage.getItem("sanctum-token");

        if (!token) {
            console.error("Token not found. Cannot fetch conversations.");
            return;
        }

        try {
            const response = await axios.get(getConversations, {
                headers: {
                    Authorization: token,
                },
            });
            setConversations(response.data);
        } catch (error) {
            console.error("Error fetching conversations:", error);
        }
    };

    const fetchMessages = async (conversationId) => {
        try {
            const url = getMessageRoute(conversationId);
            const response = await window.axios.get(url, {
                headers: {
                    Authorization: token,
                },
            });
            console.log("messages", response.data);
            setMessages(response.data);
        } catch (error) {
            console.error("Error fetching messages:", error);
        }
    };

    // Update handleConversationClick to clear unread count
    const handleConversationClick = async (conversation) => {
        setActiveConversation(conversation.id);
        setHideMessageBox(false);
        setIsMinimized(false);
        await fetchMessages(conversation.id);

        // Clear unread count for this conversation
        setUnreadCounts((prev) => ({
            ...prev,
            [conversation.id]: 0,
        }));
    };

    const sendMessage = async (messageContent) => {
        console.log("Sending message:", messageContent);

        if (!messageContent.trim() || !activeConversation) return;

        // ✅ Clear typing indicator immediately when sending message
        try {
            const typingRef = dbRef(
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

        // ✅ CREATE OPTIMISTIC MESSAGE with Firebase-safe ID (no dots!)
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

    useEffect(() => {
        if (activeConversation) {
            fetchUserDetails(activeConversation);
        }
    }, [activeConversation, userId]);

    const openChatWithUser = async (userId) => {
        // Redirect to inbox on small screens
        if (window.innerWidth < 800) {
            window.location.href = "/inbox";
            return; // stop further execution
        }
        // First, check if a conversation with this user exists
        const existingConversation = conversations.find(
            (convo) => convo.user.id === userId
        );

        if (existingConversation) {
            // If conversation exists, open it
            handleConversationClick(existingConversation);
            setIsOpen(true);
        } else {
            // If no conversation exists, fetch or create a conversation
            try {
                const response = await axios.post(
                    createConversation,
                    { user_id: userId },
                    {
                        headers: {
                            Authorization: token,
                        },
                    }
                );

                // Refresh conversations and open the new conversation
                await fetchConversations();
                handleConversationClick(response.data);
                setIsOpen(true);
            } catch (error) {
                console.error("Error creating conversation:", error);
            }
        }
    };

    // Expose the method globally
    useEffect(() => {
        window.openChatWithUser = openChatWithUser;
    }, [conversations]);

    const fetchUserDetails = async (conversationId) => {
        try {
            const url = getUserConversationRoute(conversationId);
            const response = await window.axios.get(url, {
                headers: {
                    Authorization: token,
                },
            });
            setActiveUser(response.data); // Assuming response.data contains user info
            // ✅ Set block status
            setIsBlocked(response.data.is_blocked || false);
            setIsBlockedBy(response.data.is_blocked_by || false);
            console.log("active user", response.data);
        } catch (error) {
            console.error("Error fetching user details:", error);
        }
    };

    // Add block/unblock handlers
    const handleBlockUser = async () => {
        if (!activeUser) return;

        const confirmMessage = isBlocked
            ? `Are you sure you want to unblock ${activeUser.first_name}?`
            : `Are you sure you want to block ${activeUser.first_name}? You won't be able to send or receive messages.`;

        if (!confirm(confirmMessage)) return;

        try {
            const url = isBlocked ? unblockUserRoute : blockUserRoute;
            const response = await window.axios.post(
                url,
                { user_id: activeUser.id },
                { headers: { Authorization: token } }
            );

            setIsBlocked(!isBlocked);
            setShowBlockMenu(false);

            alert(response.data.message);
        } catch (error) {
            console.error("Error blocking/unblocking user:", error);
            alert("Failed to update block status");
        }
    };
    // Helper function to convert time to "X minutes ago" format
    function timeAgo(date) {
        const now = new Date();
        const diffInSeconds = Math.floor((now - new Date(date)) / 1000);
        const minutes = Math.floor(diffInSeconds / 60);
        const hours = Math.floor(diffInSeconds / 3600);
        const days = Math.floor(diffInSeconds / 86400);
        const months = Math.floor(diffInSeconds / 2592000);
        const years = Math.floor(diffInSeconds / 31536000);

        if (minutes < 1) return "Just now";
        if (minutes < 60)
            return `${minutes} minute${minutes !== 1 ? "s" : ""} ago`;
        if (hours < 24) return `${hours} hour${hours !== 1 ? "s" : ""} ago`;
        if (days < 30) return `${days} day${days !== 1 ? "s" : ""} ago`;
        if (months < 12) return `${months} month${months !== 1 ? "s" : ""} ago`;
        return `${years} year${years !== 1 ? "s" : ""} ago`;
    }
    // Helper function to format time like "5:26 AM"
    function formatTime(date) {
        const d = new Date(date);
        let hours = d.getHours();
        let minutes = d.getMinutes();
        const ampm = hours >= 12 ? "PM" : "AM";

        hours = hours % 12;
        hours = hours ? hours : 12; // the hour '0' should be '12'
        minutes = minutes < 10 ? "0" + minutes : minutes;

        return `${hours}:${minutes} ${ampm}`;
    }
    let typingTimeout;
    const showTypingIndicator = (user) => {
        if (user.id !== window.userId) {
            setTypingUser(user);
            clearTimeout(typingTimeout); // Clear the old timeout
            typingTimeout = setTimeout(() => setTypingUser(null), 3000);
        }
    };

    const handleTyping = async () => {
        if (!activeConversation) return;

        // Clear previous timeout
        if (typingTimeoutRef.current) {
            clearTimeout(typingTimeoutRef.current);
        }

        try {
            const typingRef = dbRef(
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

    const handleChatIconClick = () => {
        if (window.innerWidth < 800) {
            window.location.href = "/inbox";
        } else {
            setIsOpen(!isOpen);
        }
    };

    return (
        <div className="chatContainerInner" key={userId}>
            {activeConversation && activeUser && (
                // Messages View
                <div
                    className={`messageBox messageBoxListOpen ${
                        isMinimized
                            ? "messageBoxListClose"
                            : "messageBoxListOpen"
                    } ${hideMessageBox ? "messageBoxHide" : "messageBoxShow"}`}
                >
                    <div
                        className="messageBoxHead"
                        onClick={() => setIsMinimized(!isMinimized)}
                    >
                        <div className="messageBoxHeadInner">
                            <div className="messageBoxHeadInnerAvatarBox">
                                {activeUser.user_has_photo ? (
                                    <img
                                        src={activeUser.photo}
                                        alt="Sender"
                                        className="messageBoxHeadSenderPhoto"
                                    />
                                ) : (
                                    <div className="avatar-initials messageBoxHeadSenderPhoto">
                                        {activeUser.user_initials ||
                                            getInitials(
                                                activeUser.first_name,
                                                activeUser.last_name
                                            )}
                                    </div>
                                )}
                                {/* ✅ Add online status indicator */}
                                <OnlineStatus
                                    userId={activeUser.id}
                                    className="avatar-status-badge"
                                />
                            </div>

                            <div className={`messageBoxHeadContent`}>
                                <a
                                    href={`/user/profile/${activeUser.slug}`}
                                    className="messageBoxHeadUsername"
                                >
                                    {activeUser.first_name ?? "Unknown"}{" "}
                                    {activeUser.last_name ?? "User"}
                                </a>

                                {/* ✅ Add online status text */}
                                <OnlineStatus
                                    userId={activeUser.id}
                                    showText={true}
                                />

                                {typingUser && (
                                    <div className="typing-indicator">
                                        {typingUser.first_name} is typing...
                                    </div>
                                )}
                            </div>
                        </div>
                        <div className="messageBoxHeadInner">
                            {/* ✅ Add block menu button */}
                            <button
                                type="button"
                                className="btn messageBoxMenuBtn"
                                onClick={(e) => {
                                    e.stopPropagation();
                                    setShowBlockMenu(!showBlockMenu);
                                }}
                            >
                                <i className="fa-solid fa-ellipsis-vertical"></i>
                            </button>

                            {/* ✅ Block menu dropdown */}
                            {showBlockMenu && (
                                <div className="messageBoxDropdown">
                                    <button
                                        onClick={(e) => {
                                            e.stopPropagation();
                                            handleBlockUser();
                                        }}
                                        className={
                                            isBlocked
                                                ? "unblock-btn"
                                                : "block-btn"
                                        }
                                    >
                                        <i
                                            className={`fa-solid ${
                                                isBlocked
                                                    ? "fa-lock-open"
                                                    : "fa-ban"
                                            }`}
                                        ></i>
                                        {isBlocked
                                            ? "Unblock User"
                                            : "Block User"}
                                    </button>
                                </div>
                            )}
                            <button
                                type="button"
                                className="btn-close closeMessageBoxBtn"
                                onClick={() =>
                                    setHideMessageBox(!hideMessageBox)
                                }
                            ></button>
                        </div>
                    </div>
                    <div className="messageBoxList">
                        {messages.length === 0 ? (
                            <div className="noMessages">
                                You don’t have any messages yet—check back soon.
                            </div>
                        ) : (
                            <MessageList
                                messages={messages}
                                conversationId={activeConversation}
                            />
                        )}
                        <div ref={messagesEndRef}></div>
                    </div>

                    {/* Message Input */}
                    {isBlockedBy ? (
                        <div className="messageBoxBlockedNotice">
                            <i className="fa-solid fa-ban"></i>
                            <p>You can't send messages to this user</p>
                        </div>
                    ) : isBlocked ? (
                        <div className="messageBoxBlockedNotice">
                            <i className="fa-solid fa-ban"></i>
                            <p>
                                You have blocked this user.{" "}
                                <button
                                    onClick={handleBlockUser}
                                    className="unblock-inline-btn"
                                >
                                    Unblock
                                </button>{" "}
                                to send messages.
                            </p>
                        </div>
                    ) : (
                        <MessageInput
                            onSendMessage={sendMessage}
                            onTyping={handleTyping}
                        />
                    )}
                </div>
            )}
            <div
                className={`chatBox ${isOpen ? "chatBoxOpen" : "chatBoxClose"}`}
            >
                <div className="chatBoxMinIcon" onClick={handleChatIconClick}>
                    <i className="fa-solid fa-comment-dots"></i>
                </div>

                {/* Chat Header */}
                <div className="chatBoxHead">
                    <span className="font-semibold">
                        <i class="fa-solid fa-comment-dots"></i> Messages
                    </span>
                    <div className="actionBtns">
                        <button
                            type="button"
                            onClick={() => setIsOpen(!isOpen)}
                        >
                            <i className="fa-solid fa-window-minimize"></i>
                        </button>
                    </div>
                </div>
                {/* Chat Window */}

                <div className={`chatBoxContent`}>
                    {isOpen && (
                        <>
                            {/* Conversations List or Messages */}

                            <div className="conversationBox">
                                <div className="conversationBoxSearch">
                                    <Search className="search-icon" />
                                    <input
                                        type="text"
                                        placeholder="Search Conversations"
                                        value={search}
                                        onChange={(e) =>
                                            setSearch(e.target.value)
                                        }
                                    />
                                </div>

                                {filteredConversations.length === 0 ? (
                                    <div className="noConversations">
                                        No conversations found
                                    </div>
                                ) : (
                                    // Update the conversation rendering to show unread count
                                    filteredConversations.map(
                                        (conversation, index) => (
                                            <div
                                                key={
                                                    conversation.id ||
                                                    `convo-${index}`
                                                }
                                                onClick={() =>
                                                    handleConversationClick(
                                                        conversation
                                                    )
                                                }
                                                className="conversationBoxInner"
                                            >
                                                <div className="conversationUserProfile">
                                                    {conversation.user
                                                        ?.user_has_photo ? (
                                                        <>
                                                            <img
                                                                src={
                                                                    conversation
                                                                        .user
                                                                        .photo
                                                                }
                                                                alt="User Profile"
                                                            />

                                                            <OnlineStatus
                                                                userId={
                                                                    conversation
                                                                        .user.id
                                                                }
                                                                className="avatar-status-badge"
                                                            />
                                                        </>
                                                    ) : (
                                                        <>
                                                            <div className="avatar-initials">
                                                                {conversation
                                                                    .user
                                                                    ?.user_initials ||
                                                                    getInitials(
                                                                        conversation
                                                                            .user
                                                                            ?.first_name,
                                                                        conversation
                                                                            .user
                                                                            ?.last_name
                                                                    )}
                                                            </div>

                                                            {/* ✅ Add online status */}
                                                            <OnlineStatus
                                                                userId={
                                                                    conversation
                                                                        .user.id
                                                                }
                                                                className="avatar-status-badge"
                                                            />
                                                        </>
                                                    )}
                                                </div>
                                                <div className="conversationUserDetails">
                                                    <div className="conversationHeader">
                                                        <div className="conversationUsername">
                                                            {conversation.user
                                                                ?.first_name ??
                                                                ""}{" "}
                                                            {conversation.user
                                                                ?.last_name ??
                                                                ""}
                                                        </div>
                                                        <span className="conversationTime">
                                                            {formatLastMessageTime(
                                                                conversation
                                                                    .last_message
                                                                    ?.created_at
                                                            )}
                                                        </span>
                                                    </div>

                                                    <div className="conversationLastMessage">
                                                        {
                                                            conversation
                                                                .last_message
                                                                ?.content
                                                        }
                                                    </div>
                                                </div>
                                                {/* ✅ Show unread count from Firebase */}
                                                {(unreadCounts[
                                                    conversation.id
                                                ] || 0) > 0 && (
                                                    <div className="conversationUnreadCount">
                                                        {unreadCounts[
                                                            conversation.id
                                                        ] > 99
                                                            ? "99+"
                                                            : unreadCounts[
                                                                  conversation
                                                                      .id
                                                              ]}
                                                    </div>
                                                )}
                                            </div>
                                        )
                                    )
                                )}
                            </div>
                        </>
                    )}
                </div>
            </div>
        </div>
    );
};

export default ChatBox;
