import React, { useState, useEffect } from 'react';
import { listenToUserStatus } from '../firebase.js';

const OnlineStatus = ({ userId, showText = false, className = "" }) => {
    const [status, setStatus] = useState({ online: false, last_active: null });

    useEffect(() => {
        if (!userId) return;

        const unsubscribe = listenToUserStatus(userId, (statusData) => {
            setStatus(statusData);
        });

        return () => {
            if (unsubscribe) unsubscribe();
        };
    }, [userId]);

    const formatLastSeen = (timestamp) => {
        if (!timestamp) return 'Offline';

        const now = Date.now();
        const diff = now - timestamp;

        const minutes = Math.floor(diff / 60000);
        const hours = Math.floor(diff / 3600000);
        const days = Math.floor(diff / 86400000);

        if (minutes < 1) return 'Just now';
        if (minutes < 60) return `${minutes}m ago`;
        if (hours < 24) return `${hours}h ago`;
        if (days < 7) return `${days}d ago`;
        return 'Offline';
    };

    if (showText) {
        return (
            <span className={`online-status-text ${status.online ? 'online' : 'offline'} ${className}`}>
                {status.online ? 'Online' : formatLastSeen(status.last_active)}
            </span>
        );
    }

    return (
        <span
            className={`online-status-indicator ${status.online ? 'online' : 'offline'} ${className}`}
            title={status.online ? 'Online' : formatLastSeen(status.last_active)}
        />
    );
};

export default OnlineStatus;
