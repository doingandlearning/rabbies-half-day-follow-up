---
name: create-prd
description: Generate a Product Requirements Document (PRD) in Markdown format based on an initial feature prompt, after gathering clarifying answers from the user
---

# Rule: Generating a Product Requirements Document (PRD)

## Goal

To guide an AI assistant in creating a detailed Product Requirements Document (PRD) in Markdown format, based on an initial user prompt. The PRD should be clear, actionable, and suitable for a junior developer to understand and implement the feature.

## Process

The steps below are grouped by dependency. Steps in the same phase can be done in any order (or, where noted, batched into parallel tool calls); each phase must fully complete before the next one starts.

### Phase 1 — Sequential (blocking on the user)

1. **Receive Initial Prompt:** The user provides a brief description or request for a new feature or functionality.
2. **Check for an existing file early:** Before asking anything else, check whether `/tasks/prd-[feature-name].md` already exists. If it does, confirm with the user whether to overwrite, version (`-v2`), or rename. Resolve this now — before any drafting effort is spent — not after the document has already been assembled.
3. **Ask Clarifying Questions:** Ask clarifying questions to gather sufficient detail — the "what" and "why," not the "how." Present options as letter/number lists so the user can respond with quick selections. **Do not proceed to Phase 2 until the user has answered** — the rest of the PRD depends on these answers, so this cannot be parallelized or skipped.

### Phase 2 — Parallelizable (all depend on Phase 1, not on each other)

Once clarifying answers are in hand, the individual PRD sections are independent of one another and can be drafted in any order. If any section requires research (e.g. checking the existing codebase for related functionality, looking up prior art, checking design system conventions), batch those lookups into a single set of concurrent tool calls rather than issuing them one at a time. This is ordinary parallel tool use by the same assistant, not separate agents — these sections aren't substantial enough on their own to warrant spinning up subagents.

- Introduction/Overview & Goals
- User Stories
- Functional Requirements
- Non-Goals (Out of Scope)
- Design Considerations (optional — may require a repo/docs lookup)
- Technical Considerations (optional — may require a repo/docs lookup)
- Success Metrics

### Phase 3 — Sequential (assembly and output)

4. **Assemble the PRD:** Combine the Phase 2 sections into the structure defined below, in order, and add the **Open Questions** section (this depends on having seen all other sections, so it comes last).
5. **Save PRD:** Save the generated document as `prd-[feature-name].md` inside the `/tasks` directory, at the path already confirmed in step 2.
6. **Summarize for the user:** Briefly state what was generated and where it was saved.

## Clarifying Questions (Examples)

Adapt the questions to the prompt; these are common areas to explore:

- **Problem/Goal:** "What problem does this feature solve for the user?" or "What is the main goal we want to achieve with this feature?"
- **Target User:** "Who is the primary user of this feature?"
- **Core Functionality:** "Can you describe the key actions a user should be able to perform with this feature?"
- **User Stories:** "Could you provide a few user stories? (e.g., As a [type of user], I want to [perform an action] so that [benefit].)"
- **Acceptance Criteria:** "How will we know when this feature is successfully implemented? What are the key success criteria?"
- **Scope/Boundaries:** "Are there any specific things this feature _should not_ do (non-goals)?"
- **Data Requirements:** "What kind of data does this feature need to display or manipulate?"
- **Design/UI:** "Are there any existing design mockups or UI guidelines to follow?" or "Can you describe the desired look and feel?"
- **Edge Cases:** "Are there any potential edge cases or error conditions we should consider?"

## PRD Structure

The generated PRD should include the following sections, in this order:

1. **Introduction/Overview:** Briefly describe the feature and the problem it solves. State the goal.
2. **Goals:** List the specific, measurable objectives for this feature.
3. **User Stories:** Detail the user narratives describing feature usage and benefits.
4. **Functional Requirements:** List the specific functionalities the feature must have, numbered, in clear concise language (e.g., "The system must allow users to upload a profile picture.").
5. **Non-Goals (Out of Scope):** Clearly state what this feature will _not_ include to manage scope.
6. **Design Considerations (Optional):** Link to mockups, describe UI/UX requirements, or mention relevant components/styles if applicable.
7. **Technical Considerations (Optional):** Mention any known technical constraints, dependencies, or suggestions (e.g., "Should integrate with the existing Auth module").
8. **Success Metrics:** How will the success of this feature be measured? (e.g., "Increase user engagement by 10%", "Reduce support tickets related to X").
9. **Open Questions:** List any remaining questions or areas needing further clarification.

## Target Audience

Assume the primary reader is a **junior developer**. Requirements should be explicit, unambiguous, and avoid jargon where possible — enough detail for them to understand the feature's purpose and core logic without needing to ask follow-ups.

## Project notes (use only if available in the current environment)

- If a browser automation tool (e.g. browsermcp) is connected, it may be used to check existing UI/UX for context.
- If web search is available, use it to look up documentation when stuck on a technical or domain question.
- Neither is required for this skill to function — treat both as optional enrichment, not a dependency.

## Output

- **Format:** Markdown (`.md`)
- **Location:** `/tasks/`
- **Filename:** `prd-[feature-name].md`

## Downstream note

The next stage in this workflow (`generate-tasks-from-prd`) reads this file directly and re-derives file/dependency structure from the Design and Technical Considerations sections — keep those sections concrete (name actual files, modules, or endpoints where known) rather than purely descriptive, since that's what the task-list generator has to work from.

## Final instructions

1. Do NOT generate the PRD until clarifying questions have been asked **and answered** — this is the hard gate before any drafting begins.
2. Do NOT start implementing the feature described in the PRD.
3. Use the user's answers to the clarifying questions to inform every section — do not fall back on generic boilerplate where an answer was given.
4. Resolve the existing-file check (step 2) before drafting, not after — don't do the overwrite/rename/version conversation after the document is already written.
