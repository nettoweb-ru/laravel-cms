<?php
return [
    'accepted' => 'Le champ doit être accepté.',
    'accepted_if' => 'Le champ doit être accepté lorsque :other est :value.',
    'active_url' => 'La valeur doit être une URL valide.',
    'after' => 'La valeur doit être une date postérieure à :date.',
    'after_or_equal' => 'La valeur doit être une date postérieure ou égale à :date.',
    'alpha' => 'La valeur ne doit contenir que des lettres.',
    'alpha_dash' => 'La valeur ne doit contenir que des lettres, des chiffres, des tirets et des traits de soulignement.',
    'alpha_num' => 'La valeur ne doit contenir que des lettres et des chiffres.',
    'array' => 'La valeur doit être un tableau.',
    'ascii' => 'La valeur ne doit contenir que des caractères alphanumériques et des symboles à un octet.',
    'before' => 'La valeur doit être une date antérieure à :date.',
    'before_or_equal' => 'La valeur doit être une date antérieure ou égale à :date.',
    'between' => [
        'array' => 'La valeur doit contenir entre :min et :max éléments.',
        'file' => 'La valeur doit être comprise entre :min et :max kilo-octets.',
        'numeric' => 'La valeur doit être comprise entre :min et :max.',
        'string' => 'La longueur de la valeur doit être comprise entre :min et :max caractères.',
    ],
    'boolean' => 'La valeur doit être vraie ou fausse.',
    'can' => 'La valeur contient une valeur non autorisée.',
    'confirmed' => 'La confirmation de la valeur ne correspond pas.',
    'current_password' => 'Le mot de passe est incorrect.',
    'date' => 'La valeur doit être une date valide.',
    'date_equals' => 'La valeur doit être une date égale à :date.',
    'date_format' => 'La valeur doit correspondre au format :format.',
    'decimal' => 'Value must have :decimal decimal places.',
    'declined' => 'La valeur doit être refusée.',
    'declined_if' => 'La valeur doit être refusée lorsque :other est :value.',
    'default_entity_required' => 'Le modèle par défaut est requis.',
    'different' => 'La valeur et :other doivent être différents.',
    'digits' => 'La valeur doit être composée de :digits chiffres.',
    'digits_between' => 'La longueur de la valeur doit être comprise entre :min et :max chiffres.',
    'dimensions' => "La valeur comporte des dimensions d'image non valides.",
    'distinct' => 'La valeur a une valeur en double.',
    'doesnt_end_with' => "La valeur ne doit pas se terminer par l'un des éléments suivants : :values.",
    'doesnt_start_with' => "La valeur ne doit pas commencer par l'un des éléments suivants : :values.",
    'email' => 'La valeur doit être une adresse e-mail valide.',
    'ends_with' => "La valeur doit se terminer par l'un des éléments suivants: :values.",
    'enum' => "Le :attribute sélectionné n'est pas valide.",
    'exists' => "Le :attribute sélectionné n'est pas valide.",
    'file' => 'La valeur doit être un fichier.',
    'filled' => 'La valeur doit avoir une valeur.',
    'gt' => [
        'array' => 'La valeur doit comporter plus de :value éléments.',
        'file' => 'La valeur doit être supérieure à :value kilo-octets.',
        'numeric' => 'La valeur doit être supérieure à :value.',
        'string' => 'La valeur doit être supérieure à :value caractères.',
    ],
    'gte' => [
        'array' => 'La valeur doit comporter :value éléments ou plus.',
        'file' => 'La valeur doit être supérieure ou égale à :value kilo-octets.',
        'numeric' => 'La valeur doit être supérieure ou égale à :value.',
        'string' => 'La valeur doit être supérieure ou égale à :value caractères.',
    ],
    'image' => 'La valeur doit être une image.',
    'in' => 'Le :attribute sélectionné n’est pas valide.',
    'in_array' => 'La valeur doit exister dans :other.',
    'integer' => 'La valeur doit être un entier.',
    'ip' => 'La valeur doit être une adresse IP valide.',
    'ipv4' => 'La valeur doit être une adresse IPv4 valide.',
    'ipv6' => 'La valeur doit être une adresse IPv6 valide.',
    'json' => 'La valeur doit être une chaîne JSON valide.',
    'lowercase' => 'La valeur doit être en minuscule.',
    'lt' => [
        'array' => 'La valeur doit contenir moins de :value éléments.',
        'file' => 'La valeur doit être inférieure à :value kilo-octets.',
        'numeric' => 'La valeur doit être inférieure à :value.',
        'string' => 'La valeur doit être inférieure à :value caractères.',
    ],
    'lte' => [
        'array' => 'La valeur ne doit pas contenir plus de :value éléments.',
        'file' => 'La valeur doit être inférieure ou égale à :value kilo-octets.',
        'numeric' => 'La valeur doit être inférieure ou égale à :value.',
        'string' => 'La valeur doit être inférieure ou égale à :value caractères.',
    ],
    'mac_address' => 'La valeur doit être une adresse MAC valide.',
    'max' => [
        'array' => 'La valeur ne doit pas contenir plus de :max éléments.',
        'file' => 'La valeur ne doit pas dépasser :max kilo-octets.',
        'numeric' => 'La valeur ne doit pas être supérieure à :max.',
        'string' => 'La valeur ne doit pas dépasser :max caractères.',
    ],
    'max_digits' => 'La valeur ne doit pas comporter plus de :max chiffres.',
    'mimes' => 'La valeur doit être un fichier de type: :values.',
    'mimetypes' => 'La valeur doit être un fichier de type: :values.',
    'min' => [
        'array' => 'La valeur doit comporter au moins :min éléments.',
        'file' => "La valeur doit être d'au moins :min kilo-octets.",
        'numeric' => "La valeur doit être d'au moins :min.",
        'string' => 'La valeur doit comporter au moins :min caractères.',
    ],
    'min_digits' => 'La valeur doit comporter au moins :min chiffres.',
    'missing' => 'La valeur doit manquer.',
    'missing_if' => 'La valeur doit être manquante lorsque :other est :value.',
    'missing_unless' => 'La valeur doit être manquante sauf si :other est :value.',
    'missing_with' => 'La valeur doit être manquante lorsque :values est présent.',
    'missing_with_all' => 'La valeur doit être manquante lorsque :values sont présentes.',
    'multiple_of' => 'La valeur doit être un multiple de :value.',
    'not_in' => "L'attribut :attribute n'est pas valide.",
    'not_regex' => "Le format de valeur n'est pas valide.",
    'numeric' => 'La valeur doit être un nombre.',
    'password' => [
        'letters' => 'La valeur doit contenir au moins une lettre.',
        'mixed' => 'La valeur doit contenir au moins une lettre majuscule et une lettre minuscule.',
        'numbers' => 'La valeur doit contenir au moins un chiffre.',
        'symbols' => 'La valeur doit contenir au moins un symbole.',
        'uncompromised' => ":attribute donné est apparu dans une fuite de données. Veuillez choisir un autre :attribute.",
    ],
    'present' => 'La valeur doit être présente.',
    'prohibited' => 'La valeur est interdite.',
    'prohibited_if' => 'La valeur est interdite lorsque :other est :value.',
    'prohibited_unless' => 'La valeur est interdite sauf si :other est dans :values.',
    'prohibits' => "La valeur interdit à :other d'être présent.",
    'regex' => "Le format de valeur n'est pas valide.",
    'required' => 'Valeur est requise.',
    'required_array_keys' => 'La valeur doit contenir des entrées pour: :values.',
    'required_if' => 'La valeur est requise lorsque :other est :value.',
    'required_if_accepted' => 'La valeur est requise lorsque :other est accepté.',
    'required_unless' => 'La valeur est requise sauf si :other est dans :values.',
    'required_with' => 'La valeur est requise lorsque :values est présent.',
    'required_with_all' => 'La valeur est requise lorsque :values sont présentes.',
    'required_without' => 'La valeur est requise lorsque :values n’est pas présent.',
    'required_without_all' => 'La valeur est requise lorsqu’aucune des :values n’est présente.',
    'same' => 'La valeur doit correspondre à :other.',
    'size' => [
        'array' => 'La valeur doit contenir :size éléments.',
        'file' => 'La valeur doit être de :size kilo-octets.',
        'numeric' => 'La valeur doit être :size.',
        'string' => 'La valeur doit être composée de :size caractères.',
    ],
    'starts_with' => "La valeur doit commencer par l'un des éléments suivants: :values.",
    'string' => 'La valeur doit être une chaîne.',
    'timezone' => 'La valeur doit être un fuseau horaire valide.',
    'unique' => ":attribute a déjà été pris.",
    'uploaded' => ":attribute n'a pas pu être téléchargé.",
    'uppercase' => 'La valeur doit être en majuscule.',
    'url' => 'La valeur doit être une URL valide.',
    'ulid' => 'La valeur doit être un ULID valide.',
    'uuid' => 'La valeur doit être un UUID valide.',
];
