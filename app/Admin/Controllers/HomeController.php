<?php

namespace App\Admin\Controllers;



use App\Models\Store;
use App\MallModels\Order;
use App\Models\StoreInfo;
use App\Models\UserOrder;
use App\MallModels\Stores;
use App\MallModels\Product;
use App\Models\TicketUser;
use Illuminate\Support\Arr;
use App\CardModels\RsStores;
use App\MallModels\Category;
use Illuminate\Http\Request;
use App\CardModels\CardOrder;
use Encore\Admin\Widgets\Form;
use Encore\Admin\Facades\Admin;
use App\Models\store\StoreLevel;
use Encore\Admin\Layout\Content;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index(Content $content)
    {
        return $content->body('');
    }

    
    public function region(Request $req){
        $q = $req->input('q','');
        $list = [];        
        $list = \App\Models\Region::where('city_level','>',1)
                            ->where('city_name','like',"%{$q}%")
                            ->orderBy('city_code')
                            ->paginate(5,['city_code as id','city_name as text']);
        return response()->json($list);
    }

    public function selectCity(Request $req,$level = 1){
        $q = $req->input('q');        
        $regionsList = \App\MallModels\Region::getRegions((int)$q,(int)$level,['city_code as id','city_name as text']);
        return response()->json($regionsList);
    }
}
