<?php

namespace App\Core\Handlers;

use Illuminate\Support\Arr;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\select;
use function Laravel\Prompts\multiselect;
use function Laravel\Prompts\progress;

class Input {

    public  function select(string $label, array $options){
        $keys = array_keys($options);
        $result = select(label:$label, options: $keys, required: true);

        return (object) [
            'key' => $result,
            'option' => $options[$result]
        ];
    }

    public function choice(string $label, array $options)
    {
        $keys = array_keys($options);
        $result = multiselect(label:$label, options: $keys, required : false );

        $selectedOptions = Arr::where(
            $options,
            fn ($value, $key): bool => in_array($key, $result)
        );

        return (object) [
            'key' => $result,
            'option' => $selectedOptions
        ];
    }

    public  function confirm(string $label): bool{
        $result = confirm(label: $label, required : true);
        return $result;
    }
}