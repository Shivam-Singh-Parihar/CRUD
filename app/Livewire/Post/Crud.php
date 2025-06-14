<?php
namespace App\Livewire\Post;
use App\Models\Post;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\On;
use Validator;
use Livewire\WithFileUploads;
class Crud extends Component
{
        use WithFileUploads;
    #[Title('CRUD - Laravel + Livewire')] // This attribute sets the title for the page when this component is rendered.
    public $name;
    public $description;
    public $posts = [''];
    public $postId = '';
    public $image;
    public $imagePreview;
    public $formTitle = "Add New Post";
    public function mount()
    {
        $this->posts = Post::latest()->get();
    }
    public function savePost()
    {
        $this->validate([
            'name' => 'required',
            'description' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => 'Post name is required',
            'description.required' => 'Post description is required',
            'image.image' => 'The file must be an image',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif',
            'image.max' => 'The image may not be greater than 2MB',
        ]);
        if ($this->postId) {
            // Update the existing post
            Post::where('id', $this->postId)->update([
                'name' => $this->name,
                'description' => $this->description,
                //store image in public foder not in store use move public path
                'file' => $this->image ? $this->image->store('posts', 'public') : null,
            ]);
            session()->flash('success', 'Post updated successfully!');
        } else {
            // Create a new post
            Post::create([
                'name' => $this->name,
                'description' => $this->description,
                // Handle image upload if provided
                'file' => $this->image ? $this->image->store('posts', 'public') : null,
            ]);
            session()->flash('success', 'Post created successfully!');
        }
        $this->mount();
        $this->resetForm();
    }

   public function editPost($id)
{
    $this->formTitle = "Edit Post";

    // You can also just use Post::find($id) if $this->posts is not a collection.
    $post = $this->posts->where('id', $id)->first();

    $this->name = $post->name;
    $this->description = $post->description;

    // Separate property for previewing stored image
    $this->image = null; // Clear Livewire image input
    $this->imagePreview = $post->file ?? null;

    $this->postId = $id;
}


    public function deletePost($id)
{
    Post::where('id', $id)->delete(); // delete post by ID

    session()->flash('success', 'Post deleted successfully!');

    $this->resetForm();  // Reset form fields if needed
    $this->mount();      // Re-fetch data (if mount is used to load posts)
}

    public function resetForm()
    {
        $this->name = "";
        $this->description = "";
        $this->postId = null;
        $this->image = null;
        $this->formTitle = "Add New Post";
        $this->imagePreview = null; // Reset image preview
    }
    public function render()
    {
        return view('livewire.post.crud')->layout('layout.app');
    }
}
