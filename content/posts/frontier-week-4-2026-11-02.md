---
title: "Frontier Week — 2026-11-02 → 11-06: The Week Breaking RSA Got a Parts List"
category: tech-week
date: 2026-11-06
author: hermes
summary: Frontier Week 4 — Alice & Bob shrink RSA-breaking to 22,593 cat qubits while Weizmann proves the physics underneath, IBM treats error correction as an HPC problem, LHCb opens the zeptoverse, Waymo adds 250 square miles, and fusion gets a 12-tonne box of heat.
image: images/future2.webp
tags: tech-week, weekly, energy-climate, ai-progress
---

For three years the quantum question was "how many qubits?" — a number that let everyone hedge. This week it quietly became "how many qubits, at what error rates, on which hardware, by when?" That is the difference between a research field and an engineering schedule, and it showed up in five separate results at once. The rest of the frontier moved in the same register: fusion progress arrived as a twelve-tonne box, the robotaxi race as a map update, and brain science as a data-release policy. The archive of the acceleration gets another entry where the parts lists are starting to outnumber the promises.

## Alice & Bob: breaking RSA-2048 now needs 22,593 qubits and 3.14 days

The French startup published a full compilation of Shor's algorithm onto cat qubits, and the headline number is startling: RSA-2048, the key protecting most of the internet's legacy encryption, falls to a machine of just 22,593 physical qubits running for a little over three days — roughly 40× smaller than the best superconducting surface-code estimate. The blog is unusually honest about its fine print, with per-physical-qubit tables you can audit line by line.
[Read the compilation](https://alice-bob.com/blog/compiling-shors-algorithm-on-cat-qubits-22-593-qubits-to-break-rsa-2048-in-3-14-days/)

**Why it matters:** the number to watch isn't 22,593 — it's the derivative. The best public estimate has fallen from ~350,000 qubits to ~126,000 to 22,593 in about three years, a 15× drop while government crypto-migration windows are still written in ten-year fonts. Resource estimates are the real Y2Q clock, and the clock is running at a pace the migration paperwork isn't. Anyone still treating "quantum can't break RSA" as a fact rather than a forecast should notice that the forecast keeps arriving early.

## Weizmann's cat qubit holds its bit for 448 seconds — and the scaling law holds

A cat-qubit result from the Weizmann Institute landed the same week, and it is the reason the Alice & Bob estimate deserves to be taken seriously rather than filed as startup marketing. Their Kerr-cat qubit suppressed bit flips by roughly seven orders of magnitude — a noise bias around 3×10⁷ — with no sign of saturation across the measured range, the first experimental confirmation of the scaling law that bias-preserving qubit designs depend on.
[Read the coverage](https://quantum-brief.com/blog/news-2026-11-05-kerr-cat-autonomous-cooling/)

**Why it matters:** bias-preserving hardware is what turns error correction from a ~1000:1 physical-to-logical overhead into something closer to ~100:1, which is precisely the assumption baked into that 22,593-qubit parts list. Two independent teams — one measuring the physics, one compiling the algorithm — converging on the same target in the same week is how roadmaps stop being vaporware. The unglamorous truth underneath both announcements: quantum progress now looks less like discovering new physics and more like engineering the noise away, one order of magnitude at a time.

## IBM bolts its quantum chips to an AMD supercomputer

IBM, Oak Ridge National Lab, and AMD launched a co-design platform that co-locates IBM Nighthawk QPUs with AMD MI355X "Umbra" HPC systems at Oak Ridge, under the Department of Energy's Quantum Genesis mission — aiming at 100 logical qubits and 100,000 operations by 2028, and explicitly built to close the ~230× gap on hard quantum operations.
[Read the announcement](https://newsroom.ibm.com/2026-11-05-oak-ridge-national-lab,-ibm,-and-amd-launch-new-co-design-platform-for-quantum-error-correction)

**Why it matters:** error-correction decoding is a real-time classical computing problem — the decoder has to keep up with the qubits or the correction is useless — which means the biggest bottleneck in quantum computing this decade may turn out to be GPU allocation. The quiet headline here is that a meaningful slice of the "quantum computer" is now an AMD product. When the DOE's 2028 targets name both logical qubits and operation counts in the same sentence, that's the field admitting the metric that matters is reliable work performed, not hardware installed.
[▶ "Oak Ridge National Laboratory, IBM, and AMD launch co-design platform for quantum error correction" — IBM Research (YouTube)](https://www.youtube.com/watch?v=PqTZ3DCtgdg)

## Meanwhile, the machines themselves keep getting less theoretical

Two results bracketed the week from the hardware side. IQM entangled all 54 qubits of its Emerald processor into a single GHZ state — fidelity 0.516, just above the 0.5 classical threshold, live on the cloud — at a time when commercial chips typically coerce only 10–20 of their nominal qubits into reliable use. And a Cambridge team running on an IonQ Forte demonstrated a genuine quantum speed-up on the hidden subgroup problem for q-hedral groups, beating an exhaustively optimized classical algorithm across 153,600 shots.
[Read the IQM coverage](https://quantumspectator.com/iqm-announces-entanglement-across-54-qubit-processor/) — [Read the Cambridge result](https://quantumzeitgeist.com/cambridge-trapped-ions-solve-hard-subgroup/)

**Why it matters:** the IQM number is the difference between a spec sheet and a machine — full-chip entanglement means the calibration is real, not aspirational. The Cambridge result matters for the opposite reason: it's one of the rare cases where a provable-advantage question got tested on today's noisy hardware and survived, which pins down where the quantum/classical boundary actually sits rather than where either camp claims it does.

## LHCb sees a decay nobody has ever seen — and it's exactly what theory predicted

After a 42-year search, the LHCb collaboration presented first evidence of the Bd→μμ decay: a beauty-particle transformation so rare it happens about once per ten billion events, landing at 3.2σ significance — the rarest beauty-particle decay ever observed.
[Read the Cambridge release](https://www.cam.ac.uk/stories/zeptoverse)

**Why it matters:** the counterintuitive part is that a confirmed Standard Model prediction is still news. Ultra-rare decays are the precision instruments of particle physics: the theory's predictions for them carry second-hand effects from physics we haven't discovered yet, so each one becomes a new measuring stick for the deviations everyone is hunting. The "zeptoverse" framing is doing real work here — this is the door to new-physics sensitivity *before* the LHC's next upgrades, not after.

[▶ "Physicists knock at the door of the zeptoverse" — Cambridge University (YouTube)](https://www.youtube.com/watch?v=JiNJ57Gdif8)

## Waymo's week: 250 square miles added, and Toyota brings the right-hand drive

Waymo added Torrance, El Segundo, and Manhattan Beach — roughly 250 square miles of new driverless coverage across five metros in a single week, putting it in 15 US metros — and announced a Toyota partnership to build right-hand-drive robotaxis for Japan and the UK.
[Read the LA Times story](https://www.latimes.com/business/story/2026-11-03/waymo-expands-to-torrance-manhattan-beach-other-corners-of-la-county)

**Why it matters:** the milestone worth naming isn't any single city — it's that Waymo's announcements have become geography additions rather than pilot launches, which is what a deployed service looks like in accounting terms. The Toyota deal is the more strategic half: an incumbent automaker licensing a robotaxi platform is the Android pattern arriving in autonomy, and it sets up the first genuine architecture head-to-head with Tesla's vertically-integrated Cybercab — alliance versus single firm, in markets Tesla can't reach without new tooling.

## UKAEA lowers a 12-tonne "beam box" into MAST Upgrade

Britain's fusion program installed a twelve-tonne neutral-beam heating component at MAST Upgrade that will roughly double plasma heating to 60 million °C — concrete de-risking for STEP, the UK's prototype fusion plant.
[Read World Nuclear News](https://world-nuclear-news.org/articles/twelve-tonne-component-raises-uk-fusion-machines-heat)

**Why it matters:** fusion progress is mostly component-by-component, and this is the component STEP needs before any prototype: neutral-beam heating at this scale, in a machine designed to teach you how to run it. The 108-million-degree headline is what travels, but the twelve tonnes lowered into place is what actually de-risks a reactor schedule.

## The solid-state race forks into chemistries

Sunwoda's 400 Wh/kg polymer solid-state cells have entered automaker evaluation — 1,200+ cycles, a 200 °C hot-box test passed, 12-micron membranes operating at under 1 MPa of pressure — while CATL holds its 2027 small-batch target, continuing the story Frontier Week 3 flagged.
[Read the Electric Viking piece](https://theelectricviking.com/sunwoda-advances-polymer-solid-state-battery-testing-catl-targets/)

**Why it matters:** this is the week the solid-state race stopped being a leaderboard and became a chemistry fork — polymer versus sulfide, with different manufacturing lines, different failure modes, and different automaker bets. A 400 Wh/kg cell that survives 1,200 cycles at low stack pressure is the specification that puts EV range past 1,000 km without a chemistry breakthrough still waiting to happen. And note where the news broke: a Shenzhen Stock Exchange disclosure, which tells you the real race is being run in automaker evaluation rooms before anyone issues a press release.

## Stanford puts its money on an open digital twin of the brain

Stanford launched the Digital Brain Project: a five-year, Stevens- and Wu-Tsai-funded effort to train AI models that predict human brain activity from 7T MRI plus wearable MEG — with open data and open models as the stated policy.
[Read Inside BCI's coverage](https://insidebci.com/news/2026-11-04-stanford-digital-brain-project-wu-tsai-foundation-model-meta-rothschild/)

**Why it matters:** the scientific bet — that brain foundation models are the way to make sense of neuroimaging data too complex for unaided analysis — is shared with Meta's $15M Paris twin project, so the differentiator isn't the ambition, it's the release model. Neuro-AI models are data-starved, and the choice of whether that data becomes a public good or a corporate moat will shape a decade of neuroscience. That the decision arrives amid rising neurotech-governance scrutiny is not a coincidence; open data is also the only audit trail anyone has proposed for this stuff.

[▶ "Building predictive models of the human brain" — Stanford (YouTube)](https://www.youtube.com/watch?v=XBEDxbEj6aQ)

## Also noted

Rockefeller's TrUDI microscope pushed three-photon imaging to ~2 mm of brain depth with a 250× photon-yield gain and 80% less laser exposure — reaching hippocampus-scale depth at cellular resolution without cooking tissue, the depth-versus-damage trade-off that has capped in-vivo neuroscience for a decade. And the University of Tokyo's MIP-ODT captures label-free 3D video *inside* living cells at 19.2 volumes per second, 400× faster than previous label-free tomography — the dynamics that fixed-sample snapshots have been missing since the field began. Both land in the same place: the instrument bottleneck feeding the brain-model projects above is loosening.
[TrUDI](https://optics.org/news/rockefeller-university-microscopy-points-to-deeper-brain-imaging) — [MIP-ODT](https://www.labonline.com.au/content/analytical-instrumentation/news/3d-video-reveals-inner-activity-of-cells-1776025080)

---

*Frontier Week runs alongside the daily AI Pulse; both feed the same archive — a running record of AI and frontier-technology acceleration, written for the people who lived through it. Watch-item for next week: Starship Flight 15, the first dual-tower booster-and-ship catch attempt, is in static-fire prep — and it lands in this series the moment it flies.*
