# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [0.12.22] - 2019-10-22

### Added
- `Schema::unboolSchemaData` and `Schema::unboolSchema` to public visibility.

## [0.12.21] - 2019-10-01

### Fixed
- Treating unresolvable schema as a prohibitive `false` schema instead of failing with exception.

## [0.12.20] - 2019-09-22

### Changed
- Export `null` value instead of skipping it for properties having `null` type.

[0.12.22]: https://github.com/swaggest/php-code-builder/compare/v0.12.21...v0.12.22
[0.12.21]: https://github.com/swaggest/php-code-builder/compare/v0.12.20...v0.12.21
[0.12.20]: https://github.com/swaggest/php-code-builder/compare/v0.12.19...v0.12.20
