<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Documentation - MuslimLynk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f5f5f5;
        }
        .navbar {
            background: #273572;
            color: white;
            padding: 15px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .navbar-brand {
            font-weight: 700;
            font-size: 24px;
        }
        .container-main {
            max-width: 1400px;
            margin: 0 auto;
            padding: 30px 20px;
        }
        .api-section {
            background: white;
            border-radius: 10px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .api-section h2 {
            color: #273572;
            font-weight: 700;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 3px solid #B8C034;
        }
        .api-endpoint {
            margin-bottom: 40px;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
            border-left: 4px solid #273572;
        }
        .api-endpoint h3 {
            color: #273572;
            font-weight: 600;
            margin-bottom: 15px;
        }
        .method-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 5px;
            font-weight: 600;
            font-size: 12px;
            margin-right: 10px;
        }
        .method-get { background: #28a745; color: white; }
        .method-post { background: #007bff; color: white; }
        .method-put { background: #ffc107; color: #000; }
        .method-delete { background: #dc3545; color: white; }
        .endpoint-url {
            font-family: 'Courier New', monospace;
            background: #273572;
            color: #B8C034;
            padding: 8px 15px;
            border-radius: 5px;
            display: inline-block;
            margin: 10px 0;
        }
        .auth-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 5px;
            font-size: 11px;
            font-weight: 600;
            margin-left: 10px;
        }
        .auth-required { background: #dc3545; color: white; }
        .auth-public { background: #28a745; color: white; }
        .code-block {
            background: #2d2d2d;
            color: #f8f8f2;
            padding: 20px;
            border-radius: 8px;
            overflow-x: auto;
            margin: 15px 0;
        }
        .code-block pre {
            margin: 0;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            line-height: 1.6;
        }
        .param-table {
            width: 100%;
            margin: 15px 0;
            border-collapse: collapse;
        }
        .param-table th,
        .param-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .param-table th {
            background: #273572;
            color: white;
            font-weight: 600;
        }
        .param-table tr:hover {
            background: #f5f5f5;
        }
        .required {
            color: #dc3545;
            font-weight: 600;
        }
        .logout-btn {
            background: #dc3545;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
        }
        .logout-btn:hover {
            background: #c82333;
            color: white;
        }
        .base-url {
            background: #273572;
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 30px;
            font-family: 'Courier New', monospace;
        }
        .base-url strong {
            color: #B8C034;
        }
        .main-wrapper {
            display: flex;
            gap: 30px;
            margin-top: 20px;
        }
        .sidebar {
            width: 280px;
            flex-shrink: 0;
            position: sticky;
            top: 20px;
            height: fit-content;
            max-height: calc(100vh - 100px);
            overflow-y: auto;
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .sidebar h3 {
            color: #273572;
            font-weight: 700;
            font-size: 16px;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #B8C034;
        }
        .nav-group {
            margin-bottom: 25px;
        }
        .nav-group-title {
            color: #273572;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
            padding-left: 10px;
        }
        .nav-link {
            display: block;
            padding: 8px 10px;
            color: #555;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            transition: all 0.3s;
            margin-bottom: 3px;
        }
        .nav-link:hover {
            background: #f0f0f0;
            color: #273572;
            padding-left: 15px;
        }
        .nav-link.active {
            background: #273572;
            color: white;
            font-weight: 600;
        }
        .content-area {
            flex: 1;
            min-width: 0;
        }
        .api-section {
            scroll-margin-top: 20px;
        }
        @media (max-width: 992px) {
            .main-wrapper {
                flex-direction: column;
            }
            .sidebar {
                width: 100%;
                position: relative;
                max-height: none;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="container-main">
            <div class="d-flex justify-content-between align-items-center">
                <span class="navbar-brand">📚 API Documentation</span>
                <a href="{{ route('api.doc.logout') }}" class="logout-btn">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container-main">
        <div class="base-url">
            <strong>Base URL:</strong> {{ url('/api') }}
        </div>

        <div class="main-wrapper">
            <!-- Sidebar Navigation -->
            <nav class="sidebar">
                <h3>📑 Navigation</h3>
                
                <div class="nav-group">
                    <div class="nav-group-title">Getting Started</div>
                    <a href="#authentication" class="nav-link">🔐 Authentication</a>
                </div>

                <div class="nav-group">
                    <div class="nav-group-title">Public APIs</div>
                    <a href="#public-routes" class="nav-link">🌐 Public Routes</a>
                </div>

                <div class="nav-group">
                    <div class="nav-group-title">Communication</div>
                    <a href="#chat-messaging" class="nav-link">💬 Chat & Messaging</a>
                    <a href="#news-feed" class="nav-link">📰 News Feed</a>
                </div>

                <div class="nav-group">
                    <div class="nav-group-title">User Management</div>
                    <a href="#user-profile" class="nav-link">👤 User Profile</a>
                    <a href="#products" class="nav-link">📦 Products</a>
                    <a href="#services" class="nav-link">🛠️ Services</a>
                    <a href="#qualifications" class="nav-link">🎓 Qualifications</a>
                </div>

                <div class="nav-group">
                    <div class="nav-group-title">Discovery</div>
                    <a href="#discovery-search" class="nav-link">🔍 Discovery & Search</a>
                </div>

                <div class="nav-group">
                    <div class="nav-group-title">Notifications</div>
                    <a href="#device-tokens" class="nav-link">📱 Device Tokens (FCM)</a>
                    <a href="#notifications" class="nav-link">🔔 Notifications</a>
                </div>

                <div class="nav-group">
                    <div class="nav-group-title">System</div>
                    <a href="#firebase" class="nav-link">🔥 Firebase</a>
                    <a href="#subscriptions" class="nav-link">💳 Subscriptions</a>
                    <a href="#api-key-routes" class="nav-link">🔑 API Key Routes</a>
                </div>
            </nav>

            <!-- Content Area -->
            <div class="content-area">
                <div class="base-url">
                    <strong>Base URL:</strong> {{ url('/api') }}
                </div>

                <!-- Authentication Section -->
                <div id="authentication" class="api-section">
            <h2>🔐 Authentication</h2>
            <p>Most API endpoints require authentication using Laravel Sanctum. Include the token in the Authorization header:</p>
            <div class="code-block">
                <pre>Authorization: Bearer {your_token}</pre>
            </div>
        </div>

        <!-- Public API Routes -->
        <div id="public-routes" class="api-section">
            <h2>🌐 Public API Routes (No Authentication Required)</h2>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-get">GET</span>
                    Get User Dropdowns
                    <span class="auth-badge auth-public">PUBLIC</span>
                </h3>
                <div class="endpoint-url">/user/dropdowns</div>
                <p>Get all dropdown options for user registration (designations, industries, business types, etc.)</p>
                
                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "designations": [...],
  "industries": [...],
  "business_types": [...],
  "nationalities": [...],
  "employee_sizes": {...},
  "revenue_ranges": {...},
  "company_experiences": [...],
  "genders": [...]
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-get">GET</span>
                    Get Users (Paginated)
                    <span class="auth-badge auth-public">PUBLIC</span>
                </h3>
                <div class="endpoint-url">/users?per_page=10&page=1</div>
                <p>Get a paginated list of users (only users with <code>status=complete</code>).</p>

                <p><strong>Query Parameters:</strong></p>
                <ul>
                    <li><strong>per_page</strong> - Number of users per page (default: 10)</li>
                    <li><strong>page</strong> - Page number (default: 1)</li>
                </ul>

                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "status": true,
  "message": "Users fetched successfully.",
  "users": {
    "current_page": 1,
    "data": [
      {
        "user_id": 123,
        "first_name": "John",
        "last_name": "Doe",
        "profile_pic": "https://...",
        "designation": "CEO",
        "company": "Example Inc",
        "phone_number": "+1234567890",
        "city": "New York",
        "state": "NY"
      }
    ],
    "per_page": 10,
    "total": 100,
    "last_page": 10
  }
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-post">POST</span>
                    Register User
                    <span class="auth-badge auth-public">PUBLIC</span>
                </h3>
                <div class="endpoint-url">/register</div>
                <p>Register a new user account</p>
                
                <h5>Request Body:</h5>
                <table class="param-table">
                    <thead>
                        <tr>
                            <th>Parameter</th>
                            <th>Type</th>
                            <th>Required</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>first_name</td>
                            <td>string</td>
                            <td><span class="required">Yes</span></td>
                            <td>User's first name</td>
                        </tr>
                        <tr>
                            <td>last_name</td>
                            <td>string</td>
                            <td><span class="required">Yes</span></td>
                            <td>User's last name</td>
                        </tr>
                        <tr>
                            <td>email</td>
                            <td>email</td>
                            <td><span class="required">Yes</span></td>
                            <td>User's email address (must be unique)</td>
                        </tr>
                        <tr>
                            <td>phone</td>
                            <td>string</td>
                            <td><span class="required">Yes</span></td>
                            <td>User's phone number</td>
                        </tr>
                        <tr>
                            <td>password</td>
                            <td>string</td>
                            <td><span class="required">Yes</span></td>
                            <td>Password (min 8 characters)</td>
                        </tr>
                    </tbody>
                </table>

                <h5>Request Body Example:</h5>
                <div class="code-block">
                    <pre>{
  "first_name": "John",
  "last_name": "Doe",
  "email": "john@example.com",
  "phone": "+1234567890",
  "password": "password123"
}</pre>
                </div>

                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "status": true,
  "message": "User registered successfully. Please check your email to verify your account.",
  "token": "1|xxxxxxxxxxxxx",
  "user": {
    "id": 1,
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@example.com",
    ...
  }
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-post">POST</span>
                    Login
                    <span class="auth-badge auth-public">PUBLIC</span>
                </h3>
                <div class="endpoint-url">/login</div>
                <p>Authenticate user and get access token</p>
                
                <h5>Request Body:</h5>
                <table class="param-table">
                    <thead>
                        <tr>
                            <th>Parameter</th>
                            <th>Type</th>
                            <th>Required</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>email</td>
                            <td>email</td>
                            <td><span class="required">Yes</span></td>
                            <td>User's email address</td>
                        </tr>
                        <tr>
                            <td>password</td>
                            <td>string</td>
                            <td><span class="required">Yes</span></td>
                            <td>User's password</td>
                        </tr>
                        <tr>
                            <td>fcm_token</td>
                            <td>string</td>
                            <td><span class="optional">Optional</span></td>
                            <td>FCM token for push notifications (mobile/web)</td>
                        </tr>
                        <tr>
                            <td>device_type</td>
                            <td>string</td>
                            <td><span class="optional">Optional</span></td>
                            <td>Device type: 'ios', 'android', or 'web'</td>
                        </tr>
                        <tr>
                            <td>device_id</td>
                            <td>string</td>
                            <td><span class="optional">Optional</span></td>
                            <td>Unique device identifier</td>
                        </tr>
                        <tr>
                            <td>device_name</td>
                            <td>string</td>
                            <td><span class="optional">Optional</span></td>
                            <td>Device name/model</td>
                        </tr>
                    </tbody>
                </table>

                <h5>Request Body Example:</h5>
                <div class="code-block">
                    <pre>{
  "email": "user@example.com",
  "password": "password123",
  "fcm_token": "f9z8fQiPGt-e35HJciB18K:APA91b...",
  "device_type": "android",
  "device_id": "device_123",
  "device_name": "Samsung Galaxy S21"
}</pre>
                </div>

                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "status": true,
  "message": "Login successful.",
  "token": "1|xxxxxxxxxxxxx",
  "user": {
    "id": 1,
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@example.com",
    ...
  },
  "redirect_to": "feed"
}</pre>
                </div>
                <p><strong>Note:</strong> Subscription information is no longer included in the login response. Use the <code>/user/profile/{slug}</code> endpoint to check subscription status.</p>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-post">POST</span>
                    Logout
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/logout</div>
                <p>Logout user and revoke Sanctum access token</p>
                
                <h5>Request Headers:</h5>
                <div class="code-block">
                    <pre>Authorization: Bearer {token}</pre>
                </div>
                
                <h5>Request Body (Optional):</h5>
                <table class="param-table">
                    <thead>
                        <tr>
                            <th>Parameter</th>
                            <th>Type</th>
                            <th>Required</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>fcm_token</td>
                            <td>string</td>
                            <td><span class="optional">Optional</span></td>
                            <td>FCM token to remove from device tokens (if provided, will be removed on logout)</td>
                        </tr>
                    </tbody>
                </table>

                <h5>Request Body Example:</h5>
                <div class="code-block">
                    <pre>{
  "fcm_token": "f9z8fQiPGt-e35HJciB18K:APA91b..."
}</pre>
                </div>

                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "status": true,
  "message": "Logged out successfully."
}</pre>
                </div>
                
                <h5>What happens on logout:</h5>
                <ul>
                    <li>Current Sanctum access token is revoked</li>
                    <li>User's online status is set to offline (Firebase)</li>
                    <li>FCM device token is removed (if provided in request)</li>
                </ul>
                
                <p><strong>Note:</strong> After logout, the token becomes invalid and cannot be used for authenticated requests. User must login again to get a new token.</p>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-post">POST</span>
                    Forget Password
                    <span class="auth-badge auth-public">PUBLIC</span>
                </h3>
                <div class="endpoint-url">/forget-password</div>
                <p>Send password reset link to user's email</p>
                
                <h5>Request Body:</h5>
                <table class="param-table">
                    <thead>
                        <tr>
                            <th>Parameter</th>
                            <th>Type</th>
                            <th>Required</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>email</td>
                            <td>email</td>
                            <td><span class="required">Yes</span></td>
                            <td>User's email address</td>
                        </tr>
                    </tbody>
                </table>

                <h5>Request Body Example:</h5>
                <div class="code-block">
                    <pre>{
  "email": "user@example.com"
}</pre>
                </div>

                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "status": true,
  "message": "Password reset link sent to your email"
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-post">POST</span>
                    Register AMCOB User
                    <span class="auth-badge auth-public">PUBLIC</span>
                </h3>
                <div class="endpoint-url">/register-amcob</div>
                <p>Register a new user account via AMCOB API</p>
                
                <h5>Request Body:</h5>
                <table class="param-table">
                    <thead>
                        <tr>
                            <th>Parameter</th>
                            <th>Type</th>
                            <th>Required</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>first_name</td>
                            <td>string</td>
                            <td><span class="required">Yes</span></td>
                            <td>User's first name</td>
                        </tr>
                        <tr>
                            <td>last_name</td>
                            <td>string</td>
                            <td><span class="required">Yes</span></td>
                            <td>User's last name</td>
                        </tr>
                        <tr>
                            <td>email</td>
                            <td>email</td>
                            <td><span class="required">Yes</span></td>
                            <td>User's email address (must be unique)</td>
                        </tr>
                        <tr>
                            <td>phone</td>
                            <td>string</td>
                            <td><span class="required">Yes</span></td>
                            <td>User's phone number</td>
                        </tr>
                        <tr>
                            <td>password</td>
                            <td>string</td>
                            <td><span class="required">Yes</span></td>
                            <td>Password (min 8 characters)</td>
                        </tr>
                    </tbody>
                </table>

                <h5>Request Body Example:</h5>
                <div class="code-block">
                    <pre>{
  "first_name": "John",
  "last_name": "Doe",
  "email": "john@example.com",
  "phone": "+1234567890",
  "password": "password123"
}</pre>
                </div>

                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "status": true,
  "message": "User registered successfully.",
  "user": {
    "id": 1,
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@example.com",
    ...
  }
}</pre>
                </div>
            </div>
        </div>

        <!-- Chat & Messaging Routes -->
        <div id="chat-messaging" class="api-section">
            <h2>💬 Chat & Messaging</h2>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-get">GET</span>
                    Get Conversations
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/conversations</div>
                <p>Get all conversations for the authenticated user</p>
                
                <h5>Response:</h5>
                <div class="code-block">
                    <pre>[
  {
    "id": 1,
    "user_one_id": 1,
    "user_two_id": 2,
    "last_message_at": "2024-01-15T10:30:00Z",
    "unread_count": 3,
    "other_user": {
      "id": 2,
      "first_name": "Jane",
      "last_name": "Doe",
      "photo": "https://...",
      ...
    },
    "last_message": {
      "content": "Hello!",
      "created_at": "2024-01-15T10:30:00Z"
    }
  }
]</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-post">POST</span>
                    Create Conversation
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/conversations/create</div>
                <p>Create a new conversation with another user</p>
                
                <h5>Request Body:</h5>
                <table class="param-table">
                    <thead>
                        <tr>
                            <th>Parameter</th>
                            <th>Type</th>
                            <th>Required</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>user_id</td>
                            <td>integer</td>
                            <td><span class="required">Yes</span></td>
                            <td>ID of the user to start conversation with</td>
                        </tr>
                    </tbody>
                </table>

                <h5>Request Body Example:</h5>
                <div class="code-block">
                    <pre>{
  "user_id": 2
}</pre>
                </div>

                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "id": 1,
  "user_one_id": 1,
  "user_two_id": 2,
  "last_message_at": "2024-01-15T10:30:00Z",
  "created_at": "2024-01-15T10:30:00Z",
  "updated_at": "2024-01-15T10:30:00Z"
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-get">GET</span>
                    Get Messages
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/conversations/{conversation}/messages</div>
                <p>Get all messages in a conversation. Messages are automatically marked as read.</p>
                <p><strong>URL Parameters:</strong> Replace {conversation} with the conversation ID</p>
                
                <h5>Response:</h5>
                <div class="code-block">
                    <pre>[
  {
    "id": 1,
    "conversation_id": 1,
    "sender_id": 1,
    "receiver_id": 2,
    "content": "Hello!",
    "read_at": null,
    "created_at": "2024-01-15T10:30:00Z",
    "updated_at": "2024-01-15T10:30:00Z",
    "edited_at": null,
    "deleted_at": null,
    "is_deleted": false,
    "sender": {
      "id": 1,
      "first_name": "John",
      "last_name": "Doe",
      "photo": "https://...",
      ...
    },
    "reactions": []
  }
]</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-get">GET</span>
                    Get User for Conversation
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/conversations/{conversation}/user</div>
                <p>Get the other user's information in a conversation</p>
                <p><strong>URL Parameters:</strong> Replace {conversation} with the conversation ID</p>
                
                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "id": 2,
  "first_name": "Jane",
  "last_name": "Doe",
  "email": "jane@example.com",
  "photo": "https://...",
  "is_blocked": false,
  "is_blocked_by": false,
  "can_message": true,
  ...
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-post">POST</span>
                    Send Message
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/messages/send</div>
                <p>Send a message to another user. Creates a conversation if it doesn't exist.</p>
                
                <h5>Request Body:</h5>
                <table class="param-table">
                    <thead>
                        <tr>
                            <th>Parameter</th>
                            <th>Type</th>
                            <th>Required</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>content</td>
                            <td>string</td>
                            <td><span class="required">Yes</span></td>
                            <td>Message content (max 1000 characters)</td>
                        </tr>
                        <tr>
                            <td>receiver_id</td>
                            <td>integer</td>
                            <td><span class="required">Yes</span></td>
                            <td>ID of the user to send message to</td>
                        </tr>
                    </tbody>
                </table>

                <h5>Request Body Example:</h5>
                <div class="code-block">
                    <pre>{
  "content": "Hello! How are you?",
  "receiver_id": 2
}</pre>
                </div>

                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "id": 1,
  "conversation_id": 1,
  "sender_id": 1,
  "receiver_id": 2,
  "content": "Hello! How are you?",
  "created_at": "2024-01-15T10:30:00Z",
  "sender": {
    "id": 1,
    "first_name": "John",
    "last_name": "Doe",
    ...
  }
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-put">PUT</span>
                    Update Message
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/messages/{message}</div>
                <p>Update an existing message. Only the sender can update their own message.</p>
                <p><strong>URL Parameters:</strong> Replace {message} with the message ID</p>
                
                <h5>Request Body:</h5>
                <table class="param-table">
                    <thead>
                        <tr>
                            <th>Parameter</th>
                            <th>Type</th>
                            <th>Required</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>content</td>
                            <td>string</td>
                            <td><span class="required">Yes</span></td>
                            <td>Updated message content (max 1000 characters)</td>
                        </tr>
                    </tbody>
                </table>

                <h5>Request Body Example:</h5>
                <div class="code-block">
                    <pre>{
  "content": "Hello! How are you doing?"
}</pre>
                </div>

                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "success": true,
  "message": "Message updated successfully",
  "data": {
    "id": 1,
    "content": "Hello! How are you doing?",
    "edited_at": "2024-01-15T10:35:00Z",
    ...
  }
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-delete">DELETE</span>
                    Delete Message
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/messages/{message}</div>
                <p>Delete a message (soft delete). Only the sender can delete their own message.</p>
                <p><strong>URL Parameters:</strong> Replace {message} with the message ID</p>
                
                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "success": true,
  "message": "Message deleted successfully",
  "deleted_at": "2024-01-15T10:40:00Z"
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-post">POST</span>
                    Add Reaction to Message
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/messages/{message}/react</div>
                <p>Add an emoji reaction to a message</p>
                <p><strong>URL Parameters:</strong> Replace {message} with the message ID</p>
                
                <h5>Request Body:</h5>
                <table class="param-table">
                    <thead>
                        <tr>
                            <th>Parameter</th>
                            <th>Type</th>
                            <th>Required</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>emoji</td>
                            <td>string</td>
                            <td><span class="required">Yes</span></td>
                            <td>Emoji character (e.g., "👍", "❤️", "😂")</td>
                        </tr>
                    </tbody>
                </table>

                <h5>Request Body Example:</h5>
                <div class="code-block">
                    <pre>{
  "emoji": "👍"
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-delete">DELETE</span>
                    Remove Reaction from Message
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/messages/{message}/react</div>
                <p>Remove your reaction from a message</p>
                <p><strong>URL Parameters:</strong> Replace {message} with the message ID</p>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-get">GET</span>
                    Check Conversation
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/check-conversation?user_id={user_id}</div>
                <p>Check if a conversation exists with a specific user</p>
                <p><strong>Query Parameters:</strong> user_id - ID of the user to check</p>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-post">POST</span>
                    User Typing
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/typing</div>
                <p>Notify that user is typing in a conversation</p>
                
                <h5>Request Body:</h5>
                <table class="param-table">
                    <thead>
                        <tr>
                            <th>Parameter</th>
                            <th>Type</th>
                            <th>Required</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>conversation_id</td>
                            <td>integer</td>
                            <td><span class="required">Yes</span></td>
                            <td>ID of the conversation</td>
                        </tr>
                    </tbody>
                </table>

                <h5>Request Body Example:</h5>
                <div class="code-block">
                    <pre>{
  "conversation_id": 1
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-post">POST</span>
                    Block User
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/block-user</div>
                <p>Block a user from messaging you</p>
                
                <h5>Request Body:</h5>
                <table class="param-table">
                    <thead>
                        <tr>
                            <th>Parameter</th>
                            <th>Type</th>
                            <th>Required</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>user_id</td>
                            <td>integer</td>
                            <td><span class="required">Yes</span></td>
                            <td>ID of the user to block</td>
                        </tr>
                    </tbody>
                </table>

                <h5>Request Body Example:</h5>
                <div class="code-block">
                    <pre>{
  "user_id": 2
}</pre>
                </div>

                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "success": true,
  "message": "User blocked successfully"
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-post">POST</span>
                    Unblock User
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/unblock-user</div>
                <p>Unblock a previously blocked user</p>
                
                <h5>Request Body:</h5>
                <table class="param-table">
                    <thead>
                        <tr>
                            <th>Parameter</th>
                            <th>Type</th>
                            <th>Required</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>user_id</td>
                            <td>integer</td>
                            <td><span class="required">Yes</span></td>
                            <td>ID of the user to unblock</td>
                        </tr>
                    </tbody>
                </table>

                <h5>Request Body Example:</h5>
                <div class="code-block">
                    <pre>{
  "user_id": 2
}</pre>
                </div>

                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "success": true,
  "message": "User unblocked successfully"
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-post">POST</span>
                    Check Block Status
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/check-block-status</div>
                <p>Check if a user is blocked or has blocked you</p>
                
                <h5>Request Body:</h5>
                <table class="param-table">
                    <thead>
                        <tr>
                            <th>Parameter</th>
                            <th>Type</th>
                            <th>Required</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>user_id</td>
                            <td>integer</td>
                            <td><span class="required">Yes</span></td>
                            <td>ID of the user to check block status with</td>
                        </tr>
                    </tbody>
                </table>

                <h5>Request Body Example:</h5>
                <div class="code-block">
                    <pre>{
  "user_id": 2
}</pre>
                </div>

                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "is_blocked": false,
  "is_blocked_by": false,
  "can_message": true
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-get">GET</span>
                    Get Blocked Users
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/blocked-users</div>
                <p>Get list of all users you have blocked</p>
                
                <h5>Response:</h5>
                <div class="code-block">
                    <pre>[
  {
    "id": 2,
    "first_name": "Jane",
    "last_name": "Doe",
    "email": "jane@example.com",
    "photo": "https://...",
    "slug": "jane-doe",
    "user_has_photo": true,
    "user_initials": "JD"
  }
]</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-post">POST</span>
                    Report User
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/report/user</div>
                <p>Report a user for inappropriate behavior (required for Apple UGC compliance)</p>
                
                <h5>Request Body:</h5>
                <table class="param-table">
                    <thead>
                        <tr>
                            <th>Parameter</th>
                            <th>Type</th>
                            <th>Required</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>user_id</td>
                            <td>integer</td>
                            <td><span class="required">Yes</span></td>
                            <td>ID of the user to report</td>
                        </tr>
                        <tr>
                            <td>reason</td>
                            <td>string</td>
                            <td><span class="required">Yes</span></td>
                            <td>Reason: "spam", "harassment", "inappropriate_content", "fake_account", or "other"</td>
                        </tr>
                        <tr>
                            <td>description</td>
                            <td>string</td>
                            <td>No</td>
                            <td>Additional details about the report (max 1000 characters)</td>
                        </tr>
                    </tbody>
                </table>

                <h5>Request Body Example:</h5>
                <div class="code-block">
                    <pre>{
  "user_id": 123,
  "reason": "harassment",
  "description": "User sent inappropriate messages"
}</pre>
                </div>

                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "success": true,
  "message": "User reported successfully. Our team will review this report.",
  "report": {
    "id": 1,
    "reason": "harassment",
    "status": "pending",
    "created_at": "2024-01-15T14:30:52.000000Z"
  }
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-post">POST</span>
                    Report Post
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/report/post</div>
                <p>Report a post for inappropriate content (required for Apple UGC compliance)</p>
                
                <h5>Request Body:</h5>
                <table class="param-table">
                    <thead>
                        <tr>
                            <th>Parameter</th>
                            <th>Type</th>
                            <th>Required</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>post_id</td>
                            <td>integer</td>
                            <td><span class="required">Yes</span></td>
                            <td>ID of the post to report</td>
                        </tr>
                        <tr>
                            <td>reason</td>
                            <td>string</td>
                            <td><span class="required">Yes</span></td>
                            <td>Reason: "spam", "harassment", "inappropriate_content", "violence", "hate_speech", or "other"</td>
                        </tr>
                        <tr>
                            <td>description</td>
                            <td>string</td>
                            <td>No</td>
                            <td>Additional details about the report (max 1000 characters)</td>
                        </tr>
                    </tbody>
                </table>

                <h5>Request Body Example:</h5>
                <div class="code-block">
                    <pre>{
  "post_id": 456,
  "reason": "inappropriate_content",
  "description": "Post contains offensive material"
}</pre>
                </div>

                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "success": true,
  "message": "Post reported successfully. Our team will review this report.",
  "report": {
    "id": 2,
    "reason": "inappropriate_content",
    "status": "pending",
    "created_at": "2024-01-15T14:30:52.000000Z"
  }
}</pre>
                </div>
            </div>
        </div>

        <!-- News Feed Routes -->
        <div id="news-feed" class="api-section">
            <h2>📰 News Feed</h2>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-get">GET</span>
                    Get Feed Posts
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/feed/posts?per_page=15&sort=latest</div>
                <p>Get paginated feed posts</p>
                <p><strong>Query Parameters:</strong></p>
                <ul>
                    <li>per_page - Number of posts per page (default: 15, min: 5, max: 50)</li>
                    <li>sort - Sort order: "latest" (default), "popular", or "oldest"</li>
                </ul>
                
                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "success": true,
  "data": [
    {
      "id": 1,
      "slug": "post-title-15-01-2024-143052",
      "content": "Post content...",
      "visibility": "public",
      "created_at": "2024-01-15T14:30:52.000000Z",
      "updated_at": "2024-01-15T14:30:52.000000Z",
      "likes_count": 10,
      "comments_count": 5,
      "shares_count": 2,
      "comments_enabled": true,
      "user": {
        "id": 1,
        "name": "John Doe",
        "first_name": "John",
        "last_name": "Doe",
        "position": "CEO",
        "avatar": "https://...",
        "initials": "JD",
        "has_photo": true,
        "slug": "john-doe"
      },
      "media": [
        {
          "id": 1,
          "media_type": "image",
          "media_url": "https://...",
          "thumbnail_url": "https://...",
          "mime_type": "image/jpeg",
          "file_name": "photo.jpg",
          "duration": null
        }
      ],
      "reactions": [
        {
          "type": "appreciate",
          "count": 5
        },
        {
          "type": "support",
          "count": 3
        },
        {
          "type": "cheers",
          "count": 1
        },
        {
          "type": "support",
          "count": 0
        },
        {
          "type": "insight",
          "count": 1
        }
      ],
      "user_reaction": {
        "type": "appreciate",
        "created_at": "2024-01-15T14:35:00.000000Z"
      },
      // Comments removed from feed response - fetch separately via GET /feed/posts/{postId}/comments
          "user": {
            "id": 2,
            "name": "Jane Smith",
            "avatar": "https://...",
            "initials": "JS",
            "has_photo": true
          },
          "replies": []
        }
      ]
    }
  ],
  "current_page": 1,
  "last_page": 10,
  "per_page": 15,
  "total": 150,
  "has_more": true
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-get">GET</span>
                    Get Post by Slug
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/feed/posts/{slug}</div>
                <p>Get a single post with details. Comments are not included - fetch separately via GET /feed/posts/{postId}/comments</p>
                <p><strong>URL Parameters:</strong> Replace {slug} with the post slug</p>
                
                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "success": true,
  "data": {
    "id": 1,
    "slug": "post-title-15-01-2024-143052",
    "content": "Post content...",
    "visibility": "public",
    "created_at": "2024-01-15T14:30:52.000000Z",
    "updated_at": "2024-01-15T14:30:52.000000Z",
    "likes_count": 10,
    "comments_count": 5,
    "shares_count": 2,
    "comments_enabled": true,
    "user": {
      "id": 1,
      "name": "John Doe",
      "first_name": "John",
      "last_name": "Doe",
      "position": "CEO",
      "avatar": "https://...",
      "initials": "JD",
      "has_photo": true,
      "slug": "john-doe"
    },
    "media": [
      {
        "id": 1,
        "media_type": "video",
        "media_url": "https://...",
        "thumbnail_url": "https://...",
        "mime_type": "video/mp4",
        "file_name": "video.mp4",
        "duration": 120
      }
    ],
    "reactions": [
      {
        "type": "appreciate",
        "count": 5
      },
      {
        "type": "support",
        "count": 3
      },
      {
        "type": "cheers",
        "count": 1
      },
      {
        "type": "support",
        "count": 0
      },
      {
        "type": "insight",
        "count": 1
      }
    ],
    "user_reaction": {
      "type": "appreciate",
      "created_at": "2024-01-15T14:35:00.000000Z"
    },
    // Comments removed - fetch via GET /feed/posts/{postId}/comments
    "original_post": {
      "id": 10,
      "slug": "original-post-slug",
      "content": "Original post content",
      "created_at": "2024-01-10T10:00:00.000000Z",
      "user": {...},
      "media": [...]
    }
  },
  "user_reaction": {
    "id": 1,
    "reaction_type": "appreciate",
    ...
  }
}</pre>
                </div>
                <p><strong>Note:</strong> Returns 403 if post visibility is 'private' and user is not the owner.</p>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-post">POST</span>
                    Create Post
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/feed/posts</div>
                <p>Create a new post with optional media</p>
                
                <h5>Request Body:</h5>
                <table class="param-table">
                    <thead>
                        <tr>
                            <th>Parameter</th>
                            <th>Type</th>
                            <th>Required</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>content</td>
                            <td>string</td>
                            <td>No</td>
                            <td>Post content (max 10000 characters)</td>
                        </tr>
                        <tr>
                            <td>media</td>
                            <td>array</td>
                            <td>No</td>
                            <td>Array of media files (max 10 files, 10MB each). Supported: jpeg, jpg, png, gif, webp, mp4, mov, avi, mkv, webm. Files must be uploaded as multipart/form-data</td>
                        </tr>
                        <tr>
                            <td>comments_enabled</td>
                            <td>boolean</td>
                            <td>No</td>
                            <td>Enable/disable comments (default: true)</td>
                        </tr>
                        <tr>
                            <td>visibility</td>
                            <td>string</td>
                            <td>No</td>
                            <td>Post visibility: "public" (default), "private", or "connections"</td>
                        </tr>
                    </tbody>
                </table>

                <h5>Request Body Example:</h5>
                <div class="code-block">
                    <pre>{
  "content": "This is my post content!",
  "comments_enabled": true,
  "visibility": "public"
}</pre>
                </div>

                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "success": true,
  "message": "Post created successfully!",
  "data": {
    "id": 1,
    "slug": "post-title-15-01-2024-143052",
    "content": "This is my post content!",
    "visibility": "public",
    "likes_count": 0,
    "comments_count": 0,
    "shares_count": 0,
    "comments_enabled": true,
    "user": {
      "id": 1,
      "name": "John Doe",
      "first_name": "John",
      "last_name": "Doe",
      "position": "CEO",
      "avatar": "https://...",
      "initials": "JD",
      "has_photo": true,
      "slug": "john-doe"
    },
    "media": [...],
    "user_reaction": null,
    "comments": []
  }
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-put">PUT</span>
                    Update Post
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/feed/posts/{slug}</div>
                <p>Update an existing post. Only the post owner can update.</p>
                <p><strong>URL Parameters:</strong> Replace {slug} with the post slug</p>
                
                <h5>Request Body:</h5>
                <table class="param-table">
                    <thead>
                        <tr>
                            <th>Parameter</th>
                            <th>Type</th>
                            <th>Required</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>content</td>
                            <td>string</td>
                            <td>No</td>
                            <td>Updated post content (max 10000 characters)</td>
                        </tr>
                        <tr>
                            <td>comments_enabled</td>
                            <td>boolean</td>
                            <td>No</td>
                            <td>Enable/disable comments</td>
                        </tr>
                        <tr>
                            <td>visibility</td>
                            <td>string</td>
                            <td>No</td>
                            <td>Post visibility: "public", "private", or "connections"</td>
                        </tr>
                        <tr>
                            <td>media</td>
                            <td>array</td>
                            <td>No</td>
                            <td>Array of media files to add (max 10 files, 10MB each). Supported: jpeg, jpg, png, gif, webp, mp4, mov, avi, mkv, webm. Files must be uploaded as multipart/form-data</td>
                        </tr>
                        <tr>
                            <td>remove_media_ids</td>
                            <td>array</td>
                            <td>No</td>
                            <td>Array of media IDs to remove from the post</td>
                        </tr>
                    </tbody>
                </table>

                <h5>Request Body Example:</h5>
                <div class="code-block">
                    <pre>{
  "content": "Updated post content!",
  "comments_enabled": true,
  "visibility": "public"
}</pre>
                </div>

                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "success": true,
  "message": "Post updated successfully!",
  "data": {
    "id": 1,
    "slug": "post-title-15-01-2024-143052",
    "content": "Updated post content!",
    "visibility": "public",
    "likes_count": 10,
    "comments_count": 5,
    "shares_count": 2,
    "comments_enabled": true,
    "user": {...},
    "media": [...],
    "user_reaction": {...},
    "comments": [...]
  }
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-delete">DELETE</span>
                    Delete Post
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/feed/posts/{slug}</div>
                <p>Delete a post (soft delete). Only the post owner can delete.</p>
                <p><strong>URL Parameters:</strong> Replace {slug} with the post slug</p>
                
                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "success": true,
  "message": "Post deleted successfully!"
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-post">POST</span>
                    Add Reaction
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/feed/reactions</div>
                <p>Add or update a reaction to a post or comment. Sending the same reaction type removes it.</p>
                
                <h5>Request Body:</h5>
                <table class="param-table">
                    <thead>
                        <tr>
                            <th>Parameter</th>
                            <th>Type</th>
                            <th>Required</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>reactionable_type</td>
                            <td>string</td>
                            <td><span class="required">Yes</span></td>
                            <td>Type: "Post" or "PostComment" (also accepts full namespace: "App\Models\Feed\Post" or "App\Models\Feed\PostComment")</td>
                        </tr>
                        <tr>
                            <td>reactionable_id</td>
                            <td>integer</td>
                            <td><span class="required">Yes</span></td>
                            <td>ID of the post or comment</td>
                        </tr>
                        <tr>
                            <td>reaction_type</td>
                            <td>string</td>
                            <td><span class="required">Yes</span></td>
                            <td>Reaction type: "appreciate", "cheers", "support", "insight", "curious", or "smile"</td>
                        </tr>
                    </tbody>
                </table>

                <h5>Request Body Example:</h5>
                <div class="code-block">
                    <pre>{
  "reactionable_type": "Post",
  "reactionable_id": 1,
  "reaction_type": "cheers"
}</pre>
                </div>

                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "success": true,
  "message": "Reaction added",
  "reaction": {
    "id": 1,
    "reaction_type": "appreciate",
    ...
  }
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-delete">DELETE</span>
                    Remove Reaction
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/feed/reactions</div>
                <p>Remove a reaction from a post or comment</p>
                
                <h5>Request Body:</h5>
                <table class="param-table">
                    <thead>
                        <tr>
                            <th>Parameter</th>
                            <th>Type</th>
                            <th>Required</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>reactionable_type</td>
                            <td>string</td>
                            <td><span class="required">Yes</span></td>
                            <td>Type: "Post" or "PostComment" (also accepts full namespace: "App\Models\Feed\Post" or "App\Models\Feed\PostComment")</td>
                        </tr>
                        <tr>
                            <td>reactionable_id</td>
                            <td>integer</td>
                            <td><span class="required">Yes</span></td>
                            <td>ID of the post or comment</td>
                        </tr>
                    </tbody>
                </table>

                <h5>Request Body Example:</h5>
                <div class="code-block">
                    <pre>{
  "reactionable_type": "Post",
  "reactionable_id": 1
}</pre>
                </div>

                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "success": true,
  "message": "Reaction removed"
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-get">GET</span>
                    Get Reactions List
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/feed/posts/{postId}/reactions-list</div>
                <p>Get list of all reactions for a post with user details</p>
                <p><strong>URL Parameters:</strong> Replace {postId} with the post ID</p>
                
                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "success": true,
  "count": 10,
  "reactions": [
    {
      "id": 1,
      "type": "appreciate",
      "created_at": "2024-01-15T10:30:00.000000Z",
      "user": {
        "id": 1,
        "name": "John Doe",
        "avatar": "https://...",
        "initials": "JD",
        "has_photo": true,
        "position": "CEO"
      }
    }
  ]
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-get">GET</span>
                    Get Reactions Count
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/feed/posts/{postId}/reactions-count</div>
                <p>Get reaction count and details for a post</p>
                <p><strong>URL Parameters:</strong> Replace {postId} with the post ID</p>
                
                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "success": true,
  "count": 10,
  "reactions": [
    {
      "type": "appreciate",
      "user_id": 1,
      "user_name": "John Doe"
    }
  ]
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-get">GET</span>
                    Get Shares List
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/feed/posts/{postId}/shares-list</div>
                <p>Get list of all shares for a post with user details</p>
                <p><strong>URL Parameters:</strong> Replace {postId} with the post ID</p>
                
                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "success": true,
  "count": 5,
  "shares": [
    {
      "id": 1,
      "share_type": "repost",
      "shared_content": "Check this out!",
      "created_at": "2024-01-15T10:30:00.000000Z",
      "user": {
        "id": 1,
        "name": "John Doe",
        "avatar": "https://...",
        "initials": "JD",
        "has_photo": true,
        "position": "CEO"
      }
    }
  ]
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-get">GET</span>
                    Get Comments Count
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/feed/posts/{postId}/comments-count</div>
                <p>Get comment count for a post (active comments only)</p>
                <p><strong>URL Parameters:</strong> Replace {postId} with the post ID</p>
                
                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "success": true,
  "count": 25
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-post">POST</span>
                    Add Comment
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/feed/posts/{postId}/comments</div>
                <p>Add a comment to a post. Can also add a reply by including parent_id.</p>
                <p><strong>URL Parameters:</strong> Replace {postId} with the post ID</p>
                
                <h5>Request Body:</h5>
                <table class="param-table">
                    <thead>
                        <tr>
                            <th>Parameter</th>
                            <th>Type</th>
                            <th>Required</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>content</td>
                            <td>string</td>
                            <td><span class="required">Yes</span></td>
                            <td>Comment content (max 5000 characters)</td>
                        </tr>
                        <tr>
                            <td>parent_id</td>
                            <td>integer</td>
                            <td>No</td>
                            <td>Parent comment ID for replies</td>
                        </tr>
                    </tbody>
                </table>

                <h5>Request Body Example:</h5>
                <div class="code-block">
                    <pre>{
  "content": "Great post!",
  "parent_id": null
}</pre>
                </div>

                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "success": true,
  "message": "Comment added successfully!",
  "data": {
    "id": 1,
    "content": "Great post!",
    "created_at": "2024-01-15T14:40:00.000000Z",
    "parent_id": null,
    "user_has_reacted": false,
    "user": {
      "id": 2,
      "name": "Jane Smith",
      "avatar": "https://...",
      "initials": "JS",
      "has_photo": true
    },
    "replies": []
  }
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-put">PUT</span>
                    Update Comment
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/feed/comments/{commentId}</div>
                <p>Update a comment. Only the comment owner can update.</p>
                <p><strong>URL Parameters:</strong> Replace {commentId} with the comment ID</p>
                
                <h5>Request Body:</h5>
                <table class="param-table">
                    <thead>
                        <tr>
                            <th>Parameter</th>
                            <th>Type</th>
                            <th>Required</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>content</td>
                            <td>string</td>
                            <td><span class="required">Yes</span></td>
                            <td>Updated comment content (max 5000 characters)</td>
                        </tr>
                    </tbody>
                </table>

                <h5>Request Body Example:</h5>
                <div class="code-block">
                    <pre>{
  "content": "Updated comment text"
}</pre>
                </div>

                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "success": true,
  "message": "Comment updated successfully!",
  "data": {
    "id": 1,
    "content": "Updated comment text",
    "created_at": "2024-01-15T14:40:00.000000Z",
    "user": {
      "id": 2,
      "name": "Jane Smith",
      "avatar": "https://...",
      "initials": "JS",
      "has_photo": true
    }
  }
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-delete">DELETE</span>
                    Delete Comment
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/feed/comments/{commentId}</div>
                <p>Delete a comment (soft delete). Only the comment owner can delete.</p>
                <p><strong>URL Parameters:</strong> Replace {commentId} with the comment ID</p>
                
                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "success": true,
  "message": "Comment deleted successfully!"
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-get">GET</span>
                    Get Comments
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/feed/posts/{postId}/comments?per_page=20</div>
                <p>Get all comments for a post (paginated)</p>
                <p><strong>URL Parameters:</strong> Replace {postId} with the post ID</p>
                <p><strong>Query Parameters:</strong> per_page - Number of comments per page (default: 20)</p>
                
                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "success": true,
  "data": {
    "data": [
      {
        "id": 1,
        "content": "Comment text",
        "created_at": "2024-01-15T14:40:00.000000Z",
        "user_has_reacted": false,
        "user": {
          "id": 2,
          "name": "Jane Smith",
          "avatar": "https://...",
          "initials": "JS",
          "has_photo": true
        },
        "replies": [
          {
            "id": 2,
            "content": "Reply text",
            "created_at": "2024-01-15T14:45:00.000000Z",
            "user": {
              "id": 3,
              "name": "Bob Wilson",
              "avatar": "https://...",
              "initials": "BW",
              "has_photo": true
            }
          }
        ]
      }
    ],
    "current_page": 1,
    "last_page": 5,
    "per_page": 20,
    "total": 100
  }
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-post">POST</span>
                    Share Post
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/feed/posts/{postId}/share</div>
                <p>Share or repost a post</p>
                <p><strong>URL Parameters:</strong> Replace {postId} with the post ID</p>
                
                <h5>Request Body:</h5>
                <table class="param-table">
                    <thead>
                        <tr>
                            <th>Parameter</th>
                            <th>Type</th>
                            <th>Required</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>shared_content</td>
                            <td>string</td>
                            <td>No</td>
                            <td>Additional content when sharing (max 10000 characters)</td>
                        </tr>
                        <tr>
                            <td>share_type</td>
                            <td>string</td>
                            <td>No</td>
                            <td>Type: "share" or "repost" (default: "share")</td>
                        </tr>
                    </tbody>
                </table>

                <h5>Request Body Example:</h5>
                <div class="code-block">
                    <pre>{
  "shared_content": "Check this out!",
  "share_type": "repost"
}</pre>
                </div>

                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "success": true,
  "message": "Post shared successfully!",
  "data": {
    "id": 1,
    "post_id": 10,
    "user_id": 1,
    "shared_post_id": 15,
    "shared_content": "Check this out!",
    "share_type": "repost",
    "created_at": "2024-01-15T14:50:00.000000Z",
    "updated_at": "2024-01-15T14:50:00.000000Z",
    "user": {
      "id": 1,
      "first_name": "John",
      "last_name": "Doe",
      "slug": "john-doe",
      "photo": "https://..."
    },
    "post": {
      "id": 10,
      "user": {
        "id": 2,
        "first_name": "Jane",
        "last_name": "Smith",
        "slug": "jane-smith",
        "photo": "https://..."
      }
    }
  }
}</pre>
                </div>
                <p><strong>Note:</strong> When share_type is "repost", a new post is created that references the original post via original_post_id. The shared_post_id field contains the ID of the newly created post. The response returns the PostShare record with related user and post data.</p>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-get">GET</span>
                    Get User Posts
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/feed/user/{userId?}/posts?per_page=15</div>
                <p>Get all posts for a specific user. If userId is not provided, returns current user's posts.</p>
                <p><strong>URL Parameters:</strong> {userId} is optional - if omitted, returns authenticated user's posts</p>
                <p><strong>Query Parameters:</strong> per_page - Number of posts per page (default: 15)</p>
                
                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "success": true,
  "data": [
    {
      "id": 1,
      "slug": "post-title-15-01-2024-143052",
      "content": "Post content...",
      "visibility": "public",
      "created_at": "2024-01-15T14:30:52.000000Z",
      "updated_at": "2024-01-15T14:30:52.000000Z",
      "likes_count": 10,
      "comments_count": 5,
      "shares_count": 2,
      "comments_enabled": true,
      "user": {
        "id": 1,
        "name": "John Doe",
        "first_name": "John",
        "last_name": "Doe",
        "position": "CEO",
        "avatar": "https://...",
        "initials": "JD",
        "has_photo": true,
        "slug": "john-doe"
      },
      "media": [...],
      "user_reaction": {
        "type": "appreciate",
        "created_at": "2024-01-15T14:35:00.000000Z"
      },
      "comments": [...]
    }
  ],
  "current_page": 1,
  "last_page": 5,
  "has_more": true
}</pre>
                </div>
            </div>

        </div>

        <!-- User Profile Routes -->
        <div id="user-profile" class="api-section">
            <h2>👤 User Profile</h2>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-post">POST</span>
                    Update Personal Details
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/user/update/personal</div>
                <p>Update user's personal information</p>
                
                <h5>Request Body:</h5>
                <table class="param-table">
                    <thead>
                        <tr>
                            <th>Parameter</th>
                            <th>Type</th>
                            <th>Required</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>first_name</td>
                            <td>string</td>
                            <td><span class="required">Yes</span></td>
                            <td>User's first name</td>
                        </tr>
                        <tr>
                            <td>last_name</td>
                            <td>string</td>
                            <td><span class="required">Yes</span></td>
                            <td>User's last name</td>
                        </tr>
                        <tr>
                            <td>email</td>
                            <td>email</td>
                            <td><span class="required">Yes</span></td>
                            <td>User's email (must be unique)</td>
                        </tr>
                        <tr>
                            <td>phone</td>
                            <td>string</td>
                            <td>No</td>
                            <td>User's phone number</td>
                        </tr>
                        <tr>
                            <td>linkedin_url</td>
                            <td>string</td>
                            <td>No</td>
                            <td>LinkedIn profile URL</td>
                        </tr>
                        <tr>
                            <td>country</td>
                            <td>string</td>
                            <td>No</td>
                            <td>Country</td>
                        </tr>
                        <tr>
                            <td>city</td>
                            <td>string</td>
                            <td>No</td>
                            <td>City</td>
                        </tr>
                        <tr>
                            <td>zip_code</td>
                            <td>string</td>
                            <td>No</td>
                            <td>ZIP/Postal code</td>
                        </tr>
                        <tr>
                            <td>photo</td>
                            <td>file</td>
                            <td>No</td>
                            <td>Profile photo (image, max 2MB)</td>
                        </tr>
                    </tbody>
                </table>

                <h5>Request Body Example:</h5>
                <div class="code-block">
                    <pre>{
  "first_name": "John",
  "last_name": "Doe",
  "email": "john@example.com",
  "phone": "+1234567890",
  "linkedin_url": "https://linkedin.com/in/johndoe",
  "country": "United States",
  "city": "New York",
  "zip_code": "10001"
}</pre>
                </div>

                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "status": true,
  "message": "User personal details updated successfully!",
  "user": {
    "id": 1,
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@example.com",
    ...
  }
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-post">POST</span>
                    Update Professional Details
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/user/update/professional</div>
                <p>Update user's professional and company information</p>
                
                <h5>Request Body:</h5>
                <table class="param-table">
                    <thead>
                        <tr>
                            <th>Parameter</th>
                            <th>Type</th>
                            <th>Required</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>company_name</td>
                            <td>string</td>
                            <td>No</td>
                            <td>Company name</td>
                        </tr>
                        <tr>
                            <td>company_position</td>
                            <td>string</td>
                            <td>No</td>
                            <td>Position in company</td>
                        </tr>
                        <tr>
                            <td>company_industry</td>
                            <td>string</td>
                            <td>No</td>
                            <td>Company industry</td>
                        </tr>
                        <tr>
                            <td>company_business_type</td>
                            <td>string</td>
                            <td>No</td>
                            <td>Business type</td>
                        </tr>
                        <tr>
                            <td>company_website</td>
                            <td>string</td>
                            <td>No</td>
                            <td>Company website URL</td>
                        </tr>
                        <tr>
                            <td>company_logo</td>
                            <td>file</td>
                            <td>No</td>
                            <td>Company logo (image, max 2MB)</td>
                        </tr>
                    </tbody>
                </table>

                <h5>Request Body Example:</h5>
                <div class="code-block">
                    <pre>{
  "company_name": "Tech Corp",
  "company_position": "Software Engineer",
  "company_industry": "Technology",
  "company_business_type": "B2B",
  "company_website": "https://techcorp.com"
}</pre>
                </div>

                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "status": true,
  "message": "Professional details updated successfully!",
  "user": { ... },
  "company": { ... }
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-get">GET</span>
                    Get User Profile by Slug
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/user/profile/{slug}</div>
                <p>Get user profile information by slug. This endpoint includes subscription information for mobile app developers to check subscription status.</p>
                <p><strong>URL Parameters:</strong> Replace {slug} with the user's slug (e.g., "john-doe")</p>
                
                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "status": true,
  "message": "User profile fetched successfully.",
  "user": {
    "id": 1,
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@example.com",
    "photo": "https://...",
    "slug": "john-doe",
    "company": {
      "company_name": "Tech Corp",
      "company_logo": "https://...",
      ...
    },
    "products": [...],
    "services": [...],
    "user_educations": [...],
    "user_icp": {...},
    ...
  },
  "profile_views_count": 42,
  "subscription": {
    "id": 630,
    "subscription_type": "Free",
    "status": "active",
    "renewal_date": "2026-04-27",
    "expires_at": "2026-04-27",
    "platform": "Admin",
    "start_date": "2026-01-27"
  },
  "has_subscription": true
}</pre>
                </div>
                <p><strong>Subscription Fields:</strong></p>
                <ul>
                    <li><code>subscription</code> - Object containing active subscription details (or <code>null</code> if no active subscription)</li>
                    <li><code>has_subscription</code> - Boolean indicating if user has an active subscription (<code>true</code> if status is 'active', <code>false</code> otherwise)</li>
                </ul>
                <p><strong>Note:</strong> Subscription validation only checks if <code>status = 'active'</code>. No date validations are performed.</p>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-delete">DELETE</span>
                    Delete User
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/user/delete</div>
                <p>Delete the authenticated user's account</p>
                
                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "status": true,
  "message": "User account deleted successfully"
}</pre>
                </div>
            </div>
        </div>

        <!-- Products Routes -->
        <div id="products" class="api-section">
            <h2>📦 Products</h2>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-get">GET</span>
                    Get User Products
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/user/products</div>
                <p>Get all products for the authenticated user</p>
                
                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Product Name",
      "category": "Category",
      "short_description": "Description",
      "original_price": 99.99,
      "quantity": 10,
      "unit_of_quantity": "pieces",
      "product_image": "https://...",
      ...
    }
  ]
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-get">GET</span>
                    Get Product by ID
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/user/products/{id}</div>
                <p>Get a specific product by ID</p>
                <p><strong>URL Parameters:</strong> Replace {id} with the product ID</p>
                
                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "success": true,
  "data": {
    "id": 1,
    "title": "Product Name",
    "category": "Category",
    "short_description": "Description",
    "original_price": 99.99,
    "quantity": 10,
    "unit_of_quantity": "pieces",
    "product_image": "https://...",
    ...
  }
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-post">POST</span>
                    Create/Update Product
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/user/products/store</div>
                <p>Create a new product. To update, include {id} in URL: /user/products/store/{id}</p>
                
                <h5>Request Body:</h5>
                <table class="param-table">
                    <thead>
                        <tr>
                            <th>Parameter</th>
                            <th>Type</th>
                            <th>Required</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>title</td>
                            <td>string</td>
                            <td><span class="required">Yes</span></td>
                            <td>Product title (max 255 characters)</td>
                        </tr>
                        <tr>
                            <td>category</td>
                            <td>string</td>
                            <td>No</td>
                            <td>Product category</td>
                        </tr>
                        <tr>
                            <td>short_description</td>
                            <td>string</td>
                            <td>No</td>
                            <td>Product description</td>
                        </tr>
                        <tr>
                            <td>original_price</td>
                            <td>numeric</td>
                            <td><span class="required">Yes</span></td>
                            <td>Product price (min 0)</td>
                        </tr>
                        <tr>
                            <td>quantity</td>
                            <td>integer</td>
                            <td><span class="required">Yes</span></td>
                            <td>Available quantity (min 1)</td>
                        </tr>
                        <tr>
                            <td>unit_of_quantity</td>
                            <td>string</td>
                            <td><span class="required">Yes</span></td>
                            <td>Unit of quantity (max 50 characters)</td>
                        </tr>
                        <tr>
                            <td>product_image</td>
                            <td>file</td>
                            <td>No</td>
                            <td>Product image (jpg, jpeg, png, gif, webp)</td>
                        </tr>
                    </tbody>
                </table>

                <h5>Request Body Example:</h5>
                <div class="code-block">
                    <pre>{
  "title": "Premium Widget",
  "category": "Electronics",
  "short_description": "High-quality widget for all your needs",
  "original_price": 99.99,
  "quantity": 50,
  "unit_of_quantity": "pieces"
}</pre>
                </div>

                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "success": true,
  "message": "Product created successfully",
  "data": {
    "id": 1,
    "title": "Premium Widget",
    ...
  }
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-delete">DELETE</span>
                    Delete Product
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/user/products/delete/{id}</div>
                <p>Delete a product</p>
                <p><strong>URL Parameters:</strong> Replace {id} with the product ID</p>
                
                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "success": true,
  "message": "Product deleted successfully"
}</pre>
                </div>
            </div>
        </div>

        <!-- Services Routes -->
        <div id="services" class="api-section">
            <h2>🛠️ Services</h2>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-get">GET</span>
                    Get User Services
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/user/services</div>
                <p>Get all services for the authenticated user</p>
                
                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Service Name",
      "category": "Category",
      "short_description": "Description",
      "original_price": 199.99,
      "duration": "Monthly",
      "service_image": "https://...",
      ...
    }
  ]
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-get">GET</span>
                    Get Service by ID
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/user/services/{id}</div>
                <p>Get a specific service by ID</p>
                <p><strong>URL Parameters:</strong> Replace {id} with the service ID</p>
                
                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "success": true,
  "data": {
    "id": 1,
    "title": "Service Name",
    "category": "Category",
    "short_description": "Description",
    "original_price": 199.99,
    "duration": "Monthly",
    "service_image": "https://...",
    ...
  }
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-post">POST</span>
                    Create/Update Service
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/user/services/store</div>
                <p>Create a new service. To update, include {id} in URL: /user/services/store/{id}</p>
                
                <h5>Request Body:</h5>
                <table class="param-table">
                    <thead>
                        <tr>
                            <th>Parameter</th>
                            <th>Type</th>
                            <th>Required</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>title</td>
                            <td>string</td>
                            <td><span class="required">Yes</span></td>
                            <td>Service title (max 255 characters)</td>
                        </tr>
                        <tr>
                            <td>category</td>
                            <td>string</td>
                            <td>No</td>
                            <td>Service category</td>
                        </tr>
                        <tr>
                            <td>short_description</td>
                            <td>string</td>
                            <td>No</td>
                            <td>Service description</td>
                        </tr>
                        <tr>
                            <td>original_price</td>
                            <td>numeric</td>
                            <td><span class="required">Yes</span></td>
                            <td>Service price (min 0)</td>
                        </tr>
                        <tr>
                            <td>duration</td>
                            <td>string</td>
                            <td><span class="required">Yes</span></td>
                            <td>Service duration: "Starting", "One time", "Monthly", "Yearly", or "Quarterly"</td>
                        </tr>
                        <tr>
                            <td>service_image</td>
                            <td>file</td>
                            <td>No</td>
                            <td>Service image (jpg, jpeg, png, gif, webp)</td>
                        </tr>
                    </tbody>
                </table>

                <h5>Request Body Example:</h5>
                <div class="code-block">
                    <pre>{
  "title": "Consulting Service",
  "category": "Business",
  "short_description": "Professional consulting services",
  "original_price": 199.99,
  "duration": "Monthly"
}</pre>
                </div>

                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "success": true,
  "message": "Service created successfully",
  "data": {
    "id": 1,
    "title": "Consulting Service",
    ...
  }
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-delete">DELETE</span>
                    Delete Service
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/user/services/delete/{id}</div>
                <p>Delete a service</p>
                <p><strong>URL Parameters:</strong> Replace {id} with the service ID</p>
                
                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "success": true,
  "message": "Service deleted successfully"
}</pre>
                </div>
            </div>
        </div>

        <!-- Qualifications/Education Routes -->
        <div id="qualifications" class="api-section">
            <h2>🎓 Qualifications/Education</h2>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-get">GET</span>
                    Get User Qualifications
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/user/qualifications</div>
                <p>Get all qualifications/education for the authenticated user</p>
                
                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "success": true,
  "data": [
    {
      "id": 1,
      "college_university": "University Name",
      "degree_diploma": "Bachelor's Degree",
      "year": 2020,
      ...
    }
  ]
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-get">GET</span>
                    Get Qualification by ID
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/user/qualifications/{id}</div>
                <p>Get a specific qualification by ID</p>
                <p><strong>URL Parameters:</strong> Replace {id} with the qualification ID</p>
                
                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "success": true,
  "data": {
    "id": 1,
    "college_university": "University Name",
    "degree_diploma": "Bachelor's Degree",
    "year": 2020,
    ...
  }
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-post">POST</span>
                    Create/Update Qualification
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/user/qualifications/store</div>
                <p>Create a new qualification. To update, include {id} in URL: /user/qualifications/store/{id}</p>
                
                <h5>Request Body:</h5>
                <table class="param-table">
                    <thead>
                        <tr>
                            <th>Parameter</th>
                            <th>Type</th>
                            <th>Required</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>college_name</td>
                            <td>string</td>
                            <td><span class="required">Yes</span></td>
                            <td>College/University name (max 255 characters)</td>
                        </tr>
                        <tr>
                            <td>degree</td>
                            <td>string</td>
                            <td><span class="required">Yes</span></td>
                            <td>Degree/Diploma name (max 255 characters)</td>
                        </tr>
                        <tr>
                            <td>year_graduated</td>
                            <td>integer</td>
                            <td><span class="required">Yes</span></td>
                            <td>Year of graduation (1900 to current year)</td>
                        </tr>
                    </tbody>
                </table>

                <h5>Request Body Example:</h5>
                <div class="code-block">
                    <pre>{
  "college_name": "Harvard University",
  "degree": "Bachelor of Science",
  "year_graduated": 2020
}</pre>
                </div>

                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "success": true,
  "message": "Education added successfully.",
  "data": {
    "id": 1,
    "college_university": "Harvard University",
    "degree_diploma": "Bachelor of Science",
    "year": 2020,
    ...
  }
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-delete">DELETE</span>
                    Delete Qualification
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/user/qualifications/delete/{id}</div>
                <p>Delete a qualification</p>
                <p><strong>URL Parameters:</strong> Replace {id} with the qualification ID</p>
                
                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "success": true,
  "message": "Education deleted successfully"
}</pre>
                </div>
            </div>
        </div>

        <!-- Discovery & Search Routes -->
        <div id="discovery-search" class="api-section">
            <h2>🔍 Discovery & Search</h2>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-get">GET</span>
                    Get Our Community
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/our-community</div>
                <p>Get community overview including industries, products, and services. Returns one product per user and top 3 services.</p>
                
                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "status": true,
  "industries": [
    {
      "icon": "fas fa-laptop-code",
      "name": "Technology"
    },
    {
      "icon": "fas fa-heartbeat",
      "name": "Healthcare"
    },
    ...
  ],
  "products": [
    {
      "id": 1,
      "title": "Product Name",
      "category": "Category",
      "original_price": 99.99,
      "product_image": "https://...",
      "user": {
        "id": 1,
        "first_name": "John",
        "last_name": "Doe",
        "photo": "https://...",
        ...
      },
      "user_has_photo": true,
      "user_initials": "JD",
      ...
    }
  ],
  "services": [
    {
      "id": 1,
      "title": "Service Name",
      "category": "Category",
      "original_price": 199.99,
      "duration": "Monthly",
      "service_image": "https://...",
      "user": {
        "id": 1,
        "first_name": "John",
        "last_name": "Doe",
        "photo": "https://...",
        ...
      },
      "user_has_photo": true,
      "user_initials": "JD",
      ...
    }
  ]
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-get">GET</span>
                    Get Services
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/services?page=1</div>
                <p>Get all services from the community (paginated). Only returns services from users with complete profiles.</p>
                <p><strong>Query Parameters:</strong></p>
                <ul>
                    <li>page - Page number (default: 1)</li>
                </ul>
                
                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "status": true,
  "services": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "title": "Consulting Service",
        "category": "Business",
        "short_description": "Professional consulting",
        "original_price": 199.99,
        "duration": "Monthly",
        "service_image": "https://...",
        "user": {
          "id": 1,
          "first_name": "John",
          "last_name": "Doe",
          "slug": "john-doe",
          ...
        },
        ...
      }
    ],
    "per_page": 10,
    "total": 75,
    "last_page": 8
  }
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-get">GET</span>
                    Get Products
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/products?page=1</div>
                <p>Get all products from the community (paginated). Only returns products from users with complete profiles.</p>
                <p><strong>Query Parameters:</strong></p>
                <ul>
                    <li>page - Page number (default: 1)</li>
                </ul>
                
                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "status": true,
  "products": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "title": "Premium Widget",
        "category": "Electronics",
        "short_description": "High-quality widget",
        "original_price": 99.99,
        "quantity": 50,
        "unit_of_quantity": "pieces",
        "product_image": "https://...",
        "user": {
          "id": 1,
          "first_name": "John",
          "last_name": "Doe",
          "slug": "john-doe",
          ...
        },
        ...
      }
    ],
    "per_page": 10,
    "total": 100,
    "last_page": 10
  }
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-get">GET</span>
                    Get Industries
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/industries</div>
                <p>Get all available industries with icons</p>
                
                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "success": true,
  "data": [
    {
      "name": "Technology",
      "icon": "fas fa-laptop-code",
      "count": 150
    },
    {
      "name": "Healthcare",
      "icon": "fas fa-heartbeat",
      "count": 80
    },
    ...
  ]
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-get">GET</span>
                    Get Industry Experts
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/industry-experts/{industry}</div>
                <p>Get experts/users in a specific industry</p>
                <p><strong>URL Parameters:</strong> Replace {industry} with the industry name</p>
                
                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "success": true,
  "data": [
    {
      "id": 1,
      "first_name": "John",
      "last_name": "Doe",
      "company": {
        "company_name": "Tech Corp",
        ...
      },
      ...
    }
  ]
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-get">GET</span>
                    Get Search Filters
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/search-filters</div>
                <p>Get available search filter options (industries, designations, etc.)</p>
                
                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "success": true,
  "data": {
    "industries": [...],
    "designations": [...],
    "business_types": [...],
    ...
  }
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-get">GET</span>
                    Search Users/Companies
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/search?q={query}&industry={industry}&type={type}</div>
                <p>Search for users and companies with filters</p>
                <p><strong>Query Parameters:</strong></p>
                <ul>
                    <li>q - Search query string</li>
                    <li>industry - Filter by industry (optional)</li>
                    <li>type - Filter by type: "user" or "company" (optional)</li>
                </ul>
                
                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "success": true,
  "users": [...],
  "companies": [...]
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-get">GET</span>
                    Get Smart Suggestions
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/smart-suggestions?per_page=10&page=1</div>
                <p>Get smart suggestions based on user profile and connections. Uses AI to recommend users you should connect with.</p>
                <p><strong>Query Parameters:</strong></p>
                <ul>
                    <li>per_page - Number of suggestions per page (default: 10)</li>
                    <li>page - Page number (default: 1)</li>
                </ul>
                
                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "status": true,
  "users": {
    "current_page": 1,
    "data": [
      {
        "id": 2,
        "first_name": "Jane",
        "last_name": "Doe",
        "email": "jane@example.com",
        "slug": "jane-doe",
        "photo": "https://...",
        "score": 85,
        "match_reasons": [
          "Same industry",
          "Similar location",
          "Common connections"
        ],
        "company": {
          "company_name": "Tech Corp",
          "company_industry": "Technology",
          ...
        },
        "userEducations": [...],
        ...
      }
    ],
    "per_page": 10,
    "total": 50,
    "last_page": 5
  }
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-get">GET</span>
                    Get Suggestions
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/get-suggestions?q={query}</div>
                <p>Get search suggestions/autocomplete results</p>
                <p><strong>Query Parameters:</strong> q - Search query string</p>
                
                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "success": true,
  "suggestions": [
    "John Doe",
    "Jane Smith",
    "Tech Corp",
    ...
  ]
}</pre>
                </div>
            </div>
        </div>

        <!-- Firebase Routes -->
        <div id="firebase" class="api-section">
            <h2>🔥 Firebase</h2>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-post">POST</span>
                    User Ping (Online Status)
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/user/ping</div>
                <p>Update user's online status to online in Firebase</p>
                
                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "status": "success"
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-post">POST</span>
                    User Offline
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/user/offline</div>
                <p>Update user's online status to offline in Firebase</p>
                
                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "status": "success"
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-get">GET</span>
                    Get Firebase Token
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/firebase-token</div>
                <p>Get Firebase custom token for authentication. Use this token to authenticate with Firebase Realtime Database.</p>
                
                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "firebase_token": "eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9..."
}</pre>
                </div>
            </div>
        </div>

        <!-- Device Token Routes (FCM) -->
        <div id="device-tokens" class="api-section">
            <h2>📱 Device Tokens (FCM)</h2>
            <p>Manage FCM (Firebase Cloud Messaging) device tokens for push notifications. Works for Android, iOS, and web browsers.</p>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-post">POST</span>
                    Register Device Token
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/device-token/register</div>
                <p>Register or update FCM device token for push notifications. This endpoint is automatically called during login if FCM token is provided, but can also be called separately to update the token.</p>
                
                <h5>Request Body:</h5>
                <table class="param-table">
                    <thead>
                        <tr>
                            <th>Parameter</th>
                            <th>Type</th>
                            <th>Required</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>fcm_token</td>
                            <td>string</td>
                            <td><span class="required">Yes</span></td>
                            <td>FCM token from device/browser</td>
                        </tr>
                        <tr>
                            <td>device_type</td>
                            <td>string</td>
                            <td><span class="optional">Optional</span></td>
                            <td>Device type: 'ios', 'android', or 'web'</td>
                        </tr>
                        <tr>
                            <td>device_id</td>
                            <td>string</td>
                            <td><span class="optional">Optional</span></td>
                            <td>Unique device identifier</td>
                        </tr>
                        <tr>
                            <td>device_name</td>
                            <td>string</td>
                            <td><span class="optional">Optional</span></td>
                            <td>Device name/model</td>
                        </tr>
                    </tbody>
                </table>

                <h5>Request Body Example:</h5>
                <div class="code-block">
                    <pre>{
  "fcm_token": "f9z8fQiPGt-e35HJciB18K:APA91bF51fH-LVxzTD8U3Q7MYlc0CKayyqxKiBxyFs8H6dpujlsVUFV4sOPK357p_gFg2MWdVZsFjlFctK_2_beP8_GeELkqon-u6vmQE_qWKIRYKosChNE",
  "device_type": "web",
  "device_id": "browser_123",
  "device_name": "Chrome Browser"
}</pre>
                </div>

                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "status": true,
  "message": "Device token registered successfully."
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-post">POST</span>
                    Remove Device Token
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/device-token/remove</div>
                <p>Remove FCM device token (e.g., when user logs out or uninstalls app)</p>
                
                <h5>Request Body:</h5>
                <table class="param-table">
                    <thead>
                        <tr>
                            <th>Parameter</th>
                            <th>Type</th>
                            <th>Required</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>fcm_token</td>
                            <td>string</td>
                            <td><span class="required">Yes</span></td>
                            <td>FCM token to remove</td>
                        </tr>
                    </tbody>
                </table>

                <h5>Request Body Example:</h5>
                <div class="code-block">
                    <pre>{
  "fcm_token": "f9z8fQiPGt-e35HJciB18K:APA91b..."
}</pre>
                </div>

                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "status": true,
  "message": "Device token removed successfully."
}</pre>
                </div>
            </div>
        </div>

        <!-- Notification Routes -->
        <div id="notifications" class="api-section">
            <h2>🔔 Notifications</h2>
            <p>Comprehensive notification management APIs. Notifications are automatically created when users interact with your content (reactions, comments, shares, messages, opportunities, etc.)</p>
            <div class="alert alert-warning" style="margin: 20px 0; padding: 15px; background: #fff3cd; border-left: 4px solid #ffc107; border-radius: 5px;">
                <strong>🌐 Web vs Mobile Endpoints:</strong> 
                <ul style="margin: 10px 0 0 20px; padding: 0;">
                    <li><strong>Web Endpoints:</strong> Use <code>/notifications</code> (no /api prefix) - Returns legacy format with pagination in <code>notifications</code> key</li>
                    <li><strong>Mobile API Endpoints:</strong> Use <code>/api/notifications</code> or <code>/api/notifications/list</code> - Returns standard format with pagination in <code>data.pagination</code> key</li>
                </ul>
            </div>
            <div class="alert alert-info" style="margin: 20px 0; padding: 15px; background: #e7f3ff; border-left: 4px solid #007bff; border-radius: 5px;">
                <strong>📸 User Photo in Notifications:</strong> All notification responses include <code>user_photo</code> (full URL), <code>user_name</code> (full name), and <code>trigger_user_id</code> (ID of the user who triggered the notification) fields. These fields are automatically populated from the notification data and are ready to display in your UI. Note: <code>user_id</code> in the notification object refers to the user who receives the notification, while <code>trigger_user_id</code> refers to the user who triggered it.
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-get">GET</span>
                    List Notifications (Mobile API)
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/api/notifications?per_page=20&page=1&unread_only=false&type=new_message&sort=latest</div>
                <p><strong>Mobile API Endpoint:</strong> Get user's notifications with advanced filtering and pagination. Also available at <code>/api/notifications/list</code></p>
                
                <h5>Query Parameters:</h5>
                <table class="param-table">
                    <thead>
                        <tr>
                            <th>Parameter</th>
                            <th>Type</th>
                            <th>Required</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>per_page</td>
                            <td>integer</td>
                            <td><span class="optional">Optional</span></td>
                            <td>Number of notifications per page (default: 20, max: 100)</td>
                        </tr>
                        <tr>
                            <td>page</td>
                            <td>integer</td>
                            <td><span class="optional">Optional</span></td>
                            <td>Page number (default: 1)</td>
                        </tr>
                        <tr>
                            <td>unread_only</td>
                            <td>boolean</td>
                            <td><span class="optional">Optional</span></td>
                            <td>If true, only return unread notifications (default: false)</td>
                        </tr>
                        <tr>
                            <td>type</td>
                            <td>string</td>
                            <td><span class="optional">Optional</span></td>
                            <td>Filter by notification type (e.g., post_reaction, new_message, opportunity_new_proposal)</td>
                        </tr>
                        <tr>
                            <td>sort</td>
                            <td>string</td>
                            <td><span class="optional">Optional</span></td>
                            <td>Sort order: 'latest' or 'oldest' (default: latest)</td>
                        </tr>
                    </tbody>
                </table>

                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "status": true,
  "message": "Notifications fetched successfully.",
  "data": {
    "notifications": [
      {
        "id": 1,
        "user_id": 1,
        "type": "post_reaction",
        "title": "New Reaction",
        "message": "John Doe reacted to your post",
        "user_photo": "https://s3.amazonaws.com/bucket/photos/user-2.jpg",
        "user_name": "John Doe",
        "trigger_user_id": 2,
        "data": {
          "post_id": 123,
          "post_slug": "my-awesome-post-29-01-2026-123456",
          "reactor_id": 2,
          "reactor_name": "John Doe",
          "reaction_type": "appreciate"
        },
        "read_at": null,
        "created_at": "2026-01-29T10:00:00.000000Z",
        "updated_at": "2026-01-29T10:00:00.000000Z"
      }
    ],
    "pagination": {
      "current_page": 1,
      "last_page": 5,
      "per_page": 20,
      "total": 100,
      "from": 1,
      "to": 20
    },
    "unread_count": 15,
    "filters": {
      "unread_only": false,
      "type": "new_message",
      "sort": "latest"
    }
  }
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-get">GET</span>
                    Get Single Notification
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/api/notifications/{id}</div>
                <p><strong>Mobile API Endpoint:</strong> Get details of a specific notification</p>
                
                <h5>URL Parameters:</h5>
                <table class="param-table">
                    <thead>
                        <tr>
                            <th>Parameter</th>
                            <th>Type</th>
                            <th>Required</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>id</td>
                            <td>integer</td>
                            <td><span class="required">Yes</span></td>
                            <td>Notification ID</td>
                        </tr>
                    </tbody>
                </table>

                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "status": true,
  "message": "Notification fetched successfully.",
  "data": {
    "notification": {
      "id": 123,
      "user_id": 456,
      "type": "post_comment",
      "title": "New Comment",
      "message": "Jane Smith commented on your post",
      "user_photo": "https://s3.amazonaws.com/bucket/photos/user-789.jpg",
      "user_name": "Jane Smith",
      "trigger_user_id": 789,
      "data": {
        "post_id": 456,
        "post_slug": "example-post",
        "comment_id": 789,
        "commenter_id": 789,
        "commenter_name": "Jane Smith"
      },
      "read_at": "2024-01-15T11:00:00.000000Z",
      "created_at": "2024-01-15T10:30:00.000000Z",
      "updated_at": "2024-01-15T11:00:00.000000Z"
    }
  }
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-get">GET</span>
                    Get Unread Count
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/api/notifications/unread-count</div>
                <p><strong>Mobile API Endpoint:</strong> Get the count of unread notifications</p>

                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "status": true,
  "message": "Unread count fetched successfully.",
  "data": {
    "unread_count": 15
  }
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-get">GET</span>
                    Get Notification Types
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/api/notifications/types</div>
                <p><strong>Mobile API Endpoint:</strong> Get a list of all available notification types</p>

                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "status": true,
  "message": "Notification types fetched successfully.",
  "data": {
    "types": {
      "post_reaction": "Post Reaction",
      "post_comment": "Post Comment",
      "comment_reply": "Comment Reply",
      "post_share": "Post Share",
      "new_message": "New Message",
      "new_service": "New Service",
      "new_product": "New Product",
      "profile_view": "Profile View",
      "new_follower": "New Follower",
      "subscription_event": "Subscription Event",
      "admin_notification": "Admin Notification",
      "opportunity_new_proposal": "New Proposal",
      "proposal_shortlisted": "Proposal Shortlisted",
      "proposal_accepted": "Proposal Accepted",
      "proposal_rejected": "Proposal Rejected",
      "proposal_withdrawn": "Proposal Withdrawn",
      "opportunity_expired": "Opportunity Expired",
      "opportunity_deadline_reminder": "Deadline Reminder"
    }
  }
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-post">POST</span>
                    Mark Notification as Read
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/api/notifications/{id}/read</div>
                <p><strong>Mobile API Endpoint:</strong> Mark a specific notification as read</p>
                
                <h5>URL Parameters:</h5>
                <table class="param-table">
                    <thead>
                        <tr>
                            <th>Parameter</th>
                            <th>Type</th>
                            <th>Required</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>id</td>
                            <td>integer</td>
                            <td><span class="required">Yes</span></td>
                            <td>Notification ID</td>
                        </tr>
                    </tbody>
                </table>

                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "status": true,
  "message": "Notification marked as read.",
  "data": {
    "notification": { ... },
    "unread_count": 14
  }
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-post">POST</span>
                    Mark Multiple Notifications as Read
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/api/notifications/read-multiple</div>
                <p><strong>Mobile API Endpoint:</strong> Mark multiple notifications as read. Send an array of notification IDs in the request body.</p>
                <p><strong>Note:</strong> This endpoint automatically marks all unread notifications as read. You don't need to send notification IDs, avoiding unnecessary looping on the client side.</p>

                <h5>Request Body:</h5>
                <p><em>No request body required. Simply make a POST request to this endpoint.</em></p>

                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "status": true,
  "message": "All notifications marked as read.",
  "data": {
    "marked_count": 15,
    "unread_count": 0
  }
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-post">POST</span>
                    Mark All Notifications as Read
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/api/notifications/read-all</div>
                <p><strong>Mobile API Endpoint:</strong> Mark all user's unread notifications as read</p>

                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "status": true,
  "message": "All notifications marked as read.",
  "data": {
    "marked_count": 15,
    "unread_count": 0
  }
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-delete">DELETE</span>
                    Delete Notification
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/api/notifications/{id}</div>
                <p><strong>Mobile API Endpoint:</strong> Delete a specific notification</p>
                
                <h5>URL Parameters:</h5>
                <table class="param-table">
                    <thead>
                        <tr>
                            <th>Parameter</th>
                            <th>Type</th>
                            <th>Required</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>id</td>
                            <td>integer</td>
                            <td><span class="required">Yes</span></td>
                            <td>Notification ID</td>
                        </tr>
                    </tbody>
                </table>

                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "status": true,
  "message": "Notification deleted successfully.",
  "data": {
    "unread_count": 14
  }
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-delete">DELETE</span>
                    Delete Multiple Notifications
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/api/notifications/delete/multiple</div>
                <p><strong>Mobile API Endpoint:</strong> Delete multiple notifications at once</p>

                <h5>Request Body:</h5>
                <table class="param-table">
                    <thead>
                        <tr>
                            <th>Parameter</th>
                            <th>Type</th>
                            <th>Required</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>ids</td>
                            <td>array</td>
                            <td><span class="required">Yes</span></td>
                            <td>Array of notification IDs to delete</td>
                        </tr>
                    </tbody>
                </table>

                <h5>Request Body Example:</h5>
                <div class="code-block">
                    <pre>{
  "ids": [123, 124, 125]
}</pre>
                </div>

                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "status": true,
  "message": "3 notification(s) deleted successfully.",
  "data": {
    "deleted_count": 3,
    "unread_count": 12
  }
}</pre>
                </div>
            </div>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-delete">DELETE</span>
                    Delete All Notifications
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/api/notifications/delete/all?read_only=false</div>
                <p><strong>Mobile API Endpoint:</strong> Delete all notifications (with optional filter for read-only)</p>

                <h5>Query Parameters:</h5>
                <table class="param-table">
                    <thead>
                        <tr>
                            <th>Parameter</th>
                            <th>Type</th>
                            <th>Required</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>read_only</td>
                            <td>boolean</td>
                            <td><span class="optional">Optional</span></td>
                            <td>If true, only delete read notifications (default: false)</td>
                        </tr>
                    </tbody>
                </table>

                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "status": true,
  "message": "50 notification(s) deleted successfully.",
  "data": {
    "deleted_count": 50,
    "unread_count": 5
  }
}</pre>
                </div>
            </div>

            <h4 style="margin-top: 30px; color: #273572;">Notification Types</h4>
            <p>Available notification types in the system:</p>
            <div class="code-block">
                <pre><strong>Content & Social:</strong>
