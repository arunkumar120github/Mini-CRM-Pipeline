<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'email', 'phone', 'status', 'user_id'];
    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();
        static::creating(fn ($lead) => $lead->id = $lead->id ?? Str::uuid());
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
