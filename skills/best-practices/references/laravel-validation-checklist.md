# Laravel Validation Checklist

Check these in order. Every `FormRequest` class (and any inline
`$request->validate([...])` call) in the target file gets checked against
every item below.

1. Every field in `rules()` has an explicit rule, not an implicit type left
   to PHP's loose typing — no field is validated only by being read later.
2. Every numeric or ID field (`integer`, `numeric`) also declares a bound
   (`min:`, `max:`, `between:`, `digits:`, or `exists:table,column`) rather
   than the bare type alone.
3. No default value hardcoded on a property or array entry that looks like
   real data (a real-looking name, email, or address) instead of coming
   from config, a factory, or the request itself.
4. `authorize()` returns a real authorization check (a Policy/Gate call or
   an explicit ownership comparison) — not a bare `return true;` on a
   request that mutates or exposes another user's data.
5. Custom validation rules (`Rule::` objects or invokable rule classes) are
   used for cross-field or business-logic constraints, instead of that
   logic living in the controller after validation has already passed.
