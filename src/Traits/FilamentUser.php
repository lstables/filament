<?php

namespace Filament\Traits;

use Filament\Notifications\ResetPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;
use Thomaswelton\LaravelGravatar\Facades\Gravatar;
use Spatie\Permission\Traits\HasRoles;
use Plank\Metable\Metable;
use Filament\Traits\ManipulatesModels;
use Filament\Models\Media;
use Filament;

trait FilamentUser 
{
    use ManipulatesModels, HasRoles, Metable;

    /**
     * Initialize the trait.
     * 
     * @return void
     */
    public function initializeFilamentUser()
    {
        $this->mergeFillable([
            'is_super_admin', 
            'avatar',
        ]);

        $this->mergeCasts([
            'avatar' => 'array',
            'is_super_admin' => 'boolean',
            'last_login_at' => 'datetime',
        ]);
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::deleted(function ($user) {
            Media::where('mediable_id', $user->id)->get()->each(function($media) {
                $media->delete();
            });
        });
    }

    /**
     * Get the user's avatar.
     * 
     * @param int $size
     * @return string
     */
    public function avatar($size = 48)
    {
        if ($this->avatar) {
            $value = collect(Media::find($this->avatar)->pluck('value')->first());
            return Filament::image($value->get('path'), [
                'w' => 48,
                'h' => 48,
                'fit' => 'crop',
                'dpr' => 2,
            ]);
        }

        return Gravatar::src($this->email, (int) $size);
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    /**
     * Hash the users password.
     *
     * @param  string  $pass
     * @return void
     */
    public function setPasswordAttribute($pass)
    {
        $this->attributes['password'] = Hash::make($pass);
    }

    /**
     * Get all of the user's media.
     */
    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }
}