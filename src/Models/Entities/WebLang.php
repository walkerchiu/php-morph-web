<?php

namespace WalkerChiu\MorphWeb\Models\Entities;

use WalkerChiu\Core\Models\Entities\Lang;

class WebLang extends Lang
{
    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = array())
    {
        $this->table = config('wk-core.table.morph-web.webs_lang');

        parent::__construct($attributes);
    }
}
