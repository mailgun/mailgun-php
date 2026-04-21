# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

```bash
# Install dependencies
composer install

# Run tests
composer test
# or directly:
vendor/bin/phpunit

# Run a single test file
vendor/bin/phpunit tests/Api/DomainTest.php

# Run a single test method
vendor/bin/phpunit --filter testMethodName tests/Api/DomainTest.php

# Static analysis (Psalm, level 3)
vendor/bin/psalm

# PHPStan (level 5)
vendor/bin/phpstan analyse

# Code style check
vendor/bin/phpcs

# Normalize composer.json
composer normalize
```

## Architecture

The SDK is a PSR-18-based HTTP client wrapper around the Mailgun API. It is intentionally not coupled to any specific HTTP client (Guzzle, Symfony, etc.) — consumers bring their own PSR-7 and PSR-18 implementations.

### Entry point

`Mailgun\Mailgun` is the main class. It is instantiated either via `Mailgun::create($apiKey)` (convenience factory) or by passing a configured `HttpClientConfigurator` directly to the constructor. Each API namespace (domains, messages, events, etc.) is exposed as a method that returns a new instance of the corresponding `Api\*` class.

### HTTP layer

`HttpClientConfigurator` (`src/HttpClient/HttpClientConfigurator.php`) configures a `php-http/client-common` `PluginClient` that wraps the discovered PSR-18 client. Plugins add:
- Base URI (`AddHostPlugin`)
- `Authorization: Basic` header with the API key
- `X-Mailgun-On-Behalf-Of` header for sub-account requests
- Response history tracking (`HistoryPlugin`)

`RequestBuilder` builds PSR-7 requests. For form-encoded or multipart POST bodies it assembles the body as an array of `[name, content]` pairs and encodes them via `php-http/multipart-stream-builder`.

### API classes

All API classes extend `HttpApi` (`src/Api/HttpApi.php`), which provides:
- `httpGet`, `httpPost`, `httpPostRaw`, `httpPut`, `httpDelete` helpers
- `hydrateResponse($response, $class)` to deserialize responses
- `handleErrors` that maps HTTP status codes to typed exceptions (`HttpClientException`, `HttpServerException`)

Each `Api\*` class calls these helpers and passes a model class string to `hydrateResponse`.

### Hydration

Three hydrators are available:
- `ModelHydrator` (default) — deserializes JSON into model objects by calling `ClassName::create(array $data)` (for classes implementing `ApiResponse`) or `new ClassName($data)`.
- `ArrayHydrator` — returns raw associative arrays.
- `NoopHydrator` — returns the raw `ResponseInterface` with no error handling.

### Models

Models live in `src/Model/<Api>/`. They implement `ApiResponse` (which requires a static `create(array $data)` factory method) and expose typed getters. They are plain value objects.

### Tests

Tests live in `tests/`. API tests extend `Mailgun\Tests\Api\TestCase` (not `MailgunTestCase`), which provides `getApiMock()` and `getApiInstance()` helpers. `getApiInstance()` sets up a real API class with a mocked PSR-18 client and lets you control the HTTP response via `setHttpResponse()` and expected request via `setRequestMethod()`, `setRequestUri()`, `setRequestBody()`.

Model tests extend `Mailgun\Tests\Model\BaseModel`, which reads fixture JSON from `tests/TestAssets/` and asserts on model properties.

### Adding a new API endpoint

1. Create `src/Api/MyFeature.php` extending `HttpApi`.
2. Create model(s) in `src/Model/MyFeature/` implementing `ApiResponse`.
3. Expose the API class from `Mailgun::myFeature()` in `src/Mailgun.php`.
4. Add tests in `tests/Api/MyFeatureTest.php` and `tests/Model/MyFeature/`.
