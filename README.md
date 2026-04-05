# duk-php

[Duk](https://github.com/johyunduk/duk) 디버거 앱을 위한 Laravel PHP 클라이언트입니다.

## 요구 사항

- PHP 8.1+
- Laravel 10 / 11 / 12 / 13

## 설치

`composer.json`에 아래 내용을 추가하세요.

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/johyunduk/duk-php"
        }
    ],
    "require": {
        "debugger/duk": "^1.0"
    }
}
```

그 다음 설치:

```bash
composer install
```

## 설정

`.env` 파일에 추가:

```env
DEBUGGER_ENABLED=true
DEBUGGER_HOST=localhost
DEBUGGER_PORT=23517
```

설정 파일을 퍼블리시하려면:

```bash
php artisan vendor:publish --tag=duk-config
```

## 사용법

```php
// 변수 출력
duk($variable);

// 여러 값 동시에
duk($a, $b, $c);

// 색상
duk($data)->red();
duk($data)->green();
duk($data)->blue();
duk($data)->orange();
duk($data)->purple();
duk($data)->gray();

// 레이블
duk($data)->label('유저 정보');

// 색상 + 레이블 조합
duk($data)->green()->label('성공');

// 예외
duk()->exception($e);

// SQL 쿼리 추적
duk()->showQueries();

// 값을 그대로 반환 (파이프라인에서 유용)
$result = duk($value)->pass($value);
```

## 앱 다운로드

[Duk 앱 다운로드](https://github.com/johyunduk/duk/releases)
