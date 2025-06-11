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
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only('name', 'description');

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName(); // unique name
            $image->move(public_path('posts'), $imageName); // move to public/posts
            $data['image'] = 'posts/' . $imageName; // save relative path in DB
        }
        $post = Post::create([
            'name' => $data['name'],
            'description' => $data['description'],
            'file' => $data['image'] ?? null, // handle optional image
        ]);

        return response()->json($post);
    }
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'images' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only('name', 'description');

        if ($request->hasFile('image')) {
            // Optional: delete old image
            if ($post->image && file_exists(public_path($post->image))) {
                unlink(public_path($post->image));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('posts'), $imageName);
            $data['image'] = 'posts/' . $imageName;
        }

        $post->update([
            'name' => $data['name'],
            'description' => $data['description'],
            'file' => $data['image'] ?? $post->file, // keep old image if not updated
        ]);

        return response()->json($post);
    }
    public function delete($id)
    {
        Post::where('id', $id)->delete();
        return response()->json(['success' => true]);
    }
}
