# Test Matrix

This file is the persistent test matrix for the JEM ↔ uddeIM integration repository.

## Rules

- Keep this file updated whenever an integration Playwright test is added or changed.
- Record only locally confirmed outcomes as PASS/FAIL/SKIP.
- Integration tests should create their own test users/data where practical and clean them up afterwards.
- Use one Chromium worker where shared Joomla/uddeIM configuration is modified temporarily.
- Never store passwords, sessions or secrets in this repository.

## Current verified Event Hub checkpoint

Target: **JEM Event Hub v0.6.18.3**  
Checkpoint date: **2026-08-18**

| Batch | Contract area | Result |
|---|---|---|
| E | Itemid selector / menu context | 7/7 PASS |
| F | Automatic placement + controlled Itemid | 6/6 PASS |
| G | Functional event context | 6/6 PASS |
| H | Event announcement under automatic placement | 5/5 PASS |
| I-B | Actual uddeIM delivery/persistence with Community Builder active | 5/5 PASS |
| I-A | Actual uddeIM delivery/persistence with Community Builder System plugin disabled | 5/5 PASS |
| J | Community Builder profile-link foundation | 5/5 PASS |
| K | Community Builder avatar foundation | 5/5 PASS |
| L | Organiser card | 5/5 PASS |
| M | Organiser participants panel | 5/5 PASS |
| M2 | Open gesprek interaction hardening | 5/5 PASS |
| M3 | Organiser-attendee role consistency | 6/6 PASS |
| N | Participant search / status filters | 5/5 PASS |
| O | Organiser summary | 5/5 PASS |

Latest locally confirmed results:

```text
Batch M2 — 5 passed (1.3m)
Batch M3 — 6 passed (1.7m)
```

## Important verified behavioural contracts

- Native JEM attendees remain independently visible/usable when Event Hub is absent.
- Event Hub does not replace JEM registration data.
- uddeIM remains the message persistence/delivery layer.
- Community Builder remains optional enrichment only.
- Event announcement recipients are validated server-side against active/eligible JEM attendees.
- Waitlist/inactive users are not treated as eligible private-message recipients.
- `Open gesprek` opens a previously collapsed private-conversation section, shows the selected participant, exposes the direct-message form, performs one conversation AJAX request without document reload, and can reopen the conversation after it is collapsed again.
- A current organiser who is also a real JEM attendee remains visible in the participant panel and summary.
- That organiser-attendee row is marked with the organiser role and, when applicable, the current-user marker.
- The current user is excluded from the private-message recipient selector and has no self-message action.

## Pre-refactor regression gate

Before the planned architecture refactor is considered stable, the existing Event Hub suites must be re-run against the new `com_jemeventhub` + `mod_jemeventhub` architecture without changing their public behaviour solely to satisfy the refactor.

Additional hardening to add after the architectural baseline is green:

- dependency/health preflight;
- security/privacy recipient manipulation;
- installer/update contract;
- performance / N+1 checks for larger attendee sets;
- central status-mapping regression coverage.

## Local environment baseline

Current shared development baseline:

- Joomla 6.x
- JEM 5.x
- uddeIM Joomla 6 compatibility work
- Community Builder optional
- Playwright Chromium
- `--workers=1`
- autonomous test fixtures where practical
