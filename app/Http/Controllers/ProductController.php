<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function __construct(){

    }

    public function index(){
        try{
            if(auth()->user()->role_id == 2) $products = Product::where('user_id', auth()->id())->get();
            else $products = Product::all();
            return view('products.index', ['products'=> $products]);
        }catch(Exception $e){
            Log::debug('Error in' . __FUNCTION__ . ' function in ' .__CLASS__. ' class, check details.');
            Log::error($e->getMessage());
            return $this->sendError($e->getMessage());
        }
    }

    public function create(){
        return view('products.create');
    }

    public function show($id){
        $product = Product::find($id);
        $reviews = Review::where('product_id', $id)->with('user')->get();


        if($product) return view('products.detail', ["item"=>$product, 'reviews'=> $reviews]);
        return redirect('/');
    }

    public function store(Request $request){
        try{
            $input = $request->only('name', 'price', 'quantity', 'is_verified');
            $validate = Validator::make($input, Product::$productRules);
            if($validate->fails()) $this->sendError($validate->errors()->first(), 400);
           
            $input['user_id'] = auth()->id();
            $product = Product::create($input);
            return $this->sendRessponse($product, "Product Created Successfully.", 201);

        }catch(Exception $e){
            Log::debug('Error in' . __FUNCTION__ . ' function in ' .__CLASS__. ' class, check details.');
            Log::error($e->getMessage());
            return $this->sendError($e->getMessage());
        }
    }
}
