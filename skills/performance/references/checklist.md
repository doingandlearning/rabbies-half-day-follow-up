# Performance Checklist

Concrete patterns to check for — named specifically so they can actually be
pattern-matched, not described in the abstract.

## N+1 Eloquent queries
- [ ] No `foreach` (or Blade/Resource loop) over a collection that accesses
      a relationship (`$task->owner`, `$task->comments`) without that
      relationship having been eager-loaded via `with()`/`load()` first
- [ ] Queries built for an API index/list endpoint eager-load every
      relationship the Resource actually serializes
- [ ] `withCount()` is used instead of loading a full relationship just to
      count it

## Blocking calls that should be queued
- [ ] No synchronous outbound HTTP call (`Http::get/post`, Guzzle) inside a
      request-handling method for work that doesn't need to block the
      response (webhooks, notifications, syncing to a third-party system)
- [ ] No synchronous `Mail::send`/`Notification::send` for non-transactional
      email inside the request cycle instead of a queued mailable/
      notification (`ShouldQueue`)
- [ ] Any genuinely slow or external operation is dispatched as a `Job`
      (`SomeJob::dispatch(...)`) rather than awaited inline and returned to
      the caller

## Connection / resource reuse
- [ ] HTTP/SDK clients are resolved once (bound in a service provider, or
      via `Http::baseUrl(...)`/a shared facade) — not `new`'d fresh inside a
      loop or on every request
- [ ] No DB connection or client is opened without being released, outside
      of Laravel's own connection pooling

## Unbounded queries
- [ ] No `Model::all()` (or `get()` with no limit) against a table that can
      grow large in a request-handling path — use pagination (`paginate()`,
      `simplePaginate()`), `chunk()`, or `cursor()` instead
- [ ] Export/report-style operations that must touch every row use
      `chunk()`/`cursor()` or are pushed to a queued Job, not loaded fully
      into memory inline

## Notes
- Every finding here should describe the *same functional result*, just
  slower or more resource-hungry under real load/data volume — if a change
  would alter behaviour or output, it belongs in `best-practices` or
  `consistency`, not here.
