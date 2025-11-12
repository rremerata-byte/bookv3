<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'fullname',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    // Relationship with books (if admin manages books)
    public function managedBooks()
    {
        return $this->hasMany(Book::class, 'added_by');
    }

    // Helper methods for role checking
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isGuard()
    {
        return $this->role === 'guard';
    }
}
