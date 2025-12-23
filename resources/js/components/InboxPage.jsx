// resources/js/components/InboxPage.jsx
import React, { useState, useEffect, useRef } from 'react';
import { Search, MoreVertical, Archive, Trash2, Star } from 'lucide-react';
import MessageList from "./MessageList.jsx";
import MessageInput from './MessageInput.jsx';
import { format } from 'date-fns';

const InboxPage = () => {
  const [conversations, setConversations] = useState([]);
  const [messages, setMessages] = useState([]);
  const [activeConversation, setActiveConversation] = useState(null);
  const [activeUser, setActiveUser] = useState(null);
  const [search, setSearch] = useState("");
  const [loading, setLoading] = useState(true);
  const [typingUser, setTypingUser] = useState(null);
  const messagesEndRef = useRef(null);
  const token = localStorage.getItem("sanctum-token");
  const DEFAULT_AVATAR = 'https://static.vecteezy.com/system/resources/previews/009/292/244/non_2x/default-avatar-icon-of-social-media-user-vector.jpg';

  const scrollToBottom = () => {
    messagesEndRef.current?.scrollIntoView({ behavior: "smooth" });
  };

  useEffect(() => {
    if (!window.Echo) {
      console.error("Echo is not initialized yet.");
      return;
    }

    fetchConversations();

    // Subscribe to new messages
    const messageChannel = window.Echo.private(`private-chat.${window.userId}`);

    messageChannel.listen('.message.new', async (message) => {
      console.log("New message received:", message);

      if (message.conversation_id === activeConversation) {
        setMessages((prevMessages) => [
          ...prevMessages,
          {
            id: message.id,
            conversation_id: message.conversation_id,
            sender_id: message.sender.id,
            content: message.content,
            created_at: message.created_at,
            sender: message.sender,
          },
        ]);
      }
      await fetchConversations();
    });

    // Subscribe to typing indicators
    let userActivityChannel;
    if (activeConversation) {
      userActivityChannel = window.Echo.private(`user-activity.${activeConversation}`);

      userActivityChannel.listen('.user.typing', (e) => {
        if (e.conversation_id === activeConversation && e.user.id !== window.userId) {
          showTypingIndicator(e.user);
        }
      });
    }

    return () => {
      window.Echo.leave(`private-chat.${window.userId}`);
      if (activeConversation) {
        window.Echo.leave(`user-activity.${activeConversation}`);
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
      const response = await axios.get(getConversations, {
        headers: { "Authorization": token },
      });
      setConversations(response.data);
      setLoading(false);
    } catch (error) {
      console.error('Error fetching conversations:', error);
      setLoading(false);
    }
  };

  const fetchMessages = async (conversationId) => {
    try {
      const url = getMessageRoute(conversationId);
      const response = await axios.get(url, {
        headers: { "Authorization": token }
      });
      setMessages(response.data);
    } catch (error) {
      console.error('Error fetching messages:', error);
    }
  };

  const fetchUserDetails = async (conversationId) => {
    try {
      const url = getUserConversationRoute(conversationId);
      const response = await axios.get(url, {
        headers: { "Authorization": token },
      });
      setActiveUser(response.data);
    } catch (error) {
      console.error('Error fetching user details:', error);
    }
  };

  const handleConversationClick = async (conversation) => {
    setActiveConversation(conversation.id);
    await fetchMessages(conversation.id);
    await fetchUserDetails(conversation.id);
  };

  const sendMessage = async (messageContent) => {
    if (!messageContent.trim() || !activeConversation) return;

    const activeUserData = conversations.find((convo) => convo.id === activeConversation)?.user;
    if (!activeUserData) return;

    try {
      const response = await axios.post(
        sendMsg,
        {
          content: messageContent,
          receiver_id: activeUserData.id,
        },
        {
          headers: { Authorization: token },
        }
      );

      setMessages((prev) => [...prev, response.data]);
      await fetchConversations();
    } catch (error) {
      console.error('Error sending message:', error);
    }
  };

  const handleTyping = async () => {
    if (!activeConversation) return;

    try {
      await axios.post(userIsTyping, {
        conversation_id: activeConversation
      }, {
        headers: { "Authorization": token }
      });
    } catch (error) {
      console.error('Error sending typing event:', error);
    }
  };

  let typingTimeout;
  const showTypingIndicator = (user) => {
    setTypingUser(user);
    clearTimeout(typingTimeout);
    typingTimeout = setTimeout(() => setTypingUser(null), 3000);
  };

  const filteredConversations = conversations.filter((conversation) => {
    const fullName = `${conversation.user?.first_name ?? ""} ${conversation.user?.last_name ?? ""}`.toLowerCase();
    return fullName.includes(search.toLowerCase());
  });

  const formatLastMessageTime = (date) => {
    if (!date) return '';
    const messageDate = new Date(date);
    const today = new Date();
    const yesterday = new Date(today);
    yesterday.setDate(yesterday.getDate() - 1);

    if (messageDate.toDateString() === today.toDateString()) {
      return format(messageDate, 'h:mm a');
    } else if (messageDate.toDateString() === yesterday.toDateString()) {
      return 'Yesterday';
    } else {
      return format(messageDate, 'MMM d');
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
                onClick={() => handleConversationClick(conversation)}
                className={`inbox-conversation-item ${
                  activeConversation === conversation.id ? 'active' : ''
                }`}
              >
                <div className="inbox-conversation-avatar">
                  <img
                    src={conversation.user?.photo ?? DEFAULT_AVATAR}
                    alt="User Profile"
                    onError={(e) => (e.target.src = DEFAULT_AVATAR)}
                  />
                  {conversation.unread_count > 0 && (
                    <span className="inbox-unread-badge">
                      {conversation.unread_count}
                    </span>
                  )}
                </div>

                <div className="inbox-conversation-details">
                  <div className="inbox-conversation-header">
                    <h4 className="inbox-conversation-name">
                      {conversation.user?.first_name ?? ""} {conversation.user?.last_name ?? ""}
                    </h4>
                    <span className="inbox-conversation-time">
                      {formatLastMessageTime(conversation.last_message?.created_at)}
                    </span>
                  </div>
                  <div className="inbox-conversation-preview">
                    <p className={conversation.unread_count > 0 ? 'unread' : ''}>
                      {conversation.last_message?.content ?? 'No messages yet'}
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
                <img
                  src={activeUser.photo ?? DEFAULT_AVATAR}
                  alt="User"
                  className="inbox-main-header-avatar"
                />
                <div className="inbox-main-header-info">
                  <a href={`/user/profile/${activeUser.slug}`} className="inbox-main-header-name">
                    {activeUser.first_name ?? 'Unknown'} {activeUser.last_name ?? 'User'}
                  </a>
                  {typingUser && (
                    <span className="inbox-typing-indicator">
                      {typingUser.first_name} is typing...
                    </span>
                  )}
                </div>
              </div>

              <div className="inbox-main-header-actions">
                <button className="inbox-action-btn" title="More options">
                  <MoreVertical size={20} />
                </button>
              </div>
            </div>

            <div className="inbox-messages-container">
              {messages.length === 0 ? (
                <div className="inbox-no-messages">
                  <p>No messages yet. Start the conversation!</p>
                </div>
              ) : (
                <MessageList messages={messages} />
              )}
              <div ref={messagesEndRef}></div>
            </div>

            <MessageInput
              onSendMessage={sendMessage}
              onTyping={handleTyping}
            />
          </>
        ) : (
          <div className="inbox-no-selection">
            <div className="inbox-no-selection-content">
              <svg width="200" height="200" viewBox="0 0 200 200" fill="none">
                <circle cx="100" cy="100" r="80" stroke="#e0e0e0" strokeWidth="2"/>
                <path d="M70 90 L100 110 L130 90" stroke="#e0e0e0" strokeWidth="2" fill="none"/>
                <path d="M70 90 L70 130 L130 130 L130 90" stroke="#e0e0e0" strokeWidth="2" fill="none"/>
              </svg>
              <h3>Select a conversation</h3>
              <p>Choose a conversation from the sidebar to start messaging</p>
            </div>
          </div>
        )}
      </div>
    </div>
  );
};

export default InboxPage;
