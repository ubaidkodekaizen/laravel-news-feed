import React, { useState } from "react";
import { Send, Smile } from "lucide-react";
import data from "@emoji-mart/data";
import Picker from "@emoji-mart/react";

const MessageInput = ({ onSendMessage, onTyping }) => {
    const [message, setMessage] = useState("");
    const [showEmojiPicker, setShowEmojiPicker] = useState(false);

    const handleSendMessage = () => {
        if (message.trim()) {
            onSendMessage(message);
            setMessage("");
            setShowEmojiPicker(false);
        }
    };

    const handleEmojiSelect = (emoji) => {
        setMessage((prev) => prev + emoji.native);
    };

    const handleKeyPress = (e) => {
        if (e.key === "Enter" && !e.shiftKey) {
            e.preventDefault();
            handleSendMessage();
        }
    };

    return (
        <div className="messageBoxInput relative">
            <div className="messageBoxInputInner">
                <div className="messageBoxInputInnerCol">
                    <textarea
                        value={message}
                        onChange={(e) => {
                            setMessage(e.target.value);
                            onTyping();
                        }}
                        onKeyPress={handleKeyPress}
                        className="messageBoxInputField flex-1"
                        placeholder="Write a message..."
                    ></textarea>
                </div>
                <div className="messageBoxInputInnerCol">
                    <button
                        onClick={() => setShowEmojiPicker(!showEmojiPicker)}
                        className="messageBoxEmojiBtn"
                        type="button"
                    >
                        <Smile className="messageBoxEmojiBtnIcon" />
                    </button>

                    <button
                        onClick={handleSendMessage}
                        className="messageBoxInputBtn"
                        disabled={!message.trim()}
                    >
                        Send
                        <Send className="messageBoxInputBtnIcon" />
                    </button>
                </div>
            </div>

            {showEmojiPicker && (
                <div className="messageBoxEmojiBox">
                    <div className="relative">
                        <div
                            className="fixed inset-0"
                            onClick={() => setShowEmojiPicker(false)}
                        />
                        <Picker
                            data={data}
                            onEmojiSelect={handleEmojiSelect}
                            theme="light"
                            previewPosition="none"
                            skinTonePosition="none"
                        />
                    </div>
                </div>
            )}
        </div>
    );
};

export default MessageInput;
