<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'user_name',
        'role_name',
        'action',
        'module',
        'description',
        'ip_address',
        'created_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function log(?User $user, string $action, string $module, string $description = '')
    {
        return self::create([
            'user_id' => $user?->id,
            'user_name' => $user?->name ?? 'System',
            'role_name' => $user?->role?->display_name ?? 'Guest',
            'action' => $action,
            'module' => $module,
            'description' => $description,
            'ip_address' => request()->ip(),
            'created_at' => now(),
        ]);
    }
}
