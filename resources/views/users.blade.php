<!DOCTYPE html>
<html>
<head>
    <title>User List (AJAX + Pagination + Bootstrap)</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="p-4">

    <div class="container">
        <h2 class="mb-4">User List</h2>

        <div class="mb-3">
            <input type="text" id="search" class="form-control" placeholder="Search by name or email..." >
        </div>

        <div id="user-list" class="mb-3">Loading users...</div>

        <nav>
            <ul id="pagination" class="pagination"></ul>
        </nav>

        <div id="user-detail" class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">User Detail</h5>
                <p class="card-text">Select a user to view details.</p>
            </div>
        </div>
    </div>

    <script>
        let currentPage = 1;
        let lastPage = 1;

        function loadUsers(page = 1, search = '') {
            $.ajax({
                url: '/api/users',
                type: 'GET',
                data: { page: page, search: search },
                success: function (response) {
                    currentPage = response.current_page;
                    lastPage = response.last_page;

                    let html = `<table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr><th>Name</th><th>Email</th></tr>
                        </thead><tbody>`;

                    $.each(response.data, function (i, user) {
                        html += `<tr class="user-row" data-id="${user.id}" style="cursor:pointer;">
                            <td>${user.name}</td><td>${user.email}</td>
                        </tr>`;
                    });

                    html += `</tbody></table>`;
                    $('#user-list').html(html);
                    renderPagination();
                },
                error: function () {
                    $('#user-list').html(`<div class="alert alert-danger">Failed to load users.</div>`);
                }
            });
        }

        function renderPagination() {
            let html = '';

            if (currentPage > 1) {
                html += `<li class="page-item"><button class="page-link" onclick="loadUsers(${currentPage - 1}, $('#search').val())">Previous</button></li>`;
            }

            html += `<li class="page-item disabled"><span class="page-link">Page ${currentPage} of ${lastPage}</span></li>`;

            if (currentPage < lastPage) {
                html += `<li class="page-item"><button class="page-link" onclick="loadUsers(${currentPage + 1}, $('#search').val())">Next</button></li>`;
            }

            $('#pagination').html(html);
        }

        function loadUserDetail(id) {
            $.ajax({
                url: `/api/users/${id}`,
                type: 'GET',
                success: function (user) {
                    $('#user-detail').html(`
                        <div class="card-body">
                            <h5 class="card-title">${user.name}</h5>
                            <p class="card-text">
                                <strong>Email:</strong> ${user.email}<br/>
                                <strong>User ID:</strong> ${user.id}<br/>
                            </p>
                        </div>
                    `);
                }
            });
        }

        $(document).ready(function () {
            loadUsers();

            $('#search').on('keyup', function () {
                loadUsers(1, $(this).val());
            });

            $(document).on('click', '.user-row', function () {
                let userId = $(this).data('id');
                loadUserDetail(userId);
            });
        });
    </script>

</body>
</html>
