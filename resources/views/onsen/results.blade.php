@extends('layouts.app')

@section('title', $prefecture . 'の温泉宿一覧 | 全国温泉一覧')
@section('description', $prefecture . 'にある温泉宿・温泉旅館の一覧です。宿名・住所・写真・予約リンク・実際に泊まった人の口コミをまとめて確認できます。')

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
    'itemListElement' => collect($results)->values()->map(function ($item, $i) use ($reviews) {
        $hotel = $item['hotel'][0]['hotelBasicInfo'] ?? [];
        $hotelReviews = $reviews->get($hotel['hotelNo'] ?? null);

        $entry = [
            '@type' => 'LodgingBusiness',
            'name' => $hotel['hotelName'] ?? '',
            'url' => $hotel['hotelInformationUrl'] ?? null,
            'image' => $hotel['hotelImageUrl'] ?? null,
            'address' => trim(($hotel['address1'] ?? '') . ($hotel['address2'] ?? '')),
        ];

        if ($hotelReviews && $hotelReviews->count() > 0) {
            $entry['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => round($hotelReviews->avg('rating'), 1),
                'reviewCount' => $hotelReviews->count(),
            ];
        }

        return [
            '@type' => 'ListItem',
            'position' => $i + 1,
            'item' => $entry,
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
      @php
        $hotel = $item['hotel'][0]['hotelBasicInfo'] ?? [];
        $hotelReviews = $reviews->get($hotel['hotelNo'] ?? null, collect());
      @endphp
      <article class="mb-4 pb-4 border-bottom">
        <h2 class="h5"><a href="{{ $hotel['hotelInformationUrl'] ?? '#' }}" target="_blank" rel="noopener noreferrer">{{ $hotel['hotelName'] ?? '' }}</a></h2>
        <address class="mb-2">{{ $hotel['address1'] ?? '' }}{{ $hotel['address2'] ?? '' }}</address>
        @if(!empty($hotel['hotelImageUrl']))
          <img src="{{ $hotel['hotelImageUrl'] }}" alt="{{ $hotel['hotelName'] ?? $prefecture . 'の温泉宿' }}" width="200" loading="lazy">
        @endif

        <div class="mt-3">
          @if($hotelReviews->isEmpty())
            <p class="text-muted small">まだ口コミがありません。最初の口コミを投稿してみませんか？</p>
          @else
            <p class="fw-bold small mb-2">
              口コミ {{ $hotelReviews->count() }}件（平均★{{ round($hotelReviews->avg('rating'), 1) }}）
            </p>
            @foreach($hotelReviews as $review)
              <div class="border rounded p-2 mb-2 small">
                <div>{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}
                  <strong>{{ $review->nickname }}</strong>
                  <span class="text-muted">{{ $review->created_at->format('Y-m-d') }}</span>
                </div>
                <div>{{ $review->comment }}</div>
              </div>
            @endforeach
          @endif

          <details class="mt-2">
            <summary class="small">口コミを投稿する</summary>
            <form method="POST" action="{{ route('reviews.store') }}" class="mt-2">
              @csrf
              <input type="hidden" name="hotel_no" value="{{ $hotel['hotelNo'] ?? '' }}">
              <input type="hidden" name="hotel_name" value="{{ $hotel['hotelName'] ?? '' }}">
              <input type="hidden" name="prefecture" value="{{ $prefecture }}">
              <div style="position:absolute;left:-9999px;" aria-hidden="true">
                <label>ウェブサイト <input type="text" name="website" tabindex="-1" autocomplete="off"></label>
              </div>
              <div class="mb-2">
                <label class="form-label small">ニックネーム（任意）</label>
                <input type="text" name="nickname" class="form-control form-control-sm" maxlength="30">
              </div>
              <div class="mb-2">
                <label class="form-label small">評価</label>
                <select name="rating" class="form-select form-select-sm" required>
                  <option value="">選択してください</option>
                  <option value="5">★★★★★</option>
                  <option value="4">★★★★☆</option>
                  <option value="3">★★★☆☆</option>
                  <option value="2">★★☆☆☆</option>
                  <option value="1">★☆☆☆☆</option>
                </select>
              </div>
              <div class="mb-2">
                <label class="form-label small">口コミ</label>
                <textarea name="comment" class="form-control form-control-sm" rows="3" minlength="5" maxlength="1000" required></textarea>
              </div>
              @if ($errors->any())
                <p class="text-danger small">{{ $errors->first() }}</p>
              @endif
              <button type="submit" class="btn btn-sm btn-outline-primary">投稿する</button>
            </form>
          </details>
        </div>
      </article>
    @endforeach
  @endif
</div>
@endsection
