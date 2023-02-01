<?php

namespace App\Http\Controllers\Allcine;

use App\Http\Controllers\Controller;
use App\Models\Ventecanal;
use Illuminate\Http\Request;

class VenteCanalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index(Request $request ,Ventecanal $ventecanal)
    {
        if ($request->has("type")) {
            return $ventecanal
                ->where('type', "=", $request->get("type"))
                ->get();
        }
        return  Ventecanal::all();


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
            'Bienvenue' => 3000,
            'Access' => 5500,
            'Evasion' => 11000,
            'Essentiel' => 12000,
            'Essentiel+' => 17500,
            'Evasion+' => 22000,
            'Tout' => 44000
        ];
        $prix = $prices[$request->get('type')] ?? 0;

        $save = new Ventecanal();
        $save->type =$request->get('type');
        $save->prix =$prix;
        $save->nom =$request->input(["nom"]);
        $save->prenom =$request->input(["prenom"]);
        $save->numero =$request->input(["numero"]);
        $save->user_id =(int)$request->get('user_id');
        $save->save();

        Return response()->json($save);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ventecanal  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $vente= Ventecanal::find($id);

        Return response()->json($vente);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request,Ventecanal $product)
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
            $d[] = Ventecanal::findOrFail($id);
        }

        foreach ($d as $table) {
            if ($table) {
                $table->delete();
            }
        }
        return response()->json("sucess");
    }


}
