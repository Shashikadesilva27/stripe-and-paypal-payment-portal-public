# Example API Payloads

## Incoming payment request

```json
{
  "external_order_id": "EXT-10001",
  "source_domain": "partner.example.com",
  "amount": 149.99,
  "currency": "USD",
  "return_url": "https://partner.example.com/thank-you/",
  "timestamp": 1710000000
}
```

Header:

```http
X-PPS-Signature: hmac_sha256_signature
```

## Outgoing payment confirmation callback

```json
{
  "external_order_id": "EXT-10001",
  "woo_order_id": 1234,
  "status": "paid",
  "timestamp": 1710000000
}
```
