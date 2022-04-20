# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.1.0] - 2022-04-20
### Added
- New methods for `BigInteger` polyfill class: `compareTo`, `plus`, `minus` and `multipliedBy`. 

### Fixed
- Fixed an index mismatch in the array returned by `Parser::decode()`.
- Fixed incorrect deserialization in `BigInteger::fromBytes()`.
- Improved type checking and PHPDoc in `Parser`.
- Removed dead and/or redundant code.
- Other minor bug fixes.

## [1.0.1] - 2022-04-18
### Changed
- Updated dependencies.

## [1.0.0] - 2022-04-18
### Added
- Serialization/deserialization of integers, longs and strings.
- Support for base64 URL and RLE encoding/decoding.
- Support for 32-bit systems (install [brick/math](https://packagist.org/packages/brick/math)).
- Unit testing and code coverage.


[Unreleased]: https://github.com/Sysbot-org/bin/compare/1.0.1...HEAD
[1.0.1]: https://github.com/Sysbot-org/bin/compare/1.0.0...1.0.1
[1.0.0]: https://github.com/Sysbot-org/tgscraper/releases/tag/1.0.0