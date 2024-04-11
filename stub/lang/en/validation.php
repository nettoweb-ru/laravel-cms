<?php
return [
    'accepted' => 'Field must be accepted.',
    'accepted_if' => 'Field must be accepted when :other is :value.',
    'active_url' => 'Value must be a valid URL.',
    'after' => 'Value must be a date after :date.',
    'after_or_equal' => 'Value must be a date after or equal to :date.',
    'alpha' => 'Value must only contain letters.',
    'alpha_dash' => 'Value must only contain letters, numbers, dashes, and underscores.',
    'alpha_num' => 'Value must only contain letters and numbers.',
    'array' => 'Value must be an array.',
    'ascii' => 'Value must only contain single-byte alphanumeric characters and symbols.',
    'before' => 'Value must be a date before :date.',
    'before_or_equal' => 'Value must be a date before or equal to :date.',
    'between' => [
        'array' => 'Value must have between :min and :max items.',
        'file' => 'Value must be between :min and :max kilobytes.',
        'numeric' => 'Value must be between :min and :max.',
        'string' => 'Value length must be between :min and :max characters.',
    ],
    'boolean' => 'Value must be true or false.',
    'can' => 'Value contains an unauthorized value.',
    'confirmed' => 'Value confirmation does not match.',
    'current_password' => 'The password is incorrect.',
    'date' => 'Value must be a valid date.',
    'date_equals' => 'Value must be a date equal to :date.',
    'date_format' => 'Value must match the format :format.',
    'decimal' => 'Value must have :decimal decimal places.',
    'declined' => 'Value must be declined.',
    'declined_if' => 'Value must be declined when :other is :value.',
    'default_entity_required' => 'Default model is required.',
    'different' => 'Value and :other must be different.',
    'digits' => 'Value must be :digits digits.',
    'digits_between' => 'Value length must be between :min and :max digits.',
    'dimensions' => 'Value has invalid image dimensions.',
    'distinct' => 'Value has a duplicate value.',
    'doesnt_end_with' => 'Value must not end with one of the following: :values.',
    'doesnt_start_with' => 'Value must not start with one of the following: :values.',
    'email' => 'Value must be a valid email address.',
    'ends_with' => 'Value must end with one of the following: :values.',
    'enum' => 'The selected :attribute is invalid.',
    'exists' => 'The selected :attribute is invalid.',
    'file' => 'Value must be a file.',
    'filled' => 'Value must have a value.',
    'gt' => [
        'array' => 'Value must have more than :value items.',
        'file' => 'Value must be greater than :value kilobytes.',
        'numeric' => 'Value must be greater than :value.',
        'string' => 'Value must be greater than :value characters.',
    ],
    'gte' => [
        'array' => 'Value must have :value items or more.',
        'file' => 'Value must be greater than or equal to :value kilobytes.',
        'numeric' => 'Value must be greater than or equal to :value.',
        'string' => 'Value must be greater than or equal to :value characters.',
    ],
    'image' => 'Value must be an image.',
    'in' => 'The selected :attribute is invalid.',
    'in_array' => 'Value must exist in :other.',
    'integer' => 'Value must be an integer.',
    'ip' => 'Value must be a valid IP address.',
    'ipv4' => 'Value must be a valid IPv4 address.',
    'ipv6' => 'Value must be a valid IPv6 address.',
    'json' => 'Value must be a valid JSON string.',
    'lowercase' => 'Value must be lowercase.',
    'lt' => [
        'array' => 'Value must have less than :value items.',
        'file' => 'Value must be less than :value kilobytes.',
        'numeric' => 'Value must be less than :value.',
        'string' => 'Value must be less than :value characters.',
    ],
    'lte' => [
        'array' => 'Value must not have more than :value items.',
        'file' => 'Value must be less than or equal to :value kilobytes.',
        'numeric' => 'Value must be less than or equal to :value.',
        'string' => 'Value must be less than or equal to :value characters.',
    ],
    'mac_address' => 'Value must be a valid MAC address.',
    'max' => [
        'array' => 'Value must not have more than :max items.',
        'file' => 'Value must not be greater than :max kilobytes.',
        'numeric' => 'Value must not be greater than :max.',
        'string' => 'Value must not be greater than :max characters.',
    ],
    'max_digits' => 'Value must not have more than :max digits.',
    'mimes' => 'Value must be a file of type: :values.',
    'mimetypes' => 'Value must be a file of type: :values.',
    'min' => [
        'array' => 'Value must have at least :min items.',
        'file' => 'Value must be at least :min kilobytes.',
        'numeric' => 'Value must be at least :min.',
        'string' => 'Value must be at least :min characters.',
    ],
    'min_digits' => 'Value must have at least :min digits.',
    'missing' => 'Value must be missing.',
    'missing_if' => 'Value must be missing when :other is :value.',
    'missing_unless' => 'Value must be missing unless :other is :value.',
    'missing_with' => 'Value must be missing when :values is present.',
    'missing_with_all' => 'Value must be missing when :values are present.',
    'multiple_of' => 'Value must be a multiple of :value.',
    'not_in' => 'The selected :attribute is invalid.',
    'not_regex' => 'Value format is invalid.',
    'numeric' => 'Value must be a number.',
    'password' => [
        'letters' => 'Value must contain at least one letter.',
        'mixed' => 'Value must contain at least one uppercase and one lowercase letter.',
        'numbers' => 'Value must contain at least one number.',
        'symbols' => 'Value must contain at least one symbol.',
        'uncompromised' => 'The given :attribute has appeared in a data leak. Please choose a different :attribute.',
    ],
    'present' => 'Value must be present.',
    'prohibited' => 'Value is prohibited.',
    'prohibited_if' => 'Value is prohibited when :other is :value.',
    'prohibited_unless' => 'Value is prohibited unless :other is in :values.',
    'prohibits' => 'Value prohibits :other from being present.',
    'regex' => 'Value format is invalid.',
    'required' => 'Value is required.',
    'required_array_keys' => 'Value must contain entries for: :values.',
    'required_if' => 'Value is required when :other is :value.',
    'required_if_accepted' => 'Value is required when :other is accepted.',
    'required_unless' => 'Value is required unless :other is in :values.',
    'required_with' => 'Value is required when :values is present.',
    'required_with_all' => 'Value is required when :values are present.',
    'required_without' => 'Value is required when :values is not present.',
    'required_without_all' => 'Value is required when none of :values are present.',
    'same' => 'Value must match :other.',
    'size' => [
        'array' => 'Value must contain :size items.',
        'file' => 'Value must be :size kilobytes.',
        'numeric' => 'Value must be :size.',
        'string' => 'Value must be :size characters.',
    ],
    'starts_with' => 'Value must start with one of the following: :values.',
    'string' => 'Value must be a string.',
    'timezone' => 'Value must be a valid timezone.',
    'unique' => 'The :attribute has already been taken.',
    'uploaded' => 'The :attribute failed to upload.',
    'uppercase' => 'Value must be uppercase.',
    'url' => 'Value must be a valid URL.',
    'ulid' => 'Value must be a valid ULID.',
    'uuid' => 'Value must be a valid UUID.',
];
