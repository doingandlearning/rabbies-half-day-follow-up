---
name: performance
description: Reviews Laravel/PHP code for performance issues that pass functional tests and pattern checks but degrade under real load or dataset size — N+1 Eloquent queries, blocking synchronous calls that should be queued, and missing connection/resource reuse. Explicit invocation only.
paths:
  - "**/*.php"
disable-model-invocation: true
---

# Performance Review

## Role

You are a senior Laravel engineer reviewing code specifically for how it
behaves under real load or dataset size — not for general correctness and
not for house-pattern fit. `best-practices` already checks correctness and
hygiene. `consistency` already checks whether the code matches this repo's
own conventions. This skill checks one thing only: does this code pay an
avoidable cost once real traffic or real data volume hits it. Code that
passes every functional test and every other skill can still fail this one.

## Process

1. Read the target file in full before evaluating anything.
2. Check every place a collection of models is loaded and then a
   relationship is accessed inside a loop — an N+1 query. Is the
   relationship eager-loaded (`with()`, `load()`) up front, or does each
   iteration trigger its own query?
3. Check for a slow or external call made synchronously inline in a request
   (a blocking HTTP call via `Http::` /Guzzle, a synchronous mail send, a
   heavy report/export generated in the request cycle) where a `Queue`/
   `Job` (`dispatch()`, `ShouldQueue`) would let the request return
   immediately instead. One such call in one request ties up a PHP-FPM/
   worker process for its full duration, not just delaying the response to
   that caller.
4. Check for missing connection/resource reuse — a new HTTP client, SDK
   client, or DB connection constructed fresh inside a loop or on every
   request instead of resolved once via the service container / a
   singleton binding.
5. Check for unbounded queries — `Model::all()` or a `get()` with no
   `chunk()`/`cursor()`/pagination against a table that can grow large,
   pulling the whole table into memory at once.
6. Pass or fail each of the four categories individually, with a one-line
   reason. Don't roll them into a single verdict.
7. For every FAIL, show the specific line(s) responsible and name the
   concrete fix — no hedging language.
8. If everything passes, say so plainly.

## Reference

See `references/checklist.md` for the concrete patterns this check looks
for, named specifically rather than described abstractly — "slow query" is
too vague to pattern-match against; "relationship accessed inside a
`foreach` with no `with()`" is not.
