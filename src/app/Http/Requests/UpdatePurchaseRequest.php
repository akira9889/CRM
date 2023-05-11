<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePurchaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'id' => 'required|integer',
            'status' => 'required|integer',
            'items' => 'required|array',
            'items.*.id' => 'required|integer',
            'items.*.name' => 'required|string',
            'items.*.price' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:0',
        ];
    }

    public function messages()
    {
        $items = $this->input('items', []);
        foreach ($items as $index => $item) {
            $itemId = $item['id'];
            $messages["items.$index.quantity.required"] = "商品ID $itemId の数量を指定してください。";
            $messages["items.$index.quantity.integer"] = "商品ID $itemId の数量は整数で入力してください。";
            $messages["items.$index.quantity.min"] = "商品ID $itemId の数量は少なくとも0以上である必要があります。";
        }

        $messages['status'] = 'ステータスを指定してください。';

        return $messages;
    }
}
