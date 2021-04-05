<?php

namespace WalkerChiu\MorphWeb\Models\Forms;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use WalkerChiu\Core\Models\Forms\FormRequest;

class WebFormRequest extends FormRequest
{
    /**
     * @Override Illuminate\Foundation\Http\FormRequest::getValidatorInstance
     */
    protected function getValidatorInstance()
    {
        $request = Request::instance();
        $data = $this->all();
        if ($request->isMethod('put') && empty($data['id']) && isset($request->id)) {
            $data['id'] = (int) $request->id;
            $this->getInputSource()->replace($data);
        }

        return parent::getValidatorInstance();
    }

    public function attributes()
    {
        return [
            'morph_type'  => trans('php-morph-web::system.morph_type'),
            'morph_id'    => trans('php-morph-web::system.morph_id'),
            'type'        => trans('php-morph-web::system.type'),
            'serial'      => trans('php-morph-web::system.serial'),
            'target'      => trans('php-morph-web::system.target'),
            'url'         => trans('php-morph-web::system.url'),
            'order'       => trans('php-morph-web::system.order'),
            'is_enabled'  => trans('php-morph-web::system.is_enabled'),

            'name'        => trans('php-morph-web::system.name'),
            'description' => trans('php-morph-web::system.description')
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'morph_type'  => 'required|string',
            'morph_id'    => 'required|integer|min:1',
            'type'        => 'required|string|max:15',
            'serial'      => '',
            'target'      => 'required|string|max:10',
            'url'         => 'required|active_url',
            'order'       => 'nullable|numeric|min:0',
            'is_enabled'  => 'required|boolean',

            'name'        => 'required|string|max:255',
            'description' => ''
        ];

        $request = Request::instance();
        if ($request->isMethod('put') && isset($request->id)) {
            $rules = array_merge($rules, ['id' => ['required','integer','min:1','exists:'.config('wk-core.table.morph-web.webs').',id']]);
        } elseif ($request->isMethod('post')) {
            $rules = array_merge($rules, ['id' => ['nullable','integer','min:1','exists:'.config('wk-core.table.morph-web.webs').',id']]);
        }

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'id.required'         => trans('php-core::validation.required'),
            'id.integer'          => trans('php-core::validation.integer'),
            'id.min'              => trans('php-core::validation.min'),
            'id.exists'           => trans('php-core::validation.exists'),
            'morph_type.required' => trans('php-core::validation.required'),
            'morph_type.string'   => trans('php-core::validation.string'),
            'morph_id.required'   => trans('php-core::validation.required'),
            'morph_id.integer'    => trans('php-core::validation.integer'),
            'morph_id.min'        => trans('php-core::validation.min'),
            'type.required'       => trans('php-core::validation.required'),
            'type.max'            => trans('php-core::validation.max'),
            'target.required'     => trans('php-core::validation.required'),
            'target.max'          => trans('php-core::validation.max'),
            'url.required'        => trans('php-core::validation.required'),
            'url.url'             => trans('php-core::validation.active_url'),
            'order.numeric'       => trans('php-core::validation.numeric'),
            'order.min'           => trans('php-core::validation.min'),
            'is_enabled.required' => trans('php-core::validation.required'),
            'is_enabled.boolean'  => trans('php-core::validation.boolean'),

            'name.required'       => trans('php-core::validation.required'),
            'name.string'         => trans('php-core::validation.string'),
            'name.max'            => trans('php-core::validation.max')
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $data = $validator->getData();
            if (isset($data['morph_type']) && isset($data['morph_id'])) {
                if ( config('wk-morph-web.onoff.site') && !empty(config('wk-core.class.site.site')) && $data['morph_type'] == config('wk-core.class.site.site') ) {
                    $result = DB::table(config('wk-core.table.site.sites'))
                                ->where('id', $data['morph_id'])
                                ->exists();
                    if (!$result)
                        $validator->errors()->add('morph_id', trans('php-core::validation.exists'));

                } elseif ( config('wk-morph-web.onoff.group') && !empty(config('wk-core.class.group.group')) && $data['morph_type'] == config('wk-core.class.group.group') ) {
                    $result = DB::table(config('wk-core.table.group.groups'))
                                ->where('id', $data['morph_id'])
                                ->exists();
                    if (!$result)
                        $validator->errors()->add('morph_id', trans('php-core::validation.exists'));

                } elseif ( config('wk-morph-web.onoff.account') && !empty(config('wk-core.class.account.profile')) && $data['morph_type'] == config('wk-core.class.account.profile') ) {
                    $result = DB::table(config('wk-core.table.account.profiles'))
                                ->where('id', $data['morph_id'])
                                ->exists();
                    if (!$result)
                        $validator->errors()->add('morph_id', trans('php-core::validation.exists'));

                } elseif ( !empty(config('wk-core.class.user')) && $data['morph_type'] == config('wk-core.class.user') ) {
                    $result = DB::table(config('wk-core.table.user'))
                                ->where('id', $data['morph_id'])
                                ->exists();
                    if (!$result)
                        $validator->errors()->add('morph_id', trans('php-core::validation.exists'));
                }
            }
        });
    }
}
