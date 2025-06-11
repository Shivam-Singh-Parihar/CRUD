@extends('layout.app')
@section('title', 'AJAX CRUD - Laravel + jQuery')
@section('main-content')
    <div class="container mt-5">
        <div class="alertBox">
        </div>
        <!-- Add New Post Form -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Add New Post</h5>
            </div>
            <div class="card-body">
                <form id="postForm" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="title" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" placeholder="Enter title">
                    </div>
                    <div class="mb-3">
                        <label for="body" class="form-label">Description</label>
                        <textarea class="form-control" id="description" rows="3" placeholder="Enter description"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="body" class="form-label">Image</label>
                        <input type="file" id="image" name="image">
                    </div>
                    <button type="button" class="btn btn-primary" id="addBtn">Add Post</button>
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
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="postTable">
                            <!-- Posts will be loaded here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap 5 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        //fetch all Posts
        function loadPosts() {
            $.ajax({
                url: '/ajax-posts',
                type: 'GET',
                success: function(data) {
                    console.log(data);
                    let rows = '';
                    $.each(data, function(index, post) {
                        rows += `<tr>
    <td>${post.id}</td>
    <td contenteditable="true" class="edit-name" data-id="${post.id}">${post.name}</td>
    <td contenteditable="true" class="edit-description" data-id="${post.id}">${post.description}</td>
     <td>
        <img src="/${post.file}" width="50">
        <input type="file" class="edit-image" data-id="${post.id}" />
    </td>
    <td><button class="deleteBtn" data-id="${post.id}">Delete</button></td>
</tr>`;
                    });
                    $('#postTable').html(rows);
                }
            });
        }
        loadPosts();
        //add new post
        $('#addBtn').click(function() {
            let formData = new FormData();
            formData.append('name', $('#name').val());
            formData.append('description', $('#description').val());
            formData.append('image', $('#image')[0].files[0]);

            $.ajax({
                url: '/ajax-posts',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function() {
                    $('#name').val('');
                    $('#description').val('');
                    $('#image').val('');
                    $('.alertBox').html(
                        `<div class="alert alert-success">Post created successfully</div>`);
                    loadPosts();
                }
            });
        });
        //updte post on blur
        // Function to update post
        function updatePost(row, id) {
            const name = row.find('.edit-name').text().trim();
            const description = row.find('.edit-description').text().trim();
            const imageInput = row.find('.edit-image')[0];
            const formData = new FormData();

            formData.append('name', name);
            formData.append('description', description);
            formData.append('_method', 'PUT');

            if (imageInput && imageInput.files.length > 0) {
                formData.append('image', imageInput.files[0]);
            }

            $.ajax({
                url: `/ajax-posts/${id}`,
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function() {
                    $('.alertBox').html(`
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Post</strong> updated successfully
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>`);
                    loadPosts();
                }
            });
        }

        // ✅ Update on blur (name or description)
        $(document).on('blur', '.edit-name, .edit-description', function() {
            const row = $(this).closest('tr');
            const id = $(this).data('id');
            updatePost(row, id);
        });

        // ✅ Update on image change (triggers same update logic)
        $(document).on('change', '.edit-image', function() {
            const row = $(this).closest('tr');
            const id = $(this).data('id');
            updatePost(row, id);
        });

        $(document).on('click', '.deleteBtn', function() {
            const id = $(this).data('id');
            $.ajax({
                url: `/ajax-posts/${id}`,
                type: 'DELETE',
                success: function() {
                    alert = ` <div class="alert alert-danger alert-dismissible fade show" role="alert">
  <strong>Post</strong> post deleted sucecess fully
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>`
                    $('.alertBox').html(alert);
                    loadPosts();
                }
            })
        })
    </script>
@endsection
