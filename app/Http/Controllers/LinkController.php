<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;


class LinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('link.index');
    }

    public function getLink(Request $request)
    {
        if($request->ajax())
        {
            $link = Link::all();
            //dd($artikel);
            return DataTables::of($link)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    $actionBtn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->id.'" class="edit btn btn-success btn-sm"><i class="far fa-edit"></i></a>';
                    $actionBtn .= '<a href="javascript:void(0)" id="'.$row->id.'" data-toggle="tooltip" class="delete btn btn-danger btn-sm"><i class="far fa-trash-alt"></i></a>';
                    $actionBtn .= '<a href="'.route('artikel.link', $row->id).'" class="delete btn btn-primary btn-sm"><i class="far fa-eye"></i></a>';
                    return $actionBtn;
                })
                ->editColumn('status', function($row) {
                    if($row->status === 'active'){
                        return '<a class="edit-status" id="edit-status" href="javascript:void(0)" data-toogle="tooltip" data-placement="top" data-id="'.$row->id.'" title="klik untuk deactive"><span class="badge badge-success">'.$row->status.'</span></a>';
                    }
                    else {
                        return '<a class="edit-status" id="edit-status" href="javascript:void(0)" data-toogle="tooltip" data-placement="top" data-id="'.$row->id.'" title="klik untuk active"><span class="badge badge-danger">'.$row->status.'</span></a>';
                    }
                })
                ->rawColumns(['action','status'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $post = Link::updateOrCreate(['id' => $request->id],
        [
            'nama_website'=>$request->nama_website,
            'url'=>$request->url,
            'jumlah'=>$request->jumlah,
            'status'=>'deactive'
        ]);

        return response()->json($post);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Link  $link
     * @return \Illuminate\Http\Response
     */
    public function show(Link $link)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Link  $link
     * @return \Illuminate\Http\Response
     */
    public function edit($link)
    {
        $post  = Link::find($link);

        return response()->json($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Link  $link
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if($request->ajax())
        {
            if($request->text === "active")
            {
                $link = Link::find($id);
                $link->status = "deactive";
                $link->save();

                return response()->json(['success' => 'Berhasil', 'link' => $link]);
            }
            else {
                Link::query()->update(['status' => 'deactive']);
                $link = Link::find($id);
                $link->status = "active";
                $link->save();

                return response()->json(['success' => 'Berhasil', 'link' => $link]);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Link  $link
     * @return \Illuminate\Http\Response
     */
    public function destroy(Link $link)
    {
        //
    }
}
