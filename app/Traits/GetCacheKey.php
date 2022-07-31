<?php

namespace App\Traits;

trait GetCacheKey
{
    /**
     * Builds a cache key for the model for different scenarios
     *
     * @return string
     */
    public function cacheKey(string $type): string
    {
        $className = class_basename($this);
        return "{$className}-{$this->id}-{$type}";
    }
}
