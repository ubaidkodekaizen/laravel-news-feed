import React, { useState, useEffect } from 'react';
import { ref, onValue } from 'firebase/database';
import { database } from '../firebase.js';

const UnreadCountBadge = () => {
    const [totalUnread, setTotalUnread] = useState(0);

    useEffect(() => {
        if (!window.userId) return;

        // ✅ Changed from unread_total to unread_totals
        const unreadRef = ref(database, `unread_totals/${window.userId}`);

        const unsubscribe = onValue(unreadRef, (snapshot) => {
            const count = snapshot.val() || 0;
            setTotalUnread(count);
        });

        return () => unsubscribe();
    }, []);

    if (totalUnread === 0) return null;

    return (
        <span className="unread-badge">
            {totalUnread > 99 ? '99+' : totalUnread}
        </span>
    );
};

export default UnreadCountBadge;
