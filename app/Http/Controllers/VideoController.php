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

    // for Ajx call to send like to database
    public function like(Video $video)
    {
    	$video->like();
    	return;
    }

    // for Ajx call to send dislike to database
    public function dislike(Video $video)
    {
    	$video->dislike();
    	return;
    }
}