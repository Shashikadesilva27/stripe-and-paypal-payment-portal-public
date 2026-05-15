# Architecture Notes

## Components

- REST request controller for incoming signed payment requests
- WooCommerce order creation service
- External order log table
- Payment provider abstraction
- Webhook/callback sender
- Admin configuration page

## Idempotency

External order IDs should be unique. Incoming requests update existing logs instead of creating duplicates.

## Production Hardening Checklist

- Store secrets outside public repos
- Verify provider webhooks using official signatures
- Add timestamp and replay protection
- Add rate limiting by source domain/IP
- Add structured logs with retention policy
- Add retry queue for failed callbacks
- Add staging/live environment separation
