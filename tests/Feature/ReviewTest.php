<?php

namespace Tests\Feature;

use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_review_can_be_submitted(): void
    {
        $response = $this->post(route('reviews.store'), [
            'hotel_no' => 16089,
            'hotel_name' => 'テスト温泉旅館',
            'prefecture' => '東京都',
            'nickname' => 'テスト太郎',
            'rating' => 5,
            'comment' => 'とても良い温泉でした。',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('reviews', [
            'hotel_no' => 16089,
            'nickname' => 'テスト太郎',
            'rating' => 5,
        ]);
    }

    public function test_review_without_nickname_defaults_to_anonymous(): void
    {
        $this->post(route('reviews.store'), [
            'hotel_no' => 16089,
            'hotel_name' => 'テスト温泉旅館',
            'prefecture' => '東京都',
            'rating' => 4,
            'comment' => '良かったです。',
        ]);

        $this->assertDatabaseHas('reviews', ['nickname' => '匿名']);
    }

    public function test_honeypot_field_silently_rejects_the_review(): void
    {
        $this->post(route('reviews.store'), [
            'hotel_no' => 16089,
            'hotel_name' => 'テスト温泉旅館',
            'prefecture' => '東京都',
            'rating' => 5,
            'comment' => 'スパムコメントです。',
            'website' => 'https://spam.example.com',
        ]);

        $this->assertDatabaseCount('reviews', 0);
    }

    public function test_ng_word_is_rejected(): void
    {
        $response = $this->post(route('reviews.store'), [
            'hotel_no' => 16089,
            'hotel_name' => 'テスト温泉旅館',
            'prefecture' => '東京都',
            'rating' => 1,
            'comment' => 'この宿は死ねばいいのに',
        ]);

        $response->assertSessionHasErrors('comment');
        $this->assertDatabaseCount('reviews', 0);
    }

    public function test_rating_out_of_range_is_rejected(): void
    {
        $response = $this->post(route('reviews.store'), [
            'hotel_no' => 16089,
            'hotel_name' => 'テスト温泉旅館',
            'prefecture' => '東京都',
            'rating' => 6,
            'comment' => '評価が範囲外です。',
        ]);

        $response->assertSessionHasErrors('rating');
    }

    public function test_search_results_page_shows_existing_reviews(): void
    {
        Http::fake([
            'openapi.rakuten.co.jp/*' => Http::response([
                'hotels' => [
                    [
                        'hotel' => [
                            [
                                'hotelBasicInfo' => [
                                    'hotelNo' => 16089,
                                    'hotelName' => 'テスト温泉旅館',
                                    'address1' => '東京都',
                                    'address2' => '千代田区1-1-1',
                                ],
                            ],
                        ],
                    ],
                ],
            ], 200),
        ]);

        Review::create([
            'hotel_no' => 16089,
            'hotel_name' => 'テスト温泉旅館',
            'prefecture' => '東京都',
            'nickname' => '口コミ太郎',
            'rating' => 5,
            'comment' => '最高の温泉でした！',
            'ip_hash' => hash('sha256', '127.0.0.1'),
        ]);

        $response = $this->get('/search?prefecture=' . urlencode('東京都'));

        $response->assertStatus(200);
        $response->assertSee('口コミ太郎');
        $response->assertSee('最高の温泉でした！');
    }
}
