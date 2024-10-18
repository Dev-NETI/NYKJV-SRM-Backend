<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'f_name',
        'm_name',
        'l_name',
        'suffix',
        'email',
        'password',
        'slug',
        'company_id',
        'department_id',
        'supplier_id',
        'suffix',
        'contact_number',
        'is_active',
    ];

    protected $with = ['role_users', 'roles'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $lastId = $model::orderBy('id', 'DESC')->first();
            $slug = $lastId != NULL ? encrypt($lastId->id + 1) : encrypt(1);
            $model->slug = $slug;
            $model->is_active = 1;
            $model->modified_by = 'system';
        });

        static::updating(function ($model) {
            $model->modified_by = Auth::user()->FullName;
        });
    }


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function supplier()
    {
        $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function getFullNameAttribute()
    {
        return $this->firstname . " " . $this->lastname;
    }

    public function role_users()
    {
        return $this->hasMany(RoleUser::class);
    }

    // You might also want to add a direct relationship to roles
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_users');
    }
}
