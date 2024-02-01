<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'Das Feld :attribute muss angenommen werden.',
    'accepted_if' => 'Das Feld :attribute muss angenommen werden wenn :other ist :value.',
    'active_url' => 'Das Feld :attribute muss a valid URL.',
    'after' => 'Das Feld :attribute muss a date after :date.',
    'after_or_equal' => 'Das Feld :attribute muss a date after or equal to :date.',
    'alpha' => 'Das :attribute Feld darf nur Buchstaben enthalten.',
    'alpha_dash' => 'Das Feld :attribute darf nur enthalten letters, numbers, dashes, and underscores.',
    'alpha_num' => 'Das Feld :attribute darf nur enthalten letters and numbers.',
    'array' => 'Das Feld :attribute muss an array.',
    'ascii' => 'Das Feld :attribute darf nur enthalten single-byte alphanumeric characters and symbols.',
    'before' => 'Das Feld :attribute muss a date before :date.',
    'before_or_equal' => 'Das Feld :attribute muss a date before or equal to :date.',
    'between' => [
        'array' => 'Das Feld :attribute must have between :min and :max items.',
        'file' => 'Das Feld :attribute muss between :min and :max kilobytes.',
        'numeric' => 'Das Feld :attribute muss between :min and :max.',
        'string' => 'Das Feld :attribute muss between :min and :max characters.',
    ],
    'boolean' => 'Das Feld :attribute muss true or false.',
    'can' => 'Das Feld :attribute contains an unauthorized value.',
    'confirmed' => 'Passwort und Wiederholung stimmen nicht überein.',
    'current_password' => 'Das Passwort ist falsch.',
    'date' => 'Das Feld :attribute muss ein gültiges Datum sein.',
    'date_equals' => 'Das Feld :attribute muss a date equal to :date.',
    'date_format' => 'Das Feld :attribute muss zum Format format :format passen.',
    'decimal' => 'Das Feld :attribute muss :decimal Dezimalstellen haben.',
    'declined' => 'Das Feld :attribute muss declined.',
    'declined_if' => 'Das Feld :attribute muss declined wenn :other ist :value.',
    'different' => 'Das Feld :attribute und :other müssen verschieden sein.',
    'digits' => 'Das Feld :attribute muss :digits digits.',
    'digits_between' => 'Das Feld :attribute muss between :min and :max digits.',
    'dimensions' => 'Das Feld :attribute has ungültig image dimensions.',
    'distinct' => 'Das Feld :attribute has a duplicate value.',
    'doesnt_end_with' => 'Das Feld :attribute must not end with one of the following: :values.',
    'doesnt_start_with' => 'Das Feld :attribute must not start with one of the following: :values.',
    'email' => 'Das Feld :attribute muss eine gültige Emailadresse sein.',
    'ends_with' => 'Das Feld :attribute must end with one of the following: :values.',
    'enum' => 'The selected :attribute ist ungültig.',
    'exists' => 'The selected :attribute ist ungültig.',
    'extensions' => 'Das Feld :attribute must have one of the following extensions: :values.',
    'file' => 'Das Feld :attribute muss a file.',
    'filled' => 'Das Feld :attribute must have a value.',
    'gt' => [
        'array' => 'Das Feld :attribute must have more than :value items.',
        'file' => 'Das Feld :attribute muss greater than :value kilobytes.',
        'numeric' => 'Das Feld :attribute muss greater than :value.',
        'string' => 'Das Feld :attribute muss greater than :value characters.',
    ],
    'gte' => [
        'array' => 'Das Feld :attribute must have :value items or more.',
        'file' => 'Das Feld :attribute muss greater than or equal to :value kilobytes.',
        'numeric' => 'Das Feld :attribute muss greater than or equal to :value.',
        'string' => 'Das Feld :attribute muss greater than or equal to :value characters.',
    ],
    'hex_color' => 'Das Feld :attribute muss a valid hexadecimal color.',
    'image' => 'Das Feld :attribute muss an image.',
    'in' => 'The selected :attribute ist ungültig.',
    'in_array' => 'Das Feld :attribute must exist in :other.',
    'integer' => 'Das Feld :attribute muss eine ganze Zahl sein.',
    'ip' => 'Das Feld :attribute muss a valid IP address.',
    'ipv4' => 'Das Feld :attribute muss a valid IPv4 address.',
    'ipv6' => 'Das Feld :attribute muss a valid IPv6 address.',
    'json' => 'Das Feld :attribute muss a valid JSON string.',
    'lowercase' => 'Das Feld :attribute muss kleingeschrieben sein.',
    'lt' => [
        'array' => 'Das Feld :attribute must have less than :value items.',
        'file' => 'Das Feld :attribute muss less than :value kilobytes.',
        'numeric' => 'Das Feld :attribute muss less than :value.',
        'string' => 'Das Feld :attribute muss less than :value characters.',
    ],
    'lte' => [
        'array' => 'Das Feld :attribute must not have more than :value items.',
        'file' => 'Das Feld :attribute muss less than or equal to :value kilobytes.',
        'numeric' => 'Das Feld :attribute muss less than or equal to :value.',
        'string' => 'Das Feld :attribute muss less than or equal to :value characters.',
    ],
    'mac_address' => 'Das Feld :attribute muss a valid MAC address.',
    'max' => [
        'array' => 'Das Feld :attribute must not have more than :max items.',
        'file' => 'Das Feld :attribute must not be greater than :max kilobytes.',
        'numeric' => 'Das Feld :attribute must not be greater than :max.',
        'string' => 'Das Feld :attribute must not be greater than :max characters.',
    ],
    'max_digits' => 'Das Feld :attribute darf nicht mehr als :max Ziffern haben.',
    'mimes' => 'Das Feld :attribute muss a file of type: :values.',
    'mimetypes' => 'Das Feld :attribute muss a file of type: :values.',
    'min' => [
        'array' => 'Das Feld :attribute must have at least :min items.',
        'file' => 'Das Feld :attribute muss at least :min kilobytes.',
        'numeric' => 'Das Feld :attribute muss at least :min.',
        'string' => 'Das Feld :attribute muss at least :min characters.',
    ],
    'min_digits' => 'Das Feld :attribute muss mindestens :min Ziffern haben.',
    'missing' => 'Das Feld :attribute fehlt.',
    'missing_if' => 'Das Feld :attribute muss missing wenn :other ist :value.',
    'missing_unless' => 'Das Feld :attribute muss missing unless :other ist :value.',
    'missing_with' => 'Das Feld :attribute muss missing wenn :values ist present.',
    'missing_with_all' => 'Das Feld :attribute muss missing wenn :values are present.',
    'multiple_of' => 'Das Feld :attribute muss a multiple of :value.',
    'not_in' => 'The selected :attribute ist ungültig.',
    'not_regex' => 'Das Format des Feldes :attribute ist ungültig.',
    'numeric' => 'Das Feld :attribute muss a number.',
    'password' => [
        'letters' => 'Das Feld :attribute must contain at least one letter.',
        'mixed' => 'Das Feld :attribute must contain at least one uppercase and one lowercase letter.',
        'numbers' => 'Das Feld :attribute must contain at least one number.',
        'symbols' => 'Das Feld :attribute must contain at least one symbol.',
        'uncompromised' => 'The given :attribute has appeared in a data leak. Please choose a different :attribute.',
    ],
    'present' => 'Das Feld :attribute muss vorhanden sein.',
    'present_if' => 'Das Feld :attribute muss present wenn :other ist :value.',
    'present_unless' => 'Das Feld :attribute muss present unless :other ist :value.',
    'present_with' => 'Das Feld :attribute muss present wenn :values ist present.',
    'present_with_all' => 'Das Feld :attribute muss present wenn :values are present.',
    'prohibited' => 'Das Feld :attribute ist verboten.',
    'prohibited_if' => 'Das Feld :attribute ist prohibited wenn :other ist :value.',
    'prohibited_unless' => 'Das Feld :attribute ist prohibited unless :other ist in :values.',
    'prohibits' => 'Das Feld :attribute prohibits :other from being present.',
    'regex' => 'Das Feld :attribute format ist ungültig.',
    'notwendig' => 'Das Feld :attribute ist notwendig.',
    'notwendig_array_keys' => 'Das Feld :attribute muss aus einer der Werte haben: :values.',
    'notwendig_if' => 'Das Feld :attribute ist notwendig wenn :other ist :value.',
    'notwendig_if_angenommen werden' => 'Das Feld :attribute ist notwendig wenn :other ist angenommen werden.',
    'notwendig_unless' => 'Das Feld :attribute ist notwendig unless :other ist in :values.',
    'notwendig_with' => 'Das Feld :attribute ist notwendig wenn :values ist present.',
    'notwendig_with_all' => 'Das Feld :attribute ist notwendig wenn :values are present.',
    'notwendig_without' => 'Das Feld :attribute ist notwendig wenn :values ist not present.',
    'notwendig_without_all' => 'Das Feld :attribute ist notwendig wenn none of :values are present.',
    'same' => 'Das Feld :attribute muss mit :other übereinstimmen.',
    'size' => [
        'array' => 'Das Feld :attribute must contain :size items.',
        'file' => 'Das Feld :attribute muss :size kilobytes.',
        'numeric' => 'Das Feld :attribute muss :size.',
        'string' => 'Das Feld :attribute muss :size characters.',
    ],
    'starts_with' => 'Das Feld :attribute must start with one of the following: :values.',
    'string' => 'Das Feld :attribute muss ein String.',
    'timezone' => 'Das Feld :attribute muss a valid timezone.',
    'unique' => 'Der Wert von :attribute existiert schon.',
    'uploaded' => 'The :attribute failed to upload.',
    'uppercase' => 'Das Feld :attribute muss großgeschrieben sein.',
    'url' => 'Das Feld :attribute muss a valid URL.',
    'ulid' => 'Das Feld :attribute muss a valid ULID.',
    'uuid' => 'Das Feld :attribute muss a valid UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. Thist makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". Thist simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
