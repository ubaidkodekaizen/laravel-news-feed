import React from 'react';
import { format, isToday, isYesterday, isSameDay } from 'date-fns';

const MessageList = ({ messages }) => {
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
            messages: currentGroup
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
        messages: currentGroup
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

  const messageGroups = groupMessagesByDate(messages);

  return (
    <div className="flex flex-col space-y-4">
      {messageGroups.map((group, groupIndex) => (
        <div key={groupIndex} className="space-y-4">
          <div className="flex items-center justify-center">
            <div className="messageDateHeader">
              {formatDateHeader(group.date)}
            </div>
          </div>
          {group.messages.map((msg, index) => (
            <div
              key={msg.id || `msg-${index}`}
              className={`messageBoxListItem ${
                msg.sender_id === window.userId ? 'messageRight' : 'messageLeft'
              }`}
            >
              <div className="messageBoxListItemInner">
                <img
                  src={msg.sender?.photo ?? DEFAULT_AVATAR}
                  alt="Sender"
                  className="messageSenderPhoto"
                />
                <div
                  className={`messageBoxListItemContent ${
                    msg.sender_id === window.userId
                      ? 'bg-blue-600 text-black'
                      : 'bg-gray-100 text-gray-900'
                  }`}
                >
                  <div className="messageBoxListItemContentUsername">
                     <a href={`/user/profile/${msg.sender.slug}`}>
                     {msg.sender?.first_name ?? 'Unknown'} {msg.sender?.last_name ?? 'User'}
                     </a>
                    <div className="messageBoxTime">
                      {format(new Date(msg.created_at), 'h:mm a')}
                    </div>
                  </div>
                  <div className="messageBoxListItemContentMsg">
                    {msg.content}
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