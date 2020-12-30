<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Tweet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;


class TweetController extends Controller
{
    public function index()
    {
        $tweets = Tweet::with([
            'user' => fn ($query) => $query ->withCount([
                'followers as is_followed' => fn ($query)
                => $query ->where('follower_id',auth()->user()->id)
            ])
            ->withCasts(['is_followed' => 'boolean'])
        ])
        ->orderBy('created_at','DESC')
        ->get();

        return Inertia::render('Tweets/Index',[
            'tweets' => $tweets
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'content'=>['required','max:300'],
            'user_id'=>['exists:users,id']
        ]);

        Tweet::create([
            'content' => $request ->input('content'),
            'user_id' => auth()->user()->id
        ]);

        return Redirect::route('tweets.index');
    }

    public function follows(User $user)
    {
        auth()->user()->followings()->attach($user->id);
        return Redirect::route('tweets.index');
    }
    public function unfollows(User $user)
    {
        auth()->user()->followings()->detach($user->id);
        return redirect()->back();
    }



    public function followings()
    {
      /*  $tweets = DB::table('followings')
        ->join('tweets','tweets.user_id','=','followings.following_id')
        ->join('users','tweets.user_id','=','users.id')
        ->where('followings.follower_id',auth()->user()->id)
        ->select('users.name as name','users.profile_photo_path as profile_photo_path','tweets.id as id','tweets.content as content','tweets.created_at as created_at','tweets.user_id as user_id')
        ->orderBy('followings.id','DESC')
        ->get();

        return Inertia::render('Tweets/followings',[
                'tweets' => $tweets
        ]);*/
        $followings = Tweet::with('user')
        ->whereIn('user_id',auth()->user()->followings->pluck('id')->toArray())
        ->orderBy('created_at','DESC')
        ->get();
        return Inertia::render('Tweets/followings',[
            'followings' => $followings
    ]);

    }
    public function profil(User $user)
    {
        $profilUser = $user ->loadCount([
            'followings as is_following_you' => fn ($query) => $query -> where('following_id',auth()->user()->id)
            ->withCasts(['is_following_you' => 'boolean']),

            'followers as is_followed' => fn ($query) => $query -> where('follower_id',auth()->user()->id)
            ->withCasts(['is_followed' => 'boolean'])

        ]);
        $tweets = $user -> tweets;

         return Inertia::render('Tweets/Profil',[
            'profilUser' => $profilUser,
            'tweets' => $tweets
        ]);
    }

}
