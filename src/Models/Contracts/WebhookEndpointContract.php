<?php

namespace CustomD\WebhookRegistry\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\HasMany;

interface WebhookEndpointContract
{

     /**
      * Get the events relationship
      */
    public function events(): HasMany;
}
