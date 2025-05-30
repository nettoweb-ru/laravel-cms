<?php
return [
    'accepted' => 'Alan kabul edilmelidir.',
    'accepted_if' => ':other değeri :value olduğunda alan kabul edilmelidir.',
    'active_url' => 'Değer geçerli bir URL olmalıdır.',
    'after' => 'Değer :date tarihinden sonraki bir tarih olmalıdır.',
    'after_or_equal' => "Değer :date'den sonraki veya ona eşit bir tarih olmalıdır.",
    'alpha' => 'Değer yalnızca harf içermelidir.',
    'alpha_dash' => 'Değer yalnızca harf, rakam, tire ve alt çizgilerden oluşmalıdır.',
    'alpha_num' => 'Değer yalnızca harf ve rakamlardan oluşmalıdır.',
    'array' => 'Değer bir dizi olmalıdır.',
    'ascii' => 'Değer yalnızca tek baytlık alfanümerik karakterler ve semboller içermelidir.',
    'before' => "Değer :date'den önceki bir tarih olmalıdır.",
    'before_or_equal' => "Değer :date'den önceki veya ona eşit bir tarih olmalıdır.",
    'between' => [
        'array' => 'Değer :min ile :max arasında olmalıdır.',
        'file' => 'Değer :min ile :max kilobayt arasında olmalıdır.',
        'numeric' => 'Değer :min ile :max arasında olmalıdır.',
        'string' => 'Değer uzunluğu :min ile :max karakter arasında olmalıdır.',
    ],
    'boolean' => 'Değer doğru veya yanlış olmalıdır.',
    'can' => 'Değer yetkisiz bir değer içeriyor.',
    'confirmed' => 'Değer onayı uyuşmuyor.',
    'current_password' => 'Şifre hatalı.',
    'date' => 'Değer geçerli bir tarih olmalıdır.',
    'date_equals' => 'Değer :date değerine eşit bir tarih olmalıdır.',
    'date_format' => 'Değer :format biçimine uygun olmalıdır.',
    'decimal' => 'Değerin ondalık basamak sayısı :decimal olmalıdır.',
    'declined' => 'Değer reddedilmelidir.',
    'declined_if' => ':other ifadesi :value olduğunda değer reddedilmelidir.',
    'default_entity_required' => 'Varsayılan model gereklidir.',
    'different' => 'Değer ve :other farklı olmalıdır.',
    'digits' => 'Değer :digits haneli olmalıdır.',
    'digits_between' => 'Değer uzunluğu :min ile :max arasında olmalıdır.',
    'dimensions' => 'Değer geçersiz resim boyutlarına sahip.',
    'distinct' => 'Değerin aynı değeri vardır.',
    'doesnt_end_with' => 'Değer aşağıdakilerden biriyle bitmemelidir: :values.',
    'doesnt_start_with' => 'Değer aşağıdakilerden biriyle başlamamalıdır: :values.',
    'email' => 'Değer geçerli bir e-posta adresi olmalıdır.',
    'ends_with' => 'Değer aşağıdakilerden biriyle bitmelidir: :values.',
    'enum' => 'Seçilen :attribute geçersiz.',
    'exists' => 'Seçilen :attribute geçersiz.',
    'file' => 'Değer bir dosya olmalıdır.',
    'filled' => 'Değerin bir değeri olmalı.',
    'gt' => [
        'array' => 'Değer, :value öğesinden fazla olmalıdır.',
        'file' => 'Değer :value kilobayttan büyük olmalıdır.',
        'numeric' => 'Değer :value değerinden büyük olmalıdır.',
        'string' => 'Değer :value karakterlerinden büyük olmalıdır.',
    ],
    'gte' => [
        'array' => 'Değer :value veya daha fazla öğeye sahip olmalıdır.',
        'file' => 'Değer :value kilobayttan büyük veya eşit olmalıdır.',
        'numeric' => "Değer :value'dan büyük veya eşit olmalıdır.",
        'string' => 'Değer :value karakterlerinden büyük veya eşit olmalıdır.',
    ],
    'image' => 'Değer bir imaj olmalıdır.',
    'in' => 'Seçilen :attribute geçersiz.',
    'in_array' => 'Değer :other içinde bulunmalıdır.',
    'integer' => 'Değer tam sayı olmalıdır.',
    'ip' => 'Değer geçerli bir IP adresi olmalıdır.',
    'ipv4' => 'Değer geçerli bir IPv4 adresi olmalıdır.',
    'ipv6' => 'Değer geçerli bir IPv6 adresi olmalıdır.',
    'json' => 'Değer geçerli bir JSON dizesi olmalıdır.',
    'lowercase' => 'Değer küçük harfle yazılmalıdır.',
    'lt' => [
        'array' => 'Değer :value değerinden az olmalıdır.',
        'file' => 'Değer :value kilobayttan az olmalıdır.',
        'numeric' => 'Değer :value değerinden küçük olmalıdır.',
        'string' => 'Değer :value karakterinden küçük olmalıdır.',
    ],
    'lte' => [
        'array' => 'Değer en fazla :value değerinde öğe içerebilir.',
        'file' => 'Değer :value kilobayttan küçük veya eşit olmalıdır.',
        'numeric' => "Değer :value'dan küçük veya eşit olmalıdır.",
        'string' => 'Değer :value karakterlerinden küçük veya eşit olmalıdır.',
    ],
    'mac_address' => 'Değer geçerli bir MAC adresi olmalıdır.',
    'max' => [
        'array' => 'Değer en fazla :max öğeye sahip olmalıdır.',
        'file' => 'Değer :max kilobayttan büyük olmamalıdır.',
        'numeric' => "Değer :max'tan büyük olmamalıdır.",
        'string' => 'Değer :max karakterden büyük olmamalıdır.',
    ],
    'max_digits' => 'Değer en fazla :max rakamdan oluşmalıdır.',
    'mimes' => 'Değer şu türde bir dosya olmalıdır: :values.',
    'mimetypes' => 'Değer şu türde bir dosya olmalıdır: :values.',
    'min' => [
        'array' => 'Değer en az :min ögeye sahip olmalıdır.',
        'file' => 'Değer en az :min kilobayt olmalıdır.',
        'numeric' => 'Değer en az :min olmalıdır.',
        'string' => 'Değer en az :min karakter olmalıdır.',
    ],
    'min_digits' => 'Değer en az :min rakamdan oluşmalıdır.',
    'missing' => 'Değer eksik olmalı.',
    'missing_if' => ':other ifadesi :value olduğunda değer eksik olmalıdır.',
    'missing_unless' => ':other ifadesi :value olmadığı sürece değer eksik olmalıdır.',
    'missing_with' => ':values mevcut olduğunda değer eksik olmalıdır.',
    'missing_with_all' => ':values mevcut olduğunda değer eksik olmalıdır.',
    'multiple_of' => "Değer :value'nun bir katı olmalıdır.",
    'not_in' => 'Seçilen :attribute geçersiz.',
    'not_regex' => 'Değer biçimi geçersiz.',
    'numeric' => 'Değer bir sayı olmalıdır.',
    'password' => [
        'letters' => 'Değer en az bir harf içermelidir.',
        'mixed' => 'Değer en az bir büyük ve bir küçük harf içermelidir.',
        'numbers' => 'Değer en az bir rakam içermelidir.',
        'symbols' => 'Değer en az bir sembol içermelidir.',
        'uncompromised' => 'Verilen :attribute bir veri sızıntısında ortaya çıktı. Lütfen farklı bir :attribute seçin.',
    ],
    'present' => 'Değer mevcut olmalıdır.',
    'prohibited' => 'Değer yasaktır.',
    'prohibited_if' => ':other ifadesi :value olduğunda değer yasaktır.',
    'prohibited_unless' => ':other, :values içinde olmadığı sürece değer yasaktır.',
    'prohibits' => 'Değer :other var olmasını yasaklar.',
    'regex' => 'Değer biçimi geçersiz.',
    'required' => 'Değer gereklidir.',
    'required_array_keys' => 'Değer, aşağıdaki girdileri içermelidir: :values.',
    'required_if' => ':other ifadesi :value olduğunda değer gereklidir.',
    'required_if_accepted' => ':other kabul edildiğinde değer gereklidir.',
    'required_unless' => ':other, :values içinde olmadığı sürece değer gereklidir.',
    'required_with' => ':values mevcut olduğunda değer gereklidir.',
    'required_with_all' => ':values mevcut olduğunda değer gereklidir.',
    'required_without' => ':values mevcut olmadığında değer gereklidir.',
    'required_without_all' => ":values'lardan hiçbiri mevcut olmadığında değer gereklidir.",
    'same' => 'Değer :other ile eşleşmelidir.',
    'size' => [
        'array' => 'Değer :size öğelerini içermelidir.',
        'file' => 'Değer :size kilobayt olmalıdır.',
        'numeric' => 'Değer :size olmalıdır.',
        'string' => 'Değer :size karakter olmalıdır.',
    ],
    'starts_with' => 'Değer aşağıdakilerden biriyle başlamalıdır: :values.',
    'string' => 'Değer bir dize olmalıdır.',
    'timezone' => 'Değer geçerli bir zaman dilimi olmalıdır.',
    'unique' => ':attribute zaten alınmış.',
    'uploaded' => ':attribute yüklenemedi.',
    'uppercase' => 'Değer büyük harfle yazılmalıdır.',
    'url' => 'Değer geçerli bir URL olmalıdır.',
    'ulid' => 'Değer geçerli bir ULID olmalıdır.',
    'uuid' => 'Değer geçerli bir UUID olmalıdır.',
];
