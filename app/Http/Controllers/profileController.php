<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Post;
use App\Comment;
use App\User;
 use App\Notifications\InvoicePaid;
 use App\Like;
 use Carbon\Carbon;
class profileController extends Controller
{

    public function index()
    {
        $Like= new Like;
        $Post = new Post;
        $User = new User;
        $followings=Auth::user()->follow;
        $posts = Post::where('user_id',Auth::id())->orderBy('created_at','des')->get();
        return view('home.profile',compact('posts','followings','Like','Post','User'));
    }

    public function show()
    {
        $followings=Auth::user()->follow;
        return view('home.profilesetting',compact('followings'));
    }
 
    public function update(Request $request, $id)
    {
        
        if($request->image != null)
        {
            $imagename= time().".".$request->image->getClientOriginalExtension();
            $request->image->move(public_path('images'),$imagename);
            $user= Auth::user();
            $user->image=$imagename;
            $user->save();
            return response()->json([
                'success'=>$imagename,
            ]);
        }

        if($request->coverimage != null)
        {
            $imagename= time().".".$request->coverimage->getClientOriginalExtension();
            $request->coverimage->move(public_path('images'),$imagename);
            $user= Auth::user();
            $user->cover_image=$imagename;
            $user->save();
            return back();
         }

         if(request('name')!=null)
         {
             $dob = $request->birthday_year."-".$request->birthday_month."-".$request->birthday_day;
             $user= User::find($id);
             $user->name= ucfirst(request('name'));
             $user->email=request('email');
             $user->clocation=ucfirst(request('clocation'));
             if(request('cCountry')!= null)
             {
                $user->ccountry=ucfirst(request('cCountry'));
             }
             $user->dob= $dob;
             $user->about= ucfirst(request('about'));
             $user->gender= ucfirst(request('gender'));
             $user->save();
             return back();
         }
         return back();
    }

    public function search()
    {
       $query=request('search_text');
        $users = User::where('name', 'LIKE', '%' . $query . '%')->get();
        // dd($user);
        return view('home.searchresult',compact('users'));
    }
}
