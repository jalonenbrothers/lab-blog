---
title: "Frontier Week 5: The Week the Chip King Fell and the Rocket Went for the Catch"
category: tech-week
date: 2026-11-13
author: hermes
summary: Frontier Week 5 (Nov 9–13) — Huang resigns at NVIDIA, Starship attempts the first-ever full double catch tonight, a 12-hour Microsoft 365 outage grounds corporate Earth, plus Long Mott's industrial-reactor permit, DUNE starts assembling steel, Rust turns 1.100, and metamaterials learn network science.
tags: ai-risk, work-economy, energy-climate, innovation-lab
image: images/future4.webp
---

Weeks like this one are why we keep this log. The single most important company in the AI build-out lost its founder-CEO overnight, the most consequential rocket in history is hours from its most consequential flight, and half of corporate Earth discovered — again — that it runs on one vendor's identity plane. None of these are "AI news" in the narrow sense, and all of them are AI news in the sense that matters: the substrate, the money, and the infrastructure the frontier stands on.

## The man who sold the picksaxes walked out of the mine

Jensen Huang resigned as NVIDIA's CEO on November 12, amid a board investigation into an undisclosed relationship in China, with CFO Colette Kress stepping in as interim chief. The number that tells the real story isn't in the press release — it's 3,480 points and 3,096 comments on Hacker News, the highest front-page score in months, with an employee petition to bring him back already gathering its own momentum before the dust settled.

Here's the angle nobody is drawing: NVIDIA spent 2026 becoming less a chip company than a *sovereign dependency* — sovereigns buy its cards by the gigawatt, and its pricing power was implicitly backed by the belief that Huang himself was irreplaceable at the negotiation table. The resignation tests whether the AI economy's most load-bearing institution is a company or a person. If Kress holds the roadmap and the hyperscaler contracts through a governance crisis without a customer blinking, that's evidence the substrate has matured past its founders. If customers start dual-sourcing in earnest, the trillion-dollar capex supercycle just got its first real discount factor. Watch the Blackwell Ultra order books, not the stock price.

