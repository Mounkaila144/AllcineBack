<?php

namespace App\Http\Controllers\Allcine;

use App\Http\Controllers\Controller;
use App\Models\Carte;
use App\Models\Ventecarte;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CarteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index(Request $request ,Carte $carte)
    {
        if ($request->has("type")) {
            return $carte
                ->where('type', "=", $request->get("type"))
                ->get();
        }
        return  Carte::all();
    }

    public function Dashboard(Request $request)
    {
        $types = ['orange', 'airtel', 'moov'];
        $temps = ['Jour', 'Moi', 'Annee'];
        $prix = ['100', '200', '500', '1000'];
        $data = [];
        foreach ($types as $type) {

            foreach ($prix as $price) {
                $data["vente$type$price"] = Ventecarte::where('type', $type)->where('prix', $price)->sum('quantite');

                foreach ($temps as $temp){
                $temp==="Jour"?$data["vente$type$temp$price"] = Ventecarte::where('type', $type)->where('prix', $price)->whereDate('created_at', '=', Carbon::today())->sum('quantite'):null;
                $temp==="Moi"?$data["vente$type$temp$price"] = Ventecarte::where('type', $type)->where('prix', $price)->whereMonth('created_at', Carbon::now()->month)->sum('quantite'):null;
                $temp==="Annee"?$data["vente$type$temp$price"] = Ventecarte::where('type', $type)->where('prix', $price)->whereYear('created_at', Carbon::now()->year)->sum('quantite'):null;
            }
                $data["$type$price"] = Carte::where('type', $type)->where('prix', $price)->sum('quantite')-$data["vente$type$price"];
                $data["{$type}Moi$price"] = Carte::where('type', $type)->whereMonth('created_at', Carbon::now()->month)->sum('quantite')-$data["vente$type"."Moi$price"];
                $data["{$type}Annee$price"] = Carte::where('type', $type)->whereYear('created_at', Carbon::now()->year)->sum('quantite')-$data["vente$type"."Annee$price"];

            }

        }
        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $r
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $prices = [
            '100' => 100,
            '200' => 200,
            '500' => 500,
            '1000' => 1000
        ];
        $save = new Carte();
        $save->type =$request->get('type');
        $save->prix =$request->get('prix');
        $save->quantite =(int)$request->input(["quantite"]);
        $save->user_id =1;
        $save->save();

        Return response()->json($save);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Carte  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $vente= Carte::find($id);

        Return response()->json($vente);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request,Carte $product)
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
            $d[] = Carte::findOrFail($id);
        }

        foreach ($d as $table) {
            if ($table) {
                $table->delete();
            }
        }
        return response()->json("sucess");
    }


}
