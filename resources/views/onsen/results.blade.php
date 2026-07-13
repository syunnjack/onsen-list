@extends('layouts.app')

@section('title', $prefecture . 'の温泉宿一覧 | 全国温泉一覧')
@section('description', $prefecture . 'にある温泉宿・温泉旅館の一覧です。宿名・住所・写真・予約リンクをまとめて確認できます。')

@push('structured-data')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => '全国温泉一覧', 'item' => url('/')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => $prefecture . 'の温泉'],
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@if (!empty($results))
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'ItemList',
    'name' => $prefecture . 'の温泉宿一覧',
    'itemListElement' => collect($results)->values()->map(function ($item, $i) {
        $hotel = $item['hotel'][0]['hotelBasicInfo'] ?? [];
        return [
            '@type' => 'ListItem',
            'position' => $i + 1,
            'item' => [
                '@type' => 'LodgingBusiness',
                'name' => $hotel['hotelName'] ?? '',
                'url' => $hotel['hotelInformationUrl'] ?? null,
                'image' => $hotel['hotelImageUrl'] ?? null,
                'address' => trim(($hotel['address1'] ?? '') . ($hotel['address2'] ?? '')),
            ],
        ];
    })->all(),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endif
@endpush

@section('content')
<div class="container">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('onsen.index') }}">全国温泉一覧</a></li>
      <li class="breadcrumb-item active" aria-current="page">{{ $prefecture }}</li>
    </ol>
  </nav>

  <h1>{{ $prefecture }}の温泉宿一覧</h1>

  @if(empty($results))
    <p>{{ $prefecture }}の温泉宿が見つかりませんでした。他の都道府県もあわせてご確認ください。</p>
    <a href="{{ route('onsen.index') }}" class="btn btn-outline-primary">都道府県一覧に戻る</a>
  @else
    <p class="text-muted">{{ $prefecture }}にある温泉宿・温泉旅館 {{ count($results) }}件を掲載しています。</p>
    @foreach($results as $item)
      @php $hotel = $item['hotel'][0]['hotelBasicInfo'] ?? []; @endphp
      <article class="mb-4 pb-4 border-bottom">
        <h2 class="h5"><a href="{{ $hotel['hotelInformationUrl'] ?? '#' }}" target="_blank" rel="noopener noreferrer">{{ $hotel['hotelName'] ?? '' }}</a></h2>
        <address class="mb-2">{{ $hotel['address1'] ?? '' }}{{ $hotel['address2'] ?? '' }}</address>
        @if(!empty($hotel['hotelImageUrl']))
          <img src="{{ $hotel['hotelImageUrl'] }}" alt="{{ $hotel['hotelName'] ?? $prefecture . 'の温泉宿' }}" width="200" loading="lazy">
        @endif
      </article>
    @endforeach
  @endif
</div>
@endsection
