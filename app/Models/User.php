<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'huan_luyen_vien';

    /**
     * The column name of the "username" for authentication.
     *
     * @var string
     */
    public function getAuthIdentifierName()
    {
        return 'ma_hoi_vien';
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'ma_hoi_vien',
        'ho_va_ten',
        'ngay_thang_nam_sinh',
        'ma_clb',
        'ma_don_vi',
        'quyen_so',
        'cap_dai_id',
        'gioi_tinh',
        'email',
        'password',
        'role',
        'phone',
        'photo_url',
        'images',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            // 'password' => 'hashed',
            'is_active' => 'boolean',
            'ngay_thang_nam_sinh' => 'date',
        ];
    }

    /**
     * Get role name for authorization
     * Maps database role ('owner'/'admin') to application role ('super_admin'/'admin_ketoan')
     *
     * @return string
     */
    public function getRoleName()
    {
        if ($this->role === 'owner') {
            return 'owner';
        }

        if ($this->role === 'admin') {
            return 'admin';
        }

        return $this->role ?? 'admin';
    }

    /**
     * Check if user is super admin (owner)
     *
     * @return bool
     */
    public function isSuperAdmin()
    {
        return $this->role === 'owner';
    }
}
