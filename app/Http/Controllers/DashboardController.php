<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use App\Models\Link;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $lists = Link::join('artikels','artikels.link_id','links.id')
                    ->where('status', 'active')
                    ->paginate(24);

        $active_film = Link::whereStatus('active')->first();
        $url = explode("/",$active_film->url);
        $sumber = $url[0]."//".$url[2];
        return view('index', compact('lists','active_film','sumber'));
    }

    public function detail($tahun, $id)
    {
        $artikel = Artikel::whereId($id)
                            ->whereTahun($tahun)
                            ->first();

        $terbaru = Artikel::orderByDesc('rating')->limit(5)->get();

        return view('detail.index', compact('artikel','terbaru'));
    }

    public function about()
    {
        return 'test';
    }
}
