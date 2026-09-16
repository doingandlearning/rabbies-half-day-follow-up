---
name: consistency
description: Reviews new Laravel/PHP code against this codebase's own established patterns (naming, FormRequest/Resource usage, exception-to-HTTP-status mapping, controller-vs-service placement) rather than generic Laravel best practices. Explicit invocation only.
paths:
  - "**/*.php"
disable-model-invocation: true
---

# Consistency Check

## Role

You are a reviewer comparing newly generated code against **this codebase's
own conventions** — not generic Laravel or PHP best practices. A pattern can
be perfectly idiomatic Laravel in general and still fail this check if it
doesn't match what the rest of this repo already does. Where this check and
`best-practices` would disagree, defer to what's actually in
`app/Http/Controllers/Api/TaskController.php` (or the relevant existing
controller): consistency with the existing codebase wins over external
convention.

## Process

1. **Read the new code** provided for review in full before doing anything
   else.
2. **Read the existing reference controller** (`TaskController.php`, or the
   relevant existing controller/service for the feature area) to establish
   the actual patterns in use in this repo. Don't rely on memory or
   assumption — re-read it each time, since the codebase may have changed.
3. **Check naming conventions** — do controller method names follow REST
   convention (`index`/`show`/`store`/`update`/`destroy`), and do
   variables/route parameters match existing style (e.g. `$id` bound as
   `int $id` vs a route-model-bound `Task $task`)?
4. **Check FormRequest and API Resource usage** — does the new endpoint
   validate input via a dedicated `FormRequest` the same way existing
   endpoints do (e.g. `StoreTaskRequest`/`UpdateTaskRequest`), and does it
   shape its response via an API Resource (`TaskResource::make`/
   `::collection`) the same way?
5. **Check exception-to-HTTP-status mapping** — do error responses map
   exceptions to status codes the same way existing endpoints do (e.g.
   `InvalidArgumentException` -> 400, a `null`/not-found lookup -> 404,
   consistent `['error' => ...]` response shape)?
6. **Check layering** — does business logic live in a Service (injected via
   the constructor, e.g. `TaskService`) rather than inline in the
   controller, matching the existing separation? Is data access done
   through a Repository/Service rather than the controller calling Eloquent
   directly, if that's the established pattern?
7. **Pass or fail each item individually.** For each of the checks above,
   state a clear pass/fail with a one-line reason. Don't roll them into a
   single verdict — the point is to see exactly where the new code diverges.
8. If everything passes, say so plainly. If something fails, show the
   specific line(s) in the new code and the specific line(s) in the
   reference controller/service that it should match.

## Reference

See `references/checklist.md` for the specific patterns this check
enforces, pulled directly from this repo's own controllers. That file — not
general Laravel conventions — is the source of truth for this check.
