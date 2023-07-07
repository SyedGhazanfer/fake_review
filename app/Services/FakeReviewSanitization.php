<?php

namespace App\Services;

use App\Models\Review;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Sentiment\Analyzer;

class FakeReviewSanitization{
    private static $hoursForGettingReviews = '-2'; // This value for getting reviews before 2 Hours. Note: value in negative number
    private static $userQuantity = 2; //This Number is for allowed user per execution.
    private function __construct(){
    }

    public static function handle(){
        $userIds = [];
        $reviews = self::getDataForSanitization();
        $analyzer = new Analyzer();
        foreach($reviews as $review){
            if(!in_array($review->user_id, $userIds)) array_push($userIds, $review->user_id);
            $reviewCounts = self::getReviewCount($review->user_id);
            foreach($reviewCounts as $reviewCount){
                if($reviewCount->total_review > self::$userQuantity) self::changeReviewSanitization($review->user_id);
                $isSuspicious = self::isUserHasSuspiciousReview($review->user_id);
                if($isSuspicious > 0) self::changeReviewSanitization($review->user_id);
                $sentiment = $analyzer->getSentiment($review->review);
                if($sentiment['neg'] > 1) self::updateSanitizationByReviewId($review->review_id);
            }
        }
        Log::info("Fake Review Sanitization Successfully Completed.");
        return true;
    }

    private static function getReviewCount($userId){
        try{

            Log::info(__FUNCTION__ . '() start successfully, not found any error.');
            $review = Review::select(DB::raw('count(s.id) as total_review'), 's.name as seller_name', 's.id as seller_id')
            ->leftJoin('users as u', 'reviews.user_id', 'u.id')
            ->leftJoin('products as p', 'p.id', 'reviews.product_id')
            ->leftJoin('users as s', 'p.user_id', 's.id')
            ->where(['reviews.is_active' => 1, 'reviews.is_sanitized' => 0, 'u.is_active' => 1 , 's.is_active' => 1, "u.id" => $userId,])
            ->whereRaw('DATE_ADD(NOW(), INTERVAL '.self::$hoursForGettingReviews.' HOUR) < reviews.created_at')
            ->groupBy('s.id')
            ->get();
            Log::info(__FUNCTION__ . '() run successfully, not found any error.');

            return $review;
        }catch(Exception $e){
            Log::warning(__FUNCTION__ . " throw this error from class " . __CLASS__);
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    private static function getDataForSanitization(){
        try{

            Log::info(__FUNCTION__ . '() start successfully, not found any error.');
            $review = Review::select(
                DB::raw('time_format(timediff(now() , reviews.created_at), "%H") as timediff'), 'reviews.id as review_id','reviews.review as review',
                'reviews.user_id as user_id', 'reviews.created_at as review_created_at',
                "u.name as user_name", "p.id as product_id", "p.name as product_name", "s.id as seller_id", "s.name as seller_name",
            )
            ->leftJoin('users as u', 'reviews.user_id', 'u.id')
            ->leftJoin('products as p', 'p.id', 'reviews.product_id')
            ->leftJoin('users as s', 'p.user_id', 's.id')
            ->where(['reviews.is_active' => '1', 'reviews.is_sanitized' => '0', 'u.is_active' => '1', 's.is_active' => '1'])
            ->get();
            Log::info(__FUNCTION__ . '() run successfully, not found any error.');

            return $review;
        }catch(Exception $e){
            Log::warning(__FUNCTION__ . " throw this error from class " . __CLASS__);
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    private static function changeReviewSanitization($user_id){
        try{
            Log::info(__FUNCTION__ . '() start successfully, not found any error.');
            $reviews = Review::select('reviews.*')
            ->leftJoin('users as u', 'u.id', 'reviews.user_id')
            ->where(['reviews.is_active' => '1', 'reviews.is_sanitized' => '0', 'u.is_active' => '1', 'reviews.user_id' => $user_id])
            ->whereRaw('DATE_ADD(NOW(), INTERVAL '.self::$hoursForGettingReviews.' HOUR) < reviews.created_at')
            ->get();
            foreach($reviews as $review) {
                $review->update([
                    "is_sanitized" => 1,
                    "is_suspicious" => 1
                ]);
            }
            Log::info(__FUNCTION__ . '() run successfully, not found any error.');
        }catch(Exception $e){
            Log::warning(__FUNCTION__ . " throw this error from class " . __CLASS__);
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    private static function isUserHasSuspiciousReview($user_id){
        try{
            Log::info(__FUNCTION__ . '() start successfully, not found any error.');
            $review = Review::select(DB::raw('COUNT(id) count'))->where(['is_suspicious' => 1, 'user_id' => $user_id])->groupBy('user_id')->first();
            Log::info(__FUNCTION__ . '() run successfully, not found any error.');
            return $review->count ?? 0;
        }catch(Exception $e){
            Log::warning(__FUNCTION__ . " throw this error from class " . __CLASS__);
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    private static function updateSanitizationByReviewId($id){
        try{
            Log::info(__FUNCTION__ . '() start successfully, not found any error.');
            $review = Review::find($id)->update([
                "is_sanitized" => 1,
                "is_suspicious" => 1
            ]);
            Log::info(__FUNCTION__ . '() end successfully, not found any error.');
            return $review;
        }catch(Exception $e){
            Log::warning(__FUNCTION__ . " throw this error from class " . __CLASS__);
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

}
