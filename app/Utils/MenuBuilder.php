<?php

namespace App\Utils;

use Illuminate\Support\Arr;
use function Laravel\Prompts\select;

class MenuBuilder
{
    /**
     * Method to extract the options of the array
     *
     * @param array $options
     * @return array
     */
    private static function extractMenuOptions (array $options): array
    {
        return Arr::map($options, function ($value, string $key) {
            return $key;
        });
    }

    /**
     * Method to extract options of array without the selected part
     *
     * @param array $options
     * @param array $keys
     * @return array
     */
    private static function excludeOptions(array $options, array $keys): array
    {
        return Arr::except($options, $keys);
    }

    /**
     * Method to generate options of menu
     *
     * @param array $options
     * @param array $optionsToExclude
     * @return array
     */
    public static function createMenu(array $options, string $label, array $optionsToExclude = [])
    {
        $optionsProcessed = self::extractMenuOptions($options);
        if (isset($optionsToExclude) && count($optionsToExclude) > 0) {
            $optionsProcessed = self::excludeOptions($optionsProcessed, $optionsToExclude);
        }
        return select(
            label: $label,
            options: $optionsProcessed,
        );
        // return $optionsProcessed;
    }
}
