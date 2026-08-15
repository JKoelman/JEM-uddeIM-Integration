# JEM–uddeIM Integration

Integration layer for **JEM** and **uddeIM** on Joomla 6.

This repository is deliberately separate from the Joomla 6 compatibility work in `JKoelman/uddeIM`.

## Scope

This repository contains **INT-JEM** functionality only: integration code that connects JEM to uddeIM without turning the uddeIM core into a JEM-specific fork.

Examples include:

- contact an event organiser through uddeIM;
- attendee/event messaging;
- event-aware message context;
- recipient resolution based on JEM context;
- integration plugins/modules;
- integration-specific Playwright tests.

## What does not belong here

Generic uddeIM fixes and Joomla 6 compatibility work belong in the `joomla6-compat` branch of `JKoelman/uddeIM`:

- **CORE-J6** — changes required for Joomla 6 compatibility;
- **CORE-GEN** — generic uddeIM bug fixes, hardening and reusable extension points.

If JEM needs a capability that uddeIM does not yet expose, the preferred approach is to add a **generic** API/hook to uddeIM and keep all JEM-specific behaviour in this repository.

## Design principles

- Joomla-first
- no hard fork of uddeIM for JEM-specific behaviour
- use uddeIM public APIs/hooks wherever possible
- keep JEM and uddeIM independently installable
- test integration behaviour end-to-end with Playwright
- never commit credentials, passwords, session data or environment secrets

## Status

Initial repository structure. Existing JEM ↔ uddeIM integration findings will be documented here as they are promoted from the Joomla 6 compatibility/testing work.
