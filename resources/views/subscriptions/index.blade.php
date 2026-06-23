<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>OLX Price Tracker</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }

        .container {
            max-width: 600px;
        }

        .field {
            margin-bottom: 15px;
        }

        input {
            width: 100%;
            padding: 10px;
        }

        button {
            padding: 10px 20px;
        }

        .success {
            color: green;
            margin-bottom: 20px;
        }

        .error {
            color: red;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>

<div class="container">

    <h1>OLX Price Tracker</h1>

    @if(session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('subscriptions.store') }}">
        @csrf

        <div class="field">
            <label>OLX URL</label>

            <input
                type="text"
                name="url"
                value="{{ old('url') }}"
                placeholder="https://www.olx.ua/..."
            >

            @error('url')
            <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="field">
            <label>Email</label>

            <input
                type="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="user@example.com"
            >

            @error('email')
            <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit">
            Subscribe
        </button>
    </form>

</div>

</body>
</html>
