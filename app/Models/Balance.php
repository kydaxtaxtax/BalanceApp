<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
    use HasFactory;
    protected $fillable = ['amount', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function operations()
    {
        return $this->hasMany(Operation::class);
    }
}
