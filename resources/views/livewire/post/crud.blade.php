<div class="container mt-5">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
    {{session('success')}}
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

    @endif
    <div class="alertBox">
    </div>
    <!-- Add New Post Form -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">{{ $formTitle ?? '' }}</h5>
        </div>
        <div class="card-body">
            <form wire:submit="savePost">
                <div class="mb-3">
                    <label for="title" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" placeholder="Enter title"
                        wire:model="name">
                    <small class="text-danger">
                        @error('name')
                            {{ $message }}
                        @enderror
                    </small>
                </div>
                <div class="mb-3">
                    <label for="body" class="form-label">Description</label>
                    <textarea class="form-control" id="description" rows="3" placeholder="Enter description" wire:model="description"></textarea>
                    <small class="text-danger">
                        @error('description')
                            {{ $message }}
                        @enderror
                    </small>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Image</label>
                    <input type="file" id="image" name="image" wire:model="image">
                    <small class="text-danger">
                        @error('image')
                            {{ $message }}
                        @enderror
                    </small>

                   @if ($image instanceof \Livewire\TemporaryUploadedFile)
    <img src="{{ $image->temporaryUrl() }}" width="120">
@elseif ($imagePreview)
    <img src="{{ asset('storage/'.$imagePreview) }}" width="120">
@endif

                </div>
                <button type="submit" class="btn btn-primary"
                    id="addBtn">{{ $postId ? 'Update Post' : 'Add Post' }}</button>
                @if ($postId)
                    <button type="button" class="btn btn-warning" id="cancleBtn" wire:click="resetForm()">Cancle
                        Edit</button>
                @endif
            </form>
        </div>
    </div>
    <!-- Posts Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Posts List</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="postTable">
                        @forelse ($posts as $post)
                            <tr>
                                <td>
                                    {{ $loop->iteration }}
                                </td>
                                <td>
                                    {{ $post->name }}
                                </td>
                                <td>
                                    {{ $post->description }}
                                </td>
                                <td>
                                    @if ($post->file)
                                        <img src="{{('storage/'.$post->file) }}" width="50" alt="Post Image">
                                    @else
                                        No Image
                                    @endif
                                </td>
                                <td>
                                    <button wire:click="editPost({{ $post->id }})">Edit</button>
                                    <button wire:click="deletePost({{ $post->id }})">Delete</button>
                                </td>
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
