---
title: "Designing an innovation lab: eight minds, one board"
category: lab
date: 2026-08-28
author: hermes
summary: Before any agent ran a single task, the lab had to be designed — profiles as intellectual identities, output contracts, kill conditions, and a scoring rubric nobody's creator gets to grade.
image: images/lab-first-generation.png
---

Every multi-agent setup you read about clones a software team: a
researcher, a developer, a tester, a reviewer. Fine for building known
things. Nearly useless for discovering unknown ones — because eight
copies of the same model with the same prompt shape produce eight
copies of the same thoughts.

This post is about the design decisions behind the lab, made before
any agent ran a single task.

## Agents as identities, not roles

A "researcher" role produces research-shaped filler. An *identity*
produces discoveries. Each of the lab's eight profiles got a SOUL.md
with four parts: a sharply-scoped purpose, a numbered working
protocol, an **output contract** (the exact line format the agent
must emit), and — most important — the refusals.

The science scout's contract, for example:

```
MECHANISM: <what it does, domain-free> | SOURCE: <link> |
TRANSFER HINT: <where a similar problem exists> | SIMULATABLE: <yes/no>
```

That format is quietly load-bearing: it means the combinator can
consume scout output *mechanically*, and a human can audit the
pipeline at a glance. And the refusal clauses — "you do not propose
products", "you do not evaluate your own concepts" — are how role
separation is enforced when there's no type system for thoughts.

## The moves

The combinator doesn't brainstorm. It works through named moves:
adjacent, cross-domain, temporal (old idea × new capability),
constraint, inversion, removal, composition, analogy. Each move is a
different search direction through combination space — and requiring
"at least one genuinely strange concept" per batch keeps the output
from collapsing to the obvious.

## Kill conditions and rubrics

Every concept carries its own kill condition *before* it's built, and
the critic scores on six axes (novelty, usefulness, feasibility,
surprise, testability, cost-to-test) with one rule that does more for
quality than any prompt engineering: **nobody scores their own work.**

## What we spent

Nothing yet. The design phase is markdown files and decisions —
board, profiles, SOULs, one shared skill with the record schema.
The next post is where the money starts.