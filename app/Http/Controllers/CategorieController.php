<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Categorie;
use App\Models\Stock;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index(Request $request, Categorie $categories)
    {
        $categories = $categories->newQuery();
        if ($request->has("nom")) {
            return $categories
                ->where('nom', 'LIKE', "%{$request->get("nom")}%")
                ->where("stock", ">", 0)
                ->get();
        }
        return Categorie::orderBy('id', 'ASC')->get();


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $r
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $imageName = time() . '.' . $request->file('image')->extension();
        // $request->image->move(public_path('images'), $imageName);
        $request->file('image')->storeAs('public/categorie', $imageName);

        $save = new Categorie();
        $save->image = $imageName;
        $save->nom = $request->input(["nom"]);
        $save->save();

        return response()->json($save);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Categorie $categorie
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $Categorie = Categorie::find($id);

        return response()->json($Categorie);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $categorie = Categorie::findOrFail($id);
        $data = [];
        $imageName = '';
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->file('image')->extension();
            $request->file('image')->storeAs('public/categorie', $imageName);
            if ($categorie->image) {
                Storage::delete('public/categorie/' . $categorie->image);
            }
            $data["image"] = $imageName;
        }

        $request->has('nom') ? $data["nom"] = $request->input(["nom"]) : null;
        $categorie->update($data);
        return response()->json($categorie);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Categorie $categorie
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id,Article $article): \Illuminate\Http\JsonResponse
    {

        $Categorie = Categorie::findOrFail($id);
        if ($Categorie) {
            $Article=$article
                ->where('categorie_id', '=', $Categorie->id)
                ->get();
            foreach ($Article as $value){
                $stock = new Stock();
                $stock->type = "delect";
                $stock->nom = $value->nom;
                $stock->identifiant = $value->id;
                $stock->user_id = 1;
                $stock->save();
                Storage::delete('public/article/' . $value->image);
                $value->delete();
            }

            Storage::delete('public/categorie/' . $Categorie->image);
            $Categorie->delete();


        } else
            return response()->json("eureur");
        return response()->json(null);
    }


}
