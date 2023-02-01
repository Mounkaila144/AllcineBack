<?php

namespace App\Http\Controllers\Allcine;

use App\Http\Controllers\Controller;
use App\Models\Shapcanal;
use App\Models\Venteshapshap;
use Exception;
use Illuminate\Http\Request;

class VenteShapshapController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index(Request $request ,Venteshapshap $venteshapshap)
    {
        if ($request->has("type")) {
            return $venteshapshap
                ->where('type', "=", $request->get("type"))
                ->get();
        }
        return  Venteshapshap::all();


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $r
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $save = new Venteshapshap();
            $save->type =$request->get('type');
            $save->prix =(int)$request->get('prix');
            $save->user_id =(int)$request->get('user_id');
            $save->save();
            Return response()->json($save);

        }
        catch (Exception $e){
            throw new \Exception("eureur");

        }



    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Venteshapshap  $venteshapshap
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $vente= Venteshapshap::find($id);

        Return response()->json($vente);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request,Venteshapshap $venteshapshap)
    {
        $input=$request->all();
        $venteshapshap->update($input);
        $venteshapshap->save();
            return response()->json($venteshapshap);
    }
    public function destroy(Request $request): \Illuminate\Http\JsonResponse
    {

        $data = $request->input(["data"]);
        $d = [];
        foreach ($data as $id) {
            $d[] = Venteshapshap::findOrFail($id);
        }

        foreach ($d as $table) {
            if ($table) {
                $table->delete();
            }
        }
        return response()->json("sucess");
    }


}
