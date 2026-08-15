# Tests

Integration-specific Playwright tests belong here or in the shared Playwright project until they are migrated.

The canonical integration test inventory is `docs/TEST_MATRIX.md`.

Guidelines:

- create test users/data where practical;
- clean up after each test or suite;
- use unique message/event markers;
- verify real delivery/authorization rather than only UI state;
- use `--workers=1` when tests temporarily modify shared Joomla/uddeIM configuration;
- never commit credentials or local environment secrets.
