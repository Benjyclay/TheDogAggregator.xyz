<?php

namespace App\Models;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\User;

class Video extends Model
{
    public $API_KEY = 'AIzaSyCUX4Kui_NXBd81DJOE2KhZR_MkVN1S0bM';
    public $query_string = 'cute dogs';

    public $i;

    public $nextPageToken;

    public function like()
    {
        $videoId = $_COOKIE['ID'];
        $likes = \DB::table('videos')->where('videoId', $videoId)->first()->likes;

        \DB::table('videos')->where('videoId', $videoId)->update([
            'likes' => $likes + 1,
        ]);

        return;
    }   

    public function dislike()
    {
        $videoId = $_COOKIE['ID'];
        $dislikes = \DB::table('videos')->where('videoId', $videoId)->first()->dislikes;

        \DB::table('videos')->where('videoId', $videoId)->update([
            'dislikes' => $dislikes + 1,
        ]);

        return;
    }   

    public function getVideo($ip){
    	//Check if User has visited Before by IP
    	$numberOfUsersWithIP = \DB::table('users')->where('ip_address', $ip)->count();
    	
    	//If they have not insert into DB
    	if($numberOfUsersWithIP == 0){
    		User::insertUserIntoDB($ip);
    	}

        $viewed = 1;
        $this->i = rand(0,49);

        // Loop until a video is found that user has not seen
        while($viewed == 1){
            //Returns Video Id and Title
            $videoData = $this->processVideo($this->i);

            //Returns 1 if user has seen 0 if not
            $viewed = $this->hasSeen($videoData[0], $ip);

            $this->i++;
        }

        setcookie("ID", $videoData[0]);

        $likes_db = \DB::table('videos')->where('videoId', $videoData[0])->first()->likes; 
        $dislikes = \DB::table('videos')->where('videoId', $videoData[0])->first()->dislikes; 

        $likes = $likes_db - $dislikes;

    	return [
                    'Title' => $videoData[1],
                    'URL' => $this->intoUrl($videoData[0]),
                    'likes' => $likes
		    	];
    }

    public function hasSeen($videoId_YT, $ip)
    {   
        //Get id of user by IP, Does this rather than lastinsertedID to avoid race conditions (MAY EFFECT PERFORMANCE)
        $userId = \DB::table('users')->where('ip_address', $ip)->first()->id;

        //Get id of Video by VideoId Does this rather than lastinsertedID to avoid race conditions (MAY EFFECT PERFORMANCE)
        $VideoId = \DB::table('videos')->where('videoId', $videoId_YT)->first()->id; 

        //Check to see if user has viewed the video
        $hasUserSeenVideo = \DB::table('users_matrix')
            ->where('userId', $userId)
            ->where('videoId', $VideoId)
            ->count();

        //Returns 1 if user has seen 0 if not
        if($hasUserSeenVideo == 0){
            //Add to users_matrix User has seen video
            $this->UserSeenVideo($VideoId, $userId);
            return 0;
        }elseif ($hasUserSeenVideo > 0) {
            return 1;
        }else{
            abort(500);
        }
    }

    public function UserSeenVideo($videoId, $userId)
    {
        \DB::table('users_matrix')->insert([
            'userId' => $userId,
            'videoId' => $videoId,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        return;
    }

    public function processVideo($i)
    {
        $videoData = $this->search($i); //Gets Video Id and Title
        $this->checkIfVideoExists($videoData[0]); // Checks if its in the database, if not inserts
        return $videoData;
    }

	public function checkIfVideoExists($videoId)
	{
    	$numberOfVideos = \DB::table('videos')->where('videoId', $videoId)->count();
    	if($numberOfVideos == 0){
    		$this->insertVideoIntoDB($videoId); // Insert videoID into DB
		} 
		return;
	}

    public function insertVideoIntoDB($videoId)
    {
        \DB::table('videos')->insert([
            'videoId' => $videoId,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        return;
    }

    protected function intoUrl($videoId)
    {
    	return 'http://www.youtube.com/embed/'. $videoId .'?autoplay=1&autohide=2&border=0&wmode=opaque&enablejsapi=1&controls=0&showinfo=0';
    }

    protected function search($i){
    	$client = new \Google_Client();
    	$client->setDeveloperKey($this->API_KEY);
  		$youtube = new \Google_Service_YouTube($client);

        if($i == 49){
            $pageToken = $this->nextPageToken;
            $this->i = 1;
        }else{
            $pageToken = '';
        };

  		$searchResponse = $youtube->search->listSearch('id,snippet', array(
            'type' => 'video',
            'maxResults' => '50',
            'safeSearch' => 'strict',
            'videoDuration' => 'short',
            'videoEmbeddable' => 'true',

            'q' => $this->query_string,
            'pageToken' => $pageToken,
            'order' => 'viewCount',
	    ));

        $this->nextPageToken = $searchResponse['nextPageToken'];

	    return [
            $searchResponse['items'][$i]['modelData']['id']['videoId'], // Id of the Video
            $searchResponse['items'][$i]['modelData']['snippet']['title'] //Title of the Video
	 	];
    }

    //20 per page
}
