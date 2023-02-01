<?php

namespace App\Http\Controllers\Allcine;

use App\Http\Controllers\Controller;
use App\Models\Carte;
use App\Models\Ventecarte;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class VenteCarteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index(Request $request ,Ventecarte $carte)
    {
        if ($request->has("type")) {
            return $carte
                ->where('type', "=", $request->get("type"))
                ->get();
        }
        return  Ventecarte::all();
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $r
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        $types = ['orange', 'airtel', 'moov'];
        $prix = ['100', '200', '500', '1000'];
        $data = [];
        foreach ($types as $type) {
            foreach ($prix as $price) {
                $data["vente$type$price"] = Ventecarte::where('type', $type)->where('prix', $price)->sum('quantite');

                $data["$type$price"] = Carte::where('type', $type)->where('prix', $price)->sum('quantite') - $data["vente$type$price"];
                if ($request->get('type')==$type){
                if ($request->get('prix')==$price){
                    if ((int)$request->input(["quantite"])<=$data["$type$price"]){

                        $save = new Ventecarte();
                        $save->type =$request->get('type');
                        $save->prix =$request->get('prix');
                        $save->quantite =(int)$request->input(["quantite"]);
                        $save->user_id =1;
                        $save->save();
                    }else{
                        throw new \Exception("eureur");
                    }
                    }
                }
            }
        }

        Return response()->json($save);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ventecarte  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $vente= Ventecarte::find($id);

        Return response()->json($vente);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request,Ventecarte $product)
    {
        $input=$request->all();
        $product->update($input);
        $product->save();
            return response()->json($product);
    }
    public function destroy(Request $request): \Illuminate\Http\JsonResponse
    {

        $data = $request->input(["data"]);
        $d = [];
        foreach ($data as $id) {
            $d[] = Ventecarte::findOrFail($id);

        }

        foreach ($d as $table) {
            if ($table) {

                $table->delete();
            }
        }
        return response()->json("sucess");
    }


}
