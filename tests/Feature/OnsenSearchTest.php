<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class OnsenSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_lists_all_47_prefectures(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('北海道');
        $response->assertSee('沖縄県');
    }

    public function test_search_without_prefecture_redirects_to_index(): void
    {
        $response = $this->get('/search');

        $response->assertRedirect(route('onsen.index'));
    }

    public function test_search_renders_hotels_returned_by_rakuten(): void
    {
        Http::fake([
            'openapi.rakuten.co.jp/*' => Http::response([
                'hotels' => [
                    [
                        'hotel' => [
                            [
                                'hotelBasicInfo' => [
                                    'hotelName' => 'テスト温泉旅館',
                                    'hotelInformationUrl' => 'https://example.com/hotel/1',
                                    'hotelImageUrl' => 'https://example.com/hotel/1.jpg',
                                    'address1' => '東京都',
                                    'address2' => '千代田区1-1-1',
                                ],
                            ],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $response = $this->get('/search?prefecture=' . urlencode('東京都'));

        $response->assertStatus(200);
        $response->assertSee('テスト温泉旅館');
        $response->assertSee('東京都千代田区1-1-1');
    }

    public function test_search_excludes_hotels_from_other_prefectures(): void
    {
        Http::fake([
            'openapi.rakuten.co.jp/*' => Http::response([
                'hotels' => [
                    [
                        'hotel' => [
                            [
                                'hotelBasicInfo' => [
                                    'hotelName' => '東京都の宿',
                                    'address1' => '東京都',
                                    'address2' => '千代田区1-1-1',
                                ],
                            ],
                        ],
                    ],
                    [
                        'hotel' => [
                            [
                                'hotelBasicInfo' => [
                                    'hotelName' => '新潟県の宿',
                                    'address1' => '新潟県',
                                    'address2' => '南蒲原郡田上町1-1-1',
                                ],
                            ],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $response = $this->get('/search?prefecture=' . urlencode('東京都'));

        $response->assertStatus(200);
        $response->assertSee('東京都の宿');
        $response->assertDontSee('新潟県の宿');
    }

    public function test_search_shows_empty_message_when_no_hotels_found(): void
    {
        Http::fake([
            'openapi.rakuten.co.jp/*' => Http::response(['hotels' => []], 200),
        ]);

        $response = $this->get('/search?prefecture=' . urlencode('沖縄県'));

        $response->assertStatus(200);
        $response->assertSee('見つかりませんでした');
    }

    public function test_search_handles_api_failure_gracefully(): void
    {
        Http::fake([
            'openapi.rakuten.co.jp/*' => Http::response(null, 500),
        ]);

        $response = $this->get('/search?prefecture=' . urlencode('京都府'));

        $response->assertStatus(200);
        $response->assertSee('見つかりませんでした');
    }
}
