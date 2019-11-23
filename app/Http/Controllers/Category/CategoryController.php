<?php

namespace App\Http\Controllers\Category;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ResponseHelper as RS;
use App\Category;

class CategoryController extends Controller
{
    public function insert(Request $request) {
      $validator = Validator::make($request->all(), [
        'categoryHeader' => 'required|numeric', 
        'categoryName' => 'required|string|max:50'
      ]);

      if($validator->fails()) {
        return response()->json($validator->errors(), 400);
      }

      $category_header = $request->get('categoryHeader');
      $category_name  = $request->get('categoryName');

      try {
        $insert = Category::create([
          'category_header' => $category_header,
          'category_name'   => $category_name
        ]);
      } catch (\Exception $th) {
        $response = array(
          'status'  => false,
          'message' => $th->getMessage(),
        );

        return response()->json(compact('response'), 500);
      }

      $status  = true;
      $message = 'Insert data success';
      $data    = $insert;

      $response = RS::getResponse($status, $message, $data);
      return response()->json(compact('response'));
    }

    public function get() {
      try {
        $data = Category::select('id', 'category_name', 'status')->get();
      } catch (\Exception $th) {
        $data = [];
        $response = array (
          'status'  => false,
          'message' => $th->getMessage(),
          'data'    => []
        ) ;

        return response()->json(compact('response'), 500);
      }

      $status  = true;
      $message = count($data) . ' data found';
      $data    = $data;

      $response = RS::getResponse($status, $message, $data);
      return response()->json(compact('response'), 200);
    }

    public function getId($id) {
      try {
        $row = Category::select('id', 'category_name', 'status')->first();
      } catch (\Exception $th) {
        $row = array();
        $response = array (
          'status'  => false,
          'message' => $th->getMessage(),
          'data'    => $row
        ) ;

        return response()->json(compact('response'), 500);
      }

      $status  = true;
      $message = 'Data found';
      $data    = $row;

      $response = RS::getResponse($status, $message, $data);
      return response()->json(compact('response'), 200);
    }

    public function delete($id) {
      try {
        $delete = Category::where('id', $id)->delete();
      } catch (\Exception $th) {
        $response = array (
          'status'  => false,
          'message' => $th->getMessage()
        ) ;

        return response()->json(compact('response'), 500);
      }

      $status  = true;
      $message = 'Data deleted';
      $data    = [];

      $response = RS::getResponse($status, $message, $data);
      return response()->json(compact('response'), 200);
    }

    public function put(Request $request) {
      $validator = Validator::make($request->all(), [
        'id'           => 'required',
        'categoryHeader' => 'required|numeric',
        'categoryName' => 'required|string|max:15'
      ]);

      if($validator->fails()) {
        return response()->json($validator->errors(), 400);
      }

      $id               = $request->get('id');
      $category_header  = $request->get('categoryHeader');
      $category_name    = $request->get('categoryName');

      try {
        $update = Category::where('id', $id)->
        update([
          'category_header'  => $category_header,
          'category_name'    => $category_name
        ]);
      } catch (\Exception $th) {
        $response = array(
          'status'  => false,
          'message' => $th->getMessage(),
        );

        return response()->json(compact('response'), 500);
      }

      $status  = true;
      $message = 'Update data success';
      $data    = $update;

      $response = RS::getResponse($status, $message, $data);
      return response()->json(compact('response'));
    }
}
