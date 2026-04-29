<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends Controller
{

    private function sendResponse($success, $data = null, $error = null)
    {
        return response()->json(compact('success', 'data', 'error'));
    }
    public function artworkVersion(Request $request) // exercise = 1
    {
        $validator = validator($request->all(), [
            'input' => 'required|array',
            'input.*.id' => 'required|integer',
            'input.*.approved' => 'required|boolean',
            'input.*.rejected' => 'required|boolean',
            'input.*.time|integer'
        ]);

        if($validator->fails()) {
            return $this->sendResponse(false, null, $validator->errors()->first());
        }
        $input = $request->input('input');
        $array = array_filter($input, function ($value) {
            return $value['approved'] == true && $value['rejected'] == false;
        });
        $array = array_values($array);
        usort($array, function($a, $b) {
            return $a['time'] < $b['time'];
        });
        usort($array, function($a, $b) {
            return $a['id'] < $b['id'] && $a['time'] == $b['time'];
        });
        $first = array_first($array);
        $result = [
            "id" => $first['id'] ?? null,
        ];
        return $this->sendResponse(true, $result);
    }

    public function tierPricing(Request $request)
    {
        $validator = validator($request->all(), [
            'input' => 'required|array',
            'input.quantity' => 'required',
            'input.tiers' => 'required|array',
            'input.tiers.*.min' => 'required|int|distinct',
            'input.tiers.*.price' => 'required|numeric'
        ], [
            'input.array' => 'The input field must be an object.'
        ]);

        if($validator->fails()) {
            return $this->sendResponse(false, null, $validator->errors()->first());
        }
        $quantity = $request->input('input.quantity');
        $tiers = $request->collect('input.tiers');
        $selectedTier = $tiers->sortByDesc('min')->where('min', '<=', $quantity)->first();
        if(!$selectedTier) {
            return $this->sendResponse(false, null, 'There is no tier applied against the input quantity.');
        }
        $result = [
            'price' => $selectedTier['price']
        ];
        return $this->sendResponse(true, $result);
    }

    public function cartValidator(Request $request)
    {
        $validator = validator($request->all(), [
            'input' => 'required|array',
            'input.*.id' => 'required|int',
            'input.*.required' => 'required|boolean',
            'input.*.done' => 'required|boolean'
        ]);

        if($validator->fails()) {
            return $this->sendResponse(false, null, $validator->errors()->first());
        }

        $input = $request->collect('input');

        $invalid_items = $input->unique('id')->filter(function($value) {
            return $value['required'] == true && $value['done'] == false;
        })->values();
        $invalid_item_ids = $invalid_items->pluck('id')->toArray();
        $isValid = $invalid_items->count() < 1;

        return $this->sendResponse(true, [
            'valid' => $isValid,
            'invalid_items' => $invalid_item_ids
        ]);
    }
}
