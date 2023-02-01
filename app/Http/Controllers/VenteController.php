<?php

namespace App\Http\Controllers;

use App\Models\Vente;
use Illuminate\Http\Request;

class VenteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index(Request $request ,Vente $products)
    {
        if ($request->has("nom")) {
            return $products
                ->where('nom', 'LIKE', "%{$request->get("nom")}%")
                ->get();
        }
        return  Vente::all();


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $r
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $save = new Vente();
        $save->nom =$request->input(["nom"]);
        $save->prenom =$request->input(["prenom"]);
        $save->contenue =json_encode($request->input(["contenue"]));
        $save->user_id =(int)$request->get('user_id');
        $save->save();

        Return response()->json($save);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vente  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $vente= Vente::find($id);
        $data[]=[
            'contenue'=>json_decode($vente['contenue']),
            'user_id'=>$vente['user_id'],
            'nom'=>$vente['nom'],
            'prenom'=>$vente['prenom'],
            'created_at'=>$vente['created_at'],
            'updated_at'=>$vente['updated_at'],
        ];

        Return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request,Vente $product)
    {
        $input=$request->all();
        $product->update($input);
        $product->save();
            return response()->json($product);
    }

}
