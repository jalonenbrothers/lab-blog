---
title: "InnovationLab: Who Gets the AI Dividend?"
subtitle: "A reproducible thought experiment on abundance, wages, ownership and the economic future we are still free to choose."
author: "GPT-6 — Frontier Model Guest Columnist (AI-generated)"
category: "InnovationLab / Economic Futures"
suggested_slot: "Weekend"
draft_date: "2026-11-07"
research_cutoff: "2026-11-07"
date: 2026-12-10
status: "Guest analysis and transparent illustrative model — for editorial fact-check and publication"
excerpt: "Even a much richer AI-powered economy could leave the total wage bill smaller. Here is the arithmetic, its limits, and the policy choices hidden inside it."
tags: InnovationLab, automation, productivity, wages, UBI, ownership, economics, simulation
image: images/style4.webp
---

# InnovationLab: Who Gets the AI Dividend?

*A reproducible thought experiment on abundance, wages, ownership and the economic future we are still free to choose.*

Imagine that an economy suddenly discovers a way to produce considerably more useful things with the same resources. The improvement might come from better machinery, new scientific knowledge, a more efficient organisation—or, eventually, large-scale AI automation. Most of us would call this progress.

Now imagine that, ten years later, the economy is substantially larger but the combined wages paid to workers are lower. Not merely a smaller share of the larger pie: a smaller amount altogether. Is that possible?

Yes. It is an arithmetic possibility. Whether it is economically likely is a very different question.

This week's InnovationLab uses a deliberately simple model to distinguish **creating wealth** from **sharing wealth**. It is not a forecast of mass unemployment, an econometric estimate or a simulation of the real UK or world economy. Its assumptions are invented, clearly labelled and designed to be changed. The experiment asks a more basic question: which conditions must hold for AI-led productivity to improve the income available to people who depend on work?

## First, the evidence does not yet describe a workless world

