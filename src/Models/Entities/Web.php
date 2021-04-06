<?php

namespace WalkerChiu\MorphWeb\Models\Entities;

use WalkerChiu\Core\Models\Entities\LangTrait;
use WalkerChiu\Core\Models\Entities\UuidEntity;
use WalkerChiu\MorphImage\Models\Entities\ImageTrait;

class Web extends UuidEntity
{
    use LangTrait;
    use ImageTrait;

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = array())
    {
        $this->table = config('wk-core.table.morph-web.webs');
        $this->fillable = array_merge($this->fillable, [
            'morph_type', 'morph_id',
            'type',
            'serial',
            'target', 'url',
            'order'
        ]);

        parent::__construct($attributes);
    }

    /**
     * Get it's lang entity.
     *
     * @return Lang
     */
    public function lang()
    {
        if (config('wk-core.onoff.core-lang_core') || config('wk-morph-web.onoff.core-lang_core'))
            return config('wk-core.class.core.langCore');
        else
            return config('wk-core.class.morph-web.webLang');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function langs()
    {
        if (config('wk-core.onoff.core-lang_core') || config('wk-morph-web.onoff.core-lang_core'))
            return $this->langsCore();
        else
            return $this->hasMany(config('wk-core.class.morph-web.webLang'), 'morph_id', 'id');
    }

    /**
     * Get the owning morph model.
     */
    public function morph()
    {
        return $this->morphTo();
    }
}
