## DEV-5815: New automated pre-departure email

**Parent:** BAU 2026Q3 Priorities (DEV-4998)
**Type:** Story | **Priority:** Medium | **Status:** Scoped
**Sprint:** DEV Sprint 80-I
**Reporter:** Benjamin Kinnard | **Assignee:** Unassigned
**Labels:** Email, Internal
**Attachment:** Pre-departure email 20260911.docx

### Background

Request from Yas (via [Spiceworks ticket #171594](https://on.spiceworks.com/tickets/open_dev_help/1?sort=updated_at-desc&ticket_number=171594)). Primary aim is to save RCC time answering the same pre-departure questions repeatedly.

We want to send an automated email the evening before departure. It's for **direct bookings only** — not agent or OTA bookings, since we don't have their customers' email addresses.

Note the send-time discrepancy in the source ticket: the intro text says "the evening before at 5pm", but the template itself specifies "Send time: 8pm night before departure." Worth clarifying with Yas/product owner before building — the Gherkin below only says "5pm" and "the evening before", so this needs confirming as the acceptance criteria are ambiguous too.

**Decision already made:** hardcode the luggage requirements rather than making them a variable.

### Email template (from Yas, reworked per Ops feedback)

**Subject:** Your boarding card is ready

> **Your Adventure Starts Tomorrow**
>
> Hi ${First Name},
>
> Tomorrow's the day. We're looking forward to welcoming you on your ${TOURNAME}. To help you get ready, here's everything you need to know before you set off.
>
> ---
>
> **Your Tour Details**
> - ${Departure Date}
> - Departure Point: ${Departure Location} [Link to Google Maps]
> - ${Departure Time} ⏰ Expected Return Time: ${End Time}
> - ❗ Please arrive at least 15 minutes before departure so your guide can check everyone in
> - Your Booking Reference: ${Booking Reference}
>
> ---
>
> **Check-in**
> Look out for your white Rabbie's-branded mini-coach when you arrive. Your Driver-Guide will be waiting at the departure point and will check you in.
>
> **Your Luggage Allowance** [Luggage Graphic]
> Each passenger may bring:
> - One piece of luggage (up to 20kg), maximum dimensions 55cm x 45cm x [cut off in source — check attachment]
> - One small personal bag for items you'll need during the journey
> - ❗ Luggage exceeding these limits will not be accepted; please store any excess. Baggage limits are in place for the safety and comfort of all passengers.
>
> **What to Bring**
> - Your tour booking confirmation email, ready for check-in on arrival
> - A valid form of identification
> - Comfortable clothing and footwear
> - Weather-appropriate layers
> - Bottled water
> - Camera or phone for photos
>
> ---
>
> **Need Assistance?**
> If you have any questions before departure, please visit our FAQ page.

The luggage dimensions and a few line breaks got mangled in the copy-paste into Jira — worth pulling the actual attached .docx for the exact wording/branding rather than working from the ticket text alone.

### Acceptance criteria (Gherkin)

```gherkin
Feature: Pre departure email

  Scenario: Direct booking
    Given I have made a direct booking for any tour
    When the evening before the tour at 5pm comes
    Then the pre-departure email is sent that matches the template above
    And it contains the Rabbie's branding

  Scenario: Agent booking
    Given there is an agent or OTA booking
    When the night before the tour comes
    Then there is no pre-departure email sent
```

### Definition of Done

1. Built to requirements
2. Code reviewed (doc blocks added, standard route path used, PSR standards)
3. Works as intended (functional review)
4. Instructions provided if new/significantly changed feature
5. Deployed and tested on UAT
6. Time logged to the ticket
7. Accepted by Product Owner

