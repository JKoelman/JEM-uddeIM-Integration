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
| A3 | `v0.7.0-alpha3` Community Builder profile provider | R | 5/5 PASS | Participant and organiser runtimes use the component CB profile provider; existing organiser/participant profile links remain intact; CB-off keeps the provider loaded while publishing no CB profile links. |
| A4 | `v0.7.0-alpha4` event context resolver | S | 5/5 PASS | Participant and organiser runtimes use the component event context resolver; creator fallback and uddeIM organiser targeting remain correct; A2 attendee and A3 profile providers remain compatible. |
| A5 | `v0.7.0-alpha5` messaging eligibility provider | T | 5/5 PASS | Organiser runtime uses the component messaging eligibility provider; only active valid JEM attendees enter the recipient preview; waitlist/inactive and self-recipient are excluded; A2–A4 providers remain compatible. |
| A6 | `v0.7.0-alpha6` dependency/fallback hardening | U | 5/5 PASS | All four internal runtime services are required and healthy; frontend runtime uses strict component-service mode without legacy providers; eligibility and native JEM status/self-exclusion remain intact; Community Builder remains an optional external dependency. |
| A7 | `v0.7.0-alpha7` generic messaging provider | V | 5/5 PASS | `MessagingProviderInterface` and `UddeimMessagingProvider` are required and healthy; frontend publishes uddeIM as the active component messaging adapter; SEF/non-SEF compose routing remains valid; real send + conversation-read roundtrip keeps message semantics intact; JEM eligibility and self-exclusion remain outside the provider. |
| A8 | `v0.7.0-alpha8` messaging provider registry/resolver | W | 5/5 PASS | Registry and resolver are required and healthy; `auto` resolves to available uddeIM, explicit `uddeim` resolves the same provider, `off` fails closed without messaging actions, and switching back to `auto` restores the existing compose contract. |

Latest locally confirmed refactor results:

```text
Batch Q — 7 passed (1.8m)
Batch J + K regression gate on alpha3 — 10 passed (2.9m)
Batch R — 5 passed (1.6m)
Batch S — 5 passed (2.1m)
Batch T — 5 passed (2.0m)
Batch U — 5 passed (1.4m)
Batch V — 5 passed (1.4m)
Batch W — 5 passed (1.6m)
```

### A2 component-provider contract

The organiser participant container is verified to use the component provider in normal runtime:

```text
[data-jem-eventhub-organizer-participants]
data-participant-provider="component"
```

This is the regression signal that `mod_jemeventhub` is using the `JemAttendeeProvider` from `com_jemeventhub` rather than the temporary legacy fallback.

A2 remains read-only with respect to JEM registrations. No Event Hub attendee storage or schema is introduced.

### A3 Community Builder provider contract

The Event Hub runtime is verified to use the component Community Builder provider:

```text
data-profile-provider="component"
```

Verified behaviour:

- participant runtime uses the component provider;
- organiser runtime uses the same component provider;
- organiser CB profile enrichment remains available;
- active participant CB profile enrichment remains available;
- with Community Builder integration disabled, the component provider remains loaded but no CB profile links are published;
- the existing Batch J + K profile/avatar behaviour remains green on alpha3.

Community Builder remains optional enrichment only. JEM remains the attendee/status source of truth and uddeIM write behaviour is not moved in A3.

### A4 event context resolver contract

The Event Hub runtime is verified to use the component event context resolver:

```text
data-event-context-provider="component"
```

Verified behaviour:

- participant runtime uses the component resolver;
- organiser runtime uses the same component resolver;
- when no contact-user is linked, the JEM event creator remains the resolved organiser;
- the uddeIM compose link remains targeted at the resolved organiser;
- A4 remains compatible with the already verified A2 attendee provider and A3 Community Builder profile provider.

A4 remains read/context-only. Event/menu filtering, ACL and uddeIM write behaviour remain outside the resolver.

### A5 messaging eligibility provider contract

