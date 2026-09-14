# Module 2 — Mob-Write Materials

**Used live, as a group, in the room.** These aren't individual exercises — they're the working documents for the mob-write session. Set both up in the shared doc before the session starts.

---

## 1. First-cut team spec template

Starting scaffold — edit this live as a group during Step 2 (tighten the spec, draft the team template). It's deliberately close to `create-prd`'s structure, because that's the starting point everyone already knows from the core day — the point of this exercise is to adapt it, not replace it wholesale.

```markdown
# Spec: [Story Name]

## What this is and why
[1-2 sentences: the problem this solves, and for whom.]

## What "done" looks like
[The acceptance criteria, in the team's own words — copy from the backlog
story, then tighten as needed.]

## Requirements
[Numbered, one behaviour per line. Specific enough that "is this satisfied?"
has a yes/no answer once code exists.]
1.
2.
3.

## Explicitly out of scope
[What this story does NOT include — scope decisions made once, visibly.]
-

## Open questions
[Anything genuinely unresolved — flagged, not silently guessed.]
-

## Sign-off required from
[Named person or role, and what "sign-off" means for this story —
diff review, behaviour verification, or both.]
```

**As you go, ask the group:**
- Which of these sections did we actually use for real, and which felt like dead weight?
- Is there a section specific to how this team works that's missing?
- Do any of these headings need renaming to match how the team already talks about specs day to day?

Update the template live based on the answers. What comes out the other end is the team's actual first-cut template — save it somewhere the team will find it again (e.g. `.github/skills/` alongside `create-prd`, or wherever the team keeps shared conventions).

---

## 2. Sign-off decision worksheet

Used in Step 5. Fill this in as a group, in the shared doc, before moving to Module 3.

```markdown
## Sign-off agreement — [Story Name]

Regardless of tooling quality, the following require human sign-off:
-

For this story specifically:
- Who signs off:
- What "sign-off" means here (diff review / behaviour verification / both):
- What would make us skip this in future (if anything) — or is it truly non-negotiable:
```

This worksheet's output carries forward into Module 4's trial-commitment tracker — don't lose the doc between modules.
