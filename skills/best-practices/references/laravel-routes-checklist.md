# Laravel Routes Checklist

Check these in order. Every controller action in the target file gets
checked against every item below.

1. Every action that returns a model or collection shapes it through an API
   Resource (`SomeResource::make(...)` / `SomeResource::collection(...)`),
   not a raw model, array, or `->toArray()`.
2. Every `store()` action that creates a resource returns `201` (e.g.
   `response()->json(..., 201)`), not the default `200`.
3. Route parameters are typed (`int $id`) or route-model-bound
   (`Task $task`), never left as an untyped/untrimmed string when they
   represent an id or other structured value.
4. Query parameters with a constraint are validated (via a FormRequest, or
   `$request->validate([...])` on the query bag) rather than checked with a
   manual `if` inside the action body.
5. Every action has a docblock or a name that makes its purpose obvious on
   its own (`index`/`show`/`store`/`update`/`destroy`, or a clearly named
   custom action) — not a vague name like `handle()` or `process()`.
