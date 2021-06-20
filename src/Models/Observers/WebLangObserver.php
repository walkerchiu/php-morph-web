<?php

namespace WalkerChiu\MorphWeb\Models\Observers;

class WebLangObserver
{
    /**
     * Handle the entity "retrieved" event.
     *
     * @param  $entity
     * @return void
     */
    public function retrieved($entity)
    {
        //
    }

    /**
     * Handle the entity "creating" event.
     *
     * @param  $entity
     * @return void
     */
    public function creating($entity)
    {
        //
    }

    /**
     * Handle the entity "created" event.
     *
     * @param  $entity
     * @return void
     */
    public function created($entity)
    {
        $query =
            config('wk-core.class.morph-web.webLang')::withTrashed()
                ->where('morph_type', $entity->morph_type)
                ->where('morph_id', $entity->morph_id)
                ->where('code', $entity->code)
                ->where('key', $entity->key)
                ->where('id', '<>', $entity->id);

        if (config('wk-morph-web.soft_delete') && (config('wk-core.lang_log') || config('wk-morph-web.lang_log')))
            $query->update(['is_current' => 0]);
        else
            $query->forceDelete();
    }

    /**
     * Handle the entity "updating" event.
     *
     * History should not be modify.
     *
     * @param  $entity
     * @return void
     */
    public function updating($entity)
    {
        return false;
    }

    /**
     * Handle the entity "updated" event.
     *
     * @param  $entity
     * @return void
     */
    public function updated($entity)
    {
        //
    }

    /**
     * Handle the entity "saving" event.
     *
     * @param  $entity
     * @return void
     */
    public function saving($entity)
    {
        //
    }

    /**
     * Handle the entity "saved" event.
     *
     * @param  $entity
     * @return void
     */
    public function saved($entity)
    {
        //
    }

    /**
     * Handle the entity "deleting" event.
     *
     * @param  $entity
     * @return void
     */
    public function deleting($entity)
    {
        //
    }

    /**
     * Handle the entity "deleted" event.
     *
     * @param  $entity
     * @return void
     */
    public function deleted($entity)
    {
        //
    }

    /**
     * Handle the entity "restoring" event.
     *
     * @param  $entity
     * @return void
     */
    public function restoring($entity)
    {
        //
    }

    /**
     * Handle the entity "restored" event.
     *
     * @param  $entity
     * @return void
     */
    public function restored($entity)
    {
        //
    }
}
