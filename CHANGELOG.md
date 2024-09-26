# Change Log

The change log describes what is "Added", "Removed", "Changed" or "Fixed" between each release.

## 4.3.2
 - Added new API endpoint for getting metrics @see https://documentation.mailgun.com/docs/mailgun/api-reference/openapi-final/tag/Metrics/

## 4.3.1
 - Add method for retrieving stored messages by @oleksandr-mykhailenko in #920
 - Add missed params to the create method for DomainV4.php by @oleksandr-mykhailenko in #921

## 4.3.0
- End of support php 7.3
- Updated properties and added types to the classes properties
- Update code style
- Add missed field into IndexResponse for Webhooks
- Fixed template search filters
- Fixed tags API endpoints
- Added new API endpoints

## 4.2.0
- Added basic templates functionality

## 4.0.1
 - Fix wrong classes in tests
 - Fixed response in case of 404 http error. Respect server error message

## 4.0
 - SubAccount support @oleksandr-mykhailenko in #886
 - Requests of behalf of Sub Account

## 3.6.2
 - Bugfix: TypeError caused by improper use of new self() instead of new static() in base class method

## 3.6.1
 - update library
 - Improvement: SDK version headers v2 vs v3
 - Update packages by @oleksandr-mykhailenko

## 3.5.9
- Fixed: bug when params `to` and `reply-to` have the same address

## 3.5.6
 - Changed: support bool value for method `createMultiple`

## 3.5.5

### Fixed
- Cast integer values to string for sending multipart data

## 3.5.4

### Added
- Added ability to make own API request to needed endpoint

## 3.5.3

### Added

- Ability to update web_scheme for domain
- `http` or `https` - set your open, click and unsubscribe URLs to use http or https. The default is http

## 3.5.1

### Fixed

- Error with empty array for param recipient-variables. Fix was suggested by @deviarte
- Use null coalescing operator in IndexResponse.php when. Fix proposed by @TWithers

## 3.5.0

### Added

