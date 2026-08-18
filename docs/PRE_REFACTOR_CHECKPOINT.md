# JEM Event Hub — pre-refactor checkpoint

**Checkpoint:** Event Hub v0.6.18.3  
**Date:** 2026-08-18

This checkpoint freezes the verified behaviour before the integration is refactored from a module-heavy implementation to a lightweight component + module architecture.

## Core definition

JEM Event Hub is a contextual communication and attendee-interaction layer for JEM events. It may optionally combine existing Joomla extensions such as uddeIM and Community Builder without taking over their responsibilities.

## Responsibility boundaries

- **JEM** owns events, organisers/contacts, registrations and attendee status.
- **Joomla** owns user identity, authentication and ACL.
- **uddeIM** owns one-to-one messaging, announcement delivery and message persistence.
- **Community Builder** is optional profile enrichment only.
- **Event Hub** owns context, orchestration and UI.

Event Hub must not create a second attendee database, a second identity model, a replacement registration model or a replacement private-message store.

## Verified functionality before refactor

- Itemid/menu context and automatic event placement.
- Server-authoritative event context.
- Organiser ↔ participant private uddeIM messaging.
- Event announcements delivered as individual private messages to eligible active attendees.
- Community Builder optional integration, profile links and avatars.
- Organiser card.
- Organiser participant panel.
- Client-side participant search and status filters.
- Organiser summary derived from JEM registrations.
- A real JEM registration remains visible when the organiser is also an attendee.
- Organiser-attendee rows are marked as `Organisator`; the current user is additionally marked as `Jij`.
- Own registration is never offered as a private-message recipient.
- Native JEM attendee UI remains independently available.

## Confirmed Playwright results

- Batch E: 7/7 PASS
- Batch F: 6/6 PASS
- Batch G: 6/6 PASS
- Batch H: 5/5 PASS
- Batch I-B: 5/5 PASS
- Batch I-A with Community Builder System plugin disabled: 5/5 PASS
- Batch J: 5/5 PASS
- Batch K: 5/5 PASS
- Batch L: 5/5 PASS
- Batch M: 5/5 PASS
- Batch M3: 6/6 PASS
- Batch N: 5/5 PASS
- Batch O: 5/5 PASS

Latest locally confirmed Batch M3 result: `6 passed (1.7m)`.

## Refactor target

```text
pkg_jemeventhub
├─ com_jemeventhub
│  ├─ services / providers
│  ├─ dependency and health diagnostics
│  ├─ ACL/capabilities
│  ├─ central JEM status mapping
│  └─ AJAX/API endpoints where appropriate
├─ mod_jemeventhub
│  └─ contextual frontend rendering
└─ plg_system_jemeventhubplacement
   └─ automatic placement on JEM event detail pages
```

The existing frontend behaviour is not to be redesigned during the first refactor step. The established Playwright suites are the regression safety net.

## Initial refactor backlog

1. Extract JEM event/registration logic behind a provider/service.
2. Extract uddeIM integration behind a messaging provider.
3. Extract Community Builder integration behind an optional profile provider.
4. Centralise attendee-status mapping.
5. Add dependency/health diagnostics.
6. Keep per-module-instance presentation settings in `mod_jemeventhub`.
7. Preserve native JEM rendering independence.
8. Re-run the established Event Hub Playwright batches after each architectural slice.

No new frontend feature should be required to complete the initial architecture migration.