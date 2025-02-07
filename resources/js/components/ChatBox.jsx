import React, { useState, useEffect, useRef } from 'react';
import { MessageCircle, X, Send, MinusSquare, Square, User } from 'lucide-react';
import MessageList from "./MessageList.jsx";
import MessageInput from './MessageInput.jsx';
const ChatBox = () => {
  const [isOpen, setIsOpen] = useState(false);
  const [isMinimized, setIsMinimized] = useState(false);
  const [hideMessageBox, setHideMessageBox] = useState(false);
  const [message, setMessage] = useState('');
  const [messages, setMessages] = useState([]);
  const [conversations, setConversations] = useState([]);
  const [activeConversation, setActiveConversation] = useState(null);
  const [activeUser, setActiveUser] = useState(null); // Store the user data
  const messagesEndRef = useRef(null);
  const [userId, setUserId] = useState(window.userId);
  const [typingUser, setTypingUser] = useState(null);
  const [search, setSearch] = useState("");
  const token = localStorage.getItem("sanctum-token");
  const DEFAULT_AVATAR = 'https://static.vecteezy.com/system/resources/previews/009/292/244/non_2x/default-avatar-icon-of-social-media-user-vector.jpg';
  const scrollToBottom = () => {
    messagesEndRef.current?.scrollIntoView({ behavior: "smooth" });
  };

  useEffect(() => {
    console.log("User changed, resetting chat state...");
    setConversations([]); // Clear conversations
    setMessages([]); // Clear messages
    setActiveConversation(null); // Reset active conversation
    setActiveUser(null); // Reset active user
    fetchConversations(); // Fetch new conversations for the logged-in user
  }, [userId]); // Run this effect whenever userId changes

  useEffect(() => {
    if (!window.Echo) {
      console.error("Echo is not initialized yet.");
      return;
    }
  
    // Fetch conversations from the API
    fetchConversations();
  
    // Subscribe to the private channel for new messages
    const messageChannel = window.Echo.private(`private-chat.${window.userId}`);
    console.log("Message Channel initialized:", messageChannel);
  
    messageChannel.listen('.message.new', async (message) => {
      console.log("message.new received:", message);
  
      if (message.conversation_id === activeConversation) {
        setMessages((prevMessages) => [
          ...prevMessages,
          {
            id: message.id,
            conversation_id: message.conversation_id,
            sender_id: message.sender.id,
            content: message.content,
            created_at: message.created_at,
            sender: {
              id: message.sender.id,
              first_name: message.sender.first_name,
              last_name: message.sender.last_name,
              email: message.sender.email,
              photo: message.sender.photo,
            },
          },
        ]);
      } else {
        await fetchConversations(activeConversation);
      }
    });
  
    // Subscribe to the user activity channel for typing indicators
    let userActivityChannel;
    if (activeConversation) {
      console.log(`Subscribing to user-activity.${activeConversation}...`);
      userActivityChannel = window.Echo.private(`user-activity.${activeConversation}`);
  
      userActivityChannel.listen('.user.typing', (e) => {
        console.log('Typing event received:', e);
        if (e.conversation_id === activeConversation) {
          showTypingIndicator(e.user);
        }
      });
    }
  
    // Cleanup function
    return () => {
      console.log(`Leaving private-chat.${window.userId}...`);
      window.Echo.leave(`private-chat.${window.userId}`);
  
      if (activeConversation) {
        console.log(`Leaving user-activity.${activeConversation}...`);
        window.Echo.leave(`user-activity.${activeConversation}`);
      }
    };
  }, [activeConversation, userId]);

  
  useEffect(() => {
    // Scroll to the bottom when the component is first loaded or when messages change
    const timer = setTimeout(() => {
      scrollToBottom();
    }, 100); // Give React time to update the DOM

    return () => clearTimeout(timer); // Cleanup the timeout
  }, [messages, activeConversation, conversations, activeUser]);

 
  const filteredConversations = conversations.filter((conversation) => {
    const fullName = `${conversation.user?.first_name ?? ""} ${conversation.user?.last_name ?? ""}`.toLowerCase();
    return fullName.includes(search.toLowerCase());
  });
  


  const fetchConversations = async () => {
    const token = localStorage.getItem("sanctum-token");
  
    if (!token) {
      console.error("Token not found. Cannot fetch conversations.");
      return;
    }
  
    try {
      const response = await axios.get('/api/conversations', {
        headers: {
          "Authorization": token,
        },
      });
      setConversations(response.data);
    } catch (error) {
      console.error('Error fetching conversations:', error);
    }
  };

  const fetchMessages = async (conversationId) => {
    try {
      const response = await window.axios.get(`/api/conversations/${conversationId}/messages`, {
        headers: {
          "Authorization": token
        }
      });
      console.log("messages", response.data);
      setMessages(response.data);

    } catch (error) {
      console.error('Error fetching messages:', error);
    }
  };

  const handleConversationClick = (conversation) => {
    setActiveConversation(conversation.id);
    fetchMessages(conversation.id);
    setHideMessageBox(false);
  };

  const sendMessage = async (messageContent) => {
    console.log("Sending message:", messageContent); // Debugging
  
    if (!messageContent.trim() || !activeConversation) return;
  
    const activeUser = conversations.find((convo) => convo.id === activeConversation)?.user;
    if (!activeUser) return;
  
    try {
      const response = await window.axios.post(
        '/api/messages/send',
        {
          content: messageContent,
          receiver_id: activeUser.id,
        },
        {
          headers: { Authorization: token },
        }
      );
  
      console.log("Message sent successfully:", response.data); // Debugging
  
      setMessages((prev) => [...prev, response.data]);
      await fetchConversations();
      await fetchMessages(activeConversation);
    } catch (error) {
      console.error('Error sending message:', error);
    }
  };
  

  useEffect(() => {
    if (activeConversation) {
      fetchUserDetails(activeConversation);
    }
  }, [activeConversation, userId]);

  const openChatWithUser = async (userId) => {
    // First, check if a conversation with this user exists
    const existingConversation = conversations.find(
      convo => convo.user.id === userId
    );

    if (existingConversation) {
      // If conversation exists, open it
      handleConversationClick(existingConversation);
      setIsOpen(true);
    } else {
      // If no conversation exists, fetch or create a conversation
      try {
        const response = await axios.post('/api/conversations/create',
          { user_id: userId }, 
          {
            headers: {
              "Authorization": token
            }
          }
        );

        // Refresh conversations and open the new conversation
        await fetchConversations();
        handleConversationClick(response.data);
        setIsOpen(true);
      } catch (error) {
        console.error('Error creating conversation:', error);
      }
    }
  };

  // Expose the method globally
  useEffect(() => {
    window.openChatWithUser = openChatWithUser;
  }, [conversations]);

  const fetchUserDetails = async (conversationId) => {
    try {
      const response = await window.axios.get(`/api/conversations/${conversationId}/user`, {
        headers: {
          "Authorization": token,
        },
      });
      setActiveUser(response.data); // Assuming response.data contains user info
      console.log("active user", response.data);
    } catch (error) {
      console.error('Error fetching user details:', error);
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
    if (minutes < 60) return `${minutes} minute${minutes !== 1 ? 's' : ''} ago`;
    if (hours < 24) return `${hours} hour${hours !== 1 ? 's' : ''} ago`;
    if (days < 30) return `${days} day${days !== 1 ? 's' : ''} ago`;
    if (months < 12) return `${months} month${months !== 1 ? 's' : ''} ago`;
    return `${years} year${years !== 1 ? 's' : ''} ago`;
  }
  // Helper function to format time like "5:26 AM"
  function formatTime(date) {
    const d = new Date(date);
    let hours = d.getHours();
    let minutes = d.getMinutes();
    const ampm = hours >= 12 ? 'PM' : 'AM';

    hours = hours % 12;
    hours = hours ? hours : 12; // the hour '0' should be '12'
    minutes = minutes < 10 ? '0' + minutes : minutes;

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

    try {
      await axios.post('/api/typing', {
        conversation_id: activeConversation
      }, {
        headers: {
          "Authorization": token
        }
      });
    } catch (error) {
      console.error('Error sending typing event:', error);
    }
  };



  return (<div className='chatContainerInner' key={userId}>
    {activeConversation && activeUser && (
      // Messages View
      <div className={`messageBox messageBoxListOpen ${isMinimized ? 'messageBoxListClose' : 'messageBoxListOpen'} ${hideMessageBox ? 'messageBoxHide' : 'messageBoxShow'}`}>
        <div className='messageBoxHead' onClick={() => setIsMinimized(!isMinimized)}>
          <div className='messageBoxHeadInner'>
            <img
              src={activeUser.photo ?? DEFAULT_AVATAR} // Use activeUser.photo
              alt="Sender"
              className="messageBoxHeadSenderPhoto"
            />
            <div className={`messageBoxHeadContent`}>
              <a href={`/user/profile/${activeUser.slug}`} className='messageBoxHeadUsername'>
                {activeUser.first_name ?? 'Unknown'} {activeUser.last_name ?? 'User'}
              </a>
              {typingUser && (
                <div className="typing-indicator">
                  {typingUser.first_name} is typing...
                </div>
              )}
            </div>
          </div>
          <div className='messageBoxHeadInner'>
            <button type='button' className='btn-close closeMessageBoxBtn' onClick={() => setHideMessageBox(!hideMessageBox)}></button>
          </div>

        </div>
        <div className='messageBoxList'>
          {messages.length === 0 ? (
            <div className="noMessages">
              No messages found yet
            </div>
          ) : (
            <MessageList messages={messages} />
          )}
          <div ref={messagesEndRef}></div>
        </div>




        {/* Message Input */}
        <MessageInput
          onSendMessage={sendMessage}
          onTyping={handleTyping}
        />
      </div>
    )}
    <div className={`chatBox ${isOpen ? 'chatBoxOpen' : 'chatBoxClose'}`} >
       <div className='chatBoxMinIcon' onClick={() => setIsOpen(!isOpen)}>
        <i className="fa-solid fa-comment-dots"></i>
       </div>
       
      {/* Chat Header */}
      <div className="chatBoxHead" >
        <span className="font-semibold">Messages</span>
        <div className="actionBtns">
          <button type='button' onClick={() => setIsOpen(!isOpen)}>
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
        <input
          type="text"
          placeholder="Search Conversations"
          value={search}
          onChange={(e) => setSearch(e.target.value)}
        />
      </div>

      {filteredConversations.length === 0 ? (
        <div className="noConversations">No conversations found</div>
      ) : (
        filteredConversations.map((conversation, index) => (
          <div
            key={conversation.id || `convo-${index}`}
            onClick={() => handleConversationClick(conversation)}
            className="conversationBoxInner"
          >
            <div className="conversationUserProfile">
              <img
                src={conversation.user?.photo ?? DEFAULT_AVATAR}
                alt="User Profile"
                onError={(e) => (e.target.src = DEFAULT_AVATAR)}
              />
            </div>
            <div className="conversationUserDetails">
              <div className="conversationUsername">
                {conversation.user?.first_name ?? ""} {conversation.user?.last_name ?? ""}
              </div>
              <div className="conversationLastMessage">{conversation.last_message?.content}</div>
            </div>
            {(conversation.unread_count || 0) > 0 && (
              <div className="conversationUnreadCount">{conversation.unread_count}</div>
            )}
          </div>
        ))
      )}
    </div>


            


          </>
        )}
      </div>

    </div>
  </div>);
};

export default ChatBox;