<!DOCTYPE html>
<html>
<head>
    <title>{{ $ad->title }}</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<h1>{{ $ad->title }}</h1>

<canvas id="priceChart"></canvas>

<table border="1" cellpadding="10">
    <tr>
        <th>Date</th>
        <th>Price </th>
        <th>Currency</th>

    </tr>

    @foreach($history as $row)
        <tr>
            <td>{{ $row['date'] }}</td>
            <td>{{ $row['price'] }}</td>
            <td>{{ $row['currency'] }}</td>
        </tr>
    @endforeach
</table>

<script>
    const data = @json($history);


    const ctx = document.getElementById('priceChart');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.map(i => i.date),
            datasets: [{
                label: `Price (${data[1].currency})`,
                data: data.map(i => i.price),
                borderWidth: 2
            }]
        }
    });
</script>

</body>
</html>
