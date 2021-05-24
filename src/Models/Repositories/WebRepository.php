<?php

namespace WalkerChiu\MorphWeb\Models\Repositories;

use Illuminate\Support\Facades\App;
use WalkerChiu\Core\Models\Forms\FormTrait;
use WalkerChiu\Core\Models\Repositories\Repository;
use WalkerChiu\Core\Models\Repositories\RepositoryTrait;
use WalkerChiu\MorphImage\Models\Repositories\ImageRepositoryTrait;

class WebRepository extends Repository
{
    use FormTrait;
    use RepositoryTrait;
    use ImageRepositoryTrait;

    protected $entity;

    public function __construct()
    {
        $this->entity = App::make(config('wk-core.class.morph-web.web'));
    }

    /**
     * @param String  $code
     * @param Array   $data
     * @param Int     $page
     * @param Int     $nums per page
     * @param Boolean $is_enabled
     * @param Boolean $toArray
     * @return Array|Collection
     */
    public function list(String $code, Array $data, $page = null, $nums = null, $is_enabled = null, $toArray = true)
    {
        $this->assertForPagination($page, $nums);

        $entity = $this->entity;
        if ($is_enabled === true)      $entity = $entity->ofEnabled();
        elseif ($is_enabled === false) $entity = $entity->ofDisabled();

        $data = array_map('trim', $data);
        $records = $entity->with(['langs' => function ($query) use ($code) {
                                $query->ofCurrent()
                                      ->ofCode($code);
                            }])
                          ->when($data, function ($query, $data) {
                              return $query->unless(empty($data['id']), function ($query) use ($data) {
                                          return $query->where('id', $data['id']);
                                      })
                                      ->unless(empty($data['morph_type']), function ($query) use ($data) {
                                          return $query->where('morph_type', $data['morph_type']);
                                      })
                                      ->unless(empty($data['morph_id']), function ($query) use ($data) {
                                          return $query->where('morph_id', $data['morph_id']);
                                      })
                                      ->unless(empty($data['type']), function ($query) use ($data) {
                                          return $query->where('type', $data['type']);
                                      })
                                      ->unless(empty($data['serial']), function ($query) use ($data) {
                                          return $query->where('serial', $data['serial']);
                                      })
                                      ->unless(empty($data['target']), function ($query) use ($data) {
                                          return $query->where('target', $data['target']);
                                      })
                                      ->unless(empty($data['url']), function ($query) use ($data) {
                                          return $query->where('url', 'LIKE', $data['url']."%");
                                      })
                                      ->unless(empty($data['name']), function ($query) use ($data) {
                                          return $query->whereHas('langs', function($query) use ($data) {
                                              $query->ofCurrent()
                                                    ->where('key', 'name')
                                                    ->where('value', 'LIKE', "%".$data['name']."%");
                                          });
                                      })
                                      ->unless(empty($data['description']), function ($query) use ($data) {
                                          return $query->whereHas('langs', function($query) use ($data) {
                                              $query->ofCurrent()
                                                    ->where('key', 'description')
                                                    ->where('value', 'LIKE', "%".$data['description']."%");
                                          });
                                      });
                            })
                          ->orderBy('order', 'ASC')
                          ->get()
                          ->when(is_integer($page) && is_integer($nums), function ($query) use ($page, $nums) {
                              return $query->forPage($page, $nums);
                          });
        if ($toArray) {
            $list = [];
            foreach ($records as $record) {
                $data = $record->toArray();
                array_push($list,
                    array_merge($data, [
                        'name'        => $record->findLangByKey('name'),
                        'description' => $record->findLangByKey('description')
                    ])
                );
            }

            return $list;
        } else {
            return $records;
        }
    }

    /**
     * @param Web $entity
     * @param Array|String $code
     * @return Array
     */
    public function show($entity, $code)
    {
    }
}
