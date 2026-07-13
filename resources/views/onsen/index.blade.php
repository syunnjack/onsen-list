@extends('layouts.app')

@section('title', '全国温泉一覧 | 都道府県から温泉宿・温泉旅館を探す')
@section('description', '全国47都道府県から温泉宿・温泉旅館を検索できる温泉情報サイトです。行きたい都道府県を選ぶだけで、楽天トラベルの最新の宿泊施設情報を一覧表示します。')

@push('structured-data')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'WebSite',
    'name' => '全国温泉一覧',
    'url' => url('/'),
    'description' => '全国47都道府県から温泉宿・温泉旅館を検索できる温泉情報サイト。',
    'inLanguage' => 'ja',
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@section('content')
<div class="container">
  <h1>都道府県から温泉を探す</h1>
  <p class="text-muted">
    全国温泉一覧では、47都道府県すべての温泉宿・温泉旅館を無料で検索できます。
    下の都道府県ボタンを選ぶと、その地域の温泉宿一覧（宿名・住所・写真・予約リンク）が表示されます。
  </p>

  <div class="row row-cols-2 row-cols-md-4 g-2 mt-3">
    @foreach ($prefectures as $pref)
      <div class="col">
        <a href="{{ route('onsen.search', ['prefecture' => $pref]) }}" class="btn btn-outline-primary w-100">
          {{ $pref }}
        </a>
      </div>
    @endforeach
  </div>
</div>
@endsection
