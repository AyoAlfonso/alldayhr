<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable, EntrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'image', 'firstname', 'lastname', 'remember_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    // protected $appends = [
    //     'profile_image_url'
    // ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function education()
    {
        return $this->hasMany(CandidateEducation::class, 'candidate_id', 'candidate_id');
    }

    public function work()
    {
        return $this->hasMany(CandidateWorkHistory::class, 'candidate_id', 'candidate_id');
    }

    public function documents()
    {
        return $this->hasMany(CandidateDocument::class, 'candidate_id', 'candidate_id');
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
    public function getProfileImageUrlAttribute()
    {
        if (is_null($this->image)) {
            return asset('avatar.png');
        }
        return asset('user-uploads/profile/' . $this->image);
    }

    public function role()
    {
        return $this->hasOne(RoleUser::class, 'user_id');
    }

    public function candidate()
    {
        return $this->hasOne(CandidateInfo::class, 'user_id','id');
    }

    public static function allAdmins($exceptId = NULL)
    {
        $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('users.id', 'users.name', 'users.email', 'users.created_at')
            ->where('roles.name', '!=', 'candidate');

        if (!is_null($exceptId)) {
            $users->where('users.id', '<>', $exceptId);
        }

        return $users->get();
    }

    public static function allCandidates($exceptId = NULL)
    {
        $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('users.id', 'users.name', 'users.email', 'users.created_at', 'users.firstname', 'users.lastname')
            ->where('roles.name', 'candidate')
            ->with(['user','education', 'work','documents','documents.type'])
            ->paginate(10);
            return $users;

        if(!is_null($exceptId)){
            $users->where('users.id', '<>', $exceptId);
        }
        return $users->get();
    }

    public static function getUser($credentials)
    {
        if ($user = self::where('email',$credentials['email'])->first()) {
            if(Hash::check($credentials['password'], $user->password))
                return $user;
        }
        return false;
    }

}
