@extends('layouts.app')

@section('title', 'このサイトについて | 全国温泉一覧')
@section('description', '全国温泉一覧の運営方針、データの出典、口コミの取り扱いについて説明しています。')

@section('content')
<div class="container">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('onsen.index') }}">全国温泉一覧</a></li>
      <li class="breadcrumb-item active" aria-current="page">このサイトについて</li>
    </ol>
  </nav>

  <h1>このサイトについて</h1>

  <section class="mb-4">
    <h2 class="h5">サイトの目的</h2>
    <p>
      「全国温泉一覧」は、全国47都道府県の温泉宿・温泉旅館を、目的（貸切風呂・露天風呂・サウナなど）で絞り込みながら探せる検索サイトです。
      宿泊施設の情報だけでなく、実際に利用した方の口コミもあわせて確認できるようにしています。
    </p>
  </section>

  <section class="mb-4">
    <h2 class="h5">掲載データの出典</h2>
    <p>
      掲載している宿泊施設の情報（宿名・住所・写真・予約リンク等）は、楽天トラベルが提供する
      <a href="https://webservice.rakuten.co.jp/" target="_blank" rel="noopener noreferrer">楽天ウェブサービス</a>
      のAPIを通じて取得しており、随時最新の情報に更新されます。予約は楽天トラベルのサイトで行われます。
    </p>
  </section>

  <section class="mb-4">
    <h2 class="h5">口コミについて</h2>
    <p>
      口コミは、どなたでもログイン不要で投稿できます。投稿内容は運営による事前確認を行わず即時公開されますが、
      不適切な投稿を発見された場合は内容を精査のうえ対応します。口コミはあくまで投稿者個人の感想であり、
      当サイトが内容の正確性を保証するものではありません。
    </p>
  </section>

  <section class="mb-4">
    <h2 class="h5">日帰り温泉・宿泊温泉・スーパー銭湯の違い</h2>
    <div class="table-responsive">
      <table class="table table-bordered table-sm">
        <thead>
          <tr>
            <th></th>
            <th>宿泊温泉（温泉旅館・ホテル）</th>
            <th>日帰り温泉</th>
            <th>スーパー銭湯</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th>泉質</th>
            <td>天然温泉が中心</td>
            <td>天然温泉が中心</td>
            <td>天然温泉の場合も、沸かし湯の場合もある</td>
          </tr>
          <tr>
            <th>滞在時間</th>
            <td>1泊〜（食事・部屋付き）</td>
            <td>数時間程度</td>
            <td>数時間程度</td>
          </tr>
          <tr>
            <th>料金目安</th>
            <td>高め（1万円台〜）</td>
            <td>中程度（数百円〜数千円）</td>
            <td>安め（数百円〜千円台）</td>
          </tr>
          <tr>
            <th>こんな人向け</th>
            <td>ゆっくり旅行・記念日</td>
            <td>近場で気軽に温泉を楽しみたい</td>
            <td>日常使い・サウナ目的</td>
          </tr>
        </tbody>
      </table>
    </div>
    <p class="text-muted small">
      当サイトは楽天トラベルAPIの宿泊施設データを扱っているため、掲載しているのは主に「宿泊温泉」です。
    </p>
  </section>
</div>
@endsection
