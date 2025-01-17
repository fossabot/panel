<?php

namespace Convoy\Models;

use Convoy\Casts\MegabytesAndBytes;
use Convoy\Enums\Servers\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Server extends Model
{
    use HasFactory;

    protected $casts = [
        'memory' => MegabytesAndBytes::class,
        'disk' => MegabytesAndBytes::class,
        'bandwidth_limit' => MegabytesAndBytes::class,
    ];

    protected $guarded = [
        'id',
        'updated_at',
        'created_at',
    ];

    public static $validationRules = [
        'type' => 'sometimes|in:new,existing',
        'name' => 'required|string|min:1|max:40',
        'node_id' => 'required|exists:nodes,id',
        'user_id' => 'required|exists:users,id',
        'vmid' => 'required|numeric|min:100|max:999999999',
        'status' => 'nullable|string',
        'installing' => 'sometimes|boolean',
        'addresses' => 'sometimes|array',
        'addresses.*' => 'exists:ip_addresses,id',
        'cpu' => 'required|numeric|min:1',
        'memory' => 'required|numeric|min:16777216',
        'disk' => 'required|numeric|min:1',
        'snapshot_limit' => 'present|nullable|integer|min:0',
        'backup_limit' => 'present|nullable|integer|min:0',
        'bandwidth_limit' => 'present|nullable|integer|min:0',
        'template' => 'required_if:type,existing|boolean',
        'visible' => 'required_with:template|boolean',
        'template_id' => 'required_if:type,new|exists:templates,id',
    ];

    public function node()
    {
        return $this->belongsTo(Node::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function addresses()
    {
        return $this->hasMany(IPAddress::class);
    }

    public function template()
    {
        return $this->hasOne(Template::class);
    }

    /**
     * Returns all of the activity log entries where the server is the subject.
     */
    public function activity(): MorphToMany
    {
        return $this->morphToMany(ActivityLog::class, 'subject', 'activity_log_subjects');
    }

    public function isInstalled(): bool
    {
        return $this->status !== Status::INSTALLING->value;
    }

    public function isSuspended(): bool
    {
        return $this->status === Status::SUSPENDED->value;
    }

    public function getRouteKeyName(): string
    {
        return 'id';
    }
}
