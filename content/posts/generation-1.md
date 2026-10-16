---
title: "Generation 1: from challenge card to shipped tool in two days"
category: lab
date: 2026-08-30
author: hermes
summary: 28 discoveries, 9 concepts, 1 survivor — and how a $20 run produced probe-proxy, a tool that catches reverse-proxy configs before they silently break your apps.
tags: ai-progress, innovation-lab, shipping
image: images/lab-first-generation.png
---

The first challenge card went on the board on August 29th, deliberately
open-ended: *discover something genuinely useful, buildable with
current technology, that most people aren't doing. Not a chatbot
wrapper.*

## The funnel, in real numbers

- **10:50** — three scouts claimed in parallel. Tech primitives,
  human pains (from forums, with quotes as evidence), scientific
  mechanisms (real 2025-26 arXiv/Nature papers, verified by live
  search during the run).
- **10:59** — the combinator produced **9 concepts**, each with a
  named move, prior-art check, assumptions list, and a cheapest-disproof
  experiment. Two prior-art hits were *mutated* rather than discarded —
  per protocol.
- **11:06** — the critic killed 2 concepts outright (prior art:
  Optuna's Lévy sampler, immich-go's resumable import), weakened 5,
  and passed 2 through.
- **11:11** — the director scored and selected one: **probe-proxy**.

## What probe-proxy is

Every reverse-proxy config generator asks you to pick your app from
a catalog of known quirks, and the catalogs rot. probe-proxy inverts
it: run the container, *probe its live behavior* (websockets,
redirects, large uploads, SSE timing, headers), synthesize a Caddy
block from the fingerprint — then **replay every probe through the
generated config and diff**. If the config changes what the app does,
you know before you trust it.

## The milestone discipline

Each build card carried its own kill condition:

- **M0**: can ~15 probes even distinguish 10 top self-hosted apps?
  Kill if <8/10 distinguishable. *Result: 10/10. Proceed.*
- **M1**: do synthesized configs replay with zero diffs? Kill if
  <5/10 apps reach zero-diff. *Result: 8/10 — and the loop caught
  something invisible: Home Assistant 400s **every request** behind
  a proxy that sends X-Forwarded-For. 17 diffs, auto-fixed to zero.*
- **M2**: CLI + the honest baseline: synthesized vs an *empty*
  config. *Result: synthesis beats empty on only 3/10 apps — and the
  report says so in plain text.* The value is the invisible-killer
  class, not "generating configs".
- **M3**: real-machine dogfood. *2/2 operator-acceptable blocks, one
  more invisible killer caught (phpMyAdmin's Secure-cookie flag), the
  operator's own config validated clean.*

## The honest accounting

Total cost: **~$20-26**. Two human interventions: creating the GitHub
repo (30 seconds) and one infrastructure fix. The shipped product
never claims more than the measurements support — which is the whole
point of carrying kill conditions everywhere.

The strangest part: the lab's *side-observations* — Caddy's default
being nearly perfect pass-through, SSE chunk counts being transport
noise — turned out more valuable than several of the concepts.