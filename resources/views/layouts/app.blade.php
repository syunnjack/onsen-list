<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', '全国温泉一覧 | 都道府県から温泉宿を探す')</title>
    <meta name="description" content="@yield('description', '全国47都道府県から温泉宿・温泉旅館を検索できる温泉情報サイトです。行きたいエリアを選ぶだけで、楽天トラベルの最新の宿泊施設情報を一覧表示します。')">
    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:site_name" content="全国温泉一覧">
    <meta property="og:type" content="website">
    <meta property="og:title" content="@yield('title', '全国温泉一覧 | 都道府県から温泉宿を探す')">
    <meta property="og:description" content="@yield('description', '全国47都道府県から温泉宿・温泉旅館を検索できる温泉情報サイトです。行きたいエリアを選ぶだけで、楽天トラベルの最新の宿泊施設情報を一覧表示します。')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:locale" content="ja_JP">

    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="@yield('title', '全国温泉一覧 | 都道府県から温泉宿を探す')">
    <meta name="twitter:description" content="@yield('description', '全国47都道府県から温泉宿・温泉旅館を検索できる温泉情報サイトです。行きたいエリアを選ぶだけで、楽天トラベルの最新の宿泊施設情報を一覧表示します。')">

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    @stack('structured-data')
</head>
<body>
    <nav class="navbar navbar-dark bg-dark text-white p-3 mb-4">
        <div class="container">
            <a href="{{ route('onsen.index') }}" class="h4 mb-0 text-white text-decoration-none">全国温泉一覧</a>
        </div>
    </nav>

    <main class="container">
        @yield('content')
    </main>
</body>
</html>
