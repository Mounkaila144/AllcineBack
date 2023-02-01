<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Stock;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index(Request $request, Article $products)
    {
        $products = $products->newQuery();
        if ($request->has("nom")) {
            return $products
                ->where('nom', 'LIKE', "%{$request->get("nom")}%")
                ->where("stock", ">", 0)
                ->get();
        }
        if ($request->has("id")) {
            return $products
                ->where("categorie_id", "=", $request->get("id"))
                ->get();
        }

        return Article::orderBy('id', 'ASC')->get();


    }
    public function alert(Article $products)
    {
        $products = $products->newQuery();
            return $products
                ->whereColumn("stock", "<=", 'alert')
                ->get();

    }
    public function alertCount(Article $products)
    {
        $products = $products->newQuery();
            return ["alert"=>$products
                ->whereColumn("stock", "<=", 'alert')
                ->count()];

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
            'prixAchat' => 'required',
            'prixVente' => 'required',
            'stock' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $imageName = time() . '.' . $request->file('image')->extension();
        // $request->image->move(public_path('images'), $imageName);
        $request->file('image')->storeAs('public/article', $imageName);

        $save = new Article();
        $save->image = $imageName;
        $save->nom = $request->input(["nom"]);
        $save->prixAchat = (int)$request->get('prixAchat');
        $save->prixVente = (int)$request->get('prixVente');
        $save->stock = (int)$request->get('stock');
        $save->alert = (int)$request->get('alert');
        $save->vendue = 0;
        $save->categorie_id = (int)$request->get('categorie_id');
        $save->save();

        return response()->json($save);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Article $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $Article = Article::find($id);

        return response()->json($Article);
    }

    public function stocks($id, Request $request)
    {
        $Article = Article::find($id);
        if ($request->has('add')) {
            if ($request->input(["add"]) > 0) {
                $Article->update(["stock" => $Article->stock + $request->input(["add"])]);
                $stock = new Stock();
                $stock->type = "add";
                $stock->identifiant = $Article->id;
                $stock->quantite = $request->input(["add"]);
                $stock->nom = $Article->nom;
                $stock->user_id = 1;
                $stock->save();
                return response()->json($Article);
            } else {
                throw new Exception("Database error");
            }
        } else if ($request->has('remove')) {
            if ($request->input(["remove"]) > 0 and $Article->stock >= $request->input(["remove"])) {
                $Article->update(["stock" => $Article->stock - $request->input(["remove"])]);
                $stock = new Stock();
                $stock->type = "remove";
                $stock->identifiant = $Article->id;
                $stock->quantite = $request->input(["remove"]);
                $stock->nom = $Article->nom;
                $stock->user_id = 1;
                $stock->save();
                return response()->json($Article);
            } else {
                throw new Exception("Database error");
            }
        } else if ($request->has('prixAchat') and $request->has('prixVente')) {
            if ($request->input(["prixAchat"]) >=0 and $request->input(["prixVente"])>=0) {
                $Article->update(["prixAchat" => $request->input(["prixAchat"]),"prixVente" => $request->input(["prixVente"])]);
                $stock = new Stock();
                $stock->type = "prix";
                $stock->identifiant = $Article->id;
                $stock->nom = $Article->nom;
                $stock->prixAchat = $request->input(["prixAchat"]);
                $stock->prixVente = $request->input(["prixVente"]);
                $stock->user_id = 1;
                $stock->save();
                return response()->json($Article);
            } else {
                throw new Exception("Database error");
            }
        } else {
            throw new Exception("Database error");
        }


    }

    public function edit(Article $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $product = Article::findOrFail($id);
        $data = [];
        $imageName = '';
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->file('image')->extension();
            $request->file('image')->storeAs('public/article', $imageName);
            if ($product->image) {
                Storage::delete('public/article/' . $product->image);
            }
            $data["image"] = $imageName;
        }

        $request->has('nom') ? $data["nom"] = $request->input(["nom"]) : null;
        $request->has('prixVente') ? $data['prixVente'] = $request->input(["prixVente"]) : null;
        $request->has('prixAchat') ? $data['prixAchat'] = $request->input(["prixAchat"]) : null;
        $product->update($data);
        return response()->json($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Article $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id): \Illuminate\Http\JsonResponse
    {

        $Article = Article::findOrFail($id);
        if ($Article) {
            Storage::delete('public/article/' . $Article->image);
            $Article->delete();
        } else
            return response()->json("eureur");
        return response()->json(null);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Article $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeAll(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->input(["data"]);

        $d = [];
        foreach ($data as $id) {
            $d[] = Article::findOrFail($id);
        }

        foreach ($d as $Article) {
            if ($Article) {
                $stock = new Stock();
                $stock->type = "delect";
                $stock->nom = $Article->nom;
                $stock->identifiant = $Article->id;
                $stock->user_id = 1;
                $stock->save();
                Storage::delete('public/article/' . $Article->image);
                $Article->delete();
            }
        }
        return response()->json("sucess");
    }

}
