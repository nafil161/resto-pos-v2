<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\Enums\Status;
use Illuminate\Support\Str;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'phone',
        'branch_id',
        'country_code',
        'is_guest',
        'status',
        'email_verified_at'
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
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'id'                => 'integer',
            'name'              => 'string',
            'email'             => 'string',
            'username'          => 'string',
            'phone'             => 'string',
            'branch_id'         => 'integer',
            'country_code'      => 'string',
            'is_guest'          => 'integer',
            'status'            => 'integer',
            'email_verified_at' => 'datetime',
        ];
    }

    protected $dates = ['deleted_at'];

    protected $appends = ['myrole'];

    public function appSubscriptions(): HasMany
    {
        return $this->hasMany(UserApp::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }

    public function reminders(): HasMany
    {
        return $this->hasMany(Reminder::class);
    }

    public function todos(): HasMany
    {
        return $this->hasMany(Todo::class);
    }

    public function hasApp(string $slug): bool
    {
        return $this->appSubscriptions()->where('app_slug', $slug)->exists();
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    protected static function boot()
    {
        parent::boot();
        // static::addGlobalScope(new BranchScope());

        static::creating(function ($user) {
            if (empty($user->username) && !empty($user->email)) {
                $baseUsername = Str::before($user->email, '@');

                $username = $baseUsername;
                $counter = 1;

                // Ensure username is unique
                while (self::where('username', $username)->exists()) {
                    $username = $baseUsername . $counter;
                    $counter++;
                }

                $user->username = $username;
            }
        });

        static::updating(function ($user) {
            if ($user->id === 1) {
                $user->status = Status::ACTIVE;
            }
        });
    }

    public function getMyRoleAttribute()
    {
        return $this->roles?->pluck('id', 'id')?->first();
    }

    public function getrole(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Role::class, 'id', 'myrole');
    }

    public function addresses(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function getFirstNameAttribute(): string
    {
        $name = explode(' ', $this->name, 2);
        return $name[0];
    }

    public function getLastNameAttribute(): string
    {
        $name = explode(' ', $this->name, 2);
        return !empty($name[1]) ? $name[1] : '';
    }

    public function getImageAttribute(): string
    {
        if (!empty($this->getFirstMediaUrl('profile'))) {
            return asset($this->getFirstMediaUrl('profile'));
        }
        return asset('images/default/profile.png');
    }

    public function orders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Order::class, 'user_id', 'id');
    }

    public function messages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MessageHistory::class, 'user_id', 'id')->where('is_read', Ask::NO);
    }

    public static function defaultCustomer()
    {
        return self::firstOrCreate(
            ['username' => 'default-customer'],
            ['name' => 'Walking Customer']
        );
    }
}
