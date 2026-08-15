# Test Matrix

This file is the persistent test matrix for the JEM ↔ uddeIM integration repository.

## Rules

- Keep this file updated whenever an integration Playwright test is added or changed.
- Record only locally confirmed outcomes as PASS/FAIL/SKIP.
- Integration tests should create their own test users/data where practical and clean them up afterwards.
- Use one Chromium worker where shared Joomla/uddeIM configuration is modified temporarily.
- Never store passwords, sessions or secrets in this repository.

## Current contracts

| ID | Contract | Layer | Status |
|---|---|---|---|
| INT-JEM-001 | JEM organiser compose can open uddeIM with a preselected organiser and message context | JEM → uddeIM compose | Previously verified in shared integration work; migration to this repository pending |
| INT-JEM-002 | Event/attendee messaging through uddeIM | JEM → uddeIM delivery | Existing shared Playwright coverage exists; repository-specific migration pending |

## Planned contracts

| ID | Contract | Notes |
|---|---|---|
| INT-JEM-003 | Integration degrades cleanly when uddeIM is unavailable/disabled | No fatal errors or broken JEM page |
| INT-JEM-004 | Integration degrades cleanly when JEM context is unavailable | No unintended unrestricted recipient fallback |
| INT-JEM-005 | Event-scoped recipient rules cannot be bypassed by manually typing a username | Only applicable once strict event recipient scope is introduced |
| INT-JEM-006 | Multiple-recipient event messaging targets only the intended active/eligible attendees | Must verify actual Inbox delivery, not UI state alone |

## Local environment baseline

Current shared development baseline:

- Joomla 6.x
- JEM 5.x
- uddeIM 5.6.x Joomla 6 compatibility work
- Playwright Chromium
- `--workers=1`

Exact versions and run results should be recorded per test batch when the integration suite is moved into this repository.
