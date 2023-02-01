<?php

namespace App\Http\Controllers\Allcine;

use App\Http\Controllers\Controller;
use App\Models\Shapcanal;
use App\Models\Ventecanal;
use App\Models\Venteshapshap;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ShapCanalController extends Controller
{

    public function ShapDashboard(Request $request)
    {
        $types = ['orange', 'airtel', 'moov'];
        $temps = ['Jour', 'Moi', 'Annee'];
        $data = [];
        foreach ($types as $type) {

            $data["vente$type"] = Venteshapshap::where('type', $type)->sum('prix');
            foreach ($temps as $temp){
                $temp==="Jour"?$data["vente$type$temp"] = Venteshapshap::where('type', $type)->whereDate('created_at', '=', Carbon::today())->sum('prix'):null;
                $temp==="Moi"?$data["vente$type$temp"] = Venteshapshap::where('type', $type)->whereMonth('created_at', Carbon::now()->month)->sum('prix'):null;
                $temp==="Annee"?$data["vente$type$temp"] = Venteshapshap::where('type', $type)->whereYear('created_at', Carbon::now()->year)->sum('prix'):null;
            }
            $data[$type] = Shapcanal::where('type', $type)->sum('prix')-$data["vente$type"];
            $data["{$type}Moi"] = Shapcanal::where('type', $type)->whereMonth('created_at', Carbon::now()->month)->sum('prix')-$data["vente$type"."Moi"];
            $data["{$type}Annee"] = Shapcanal::where('type', $type)->whereYear('created_at', Carbon::now()->year)->sum('prix')-$data["vente$type"."Annee"];

        }
        return response()->json($data);
    }
    public function CanalDashboard(Request $request)
    {
        $types = ["Bienvenue"=>3000,"Access"=>555,"Evasion"=>11000,"Essentiel"=>12000,"Essentiel+"=>17500,"Evasion+"=>22000,"Tout"=>44000];
        $temps = ['Jour', 'Moi', 'Annee'];
        $data = [];
        $data["venteTotal"] =0;
        foreach ($types as $type=>$prix) {
            $data["vente$type"] = Ventecanal::where('type', $type)->sum('prix');
            $data["venteTotal"] += Ventecanal::where('type', $type)->sum('prix');
            foreach ($temps as $temp){
                $temp==="Jour"?$data["vente$type$temp"] = Ventecanal::where('type', $type)->whereDate('created_at', '=', Carbon::today())->sum('prix'):null;
                $temp==="Moi"?$data["vente$type$temp"] = Ventecanal::where('type', $type)->whereMonth('created_at', Carbon::now()->month)->sum('prix'):null;
                $temp==="Annee"?$data["vente$type$temp"] = Ventecanal::where('type', $type)->whereYear('created_at', Carbon::now()->year)->sum('prix'):null;
            }
        }
        $data["canal"] = Shapcanal::where('type', "canal")->sum('prix')-$data["venteTotal"];

        return response()->json($data);
    }


    public function index(Request $request, Shapcanal $shapcanal)
    {
        if ($request->has("type")) {
            return $shapcanal
                ->where('type', "=", $request->get("type"))
                ->get();
        }
        return Shapcanal::all();


    }
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $r
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $save = new Shapcanal();
        $save->type =$request->input(["type"]);
        $save->prix =$request->input(["prix"]);
        $save->save();

        Return response()->json($save);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Shapcanal  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $vente= Shapcanal::find($id);

        Return response()->json($vente);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $product = Shapcanal::findOrFail($id);
        $input=[];
        $input["prix"]=$product->prix+$request->input(["prix"]);
        $product->update($input);
        $product->save();
            return response()->json($product);
    }
    public function destroy(Request $request): \Illuminate\Http\JsonResponse
    {

        $data = $request->input(["data"]);
        $d = [];
        foreach ($data as $id) {
            $d[] = Shapcanal::findOrFail($id);
        }

        foreach ($d as $table) {
            if ($table) {
                $table->delete();
            }
        }
        return response()->json("sucess");
    }


}
