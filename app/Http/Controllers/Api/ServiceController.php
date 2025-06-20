<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceListRequest;
use App\Models\Service;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ServiceController extends Controller
{
    public function index(ServiceListRequest $request){
        try{

            $query = Service::with('category');
            if ($request->has('category_id')) {
                $query->where('category_id', $request->category_id);
            }
            if ($request->has('price_min')) {
                $query->where('price', '>=', $request->price_min);
            }
            if ($request->has('price_max')) {
                $query->where('price', '<=', $request->price_max);
            }
            $services = $query->get();

            return response()->json([
                'status' => true,
                'message' => 'Showing service list',
                'data' => $services,
            ], Response::HTTP_OK);

        } catch (\Throwable $e) {
            Log::error($e);
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
