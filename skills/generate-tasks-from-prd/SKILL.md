---
name: generate-tasks-from-prd
description: Generate task list from PRD 
---
# Rule: Generating a Task List from a PRD

## Goal

To guide an AI assistant in creating a detailed, step-by-step task list in Markdown format based on an existing Product Requirements Document (PRD). The task list should guide a developer through implementation, and should make clear which work can be run in parallel versus which must happen in sequence. The output of this skill is consumed directly by `process-task-list`, so the Track structure and file-overlap notes produced here are load-bearing, not decorative — the implementer treats them as authoritative rather than re-deriving them.

## Output

- **Format:** Markdown (`.md`)
- **Location:** `/tasks/`
- **Filename:** `tasks-[prd-file-name].md` (e.g., `tasks-prd-user-profile-editing.md`)

## Process

1. **Receive PRD Reference:** The user points the AI to a specific PRD file.
2. **Check for an existing task list early:** Before doing any analysis, check whether `/tasks/tasks-[prd-file-name].md` already exists. If it does, confirm with the user whether to overwrite it, version it, or treat this as a refresh that should preserve already-checked-off progress — don't silently clobber a task list that's mid-implementation.
3. **Analyze PRD:** Read and analyze the functional requirements, user stories, and other sections of the specified PRD.
4. **Assess Current State:** Review the existing codebase to understand existing infrastructure, architectural patterns and conventions. Identify any existing components or features already relevant to the PRD requirements, and identify existing related files, components, and utilities that can be leveraged or need modification.
5. **Phase 1: Generate Parent Tasks:** Based on the PRD analysis and current state assessment, create the file and generate the main, high-level tasks required to implement the feature. Use your judgement on how many high-level tasks to use — likely around 5. Present these tasks to the user in the specified format (without sub-tasks yet). Inform the user: "I have generated the high-level tasks based on the PRD. Ready to generate the sub-tasks? Respond with 'go' (or 'yes'/'y') to proceed."
6. **Wait for Confirmation:** Pause and wait for the user's approval.
7. **Phase 2: Generate Sub-Tasks, Map Dependencies, and Identify Parallel Tracks (one combined pass):** Once the user confirms, break down each parent task into smaller, actionable sub-tasks necessary to complete it, ensuring sub-tasks logically follow from the parent task and cover the implementation details implied by the PRD. As part of the same pass — not a separate later step — determine what each parent task and sub-task depends on (shared files, data models, APIs, or earlier steps that must land first), and group tasks with no dependency on one another, and no shared files, into named parallel tracks (e.g., "Track A: Backend API", "Track B: Frontend components", "Track C: Test scaffolding") that could be picked up simultaneously by different developers or agents. Keep genuinely sequential work (anything blocked on another task's output) as ordered steps within a track, and call out explicitly where tracks must sync back up (a shared interface, a merge point, an integration task that needs multiple tracks finished first).
8. **Identify Relevant Files:** Based on the tasks and PRD, identify potential files that will need to be created or modified. List these under the `Relevant Files` section, including corresponding test files if applicable. **Explicitly flag any file touched by more than one track** — this is the signal the implementer uses to override track independence, so it must be accurate and not skipped even when the overlap seems minor.
9. **Generate Final Output:** Combine the parent tasks (organized into parallel tracks plus any sequential/integration tasks), sub-tasks, relevant files, and notes into the final Markdown structure.
10. **Save Task List:** Save the generated document in the `/tasks/` directory with the filename `tasks-[prd-file-name].md`, where `[prd-file-name]` matches the base name of the input PRD file (e.g., if the input was `prd-user-profile-editing.md`, the output is `tasks-prd-user-profile-editing.md`).

## Output Format

The generated task list _must_ follow this structure. Parent tasks are grouped under their parallel track (or under "Sequential / Integration" for anything that can't be parallelized or that syncs tracks together):

```markdown
## Relevant Files

- `path/to/potential/file1.ts` - Brief description of why this file is relevant (e.g., Contains the main component for this feature).
- `path/to/file1.test.ts` - Unit tests for `file1.ts`.
- `path/to/another/file.tsx` - Brief description (e.g., API route handler for data submission). **Touched by Track A and Track B** — treat sub-tasks in both tracks that touch this file as sequential relative to each other, not independent.
- `path/to/another/file.test.tsx` - Unit tests for `another/file.tsx`.
- `lib/utils/helpers.ts` - Brief description (e.g., Utility functions needed for calculations).
- `lib/utils/helpers.test.ts` - Unit tests for `helpers.ts`.

### Notes

- Unit tests should typically be placed alongside the code files they are testing (e.g., `MyComponent.tsx` and `MyComponent.test.tsx` in the same directory).
- Use `npx jest [optional/path/to/test/file]` to run tests. Running without a path executes all tests found by the Jest configuration.

## Parallel Work Breakdown

- **Track A: [Name, e.g. Backend API]** — can start immediately, no dependency on other tracks.
- **Track B: [Name, e.g. Frontend UI]** — can start immediately; depends on Track A's interface being agreed but not built.
- **Sequential / Integration** — tasks that must wait on two or more tracks finishing (e.g., wiring the frontend to the real API, end-to-end tests). Name exactly which tracks each integration task depends on.

## Tasks

### Track A: [Name]

- [ ] 1.0 Parent Task Title
  - [ ] 1.1 [Sub-task description 1.1]
  - [ ] 1.2 [Sub-task description 1.2]

### Track B: [Name]

- [ ] 2.0 Parent Task Title
  - [ ] 2.1 [Sub-task description 2.1]

### Sequential / Integration

- [ ] 3.0 Parent Task Title (depends on Track A + Track B; may not require sub-tasks if purely structural or configuration)
```

## Interaction Model

The process explicitly requires a pause after generating parent tasks to get user confirmation ("go"/"yes"/"y") before proceeding to generate the detailed sub-tasks. This ensures the high-level plan aligns with user expectations before diving into details. The parallel-track grouping happens as part of that sub-task generation pass (step 7), together with the detailed tasks and dependency mapping — not as a separate later pass — so tracks and file-overlap flags are internally consistent with the sub-tasks they describe.

## Target Audience

Assume the primary reader of the task list is a **junior developer** (or a team of them, working in parallel) who will implement the feature with awareness of the existing codebase context.
