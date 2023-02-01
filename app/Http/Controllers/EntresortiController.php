<?php

namespace App\Http\Controllers;

use App\Models\Entresorti;
use Illuminate\Http\Request;

class EntresortiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index(Request $request ,Entresorti $products)
    {
        return  Entresorti::all();


    }
    public function retirer(Request $request ,Entresorti $products)
    {
        return $products
            ->where('type', '=',0)
            ->get();
    }
    public function ajouter(Request $request ,Entresorti $products)
    {
        return $products
            ->where('type', '=',1)
            ->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $r
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $save = new Entresorti();
        $save->motif =$request->input(["motif"]);
        $save->type =$request->input(["type"]);
        $save->prix =(int)$request->get('prix');
        $save->user_id =(int)$request->get('user_id');
        $save->save();

        Return response()->json($save);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Entresorti  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $Entresorti= Entresorti::find($id);

        Return response()->json($Entresorti);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request,Entresorti $product)
    {
        $input=$request->all();
        $product->update($input);
        $product->save();
            return response()->json($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Entresorti  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $Entresorti = Entresorti::findOrFail($id);
        if($Entresorti)
            $Entresorti->delete();
        else
            return response()->json("eureur");
        return response()->json(null);
    }

}
