# News Feed System - Complete Flow Documentation

## 📋 Table of Contents
1. [Overall Architecture](#overall-architecture)
2. [Request Flow](#request-flow)
3. [Data Flow](#data-flow)
4. [User Interaction Flows](#user-interaction-flows)
5. [Model Relationships Flow](#model-relationships-flow)

---

## 🏗️ Overall Architecture

```
┌─────────────────┐
│   Frontend      │
│  (Blade/JS)     │
└────────┬────────┘
         │ HTTP Requests
         ▼
┌─────────────────┐
│   Routes        │
│  (web.php/api.php)│
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│  Middleware     │
│  (auth, role)    │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│  Controllers    │
│  (FeedController)│
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│   Models        │
│  (Post, Comment,│
│   Reaction, etc)│
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│   Database      │
│  (MySQL/Postgres)│
└─────────────────┘
```

---

## 🔄 Request Flow

### 1. **Initial Page Load**
```
User visits /news-feed
    ↓
Route: GET /news-feed
    ↓
Middleware: auth + role:4
    ↓
FeedController@index()
    ↓
Returns: view('user.news-feed')
    ↓
Frontend loads Blade template
```

### 2. **Loading Posts (AJAX)**
```
Frontend JavaScript
    ↓
AJAX Request: GET /feed/posts?per_page=15
    ↓
Route: feed.posts
    ↓
Middleware: auth + role:4
    ↓
FeedController@getFeed()
    ↓
Query Database with Eager Loading
    ↓
Return JSON Response
    ↓
Frontend renders posts
```

### 3. **Creating a Post**
```
User fills form + uploads media
    ↓
AJAX Request: POST /feed/posts
    (FormData with content + media files)
    ↓
Route: feed.post.create
    ↓
Middleware: auth + role:4
    ↓
FeedController@createPost()
    ↓
Validation: content, media, comments_enabled
    ↓
DB Transaction Start
    ↓
Create Post record
    ↓
Upload media files to storage
    ↓
Create PostMedia records
    ↓
DB Transaction Commit
    ↓
Return JSON with new post data
    ↓
Frontend adds post to feed
```

---

## 📊 Data Flow

### **Post Retrieval Flow**

```
1. Controller receives request
   ↓
2. Query Builder:
   Post::with([
       'user' => selects only: id, first_name, last_name, slug, photo, user_position
       'user.company' => selects only: id, user_id, company_name, company_logo
       'media' => all post media
       'reactions' => filtered by current user_id
       'comments' => active only, limit 3, with replies
       'originalPost' => if shared post
   ])
   ↓
3. Filters:
   - status = 'active'
   - deleted_at IS NULL
   - orderBy created_at DESC
   ↓
4. Pagination: 15 per page
   ↓
5. Eloquent returns Collection
   ↓
6. JSON Response:
   {
     "success": true,
     "data": {
       "current_page": 1,
       "data": [Post objects with relationships],
       "per_page": 15,
       ...
     }
   }
```

### **Post Creation Flow**

```
1. Request arrives with:
   - content (text)
   - media[] (files)
   - comments_enabled (boolean)
   ↓
2. Validation passes
   ↓
3. DB Transaction starts
   ↓
4. Create Post:
   - user_id = Auth::id()
   - content = request content
   - status = 'active'
   - reactions_count = 0
   - comments_count = 0
   - shares_count = 0
   ↓
5. For each media file:
   - Determine type (image/video)
   - Store in 'posts/media' directory
   - Create PostMedia record
   - Set order
   ↓
6. Transaction commits
   ↓
7. Reload post with relationships
   ↓
8. Return JSON response
```

---

## 👤 User Interaction Flows

### **Flow 1: Viewing Feed**
```
1. User opens /news-feed
   ↓
2. Page loads (Blade template)
   ↓
3. JavaScript fetches posts: GET /feed/posts
   ↓
4. Posts render with:
   - User info (name, photo, position, company)
   - Post content
   - Media (images/videos)
   - Reaction counts
   - Comment previews (3 latest)
   - Share count
   ↓
5. User scrolls → Load more (pagination)
```

### **Flow 2: Creating Post**
```
1. User types content in post composer
   ↓
2. User uploads images/videos (optional)
   ↓
3. User clicks "Post"
   ↓
4. JavaScript sends POST /feed/posts
   - FormData with content + files
   ↓
5. Backend:
   - Validates
   - Stores post
   - Uploads media
   - Returns new post data
   ↓
6. Frontend:
   - Adds post to top of feed
   - Shows success message
   - Clears composer
```

### **Flow 3: Reacting to Post**
```
1. User clicks reaction button
   ↓
2. Reaction picker shows (like, love, haha, wow, sad, angry)
   ↓
3. User selects reaction
   ↓
4. JavaScript sends POST /feed/reactions
   {
     reactionable_type: "App\Models\Feed\Post",
     reactionable_id: 123,
     reaction_type: "like"
   }
   ↓
5. Backend checks:
   - Does user already have reaction?
     - YES: Same type? → Remove reaction
     - YES: Different type? → Update reaction
     - NO: Create new reaction
   ↓
6. Model Events:
   - Reaction created → Post increments reactions_count
   - Reaction deleted → Post decrements reactions_count
   ↓
7. Return updated reaction data
   ↓
8. Frontend updates UI:
   - Update reaction count
   - Highlight user's reaction
   - Update reaction emoji display
```

### **Flow 4: Commenting**
```
1. User clicks "Comment"
   ↓
2. Comment input appears
   ↓
3. User types comment
   ↓
4. User clicks "Post Comment"
   ↓
5. JavaScript sends POST /feed/posts/{id}/comments
   {
     content: "Great post!",
     parent_id: null (for top-level comment)
   }
   ↓
6. Backend:
   - Validates content
   - Checks if comments enabled
   - Creates PostComment
   - Sets status = 'active'
   ↓
7. Model Event (boot method):
   - PostComment created → Post increments comments_count
   ↓
8. Return comment with user data
   ↓
9. Frontend:
   - Adds comment to list
   - Updates comment count
   - Clears input
```

### **Flow 5: Replying to Comment**
```
1. User clicks "Reply" on a comment
   ↓
2. Reply input appears (nested)
   ↓
3. User types reply
   ↓
4. JavaScript sends POST /feed/posts/{id}/comments
   {
     content: "I agree!",
     parent_id: 456 (parent comment ID)
   }
   ↓
5. Backend:
   - Creates PostComment with parent_id
   - Still increments post's comments_count
   ↓
6. Return reply with user data
   ↓
7. Frontend:
   - Adds reply under parent comment
   - Updates comment count
```

### **Flow 6: Sharing/Reposting**
```
1. User clicks "Share" button
   ↓
2. Share modal appears
   ↓
3. User selects share type:
   - "Share" (just record the share)
   - "Repost" (create new post referencing original)
   ↓
4. User adds optional comment
   ↓
5. JavaScript sends POST /feed/posts/{id}/share
   {
     shared_content: "Check this out!",
     share_type: "repost"
   }
   ↓
6. Backend:
   - If repost: Creates new Post with original_post_id
   - Creates PostShare record
   ↓
7. Model Event:
   - PostShare created → Original post increments shares_count
   ↓
8. Return share data
   ↓
9. Frontend updates share count
```

---

## 🔗 Model Relationships Flow

### **Post Relationships**
```
Post
├── belongsTo User
│   └── hasOne Company
├── hasMany PostMedia
├── morphMany Reactions (via feed_reactions)
├── hasMany PostComments
│   ├── belongsTo User
│   ├── belongsTo PostComment (parent)
│   └── morphMany Reactions
├── hasMany PostShares
├── belongsTo Post (originalPost) - if shared
└── hasMany Post (sharedPosts) - posts that share this
```

### **Eager Loading Strategy**
```
When fetching posts, we load:
1. user (with specific columns only)
2. user.company (with specific columns)
3. media (all media for post)
4. reactions (filtered by current user)
5. comments (active, limit 3, with replies)
6. originalPost (if shared post)

This prevents N+1 query problems.
```

### **Count Updates Flow**
```
Post Model has cached counts:
- reactions_count
- comments_count
- shares_count

These are updated via:
1. Model Events (boot method)
2. Helper methods:
   - incrementReactionsCount()
   - decrementReactionsCount()
   - incrementCommentsCount()
   - decrementCommentsCount()
   - incrementSharesCount()
   - decrementSharesCount()

Example:
PostComment created
  → boot() method fires
  → $comment->post->incrementCommentsCount()
  → Post.comments_count += 1
```

---

## 🔐 Security & Validation Flow

### **Authentication Flow**
```
1. User must be authenticated (auth middleware)
2. User must have role_id = 4 (RoleMiddleware)
3. All actions check ownership:
   - Update/Delete Post → Check user_id matches Auth::id()
   - Update/Delete Comment → Check user_id matches Auth::id()
```

### **Validation Flow**
```
1. Request arrives
2. Controller validates:
   - createPost: content (nullable, max 10000), media (array, max 10 files)
   - addComment: content (required, max 5000)
   - addReaction: reaction_type (required, in: like,love,haha,wow,sad,angry)
3. If validation fails → 422 response
4. If validation passes → Continue processing
```

---

## 📁 File Upload Flow

### **Media Upload Process**
```
1. User selects files (images/videos)
2. Frontend validates file types/sizes
3. Files sent as FormData
4. Backend receives files
5. For each file:
   - Determine MIME type
   - Set media_type (image/video)
   - Store in storage/app/public/posts/media/
   - Generate public URL
   - Save metadata to PostMedia:
     * media_path
     * media_url
     * file_name
     * file_size
     * mime_type
     * order (for multiple files)
6. Return media URLs to frontend
7. Frontend displays media
```

---

## 🗄️ Database Transaction Flow

### **Critical Operations Use Transactions**
```
Operations that use DB transactions:
1. createPost() - Post + PostMedia
2. sharePost() - PostShare + possibly new Post

Transaction Flow:
1. DB::beginTransaction()
2. Perform operations
3. If success: DB::commit()
4. If error: DB::rollBack()
5. Log error
6. Return error response
```

---

## 🔄 Soft Delete Flow

### **Deleting Post**
```
1. User clicks delete
2. JavaScript sends DELETE /feed/posts/{id}
3. Backend:
   - Find post (must be owner)
   - Set status = 'deleted'
   - Call $post->delete() (soft delete)
   - Sets deleted_at timestamp
4. Post still exists in DB but:
   - Won't appear in queries (whereNull('deleted_at'))
   - Can be restored if needed
5. Return success
6. Frontend removes from UI
```

### **Deleting Comment**
```
1. User clicks delete on comment
2. JavaScript sends DELETE /feed/comments/{id}
3. Backend:
   - Find comment (must be owner)
   - Set status = 'deleted'
   - Call $comment->delete() (soft delete)
   - Model event fires → decrements post comments_count
4. Comment hidden from queries
5. Return success
6. Frontend removes from UI
```

---

## 📱 API vs Web Routes Flow

### **Web Routes** (`/feed/*`)
```
- Used by Blade templates
- Protected by: auth + role:4 middleware
- Returns JSON for AJAX calls
- Same controller methods as API
```

### **API Routes** (`/api/feed/*`)
```
- Used by mobile apps / external clients
- Protected by: auth:sanctum middleware
- Returns JSON responses
- Same controller methods as Web
```

---

## 🎯 Key Design Patterns

1. **Eager Loading**: Prevents N+1 queries
2. **Cached Counts**: reactions_count, comments_count, shares_count
3. **Soft Deletes**: Preserves data, hides from queries
4. **Model Events**: Auto-update counts on create/delete
5. **Polymorphic Relations**: Reactions work on Posts and Comments
6. **Transactions**: Ensure data consistency
7. **Validation**: Input validation at controller level
8. **Authorization**: Ownership checks before updates/deletes

---

## 📝 Summary

The news feed system follows a clean MVC architecture:
- **Frontend** (Blade/JS) makes requests
- **Routes** direct to controllers
- **Controllers** handle business logic
- **Models** manage data and relationships
- **Database** stores everything

All operations are:
- ✅ Authenticated
- ✅ Validated
- ✅ Authorized
- ✅ Transaction-safe
- ✅ Optimized (eager loading, cached counts)

