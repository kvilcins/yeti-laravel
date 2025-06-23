<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Auction Ended</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #FF9800; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f9f9f9; }
        .auction-info { background: white; padding: 15px; margin: 15px 0; border-radius: 5px; }
        .price { font-size: 20px; font-weight: bold; color: #FF5722; }
        .btn { display: inline-block; background: #2196F3; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; margin: 10px 0; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Auction Ended</h1>
        <p>Better luck next time, {{ $user->name }}!</p>
    </div>

    <div class="content">
        <p>The auction you participated in has ended. Unfortunately, your bid was not the highest.</p>

        <div class="auction-info">
            <h3>{{ $auction->title }}</h3>
            <p><strong>Category:</strong> {{ $auction->category->name }}</p>
            <p><strong>Final price:</strong> <span class="price">${{ number_format($finalPrice, 2) }}</span></p>
            <p><strong>Auction ended:</strong> {{ $auction->timer }}</p>
        </div>

        <p>Don't be disappointed! There are many more exciting auctions waiting for you.</p>

        <a href="{{ route('home') }}" class="btn">Browse New Auctions</a>

        <p>Thank you for your participation!</p>

        <hr>
        <p><small>Keep bidding and you might win the next one!</small></p>
    </div>
</div>
</body>
</html>
