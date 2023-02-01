<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Entresorti;
use App\Models\Facture;
use App\Models\Stock;
use App\Models\Vente;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class Dahboard extends Controller
{


    public function TotalArticle(Request $request, Article $article,Vente $vente,Entresorti $entresorti)
    {
        $data=[];
        $data["TotalArticle"]= $article->count();
        $data["articleVendue"]=$vente->count();
        $pvav=(int)DB::table('ventes')->select(DB::raw('SUM(prixVente*quantite) as sum'))->first()->sum+(int)DB::table('ventereservations')->select(DB::raw('SUM(prixVente*quantite) as sum'))->first()->sum;
        $pvavAnnee=(int)DB::table('ventes')->select(DB::raw('SUM(prixVente*quantite) as sum'))->whereYear('created_at', '=', Carbon::now()->year)->first()->sum+(int)DB::table('ventereservations')->select(DB::raw('SUM(prixVente*quantite) as sum'))->whereYear('created_at', '=', Carbon::now()->year)->first()->sum;
        $pvavJour=(int)DB::table('ventes')->select(DB::raw('SUM(prixVente*quantite) as sum'))->whereDate('created_at', '=', Carbon::today())->first()->sum+(int)DB::table('ventereservations')->select(DB::raw('SUM(prixVente*quantite) as sum'))->whereDate('created_at', '=', Carbon::today())->first()->sum;
        $pvavMoi=(int)DB::table('ventes')->select(DB::raw('SUM(prixVente*quantite) as sum'))->whereMonth('created_at', '=', Carbon::now()->month)->first()->sum+(int)DB::table('ventereservations')->select(DB::raw('SUM(prixVente*quantite) as sum'))->whereMonth('created_at', '=', Carbon::now()->month)->first()->sum;
        $paav=(int)DB::table('ventes')->select(DB::raw('SUM(prixAchat*quantite) as sum'))->first()->sum+(int)DB::table('ventereservations')->select(DB::raw('SUM(prixAchat*quantite) as sum'))->first()->sum;
        $paavAnnee=(int)DB::table('ventes')->select(DB::raw('SUM(prixAchat*quantite) as sum'))->whereYear('created_at', '=', Carbon::now()->year)->first()->sum+(int)DB::table('ventereservations')->select(DB::raw('SUM(prixAchat*quantite) as sum'))->whereYear('created_at', '=', Carbon::now()->year)->first()->sum;
        $paavJour=(int)DB::table('ventes')->select(DB::raw('SUM(prixAchat*quantite) as sum'))->whereDate('created_at', '=', Carbon::today())->first()->sum+(int)DB::table('ventereservations')->select(DB::raw('SUM(prixAchat*quantite) as sum'))->whereDate('created_at', '=', Carbon::today())->first()->sum;
        $paavMoi=(int)DB::table('ventes')->select(DB::raw('SUM(prixAchat*quantite) as sum'))->whereMonth('created_at', '=', Carbon::now()->month)->first()->sum+(int)DB::table('ventereservations')->select(DB::raw('SUM(prixAchat*quantite) as sum'))->whereMonth('created_at', '=', Carbon::now()->month)->first()->sum;
        $retirer=(int)DB::table('entresortis')->select(DB::raw('SUM(prix) as sum'))->where('type', '=', 0)->first()->sum;
        $retirerMoi=(int)DB::table('entresortis')->select(DB::raw('SUM(prix) as sum'))->where('type', '=', 0)->whereMonth('created_at', '=', Carbon::now()->month)->first()->sum;
        $retirerAnnee=(int)DB::table('entresortis')->select(DB::raw('SUM(prix) as sum'))->where('type', '=', 0)->whereYear('created_at', '=', Carbon::now()->year)->first()->sum;
        $ajouter=(int)DB::table('entresortis')->select(DB::raw('SUM(prix) as sum'))->where('type', '=', 1)->first()->sum;
        $ajouterMoi=(int)DB::table('entresortis')->select(DB::raw('SUM(prix) as sum'))->where('type', '=', 1)->whereMonth('created_at', '=', Carbon::now()->month)->first()->sum;
        $ajouterAnnee=(int)DB::table('entresortis')->select(DB::raw('SUM(prix) as sum'))->where('type', '=', 1)->whereYear('created_at', '=', Carbon::now()->year)->first()->sum;
        $data["recet"]=$pvav;
        $data["recetJour"]=$pvavJour;
        $data["recetMoi"]=$pvavMoi;
        $data["recetAnnee"]=$pvavAnnee;
        $data["prixAchatArticleVendue"]=$paav;
        $data["benefice"]=$pvav-$paav;
        $data["beneficeAnnee"]=$pvavAnnee-$paavAnnee;
        $data["beneficeMoi"]=$pvavMoi-$paavMoi;
        $data["beneficeJour"]=$pvavJour-$paavJour;
        $data["prixRetirer"]=$retirer;
        $data["prixRetirerMoi"]=$retirerMoi;
        $data["prixRetirerAnnee"]=$retirerAnnee;
        $data["prixAjouter"]=$ajouter;
        $data["prixAjouterMoi"]=$ajouterMoi;
        $data["prixAjouterAnnee"]=$ajouterAnnee;
        $data["caisse"]=$ajouter+$pvav-$retirer;
//            ->select(DB::raw('SUM(column_name) as sum'))
//            ->first();
        return $data;
    }

}
