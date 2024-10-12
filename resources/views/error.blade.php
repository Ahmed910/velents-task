<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Error</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffebee;
            color: #c62828;
            text-align: center;
            padding: 50px;
        }
        h1 {
            font-size: 2.5em;
            margin-bottom: 20px;
        }
        p {
            font-size: 1.2em;
        }
        .container {
            border: 2px solid #c62828;
            border-radius: 10px;
            padding: 20px;
            display: inline-block;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Error!</h1>
        <p>{{ $message }}</p>
    </div>
</body>
</html>