<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use App\Models\User;
use App\Models\Feed\Post;
use App\Models\Feed\PostMedia;
use App\Models\Feed\PostComment;
use App\Models\Feed\Reaction;
use App\Models\Feed\PostShare;
use Illuminate\Support\Facades\DB;

class FeedSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        DB::transaction(function () use ($faker) {
            // Create (or fetch) demo users
            $users = [];
            for ($i = 1; $i <= 5; $i++) {
                $users[] = User::firstOrCreate(
                    ['email' => "demo{$i}@example.com"],
                    [
                        // fallback fields - adapt to your users table
                        'password' => bcrypt('password'),
                        'first_name' => $faker->firstName,
                        'last_name' => $faker->lastName,
                        // if your user model uses different names, adjust them
                        'photo' => 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png',
                        'user_position' => $faker->jobTitle,
                        'city' => $faker->city,
                    ]
                );
            }

            // For each user, create a couple posts with media, comments, reactions and shares
            foreach ($users as $uIndex => $user) {
                for ($p = 0; $p < 2; $p++) {
                    $content = $faker->sentences(rand(1, 4), true) . ' #demo #feed';
                    $post = Post::create([
                        'user_id' => $user->id,
                        'original_post_id' => null,
                        'content' => $content,
                        'comments_enabled' => true,
                        'status' => 'active',
                        'reactions_count' => 0,
                        'comments_count' => 0,
                        'shares_count' => 0,
                    ]);

                    // Add 0..2 media items
                    $mediaCount = rand(0, 2);
                    for ($m = 0; $m < $mediaCount; $m++) {
                        // choose URLs
                        $isImage = $m === 0;
                        $mediaUrl = $isImage
                            ? 'https://images.unsplash.com/photo-1531297484001-80022131f5a1?w=800&q=80'
                            : 'https://sample-videos.com/video123/mp4/720/big_buck_bunny_720p_1mb.mp4';

                        // use a non-null media_path — here we use the public URL as a safe placeholder.
                        // If you prefer a local storage path, change this to something like "uploads/posts/..."
                        $mediaPath = $mediaUrl;

                        // thumbnail placeholder (non-null). You may replace with a real thumbnail path if available.
                        $thumbnail = $mediaUrl;

                        PostMedia::create([
                            'post_id' => $post->id,
                            'media_type' => $isImage ? 'image' : 'video',
                            'media_path' => $mediaPath,
                            'media_url' => $mediaUrl,
                            'thumbnail_path' => $thumbnail,
                            'file_name' => $faker->word . ($isImage ? '.jpg' : '.mp4'),
                            'file_size' => rand(1000, 500000),
                            'mime_type' => $isImage ? 'image/jpeg' : 'video/mp4',
                            // ensure duration is not null (0 for images)
                            'duration' => $isImage ? 0 : rand(10, 180),
                            'order' => $m + 1,
                        ]);
                    }

                    // Add 1..3 comments
                    $commentsCount = rand(1, 3);
                    for ($c = 0; $c < $commentsCount; $c++) {
                        $commentUser = $users[array_rand($users)];
                        $comment = PostComment::create([
                            'post_id' => $post->id,
                            'user_id' => $commentUser->id,
                            'parent_id' => null,
                            'content' => $faker->sentence,
                            'status' => 'active',
                        ]);
                        // Optionally add a reply to the comment sometimes
                        if (rand(0, 1)) {
                            PostComment::create([
                                'post_id' => $post->id,
                                'user_id' => $users[array_rand($users)]->id,
                                'parent_id' => $comment->id,
                                'content' => $faker->sentence,
                                'status' => 'active',
                            ]);
                        }
                    }

                    // Add 0..5 reactions (these use feed_reactions table via Reaction model)
                    $reactionTypes = ['like', 'love', 'haha', 'wow', 'sad', 'angry'];

                    // shuffle users and react with a subset
                    $reactingUsers = collect($users)->shuffle()->take(rand(0, count($users)));

                    foreach ($reactingUsers as $reactingUser) {
                        Reaction::updateOrCreate(
                            [
                                'reactionable_id' => $post->id,
                                'reactionable_type' => Post::class,
                                'user_id' => $reactingUser->id,
                            ],
                            [
                                'reaction_type' => $reactionTypes[array_rand($reactionTypes)],
                            ]
                        );
                    }

                    // Valid share types for your DB
                    $shareTypes = ['share', 'repost'];

                    // Add 0..1 share
                    if (rand(0, 1)) {
                        $sharingUser = $users[array_rand($users)];
                        $sharedPost = Post::create([
                            'user_id' => $sharingUser->id,
                            'original_post_id' => $post->id,
                            'content' => 'Reposting: ' . Str::limit($post->content, 140),
                            'comments_enabled' => true,
                            'status' => 'active',
                            'reactions_count' => 0,
                            'comments_count' => 0,
                            'shares_count' => 0,
                        ]);

                        PostShare::create([
                            'post_id' => $post->id,
                            'user_id' => $sharingUser->id,
                            'shared_post_id' => $sharedPost->id,
                            'shared_content' => $sharedPost->content,
                            'share_type' => $shareTypes[array_rand($shareTypes)],
                        ]);
                    }

                    // Sync cached counts on post (optional)
                    $post->reactions_count = $post->reactions()->count();
                    $post->comments_count = $post->comments()->count();
                    $post->shares_count = $post->shares()->count();
                    $post->save();
                }
            }
        });
    }
}
