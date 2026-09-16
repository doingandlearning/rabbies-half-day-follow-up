# Security Checklist

Five categories, named specifically so they can be pattern-matched against.

## Authentication gaps
- [ ] Every route (or its enclosing `Route::group`/`Route::middleware`) has
      an auth middleware (`auth:sanctum`, `auth:api`, `auth`) applied
- [ ] Routes restricted to a role or ability (e.g. admin/manager-only
      actions) also check that role/ability explicitly via a Policy, Gate,
      or `$this->authorize(...)` — authentication alone is not authorization
- [ ] No route relies on obscurity (an unguessable URL or numeric ID)
      instead of an enforced auth middleware

## Data exposure
- [ ] The response is built from an API Resource (`JsonResource` /
      `ResourceCollection`) with an explicit, scoped field list — not a raw
      Eloquent model or `->toArray()`/`response()->json($model)` returned
      as-is
- [ ] No internal-only column (notes, flags, audit annotations, admin
      comments, password hash, remember token) is reachable in a response
      to a non-privileged caller
- [ ] Any field intentionally exposed to some callers but not others is
      gated by an explicit `when()`/role check inside the Resource, not by
      hoping the client ignores it

## Input validation
- [ ] Every controller action validates its input via a `FormRequest`
      (preferred) or an inline `$request->validate([...])` — not read
      straight off `$request->input()`/`$request->query()` with no rule
- [ ] Numeric/ID inputs have an explicit rule (`integer`, `exists:`) or a
      route constraint (`->whereNumber(...)`), not just implicit casting
- [ ] Invalid input fails with a 422 (Laravel's default validation
      response) at the boundary — it never reaches the Service/Repository
      layer unvalidated

## Insecure direct object references (IDOR)
- [ ] Read routes (`GET /{id}`) check that the caller owns or is otherwise
      authorized against the specific model instance — not just that
      `Model::find($id)` returns something
- [ ] Write/delete routes (`PUT`/`PATCH`/`DELETE /{id}`) apply the same
      ownership check as read routes for the same resource, typically via a
      Policy (`$this->authorize('update', $task)`) rather than a manual
      owner-id comparison scattered per-controller
- [ ] No route trusts a client-supplied ID as sufficient proof of
      authorization on its own

## CORS misconfiguration
- [ ] In `config/cors.php`, `allowed_origins` is not `['*']` when
      `supports_credentials` is `true` — that combination is flagged
      regardless of any other settings
- [ ] If a wildcard origin is genuinely required, `supports_credentials` is
      `false`
- [ ] Allowed origins, when explicit, are an actual allowlist — not an
      `allowed_origins_patterns` entry broad enough to match unintended
      hosts

## Notes
- Each finding here should be about whether the code is *safe*, not whether
  it works or matches house style — those are `best-practices` and
  `consistency`. A change that fixes a finding here should not alter
  functional behaviour for a legitimate, authorized caller.
