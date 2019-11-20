<?php

namespace App\Http\Controllers\sdc;

use App\Tax;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class SdcMashikAyController extends Controller
{
    public function sdcMashikAy(){
        return view('pages.front_end.sdc.mashikAy.talika');
    }

    public function sdcMashikAyShow(Request $request){
        $columns = array(
            0 =>'id',
            1 => 'bname',
            2 => 'bfname',
            3 => 'bmname',
            4 => 'b_gram',
            5 => 'occupation',
            6 => 'dob',
            7 => 'nid',
            8 => 'mob',
        );


        $totalData = Tax::count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $tax = Tax::where('word_no',$request->word_no)
                ->selectRaw('*, sum(masik_ay) as pum')
                ->groupBy('holding_no')
                ->havingRaw('pum >= ?',[$request->masikAy1])
                ->havingRaw('pum <= ?',[$request->masikAy2])
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();
            //return response()->json($tax);
        }
        else {
            $search = $request->input('search.value');

            $tax =  Tax::where('word_no',$request->word_no)
                ->selectRaw('*, sum(masik_ay) as pum')
                ->groupBy('holding_no')
                ->havingRaw('pum >= ?',[$request->masikAy1])
                ->havingRaw('pum <= ?',[$request->masikAy2])
                ->where(function ($query) use ($search ) {
                    $query->where('bname','LIKE',"%{$search}%")
                        ->orWhere('bfname','LIKE',"%{$search}%")
                        ->orWhere('bmname','LIKE',"%{$search}%")
                        ->orWhere('b_gram','LIKE',"%{$search}%")
                        ->orWhere('nid','LIKE',"%{$search}%")
                        ->orWhere('mob','LIKE',"%{$search}%");
                })
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalFiltered = Tax::where('word_no',$request->word_no)
                ->selectRaw('*, sum(masik_ay) as pum')
                ->groupBy('holding_no')
                ->havingRaw('pum >= ?',[$request->masikAy1])
                ->havingRaw('pum <= ?',[$request->masikAy2])
                ->where(function ($query) use ($search ) {
                    $query->where('bname','LIKE',"%{$search}%")
                        ->orWhere('bfname','LIKE',"%{$search}%")
                        ->orWhere('bmname','LIKE',"%{$search}%")
                        ->orWhere('b_gram','LIKE',"%{$search}%")
                        ->orWhere('nid','LIKE',"%{$search}%")
                        ->orWhere('mob','LIKE',"%{$search}%");
                })
                ->count();
        }

        $data = array();

        if(!empty($tax))
        {
            foreach ($tax as $key => $value)
            {
                $nestedData['id'] = $key+1;
                $nestedData['bname'] = $value->bname;
                $nestedData['bfname'] = $value->bfname;
                $nestedData['bmname'] = $value->bmname;
                $nestedData['b_gram'] = $value->b_gram;
                $nestedData['occupation'] = $value->occupations->occupation;
                $nestedData['dob'] = date("d M Y",strtotime($value->dob));
                $nestedData['nid'] = $value->nid;
                $nestedData['mob'] = $value->mob;
                $data[] = $nestedData;

            }
        }

        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data,


        );

        echo json_encode($json_data);
    }
}
