# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [0.12.39] - 2021-10-16

### Added
- Polyfill dependency for mbstring extension.

## [0.12.38] - 2021-09-17

### Fixed
- PHP 8.1 Deprecation notices with missing returns.

## [0.12.37] - 2021-08-29

### Added
- `InvalidValue` now exposes `data` and `constraint` values for structured context of validation failure.

### Fixed 
- Handling of `multipleOf: 0.01` float precision.

## [0.12.36] - 2021-07-14

### Added
- Optional properties mapping in names reflector.

## [0.12.35] - 2021-06-18

### Fixed
- Suppressed failure during reference resolution in complex schemas.

## [0.12.34] - 2021-06-17

### Fixed
- Suppressed failure during reference resolution.

## [0.12.33] - 2021-05-27

### Fixed
- Disable validation for default and const values.

## [0.12.32] - 2021-05-12

### Fixed
- Suppressed failure during validation in referenced schema.

## [0.12.31] - 2020-09-21

### Fixed
- Missing support for `null` `default` values, [#107](https://github.com/swaggest/php-json-schema/issues/107). 

## [0.12.30] - 2020-09-09

### Added
- Support for `full-date` and `full-time` formats as aliases of `date` and `time` respectively. 

## [0.12.29] - 2020-03-19

### Added
- AJV and JSON Shema Test suites updated. 

### Fixed
- Validating `enum` and `const` in case of float vs. int comparison for equal values.  
- Validation of time format edge cases.

## [0.12.28] - 2020-01-27

### Fixed
- Trying to access array offset on value of type null in PHP 7.4, [#101](https://github.com/swaggest/php-json-schema/pull/101).

## [0.12.27] - 2020-01-26

### Fixed
- PHP version check for empty property name support.

## [0.12.26] - 2020-01-24

### Fixed
- Export additional and pattern properties, [#97](https://github.com/swaggest/php-json-schema/pull/97), [#99](https://github.com/swaggest/php-json-schema/pull/99).

## [0.12.25] - 2020-01-07

### Fixed
- Validation always passes with invalid JSON schema, [#96](https://github.com/swaggest/php-json-schema/pull/96).

## [0.12.24] - 2019-12-03

### Fixed
- Behavior of `tolerateStrings` when decoding bool and applying values to original data (now for real).

## [0.12.23] - 2019-12-02

### Fixed
- Behavior of `tolerateStrings` when decoding bool and applying values to original data.

## [0.12.22] - 2019-10-22

### Added
- `Schema::unboolSchemaData` and `Schema::unboolSchema` to public visibility.

## [0.12.21] - 2019-10-01

### Fixed
- Treating unresolvable schema as a prohibitive `false` schema instead of failing with exception.

## [0.12.20] - 2019-09-22

### Changed
- Export `null` value instead of skipping it for properties having `null` type.

[0.12.39]: https://github.com/swaggest/php-json-schema/compare/v0.12.38...v0.12.39
[0.12.38]: https://github.com/swaggest/php-json-schema/compare/v0.12.37...v0.12.38
[0.12.37]: https://github.com/swaggest/php-json-schema/compare/v0.12.36...v0.12.37
[0.12.36]: https://github.com/swaggest/php-json-schema/compare/v0.12.35...v0.12.36
[0.12.35]: https://github.com/swaggest/php-json-schema/compare/v0.12.34...v0.12.35
[0.12.34]: https://github.com/swaggest/php-json-schema/compare/v0.12.33...v0.12.34
[0.12.33]: https://github.com/swaggest/php-json-schema/compare/v0.12.32...v0.12.33
[0.12.32]: https://github.com/swaggest/php-json-schema/compare/v0.12.31...v0.12.32
[0.12.31]: https://github.com/swaggest/php-json-schema/compare/v0.12.30...v0.12.31
[0.12.30]: https://github.com/swaggest/php-json-schema/compare/v0.12.29...v0.12.30
[0.12.29]: https://github.com/swaggest/php-json-schema/compare/v0.12.28...v0.12.29
[0.12.28]: https://github.com/swaggest/php-json-schema/compare/v0.12.27...v0.12.28
[0.12.27]: https://github.com/swaggest/php-json-schema/compare/v0.12.26...v0.12.27
[0.12.26]: https://github.com/swaggest/php-json-schema/compare/v0.12.25...v0.12.26
[0.12.25]: https://github.com/swaggest/php-json-schema/compare/v0.12.24...v0.12.25
[0.12.24]: https://github.com/swaggest/php-json-schema/compare/v0.12.23...v0.12.24
[0.12.23]: https://github.com/swaggest/php-json-schema/compare/v0.12.22...v0.12.23
[0.12.22]: https://github.com/swaggest/php-json-schema/compare/v0.12.21...v0.12.22
[0.12.21]: https://github.com/swaggest/php-json-schema/compare/v0.12.20...v0.12.21
[0.12.20]: https://github.com/swaggest/php-json-schema/compare/v0.12.19...v0.12.20
