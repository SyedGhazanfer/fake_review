<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function store(Request $request){
        try{
            $input = $request->only('review', 'product_id');
            $validate = Validator::make($input, Review::$reviewRule);
            if($validate->fails()) return $this->sendError($validate->errors()->first(), 400);

            $input['user_id'] = auth()->id();
            $reviews = Review::create($input);
            return $this->sendRessponse($reviews, "Thank you for your review.");

        }catch(Exception $e){
            Log::debug('Error in' . __FUNCTION__ . ' function in ' .__CLASS__. ' class, check details.');
            Log::error($e->getMessage());
            return $this->sendError($e->getMessage());
        }
    }
}
