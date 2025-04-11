<?php

namespace Ivacuum\Generic\Utilities;

class PhoneHelper
{
    // Индекс массива — первая цифра кода города
    const CODE_REGEXES = [
        3 => '/^(
            301(?:2|30|4[25])| # Бурятия
            302(?:2)| # Забайкальский край
            341(?:2)| # Удмуртия
            342(?:4)| # Пермский край
            343(?:5|69|97)| # Свердловская область
            345(?:2|42)| # Тюменская область
            346(?:2|7)| # Ханты-Мансийский АО
            347(?:3|61)| # Башкортостан
            349(?:6)| # Ямало-Ненецкий АО
            351(?:3|9)| # Челябинская область
            352(?:2|52)| # Курганская область
            353(?:2)| # Оренбургская область
            365(?:2|6[12])| # Крым
            381(?:2)| # Омская область
            382(?:2)| # Томская область
            383(?:62)| # Новосибирская область
            384(?:2|3)| # Кемеровская область
            385(?:2|4)| # Алтайский край
            388(?:22)| # Алтай
            390(?:2)| # Хакасия
            391(?:3[12]|4[45]|5[135]|61|9)| # Красноярский край
            394(?:22)| # Тыва
            395(?:1[0-9]|2|3(?:0)|4[1-9]|50|6[1-9]) # Иркутская область
        )/x',
        4 => '/^(
            401(?:2|4[1-9]|532|566[068]|5[0-9]|6[1-9])| # Калининградская область
            411(?:2|47)| # Саха (Якутия)
            413(?:2)| # Магаданская область
            415(?:2)| # Камчатский край
            416(?:2)| # Амурская область
            421(?:2|7)| # Хабаровский край
            423| # Приморский край
            424(?:2)| # Сахалинская область
            426(?:22)| # Еврейская АО
            427(?:22|3[278])| # Чукотский АО
            471(?:2)| # Курская область
            472(?:2|3[46]|41|5)| # Белгородская область
            473(?:62|91)| # Воронежская область
            474(?:2|67)| # Липецкая область
            475(?:2)| # Тамбовская область
            481(?:2)| # Смоленская область
            482(?:2|49|51)| # Тверская область
            483(?:2)| # Брянская область
            484(?:2|3[1-9]|4[1-9]|5[1-9])| # Калужская область
            485(?:2)| # Ярославская область
            486(?:2)| # Орловская область
            487(?:2|3[1-9]|4[1-9]|5[1-9]|6[1-9])| # Тульская область
            491(?:2)| # Рязанская область
            492(?:2)| # Владимирская область
            493(?:2)| # Ивановская область
            494(?:2|37)| # Костромская область
            49[59]| # Москва
            496(?:34|4[12]|5[138])| # Московская область
            497| # Московская область
            498(?:48) # Московская область
        )/x',
        8 => '/^(
            80[0-9]| # 8 80x
            811(?:2|31|53)| # Псковская область
            812| # Санкт-Петербург
            813(?:6[13678]|765[039]|7[012345689])| # Ленинградская область
            814(?:2)| # Карелия
            815(?:2)| # Мурманская область
            816(?:2)| # Новгородская область
            817(?:3[89])|820(?:2)| # Вологодская область
            818(?:2|3[46]|5[03])| # Архангельская область и Ненецкий АО
            821(?:2)| # Коми
            831(?:3|4[457]|59|61|7[017])| # Нижегородская область
            833(?:2|34)| # Кировская область
            834(?:2)| # Мордовия
            835(?:2|40)| # Чувашия
            836(?:2)| # Марий-Эл
            841(?:43|55)| # Пензенская область
            842(?:2)| # Ульяновская область
            843(?:42)|855(?:2|3|5[78]|5|9[245])| # Татарстан
            844(?:2|3|42|5[47]|65)| # Волгоградская область
            845(?:2|3|4[05]|5[25]|6[47]|74|93)| # Саратовская область
            846(?:3[59]|4|5[67]|6[13]|76)|848(?:2|62)| # Самарская область
            847(?:22|3[1346]|4[12467])| # Калмыкия
            851(?:2|47|71)| # Астраханская область
            861(?:3[12378]|4[168]|67|7)|862| # Краснодарский край
            863(?:42|4|5[04]|5|6[59]|6|72|9)| # Ростовская область
            865(?:2|59)|879(?:22|3)| # Ставропольский край
            866(?:2|31|62)| # Кабардино-Балкария
            867(?:2|3[1234679])| # Северная Осетия
            869(?:2)| # Севастополь
            871(?:2|52)| # Чеченская республика
            872(?:2|46)| # Дагестан
            873(?:2|4)| # Ингушетия
            877(?:2)| # Адыгея
            878(?:2|7[89]) # Карачаево-Черкессия
        )/x',
    ];

    const PRINT_PREFIX = '+7';
    const TOLL_FREE_PREFIX = '8';
    const NORMALIZED_PREFIX = '7';

    public function dashed(string $phone): string
    {
        return match (strlen($phone)) {
            5 => vsprintf('%s-%s-%s', sscanf($phone, '%1s%2s%2s')),
            6 => vsprintf('%s-%s-%s', sscanf($phone, '%2s%2s%2s')),
            7 => vsprintf('%s-%s-%s', sscanf($phone, '%3s%2s%2s')),
            default => $phone,
        };
    }

    public function format(string|null $phones, string|null $prefix = null): array
    {
        if (null === $phones) {
            return [];
        }

        $result = [];

        foreach (explode(',', $this->tidy($phones)) as $phone) {
            $result[] = $this->formatOne($phone, $prefix);
        }

        return $result;
    }

    public function formatFirst(string $phones, string|null $prefix = null): string
    {
        return $this->formatOne(explode(',', $this->tidy($phones))[0], $prefix);
    }

    public function formatOne(string $phone, string|null $prefix = null): string
    {
        $len = strlen($phone);

        if ($len <= 7) {
            return $this->dashed($phone);
        }

        // Неправильные телефоны как есть
        if ($len > 11) {
            return $phone;
        }

        // Удаление кода страны => 9101002035
        if ($len > 10) {
            $phone = substr($phone, 1);
            $len--;
        }

        $code = '';
        $prefix = $prefix ?: static::PRINT_PREFIX;

        if ($len === 10) {
            $firstDigit = (int) $phone[0];

            if (isset(static::CODE_REGEXES[$firstDigit]) &&
                preg_match(static::CODE_REGEXES[$firstDigit], $phone, $matches)
            ) {
                $code = $matches[1];
                $phone = substr($phone, strlen($code));

                if (str_starts_with($code, '80')) {
                    $prefix = static::TOLL_FREE_PREFIX;
                }

                return "{$prefix} {$code} " . $this->dashed($phone);
            }

            // Для неопознанных городов код города считается трехсимвольным
            // Мобильные телефоны тоже попадают под это условие
            $code = substr($phone, 0, 3);
            $phone = substr($phone, 3);

            return "{$prefix} {$code} " . $this->dashed($phone);
        }

        return $code
            ? "{$prefix} {$code} " . $this->dashed($phone)
            : "{$prefix}{$phone}";
    }

    public function normalize(string $phone, string $code = ''): string
    {
        $len = strlen($phone);

        if ($code && 10 === strlen($code) + $len) {
            return static::NORMALIZED_PREFIX . $code . $phone;
        }

        if (10 === $len) {
            return static::NORMALIZED_PREFIX . $phone;
        }

        if (11 === $len) {
            return static::NORMALIZED_PREFIX . mb_substr($phone, 1);
        }

        return $phone;
    }

    public function normalizeMany(string $phones, string $code = ''): string
    {
        $normalized = [];

        foreach (explode(',', $phones) as $phone) {
            $normalized[] = $this->normalize($phone, $code);
        }

        return implode(',', $normalized);
    }

    public function isValid(string $phone): bool
    {
        return 11 === strlen($phone);
    }

    public function tidy(string $phones): string
    {
        return trim(preg_replace('/[^\d,]/', '', str_replace(';', ',', $phones)), ',');
    }

    public function tidyAndNormalize(string $phone, string $code = ''): string
    {
        return $this->normalize($this->tidy($phone), $code);
    }

    public function tidyAndNormalizeMany(string $phones, string $code = ''): string
    {
        return $this->normalizeMany($this->tidy($phones), $code);
    }
}
