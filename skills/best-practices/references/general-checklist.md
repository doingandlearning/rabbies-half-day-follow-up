# General Checklist

Check these in order. Applies to the whole file, not just requests or
controllers.

1. No overly broad `catch (\Exception $e)` or `catch (\Throwable $e)` that
   swallows the error — catch a specific exception type, or re-throw/log
   the broad catch instead of silently continuing.
2. Errors returned to the client are a deliberate, typed response (a
   thrown `HttpException`/custom exception mapped by the handler, or an
   explicit `response()->json([...], $status)`) — never a silently
   swallowed exception and never a bare `return null;` standing in for an
   error.
3. No hardcoded values (URLs, magic strings, credentials, default business
   data like a real-looking address or name) that should be configuration
   (`config('...')`, `.env`) or a named constant/Enum.
4. No `dd()`, `dump()`, `var_dump()`, or `print_r()` left in application
   code — use the `Log` facade or remove the debug call entirely.
5. Method and variable names describe intent — no single-letter names
   outside a short loop counter.
