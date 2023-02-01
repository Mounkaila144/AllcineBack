<?php

namespace App\Http\Controllers;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Article;
use App\Models\Facture;
use App\Models\Vente;
use Exception;
use Illuminate\Http\Request;

class FactureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index(Request $request ,Facture $products)
    {
        return  Facture::all();


    }
    public function download(Request $request, $id)
    {
        $factures = Facture::find($id);
        $contenue=json_decode($factures->contenue);
        $total=0;
        foreach ($contenue as$value){
            $total+=$value->itemTotal;
        }
        return view('factures.facture',["factures"=>$factures,"contenue"=>$contenue,"total"=>$total]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $r
     * @return \Illuminate\Http\JsonResponse
     * @throws Exception
     */
    public function store(Request $request)
    {
        $save = new Facture();
        $save->nom =$request->input(["nom"]);
        $save->prenom =$request->input(["prenom"]);
        $save->adresse =$request->input(["adresse"]);
        $save->contenue =json_encode($request->input(["contenue"]));
        $save->user_id =(int)$request->get('user_id');
        $save->save();
        if ($request->has("contenue")){
            $contenue =$request->input(["contenue"]);
            $v=[];
            foreach ($contenue as $content){
                $article=Article::findOrFail($content["id"]);
                if ($article->stock-$content["quantity"]<0 or $article->stock<$content["quantity"]){
                    throw new Exception("Database error");
                }
                else{
                    $article->update(["stock"=>$article->stock-$content["quantity"],"vendue"=>$article->vendue+$content["quantity"]]);
                    $vente=new Vente();
                    $vente->nom=$article->nom;
                    $vente->identifiant=$save->id;
                    $vente->prixAchat=$article->prixAchat;
                    $vente->prixVente=$article->prixVente;
                    $vente->quantite=$content["quantity"];
                    $vente->user_id=$request->input(["user_id"]);
                    $vente->save();
                }
            }

        }else{
            Return response()->json("404");
        }


        Return response()->json($save);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Facture  $product
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        $factures = Facture::find($id);
        $contenue=json_decode($factures->contenue);
        $total=0;
        foreach ($contenue as$value){
            $total+=$value->itemTotal;
        }
        return view('factures.facture',["factures"=>$factures,"contenue"=>$contenue,"total"=>$total]);
    }
    public function destroy(Request $request): \Illuminate\Http\JsonResponse
    {

        $data = $request->input(["data"]);
        $d = [];
        foreach ($data as $id) {
            $d[] = Facture::findOrFail($id);
        }

        foreach ($d as $facture) {
            if ($facture) {
                $contenue = json_decode($facture->contenue);
                foreach ($contenue as $content) {
                    $article = Article::find($content->id);
                    $article===null?null:$article->update(["stock" => $article->stock + $content->quantity,"vendue" => $article->vendue - $content->quantity]);
                }

                Vente::where("identifiant",$facture->id)->delete();
                $facture->delete();
            } else {
                return response()->json("eureur");
            }
        }
        return response()->json("sucess");
    }


}
