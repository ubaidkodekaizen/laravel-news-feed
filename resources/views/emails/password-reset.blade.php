<p>You requested a password reset. Click the link below to reset your password:</p>
<p>
    <a href="{{ route('password.reset', ['token' => $token]) }}">Reset Password</a>
</p>
<p>If you did not request this password reset, please ignore this email.</p>
