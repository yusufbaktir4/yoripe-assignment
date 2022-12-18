<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Collection;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    protected function modifyValueDataUsers($users) {
        if($users instanceof Collection) {
            $users = $users->map(function ($user, $key) {
                $user->role = self::getRoleMap($user->role);

                return $user;
            });
        } else {
            if(!empty($users) && !is_null($users)) {
                $users->role = self::getRoleMap($users->role);
            }
        }

        return $users;
    }

    const   NORMAL_USER = 1,
            MANAGER = 2,
            ADMIN = 3;

    public static function getRoleMap(int $role = null)
    {
        $roles = [
            self::NORMAL_USER => 'User',
            self::MANAGER => 'Manager',
            self::ADMIN => 'Admin',
        ];

        return $roles[$role] ?? $role ?? $roles;
    }
}
