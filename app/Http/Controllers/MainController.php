<?php

namespace App\Http\Controllers;
use App\Models\Post;
use Illuminate\Http\Request;
use Symfony\Contracts\Service\Attribute\Required;

class MainController extends Controller
{
    public function index(){
        $posts = Post :: get();

       return view('index',compact('posts'));
    }
    public function store(Request $request){
        $request->validate([
        'name' => 'required',
        'description'=>'required'
    ]);
 Post::create([
        'name'=>$request->name,
        'description'=>$request->description,
    ]);
     return response()->json('success');
    }
}
