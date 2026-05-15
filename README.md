# WooCommerce Stripe and Paypal External Payment Portal Showcase

Public-safe engineering showcase for a WooCommerce external payment portal architecture.

This repository demonstrates the architecture and implementation pattern for a cross-domain WooCommerce payment portal without exposing any client-owned code, production domains, credentials, business rules, or private implementation details.

## What this demonstrates

- External order intake from a partner website
- Secure signed redirect flow
- Virtual WooCommerce checkout order creation
- Stripe / PayPal style payment session abstraction
- Webhook/callback verification
- Order status reconciliation
- External order log table
- Admin settings structure
- Debug logging strategy
- Public-safe checkout template and JavaScript flow

## What is intentionally excluded

- Client-specific business logic
- Production API keys or credentials
- Real domains and endpoints
- Proprietary UI assets
- Original comments
- NDA-protected workflows

## High-level flow

1. External site sends a signed payment request.
2. Portal validates the request signature.
3. Portal creates or updates an external order log entry.
4. Portal creates a WooCommerce order using a virtual product.
5. Customer completes payment using a selected gateway.
6. Gateway callback/webhook confirms payment status.
7. Portal notifies the external site through a signed webhook.
8. Customer is redirected back to the originating site.

## Security features shown

- HMAC request signing
- Timestamp validation placeholder
- Input sanitization
- Server-side payment creation
- No client-side secret exposure
- Idempotency-safe order log design
- Webhook verification layer

## Suggested use

This repo is intended as a portfolio/demo project for senior WordPress, WooCommerce, API, and payment integration work.

It is not a drop-in production plugin. It is a public-safe architectural demonstration.
