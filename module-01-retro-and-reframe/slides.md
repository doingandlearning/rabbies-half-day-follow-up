# Quick Retro & Reframe

**Half-Day Follow-Up — AI-Assisted Development for Laravel Teams**

<!-- end_slide -->

## Where we left off

The core day covered: shared vocabulary and how LLMs work, prompting principles, Copilot's specific surfaces, AI across the full SDLC, team conventions, security guardrails, and a first look at agents and MCP.

<!-- pause -->

That was four weeks ago. Since then, you've been using this in real work — on real deadlines, in your real codebase.

**Today**: what actually stuck, what didn't, and two things worth going deeper on — spec-led development and evaluating AI output.

<!-- end_slide -->

## Quick retro

Not a long discussion. A shared-doc capture, fast, that sets the agenda for the rest of the session.

<!-- pause -->

Three columns, filled in together:

- **What's actually been used** — which habits from the core day stuck
- **What hasn't** — what got tried once and dropped, or never tried
- **Where the friction showed up** — prompting, review, trust in output, consistency across the team

<!-- pause -->

**Round the room or shared doc** — whichever is faster. Capture, don't debate yet.

<!-- end_slide -->

## What tends to stick

From similar teams, four weeks on:

<!-- incremental_lists: true -->
- Inline signal techniques (naming, comment-first) — usually sticks, it's low-friction
- `/explain`, `/fix`, `/tests` slash commands — usually sticks
- The 3Cs as a conscious checklist — often fades under deadline pressure
- PRD-driven development for anything beyond a small change — mixed: powerful when used, often skipped because it feels like overhead


<!-- pause -->

That last one is today's focus.

<!-- end_slide -->

## The pattern behind the friction

If the retro surfaces "we know we should write a spec, but under time pressure we just went back and forth with Copilot until something worked" — that's not a discipline failure. It's a real trade-off the team hasn't resolved yet.

<!-- pause -->

**Today's job**: make the spec-led path faster and more natural to reach for, not just theoretically better.

<!-- end_slide -->

## Reframe: what a prompting chain actually looks like

A chain: a sequence of ad-hoc prompts, each responding to the output of the last, gradually shaping a feature.

<!-- pause -->

```
"Add an assignee field to tasks"
  → "actually make it nullable, not every task has one"
  → "now show it on the task list"
  → "the controller needs to validate the assignee exists"
  → "add a test for that"
  → "oh, and log who changed it"
```

<!-- pause -->

Each step is individually reasonable. The feature that comes out the other end was never written down anywhere as a whole.

<!-- end_slide -->

## What's wrong with the chain — not that it's slow

The chain often *works*. That's not the problem.

<!-- incremental_lists: true -->
- Nothing is reviewable until code already exists
- Nobody else can see the requirements without reading the whole conversation
- It doesn't survive the session — the next similar feature starts from zero again
- Edge cases get added only when someone happens to think of them mid-chain
- "Done" is whenever the chain stops, not a criterion anyone agreed in advance


<!-- end_slide -->

## The spec-led alternative

One planning step, before generation starts, that produces a reviewable artefact: a spec.

<!-- pause -->

This isn't new — it's the `create-prd` → `generate-tasks` → `process-task-list` flow from Module 2 of the core day. Today's reframe is about when and why to actually reach for it.

<!-- pause -->

```
create-prd: "Add assignee support to tasks"
  → clarifying questions asked up front
  → prd-assign-task.md produced — goals, user stories,
    functional requirements, non-goals, open questions
  → generate-tasks: broken into a reviewable task list
  → implementation follows the task list, one item at a time
```

<!-- end_slide -->

## Side by side

See `demos/01-chain-vs-spec-led.md` for the full worked comparison on the task-assignment example.

| | Chain | Spec-led |
|---|---|---|
| Reviewable before code exists | ❌ | ✅ |
| Requirements visible in one place | ❌ | ✅ |
| Reusable next time | ❌ | ✅ — the PRD and task list persist |
| Where review effort goes | Scattered across every reply | Concentrated on the spec, once |
| Edge cases | Found ad hoc, mid-chain | Asked for explicitly, up front |

<!-- pause -->

**One sentence takeaway**: the chain moves the thinking into the code; the spec moves it before the code.

<!-- end_slide -->

## What changes, honestly

<!-- incremental_lists: true -->
- **What's checkable before code exists**: the spec's functional requirements and non-goals — anyone can review these without reading code
- **What's reusable across sessions**: the PRD file and the task list — they're artefacts, not a conversation that scrolls away
- **Where review effort moves**: from "does this generated code do the right thing?" (hard, after the fact) to "is this spec right?" (easier, before generation)


<!-- pause -->

**What doesn't change**: for a genuinely tiny change — a one-line fix, a typo, a config tweak — the chain is still fine. Spec-led is for anything with more than one reasonable interpretation.

<!-- end_slide -->

## Bridge to Module 2

**What we've done**: captured what's actually stuck since the core day, and reframed why spec-led development earns its overhead for anything non-trivial.

**What's next**: stop talking about it. Mob-write a real spec, end to end, for a story pulled from your own backlog — right now.

<!-- end_slide -->

# Questions before the break?

*Quick Retro & Reframe — Half-Day Follow-Up*
