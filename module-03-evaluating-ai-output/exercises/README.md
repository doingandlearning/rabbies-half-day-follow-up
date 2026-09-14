# Module 3 — Eval Worksheet

**For the group, using Module 2's real story.** Write 3-4 eval cases for the spec you just built. Prioritise requirements that felt hardest to verify by reading the generated code in Module 2's Step 5 — those are exactly where a lightweight eval earns its keep.

---

## Format

```markdown
## Eval case: [short name]

**Requirement it checks:** [which requirement from today's spec, quoted or numbered]

**Given:** [starting state]
**When:** [the action taken]
**Then:** [the expected outcome]
**Pass/fail:** [the single specific thing that determines yes/no — not "looks right"]
```

---

## How to pick which requirements to cover

Don't try to cover everything. Ask, for each requirement in today's spec:

- Would a quick read of the generated code reliably tell us if this is satisfied? If yes, you probably don't need a dedicated eval case — a straightforward Pest test covers it the same way it always would.
- Is this the kind of requirement where two runs of generation might produce subtly different, both-plausible-looking results? If yes, that's a strong eval candidate.
- Is there a "nothing should happen" case nearby — an update that shouldn't trigger a side effect — that's easy to silently break? Those are consistently worth a case (see Case 3 in `demos/01-eval-worked-example.md`).

Aim for 3-4 cases. If you're struggling to find that many, that's fine — it may mean today's spec is well-covered by existing test discipline already, which is a good outcome, not a gap in the exercise.

---

## What to do with these afterward

- If a case is straightforwardly expressible as a Pest test and doesn't already exist, write it — it closes a real gap in the suite either way
- If a case is genuinely about output quality or consistency rather than a single deterministic assertion, keep it as a standing eval case to re-run whenever this feature's AI-assisted generation is touched again
- Save the finished set alongside today's spec, not in a separate, easily-forgotten location
