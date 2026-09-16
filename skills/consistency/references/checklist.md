# Consistency Checklist — derived from this codebase

This checklist is NOT generic. Every item here should be a pattern actually
observed in this codebase's existing controllers/services (see
`TaskController.php`), not a best practice pulled from Laravel's docs.
Update this file whenever the codebase's conventions change — a stale
checklist will fail code that's actually consistent.

## Naming
- [ ] Controller methods use the standard resourceful names
      (`index`/`show`/`store`/`update`/`destroy`) matching existing
      controllers, not ad-hoc names (`getAll`, `fetchOne`, `save`)
- [ ] Route parameters use the same name/type as the existing convention
      (e.g. `{id}` bound as `int $id` with `->whereNumber('id')`, not a
      loosely-typed `$id` with no constraint) unless the codebase has moved
      to route-model binding — match whichever is actually in use
- [ ] Dependencies are injected via constructor property promotion
      (`private readonly TaskService $taskService`), matching existing
      controllers, not resolved via the `app()` helper or a facade inside
      the method body

## FormRequest and Resource usage
- [ ] Every route that accepts a body validates it through a dedicated
      `FormRequest` (e.g. `StoreTaskRequest`, `UpdateTaskRequest`) — not
      inline `$request->validate([...])` — unless the codebase consistently
      uses inline validation, in which case match that instead
- [ ] Every route that returns a model shapes it through an API Resource
      (`TaskResource::make($model)` for a single item,
      `TaskResource::collection($models)->response()` for a list), matching
      existing endpoints, rather than returning the raw model or a bare
      array

## Exception-to-HTTP-status mapping
- [ ] Domain validation failures (e.g. `InvalidArgumentException` thrown
      from a Service) are caught in the controller and mapped to the same
      status code existing endpoints use (400, with an `['error' =>
      $exception->getMessage()]` body)
- [ ] A `null` result from a Service/Repository lookup (not found) returns
      the same status code existing endpoints use (`response()->json(null,
      404)`), not a thrown `ModelNotFoundException` or a different shape,
      unless the codebase has moved to `findOrFail()` consistently
- [ ] Success responses use the same status code existing endpoints use for
      the same operation (200 for read/update, 201 for create)

## Layering
- [ ] Business logic (lookups, creation, validation beyond input shape)
      lives in a Service class injected into the controller — the
      controller itself stays thin (catch/translate exceptions, shape the
      response) matching `TaskController`'s split with `TaskService`
- [ ] The controller does not call Eloquent models directly when an
      existing Service/Repository already owns that responsibility for the
      same resource

## Notes
- If the reference controller doesn't establish a clear pattern for one of
  the above (e.g. it never handles a particular exception type), don't
  invent a rule — flag it as "no existing pattern to compare against"
  rather than failing against best-practices instead.
