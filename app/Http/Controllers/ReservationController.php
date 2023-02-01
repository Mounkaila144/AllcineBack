<?php

namespace App\Http\Controllers;
use App\Models\Facture;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Article;
use App\Models\Reservation;
use App\Models\Ventereservation;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index(Request $request, Reservation $products)
    {
        $products = $products->newQuery();
        return $products->get();


    }
    public function show($id)
    {
        $reservation = Reservation::find($id);
        $contenue=json_decode($reservation->contenue);
        $total=0;
        foreach ($contenue as$value){
            $total+=$value->itemTotal;
        }
        $rest = $total - (int)$reservation->payer;
        return view('reservation.facture',["reservation"=>$reservation,"contenue"=>$contenue,"total"=>$total,"rest"=>$rest,"payer"=>$reservation->payer]);
    }

    function payer(Request $request, $id)
    {
        $reservations = Reservation::find($id);
        //calcule du total de la reservation
        $contenue = json_decode($reservations->contenue);
        $total = 0;
        foreach ($contenue as $value) {
            $total += (int)$value->itemTotal;
        }
        $rest = $total - (int)$reservations->payer;
        if ($request->input(["payer"]) <= $rest) {
            $reservations->update(["payer" => $reservations->payer + $request->input(["payer"])]);
            return response()->json("payer");
        } else {
            throw new Exception("Database error");
        }
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

        $save = new Reservation();
        $save->nom = $request->input(["nom"]);
        $save->vendue =false;
        $save->prenom = $request->input(["prenom"]);
        $save->adresse = $request->input(["adresse"]);
        $save->payer = (int)$request->input(["payer"]);
        $save->contenue = json_encode($request->input(["contenue"]));
        $save->user_id = (int)$request->get('user_id');
        $save->save();
        $contenue = json_decode($save->contenue);
        foreach ($contenue as $content) {
            $article = Article::findOrFail($content->id);
            if ($article->stock - $content->quantity < 0 or $article->stock < $content->quantity) {
                throw new Exception("Database error");
            } else {
                $article->update(["stock" => $article->stock - $content->quantity]);
                $article->update([ "vendue" => $article->vendue + $content->quantity]);

                $vente = new Ventereservation();
                $vente->nom = $article->nom;
                $vente->identifiant=$save->id;
                $vente->prixAchat = $article->prixAchat;
                $vente->prixVente = $article->prixVente;
                $vente->quantite = $content->quantity;
                $vente->user_id = $save->user_id;
                $vente->save();
            }
        }


        return response()->json($save);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Reservation $product
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */

    public function destroy(Request $request): \Illuminate\Http\JsonResponse
    {

        $data = $request->input(["data"]);
        $d = [];
        foreach ($data as $id) {
            $d[] = Reservation::findOrFail($id);
        }

        foreach ($d as $reservation) {
            if ($reservation) {
                $contenue = json_decode($reservation->contenue);
                foreach ($contenue as $content) {
                    $article = Article::find($content->id);
                    $article===null?null:$article->update(["stock" => $article->stock + $content->quantity,"vendue" => $article->vendue - $content->quantity]);
                }
                Ventereservation::where("identifiant",$reservation->id)->delete();
                $reservation->delete();
            } else {
                return response()->json("eureur");
            }
        }
        return response()->json("sucess");
    }
}
