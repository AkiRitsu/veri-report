<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\HasApiTokens;
use Mailjet\Client;
use Mailjet\Resources;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'dark_mode',
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
        'password' => 'hashed',
    ];

    /**
     * Get the reports for the user.
     */
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    /**
     * Send a password reset notification through Mailjet directly.
     */
    public function sendPasswordResetNotification($token)
    {
        $resetUrl = url(route('password.reset', [
            'token' => $token,
            'email' => $this->email,
        ], false));

        $client = new Client(
            config('services.mailjet.api_key'),
            config('services.mailjet.api_secret'),
            true,
            ['version' => 'v3.1']
        );

        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => config('services.mailjet.from_email'),
                        'Name' => config('services.mailjet.from_name'),
                    ],
                    'To' => [
                        [
                            'Email' => $this->email,
                            'Name' => $this->name,
                        ],
                    ],
                    'Subject' => 'Reset your Troubleshooting Report System password',
                    'TextPart' => "Hi {$this->name},\n\nUse the following link to reset your password:\n{$resetUrl}\n\nIf you did not request this, you can safely ignore this email.",
                    'HTMLPart' => "<p>Hi {$this->name},</p><p>Use the following link to reset your password:</p><p><a href=\"{$resetUrl}\">Reset Password</a></p><p>If you did not request this, you can safely ignore this email.</p>",
                ],
            ],
        ];

        $response = $client->post(Resources::$Email, ['body' => $body]);

        if (!$response->success()) {
            Log::error('Mailjet password reset failed', ['response' => $response->getData()]);
        }
    }
}

