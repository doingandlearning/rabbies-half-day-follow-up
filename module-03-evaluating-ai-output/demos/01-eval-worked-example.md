# Demo: Worked Eval Example

**Purpose:** Show the given/when/then/pass-fail eval format applied to real requirements, so the group can immediately do the same for Module 2's actual story.

**Materials:** `../../initial-delivery/tasks/prd-task-assignment.md` (fallback/illustration example — prefer Module 2's real story if it's usable; see note below).

**Note for facilitators:** This demo uses the task-assignment feature from the core day because it's a real spec the team has already seen, not a synthetic one. If Module 2 produced a clean, real spec for the day's actual chosen story, walk through that instead — the format below still applies directly. Use this file as the illustration if Module 2's artefact isn't in a state to reuse (e.g. left mid-implementation), or to introduce the format before turning to the team's own material.

---

## Starting point: the requirements

From `prd-task-assignment.md`:

> 9. The system must record assignment and reassignment events in an audit trail.
> 10. The audit trail must capture at least the task, the new assignee, the previous assignee when applicable, the user who made the change, and the timestamp of the change.
> 12. The system must notify the newly assigned user when a task is reassigned to them.
> 13. The system must preserve the current assignee if a task is edited without changing the assignment field.

These four are good eval candidates because they're the ones a quick glance at generated code is least likely to verify reliably — audit trail completeness, reassignment vs. first-assignment notification, and a "nothing happened" case that's easy to silently break.

---

## Turning requirements into eval cases

**Case 1 — reassignment notifies the new assignee, not the old one**

```
Given: a task currently assigned to User A
When: it is reassigned to User B
Then: User B receives exactly one notification; User A receives none
Pass/fail: notification recipients are exactly {User B} — yes/no
```

**Case 2 — audit trail captures a reassignment correctly**

```
Given: a task assigned to User A
When: it is reassigned to User B by User C
Then: an audit log entry exists recording task, previous assignee (A),
      new assignee (B), changed-by (C), and a timestamp
Pass/fail: all five fields present and correct — yes/no
```

**Case 3 — editing an unrelated field preserves the assignee**

```
Given: a task assigned to User A
When: the task's title is edited, with the assignment field
      omitted from the update payload
Then: the task is still assigned to User A afterward
Pass/fail: assignee unchanged — yes/no
```

**Case 4 — first assignment (not reassignment) still notifies**

```
Given: a task with no assignee
When: it is assigned to User A for the first time
Then: User A receives a notification
Pass/fail: notification sent to User A — yes/no
```

---

## Why these four, not more

Case 3 exists because it's the kind of "nothing should happen" case that's easy to break silently — a naive implementation of assignment logic might reset the assignee on any update. Case 1 and Case 4 together exist because the original PRD makes first-assignment and reassignment notification two separate requirements (11 and 12) — a case set that only tested one would miss a real gap, exactly the kind Module 2's chain-based build was shown missing.

**The lesson to draw out**: four focused cases, each earning its place by mapping to a specific requirement or a specific way the requirement could be silently violated, beats a long list of generic cases that don't map to anything specific.

---

## Connecting back to Pest

Cases 1, 2, and 3 above are directly expressible as Pest feature tests — nothing about them requires anything beyond the team's existing testing discipline. That's the point to make explicit: these aren't a new artefact type, they're tests, framed here specifically as checks against the spec's requirements rather than general-purpose regression tests. If they don't already exist in the suite, writing them closes a real gap either way.
