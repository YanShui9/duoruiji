<?php

return [

    /*
    |--------------------------------------------------------------------------
    | 验证语言行
    |--------------------------------------------------------------------------
    |
    | 以下语言行包含验证器类使用的默认错误信息。
    | 按需修改这些语言行以满足应用需求。
    |
    */

    'accepted'             => ':attribute 必须被接受。',
    'active_url'           => ':attribute 不是有效的网址。',
    'after'                => ':attribute 必须是 :date 之后的日期。',
    'after_or_equal'       => ':attribute 必须是 :date 之后或相同的日期。',
    'alpha'                => ':attribute 只能包含字母。',
    'alpha_dash'           => ':attribute 只能包含字母、数字、短横线和下划线。',
    'alpha_num'            => ':attribute 只能包含字母和数字。',
    'array'                => ':attribute 必须是数组。',
    'before'               => ':attribute 必须是 :date 之前的日期。',
    'before_or_equal'      => ':attribute 必须是 :date 之前或相同的日期。',
    'between'              => [
        'array'   => ':attribute 必须包含 :min 到 :max 个元素。',
        'file'    => ':attribute 必须在 :min 到 :max KB 之间。',
        'numeric' => ':attribute 必须在 :min 到 :max 之间。',
        'string'  => ':attribute 必须在 :min 到 :max 个字符之间。',
    ],
    'boolean'              => ':attribute 必须是 true 或 false。',
    'confirmed'            => ':attribute 与确认输入不一致。',
    'date'                 => ':attribute 不是有效的日期。',
    'date_equals'          => ':attribute 必须是 :date 这一天。',
    'date_format'          => ':attribute 与格式 :format 不匹配。',
    'different'            => ':attribute 和 :other 必须不同。',
    'digits'               => ':attribute 必须是 :digits 位数字。',
    'digits_between'       => ':attribute 必须是 :min 到 :max 位数字。',
    'dimensions'           => ':attribute 图片尺寸不正确。',
    'distinct'             => ':attribute 存在重复值。',
    'email'                => ':attribute 必须是有效的邮箱地址。',
    'ends_with'            => ':attribute 必须以 :values 结尾。',
    'exists'               => ':attribute 不存在。',
    'file'                 => ':attribute 必须是文件。',
    'filled'               => ':attribute 不能为空。',
    'gt'                   => [
        'array'   => ':attribute 必须包含超过 :value 个元素。',
        'file'    => ':attribute 必须大于 :value KB。',
        'numeric' => ':attribute 必须大于 :value。',
        'string'  => ':attribute 必须超过 :value 个字符。',
    ],
    'gte'                  => [
        'array'   => ':attribute 必须包含 :value 个或更多元素。',
        'file'    => ':attribute 必须大于或等于 :value KB。',
        'numeric' => ':attribute 必须大于或等于 :value。',
        'string'  => ':attribute 必须大于或等于 :value 个字符。',
    ],
    'image'                => ':attribute 必须是图片。',
    'in'                   => ':attribute 无效。',
    'in_array'             => ':attribute 在 :other 中不存在。',
    'integer'              => ':attribute 必须是整数。',
    'ip'                   => ':attribute 必须是有效的 IP 地址。',
    'ipv4'                 => ':attribute 必须是有效的 IPv4 地址。',
    'ipv6'                 => ':attribute 必须是有效的 IPv6 地址。',
    'json'                 => ':attribute 必须是有效的 JSON 字符串。',
    'lt'                   => [
        'array'   => ':attribute 必须少于 :value 个元素。',
        'file'    => ':attribute 必须小于 :value KB。',
        'numeric' => ':attribute 必须小于 :value。',
        'string'  => ':attribute 必须少于 :value 个字符。',
    ],
    'lte'                  => [
        'array'   => ':attribute 不能超过 :value 个元素。',
        'file'    => ':attribute 必须小于或等于 :value KB。',
        'numeric' => ':attribute 必须小于或等于 :value。',
        'string'  => ':attribute 必须小于或等于 :value 个字符。',
    ],
    'max'                  => [
        'array'   => ':attribute 不能超过 :max 个元素。',
        'file'    => ':attribute 不能大于 :max KB。',
        'numeric' => ':attribute 不能大于 :max。',
        'string'  => ':attribute 不能超过 :max 个字符。',
    ],
    'mimes'                => ':attribute 必须是 :values 类型的文件。',
    'mimetypes'            => ':attribute 必须是 :values 类型的文件。',
    'min'                  => [
        'array'   => ':attribute 至少包含 :min 个元素。',
        'file'    => ':attribute 不能小于 :min KB。',
        'numeric' => ':attribute 不能小于 :min。',
        'string'  => ':attribute 不能少于 :min 个字符。',
    ],
    'not_in'               => ':attribute 无效。',
    'not_regex'            => ':attribute 格式不正确。',
    'numeric'              => ':attribute 必须是数字。',
    'password'             => '密码错误。',
    'present'              => ':attribute 必须存在。',
    'regex'                => ':attribute 格式不正确。',
    'required'             => ':attribute 不能为空。',
    'required_if'          => ':other 为 :value 时，:attribute 不能为空。',
    'required_unless'      => ':other 不在 :values 中时，:attribute 不能为空。',
    'required_with'        => ':values 存在时，:attribute 不能为空。',
    'required_with_all'    => ':values 都存在时，:attribute 不能为空。',
    'required_without'     => ':values 不存在时，:attribute 不能为空。',
    'required_without_all' => ':values 都不存在时，:attribute 不能为空。',
    'same'                 => ':attribute 和 :other 必须一致。',
    'size'                 => [
        'array'   => ':attribute 必须包含 :size 个元素。',
        'file'    => ':attribute 必须为 :size KB。',
        'numeric' => ':attribute 必须为 :size。',
        'string'  => ':attribute 必须为 :size 个字符。',
    ],
    'starts_with'          => ':attribute 必须以 :values 开头。',
    'string'               => ':attribute 必须是字符串。',
    'timezone'             => ':attribute 必须是有效的时区。',
    'unique'               => ':attribute 已经被占用。',
    'uploaded'             => ':attribute 上传失败。',
    'url'                  => ':attribute 格式不正确。',
    'uuid'                 => ':attribute 必须是有效的 UUID。',

    /*
    |--------------------------------------------------------------------------
    | 自定义验证语言行
    |--------------------------------------------------------------------------
    |
    | 在此可以指定属性名称的自定义验证消息。
    | 例如：
    |   'custom' => [
    |       'email' => [
    |           'required' => '邮箱地址不能为空。',
    |       ],
    |   ],
    |
    */

    'custom' => [],

    /*
    |--------------------------------------------------------------------------
    | 自定义属性名称
    |--------------------------------------------------------------------------
    |
    | 在此可以指定属性的自定义名称，以便在验证消息中使用。
    | 例如：
    |   'attributes' => [
    |       'email' => '邮箱',
    |       'password' => '密码',
    |   ],
    |
    */

    'attributes' => [
        'name'                  => '姓名',
        'email'                 => '邮箱',
        'password'              => '密码',
        'password_confirmation' => '确认密码',
        'title'                 => '标题',
        'description'           => '描述',
        'category'              => '分类',
        'cover_image'           => '封面图片',
        'video_file'            => '视频文件',
        'video_url'             => '视频链接',
        'live_url'              => '直播链接',
        'live_start_time'       => '开始时间',
        'live_end_time'         => '结束时间',
        'status'                => '状态',
        'sort_order'            => '排序',
        'hospital'              => '医院',
        'department'            => '科室',
        'bio'                   => '简介',
        'avatar'                => '头像',
        'duration'              => '时长',
        'lecture_id'            => '讲座',
        'expert_ids'            => '专家',
        'is_admin'              => '管理员权限',
    ],
];
