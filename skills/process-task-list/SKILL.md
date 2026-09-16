---
name: process-task-list
description: Process task
---
# Task List Management

Guidelines for implementing a markdown task list produced by `generate-tasks-from-prd`, tracking progress against a PRD.

## Read the whole task list before starting anything

Before implementing a single sub-task, read the entire file, including `Relevant Files` and `Parallel Work Breakdown` if present:

- If the file has named Tracks (e.g. "Track A: Backend API", "Track B: Frontend UI") and a "Sequential / Integration" section, treat the **track** — not the individual parent task — as the primary unit of parallelism. Tracks marked as having no dependency on one another are candidates for simultaneous work; the Sequential/Integration section is gated on the tracks it names as prerequisites.
- If the file has no track structure — just parent tasks and sub-tasks — use the "Fallback: untracked task lists" model below instead.
- Check `Relevant Files` for any note that a file is touched by more than one track (or by more than one sub-task within a track). **Treat that as an authoritative override**: anything sharing a flagged file is NOT independent, regardless of how it was grouped, and must be treated as sequential relative to the other thing touching that file. Do not re-derive independence from scratch — `generate-tasks-from-prd` already did this analysis; the job here is to honor it, not repeat or contradict it.

## Implementing tracked task lists

1. **Identify the currently-runnable set of tracks:** tracks with no unmet dependency on another track, and no unresolved file overlap (per `Relevant Files`) with another currently-runnable track.
2. **Dispatch each runnable track as a unit**, via the Agent tool, in a single batch of parallel calls — one agent per track. Each agent's prompt includes the track's full set of parent tasks and sub-tasks, the relevant file paths, and the same sub-task-level procedure described below, so a track with several parent tasks isn't serialized down to one sub-task at a time inside that agent.
3. **Wait for the entire batch of tracks to complete** before proceeding — don't act on partial results, and don't start a track that depends on one still in flight.
4. Once all currently-runnable tracks are done: mark their parent tasks and sub-tasks `[x]`, update `Relevant Files`, run the completion protocol below, and **pause once for user go-ahead** (accept "go", "yes", or "y") before starting the next batch of tracks or the Sequential/Integration section — not after every individual parent task within a track.
5. **Sequential / Integration tasks** run only after every track they depend on has completed and been approved. Implement these yourself (they are explicitly the sync points and are usually not parallelizable), using the same completion protocol.
6. If any track, parent task, or sub-task fails or its self-check doesn't pass, let the rest of the current batch finish, then stop and surface the failure at the checkpoint instead of silently proceeding to the next batch.

## Fallback: untracked task lists

When the task list has no Track structure, use the parent-task-level model:

1. **One parent task at a time** for approval purposes: don't start the next parent task until the user has approved the current one.
2. Before starting a parent task's sub-tasks, classify each as independent (own files, no shared state, doesn't depend on a sibling's output) or sequential (depends on a prior sub-task within the same parent). Cross-check independence against `Relevant Files` the same way as in the tracked model.
3. Dispatch all independent sub-tasks for the current parent as one batch of parallel agents; run sequential sub-tasks yourself, inline, in order.
4. Wait for the full batch before proceeding.
5. Run the completion protocol, then pause once per parent task (not per sub-task) for user go-ahead ("go" / "yes" / "y").

## Completion protocol (runs once per track-batch, or once per parent task under the fallback model — never once per sub-task)

1. Mark each finished sub-task `[x]` as its result comes in.
2. If any sub-task, track, or agent failed or its check didn't pass, stop here and surface it — don't run tests or commit against a known-broken piece of work.
3. **Run the full test suite** (`pytest`, `npm test`, `bin/rails test`, etc.).
4. **Only if all tests pass**: stage changes (`git add .`).
5. **Clean up**: remove any temporary files and temporary code before committing.
6. **Commit**: descriptive, conventional-commit-format message summarizing what was completed, as a single-line command using `-m` flags:

   ```
   git commit -m "feat: add payment validation logic" -m "- Validates card type and expiry" -m "- Adds unit tests for edge cases" -m "Related to T123 in PRD"
   ```
7. Mark the relevant parent task(s) `[x]`.

## Task List Maintenance

1. Update the task list file as results come in — sub-tasks, parent tasks, and (where present) track status — without pausing for approval between individual sub-tasks or between parent tasks within the same approved batch.
2. Add newly discovered tasks, tagging them with a track (or independent/sequential) where relevant, and re-checking `Relevant Files` for new overlaps introduced by the new task.
3. Keep `Relevant Files` accurate, including files touched by dispatched agents, and keep cross-track/cross-sub-task overlap notes current as new files are touched.

## AI Instructions

1. Read the whole task list, including `Relevant Files` and any `Parallel Work Breakdown`, before starting.
2. If tracks are present, use the tracked model: dispatch all currently-runnable tracks in one parallel batch, wait for the batch, checkpoint once, repeat until only Sequential/Integration tasks remain, then implement those and checkpoint again.
3. If no tracks are present, use the fallback model: one parent task at a time, with its independent sub-tasks dispatched as one parallel batch.
4. In either model, treat a `Relevant Files` file-overlap note as overriding any independence assumption, rather than re-deriving dependencies from scratch.
5. Run the completion protocol once per batch or parent task, not once per sub-task.
6. If a sub-task, track, or agent fails, let its batch finish, then stop and report before proceeding — don't silently continue past a known failure.
7. Accept "go", "yes", or "y" as the user's approval to proceed, consistent with the confirmation language used in `create-prd` and `generate-tasks-from-prd`.
