<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Video;

class VideoController extends Controller
{
    public function index(Video $video, Request $request){
    	$videoData = $video->getVideo($request->ip());

    	return view('welcome')->with($videoData);
    }

    public function getVideo(Video $video, Request $request){
    	$videoData = $video->getVideo($request->ip());

    	return view('welcome')->with($videoData);
    }

    public function like(Video $video)
    {
    	$video->like();
    	return;
    }

    public function dislike(Video $video)
    {
    	$video->dislike();
    	return;
    }
}