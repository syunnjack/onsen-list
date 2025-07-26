@extends('layouts.app')

@section('content')
<div class="container">
  <h1>{{ $prefecture }}の温泉一覧</h1>

  @if(empty($results))
    <p>温泉が見つかりませんでした。</p>
  @else
    @foreach($results as $item)
      @php $hotel = $item['hotel'][0]; @endphp
      <h2><a href="{{ $hotel['hotelInformationUrl'] }}" target="_blank">{{ $hotel['hotelName'] }}</a></h2>
      <p>{{ $hotel['address1'] }}{{ $hotel['address2'] }}</p>
      <img src="{{ $hotel['hotelImageUrl'] }}" width="200">
      <hr>
    @endforeach
  @endif
</div>
@endsection
