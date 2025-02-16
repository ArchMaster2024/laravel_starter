<?php

namespace App\Core\Handlers;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\select;
use function Laravel\Prompts\multiselect;

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
        multiselect(label:$label, options: $keys, required : false );
    }

    public  function confirm(string $label){
        $result = confirm(label: $label, required : true);
        return $result;
    }
}