- Support for new webhook endpoints (#609 by @svenbw & #749 by @Nyholm)

## 3.4.1

### Fixed

- Fix double urlencoding (#747 by @uavn)

## 3.4.0

### Added

- Email Validation (#726 by @uavn)
  - Please note the Email Validation requires **always** to use the US servers. The Mailgun Team didn't enable this service on the European endpoints.

## 3.3.0

### Added

- Support for PHP 8 (#727 by @DavidGarciaCat)
- Added `opened`, `clicked`, `unsubscribed` and `stored` to the `TotalResponseItem` (#739 by @Arkitecht)

### Removed

- Support for PHP 7.1 and 7.2 as they both have reached their end of life

## 3.2.0

### Added

- Domain tracking implementation (#711 by @uavn)
- Mailing list validation (#712 by @uavn)
- Suppression Whitelists (#713 by @uavn)

### Fixed

- Added pagination to tags (#717 by @uavn)

### Changed

- Expect Client to be of type ClientInterface or PluginClient (#660 by @tonythomas01)

## 3.1.0

### Added

- Suppressions allow now deleting an address passing an optional tag (#643 by @iwahara)
- Allow both `^1.0` and `^2.0` for `php-http/guzzle6-adapter` (#680 by @boboldehampsink)
- Add support for Mailing List `reply_preference` parameter (#684 by @twoonesixdigital)
- Add support for `Force DKIM Authority` when creating a Domain (#686 by @Tiboonn)
- Add support for PHP 7.4 (#698 by @snapshotpl)
- Allow assigning a domain to a list of ips during creation (#703 by @josephshanak)
- Add unmapped `log-level` property for Events (#704 by @uavn)

### Fixed

- Provide the Member's name just when it's not `null` (#639 by @indapublic)
- Fix typehint for Message `ShowResponse::getContentIdMap()` (#664 by @lvdhoorn)
- Fix endpoint for Domain's API (#668 by @tomschlick)
- Webhook support for array handling (#675 by @martin-sanjuan)
- Fix parameter name when assigning an IP to the specified Domain (#702 by @josephshanak)
- `Ip::index()` now returns all IPs instead of the shared IPs (#707 by @josephshanak)

### Changed

- Updated examples for Debugging and Hydrator usage (#634 by @tonybolzan and #681 by @Jiia)
- Updated link to the Mailgun Documentation page (#688 by @Casmo)
- Remove deprecated Laravel package due to it is archived (#695 by @tomschlick)

### Removed

- Remove method for non-existing Stats URL (#705 by @uavn)

## 3.0.0

### Added

- Support for PSR-4
- All classes `Mailgun\Model` are final or abstract.

### Changed

- Dropped PHP5 support
- Removed deprecated code
- Moved `RequestBuilder` and `HttpClientConfigurator` to `Mailgun\HttpClient` namespace
- Updated signature of `Mailgun::__construct()`

### Removed

- Dependency on `php-http/message`.

## 2.8.1

### Fixed

- Added missing method to use all Mailing List and Ip features.

## 2.8.0

### Added

- Add support for IPs endpoints
- Add spport for Mailing Lists
- Add `complaints` to Stats / Total Response
- Add more tests for our models

### Changed

- Change the PHP Exception message for Bad Request errors to help to find the issue

### Fixed

- Fix an issue validating the max path length

## 2.7.0

### Added

- Allow to set the Mailgun server when instantiating the Mailgun's client: `$mailgun = Mailgun::create('key', 'server');`
- Add new PHPUnit tests for our models
- Add new PHPUnit tests for our API
- Added `Mailgun\Api\Attachment`
- Fluent interface for `MessageBuilder` and `BatchMessage`
- Support for HTTPlug 2.0

### Changed

- Second argument to `Mailgun\Message\MessageBuilder::addBccRecipient()` is now optional.
- We try to close open resources

### Fixed

- Fixed the type error when creating tags.

## 2.6.0

### Added

- Ported MessageBuilder and BatchMessage #472

### Changed

- Cast campaign IDs to string #460
- Suggest packages used on Dev #440

## 2.5.0

### Added

- Support for 413 HTTP status codes, when we send too large payloads to the API

## 2.4.1

### Added

- Add new `Suppressions::getTotalCount()` method

### Changed

- Apply fixes from StyleCI
- Updated `README.md` file

### Fixed

- Fix `Tags` on `Unsubscribe`
- Fix typo on `Mailgun\Exception\HttpServerException`

## 2.4.0

### Added

- Add cached property for DNS record
- Add domain verification
- `HttpClientException::getResponseCode()`
- Added `AbstractDomainResponse` that `VerifyResponse` and `CreateResponse` extends.

### Fixed

- Possible empty content of `WebhookIndexResponse`.
- Typo in `TotalResponse` that caused the content to be empty.

### Changed

- Allow some parameters to `Domain::create` to be optional.

## 2.3.4

### Fixed

- Typo in DnsRecord::isValid. This make sure the correct result of the function is returned.

## 2.3.3

### Changed

- Using stable version of `php-http/multipart-stream-builder`
- Improved tests

## 2.3.2

### Fixed

- When parsing an address in `MessageBuilder` we surround the recipient name with double quotes instead of single quotes.

## 2.3.1

### Fixed

- Make sure to reset the `MultipartStreamBuilder` after a stream is built.

## 2.3.0

### Added

- Support for sending messages with Mime. `$mailgun->messages()->sendMime()`

## 2.2.0

This version contains a new way of using the API. Each endpoint return a domain object and the
endpoints are grouped like the API documentation.

### Added

- Api classes in Mailgun\Api\*
- Api models/responses in Mailgun\Model\*
- Added Hydrators to hydrate PSR-7 responses to arrays or domain objects.
- All exceptions extend `Mailgun\Exception`.
- New exceptions in `Mailgun\Exception` namespace.
- Added `HttpClientConfigurator` to configure the HTTP client.
- Added HttpClient plugins `History` and `ReplaceUriPlugin`
- Assertions with Webmozart\Assert
- `Mailgun\Mailgun::getLastResponse()`
- `Mailgun\Connection\RestClient::getAttachment($url)`
- Clear license information

### Fixed

- Fix disordered POST parameters. We do not use array syntax.
- Code styles

### Deprecated

The following classes will be removed in version 3.0.

- `Mailgun\Connection\Exceptions\GenericHTTPError`
- `Mailgun\Connection\Exceptions\InvalidCredentials`
- `Mailgun\Connection\Exceptions\MissingEndpoint`
- `Mailgun\Connection\Exceptions\MissingRequiredParameters`
- `Mailgun\Connection\Exceptions\NoDomainsConfigured`
- `Mailgun\Connection\RestClient`
- `Mailgun\Constants\Api`
- `Mailgun\Constants\ExceptionMessages`
- `Mailgun\Mailgun::$resetClient`
- `Mailgun\Mailgun::sendMessage()`
- `Mailgun\Mailgun::verifyWebhookSignature()`
- `Mailgun\Mailgun::post()`
- `Mailgun\Mailgun::get()`
- `Mailgun\Mailgun::delete()`
- `Mailgun\Mailgun::put()`
- `Mailgun\Mailgun::setApiVersion()`
- `Mailgun\Mailgun::setSslEnabled()`
- `Mailgun\Mailgun::MessageBuilder()`
- `Mailgun\Mailgun::OptInHandler()`
- `Mailgun\Mailgun::BatchMessage()`

## 2.1.2

- Bug fixes with multiple recipients, inline images and attachments.
- Added more tests
- Using PSR-2 code style

## 2.1.1

- Require php-http/message (#142)
- Declare BatchMessage::endpointUrl (#112)

## 2.1.0

- Strict comparison of hash (#117)
- No dependency on Guzzle/PSR7 (#139)
- Build URL string form an array (#138)
- Docblock update (#134)
- Minor fixes (#90, #121, #98)

## 2.0

- Migrated to PHP-HTTP (#94)
- Dropped support for PHP 5.4.

## 1.8.0

- Updated to Guzzle5 (#79)
- Updated default API version from v2 to v3 (#75)
- Show response message on 400, 401 and 404. (#72)
- PHP DocBlocks, Constants Changes, and Minor Refactors (#66)
- Added PHP 7.0 support for Travis-CI, removed PHP 5.3 support (#79)

## 1.7.2

- Added webhook signature verification - (#50)
- Test PHP 5.6 and HHVM - (#51)
- Improved error handling - (#48)
- Fixed attachment handling in Message Builder - (#56)
- Allow any data type in custom data - (#57)
- Return non-JSON response data - (#60)
- Removed legacy closing braces - (#64)

## 1.7.1

- Improved security of OptInHandler - (#31)
- Fixed typo for including an Exception - (#41)
- Fixed Mocks, removed unnecessary code, applied styling - (#44 & #42)
- Less restrictive Guzzle requirement - (#45)

## 1.7 (2014-1-30)

Bugfixes:
  - patched bug for attachments related to duplicate aggregator bug in Guzzle (#32 @travelton)

## 1.6 (2014-1-13)

Enhancement:
  - adjust file attachment/inline name (#21 @travelton)

Bugfixes:
  - fixed issue with unordered route actions (#23 @travelton)

## 1.5 (2013-12-13)

Enhancement:
  - added ability to define non-https endpoint for debugging purposes (#23 @travelton)

## 1.4 (2013-10-16)

Bugfixes:
  - template IDs were missing from recipient-variables (#15 @travelton)
  - batch jobs trigger on to, cc, and bcc (#18 @travelton)
  - batch jobs include recipient-variables for to, cc, and bcc (#18 @travelton)
  - added method to return message-ids, for easier access (#19 @travelton)

## 1.3 (2013-09-12)

Bugfixes:

  - relaxed Guzzle requirement (#7 @travelton)
  - fixed reply-to bug (#9 @travelton)

## 1.2 (2013-09-05)

Bugfixes:

  - fixed exception handling constants (@travelton)
  - fixed MessageBuilder $baseAddress return (#1 @yoye)
  - adjusted scope of recipient-variables (#3 @yoye)
  - fixed misspellings of Exceptions (#2 @dboggus)
  - undefined DEFAULT_TIME_ZONE (#4 @yoye)
  - added message IDs to return for BatchMessage (@travelton)

## 1.1 (2013-08-21)

Initial Release!
