<?php

namespace Rhaymison\ElephantChain\Helpers;

final class MathHelpers
{

    /**
     * @param array $vectorA
     * @param array $vectorB
     * @return float|int
     */
    public static function cosineSimilaruty(array $vectorA, array $vectorB): float|int
    {
        $dotProduct = self::dotProduct($vectorA, $vectorB);
        $magnitudeA = self::magnitude($vectorA);
        $magnitudeB = self::magnitude($vectorB);

        if ($magnitudeA == 0 || $magnitudeB == 0) {
            return 0;
        }

        return $dotProduct / ($magnitudeA * $magnitudeB);
    }

    /**
     * @param array $vectorA
     * @param array $vectorB
     * @return float|int
     */
    public static function dotProduct(array $vectorA, array $vectorB): float|int
    {
        $dotProduct = 0;
        for ($i = 0; $i < count($vectorA); $i++) {
            $dotProduct += $vectorA[$i] * $vectorB[$i];
        }
        return $dotProduct;
    }

    /**
     * @param array $vector
     * @return float
     */
    public static function magnitude(array $vector): float
    {
        $sum = 0;
        for ($i = 0; $i < count($vector); $i++) {
            $sum += $vector[$i] * $vector[$i];
        }
        return sqrt($sum);
    }

}