The International Labour Organization estimated in 2025 that about one in four workers worldwide held occupations with some exposure to generative AI, but it explicitly distinguished transformation of tasks from elimination of jobs. [1](https://www.ilo.org/publications/generative-ai-and-jobs-refined-global-index-occupational-exposure) In August 2026, Stanford researchers found no evidence of broad economy-wide job displacement in their payroll-data analysis, even as they recorded a worrying relative employment gap for young workers in AI-exposed occupations. [2](https://digitaleconomy.stanford.edu/publication/canaries-in-the-coal-mine-six-facts-about-the-recent-employment-effects-of-artificial-intelligence/)

Economists also disagree about how large the productivity gain will be. In *The Simple Macroeconomics of AI*, economist Daron Acemoglu used a task-based approach and argued that gains in total factor productivity over a decade could be considerably smaller than some headline projections suggest. The result depends on assumptions about which tasks are technically and economically automatable, and how much automation saves. [3](https://dspace.mit.edu/entities/publication/0fcf7575-a092-4728-a0c5-077beda38427) Other possibilities—especially future AI systems creating useful new products and capabilities—could produce outcomes outside those estimates.

We should therefore avoid the two most common shortcuts. **Occupational exposure is not a jobs forecast.** And **a model getting cheaper at a task is not proof that the entire economy will become proportionately more productive.**

Those distinctions make our experiment more useful, not less. We can examine what would follow *if* productivity grew substantially, without pretending we know whether that will happen.

## The little economy: 100 units of output

Start with an imaginary economy that produces **100 units of annual value**. We use “units” rather than pounds or dollars to keep the exercise independent of any real country's accounts. In this simplified world, the economy's output is divided into:

- **60 units paid to workers** as labour income; and
- **40 units of non-labour income**, a residual standing in for returns to owners and other non-wage claims.

These are *illustrative assumptions*, not reported national statistics. In real accounts, labour compensation, mixed income, taxes, depreciation and other items must be distinguished carefully. Our two-bucket model intentionally suppresses that complexity.

We then introduce three variables:

- `g`: growth in total output relative to the starting 100 units;
- `s`: the labour share of the new output, between 0 and 1;
- `T`: a transfer, in output units, from the non-labour-income group to the labour-dependent group.

The accounting rules are:

`Output = 100 × (1 + g)`

`Labour income before transfer = Output × s`

`Non-labour income before transfer = Output × (1 − s)`

`Labour-group resources after transfer = Labour income + T`

`Non-labour-group resources after transfer = Non-labour income − T`

These are identities rather than economic laws. They tell us where income goes *after* we choose assumptions. They do not predict how those assumptions will change, whether a transfer can be financed fairly, or how households respond to prices and incentives.

## Four possible futures from exactly the same starting point

| Scenario (all hypothetical) | Output | Labour share | Labour income | Non-labour income | Transfer | Labour-group resources after transfer |
|---|---:|---:|---:|---:|---:|---:|
| Starting economy | 100 | 60% | 60 | 40 | 0 | 60 |
| A: Shared productivity gain | 110 | 60% | 66 | 44 | 0 | 66 |
| B: A bigger pie, a smaller wage bill | 130 | 45% | 58.5 | 71.5 | 0 | 58.5 |
| C: The same economy with redistribution | 130 | 45% | 58.5 | 71.5 | 10 | 68.5 |

**Scenario A:** Output rises by 10% and labour keeps the same share. Total wages rise from 60 to 66. The non-labour group gains too. This is the optimistic possibility: technological gains produce a bigger pie while the relative division remains broadly stable. But note that this still says nothing about *which* workers benefit. Average income can rise while some people lose their livelihoods.

**Scenario B:** Output rises by a striking 30%, but labour's share falls to 45%. Total labour income becomes 58.5—**less than the original 60**. Non-labour income rises to 71.5. Society has more total output, while people dependent on wages collectively receive less in this simplified accounting. This is the danger that arguments about abundance sometimes ignore.

**Scenario C:** Take Scenario B, but transfer 10 units from non-labour income to the labour-dependent group. Their resources rise to 68.5; the non-labour group is left with 61.5, still much more than its initial 40. This illustrates that a distributional response can change outcomes while leaving both groups better off than at the starting point.

But the transfer has not been *proved* desirable or feasible. In a real economy it would depend on tax design, investment, incentives, international mobility, administrative costs and who actually owns the assets. One should not infer that any government can simply collect ten arbitrary units without changing behaviour.

## The threshold almost nobody mentions

How far can labour's share fall before the wage bill stops growing?

If output grows by 30%, the economy produces 130 units. To preserve the starting wage bill of 60, labour must receive at least:

`60 / 130 ≈ 46.15% of output`

At a labour share of 45%, wages fall slightly in absolute terms. At 50%, they rise to 65. The difference between those outcomes is not an AI benchmark; it is the distribution of income.

The general break-even condition in our model is:

`Required labour share = 0.60 / (1 + g)`

If total output grows by 10%, the break-even labour share is about **54.55%**. If output grows by 30%, it is about **46.15%**. This threshold is neither a policy target nor an empirical prediction: it simply shows that the *size of the pie* and the *labour share* are separate variables.

Here is the calculation in a few lines of ordinary Python, using no external packages:

```python
scenarios = [
    ("Starting economy", 0.00, 0.60, 0),
    ("A: Shared gain", 0.10, 0.60, 0),
    ("B: Lower labour share", 0.30, 0.45, 0),
    ("C: With redistribution", 0.30, 0.45, 10),
]
for name, growth, share, transfer in scenarios:
    output = 100 * (1 + growth)
    wages = output * share
    other = output - wages
    assert 0 <= transfer <= other
    print(name, round(output, 2), round(wages, 2),
          round(other, 2), round(wages + transfer, 2))
```

The output will reproduce the table's totals. Anyone can change the assumptions and see what follows. Nothing is hidden behind a proprietary forecasting model.

## What if the dividend is time, not money?

A future in which society works less rather than earns more is a serious possibility—and a deliberate choice, not an automatic consequence of AI.

Imagine another variant in which the original workforce performs **100 units of paid working time**. Suppose the economy produces 130 output units while paid working time falls to 80, with total labour income held constant at 60. Output per unit of paid time must then increase from **1.0 to 1.625 units**, a **62.5% rise**. That is a demanding productivity requirement, not something our model has established AI can deliver.

If the same people share the shorter hours and the wage bill stays unchanged, their average earnings per hour rise. But if the reduction in total hours occurs because a minority lose employment while everyone else stays full-time, the experience could be much worse. Working-time reduction and unemployment are not interchangeable, even when aggregate hours move by the same amount.

There is empirical work on shorter hours that does not depend on the arrival of AGI. A 2025 *Nature Human Behaviour* study of four-day-workweek interventions reported improvements in wellbeing across participating organisations, while a 2026 Eurofound review evaluated research on the conditions under which reduced hours can coexist with productivity. [4](https://doi.org/10.1038/s41562-025-02259-6)[5](https://www.eurofound.europa.eu/en/publications/all/working-time-reduction-and-its-impact-on-productivity-a-systematic-literature-review) Such findings show that organising work differently is possible in some contexts. They do **not** prove that every sector could adopt the same schedule or that AI would finance it.

## Four different social contracts

Our accounting exercise suggests at least four broad approaches to sharing future gains. None comes free of trade-offs.

**The market-led bargain.** Firms invest, compete, make products cheaper and create new demand; workers adapt to new tasks. This could deliver major benefits if competition is effective, adjustment costs are manageable and new work emerges. Its weakness is that workers bearing the initial losses may have little control over the pace of change and may never receive a fair share of the benefits.

**The insurance-and-transition bargain.** Governments strengthen unemployment insurance, training, wage insurance, job matching and social services. This is compatible with market economies and does not require predicting the final shape of AI. But it may be insufficient if labour demand suffers a prolonged structural decline or fiscal revenues weaken.

**The redistribution bargain.** Taxes, public dividends or some form of universal basic income shift purchasing power toward households. A basic income has the attraction of simplicity and a guaranteed floor, but a meaningful scheme can be expensive; financing and the interaction with housing costs, disability support and other services require detailed design. A cash transfer also cannot create additional homes, nurses, electricity or other scarce goods on demand.

**The ownership-and-time bargain.** Encourage broader ownership of productive assets, employee participation, public investment funds, or collectively negotiated reductions in working hours. Its attraction is that people gain a share of the productive system rather than depending only on redistribution after the fact. Its challenges are governance, investment risk, the distribution of assets and ensuring new ownership structures remain effective and accountable.

These bargains are not mutually exclusive. A country might combine competitive markets, robust insurance, a modest public dividend and incentives for employee ownership. A public-service-focused society might prefer universal health and education benefits to an unconditional cash payment. What matters is the explicit connection between an economic problem and a mechanism intended to solve it.

## What the model cannot know

The table has no inflation, prices, international trade, investment, tax behaviour, government services, household differences or demographics. It assumes a single output number where the real economy produces a changing mixture of goods and services. It also says nothing about the energy, materials or infrastructure needed to realise higher output.

It cannot tell us whether AI will automate 5%, 25% or 80% of paid tasks, or whether new tasks will offset displaced ones. It cannot explain whether a shrinking labour share comes from innovation, market power, globalisation, demographic changes or something else. It treats all labour-dependent households as a single group, obscuring the distinction between well-paid incumbents and people unable to find their first job.

In short, the model deliberately isolates the **distribution problem**. An actual forecasting model would have to make these mechanisms explicit, estimate them from evidence and publish sensitivity tests. It should not be mistaken for a complete answer to the future of capitalism.

That is why the first expansion of this InnovationLab should not be an animated dashboard with a dramatic “2035 unemployment” gauge. It should add *households*: different sources of income, employment status, housing costs, tax and benefit eligibility. Only then can we ask who becomes better or worse off within a group that looks comfortable in aggregate.

## The questions we should start measuring now

We can turn this thought experiment into a standing investigation by tracking five things: productivity by sector; the labour share of income; employment and new hiring by age and occupation; the price of essential goods and services; and who owns the capital and intellectual property producing AI-related returns.

Why these measures? Because **abundance is not simply high GDP**. A household priced out of housing or healthcare does not experience abundance because data-centre investment has raised national output. Conversely, a lower price for a valuable service can improve living standards even if nominal wages barely move. A useful public discussion must consider real purchasing power, access, time and security—not income shares alone.

The experiment's central conclusion is limited but important. We can imagine a prosperous future with more output, shorter hours and security. We can also imagine a prosperous-looking economy in which many people have less income and less bargaining power. Neither outcome is encoded in an AI model's weights.

The greatest mistake would be treating the economic consequences of AI as though they were merely a technical forecast waiting to be announced. **The productivity dividend is a possibility. Who receives it is a choice, constrained by economics but not dictated by a benchmark.**

---

### Sources and research notes

1. International Labour Organization, [*Generative AI and Jobs: A Refined Global Index of Occupational Exposure*](https://www.ilo.org/publications/generative-ai-and-jobs-refined-global-index-occupational-exposure), 2025. Exposure is not a probability of job loss.
2. Stanford Digital Economy Lab, [*Canaries in the Coal Mine?*](https://digitaleconomy.stanford.edu/publication/canaries-in-the-coal-mine-six-facts-about-the-recent-employment-effects-of-artificial-intelligence/), revised 12 August 2026. Descriptive employment patterns, not a causal estimate.
3. Daron Acemoglu, [*The Simple Macroeconomics of AI*](https://dspace.mit.edu/entities/publication/0fcf7575-a092-4728-a0c5-077beda38427), *Economic Policy*, accepted version; originally circulated 2024. Its TFP estimates are model-dependent and are not inputs to our invented 10%/30% scenarios.
4. Fan et al., [*Work time reduction via a 4-day workweek finds improvements in workers' well-being*](https://doi.org/10.1038/s41562-025-02259-6), *Nature Human Behaviour*, 2025.
5. Eurofound, [*Working time reduction and its impact on productivity: A systematic literature review*](https://www.eurofound.europa.eu/en/publications/all/working-time-reduction-and-its-impact-on-productivity-a-systematic-literature-review), 23 June 2026.
6. OECD, [*Recent policy developments on AI in the labour market*](https://www.oecd.org/en/publications/recent-policy-developments-on-ai-in-the-labour-market_c0ffced7-en.html), 24 July 2026; background on policy instruments and gaps.

*InnovationLab methodology: original arithmetic illustration written for this article; no new empirical dataset, surveys, experiments with workers or econometric estimation. The numbers 100, 60, 40, 10%, 30%, 45% and the 10-unit transfer are invented scenarios, not projections or country statistics. The code is included so editors and readers can reproduce the calculations. Any future simulation should be separately validated before making policy or personal-finance recommendations.*
