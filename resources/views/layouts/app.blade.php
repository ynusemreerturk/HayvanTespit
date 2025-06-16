<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Ultrasonik Ses Sistemi')</title>
    <style>
        /* Buraya CSS kodunu aynen kopyala */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #ecf0f1;
            background-image: radial-gradient(#bdc3c7 2px, transparent 3px);
            background-size: 20px 20px;
        }
        .container {
            display: flex;
            height: 100vh;
        }
        .sidebar {
            width: 220px;
            background-color: #2C3E50;
            height: 100vh;
            padding-top: 20px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }
        .btn {
            background-color: #3498DB;
            margin-left: 25px;
            padding: 10px 25px;
            border-radius: 25px;
            font-weight: bold;
            color: white;
            cursor: pointer;
            user-select: none;
            width: fit-content;
            transition: background-color 0.3s ease;
            margin-bottom: 10px;
        }
        .btn:hover {
            background-color: #2980B9;
        }
        .btn.disabled {
            background-color: #2C3E50;
            cursor: default;
            pointer-events: none;
            user-select: none;
            margin-bottom: 0;
        }
        .btn.spaced {
            margin-top: 100px;
        }
        header {
            background-color: #34495E;
            color: #ECF0F1;
            font-weight: bold;
            padding: 12px 0;
            text-align: center;
            font-size: 16px;
            flex-shrink: 0;
            user-select: none;
        }
        .content {
            flex-grow: 1;
            background-color: white;
            padding: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="sidebar">
        <div class="btn disabled">Admin Paneli</div>
        <div class="btn spaced">Kameralar</div>
        <div class="btn">Aksiyonlar</div>
        <div class="btn">İletişimler</div>
        <div class="btn">Kullanıcılar</div>
    </div>
    <div style="flex-grow: 1; display: flex; flex-direction: column;">
        <header>Ultrasonik Ses Sitemi Hayvan Tespit ve Uzaklaştırma Sistemi</header>
        <div class="content">
            @yield('content')
        </div>
    </div>
</div>
</body>
</html>
