<!DOCTYPE html>
<html>
<head>
    <title>M-Pesa Payment</title>
</head>
<body>
    <h1>Make a Payment</h1>
    <form action="{{ route('mpesa.initiate') }}" method="POST">
        @csrf
        <label for="phone">Phone Number:</label>
        <input type="text" name="phone" required><br><br>

        <label for="amount">Amount:</label>
        <input type="number" name="amount" required><br><br>

        <button type="submit">Pay Now</button>
    </form>
</body>
</html>
