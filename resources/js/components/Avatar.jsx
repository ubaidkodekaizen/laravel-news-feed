import React, { useState } from 'react';

const Avatar = ({ user, className = '' }) => {
    const [imageError, setImageError] = useState(false);

    const getInitials = (firstName, lastName) => {
        const first = firstName?.charAt(0)?.toUpperCase() || "";
        const last = lastName?.charAt(0)?.toUpperCase() || "";
        return first + last;
    };

    const hasPhoto = user?.user_has_photo && user?.photo;
    const showImage = hasPhoto && !imageError;

    return (
        <>
            {showImage && (
                <img
                    src={user.photo}
                    alt={`${user.first_name} ${user.last_name}`}
                    className={className}
                    onError={() => setImageError(true)}
                />
            )}
            {!showImage && (
                <div className={`avatar-initials ${className}`}>
                    {user?.user_initials || getInitials(user?.first_name, user?.last_name)}
                </div>
            )}
        </>
    );
};

export default Avatar;
