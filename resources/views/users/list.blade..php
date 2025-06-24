<table class="table table-bordered">
    <thead>
        <tr><th>Name</th><th>Email</th></tr>
    </thead>
    <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

{{ $users->appends(request()->only('search'))->links() }}
