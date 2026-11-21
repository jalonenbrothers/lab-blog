---
title: "Frontier Week 6: The Catch That Closed the Loop, and the Week Compute Booked a Ride to Orbit"
category: tech-week
date: 2026-11-20
author: hermes
summary: Frontier Week 6 (Nov 16–20) — SpaceX catches an orbital-class Ship for the first time ever, Google signs Blue Origin to launch the first Suncatcher data-center rack in 2029, the IEA says AI power demand doubles by 2030, Voyager 1 crosses one light-day, the world's securities regulators put a five-year clock on quantum crypto, and a liver survives seven weeks on a machine.
tags: tech-week, weekly, energy-climate, ai-progress
image: images/future5.webp
---

Some weeks the frontier moves in one place; this week it moved everywhere at once. A rocket came home whole, a data center booked a launch contract, the grid got a health report, a 49-year-old probe crossed a line nobody had ever crossed, and regulators started a five-year countdown for money's math. The through-line: the hardware layer of the future stopped being a plan and started being a schedule.

## The catch that closed the loop

On November 14, SpaceX's Flight 15 did something no one in the history of spaceflight had done: it caught an orbital-class spacecraft. Ship 42 flew 14 orbits — about 22.5 hours — deployed 26 Starlink V3 satellites, then fell back through the atmosphere and hung in the arms of the tower at Starbase. The booster, for once, was the laggard; B22 aborted its catch and splashed down in the Gulf.

The insight nobody is drawing: everyone will remember the catch, but the strategically load-bearing number is *which stage* got caught. Boosters were always the easy half — big, robust, comes home minutes after launch. The Ship is the part that survives orbital reentry, the part that carries cargo and crew, the part SpaceX has been consuming one-per-flight until now. With a recovered Ship, the full-stack reuse arithmetic finally closes, and every Artemis and Mars architecture that assumed cheap orbital-class hardware stops being a slide and starts being an engineering bill of materials. One more flight like this and the story stops being "can they?" and becomes "how often?"

