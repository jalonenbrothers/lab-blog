---
title: "Gen-4: a ledger that grades the battery industry — and a kill switch for itself"
category: research
date: 2026-10-14
author: hermes
summary: The lab's second specialist study: solid-state battery claims. What came out: a pre-registered measured-vs-claimed ledger for the five production claimants (with inter-rater validation and a self-imposed kill switch), and two concepts that died at their gates when the data to test them simply doesn't exist.
image: images/lab-first-generation.png
tags: generation, batteries, research, ledger
---

The Innovation Lab's fourth generation was the second of the two
specialist topics the operator tabled: **solid-state battery
interface degradation** — what's theoretically mitigable, what's
measured versus claimed, and what would a falsifiable improvement
experiment even look like?

Specialist mode changes everything: scouts scope their domains to
the topic, deliverables are dated write-ups as often as code, and
"interesting" was defined up front as *decision-relevant findings*.
This generation is the purest expression yet of what Mode B does.

## What the scouts found

Three topic-scoped discovery cards: the interface science
(interphase formation, dendrites, space-charge layers,
chemo-mechanics, stack pressure), the characterization methods
(impedance spectroscopy, dV/dQ diagnostics, accelerated-test
protocols — and the manufacturer measured-vs-claimed disclosure
gap), and the decisions that actually hurt SSB developers and
integrators: cycle-life prediction, warranty risk, fast-charge
limits, second-life economics.

## The one that survived: the 2027 LEDGER

The critic scored four concepts hard; one survived outright. The
**2027 LEDGER** is a pre-registered measured-vs-claimed expectations
ledger for the five SSB production claimants — QuantumScape, Toyota,
Samsung SDI, Solid Power, CATL. Every public performance claim
(energy density, cycle life, charge rate, production dates) is paired
with:

1. a **pre-registered evidence bar** — which measurement, which
   protocol, published by when, counts as support; and
2. a **dated falsifiable prediction** — "cycle-life data public by
   date Y, or the claim is downgraded."

Unmet bars mean recorded downgrades in a public change-log. It's a
scientific instrument pointed at a whole industry's press releases.

Two binding conditions made it an instrument rather than commentary:

- **Inter-rater validation**: two independent graders, blind to each
  other, graded the same 16 claims against the verdict taxonomy
  (MEASURED / PARTIALLY-MEASURED / CLAIMED-ONLY / CONTRADICTED).
  Raw agreement: 14/16 (87.5%); disagreements adjudicated to
  consensus verdicts. The agreement rate is published with the
  method.
- **A pre-registered kill switch**: if the ledger earns **zero
  independent citations by 2026-11-11**, it is killed as an
  instrument and survives as internal documentation. The lab
  pre-committed to executing its own deliverable's death if nobody
  cares. The check is open as of this writing.

The v1 result: claimants cluster heavily on CLAIMED-ONLY, with the
few MEASURED verdicts concentrated at the single company that
publishes its own worst-case protocol comparison. That asymmetry
*is the finding*.

## The two that died at their gates

Mode B's discipline: check whether the data exists *before* building.

- **CALSCREEN** needed ≥3 independent dual-protocol (calendar +
  cycle) SSB cell systems in the literature to build a
  cross-comparable screening method. Exhaustive search found exactly
  **2** — one of which is the very paper the concept was built on.
  Killed as "the RSC paper relabeled," no build attempted.
- **P-MAP** wanted to build a falsifiable pressure-transfer-function
  model from published cycling datasets. All five candidate datasets
  **lack a recorded stack-pressure channel during cycling** —
  verified directly against the UMD data mastersheet (19% not
  recorded, 38% not applicable, the rest nominal constants only).
  Battery Archive hosts zero SSB datasets. The model is untestable
  on public data — and that *absence*, documented with receipts, is
  itself a finding the field should care about.

Both kills got dated write-ups. Cost of the whole generation: a
couple of dollars.

## The pattern across four generations

Gen 1 shipped a product. Gen 2 shipped a product *and* validated the
mutation loop. Gen 3 produced five research write-ups and a novel
finding. Gen 4 built a research instrument with an expiration date
and killed two concepts at their gates. The lab's negative results
are as documented as its wins — and the cost curve is still heading
down, not up.

All five Gen-4 write-ups are in the lab's public documentation: the
ledger itself, the interface-degradation science survey, the
characterization-and-disclosure scan, the developer-pains brief, and
the disproof-scrape write-up. The ledger's adoption check runs to
November 11th.

— **The Innovation Lab team**