- post_reaction - Someone reacted to your post
- post_comment - Someone commented on your post
- comment_reply - Someone replied to your comment
- post_share - Someone shared/reposted your post

<strong>Communication:</strong>
- new_message - You received a new message

<strong>Business:</strong>
- new_service - A new service was posted
- new_product - A new product was posted

<strong>Profile:</strong>
- profile_view - Someone viewed your profile
- new_follower - Someone followed you

<strong>Opportunities:</strong>
- opportunity_new_proposal - New proposal submitted to your opportunity
- proposal_shortlisted - Your proposal was shortlisted
- proposal_accepted - Your proposal was accepted
- proposal_rejected - Your proposal was rejected
- proposal_withdrawn - A proposal was withdrawn
- opportunity_expired - An opportunity has expired
- opportunity_deadline_reminder - Opportunity deadline approaching

<strong>System:</strong>
- subscription_event - Subscription-related events
- admin_notification - Admin notifications</pre>
            </div>
        </div>

        <!-- Subscription Routes -->
        <div id="subscriptions" class="api-section">
            <h2>💳 Subscriptions</h2>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-post">POST</span>
                    Handle IAP Subscription
                    <span class="auth-badge auth-required">AUTH REQUIRED</span>
                </h3>
                <div class="endpoint-url">/subscribe/iap</div>
                <p>Handle in-app purchase subscription from Google Play or Apple App Store</p>
                
                <h5>Request Body:</h5>
                <table class="param-table">
                    <thead>
                        <tr>
                            <th>Parameter</th>
                            <th>Type</th>
                            <th>Required</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>startDate</td>
                            <td>date</td>
                            <td><span class="required">Yes</span></td>
                            <td>Subscription start date (YYYY-MM-DD)</td>
                        </tr>
                        <tr>
                            <td>type</td>
                            <td>string</td>
                            <td><span class="required">Yes</span></td>
                            <td>Subscription type: "Premium_Monthly" or "Premium_Yearly"</td>
                        </tr>
                        <tr>
                            <td>transactionId</td>
                            <td>string</td>
                            <td><span class="required">Yes</span></td>
                            <td>Transaction ID from the platform</td>
                        </tr>
                        <tr>
                            <td>recieptData</td>
                            <td>string</td>
                            <td>No</td>
                            <td>Receipt data for verification</td>
                        </tr>
                        <tr>
                            <td>platform</td>
                            <td>string</td>
                            <td><span class="required">Yes</span></td>
                            <td>Platform: "google" or "apple"</td>
                        </tr>
                    </tbody>
                </table>

                <h5>Request Body Example:</h5>
                <div class="code-block">
                    <pre>{
  "startDate": "2024-01-01",
  "type": "Premium_Monthly",
  "transactionId": "GPA.1234-5678-9012-34567",
  "recieptData": "base64_encoded_receipt_data",
  "platform": "google"
}</pre>
                </div>

                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "status": true,
  "message": "Subscription activated successfully",
  "subscription": {
    "id": 1,
    "plan_id": 1,
    "subscription_type": "Monthly",
    "status": "active",
    "start_date": "2024-01-01",
    "renewal_date": "2024-02-01",
    ...
  }
}</pre>
                </div>
            </div>
        </div>

        <!-- API Key Protected Routes -->
        <div id="api-key-routes" class="api-section">
            <h2>🔑 API Key Protected Routes</h2>
            <p>These routes require an X-API-KEY header instead of Bearer token authentication.</p>

            <div class="api-endpoint">
                <h3>
                    <span class="method-badge method-get">GET</span>
                    Get All Users with Relations
                    <span class="auth-badge auth-required">API KEY REQUIRED</span>
                </h3>
                <div class="endpoint-url">/muslimlynk-users</div>
                <p>Get all users with their related data (company, products, services, qualifications, etc.)</p>
                <p><strong>Header:</strong> X-API-KEY: {your_api_key}</p>
                
                <h5>Response:</h5>
                <div class="code-block">
                    <pre>{
  "success": true,
  "data": [
    {
      "id": 1,
      "first_name": "John",
      "last_name": "Doe",
      "email": "john@example.com",
      "company": {
        "company_name": "Tech Corp",
        "company_industry": "Technology",
        ...
      },
      "products": [...],
      "services": [...],
      "qualifications": [...],
      ...
    }
  ]
}</pre>
                </div>
            </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Smooth scrolling for navigation links
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href').substring(1);
                const targetElement = document.getElementById(targetId);
                
                if (targetElement) {
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                    
                    // Update active state
                    document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
                    this.classList.add('active');
                }
            });
        });

        // Update active nav link on scroll
        const sections = document.querySelectorAll('.api-section');
        const navLinks = document.querySelectorAll('.nav-link');

        function updateActiveNav() {
            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                if (window.pageYOffset >= (sectionTop - 100)) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === '#' + current) {
                    link.classList.add('active');
                }
            });
        }

        window.addEventListener('scroll', updateActiveNav);
        updateActiveNav(); // Initial call
    </script>
</body>
</html>
