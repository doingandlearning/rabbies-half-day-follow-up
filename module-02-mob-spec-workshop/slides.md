# Mob-Write a Real Spec, End to End

**Half-Day Follow-Up — AI-Assisted Development for Laravel Teams**

<!-- end_slide -->

## What we're doing for the next 90 minutes

One real story from your backlog. As a group, start to finish:

<!-- incremental_lists: true -->
1. Mob-write the spec together
2. Draft your own first-cut spec template as you go
3. Generate output from the spec
4. Check it against the story's actual acceptance criteria
5. Agree explicitly where a human sign-off stays non-negotiable


<!-- pause -->

This is not a demo. This is the real thing, on real work.

<!-- end_slide -->

## Mobbing, briefly

One person drives — types, runs `create-prd`, generates output. Everyone else navigates: proposes requirements, catches gaps, disagrees out loud.

<!-- pause -->

**Rotate the driver** partway through if the group is larger than about five — keeps everyone engaged, and different drivers catch different things.

**The facilitator's job**: keep momentum, not perfection. A slightly imperfect spec that gets finished beats a perfect spec that eats the whole session.

<!-- end_slide -->

## The story

*[Facilitator: state the agreed story here before the session, and paste or link its current backlog description/acceptance criteria into the shared doc now.]*

<!-- pause -->

Before we start writing: does everyone in the room understand what this story is asking for, in plain terms? If not, ask now.

<!-- end_slide -->

## Step 1: the planning conversation

Use `create-prd` as the starting point — same as Module 2 of the core day — but this time, answer its clarifying questions as a group, out loud, and capture the disagreements, not just the answers.

<!-- pause -->

Watch for:

- Places where two people in the room have different assumptions about what the story means
- Edge cases someone raises that weren't in the original backlog description
- Anything that turns out to be genuinely unclear — that's an **open question**, not something to quietly resolve by guessing

<!-- end_slide -->

## Step 2: tighten it, and draft your own template

The `create-prd` output is a starting point, not the final format. As you tighten the spec for this story, start pulling out **what your team's version of this template should look like**.

<!-- pause -->

Questions to answer as a group:

- Which sections from `create-prd` do you actually use, and which are dead weight for your stories?
- What's missing — is there a section specific to how your team works that isn't in the generic template?
- What do you want to rename, so it matches how the team already talks about specs?

<!-- pause -->

**This produces two things**: the finished spec for today's story, and a first-cut team template you can reuse next time.

<!-- end_slide -->

## Step 3: generate output

Run the tightened spec through `generate-tasks`, then implement against the task list — live, as far as time allows.

<!-- pause -->

Don't aim to finish the feature. Aim to get far enough to see whether the spec was good enough to drive real implementation — that's the actual test.

<!-- end_slide -->

## Step 4: check against acceptance criteria

Go back to the story's real acceptance criteria — not the spec you just wrote, the original ones from the backlog.

<!-- pause -->

For each one, ask: does the generated output actually satisfy this? Not "does it look plausible" — does it *do the specific thing the acceptance criterion describes*.

<!-- pause -->

**If something's missing**, that's not a failure — it's exactly the kind of gap this session exists to catch, and it's useful input for Module 3's evals discussion.

<!-- end_slide -->

## Step 5: where sign-off stays non-negotiable

Regardless of how good the tooling gets — say this out loud as a team, now, not as an assumption everyone silently makes differently.

<!-- pause -->

Prompts for the discussion:

- Is there anything in this story a human must review line by line, no matter what — auth, payments, data deletion, anything touching another team's system?
- Is there a difference between "a human reviewed the diff" and "a human verified the behaviour"? Which does this story need?
- Who is that human, specifically — not "someone," a name or a role?

<!-- pause -->

Write the answer down. It goes in the trial-commitment tracker in Module 4.

<!-- end_slide -->

## Bridge to Module 3

**What we've built**: a real spec for a real story, a first-cut team template, generated output checked against real acceptance criteria, and an explicit sign-off agreement.

**What's next**: manual review — what we just did in Step 4 — doesn't scale forever. Evaluating AI output properly is next.

<!-- end_slide -->

# Back to work

*Mob-Write a Real Spec — Half-Day Follow-Up*
