<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>You Won the Auction!</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #4CAF50; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f9f9f9; }
        .auction-info { background: white; padding: 15px; margin: 15px 0; border-radius: 5px; }
        .price { font-size: 24px; font-weight: bold; color: #4CAF50; }
        .btn { display: inline-block; background: #4CAF50; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; margin: 10px 0; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>🎉 Congratulations, {{ $winner->name }}!</h1>
        <p>You won the auction!</p>
    </div>

    <div class="content">
        <p>Great news! You are the highest bidder and have won the following auction:</p>

        <div class="auction-info">
            <h3>{{ $auction->title }}</h3>
            <p><strong>Category:</strong> {{ $auction->category->name }}</p>
            <p><strong>Your winning bid:</strong> <span class="price">${{ number_format($winningBid, 2) }}</span></p>
            <p><strong>Auction ended:</strong> {{ $auction->timer }}</p>
        </div>

        <p>The seller will contact you soon with payment and delivery details. You can view the lot details and seller's contact information by clicking the button below:</p>

        <a href="{{ route('lot.show', [$auction->category->slug, $auction->slug]) }}" class="btn">View Lot Details</a>

        <p>Thank you for participating in our auction!</p>

        <hr>
        <p><small>If you have any questions, please contact our support team.</small></p>
    </div>
</div>
</body>
</html>
