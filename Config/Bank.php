<?php
return [
    // 银行start
    // 信用卡start
    '1000' => [
        'name' => '未知',
        'code_hlb' => '',
        'code_yb' => '',
        'ybskb' => -1,
        'status' => 1,
        'bank_type' => 0 // 0信用卡或者储蓄卡 1信用卡，2储蓄卡
    ],
    '1001' => [
        'name' => '中国银行',
        'code_hlb' => 'BOC',
        'code_yb' => 'BOC',
        'ybskb' => 1, // 是否支持易宝收款宝 1支持，-1不支持
        'status' => 1, // -1禁用，1正常
        'bank_type' => 1 // 0信用卡或者储蓄卡 1信用卡，2储蓄卡
    ],
    '1002' => [
        'name' => '民生银行',
        'code_hlb' => 'CMBC',
        'code_yb' => 'CMBC',
        'ybskb' => 1,
        'status' => 1,
        'bank_type' => 1
    ],
    '1003' => [
        'name' => '平安银行',
        'code_hlb' => 'PING',
        'code_yb' => 'SDB',
        'ybskb' => 1,
        'status' => 1,
        'bank_type' => 1
    ],
    '1004' => [
        'name' => '浦东发展银行',
        'code_hlb' => 'SPDB',
        'code_yb' => 'SPDB',
        'ybskb' => 1,
        'status' => 1,
        'bank_type' => 1
    ],
    '1005' => [
        'name' => '建设银行',
        'code_hlb' => 'CCB',
        'code_yb' => 'CCB',
        'ybskb' => 1,
        'status' => 1,
        'bank_type' => 1
    ],
    '1006' => [
        'name' => '工商银行',
        'code_hlb' => 'ICBC',
        'code_yb' => 'ICBC',
        'ybskb' => 1,
        'status' => 1,
        'bank_type' => 1
    ],
    '1007' => [
        'name' => '邮政储蓄',
        'code_hlb' => 'POST',
        'code_yb' => 'POST',
        'ybskb' => 1,
        'status' => 1,
        'bank_type' => 1
    ],
    '1008' => [
        'name' => '交通银行',
        'code_hlb' => 'BOCO',
        'code_yb' => 'BOCO',
        'ybskb' => -1,
        'status' => 1,
        'bank_type' => 1
    ],
    '1009' => [
        'name' => '光大银行',
        'code_hlb' => 'CEB',
        'code_yb' => 'CEB',
        'ybskb' => 1,
        'status' => 1,
        'bank_type' => 1
    ],
    '1010' => [
        'name' => '兴业银行',
        'code_hlb' => 'CIB',
        'code_yb' => 'CIB',
        'ybskb' => 1,
        'status' => 1,
        'bank_type' => 1
    ],
    '1011' => [
        'name' => '广发银行',
        'code_hlb' => 'CGB',
        'code_yb' => 'CGB',
        'ybskb' => 1,
        'status' => 1,
        'bank_type' => 1
    ],
    '1012' => [
        'name' => '华夏银行',
        'code_hlb' => 'HXB',
        'code_yb' => 'HXB',
        'ybskb' => 1,
        'status' => 1,
        'bank_type' => 1
    ],
    '1013' => [
        'name' => '花旗银行',
        'code_hlb' => 'CITI',
        'code_yb' => '',
        'ybskb' => 1,
        'status' => 1,
        'bank_type' => 1
    ],
    '1014' => [
        'name' => '招商银行',
        'code_hlb' => 'CMBCHINA',
        'code_yb' => 'CMBCHINA',
        'ybskb' => -1,
        'status' => 1,
        'bank_type' => 1
    ],
    '1015' => [
        'name' => '农业银行',
        'code_hlb' => 'ABC',
        'code_yb' => 'ABC',
        'ybskb' => 1,
        'status' => 1,
        'bank_type' => 1
    ],
    '1016' => [
        'name' => '中信银行',
        'code_hlb' => 'ECITIC',
        'code_yb' => 'ECITIC',
        'ybskb' => 1,
        'status' => 1,
        'bank_type' => 1
    ],
    '1017' => [
        'name' => '北京银行',
        'code_hlb' => '',
        'code_yb' => 'BCCB',
        'ybskb' => 1,
        'status' => 1,
        'bank_type' => 1
    ],
    '1018' => [
        'name' => '上海银行',
        'code_hlb' => '',
        'code_yb' => 'SHB',
        'ybskb' => 1,
        'status' => 1,
        'bank_type' => 1
    ],
    '1019' => [
        'name' => '广州银行',
        'code_hlb' => '',
        'code_yb' => 'GZCB',
        'ybskb' => -1,
        'status' => -1,
        'bank_type' => 1
    ],

    // 信用卡end

    // 储蓄卡start
    '2001' => [
        'name' => '中国银行',
        'code_hlb' => 'BOC',
        'code_yb' => 'BOC',
        'ybskb' => 1,
        'status' => 1,
        'bank_type' => 2
    ],
    '2002' => [
        'name' => '招商银行',
        'code_hlb' => 'CMBCHINA',
        'code_yb' => 'CMBCHINA',
        'ybskb' => 1,
        'status' => 1,
        'bank_type' => 2
    ],
    '2003' => [
        'name' => '平安银行',
        'code_hlb' => 'PING',
        'code_yb' => 'SDB',
        'ybskb' => 1,
        'status' => 1,
        'bank_type' => 2
    ],
    '2004' => [
        'name' => '农业银行',
        'code_hlb' => 'ABC',
        'code_yb' => 'ABC',
        'ybskb' => 1,
        'status' => 1,
        'bank_type' => 2
    ],
    '2005' => [
        'name' => '建设银行',
        'code_hlb' => 'CCB',
        'code_yb' => 'CCB',
        'ybskb' => 1,
        'status' => 1,
        'bank_type' => 2
    ],
    '2006' => [
        'name' => '工商银行',
        'code_hlb' => 'ICBC',
        'code_yb' => 'ICBC',
        'ybskb' => 1,
        'status' => 1,
        'bank_type' => 2
    ],
    '2007' => [
        'name' => '邮政储蓄',
        'code_hlb' => 'POST',
        'code_yb' => 'POST',
        'ybskb' => 1,
        'status' => 1,
        'bank_type' => 2
    ],
    '2008' => [
        'name' => '交通银行',
        'code_hlb' => 'BOCO',
        'code_yb' => 'BOCO',
        'ybskb' => 1,
        'status' => 1,
        'bank_type' => 2
    ],
    '2009' => [
        'name' => '光大银行',
        'code_hlb' => 'CEB',
        'code_yb' => 'CEB',
        'ybskb' => 1,
        'status' => 1,
        'bank_type' => 2
    ],
    '2010' => [
        'name' => '兴业银行',
        'code_hlb' => 'CIB',
        'code_yb' => 'CIB',
        'ybskb' => 1,
        'status' => 1,
        'bank_type' => 2
    ],
    '2011' => [
        'name' => '华夏银行',
        'code_hlb' => 'HXB',
        'code_yb' => 'HXB',
        'ybskb' => 1,
        'status' => 1,
        'bank_type' => 2
    ],
    '2012' => [
        'name' => '浦东发展银行',
        'code_hlb' => 'SPDB',
        'code_yb' => 'SPDB',
        'ybskb' => 1,
        'status' => 1,
        'bank_type' => 2
    ],
    '2013' => [
        'name' => '中信银行',
        'code_hlb' => 'ECITIC',
        'code_yb' => 'ECITIC',
        'ybskb' => 1,
        'status' => 1,
        'bank_type' => 2
    ],
    '2014' => [
        'name' => '广发银行',
        'code_hlb' => 'CGB',
        'code_yb' => 'CGB',
        'ybskb' => 1,
        'status' => 1,
        'bank_type' => 2
    ],
    '2015' => [
        'name' => '民生银行',
        'code_hlb' => 'CMBC',
        'code_yb' => 'CMBC',
        'ybskb' => 1,
        'status' => 1,
        'bank_type' => 2
    ],
    '2016' => [
        'name' => '上海银行',
        'code_hlb' => '',
        'code_yb' => 'SHB',
        'ybskb' => 1,
        'status' => 1,
        'bank_type' => 2
    ],
    '2017' => [
        'name' => '北京银行',
        'code_hlb' => '',
        'code_yb' => 'BCCB',
        'ybskb' => 1,
        'status' => 1,
        'bank_type' => 2
    ],
    '2018' => [
        'name' => '广州银行',
        'code_hlb' => '',
        'code_yb' => 'GZCB',
        'ybskb' => 1,
        'status' => 1,
        'bank_type' => 2
    ],
    '2019' => [
        'name' => '微信支付',
//        'code_hlb' => '',
//        'code_yb' => 'GZCB',
//        'ybskb' => 1,
//        'status' => 1,
//        'bank_type' => 2
    ],
    '2020' => [
        'name' => '支付宝支付',
//        'code_hlb' => '',
//        'code_yb' => 'GZCB',
//        'ybskb' => 1,
//        'status' => 1,
//        'bank_type' => 2
    ]
    // 储蓄卡end

    // 银行end
];