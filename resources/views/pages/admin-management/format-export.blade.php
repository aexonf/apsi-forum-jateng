<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Username</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $index => $item)
            <tr>
                <td>{{$index + 1}}</td>
                <td>{{ $item->username }}</td>
            </tr>
        @endforeach

    </tbody>
</table>
