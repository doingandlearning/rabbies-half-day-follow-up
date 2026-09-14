# Demo: Chain vs. Spec-Led, Side by Side

**Purpose:** Make the reframe concrete by showing the same feature built two ways — as an ad-hoc prompting chain, and via the `create-prd` → `generate-tasks` flow — so the comparison table on the slides isn't abstract.

**Materials:** This demo, plus `../../initial-delivery/tasks/prd-task-assignment.md` and `../../initial-delivery/tasks/tasks-prd-task-assignment.md` (the real spec-led artefact from the core day).

**Setup:** No live coding needed — this is a walkthrough of two approaches to the same feature, one narrated, one shown as a real file.

---

## The feature

Add assignee support to tasks: a single assignee per task, visible in task views, changes audited, the newly assigned user notified. This is the same feature covered by the `prd-task-assignment.md` PRD from the core day.

---

## Version 1: the chain

Narrate this as if thinking out loud in a chat interface, prompt by prompt:

```
1. "Add an assignee field to tasks"
   → produces a migration and a model change, but no validation,
     no UI, no notification — because none of that was asked for

2. "actually make it nullable, not every task has one"
   → a follow-up fix to the migration

3. "now show it on the task list"
   → a view change, made without knowing whether the list view
     is the same one used elsewhere for other task metadata

4. "the controller needs to validate the assignee exists"
   → validation bolted on after the fact, in whatever request
     class happened to be open

5. "add a test for that"
   → a test for the validation just added — but nothing covering
     the earlier steps, because nobody thought to ask at the time

6. "oh, and log who changed it"
   → an audit log added last, as an afterthought, with no
     agreed shape for what the log should capture
```

**Ask the room:** at what point in this chain would you have caught that reassignment (not just first assignment) needed to notify the new user too? In the real chain, this kind of gap is common — it surfaces only if someone happens to think of it.

---

## Version 2: spec-led

Open `../../initial-delivery/tasks/prd-task-assignment.md` and walk through it section by section:

- **Goals** — stated up front, not discovered mid-implementation
- **Functional requirements** — numbered, explicit, including the "must notify on *reassignment*, not just first assignment" requirement (requirement 12) — the exact gap the chain missed
- **Non-goals** — explicitly ruling out automatic assignment, workload balancing, multiple assignees — scope decisions made once, visibly, instead of implicitly through what nobody happened to ask for
- **Open questions** — genuine unknowns flagged for a human decision, rather than silently resolved by whatever the model assumed

Then open `tasks-prd-task-assignment.md` and show the generated task breakdown — implementation proceeds against this list, one checkbox at a time, each one traceable back to a numbered requirement.

---

## The comparison, made concrete

| | Chain | Spec-led |
|---|---|---|
| Reassignment notification | Missed until someone thought of it | Requirement 12, stated up front |
| Audit trail shape | Improvised at the end | Requirement 10, specified before code |
| Review point | After code exists, scattered across 6 replies | Before code exists, one document |
| What persists after | A chat transcript nobody rereads | `prd-task-assignment.md` + task list, both in the repo |

---

## Discussion prompt

**Ask the room:** "Think of a feature you built in the last four weeks using a chain like Version 1. What's the equivalent of the reassignment-notification gap — the thing you only caught because you happened to think of it, not because anything forced the question?"

Capture answers — they're useful going into Module 2's mob-write session.
