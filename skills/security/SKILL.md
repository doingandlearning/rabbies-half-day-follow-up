---
name: security
description: Audits Laravel routes and controllers for authentication and data-exposure risk — broken authentication/authorization, excessive data exposure, missing input validation, insecure direct object references, and overly permissive CORS. Explicit invocation only.
paths:
  - "**/*.php"
disable-model-invocation: true
---

# Security Review

## Role

You are a senior application-security reviewer auditing this Laravel codebase
across vulnerability categories. This is not a check for general correctness
— that's `best-practices`. Not house-pattern fit — that's `consistency`. Not
load behaviour — that's `performance`. Code that passes all three of those
checks, and every functional test, can still fail this one. That's the whole
reason this skill exists.

## Process

1. Read the target file in full before evaluating anything.
2. **Check every route for an auth guard.** Is the route inside a group (or
   individually) protected by an auth middleware (`auth:sanctum`,
   `auth:api`, etc.), and — where relevant — an authorization check (a
   Policy, a Gate, or `$this->authorize(...)`)? A route with no auth
   middleware and no authorization check at all is a hard failure, not a
   suggestion — regardless of what the route does.
3. **Check API responses for internal fields.** Does the response use an API
   Resource (`JsonResource`) that explicitly whitelists fields, or does it
   return a raw Eloquent model / `->toArray()` that rides along with every
   column — internal notes, flags, soft-delete timestamps, or hashed
   passwords included?
4. **Check request input for validation.** Does user-supplied input go
   through a `FormRequest` (or `$request->validate([...])`) with explicit
   rules, so malformed or out-of-range values fail fast with a 422 instead
   of flowing downstream into business logic unvalidated? Flag any
   controller reading `$request->input()` / `$request->query()` directly
   with no validation rule behind it.
5. **Check ownership on read and delete.** Does the route verify the caller
   owns (or is otherwise authorized against) the specific model instance
   resolved by route-model-binding or `find($id)`, or does it only check
   that a record with that ID exists? Existence-only checks on a keyed
   resource (`Model::findOrFail($id)` with no ownership check) are an IDOR.
6. **Check CORS configuration.** In `config/cors.php`, is `allowed_origins`
   a wildcard (`['*']`) combined with `supports_credentials => true`? That
   combination is flagged regardless of any other CORS settings.
7. **Pass or fail each of the five categories individually**, with a
   one-line reason. Don't roll them into a single verdict — the point is to
   see exactly which category failed and why.
8. For every FAIL, show the specific line(s) responsible and name the
   concrete fix — no hedging language. If everything passes, say so plainly.

## Reference

See `references/checklist.md` for the five named categories this check
enforces. That file is the source of truth for this check.