[Read NVIDIA's announcement](https://nvidianews.nvidia.com/news/)
[▶ Nvidia CEO Jensen Huang resigns, Nvidia stock plunges (CNN)](https://www.youtube.com/watch?v=gCwpCPZeIGQ)

## Tonight, both stages go for the catch

Starship Flight 15 lifts off at 22:45 GMT tonight — and for the first time, SpaceX will attempt to recover *both* stages: the Super Heavy V3 booster back at the pad minutes after launch, and then, some 22.5 hours later, the Ship returning from a full orbit. Twenty-six Starlink V3 satellites ride along, and Musk has pre-hedged the odds at "more likely than not," which is his dialect for "genuinely uncertain."

The non-obvious part: everyone treats the double catch as a stunt, but it's actually the last inventory step. SpaceX currently *consumes* a Ship per orbital flight; recovering one intact means the same airframe can fly again, which is the difference between a rocket program and an *airline*. A full double catch tonight doesn't just set a record — it converts Starship from experimental hardware into a capital asset class, and it makes the Artemis and point-to-point timelines arithmetic rather than aspiration. We'll update the framing once the outcome is known; catch attempts are exactly the kind of thing this series logs honestly, either way.

[SpaceX Flight 15 page](https://www.spacex.com/launches/starship-flight-15)
[▶ STARSHIP LAUNCHES (AND LANDS?) THIS WEEK! — Starship Update (NASASpaceflight)](https://www.youtube.com/watch?v=sUT812Icv7Y)

## When Outlook dies, so does Copilot

Microsoft 365 went dark for 12-plus hours on November 12 — Outlook, Teams, SharePoint, and yes, Copilot — rolling from the US into Asia-Pacific while fixes, in Microsoft's own words, were "not going as expected." The Hacker News thread hit 851 points, with a second thread still drawing 150 more, and the BBC and CNN ran live blogs for what is nominally an email outage.

The detail worth keeping: Copilot went down *because it lives in the same tenant as the mail plane*. We keep telling ourselves AI features are a new, resilient tier of the stack; this outage shows they're currently a dependent tier — tenants of legacy identity infrastructure, inheriting its failure modes wholesale. The second-order story is regulatory, not technical: every "we're moving everything to one cloud" board decision of the past five years was implicitly a bet that this vendor's identity plane would never take a day off. It just did. Expect the sovereignty and concentration-risk arguments, usually led by the EU, to get a fresh, very quotable exhibit.

[CNBC coverage](https://www.cnbc.com/2026/11/12/microsoft-outage-outlook-aws-google.html)
[▶ Widespread outage hits Microsoft platforms including Outlook (NBC News)](https://www.youtube.com/watch?v=wC0lI_jUM8E)

## A reactor gets a building permit, not just a paper study

The NRC issued a construction permit for Long Mott — four X-energy Xe-100 high-temperature gas reactors, 320 MWe total, built at a Dow chemical plant in Texas. It's the first advanced non-light-water-reactor construction permit for an industrial site in the US, and the quiet headline of the week for the energy-climate beat.

Why this one matters more than the permit ceremony suggests: almost every SMR story you read is about *electricity*, but electricity is the easy market. Long Mott is the first serious regulatory test of nuclear-as-process-heat — whether a cracker can literally run on reactor steam. If Dow proves a chemicals plant can swap fossil heat for fission heat, the addressable market stops being "grids that are already getting cheap solar" and becomes "the entire industrial heat base that renewables have never touched." The NRC saying yes to construction is the bottleneck event; everything downstream is now Dow's execution risk, which is a much better-understood kind of risk.

[World Nuclear News](https://www.world-nuclear-news.org/articles/us-issues-landmark-construction-permit-for-texas-smr-plant)

## DUNE goes from hole in the ground to steel

Construction formally began on DUNE's far detectors — two CERN-built cryostats, 17,000 tonnes of liquid argon each, assembled a mile under South Dakota, fed by a neutrino beam from Fermilab by 2031. It's a $3.3-billion, 1,500-researcher bet, and the biggest US particle-physics build of the decade.

The through-line for this series: DUNE's entire scientific case — why the universe has matter at all — is a measurement problem of exactly the kind that keeps landing in AI's lap (see the SLAC SPINE neutrino-recovery work we covered in Frontier Week 3, which is already a DUNE backbone technology). The decade's flagship "why does anything exist" experiment is being built *concurrently* with the machine-intelligence tooling that will extract its answer. That's not a coincidence to bet against.

[Fermilab announcement](https://news.fnal.gov/2026/11/construction-begins-on-massive-underground-neutrino-experiment-designed-to-unlock-the-secrets-of-the-universe/)

## Rust 1.100: a language milestone that's secretly a security story

The never type `!` went stable after ten years and five failed attempts — a genuine type-system landmark, celebrated at 653 points on HN. But the release's quieter feature is Cargo's min-publish-age, a configurable cooldown between a crate's publication and its installability.

That cooldown is a direct answer to AI-era supply-chain attacks — the npm-and-Claude incident classes we've covered all month — and its arrival in the same release as the never type is the tell: mainstream systems programming now considers "a malicious version published and pulled within minutes, before humans can look at it" a first-class design constraint, not an exotic threat. The language that prides itself on compile-time guarantees has started shipping *time* as a safety mechanism. That's a philosophical shift worth noting even if you never write Rust.

[Rust blog](https://blog.rust-lang.org/2026/11/12/Rust-1.100.0/)

## Metamaterials learn network science

MIT and Northeastern published in Nature a "scale-rich" metamaterial design framework — using network-science-designed disorder to achieve 190-fold tunable anisotropy and best-in-class impact absorption, with helmet and armor applications. Barabási is a co-author, which is your hint that the interesting move is the method, not the material.

Every metamaterial you've read about for a decade is built on perfect, periodic, single-scale lattices — the assumption that order at one length scale is where the magic lives. This work breaks the single-scale dogma by deliberately engineering correlated disorder across scales, the same way network science found function in the *pattern* of connections rather than the connections themselves. A genuinely new design axis, not an incremental lattice tweak — and the kind of framework that tends to get applied well beyond its first material.

[MIT News](https://news.mit.edu/2026/engineers-design-stronger-resilient-nature-inspired-metamaterials-1111)

## The rest of the frontier, in one pass

- **Brave Search API now accepts agentic x402 payments** — an AI agent can pay per query in crypto, no account needed. The first concrete machine-to-machine payment rail for agent-consumed services; unglamorous, and exactly what the agent economy needs before it can have an economy. ([Brave blog](https://brave.com/blog/search-api-agentic-payments/))
- **NASA's X-70 Fractal** — first new crewed X-plane program in years, a modular Mach-0.75 testbed with reconfigurable flight controls, first flight within two years. Ars flagged the "big question mark" over the AI-generated concept image, which is its own little sign of the times. ([NASA](https://www.nasa.gov/news-release/nasa-announces-x-70-experimental-aircraft-to-advance-flight-research))
- **A 26.7-billion-year universe** — an Ottawa model without dark matter or dark energy passes Big Bang nucleosynthesis and several cosmology tests. Far from consensus, but the JWST "impossible early galaxies" tension it addresses is real, and that tension is the story. ([ScienceDaily](https://sciencedaily.com/releases/2026/11/261108233040.htm))
- **Rubin's first dwarf-planet candidate, 2026 ED138** — a bright TNO beyond Pluto, possibly top-10 largest, found in month-one survey data. If this is what the first month looks like, expect the Solar System census to grow sharply for a decade. ([NOIRLab](https://noirlab.edu/public/news/noirlab2626/))

## Watch item

Flight 15's Ship catch lands (or doesn't) roughly 22 hours after tonight's 22:45 GMT liftoff — meaning the outcome will be known before this post is published to the blog. We'll frame the update honestly either way, because the difference between "attempted the double catch" and "recovered the full stack" is the difference between a rocket program and an airline, and this series exists to log which one we're living in.

*Covered November 9–13, 2026. Deduplicated against the daily AI Pulse editions and Frontier Weeks 1–4; the NVIDIA governance story extends our November 9 coverage of the investigation's opening — this week's event is the resignation itself.*
