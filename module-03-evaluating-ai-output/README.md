# Module 3 — Evaluating AI Output

**AI-Assisted Development for Laravel Teams: Half-Day Follow-Up**

## Overview

A dedicated section on an area the team flagged as light: evaluating AI-generated output beyond manual review. Module 2 showed manual review working, but also showed its limit — checking one story's output by eye, once, does not scale to catching regressions or inconsistency across many runs. This session introduces a lightweight way to think about evals — a small set of real cases with a clear pass/fail definition, run consistently — and uses the story from Module 2 as the worked example.

## Learning objectives

By the end of this session, participants will be able to:

- Explain where manual review and sign-off stop being sufficient on their own
- Define a lightweight eval: a small set of real cases with a clear, checkable pass/fail definition
- Write a handful of meaningful eval cases for a real spec, using Module 2's story as the example
- Explain how evals connect to the team's existing test culture (Pest) rather than becoming a separate discipline

## Suggested running time

35 minutes

## Session structure

| Section | Time |
|---|---|
| Where manual review stops being enough | 8 min |
| A lightweight way to think about evals | 10 min |
| Worked example: eval cases for Module 2's story | 12 min |
| Connecting evals to existing test culture | 5 min |

## Demos

- **Worked eval example**: turning the acceptance criteria from `../initial-delivery/tasks/prd-task-assignment.md` into a small set of concrete pass/fail cases — see `demos/01-eval-worked-example.md`

## Files

- `slides.md` — Presenterm-compatible slide deck
- `TEACHING_NOTES.md` — Facilitator notes
- `demos/` — The worked eval example
- `exercises/` — Eval worksheet template, used to write real cases for Module 2's story
