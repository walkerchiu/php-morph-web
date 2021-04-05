<?php

namespace WalkerChiu\MorphWeb\Models\Entities;

trait UserTrait
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function webs($type = null) {
        return $this->morphMany(config('wk-core.class.morph-web.web'), 'morph')
                    ->when($type, function ($query, $type) {
                                return $query->where('type', $type);
                            });
    }
}
