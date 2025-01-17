<?php

namespace Convoy\Transformers\Application;

use Convoy\Enums\Network\AddressType;
use Convoy\Models\IPAddress;
use League\Fractal\TransformerAbstract;

class AddressTransformer extends TransformerAbstract
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
    public function transform(IPAddress $address)
    {
        $properties = [
            'id' => $address->id,
            'address' => $address->address,
            'cidr' => $address->cidr,
            'gateway' => $address->gateway,
            'node_id' => $address->node_id,
            'server_id' => $address->server_id,
        ];

        if ($address->type === AddressType::IPV4->value) {
            $properties['mac_address'] = $address->mac_address;
        }

        return $properties;
    }
}
