@extends('layouts.app')

@section('content')
<div class="container">
  <h1>都道府県から温泉を探す</h1>

  <div class="row row-cols-2 row-cols-md-4 g-2 mt-3">
    @foreach ($prefectures as $pref)
      <div class="col">
        <a href="{{ url('/search?prefecture=' . urlencode($pref)) }}" class="btn btn-outline-primary w-100">
          {{ $pref }}
        </a>
      </div>
    @endforeach
  </div>
</div>
@endsection
