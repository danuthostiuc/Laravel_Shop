<html>
<head>
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/style.css') }}">
</head>
<body>
<h1>
    {{ trans("Orders") }}
</h1>

<table>
    @foreach ($orders as $row)
        <tr>
            <td>
                <a href="/order/{{ $row->id }}">{{ $row->id }}</a>
            </td>
            <td>
                {{ $row->name }}
            </td>
            <td>
                {{ $row->email }}
            </td>
            <td>
                {{ $row->comment }}
            </td>
            <td>
                {{ $row->total }}
            </td>
        </tr>
    @endforeach
</table>
</body>
</html>
