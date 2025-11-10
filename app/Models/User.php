<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property int $id
 * @property string $fullname
 * @property string $email
 * @property string $studentid
 * @property string|null $courseSection
 * @property string|null $gender
 * @property string $password
 * @property string|null $phone_number
 * @property string $role
 * @property \Illuminate\Support\Carbon|null $last_login
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $course
 */
class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'fullname',
        'email',
        'studentid',
        'courseSection',
        'gender',
        'password',
        'phone_number',
        'role',
        'last_login'
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = [
        'course',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Accessor for course attribute (maps to courseSection)
    public function getCourseAttribute()
    {
        return $this->courseSection;
    }

    // Relationship with borrowed books could be added here if needed
    public function borrowedBooks()
    {
        return $this->belongsToMany(Book::class, 'book_user', 'user_id', 'book_id')
            ->withTimestamps()
            ->withPivot('borrow_date', 'return_date', 'status');
    }

    // Relationship with book requests (borrow/reserve)
    public function bookRequests()
    {
        return $this->hasMany(BookRequest::class, 'user_id');
    }

    // Relationship with notifications
    public function notificationsList()
    {
        return $this->hasMany(Notification::class, 'user_id')->orderByDesc('created_at');
    }
}