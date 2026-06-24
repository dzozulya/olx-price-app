<!DOCTYPE html>
<html>
<head>
    <title>OLX Dashboard</title>
</head>
<body>

<h1>Tracked Ads</h1>

<table border="1" cellpadding="10">
    <tr>
        <th>Title</th>
        <th>Price</th>
        <th>Action</th>
    </tr>

    @foreach($ads as $ad)
        <tr>
            <td>{{ $ad->title }}</td>
            <td>{{ $ad->last_price_value }} {{ $ad->last_currency }}</td>
            <td><a href="/dashboard/advertisements/{{ $ad->id }}">View</a></td>
        </tr>
    @endforeach
</table>

</body>
</html>
