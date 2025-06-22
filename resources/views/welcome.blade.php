<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Booking API</title>
    <style>
        body {
            font-family: 'Helvetica', Arial, sans-serif;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.8), rgba(147, 197, 253, 0.8)), 
                        url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI1MCIgaGVpZ2h0PSI1MCI+PHBhdGggZmlsbD0iIzllYjJmOCIgb3BhY2l0eT0iMC4yIiBkPSJNMCwwTDUwLDUwTDAsNTBMMCwwWiIvPjwvc3ZnPg==') repeat;
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            color: #ffffff;
            text-align: center;
        }
        .content {
            flex-grow: 1;
            padding: 40px 20px;
        }
        h1 {
            font-size: 32px;
            margin-bottom: 20px;
            font-weight: bold;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
        p {
            font-size: 18px;
            margin-bottom: 30px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            margin: 0 10px;
            background-color: #1e40af;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.3);
            transition: background-color 0.3s, transform 0.2s;
        }
        .button:hover {
            background-color: #1e3a8a;
            transform: translateY(-2px);
        }
        .button.github {
            background-color: #6b7280;
        }
        .button.github:hover {
            background-color: #4b5563;
            transform: translateY(-2px);
        }
        footer {
            background-color: #111827;
            color: #d1d5db;
            padding: 12px;
            text-align: center;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="content">
        <h1>Service Booking API</h1>
        <p>Book services like home cleaning or appliance repair with our simple, RESTful API.</p>
        <a href="https://github.com/iamRashedd/service-booking/blob/main/service-booking.postman_collection.json" class="button">Try API</a>
        <a href="https://github.com/iamrashedd/service-booking-api" class="button github">View on GitHub</a>
    </div>
    <footer>
        <p>© 2025 Service Booking API. MIT License.</p>
    </footer>
</body>
</html>