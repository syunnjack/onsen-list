<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Support\HotelTagger;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class OnsenController extends Controller
{
    public function index()
    {
    $prefectures = [
            1 => '北海道', 2 => '青森県', 3 => '岩手県', 4 => '宮城県', 5 => '秋田県',
            6 => '山形県', 7 => '福島県', 8 => '茨城県', 9 => '栃木県', 10 => '群馬県',
            11 => '埼玉県', 12 => '千葉県', 13 => '東京都', 14 => '神奈川県', 15 => '新潟県',
            16 => '富山県', 17 => '石川県', 18 => '福井県', 19 => '山梨県', 20 => '長野県',
            21 => '岐阜県', 22 => '静岡県', 23 => '愛知県', 24 => '三重県', 25 => '滋賀県',
            26 => '京都府', 27 => '大阪府', 28 => '兵庫県', 29 => '奈良県', 30 => '和歌山県',
            31 => '鳥取県', 32 => '島根県', 33 => '岡山県', 34 => '広島県', 35 => '山口県',
            36 => '徳島県', 37 => '香川県', 38 => '愛媛県', 39 => '高知県', 40 => '福岡県',
            41 => '佐賀県', 42 => '長崎県', 43 => '熊本県', 44 => '大分県', 45 => '宮崎県',
            46 => '鹿児島県', 47 => '沖縄県'
        ];

        //これがないと Undefined variable $prefectures になります
        return view('onsen.index', compact('prefectures'));
        //return view('golf.index');
    }

    public function search(Request $request)
    {
        $prefecture = $request->input('prefecture', '');

        if ($prefecture === '') {
            return redirect()->route('onsen.index');
        }

        $results = Cache::remember("onsen-search:{$prefecture}", now()->addHour(), function () use ($prefecture) {
            try {
                $response = Http::timeout(5)
                    ->withHeaders([
                        'Referer' => config('app.url'),
                        'Origin' => config('app.url'),
                    ])
                    ->get('https://openapi.rakuten.co.jp/engine/api/Travel/KeywordHotelSearch/20170426', [
                        'format' => 'json',
                        'applicationId' => env('RAKUTEN_APP_ID'),
                        'accessKey' => env('RAKUTEN_ACCESS_KEY'),
                        'affiliateId' => env('RAKUTEN_AFFILIATE_ID'),
                        'keyword' => $prefecture . ' 温泉',
                        'hits' => 30,
                    ]);
            } catch (ConnectionException) {
                return [];
            }

            $hotels = $response->successful() ? ($response->json('hotels') ?? []) : [];

            // KeywordHotelSearchはキーワードのあいまい一致のため、
            // 選択された都道府県以外の宿も混ざる。address1で厳密に絞り込む。
            return array_values(array_filter($hotels, function ($item) use ($prefecture) {
                return ($item['hotel'][0]['hotelBasicInfo']['address1'] ?? null) === $prefecture;
            }));
        });

        // 宿名・PR文からタグを抽出し、絞り込み用の候補一覧も作っておく
        $tagsByHotelNo = [];
        $availableTags = [];
        foreach ($results as $item) {
            $hotel = $item['hotel'][0]['hotelBasicInfo'] ?? [];
            $tags = HotelTagger::extract($hotel['hotelName'] ?? '', $hotel['hotelSpecial'] ?? '');
            if (isset($hotel['hotelNo'])) {
                $tagsByHotelNo[$hotel['hotelNo']] = $tags;
            }
            $availableTags = array_unique(array_merge($availableTags, $tags));
        }
        sort($availableTags);

        $tag = $request->input('tag', '');
        if ($tag !== '') {
            $results = array_values(array_filter($results, function ($item) use ($tag, $tagsByHotelNo) {
                $hotelNo = $item['hotel'][0]['hotelBasicInfo']['hotelNo'] ?? null;
                return in_array($tag, $tagsByHotelNo[$hotelNo] ?? [], true);
            }));
        }

        $hotelNos = collect($results)
            ->map(fn ($item) => $item['hotel'][0]['hotelBasicInfo']['hotelNo'] ?? null)
            ->filter()
            ->values();

        $reviews = Review::whereIn('hotel_no', $hotelNos)
            ->latest()
            ->get()
            ->groupBy('hotel_no');

        $faq = $this->buildFaq($prefecture, $reviews, $tagsByHotelNo);

        return view('onsen.results', compact('results', 'prefecture', 'reviews', 'tagsByHotelNo', 'availableTags', 'tag', 'faq'));
    }

    private function buildFaq(string $prefecture, Collection $reviews, array $tagsByHotelNo): array
    {
        $kashikiriCount = collect($tagsByHotelNo)->filter(fn ($tags) => in_array('貸切風呂', $tags, true))->count();

        $topRated = $reviews->filter(fn ($group) => $group->count() > 0)
            ->sortByDesc(fn ($group) => $group->avg('rating'))
            ->first();
        $topRatedName = $topRated ? $topRated->first()->hotel_name : null;

        $faq = [
            [
                'question' => $prefecture . 'に貸切風呂がある温泉宿はありますか？',
                'answer' => $kashikiriCount > 0
                    ? "はい、{$prefecture}には貸切風呂を掲載している温泉宿が{$kashikiriCount}件あります。一覧の「貸切風呂」タグで絞り込めます。"
                    : "現在の掲載データでは、{$prefecture}で貸切風呂を明記している温泉宿は見つかりませんでした。",
            ],
            [
                'question' => $prefecture . 'の温泉宿の口コミは見られますか？',
                'answer' => '各温泉宿のページで、実際に宿泊・利用した方の口コミ（評価と感想）を確認できます。口コミはどなたでもログイン不要で投稿できます。',
            ],
        ];

        if ($topRatedName) {
            $faq[] = [
                'question' => $prefecture . 'でおすすめの温泉宿は？',
                'answer' => "口コミ評価をもとにすると、{$topRatedName}が現在最も高い評価を得ています。ただし好みは人それぞれのため、他の宿の口コミもあわせてご確認ください。",
            ];
        }

        return $faq;
    }

}
