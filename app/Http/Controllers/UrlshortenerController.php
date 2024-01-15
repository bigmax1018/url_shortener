<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Url;
use Illuminate\Http\Response;

class UrlshortenerController extends Controller
{
    public function store(Request $request)
    {
	    try
	    {
		    if(auth()->user()->id)
		    {
			    $longUrl         = $request->get( 'url' ) ?? null;
			    $newGeneratedUrl = $request->get( 'shortlink' ) ?? null;

			    if ( $longUrl != '' && $newGeneratedUrl != '' )
			    {
				    $urlFound = Url::where( 'old_url', $longUrl )->get( [ 'id', 'new_url' ] )->toArray();
				    if ( !empty( $urlFound ) )
				    {
					    return $urlFound[ 0 ][ 'new_url' ];
				    }
				    else
				    {
					    $urlTable          = new Url;
					    $urlTable->old_url = $longUrl;
					    $urlTable->new_url = $newGeneratedUrl;
					    $urlTable->user_ip = $_SERVER['REQUEST_URI'];
					    $urlTable->user_id = auth()->user()->id;
					    $urlTable->save();
					    if ( $urlTable->save() )
					    {
						    return $urlTable->new_url;
					    }
				    }
			    }
		    }
	    }
	    catch(Exception $e)
	    {
	    	Log::error($e->getMessage());
	    }
    }
    public function handle(Request $request, $id)
    {
        try
        {
            $uri = $_SERVER['REQUEST_URI'];
            $url = Url::Where('new_url', 'like', '%' . $uri . '%')->get('old_url');

            if($uri == '' || $url == '' || count($url) == 0)
            {
                return abort(403);
            }
            else
            {
                return redirect($url[0]['old_url']);
            }
        }
        catch(Exception $e)
        {
            Log::error($e->getMessage());
        }
    }
}
