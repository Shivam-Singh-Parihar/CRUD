<?php
namespace App\Livewire\Post;
use App\Models\Post;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\On;
use Validator;
class Crud extends Component
{
    #[Title('CRUD - Laravel + Livewire')] // This attribute sets the title for the page when this component is rendered.
    public $name;
    public $description;
    public $posts = [''];
    public $postId = '';
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
        ], [
            'name.required' => 'Post name is required',
            'description.required' => 'Post description is required',
        ]);
        if ($this->postId) {
            // Update the existing post
            Post::where('id', $this->postId)->update([
                'name' => $this->name,
                'description' => $this->description,
            ]);
            session()->flash('success', 'Post updated successfully!');
        } else {
            // Create a new post
            Post::create([
                'name' => $this->name,
                'description' => $this->description,
            ]);
            session()->flash('success', 'Post created successfully!');
        }
        $this->mount();
        $this->resetForm();
    }

    public function editPost($id)
    {
        $this->formTitle = "Edit Post";
        $data = $this->posts->where('id', $id)->first()->toArray();
        $this->name = $data['name'];
        $this->description = $data['description'];
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
        $this->formTitle = "Add New Post";
    }
    public function render()
    {
        return view('livewire.post.crud')->layout('layout.app');
    }
}
