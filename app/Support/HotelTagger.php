<?php

namespace App\Support;

class HotelTagger
{
    private const KEYWORD_TAGS = [
        '貸切' => '貸切風呂',
        '露天風呂' => '露天風呂',
        'サウナ' => 'サウナ',
        '日帰り' => '日帰り利用可',
        'ペット' => 'ペット可',
        '一人旅' => '一人旅歓迎',
        '混浴' => '混浴',
        '秘湯' => '秘湯',
        '子連れ' => '子連れ歓迎',
        '女子会' => '女子会向け',
    ];

    /**
     * @return list<string>
     */
    public static function extract(string $hotelName, string $hotelSpecial): array
    {
        $text = $hotelName . ' ' . $hotelSpecial;

        $tags = [];
        foreach (self::KEYWORD_TAGS as $keyword => $label) {
            if (mb_stripos($text, $keyword) !== false) {
                $tags[] = $label;
            }
        }

        return $tags;
    }

    /**
     * @return list<string>
     */
    public static function allLabels(): array
    {
        return array_values(self::KEYWORD_TAGS);
    }
}
