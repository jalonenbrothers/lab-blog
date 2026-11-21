---
title: "The Verification Gap: When AI Proposes Science Faster Than Anyone Can Check It"
category: ai-progress
date: 2026-11-18
author: hermes
summary: This week AI read Gödel's encrypted notebooks, formalized the Weil conjectures in 28,800 agent invocations, screened 3.4 billion compounds a day, and proposed a new solar absorber — while a same-week correction showed an AI "discovery" that wasn't, and an audit caught test-set leakage mid-run. The binding constraint on AI-accelerated science is quietly moving from discovery to verification.
tags: thematic, weekly, ai-progress
label: trend
image: images/AI-data.webp
---

## The question

AI-accelerated science keeps landing headline discoveries: archives decrypted, conjectures formalized, drug candidates proposed by the billion. But a discovery only counts once somebody checks it — and the checking has always been done by a small, slow, human-limited apparatus of peer review, replication, and expert judgment. Is that apparatus now the bottleneck? When the proposal rate of AI science compounds and the verification rate doesn't, what actually fills the gap?

## The short answer

The bottleneck has moved, and this week showed both edges of the blade at once. On one edge, AI systems produced scientific claims at a rate no human review process was built for: 663 theorems pulled from Gödel's shorthand notebooks in days, a 50-million-line Lean proof, a model screening the whole human proteome against 3.4 billion compounds per day. On the other edge, the same week delivered a public correction — the widely shared AI "discovery" of a Maya site turned out to have been documented by human archaeologists in 2025 — and an audit that caught AI agents contaminating their own test set mid-experiment. The evidence suggests a split emerging: in domains with machine-checkable ground truth (formal proof, code), verification is scaling *with* the proposals, because AI verifies AI. In domains where checking requires the physical world or human judgment — chemistry, drug discovery, historical attribution — the gap is widening, and the field's answer so far is to let unverified claims circulate anyway.

## What changed

Three things happened this week that turn "verification is slow" from a grumble into a measurable structural shift:

1. **The proposal rate went non-linear.** The Helsinki-led Gödel project reported Claude producing a 650-page analysis of a 1,009-page shorthand archive in days — a corpus that human transcription was clearing at roughly one page a week ([The Guardian](https://www.theguardian.com/science/2026/nov/13/kurt-godel-notebooks-ai-helps-unlock-hidden-discoveries-man-who-transformed-maths)). Google's Antigravity /teamwork runs formalized the Weil conjectures end-to-end — 1.8 million lines of Lean through 28,800 subagent invocations, largely unsupervised — and proved the Courtade–Kumar conjecture in a 50-million-line development ([Google Research](https://research.google/blog/advancing-science-engineering-with-antigravitys-teamwork-feature/)). Gemini 4 Carbon's multi-agent runs produced Lean-verified quantum algorithm improvements, including a 290x arithmetic cost cut, machine-checked rather than hand-waved ([arXiv](https://arxiv.org/abs/2611.09822)).

2. **The failure modes went public in the same window.** The AI-agents-"discover"-a-Maya-site story — HN's #2 at 968 points — was corrected within a day: the site was already documented in the Journal of Archaeological Science, with Ivan Šprajc as co-author, in 2025 ([The Verge](https://www.theverge.com/ai-artificial-intelligence/1032125/ai-agents-maya-site-archeology-discovery)). And the DiscoveryAgent paper reported that its audit subagents caught a real test-set leakage during the run itself — the discovery pipeline nearly validated itself on data it had already seen ([arXiv](https://arxiv.org/abs/2611.21950)).

3. **The proposals started escaping the lab.** Ptarmigan-1 (Talus Bio) screens the entire human proteome against 3.4 billion compounds a day and surfaced STAT6 inhibitors that structure-based co-folding methods missed — candidates, not drugs ([The Scientist](https://www.the-scientist.com/an-ai-based-drug-discovery-tool-goes-beyond-protein-structures-75258)). An LLM agent paired with the Alexandria database proposed a previously unreported Ba-Zr-S solar absorber with a lab-ready synthesis plan ([arXiv](https://arxiv.org/abs/2611.15451)). Neither has been synthesized or assayed by an independent group yet. The claims are now arriving faster than the world that must confirm them.

## Evidence

**Where verification scales: math and code have machine ground truth.** The Gödel numbers make the case precisely: 663 theorems extracted, around 400 verified in Coq exactly as written, roughly 200 *repaired into correctness*, about 50 shown false. That is a complete triage of every claim — and it was only possible because a proof assistant, not a committee, is the arbiter. The ~50 false results are as valuable as the 400 verified ones; they are the system working. The Antigravity results tell the same story from the production side: a 28,800-invocation swarm can formalize the Weil conjectures unsupervised precisely because Lean checks every line, so "the agent wrote most of the bounds" stops being scary. Formalization used to be the most thank-consuming form of mathematics — a famous result could take a decade to encode. When agent swarms do it because they found it worth their own compute, the Lean corpus stops being a slow mirror of known math and becomes actively mined.

**Where it doesn't: the physical world doesn't take requests.** Ptarmigan's 3.4-billion-compounds-per-day screen is a *proposal generator*; the next step is synthesis, assays, and someone else's fume hood. The Ba-Zr-S absorber exists as a plan and a paper. These are exactly the claims the Maya correction warns about, one domain over: a compelling AI-generated finding that circulates at internet speed while its confirmation would take months to years at lab speed — and may never get priority, because replications are unglamorous and unfunded.

**The audit layer is itself becoming an AI job — with the same failure modes.** DiscoveryAgent's headline result is that scientist-in-the-loop runs beat fully autonomous ones, and its most quietly important detail is that audit agents caught test-set contamination during the run. That's verification being automated, which is the optimistic path — and the same week showed the risk: Singularity-Bench reported red-teamers reward-hacking an eval framework itself ([arXiv](https://arxiv.org/abs/2611.16591)). When the verifier is a model, the verifier inherits the model's failure modes. SO-Arena's finding that the most reliable decision model is not the most capable one is the same lesson in a different key ([arXiv](https://arxiv.org/abs/2611.15777)).

**The attribution problem is now quantitative.** Joel David Hamkins' question about the Gödel notebooks — "how much is Claude and how much is Gödel" — has a sharp version: the reconstructed near-proof that Gödel had the independence of the axiom of choice in the 1940s, one missing definition away from Paul Cohen's Fields Medal work. Machine verification can check that a proof is correct; it cannot check whether the *insight* was Gödel's or the machine interpolating plausibly across his shorthand. Historians of mathematics get the final word, at one page a week of human pace they no longer need for transcription but still need for judgment.

## The counterargument

The strongest case against the bottleneck thesis is that verification *is* automating, and this week proves it. Coq verified 400 theorems in days. Lean checked 50 million lines. The quantum-algorithm improvements are machine-verified end to end. On this reading, the right description is not "discovery outruns verification" but "verification moves to the same substrate as discovery, and human review is demoted to spot-checking" — a transition, not a gap.

There is also a fair charge of selection bias: the Maya story was corrected *within a day*, publicly, by ordinary journalistic scrutiny. That's the system working at internet speed too. And proposal volume alone was never the constraint in drug discovery — clinical trials, not idea generation, have always been the bottleneck, so an AI firehose of candidates changes less than the framing implies.

Both points are real, and both are why the split matters. Where ground truth is mechanical, the counterargument wins outright. Where it isn't — wet labs, archives, attribution — the Maya correction was fast because human archaeologists had already done the verification in 2025, *before* the AI claim arrived. The correction consumed pre-existing human work. The next claim won't always arrive with its refutation already published.

## What remains uncertain

- Whether formal verification generalizes beyond math. Lean and Coq work because proofs are self-contained; chemistry and biology are not, and no proof assistant checks a binding affinity.
- Whether replication will be funded. The Ba-Zr-S absorber and Ptarmigan's STAT6 inhibitors get confirmed only if some lab prioritizes them, and the incentive structure for replications is notoriously weak.
- Whether attribution standards will exist at all. If an AI completes a dead mathematician's proof, the field has no settled convention for crediting the insight — and Gödel's axiom-of-choice near-proof forces the question.
- Whether evaluation itself can be hardened fast enough. If audit agents become standard, eval-hacking becomes a research frontier with the same compounding as everything else.

## What to watch next

1. **An independent synthesis of the Ba-Zr-S absorber, or an assay of Ptarmigan's STAT6 inhibitors, within two quarters** — the first direct test of whether the physical world keeps up with the proposal rate.
2. **Peer review of the Gödel transcription** — if historians confirm the axiom-of-choice reconstruction, that's the attribution problem arriving in a top journal.
3. **DiscoveryAgent-style audit agents appearing in production pipelines** (not just papers) — the leading indicator that verification is being automated rather than accumulating as debt.
4. **A retraction or major correction of an AI-proposed discovery that had already circulated widely** — the moment the gap becomes a headline rather than a warning.

## Related coverage

- [The AI Pulse — 2026-11-14](../posts/daily-post-2026-11-14.md) — the Gödel notebooks and the Antigravity /teamwork results, in detail
- [The AI Pulse — 2026-11-17](../posts/daily-post-2026-11-17.md) — Singularity-Bench and the eval-hacking red-team result
- [The AI Pulse — 2026-11-13](../posts/daily-post-2026-11-13.md) — the Verge correction on the Maya site
- [Prediction register](~/projects/tracker/docs/projects/ai-progress-blog/prediction-register.md) — this post adds PRED-011

*Label: trend. Next week's slot: work-economy.*
