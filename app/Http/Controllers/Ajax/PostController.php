<?php
namespace App\Http\Controllers\Ajax;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
class PostController extends Controller
{
    public function index()
    {
        return view('ajax.index');
    }
    public function getPosts()
    {
        $posts = Post::latest()->get();
        return response()->json($posts, 200, );
    }
    public function store(Request $request)
    {
        // dd($request->all());
        $post = Post::create($request->all());
        return response()->json($post);
    }
    public function update(Request $request, $id)
    {
        $post = Post::find($id);
        $post->update($request->all());
        return response()->json($post);
    }
    public function delete($id)
    {
        Post::where('id', $id)->delete();
        return response()->json(['success' => true]);
    }
}
