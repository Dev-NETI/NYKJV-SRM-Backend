<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
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
        'email_verified',
        'password',
        'picture',
        'provider_id',
        'provider_token',
        'slug',
        'company_id',
        'department_id',
        'supplier_id',
        'suffix',
        'contact_number',
        'is_active',
    ];

    protected $with = ['role_users', 'roles'];

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

    //accessor
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