The organiser runtime is verified to use the component messaging eligibility provider:

```text
data-messaging-eligibility-provider="component"
```

Verified behaviour:

- only active valid JEM attendees are included in the recipient preview;
- waitlist and inactive registrations are excluded from private-message eligibility;
- the organiser's own real JEM registration remains visible but is never a recipient;
- A5 remains compatible with the A2 attendee provider, A3 Community Builder profile provider and A4 event context resolver.

A5 centralises read/preview eligibility only. The existing write-time recipient validation remains in `JemeventhubHelper.php` as the server-side security gate. uddeIM persistence/delivery is not moved in A5.

### A6 dependency/fallback hardening contract

The Event Hub runtime is verified to use strict component-service mode:

```text
data-component-service-mode="strict"
```

Verified behaviour:

- `EventContextResolver`, `JemAttendeeProvider`, `CommunityBuilderProfileProvider` and `MessagingEligibilityProvider` are required internal runtime services and are reported healthy by the component dashboard;
- the frontend runtime uses the component services without legacy provider/query fallbacks;
- messaging eligibility continues to work in strict mode;
- native JEM participant status and self-recipient exclusion remain intact;
- Community Builder itself remains optional even though the internal Community Builder profile provider is required as part of Event Hub's runtime architecture.

A6 removes the temporary A2–A5 legacy query/provider fallbacks. An internally incomplete Event Hub package should fail closed rather than silently re-enter legacy module logic.

### A7 generic messaging provider contract

The Event Hub runtime is verified to publish the active messaging adapter:

```text
data-messaging-provider="uddeim"
data-messaging-provider-adapter="component"
```

Verified behaviour:

- `MessagingProviderInterface` and `UddeimMessagingProvider` are required internal services and are reported healthy by the component dashboard;
- uddeIM is the first concrete provider behind the generic messaging boundary;
- the organiser compose-link remains correct for both SEF and non-SEF Joomla routing;
- a real organiser-to-participant send followed by conversation-read succeeds through the adapter without changing message semantics;
- JEM eligibility, waitlist/inactive filtering and current-user self-exclusion remain owned by the Event Hub component layer rather than the PMS adapter.

A7 establishes provider abstraction only. No second PMS implementation is added and JEM remains the event/registration source of truth.

### A8 messaging provider registry/resolver contract

Provider selection is now centralised through the component registry/resolver:

```text
data-messaging-provider-resolution="registry"
data-messaging-provider-requested="auto|uddeim|off"
data-messaging-provider-status="available|disabled|unavailable|unsupported"
```

Verified behaviour:

- `MessagingProviderRegistry` and `MessagingProviderResolver` are required internal services and are reported healthy by the component dashboard;
- `auto` resolves through the registry to the available uddeIM provider;
- explicit `uddeim` resolves the same provider without moving JEM policy into the PMS adapter;
- `off` is a controlled fail-closed state and publishes no messaging actions;
- server-side messaging resolution uses the same provider resolver, so disabling messaging cannot be bypassed by direct AJAX use;
- returning to `auto` restores the existing uddeIM compose contract.

A8 still ships only uddeIM as a concrete provider. The registry/resolver is the extension point for later additional providers.

### Next refactor phase

With provider selection now centralised, the next hardening phase should focus on **security/privacy recipient manipulation**. Test direct AJAX tampering, forged recipient IDs, waitlist/inactive targets, self-recipient attempts and event/recipient cross-context attacks. These checks should remain Event Hub/JEM policy and must not be delegated to the PMS adapter. After that, installer/update and larger-set performance coverage are the next high-value gates.

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

- security/privacy recipient manipulation;
- installer/update contract;
- performance / N+1 checks for larger attendee sets;
- central status-mapping regression coverage;
- consistent empty/error states;
- component/service adapter separation;
- generic messaging-provider adapter tests;
- later second-provider proof-of-concept only after the security/update gates are green.

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
