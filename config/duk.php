<?php

return [
    /*
     * 디버거 앱이 실행 중인 호스트
     */
    'host' => env('DEBUGGER_HOST', 'localhost'),

    /*
     * 디버거 앱이 수신하는 포트
     */
    'port' => env('DEBUGGER_PORT', 23517),

    /*
     * false로 설정하면 모든 duk() 호출이 무시됩니다.
     * 프로덕션 환경에서는 false로 설정하세요.
     */
    'enabled' => env('DEBUGGER_ENABLED', true),
];
