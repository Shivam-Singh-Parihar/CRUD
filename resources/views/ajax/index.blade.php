<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AJAX CRUD - Laravel + jQuery</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>

<body>
    <div class="container mt-5">
        <div class="alertBox">
        </div>
        <!-- Add New Post Form -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Add New Post</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="title" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" placeholder="Enter title">
                </div>
                <div class="mb-3">
                    <label for="body" class="form-label">description</label>
                    <textarea class="form-control" id="description" rows="3" placeholder="Enter description"></textarea>
                </div>
                <button type="button" class="btn btn-primary" id="addBtn">Add Post</button>
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
                    // console.log(data);
                    let rows = '';
                    $.each(data, function(index, post) {
                        rows += `<tr>
                            <td>${post.id}</td>
                            <td contenteditable="true" class="edit-name" data-id="${post.id}">${post.name}</td>
                            <td contenteditable="true" class="edit-description" data-id="${post.id}">${post.description}</td>
                            <td><button class="deleteBtn" data-id="${post.id}">Delete</button></td>
                            </tr>`
                    });
                    $('#postTable').html(rows);
                }
            });
        }
        loadPosts();
        //add new post
        $('#addBtn').click(function() {
            const name = $('#name').val();
            const description = $('#description').val();
            $.ajax({
                url: '/ajax-posts',
                type: 'Post',
                data: {
                    name: name,
                    description: description
                },
                success: function() {
                    $('#name').val('');
                    $('#description').val('');

                    alert = ` <div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong>Post</strong> post created sucecess fully
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>`

                    $('.alertBox').html(alert);
                    loadPosts();
                }
            });
        });
        //updte post on blur
        $(document).on('blur', '.edit-name', '.edit-description', function() {
            const id = $(this).data('id');
            const row = $(this).closest('tr');
            const name = row.find('.edit-name').text();
            const description = row.find('.edit-description').text();
            console.log(name);
            console.log(description);
            $.ajax({
                url: `/ajax-posts/${id}`,
                type: 'PUT',
                data: {
                    name: name,
                    description: description,
                },
                success: function() {
                    alert = ` <div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong>Post</strong> post updted sucecess fully
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>`
                    $('.alertBox').html(alert);
                    loadPosts();
                }
            });
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
</body>

</html>
