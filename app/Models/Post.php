<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ApiTrait;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class Post extends Model
{
    use HasFactory, ApiTrait;

    protected $table = 'posts';

    protected $casts = [
        'user_id' => 'int'
    ];

    protected $fillable = ['title', 'content', 'status', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected function getDataPostByUserAccess($user, $post_id = null)
    {
        if($this->isNormalUser($user)) {
            $posts = $user->posts;
            if(!is_null($post_id)) {
                $posts = $posts->find($post_id);
            }
        } else {
            $posts = self::latest()->get();
            if(!is_null($post_id)) {
                $posts = self::find($post_id);
            }
        }

        return $posts ?? [];
    }

    protected function modifyValueDataPost($posts) {
        if($posts instanceof Collection) {
            $posts = $posts->map(function ($post, $key) {
                $post->status = self::getStatusMap($post->status);

                return $post;
            });
        } else {
            if(!empty($posts) && !is_null($posts)) {
                $posts->status = self::getStatusMap($posts->status);
            }
        }

        return $posts;
    }

    const   ACTIVE = 1,
            NONACTIVE = 0;

    public static function getStatusMap(int $status = null)
    {
        $statuses = [
            self::ACTIVE => 'Active',
            self::NONACTIVE => 'Non Active'
        ];

        return $statuses[$status] ?? $status ?? $statuses;
    }
}
