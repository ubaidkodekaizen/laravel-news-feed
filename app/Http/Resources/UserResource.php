<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'slug' => $this->slug,
            'email' => $this->email,
            'phone' => $this->phone,
            'linkedin_url' => $this->linkedin_url,
            'x_url' => $this->x_url,
            'instagram_url' => $this->instagram_url,
            'facebook_url' => $this->facebook_url,
            'address' => $this->address,
            'country' => $this->country,
            'state' => $this->state,
            'city' => $this->city,
            'county' => $this->county,
            'zip_code' => $this->zip_code,
            'industry_to_connect' => $this->industry_to_connect,
            'sub_category_to_connect' => $this->sub_category_to_connect,
            'community_interest' => $this->community_interest,
            'status' => $this->status,
            'paid' => $this->paid,
            'phone_public' => $this->phone_public,
            'email_public' => $this->email_public,
            'user_position' => $this->user_position,
            'gender' => $this->gender,
            'age_group' => $this->age_group,
            'ethnicity' => $this->ethnicity,
            'nationality' => $this->nationality,
            'languages' => $this->languages,
            'marital_status' => $this->marital_status,
            'is_amcob' => $this->is_amcob,
            'duration' => $this->duration,

            // Relations – always present keys
            'company' => $this->whenLoaded('company', function () {
                return $this->company ? [
                    'id' => $this->company->id,
                    'user_id' => $this->company->user_id,
                    'company_logo' => $this->company->company_logo,
                    'company_name' => $this->company->company_name,
                    'company_slug' => $this->company->company_slug,
                    'company_email' => $this->company->company_email,
                    'company_web_url' => $this->company->company_web_url,
                    'company_linkedin_url' => $this->company->company_linkedin_url,
                    'company_position' => $this->company->company_position,
                    'company_about' => $this->company->company_about,
                    'company_revenue' => $this->company->company_revenue,
                    'company_address' => $this->company->company_address,
                    'company_country' => $this->company->company_country,
                    'company_state' => $this->company->company_state,
                    'company_city' => $this->company->company_city,
                    'company_county' => $this->company->company_county,
                    'company_zip_code' => $this->company->company_zip_code,
                    'company_no_of_employee' => $this->company->company_no_of_employee,
                    'company_business_type' => $this->company->company_business_type,
                    'company_industry' => $this->company->company_industry,
                    'company_sub_category' => $this->company->company_sub_category,
                    'company_community_service' => $this->company->company_community_service,
                    'company_contribute_to_muslim_community' => $this->company->company_contribute_to_muslim_community,
                    'company_affiliation_to_muslim_org' => $this->company->company_affiliation_to_muslim_org,
                    'status' => $this->company->status,
                ] : null;
            }, null),

            'products' => $this->whenLoaded('products', function () {
                return $this->products->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'user_id' => $product->user_id,
                        'title' => $product->title ?? null,
                        'slug' => $product->slug ?? null,
                        'description' => $product->description ?? null,
                        'price' => $product->price ?? null,
                        'status' => $product->status ?? null,
                    ];
                });
            }, []),

            'services' => $this->whenLoaded('services', function () {
                return $this->services->map(function ($service) {
                    return [
                        'id' => $service->id,
                        'user_id' => $service->user_id,
                        'title' => $service->title ?? null,
                        'slug' => $service->slug ?? null,
                        'description' => $service->description ?? null,
                        'price' => $service->price ?? null,
                        'status' => $service->status ?? null,
                    ];
                });
            }, []),

            'subscriptions' => $this->whenLoaded('subscriptions', function () {
                return $this->subscriptions->map(function ($subscription) {
                    return [
                        'id' => $subscription->id,
                        'user_id' => $subscription->user_id,
                        'plan_id' => $subscription->plan_id,
                        'subscription_type' => $subscription->subscription_type,
                        'subscription_amount' => $subscription->subscription_amount,
                        'start_date' => $subscription->start_date,
                        'renewal_date' => $subscription->renewal_date,
                        'status' => $subscription->status,
                    ];
                });
            }, []),

            'user_educations' => $this->whenLoaded('userEducations', function () {
                return $this->userEducations->map(function ($education) {
                    return [
                        'id' => $education->id,
                        'user_id' => $education->user_id,
                        'degree' => $education->degree ?? null,
                        'institution' => $education->institution ?? null,
                        'field_of_study' => $education->field_of_study ?? null,
                        'start_year' => $education->start_year ?? null,
                        'end_year' => $education->end_year ?? null,
                    ];
                });
            }, []),

            'conversations' => $this->whenLoaded('conversations', function () {
                return $this->conversations->map(function ($conversation) {
                    return [
                        'id' => $conversation->id,
                        'user_one_id' => $conversation->user_one_id,
                        'user_two_id' => $conversation->user_two_id,
                        'last_message_at' => $conversation->last_message_at ? $conversation->last_message_at->toIso8601String() : null,
                        'messages' => $conversation->relationLoaded('messages') ? $conversation->messages->map(function ($message) {
                            return [
                                'id' => $message->id,
                                'conversation_id' => $message->conversation_id,
                                'sender_id' => $message->sender_id,
                                'receiver_id' => $message->receiver_id,
                                'content' => $message->content,
                                'read_at' => $message->read_at ? $message->read_at->toIso8601String() : null,
                                'created_at' => $message->created_at ? $message->created_at->toIso8601String() : null,
                                'updated_at' => $message->updated_at ? $message->updated_at->toIso8601String() : null,
                            ];
                        }) : [],
                    ];
                });
            }, []),

            'user_icp' => $this->whenLoaded('userIcp', function () {
                if (!$this->userIcp) {
                    return null;
                }

                return [
                    'id' => $this->userIcp->id,
                    'user_id' => $this->userIcp->user_id,
                    'business_location' => $this->userIcp->business_location,
                    'is_decision_maker' => $this->userIcp->is_decision_maker,
                    'company_current_business_challenges' => $this->userIcp->company_current_business_challenges,
                    'company_business_goals' => $this->userIcp->company_business_goals,
                    'company_attributes' => $this->userIcp->company_attributes,
                    'company_technologies_you_use' => $this->userIcp->company_technologies_you_use,
                    'company_buying_process' => $this->userIcp->company_buying_process,
                ];
            }, null),

            'reactions' => $this->whenLoaded('reactions', function () {
                return $this->reactions->map(function ($reaction) {
                    return [
                        'id' => $reaction->id,
                        'message_id' => $reaction->message_id,
                        'user_id' => $reaction->user_id,
                        'emoji' => $reaction->emoji,
                    ];
                });
            }, []),

            'user_mosques' => $this->when($this->relationLoaded('userMosques'), function () {
                return $this->userMosques->map(function ($mosque) {
                    return [
                        'id' => $mosque->id,
                        'user_id' => $mosque->user_id,
                        'mosque_id' => $mosque->mosque_id,
                        'amount' => $mosque->amount,
                    ];
                });
            }, []),

            'profile_views_count' => $this->when(isset($this->profile_views_count), function () {
                return $this->profile_views_count;
            }, 0),

            'profile_views' => $this->whenLoaded('profileViews', function () {
                return $this->profileViews->map(function ($view) {
                    return [
                        'id' => $view->id,
                        'viewed_user_id' => $view->viewed_user_id,
                        'viewer_id' => $view->viewer_id,
                        'viewed_at' => $view->created_at ? $view->created_at->toIso8601String() : null,
                        'viewer' => $view->relationLoaded('viewer') && $view->viewer ? [
                            'id' => $view->viewer->id,
                            'first_name' => $view->viewer->first_name,
                            'last_name' => $view->viewer->last_name,
                            'slug' => $view->viewer->slug,
                            'photo' => $view->viewer->photo,
                        ] : null,
                    ];
                });
            }, []),

            'viewed_profiles' => $this->whenLoaded('viewedProfiles', function () {
                return $this->viewedProfiles->map(function ($view) {
                    return [
                        'id' => $view->id,
                        'viewed_user_id' => $view->viewed_user_id,
                        'viewer_id' => $view->viewer_id,
                        'viewed_at' => $view->created_at ? $view->created_at->toIso8601String() : null,
                        'viewed_user' => $view->relationLoaded('viewedUser') && $view->viewedUser ? [
                            'id' => $view->viewedUser->id,
                            'first_name' => $view->viewedUser->first_name,
                            'last_name' => $view->viewedUser->last_name,
                            'slug' => $view->viewedUser->slug,
                            'photo' => $view->viewedUser->photo,
                        ] : null,
                    ];
                });
            }, []),

            'posts' => $this->whenLoaded('posts', function () {
                return $this->posts->map(function ($post) {
                    return [
                        'id' => $post->id,
                        'user_id' => $post->user_id,
                        'original_post_id' => $post->original_post_id,
                        'content' => $post->content,
                        'slug' => $post->slug,
                        'comments_enabled' => $post->comments_enabled,
                        'visibility' => $post->visibility,
                        'status' => $post->status,
                        'reactions_count' => $post->reactions_count,
                        'comments_count' => $post->comments_count,
                        'shares_count' => $post->shares_count,
                        'created_at' => $post->created_at ? $post->created_at->toIso8601String() : null,
                        'updated_at' => $post->updated_at ? $post->updated_at->toIso8601String() : null,
                        
                        // Media
                        'media' => $post->relationLoaded('media') ? $post->media->map(function ($media) {
                            return [
                                'id' => $media->id,
                                'post_id' => $media->post_id,
                                'media_type' => $media->media_type,
                                'media_path' => $media->media_path,
                                'media_url' => $media->media_url,
                                'thumbnail_path' => $media->thumbnail_path,
                                'file_name' => $media->file_name,
                                'file_size' => $media->file_size,
                                'mime_type' => $media->mime_type,
                                'duration' => $media->duration,
                                'order' => $media->order,
                            ];
                        }) : [],
                        
                        // Original Post (if this is a shared post)
                        'original_post' => $post->relationLoaded('originalPost') && $post->originalPost ? [
                            'id' => $post->originalPost->id,
                            'user_id' => $post->originalPost->user_id,
                            'content' => $post->originalPost->content,
                            'slug' => $post->originalPost->slug,
                            'user' => $post->originalPost->relationLoaded('user') && $post->originalPost->user ? [
                                'id' => $post->originalPost->user->id,
                                'first_name' => $post->originalPost->user->first_name,
                                'last_name' => $post->originalPost->user->last_name,
                                'slug' => $post->originalPost->user->slug,
                                'photo' => $post->originalPost->user->photo,
                            ] : null,
                            'media' => $post->originalPost->relationLoaded('media') ? $post->originalPost->media->map(function ($media) {
                                return [
                                    'id' => $media->id,
                                    'media_type' => $media->media_type,
                                    'media_url' => $media->media_url,
                                    'thumbnail_path' => $media->thumbnail_path,
                                ];
                            }) : [],
                        ] : null,
                    ];
                });
            }, []),

            'post_reactions' => $this->whenLoaded('postReactions', function () {
                return $this->postReactions->map(function ($reaction) {
                    return [
                        'id' => $reaction->id,
                        'user_id' => $reaction->user_id,
                        'reactionable_type' => $reaction->reactionable_type,
                        'reactionable_id' => $reaction->reactionable_id,
                        'reaction_type' => $reaction->reaction_type,
                        'created_at' => $reaction->created_at ? $reaction->created_at->toIso8601String() : null,
                    ];
                });
            }, []),

            'post_comments' => $this->whenLoaded('postComments', function () {
                return $this->postComments->map(function ($comment) {
                    return [
                        'id' => $comment->id,
                        'post_id' => $comment->post_id,
                        'user_id' => $comment->user_id,
                        'parent_id' => $comment->parent_id,
                        'content' => $comment->content,
                        'status' => $comment->status,
                        'is_reply' => !is_null($comment->parent_id),
                        'created_at' => $comment->created_at ? $comment->created_at->toIso8601String() : null,
                        'updated_at' => $comment->updated_at ? $comment->updated_at->toIso8601String() : null,
                        'post' => $comment->relationLoaded('post') && $comment->post ? [
                            'id' => $comment->post->id,
                            'content' => $comment->post->content,
                            'slug' => $comment->post->slug,
                            'user_id' => $comment->post->user_id,
                        ] : null,
                    ];
                });
            }, []),
        ];
    }
}


