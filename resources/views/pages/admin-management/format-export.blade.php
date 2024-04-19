<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Username</th>
            <th>Role</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $index => $item)
            <tr>
                <td>{{$index + 1}}</td>
                <td>{{ $item->username }}</td>
                <td>{{ $item->role }}</td>
            </tr>
        @endforeach

    </tbody>
</table>
