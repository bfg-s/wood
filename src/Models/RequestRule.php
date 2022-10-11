<?php

namespace Bfg\Wood\Models;

use Bfg\Wood\ModelTopic;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Bfg\Wood\Models\RequestRule
 *
 * @property int $id
 * @property string $name
 * @property string|null $rule
 * @property mixed|null|null $class
 * @property int $order
 * @property int $request_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|RequestRule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RequestRule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RequestRule query()
 * @method static \Illuminate\Database\Eloquent\Builder|RequestRule whereClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RequestRule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RequestRule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RequestRule whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RequestRule whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RequestRule whereRequestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RequestRule whereRule($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RequestRule whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RequestRule extends ModelTopic
{
    /**
     * @var string
     */
    public string $modelIcon = 'fas fa-pencil-ruler';

    /**
     * @var string|null
     */
    public ?string $modelName = 'Request rule';

    /**
     * @var string|null
     */
    public ?string $modelDescription = 'The request rules';

    /**
     * @var string|null
     */
    public ?string $parent = Request::class;

    /**
     * @var array
     */
    public static array $schema = [
        'name' => [
            'string',
            'regexp' => '^\w*$',
            'possibleTable' => 'model_fields:name',
            'info' => 'Request field name',
        ],
        'rules' => [
            'array',
            'variants' => [
                'Accepted' => ['accepted'],
                'Accepted_if' => ['accepted_if:anotherfield,value,...'],
                'Active url' => ['active_url'],
                'After' => ['after:date'],
                'After or equal' => ['after_or_equal:date'],
                'Alpha' => ['alpha'],
                'Alpha dash' => ['alpha_dash'],
                'Alpha num' => ['alpha_num'],
                'Array' => ['array'],
                'Bail' => ['bail'],
                'Before' => ['before:date'],
                'Before or equal' => ['before_or_equal:date'],
                'Between' => ['between:min,max'],
                'Boolean' => ['boolean'],
                'Confirmed' => ['confirmed'],
                'Current password' => ['current_password'],
                'Date' => ['date'],
                'Date equals' => ['date_equals:date'],
                'Date format' => ['date_format:format'],
                'Declined' => ['declined'],
                'Declined if' => ['declined_if:anotherfield,value,...'],
                'Different' => ['different:field'],
                'Digits' => ['digits:value'],
                'Digits between' => ['digits_between:min,max'],
                'Dimensions' => ['dimensions'],
                'Distinct' => ['distinct'],
                'Doesnt start with' => ['doesnt_start_with:foo,bar,...'],
                'Doesnt end with' => ['doesnt_end_with:foo,bar,...'],
                'Email' => ['email'],
                'Ends with' => ['ends_with:foo,bar,...'],
                'Enum' => ['enum'],
                'Exclude' => ['exclude'],
                'Exclude if' => ['exclude_if:anotherfield,value'],
                'Exclude unless' => ['exclude_unless:anotherfield,value'],
                'Exclude with' => ['exclude_with:anotherfield'],
                'Exclude without' => ['exclude_without:anotherfield'],
                'Exists' => ['exists:table,column'],
                'File' => ['file'],
                'Filled' => ['filled'],
                'Gt' => ['gt:field'],
                'Gte' => ['gte:field'],
                'Image' => ['image'],
                'In' => ['in:foo,bar,...'],
                'In array' => ['in_array:anotherfield.*'],
                'Integer' => ['integer'],
                'Ip' => ['ip'],
                'Ipv4' => ['ipv4'],
                'Ipv6' => ['ipv6'],
                'Json' => ['json'],
                'Lt' => ['lt:field'],
                'Lte' => ['lte:field'],
                'Mac address' => ['mac_address'],
                'Max' => ['max:value'],
                'Max digits' => ['max_digits:value'],
                'Mimetypes' => ['mimetypes:text/plain,...'],
                'Mimes' => ['mimes:foo,bar,...'],
                'Min' => ['min:value'],
                'Min digits' => ['min_digits:value'],
                'Multiple of' => ['multiple_of:value'],
                'Not in' => ['not_in:foo,bar,...'],
                'Not regex' => ['not_regex:pattern'],
                'Nullable' => ['nullable'],
                'Numeric' => ['numeric'],
                'Password' => ['password'],
                'Present' => ['present'],
                'Prohibited' => ['prohibited'],
                'Prohibited if' => ['prohibited_if:anotherfield,value,...'],
                'Prohibited unless' => ['prohibited_unless:anotherfield,value,...'],
                'Prohibits' => ['prohibits:anotherfield,...'],
                'Regex' => ['regex:pattern'],
                'Required' => ['required'],
                'Required if' => ['required_if:anotherfield,value,...'],
                'Required unless' => ['required_unless:anotherfield,value,...'],
                'Required with' => ['required_with:foo,bar,...'],
                'Required with_all' => ['required_with_all:foo,bar,...'],
                'Required without' => ['required_without:foo,bar,...'],
                'Required without_all' => ['required_without_all:foo,bar,...'],
                'Required array_keys' => ['required_array_keys:foo,bar,...'],
                'Same' => ['same:field'],
                'Size' => ['size:value'],
                'Starts with' => ['starts_with:foo,bar,...'],
                'String' => ['string'],
                'Timezone' => ['timezone'],
                'Unique' => ['unique:table,column'],
                'Url' => ['url'],
                'Uuid' => ['uuid'],
            ],
            'info' => 'Request rules (Can be class)',
        ],
    ];
}
