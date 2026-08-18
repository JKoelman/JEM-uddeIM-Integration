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

Latest locally confirmed pre-refactor results:

```text
Batch M2 — 5 passed (1.3m)
Batch M3 — 6 passed (1.7m)
```

## Architecture refactor verification

The architecture refactor keeps the public Event Hub behaviour stable while responsibilities are moved from `mod_jemeventhub` into the `com_jemeventhub` technical backbone.

| Phase | Package / area | Batch | Result | Verified contract |
|---|---|---|---|---|
| A1 | `v0.7.0-alpha1` component backbone | P | 5/5 PASS | Component dashboard and dependency health are healthy; Community Builder remains optional; placement, AJAX and module dependencies remain available. |
| A2 | `v0.7.0-alpha2` JEM attendee provider | Q | 7/7 PASS | Organiser participant panel uses the component attendee provider and all organiser/self-registration contracts remain intact. |

Latest locally confirmed refactor result:

```text
Batch Q — 7 passed (1.8m)
```

### A2 component-provider contract

The organiser participant container is verified to use the component provider in normal runtime:

```text
[data-jem-eventhub-organizer-participants]
data-participant-provider="component"
```

This is the regression signal that `mod_jemeventhub` is using the `JemAttendeeProvider` from `com_jemeventhub` rather than the temporary legacy fallback.

A2 remains read-only with respect to JEM registrations. No Event Hub attendee storage or schema is introduced.

### Next refactor phase

A3 should centralise **Community Builder profile/avatar enrichment** behind a component service/provider while preserving the existing frontend output and the verified CB-on / CB-off fallback behaviour.

The uddeIM write path remains outside this phase.

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

## Refactor regression gate

Each refactor phase must retain the existing verified public behaviour. Do not change frontend contracts solely to satisfy the refactor.

Additional hardening after the architectural baseline is green:

- dependency/health preflight;
- security/privacy recipient manipulation;
- installer/update contract;
- performance / N+1 checks for larger attendee sets;
- central status-mapping regression coverage;
- consistent empty/error states;
- component/service adapter separation.

## Local environment baseline

Current shared development baseline:

- Joomla 6.x
- JEM 5.x
- uddeIM Joomla 6 compatibility work
- Community Builder optional
- Playwright Chromium
- `--workers=1`
- autonomous test fixtures where practical
- Joomla site timezone rather than a hardcoded local timezone
