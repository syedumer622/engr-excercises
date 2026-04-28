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
}
