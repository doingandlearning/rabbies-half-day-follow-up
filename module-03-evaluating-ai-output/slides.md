# Evaluating AI Output

**Half-Day Follow-Up — AI-Assisted Development for Laravel Teams**

<!-- end_slide -->

## What we just did in Module 2

We checked generated output against acceptance criteria — by hand, once, reading carefully.

<!-- pause -->

That worked. It also doesn't scale.

<!-- pause -->

**Today**: where manual review stops being enough on its own, and a lightweight way to check output that isn't a separate discipline from the testing you already do.

<!-- end_slide -->

## Where manual review stops being enough

<!-- incremental_lists: true -->
- **Consistency across runs**: the same prompt, run twice, can produce two different results — one review doesn't tell you about the other nine times
- **Regressions you won't spot by eye**: a change to a shared prompt or context file can quietly break something unrelated that nobody's looking at
- **Plausible but wrong**: output that reads as confident and reasonable, and is still incorrect — the failure mode manual review is worst at catching


<!-- pause -->

None of this means manual review was wrong to do. It means it needs a systematic complement, not a replacement.

<!-- end_slide -->

## A lightweight way to think about evals

Not a testing framework. Not a new discipline. A small habit:

<!-- pause -->

> **A small set of real cases, with a clear pass/fail definition, run consistently against the spec you've just built.**

<!-- pause -->

That's the whole idea. The rest is detail.

<!-- end_slide -->

## What makes a case "real"

<!-- incremental_lists: true -->
- Drawn from the actual spec's requirements — not invented edge cases nobody asked for
- Specific enough to have an unambiguous pass/fail answer
- Small in number — a handful that actually matter beats twenty that don't
- Re-runnable — the same case checked the same way every time, not re-judged fresh each time


<!-- end_slide -->

## Worked example: back to the assignee feature

See `demos/01-eval-worked-example.md` for the full walkthrough. The shape:

```
Requirement 12 (prd-task-assignment.md):
"The system must notify the newly assigned user when a task
is reassigned to them."

Eval case:
  Given: a task currently assigned to User A
  When: it is reassigned to User B
  Then: User B receives a notification, and User A does not
  Pass/fail: notification sent to exactly User B — yes/no
```

<!-- pause -->

This is a test. That's deliberate.

<!-- end_slide -->

## Evals and your existing test culture

<!-- incremental_lists: true -->
- A well-written eval case and a well-written Pest test are asking the same question: does the behaviour match the spec?
- The difference is what triggers it — a test runs on every CI build; an eval case is specifically aimed at checking AI-generated or AI-assisted output against the spec it was generated from
- If your Pest suite already covers a requirement precisely, that test *is* your eval case for that requirement — you don't need a separate artefact
- Evals earn their keep where the test suite doesn't yet reach: judging output quality, consistency across repeated generation, or behaviour that's hard to assert exactly (a wording, a tone, a partial match)


<!-- end_slide -->

## Where this plugs in, concretely

| Requirement type | Where the check lives |
|---|---|
| Exact behaviour, deterministic | A Pest test — already your existing discipline |
| Exact behaviour, currently untested | A new Pest test — same discipline, just add the gap |
| Output quality/consistency, hard to assert exactly | A small eval case set — pass/fail judged against explicit criteria, re-run each time output is regenerated |

<!-- pause -->

**The point**: evals aren't a new pillar next to testing. They're the same discipline, aimed at the specific gap testing doesn't cover yet — judged consistently, not by feel, each time.

<!-- end_slide -->

## Try it now

Using Module 2's real story and spec: write 3-4 eval cases, in the format on the slide — given / when / then / pass-fail — for requirements that feel hardest to verify just by reading the code.

See `exercises/README.md` for the worksheet.

<!-- end_slide -->

## Bridge to Module 4

**What we've covered**: where manual review stops scaling, a lightweight eval format, and how it sits inside your existing test culture rather than beside it.

**What's next**: whatever's come up in real work since the core day that hasn't had a home yet — open Q&A — then agreeing what the team actually trials before the next check-in.

<!-- end_slide -->

# Questions?

*Evaluating AI Output — Half-Day Follow-Up*
