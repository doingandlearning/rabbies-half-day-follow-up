# AI-Assisted Development for Laravel Teams — Half-Day Follow-Up

**A half-day working session for the Rabbies development team, delivered in-person or via Microsoft Teams, approximately four weeks after the core day.**

---

## What this session is

This is not more content delivery. The core day covered foundations, Copilot's surfaces, SDLC integration, and a first look at agents. By the time this session happens, the team has had several weeks to apply that in real work — and will have formed real opinions about what stuck, what didn't, and where the friction is.

This session is built around two things the team flagged as unresolved after the core day: a tendency to build features through long, ad-hoc prompting chains rather than a single reviewable spec, and a lack of a lightweight way to evaluate AI output beyond eyeballing it. Both are addressed by working on one real story pulled from the team's own backlog, not a synthetic exercise.

---

## Who it is for

The same Rabbies development team from the core day. This session assumes everything from Day 1 — the 3Cs, context as code, `#file:` referencing, and PRD-driven development using the `create-prd` / `generate-tasks` / `process-task-list` skills introduced in Module 2. It does not re-teach any of that; it builds directly on it.

---

## Learning outcomes

By the end of this session, participants will be able to:

- Articulate what has and hasn't stuck since the core day, and why
- Explain the difference between an ad-hoc prompting chain and spec-led development, and where each is appropriate
- Draft and use a team-owned spec template, adapted from the `create-prd` pattern rather than borrowed wholesale
- Take a real story from planning through a generated implementation, checked against its acceptance criteria
- Explain where manual review of AI output stops being sufficient on its own
- Define a lightweight eval as a small set of real cases with a clear pass/fail definition, and write one for a real feature
- Leave with one or two concrete things to trial before the next check-in, with named ownership

---

## Session structure

Approx. 3.5 hours, including one break.

| Section | Time |
|---|---|
| Quick retro | 20 min |
| Reframe: chain vs. spec-led | 30 min |
| *Break* | 15 min |
| Mob-write a real spec, end to end | 90 min |
| Evaluating AI output | 35 min |
| Open Q&A | 15 min |
| Close: what to trial next | 15 min |

---

## Repository structure

```
module-01-retro-and-reframe/   Quick retro + chain vs. spec-led reframe
module-02-mob-spec-workshop/   The bulk of the session — mob-writing a real spec end to end
module-03-evaluating-ai-output/ Lightweight evals for AI-generated output
module-04-qa-and-close/        Open Q&A and trial commitments
```

Each module contains:

- `slides.md` — Presenterm-compatible slide deck
- `README.md` — module overview, objectives, and timing
- `TEACHING_NOTES.md` — facilitator notes, including guidance specific to running a working session rather than teaching new content
- `exercises/` — templates used live in the room (retro capture sheet, spec template, eval worksheet, trial-commitment tracker)
- `demos/` — worked examples (where applicable)

This session does not use `demo-codebase/` as its main working material — the point of Module 2 and Module 3 is to work on a real, current story from the team's own backlog. Where a worked or illustrative example is needed (Module 1's chain-vs-spec comparison, Module 3's eval walkthrough), it draws on `../initial-delivery/demo-codebase/` and the `prd-task-assignment` PRD already produced in `../initial-delivery/tasks/` during the core day, so the illustration is grounded in something the team has already seen.

General facilitation conventions (teaching style, template sources) follow `../initial-delivery/general_teaching/`.

---

## Prerequisites for delegates

- Attendance at the core day (this session assumes that context)
- One real, upcoming story from the team's own backlog, agreed in advance with whoever is running the sprint/backlog, to use in Module 2
- The same tooling as the core day: VS Code with GitHub Copilot, or an approved agentic tool, and access to the team's actual codebase (not the demo codebase)
- A shared doc (or equivalent) open for retro capture and the trial-commitment tracker

## Facilitator preparation

Unlike the core day, this session cannot be fully scripted in advance — its value depends on being anchored in what the team actually experienced. Before delivery:

- Confirm the real backlog story for Module 2 is agreed and reasonably scoped (small enough to mob through a spec and a first generated pass in ~90 minutes)
- Have the `create-prd`, `generate-tasks`, and `process-task-list` skills and the `prd-task-assignment` example ready to reference
- Have a shared doc ready and pre-structured for the retro capture (Module 1) and the trial-commitment tracker (Module 4)

---

## Delivery

Delivered in-person or via Microsoft Teams, whichever suits the team on the day. This session is deliberately more workshop than lecture — most of the time is spent working on real material as a group, not watching slides. The facilitator's job is to keep the room moving and to make sure the session produces artefacts the team keeps: a first-cut spec template, a completed spec for a real story, and named commitments for what happens next.
