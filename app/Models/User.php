<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Rappasoft\LaravelAuthenticationLog\Traits\AuthenticationLoggable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, SoftDeletes, AuthenticationLoggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'status',
        'image',
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

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class);
    }

    public function adminlte_profile_url(){
        return "/profile";
    }

    public function adminlte_image()
    {
        $userImage = \Auth::user()->image;

        if ($userImage) {
            // Check if the image URL starts with 'https://'
            if (strpos($userImage, 'https://') === 0) {
                // If it starts with 'https://', return the URL directly
                return $userImage;
            } else {
                // Otherwise, use the default public path
                return asset('uploads/images/profile/' . $userImage);
            }
        } else {
            // Default image if no user image is set
            return asset('vendor/adminlte/dist/img/gravtar.jpg');
        }
    }

    public function profileImage()
    {
        $userImage = $this->image;

        if (!empty($userImage)) {
            return asset('uploads/images/profile/' . $userImage);
        }
        else{
            return asset('vendor/adminlte/dist/img/gravtar.jpg');
        }
    }

    //frontend user image for booking
    public function employeeImage()
    {
        $userImage = $this->image;

        if (!empty($userImage)) {
            return asset('uploads/images/profile/' . $userImage);
        }
        else{
            return asset('vendor/adminlte/dist/img/gravtar.jpg');
        }
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

}
