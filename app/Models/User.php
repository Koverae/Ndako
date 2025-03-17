<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Company\Company;
use App\Notifications\CustomVerifyEmailNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use App\Models\Team\Team;
use Illuminate\Database\Eloquent\Builder;
use Modules\Settings\Models\Identity\IdentityVerification;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

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
            'phone_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // public function sendEmailVerificationNotification()
    // {
    //     $this->notify(new CustomVerifyEmailNotification());
    // }



    public static function boot() {
        parent::boot();

        static::created(function ($model) {
            $model->generateAvatar();
        });
    }

    public function scopeIsCompany(Builder $query, $company_id)
    {
        return $query->where('company_id', $company_id);
    }

    // Get Company
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    // Get Team
    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function identityVerification()
    {
        return $this->hasOne(IdentityVerification::class);
    }

    /**
     * Route notifications for the Vonage channel.
     */
    public function routeNotificationForVonage(Notification $notification): string
    {
        return $this->phone;
    }

    // Generate OTP code
    public function generateTwoFactorCode(): void {
        $this->timestamps = false;  // Prevent updating the 'updated_at' column
        $this->two_factor_code = rand(100000, 999999);  // Generate a random code
        $this->two_factor_expires_at = now()->addMinutes(10);  // Set expiration time
        $this->save();
    }
    // Regenerate OTP code
    public function resetTwoFactorCode(): void {
        $this->timestamps = false;
        $this->two_factor_code = null;
        $this->two_factor_expires_at = null;
        $this->phone_verified_at = now();
        $this->save();
    }
    // Use Default Avatar
    public function avatar(){
        return $this->id.'_avatar';
    }

    // Generate User Avatar
    public function generateAvatar(){

        // Define the avatar directory and ensure it exists
        $avatarDir = 'storage/avatars';
        $publicAvatarDir = public_path($avatarDir);

        if (!file_exists($publicAvatarDir)) {
            mkdir($publicAvatarDir, 0777, true); // Create the directory with the correct permissions
        }

        // Generate the image

        $firstName = explode(' ', trim($this->first_name))[0];
        $name = explode(' ', trim($this->name))[0];

        $full_name = $firstName.' '.$name;
        $words = explode(' ', $full_name);
        $initials = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));

        $bgColor = '#' . substr(md5($full_name), 0, 6); // Use a unique color based on the name
        $textColor = '#ffffff'; // White text color

        $image = imagecreate(200, 200);
        $bg = imagecolorallocate($image, hexdec(substr($bgColor, 1, 2)), hexdec(substr($bgColor, 3, 2)), hexdec(substr($bgColor, 5, 2)));
        $text = imagecolorallocate($image, hexdec(substr($textColor, 1, 2)), hexdec(substr($textColor, 3, 2)), hexdec(substr($textColor, 5, 2)));
        imagefill($image, 0, 0, $bg);

        $fontPath = public_path('assets/fonts/arial/arialceb.ttf');
        if (!file_exists($fontPath)) {
            die('Font file does not exist: ' . $fontPath);
        }

        $fontSize = 75;
        $angle = 0;
        $x = 50; // Adjust the X coordinate
        $y = 150; // Adjust the Y coordinate

        imagettftext($image, $fontSize, $angle, $x, $y, $text, $fontPath, $initials);

        // Save the image to a file
        $avatarFilename = $this->id . '_avatar.png';
        $avatarPath = $avatarDir . '/' . $avatarFilename;
        imagepng($image, public_path($avatarPath));
        imagedestroy($image);

        // Update the user record with the avatar path
        $this->avatar = $avatarFilename;
        $this->save();

        // Provide feedback
        echo "Avatar created successfully: " . $avatarPath;
    }
}
