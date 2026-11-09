<?php

namespace Mpcs\Article\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Mpcs\Core\Traits\RequestTrait;

class ArticleRequest extends FormRequest
{
    use RequestTrait;

    public function rules($params = null)
    {
        $info = $this->getRequestInfo($params);
        if ($info->rules) {
            // 요청 파라미터로 규칙이 바뀌어도 HTML 태그 문자 차단은 유지
            return array_merge($info->rules, [
                'title' => 'not_regex:/[<>]/',
                'summary' => 'nullable|string|not_regex:/[<>]/',
            ]);
        }

        $rules = [
            'POST' => [
                'article_category_id' => 'required',
                'released_at' => 'required',
                'title' => 'required|max:100|not_regex:/[<>]/',
                'summary' => 'nullable|string|not_regex:/[<>]/',
            ],
            'PUT' => [
                'article_category_id' => 'sometimes|required',
                'released_at' => 'sometimes|required',
                'title' => 'sometimes|required|max:100|not_regex:/[<>]/',
                'summary' => 'nullable|string|not_regex:/[<>]/',
            ],
        ];

        return $rules[$this->method() === 'PATCH' ? 'PUT' : $this->method()] ?? [];
    }
}
