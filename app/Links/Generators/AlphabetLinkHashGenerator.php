<?php

namespace App\Links\Generators;

use App\Links\Exceptions\ErrorGeneratingHash;

/**
 * Class AlphabetLinkHashGenerator
 * @package App\Links\Generators
 */
class AlphabetLinkHashGenerator implements LinkHashGenerator
{
    const SIZE = 4;
    const ALPHABET = 'aPGHmB9cK6ylnzwMeEYgt2Dv0jTNWQrkbFJ43VSUsuxoOX1I8f7Cdqh5iLAZpR';

    /**
     * @param int $delta
     * @return string
     * @throws \Exception
     */
    public function getHashByNumber(int $delta): string
    {
        $alphabetLength = strlen(self::ALPHABET);
        $newHashPositions = $this->base10ToAnyConvert($delta, $alphabetLength);
        if (count($newHashPositions) > self::SIZE) {
            throw new ErrorGeneratingHash('Too big number for current amount of digits and alphabet');
        }

        $newHashPositions = array_merge(
            array_fill(0, self::SIZE - count($newHashPositions), 0),
            $newHashPositions
        );

        $newHash = '';
        foreach ($newHashPositions as $posInAlphabet) {
            $newHash .= self::ALPHABET[$posInAlphabet];
        }

        return $newHash;
    }

    /**
     * @param string $hash
     * @return int
     * @throws ErrorGeneratingHash
     */
    public function getNumberByHash(string $hash): int
    {
        $alphabetLength = strlen(self::ALPHABET);
        $hashLen = strlen($hash);
        $number = 0;
        for ($i = $hashLen; $i > 0; --$i) {
            $curChar = $hash[$i - 1];
            $curPos = strpos(self::ALPHABET, $curChar);
            $number += $curPos * ($alphabetLength ** ($hashLen - $i));
        }
        if (!is_int($number)) {
            throw new ErrorGeneratingHash('Too big hash');
        }
        return $number;
    }

    /**
     * @param int $input
     * @param int $toBase
     * @return array
     */
    private function base10ToAnyConvert(int $input, int $toBase): array
    {
        $output = [];
        do {
            array_unshift($output, $input % $toBase);
            $input = floor($input / $toBase);
        } while ($input > 0);

        return $output;
    }

}
