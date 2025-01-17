<?php

namespace Convoy\Transformers\Admin;

use Convoy\Models\Server;
use League\Fractal\TransformerAbstract;

class ServerTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Server $server)
    {
        return [
            'id' => $server->id,
            'uuidShort' => $server->uuidShort,
            'user_id' => $server->user_id,
            'node_id' => $server->node_id,
            'vmid' => $server->vmid,
            'name' => $server->name,
            'description' => $server->description,
            'status' => $server->status,
            'updated_at' => $server->updated_at,
            'created_at' => $server->created_at,
            'template' => $server->template,
            'owner' => [
                'id' => $server->owner->id,
                'email' => $server->owner->email,
                'name' => $server->owner->name,
            ],
            'node' => [
                'id' => $server->node->id,
                'name' => $server->node->name,
            ],
        ];
    }
}
