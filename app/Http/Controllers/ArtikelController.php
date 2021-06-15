<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Yajra\DataTables\Facades\DataTables;
use KubAT\PhpSimple\HtmlDomParser;
use Goutte;

class ArtikelController extends Controller
{
    
    public $URL_LAYARKACA = "https://158.69.0.158/genre/action/page/";
    public $URL_FILMAPIK = 'http://103.194.171.18/category/movie/action/page/';
    public $URL_DUTAFILM = 'http://103.194.171.75/genre/action/';
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $links = Link::all();
        return view('artikel.index', compact('links'));
    }

    public function getArtikel(Request $request)
    {
        if($request->ajax())
        {
            $artikel = Artikel::whereLinkId($request->link_id)->get();
            return DataTables::of($artikel)
                ->addIndexColumn()
                ->editColumn('image_link', function($row) {
                    $link = '<a href="'.$row->image_link.'" target="_blank" class="btn btn-link">Image</a>';

                    return $link;
                })
                ->rawColumns(['image_link'])
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
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Artikel  $artikel
     * @return \Illuminate\Http\Response
     */
    public function show(Artikel $artikel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Artikel  $artikel
     * @return \Illuminate\Http\Response
     */
    public function edit(Artikel $artikel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Artikel  $artikel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Artikel $artikel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Artikel  $artikel
     * @return \Illuminate\Http\Response
     */
    public function destroy(Artikel $artikel)
    {
        //
    }

    public function link($id)
    {
        $link = Link::find($id);
        return view('artikel.list.index', compact('id','link'));
    }

    public function result($link, Request $request)
    {
        $jumlah_list = (int)$request->jumlah;
        if($link === "1")
        {
            $this->scrap_layarkaca($jumlah_list, $request->page, $link);
        }
        else if($link === "2")
        {
            $this->scrap_filmapik($jumlah_list, $request->page, $link);
        }
        else if($link === "3")
        {
            $this->scrap_dutafilm($jumlah_list, $request->page, $link);
        }

        return back();

        return response()->json([
            'response'=>'Sukses'
        ], 200);
    }

    public function scrap_layarkaca($jumlah_list = 0, $page = 0, $link)
    {
        $crawler = Goutte::request('GET', $this->URL_LAYARKACA.$page);
        $i = 0;
        while($i < $jumlah_list)
        {
            $a['judul'] = str_replace('Nonton Film ','',str_replace(' Subtitle Indonesia Streaming Movie Download','',$crawler->filter('.mega-item .grid-title a')->eq($i)->attr('title')));
            $a['rating'] = $crawler->filter('.mega-item .rating')->eq($i)->text();
            $a['durasi'] = $crawler->filter('.mega-item .grid-meta .duration')->eq($i)->text();
            $a['alamat_film'] = $crawler->filter('.mega-item figure.grid-poster a')->eq($i)->attr('href');
            $a['diterbitkan'] = $this->diterbitkan($a['alamat_film']);
            $a['tahun'] = $this->tahun($crawler->filter('.mega-item header.grid-header a')->eq($i)->attr('title'), $link);
            $a['sutradara'] = $this->sutradara($a['alamat_film']);
            $a['image_link'] = $crawler->filter('.mega-item img')->eq($i)->attr('src');
            $a['negara'] = $this->negara($a['alamat_film'], $link);
            $a['sinopsis'] = ($a['sutradara'] !== "") ? $this->sinopsis($a['alamat_film'], $a['sutradara']) : '';
            $a['kualitas'] = $crawler->filter('.mega-item .grid-meta .quality')->text();
            $a['aktor'] = $this->aktor($a['alamat_film'], $link);
            $a['link_download'] = $this->link_download($a['alamat_film'], $link);
            $a['link_id'] = $link;

            Artikel::firstOrCreate([
                'judul' => $a['judul'],
                'tahun' => $a['tahun'],
                'sutradara' => $a['sutradara']
            ],$a);
            
            $i = $i + 1;
        }
    }

    public function scrap_filmapik($jumlah_list = 0, $page = 0, $link)
    {
        $crawler = Goutte::request('GET', $this->URL_FILMAPIK.$page);
        $i = 0;
        while($i < $jumlah_list)
        {
            $a['judul'] = $crawler->filter('.ml-item a h2')->eq($i)->text();
            $a['rating'] = $crawler->filter('.ml-item .mli-rating')->eq($i)->text();
            $a['alamat_film'] = $crawler->filter('.ml-item a')->eq($i)->attr('href');
            $a['durasi'] = $this->durasi($a['alamat_film'], $link);
            $a['diterbitkan'] = $this->rilis($a['alamat_film'], $link);
            $a['tahun'] = $this->tahun($a['diterbitkan'], $link);
            $a['sutradara'] = str_replace('Director: ','',($this->direktur($a['alamat_film'], $link) === "The current node list is empty.") ? '': $this->direktur($a['alamat_film'], $link));
            $a['image_link'] = $crawler->filter('.ml-item a img')->eq($i)->attr('data-original');
            $a['negara'] = ($this->negara($a['alamat_film'], $link) === "The current node list is empty.") ? '': $this->negara($a['alamat_film'], $link);
            $a['sinopsis'] = $this->desc($a['alamat_film'], $link);
            $a['kualitas'] = $crawler->filter('.ml-item .mli-quality')->text();
            $a['aktor'] = ($this->aktor($a['alamat_film'], $link) === "The current node list is empty.") ? '': $this->aktor($a['alamat_film'], $link);
            $a['link_download'] = $this->link_download($a['alamat_film'], $link);
            $a['link_id'] = $link;

            Artikel::firstOrCreate([
                'judul' => $a['judul'],
                'tahun' => $a['tahun'],
                'sutradara' => $a['sutradara']
            ],$a);
            
            $i = $i + 1;
        }
    }

    public function scrap_dutafilm($jumlah_list = 0, $page = 0, $link)
    {
        $crawler = Goutte::request('GET', $this->URL_DUTAFILM.$page);
        $i = 0;
        while($i < $jumlah_list)
        {
            $a['judul'] = $crawler->filter('.mv .mv-desc-content')->eq($i)->text();
            $a['rating'] = explode(' ',$crawler->filter('.mv .mv-meta .mv-ratdur')->eq($i)->text())[0];
            $a['alamat_film'] = $crawler->filter('.mv-content-items a')->eq($i)->attr('href');
            $a['durasi'] = explode(' ',$this->durasi($a['alamat_film'], $link))[3];
            $a['diterbitkan'] = trim(explode(' | ',$this->rilis($a['alamat_film'], $link))[2]);
            $a['tahun'] = $this->tahun($a['judul'], $link);
            $a['sutradara'] = ($this->direktur($a['alamat_film'], $link) === "The current node list is empty.") ? '': $this->direktur($a['alamat_film'], $link);
            $a['image_link'] = $crawler->filter('.mv img.mv-poster')->eq($i)->attr('src');
            $a['negara'] = explode(' | ', ($this->negara($a['alamat_film'], $link) === "The current node list is empty.") ? '': $this->negara($a['alamat_film'], $link))[3];
            $a['sinopsis'] = $this->desc($a['alamat_film'], $link);
            $a['kualitas'] = $crawler->filter('.mv .mv-meta .mv-qual')->text();
            $a['aktor'] = str_replace('Actors: ','',($this->aktor($a['alamat_film'], $link) === "The current node list is empty.") ? '': $this->aktor($a['alamat_film'], $link));
            $a['link_download'] = $a['alamat_film'];
            $a['link_id'] = $link;

            Artikel::firstOrCreate([
                'judul' => $a['judul'],
                'tahun' => $a['tahun'],
                'sutradara' => $a['sutradara']
            ],$a);
            
            $i = $i + 1;
        }
    }

    public function desc($alamat, $link)
    {
        if($link == 2)
        {
            $crawler_detail = Goutte::request('GET', $alamat);
            $desc = $crawler_detail->filter('.desc')->text();
    
            return $desc;
        }
        if($link == 3)
        {
            $crawler_detail = Goutte::request('GET', $alamat);

            try {
                $desc = $crawler_detail->filter('.vid-details-right div')->eq(9)->text();
        
                return $desc;
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }
    }

    public function direktur($alamat, $link)
    {
        if($link == 2)
        {
            $crawler_detail = Goutte::request('GET', $alamat);
            try {
                $direktur = $crawler_detail->filter('.mvic-desc .mvic-info .mvici-left p')->eq(2)->text();
    
                return $direktur;
            } catch(\Exception $e) {
                return $e->getMessage();
            }
        }
        if($link == 3)
        {
            $crawler_detail = Goutte::request('GET', $alamat);
            try {
                $direktur = $crawler_detail->filter('.vid-details-right div div div a span')->text();
    
                return $direktur;
            } catch(\Exception $e) {
                return $e->getMessage();
            }
        }
    }

    public function rilis($alamat, $link)
    {
        if($link == 2)
        {
            $crawler_detail = Goutte::request('GET', $alamat);
            $rilis = $crawler_detail->filter('.mvic-desc .mvic-info .mvici-right p')->eq(2)->filter('a')->text();
    
            return $rilis;
        }
        if($link == 3)
        {
            $crawler_detail = Goutte::request('GET', $alamat);
            try {
                $rilis = $crawler_detail->filter('.vid-details-right div')->eq(1)->text();
    
                return $rilis;
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }
    }

    public function durasi($alamat, $link)
    {
        if($link == 2)
        {
            $crawler_detail = Goutte::request('GET', $alamat);
            $durasi = $crawler_detail->filter('.mvic-desc .mvic-info .mvici-right p')->eq(0)->filter('span')->text();
    
            return $durasi;
        }

        if($link == 3)
        {
            $crawler_detail = Goutte::request('GET', $alamat);
            try {
                $durasi = $crawler_detail->filter('.vid-details-right div')->eq(1)->text();
    
                return $durasi;
            } catch(\Exception $e) {
                return $e->getMessage();
            }
        }
    }

    public function tahun($alamat, $link)
    {
        if($link == 1)
        {
            $tahun = str_replace('(','',substr($alamat,strpos($alamat,'('),5));
            
            return $tahun;
        }
        if($link == 2)
        {
            $tahun = str_replace('(','',substr($alamat,strpos($alamat,'('),5));
            
            return $tahun;
        }
        if($link == 3)
        {
            $tahun = str_replace('(','',substr($alamat,strpos($alamat,'('),5));
            
            return $tahun;
        }
    }

    public function diterbitkan($alamat)
    {
        $crawler_detail = Goutte::request('GET', $alamat);         
        $a['diterbitkan'] = $crawler_detail->filter('#movie-detail .content')->text();
        $str_awal = strpos($a['diterbitkan'], 'Diterbitkan');
        $str_akhir = strpos($a['diterbitkan'], 'Oleh');
        $new_str = substr($a['diterbitkan'],$str_awal,$str_akhir-$str_awal);
        $diterbitkan = str_replace('Diterbitkan','',$new_str);

        return $diterbitkan;
    }

    public function sutradara($alamat)
    {
        $crawler_detail = Goutte::request('GET', $alamat);
        $a['sutradara'] = $crawler_detail->filter('#movie-detail .content')->text();
        $str_awal = strpos($a['sutradara'], 'Sutradara');
        $str_akhir = strpos($a['sutradara'], 'Genre');
        $new_str = substr($a['sutradara'], $str_awal, $str_akhir-$str_awal);
        $sutradara = str_replace('Sutradara','',$new_str);
        
        return $sutradara;
    }

    public function negara($alamat, $link)
    {
        if($link === "1") 
        {
            $crawler_detail = Goutte::request('GET', $alamat);
            $negara = $crawler_detail->filter('#movie-detail .content h3 a')->eq(1)->text();

            return $negara;
        }
        
        if($link === "2")
        {
            $crawler_detail = Goutte::request('GET', $alamat);
            try {
                $negara = $crawler_detail->filter('.mvic-desc .mvic-info .mvici-left p')->eq(4)->filter('a')->text();
    
                return $negara;
            } catch(\Exception $e) {
                return $e->getMessage();
            }
        }
        if($link === "3")
        {
            $crawler_detail = Goutte::request('GET', $alamat);
            try {
                $negara = $crawler_detail->filter('.vid-details-right div')->eq(1)->text();
    
                return $negara;
            } catch(\Exception $e) {
                return $e->getMessage();
            }
        }
    }

    public function sinopsis($alamat, $sutradara)
    {
        $crawler_detail = Goutte::request('GET', $alamat);
        $s = $crawler_detail->filter('#movie-detail .content blockquote p')->text();
        $str_akhir = strpos($s, $sutradara);
        $sinopsis = substr($s, 0, $str_akhir);
        
        return $sinopsis;
    }

    public function link_download($alamat, $link)
    {
        if($link === "1")
        {
            $crawler_detail = Goutte::request('GET', $alamat);
            $link_download = $crawler_detail->filter('.download-movie a')->eq(0)->attr('href');
    
            return $link_download;
        }
        
        if($link === "2")
        {
            $crawler_detail = Goutte::request('GET', $alamat);
            $link_download = $crawler_detail->filter('#mv-info a')->attr('href');

            return $link_download;
        }
    }

    public function aktor($alamat, $link)
    {
        if($link === "1")
        {
            $crawler_detail = Goutte::request('GET', $alamat);
            $a = $crawler_detail->filter('#movie-detail .content div')->eq(2)->text();
            $aktor = str_ireplace('Bintang film','',$a);
    
            return $aktor;
        }

        if($link === "2")
        {
            $crawler_detail = Goutte::request('GET', $alamat);
            try {
                $aktor = $crawler_detail->filter('.mvic-desc .mvic-info .mvici-left p')->eq(3)->filter('span')->text();
    
                return $aktor;
            } catch(\Exception $e) {
                return $e->getMessage();
            }
        }
        if($link === "3")
        {
            $crawler_detail = Goutte::request('GET', $alamat);
            try {
                $aktor = $crawler_detail->filter('.vid-details-right div')->eq(3)->filter('div div')->eq(2)->text();
    
                return $aktor;
            } catch(\Exception $e) {
                return $e->getMessage();
            }
        }
    }

}
