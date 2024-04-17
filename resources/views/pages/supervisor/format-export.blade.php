<table>
    <thead>
        <tr>
            <th>#</th>
            <th>ID Number</th>
            <th>Nama</th>
            <th>Nomer Telepone</th>
            <th>Level</th>
            <th>Label</th>
            <th>Email</th>
            <th>Username</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $index => $item)
            <tr>
                <td>{{$index + 1}}</td>
                <td>{{ $item->id_number }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->phone_number }}</td>
                <td>{{ $item->level }}</td>
                <td>{{ $item->label }}</td>
                <td>{{ $item->email }}</td>
                <td>{{ $item->user->username }}</td>
            </tr>
        @endforeach

    </tbody>
</table>
