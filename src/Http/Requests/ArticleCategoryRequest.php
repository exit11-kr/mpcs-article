<?php

namespace Mpcs\Article\Http\Requests;

use Mpcs\Core\Traits\RequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class ArticleCategoryRequest extends FormRequest
{
    use RequestTrait;

    public function rules($params = null)
    {
        $info = $this->getRequestInfo($params);
        if ($info->rules) {
            // 요청 파라미터로 규칙이 바뀌어도 HTML 태그 문자 차단은 유지
            return array_merge($info->rules, [
                'name' => 'not_regex:/[<>]/',
                'description' => 'nullable|not_regex:/[<>]/',
            ]);
        }

        $rules = [
            'POST' => [
                'name' => 'required|max:255|not_regex:/[<>]/',
                'description' => 'nullable|max:512|not_regex:/[<>]/',
            ],
            'PUT' => [
                'name' => 'sometimes|required|max:255|not_regex:/[<>]/',
                'description' => 'nullable|max:512|not_regex:/[<>]/',
            ],
        ];

        return $rules[$this->method() === 'PATCH' ? 'PUT' : $this->method()] ?? [];
    }
}
