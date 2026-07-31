<?php

namespace App\Services;

use App\Models\Campaign;

class MarketingService
{
    public function createCampaign(string $name, array $attributes = []): Campaign
    {
        return Campaign::query()->create(array_merge([
            'name' => $name,
            'status' => 'draft',
        ], $attributes));
    }
}