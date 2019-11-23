<?php

namespace App\Http\Controllers\Menu;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ResponseHelper as RS;
use App\Category;
use App\CategoryHeader;

class MenuController extends Controller
{
    public function getMenu() {
      try {
        $categoryHeader = CategoryHeader::select('id', 'name')->get();
        
        $data = array();
        foreach ($categoryHeader as $key => $menu) {
          $category = Category::select('id', 'category_name')
                      ->where('category_header', $menu->id)
                      ->get();
          
          $data[] = array(
            'menu' => $menu->name,
            'submenu' => $category
          );
        }
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
}
