<?php

namespace Tests\Unit;

use App\Support\HotelTagger;
use PHPUnit\Framework\TestCase;

class HotelTaggerTest extends TestCase
{
    public function test_extracts_tags_from_hotel_name_and_special(): void
    {
        $tags = HotelTagger::extract('貸切風呂の宿', 'サウナも人気です');

        $this->assertContains('貸切風呂', $tags);
        $this->assertContains('サウナ', $tags);
    }

    public function test_returns_empty_array_when_no_keywords_match(): void
    {
        $tags = HotelTagger::extract('ふつうの宿', '');

        $this->assertSame([], $tags);
    }

    public function test_does_not_duplicate_tags_for_repeated_keywords(): void
    {
        $tags = HotelTagger::extract('露天風呂露天風呂の宿', '');

        $this->assertSame(['露天風呂'], $tags);
    }
}