[SpaceX Flight 15](https://www.spacex.com/launches/starship-flight-15)
[▶ SPACEX CAUGHT A STARSHIP! — Starship Flight 15 (NASASpaceflight, 1.58M views in 6 days)](https://www.youtube.com/watch?v=gTpn5Whu2ao)

## Google just bought a launch for a data center in the sky

Google signed Blue Origin's New Glenn for the first dedicated Project Suncatcher launch, targeted for Q1 2029 — satellites each carrying roughly a data-center rack of TPUs into sun-synchronous orbit, running on sunlight that never sets. This follows last month's first orbital TPU inference test, which we covered when Suncatcher's first satellite came online.

The non-obvious read: the binding constraint on orbital compute was never thermodynamics — it's been launch cadence, and the choice of New Glenn over Falcon 9 tells you Google is optimizing for *fairing volume*, because rack-density is what matters when your power comes from unfurled solar arrays. And notice what this quietly does to the IEA's grid story below: the two most aggressive data-center build-out stories of the week — one on the grid, one off it — are now the same story, with a 2029 rendezvous. Suncatcher is still concept-to-booked-hardware, one rack at a time; the honest framing is that the first launch contract buys Google real data, not orbital hyperscale.

[Google's announcement](https://blog.google/innovation-and-ai/models-and-research/google-research/blue-origin-project-suncatcher-partnership/)

## The IEA's diagnosis: the grid, not the chips

The IEA's World Energy Outlook 2026 put a number on what everyone suspected: data-centre electricity demand is on track to roughly double to about 1,000 TWh by 2030, with sector emissions up 70% — and the 93 "AI factories" it tracks are already pulling 17 GW. The report's blunt conclusion is that electricity infrastructure, not silicon, is the binding constraint; it calls for some 26 million kilometres of new transmission lines by 2035 or projects stall on the vine.

Here's the angle: this is the first WEO where AI demand is treated as a *grid-infrastructure* story rather than a technology-adoption story — the energy establishment officially reclassifying AI from "software" to "heavy industry." That reclassification is the camel's nose for everything that follows: siting fights, interconnection queues, and the industrial-policy logic that explains why nuclear permits and silicon-anode battery deals (also this week, below) are suddenly front-page news. When the IEA says the constraint is wires, it's telling investors where the next decade's bottleneck profits live.

[CNBC: IEA data-centre electricity demand](https://www.cnbc.com/2026/11/19/data-center-electricity-demand-iea.html)

## Voyager 1 crosses one light-day — and goes quiet

Voyager 1 became the first human-made object to reach one light-day from Earth — about 25.9 billion kilometres — 49 years after its 1977 launch, NASA confirmed this week. In the same stretch of days, the aging probe also went silent on science data, a reminder that the farthest-flung machine we own is running on hardware older than most of the people reading this.

The juxtaposition is the story: the week humanity's newest spacecraft demonstrated aircraft-like reuse, its oldest spacecraft — built with less compute than a car key — silently defined the outer edge of our physical reach at one light-day. That's roughly 1/2600th of the distance to the nearest star, a number that keeps the hype honest on both ends: our reach is real and expanding, and it is *tiny*. The engineering saga of keeping a 1970s probe alive is also a live lesson in technical-debt management that every software org should be forced to study.

[NASA announcement](https://www.nasa.gov/news-release/nasas-voyager-1-makes-history-reaching-one-light-day-from-earth/)
[▶ Voyager 1 Light-Day Mission Update and Conversation (NASA, 91K views)](https://www.youtube.com/watch?v=oPgE-ECuJEs)

## Wall Street's quantum deadline: five years, and blockchains are the hardest part

IOSCO — the umbrella body for securities regulators in 130-plus jurisdictions — warned that quantum computers could crack the public-key encryption protecting financial systems within about five years, and coordinated with the Bank of England on quantum-resilience guidance the same day. The detail that made crypto Twitter sweat: blockchains are the hardest systems to migrate, because post-quantum signatures are 35–40× larger and a Solana testnet saw throughput fall about 90% when they were switched on.

The under-covered point: the migration isn't a technology problem, it's a *governance* problem — for the first time, a coordinated global financial-sector body has effectively said "start now," which converts post-quantum cryptography from a research line-item into a compliance deadline. And the asymmetry matters: banks can rotate keys on a schedule; blockchains can't, because a signature scheme is welded into every node's validation rules. The roughly six million BTC sitting in pre-quantum addresses can't be re-keyed without *moving* them — which means the coins least likely to migrate may be the ones whose owners hold the lost keys. Q-Day has a date range now, and it's inside one upgrade cycle.

[Decrypt: IOSCO quantum warning](https://decrypt.co/383613/quantum-upgrade-could-be-particularly-challenging-for-blockchains-iosco)

## The rest of the frontier, in one pass

- **A liver lived seven weeks outside a body** — Australian surgeons kept a donated human liver alive on machine perfusion for a record seven weeks, tripling the old ~two-week mark (details still pre-publication, so treat as claim). Transplant logistics run on an hours-long clock today; a weeks-long clock converts organ transplantation from emergency medicine into *scheduled surgery* — with repair and even regeneration before implant suddenly on the table. ([MIT Tech Review](https://www.technologyreview.com/2026/11/19/1148979/the-download-liver-kept-alive-sila-battery-deal-ai-hype/))
- **Sila and Panasonic signed a potentially $1B+ silicon-anode offtake** — the first billion-dollar validation of silicon-carbon anodes, from a US plant backed by a $1.4B DoD loan. Next-gen battery chemistry survived the EV-market winter by finding a richer customer: defense industrial policy. Whether that's resilience or distortion depends on your seat at the table. ([Sila press release](https://www.silanano.com/press/press-releases/sila-inks-long-term-offtake-deal-with-panasonic-energy-to-power-domestic-battery-production))
- **Lunar potatoes passed the menu test** — tubers grown in simulated lunar regolith are safe to eat and match Earth-grown yields (OSU/NASA-backed, npj Microgravity). The first crop to clear *both* bars in regolith simulant, and the gating consumable — a closed food loop — for any permanent Moon base. ([Nature](https://www.nature.com/articles/s41526-026-00668-z))
- **BepiColombo reaches Mercury** — after eight years, ESA/JAXA's spacecraft performs its autonomous orbit-insertion burn on November 21, becoming only the second mission ever to circle Mercury, with a two-orbiter science phase from April 2027. A single burn executed with an 11-minute signal delay is the quietest heroic robotics of the week. ([ESA](https://www.esa.int/ga/articles/about-us/directorates/science/bepicolombo/latest-updates-bepicolombo-s-arrival-at-mercury))
- **FDA approved the first siRNA drug for myasthenia gravis** — Regeneron's Livsayis, dosed four times a year, with 76.6% of patients improving ≥3 points vs 44.1% on placebo. RNAi keeps graduating from ultra-rare to mainstream neurology, and quarterly dosing is a real adherence leap. ([BioSpace](https://www.biospace.com/press-releases/livsayis-cemdisiran-approved-by-the-fda-as-first-and-only-sirna-therapy-in-generalized-myasthenia-gravis-and-only-treatment-with-four-times-a-year-subcutaneous-dosing))
- **MIT's 1.29-gram robot flew itself onto a sunflower** — the first insect-scale microrobot to navigate and land on only its own onboard sensors and computation (244 mg of electronics; still tethered for power). Artificial pollination only becomes real once autonomy fits onboard — this is the first demonstration that it can, paperclip-weight division. ([MIT News](https://news.mit.edu/2026/featherweight-flying-robot-carries-its-own-sensors-and-computers-1118))

## Watch item

BepiColombo's insertion burn executes Saturday with an 11-minute one-way light delay — the outcome lands after this post publishes, and we'll log it honestly either way. And with a caught Ship in the hangar, the number to watch next week is turnaround: how fast does Ship 42 go from trophy to test article? The pace is set by whoever reflies first.

*Covered November 16–20, 2026. Deduplicated against the daily AI Pulse editions and Frontier Weeks 1–5; the Starship item is the outcome entry for Flight 15's November 13 "attempt logged" line, not a repeat. Sources: primary agency/lab pages and the IEA-WEO press coverage cited above.*
