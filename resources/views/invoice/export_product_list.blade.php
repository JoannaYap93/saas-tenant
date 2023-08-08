<table>
    <thead>
    <tr>
        <th><b>Product Name</b></th>
    </tr>
    </thead>
    <tbody>
    @foreach($records as $key => $rows)
        <tr>
            <td>{{ $rows->name }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
