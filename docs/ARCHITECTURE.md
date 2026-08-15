# Architecture

## Separation of concerns

The work is split into three tracks:

| Track | Repository / branch | Purpose |
|---|---|---|
| CORE-J6 | `JKoelman/uddeIM` → `joomla6-compat` | Joomla 6 compatibility fixes for uddeIM |
| CORE-GEN | `JKoelman/uddeIM` → `joomla6-compat` | Generic uddeIM fixes, hardening and reusable extension points |
| INT-JEM | `JKoelman/JEM-uddeIM-Integration` | JEM-specific integration behaviour |

## Dependency direction

```text
JEM --------------------\
                         > JEM-uddeIM Integration ----> uddeIM API / hooks
uddeIM -----------------/
```

The integration layer may depend on both JEM and uddeIM. JEM-specific behaviour must not be embedded directly in uddeIM core.

## Extension rule

When an integration requirement cannot be implemented through an existing uddeIM API/hook:

1. define the smallest generic capability required;
2. implement/test that capability as CORE-GEN in `JKoelman/uddeIM`;
3. keep event-, attendee- and organiser-specific rules in this repository;
4. cover both the generic contract and the JEM integration contract with Playwright.

A generic hook such as recipient-resolution or compose-context preparation can belong in uddeIM. Code that queries JEM events or attendees does not.

## Recipient policy

Recipient **visibility** and recipient **authorization** are separate concepts.

- Visibility controls which users are shown in lists/autocomplete.
- Authorization controls who a sender may actually message.

If a future JEM integration restricts recipients to an event, attendee set or organiser relation, that rule must also be enforced server-side. Hiding users from a list alone must never be treated as an authorization boundary.

## Testing boundary

Generic uddeIM tests remain in the uddeIM Joomla 6 test track. This repository owns integration tests such as:

- JEM organiser → uddeIM compose;
- recipient/event context propagation;
- attendee messaging;
- integration-specific permission/recipient rules;
- graceful behaviour when either integration dependency is unavailable.
