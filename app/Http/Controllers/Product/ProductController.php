<?php

namespace App\Http\Controllers\Product;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ResponseHelper as RS;
use App\Product;
use App\Helpers\CamelCaseHelper as Camel;

class ProductController extends Controller
{
  public function insert(Request $request) {
    $validator = Validator::make($request->all(), [
      'category'  => 'required|numeric',
      'name'      => 'required|string|max:20',
      'stock'     => 'required|numeric',
      'price'     => 'required',
      'thumbnail' => 'required'
    ]);

    if($validator->fails()) {
      return $response->json($validator->errors(), 400);
    }

    $category = $request->get('category');
    $name     = $request->get('name');
    $stock    = $request->get('stock');
    $price    = $request->get('price');
    $thumbnail= $request->get('thumbnail');

    try {
      $insert = Product::create([
        'category'  => $category,
        'name'      => $name,
        'stock'     => $stock,
        'price'     => str_replace(',', '', $price),
        'thumbnail' => $thumbnail
      ]);
    } catch (\Exception $th) {
      $response = array(
        'status'   => false,
        'message'  => $th->getMessage()
      );

      return response()->json(compact('response'), 500);
    }

    $status  = true;
    $message = 'Insert data success';
    $data    = $insert;

    $response = RS::getResponse($status, $message, $data);
    
    return response()->json(compact('response'), 200);
  }

  public function get($offset) {
    try {
      $data = Product::select('product.id', 'product.name', 'category.id as category_id', 'category.category_name', 'product.stock', 'product.price', 'product.thumbnail')
                      ->join('category', 'product.category', '=', 'category.id')
                      ->offset($offset)
                      ->limit(8)
                      ->orderBy('id', 'desc')
                      ->get();
    } catch (\Exception $th) {
      $response = array (
        'status'  => false,
        'message' => $th->getMessage()
      );

      return response()->json(compact('response'), 500);
    }

    $status  = true;
    $message = count($data) . ' data found';
    $data    = $data;

    $response = RS::getResponse($status, $message, $data);

    return response()->json(compact('response'), 200);
  }

  public function getDetail($id) {
    try {
      $row = Product::select('product.id', 'product.name', 'category.id as category_id', 'category.category_name', 'product.stock', 'product.price', 'product.thumbnail')
                      ->join('category', 'product.category', '=', 'category.id')
                      ->where('product.id', $id)
                      ->first();
    } catch (\Exception $th) {
      $row = array();
      $response = array(
        'status'  => false,
        'message' => $th->getMessage(),
        'data' => $row
      );

      return response()->json(compact('response'), 500);
    }

    $status = true;
    $message = 'Data found';
    $data = $row;

    $response = RS::getResponse($status, $message, $data);
    return response()->json(compact('response'), 200);
  }
}
