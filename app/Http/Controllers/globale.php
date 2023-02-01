<?php

namespace App\Http\Controllers;

use App\Models\Facture;
use App\Models\Stock;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class globale extends Controller
{
    public function addStocks(Request $request, Stock $stock)
    {
        if ($request->has("nom")) {
            return $stock
                ->where('nom', 'LIKE', "%{$request->get("nom")}%")
                ->where("type", "add")
                ->get();
        }


        return $stock->where("type", "add");
    }
    public function removeStocks(Request $request, Stock $stock)
    {
        if ($request->has("nom")) {
            return $stock
                ->where('nom', 'LIKE', "%{$request->get("nom")}%")
                ->where("type", "remove")
                ->get();
        }


        return $stock->where("type", "remove");
    }
    public function prixStocks(Request $request, Stock $stock)
    {
        if ($request->has("nom")) {
            return $stock
                ->where('nom', 'LIKE', "%{$request->get("nom")}%")
                ->where("type", "prix")
                ->get();
        }


        return $stock->where("type", "prix");
    }
    public function delectStocks(Request $request, Stock $stock)
    {
        if ($request->has("nom")) {
            return $stock
                ->where('nom', 'LIKE', "%{$request->get("nom")}%")
                ->where("type", "delect")
                ->get();
        }


        return $stock->where("type", "delect");
    }

}
