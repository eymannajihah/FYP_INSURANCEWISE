@extends('layouts.app')

@section('content')



<style>
     /* Page consistent background like other pages */
    .page-background {
        min-height: 100vh;
        padding: 100px 0;
        background: url("{{ asset('image/page-background.jpeg') }}") no-repeat center center;
        background-size: cover;
    }

    .glossary-term {
        color: #2563eb;
        font-weight: 600;
        cursor: pointer;
        text-decoration: underline;
    }

    .glossary-modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
    }

    .glossary-modal-content {
        background-color: #ffffff;
        margin: 10% auto;
        padding: 24px;
        width: 420px;
        border-radius: 10px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.2);
    }

    .glossary-close-btn {
        margin-top: 15px;
        padding: 8px 16px;
        background-color: #2563eb;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
    }

    .header-section { 
        text-align: center; 
        color: #2c3e50;
        text-shadow: 0px 1px 3px rgba(0,0,0,0.2);
    }

    .plan-banner { 
        width: 100%; 
        max-height: 300px; 
        object-fit: cover; 
        border-radius: 10px; 
        margin-bottom: 20px; 
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .plan-title { 
        font-weight: 700; 
        margin-top: 10px; 
        font-size: 32px; 
    }

    .plan-category { 
        font-size: 18px; 
        color: #555; 
        margin-bottom: 15px; 
    }

    .plan-section { 
        margin-bottom: 30px; 
        background-color: rgba(255,255,255,0.95);
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .plan-section h4 { 
        font-weight: 600; 
        margin-bottom: 10px; 
        color: #e74c3c;
    }

    .plan-section ul { 
        padding-left: 20px; 
    }

    .plan-section ul li { 
        margin-bottom: 8px; 
    }

    .btn-download {
        background-color: #e74c3c;
        color: #fff;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        transition: 0.3s;
        display: inline-block;
        margin-top: 10px;
        text-decoration: none;
    }

    .btn-download:hover {
        background-color: #c0392b;
        color: #fff;
        text-decoration: none;
    }

    .btn-back {
        background-color: #3490dc;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        margin-bottom: 20px;
        display: inline-block;
    }

    .btn-back:hover {
        background-color: #2779bd;
        color: white;
        text-decoration: none;
    }
</style>

<div class="page-background">
    <div class="container">

        <!-- Back to category button -->
        <a href="{{ route('categories.view', strtolower($plan['category'])) }}" class="btn-back">&larr; Back to {{ $plan['category'] }}</a>

        <div class="header-section">
            <h1 class="plan-title">{{ $plan['name'] }}</h1>
            <p class="plan-category">{{ $plan['category'] }} Insurance Plan</p>

            @if(!empty($plan['banner_image']))
            <img src="{{ $plan['banner_image'] }}" alt="Banner"  class="plan-banner">
            @endif
        </div>

        <div class="glossary-content">

            @php
                function bulletPoints($text) {
                    if (!$text) return [];
                    $lines = array_filter(array_map('trim', preg_split("/[\r\n]+/", $text)));
                    return $lines;
                }
            @endphp

            @if(!empty($plan['overview']))
            <div class="plan-section">
                <h4>Overview</h4>
                <p>{{ $plan['overview'] }}</p>
            </div>
            @endif

            @foreach(['features' => 'Features', 'eligibility' => 'Eligibility', 'fees' => 'Fees & Charges', 'cash_rewards' => 'Cash Rewards', 'additional_benefits' => 'Additional Benefits', 'disclaimers' => 'Important Notes & Disclaimers'] as $field => $label)
                @if(!empty($plan[$field]))
                    <div class="plan-section">
                        <h4>{{ $label }}</h4>
                        <ul>
                            @foreach(bulletPoints($plan[$field]) as $line)
                                <li>{{ $line }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            @endforeach

            @if(!empty($plan['pidm']))
            <div class="plan-section">
                <h4>Protection by PIDM</h4>
                <p>{{ $plan['pidm'] }}</p>
            </div>
            @endif
<div class="plan-section">
    <h4>Brochure</h4>
  @if(!empty($plan['brochure']))
    <a href="{{ $plan['brochure'] }}" target="_blank" class="btn-download">
        Download Brochure
    </a>
@else
    <p>Brochure Not Available</p>
@endif

</div>


        </div>
    </div>
</div>

<!-- Glossary Modal -->
<div id="glossaryModal" class="glossary-modal">
    <div class="glossary-modal-content">
        <h4 id="glossaryTitle"></h4>
        <p id="glossaryDescription"></p>
        <button class="glossary-close-btn" onclick="closeGlossaryModal()">Close</button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Glossary data (unchanged)
    const glossaryData = {
        "Med Value Point": {title: "Med Value Point", description: "A special lifetime limit on total medical claims. After reaching this limit, the plan continues to cover 80% of eligible costs beyond it."},
        "Med Saver": {title: "Med Saver", description: "A fixed amount you must pay out of pocket per disability before your insurance coverage begins."},
        "Premium": {title: "Premium", description: "The amount you regularly pay (e.g., monthly or yearly) to keep your insurance coverage active."},
        "Room & Board": {title: "Room & Board", description: "The daily hospital accommodation cost covered by the plan (e.g., RM150–RM600 per day)."},
         "Deductible": {title:"Deductible",description:"A fixed amount you must pay out of pocket before your insurance plan starts paying for covered expenses."},
    "Lifetime Renewable": {title:"Lifetime Renewable",description:"Means the plan can be kept active up to a specified age (like 100) as long as you keep paying the premiums."},
    "Coverage Limit": {title:"Coverage Limit / Multiple Claims",description:"The maximum coverage for critical illness conditions. Multiple claims allowed up to a specified percentage of rider sum assured."},
    "No Claim Bonus": {title:"No Claim Bonus (NCB)",description:"A reward for staying claim-free during the policy year. May increase future coverage or reduce premiums."},
    "Medical Booster": {title:"Medical Booster",description:"An optional feature that increases coverage temporarily or permanently for specific treatments."},
    "Lifetime medical protection": {title:"Lifetime Medical Protection",description:"The plan does not cap total benefits you can claim during your lifetime. There is no lifetime limit on claims."},
    "Annual coverage limit": {title:"Annual Coverage Limit",description:"The maximum amount the plan will pay for medical expenses per policy year."},
    "PRUMillion Med Booster 2.0": {title:"PRUMillion Med Booster 2.0",description:"Optional add-on that instantly increases your coverage, e.g., additional RM10 million coverage for medical expenses."},
    "Pre-hospitalisation": {title:"Pre-Hospitalisation Coverage",description:"Medical expenses incurred before hospital admission, such as tests or consultations."},
    "Post-hospitalisation": {title:"Post-Hospitalisation Coverage",description:"Medical care after hospital discharge. Duration depends on condition severity and booster coverage."},
    "Rider Sum Assured": {title:"Rider Sum Assured",description:"Maximum amount the critical illness rider will pay per claim. Multiple claims may be allowed up to a percentage of this amount."},
    "Special Benefit": {title:"Special Benefit",description:"One-time payout for specific conditions (like diabetic or joint-related), up to a capped amount. Does not reduce rider sum assured."},
    "Auto-Extension Feature": {title:"Auto-Extension Feature",description:"Automatically extends the coverage term beyond the original plan end age without buying a new policy."},
    "Basic Sum Assured": {title:"Basic Sum Assured (BSA)",description:"The guaranteed amount your plan pays in the event of a claim. Percentages of BSA are used for specific benefits like CIS, Pregnancy Care, Fertility Care, etc."},
    "Golden Cash Reward": {title:"Golden Cash Reward",description:"Reward based on total premiums paid at specified ages, e.g., 60 and 65."},
    "Money Back Benefit": {title:"Money Back Benefit",description:"Refund of premiums at a certain age, e.g., age 70."},
    "Life Celebration Benefit": {title:"Life Celebration Benefit",description:"Cash reward for major life milestones like marriage, promotion, or childbirth."},
    "Monthly Income Benefit": {title:"Monthly Income Benefit (MIB)",description:"Guaranteed monthly payment if the insured cannot perform certain Activities of Daily Living (ADLs) due to disability. Amount and duration depend on the plan."},
    "Activities of Daily Living": {title:"Activities of Daily Living (ADL)",description:"Basic self-care tasks used to assess disability: Transfer, Mobility, Dressing, Eating, Bathing/Washing, and Continence."},
    "Premium Waiver": {title:"Premium Waiver",description:"If the insured becomes disabled, premium payments are waived, but coverage continues without additional cost."},
    "Free-Look Period": {title:"Free-Look Period",description:"Time frame after purchasing the policy during which the policyholder can review and cancel the policy for a full refund."},
    "Riders": {title:"Riders",description:"Optional add-ons that enhance your coverage, including medical, critical illness, accidental, payor, and mum & baby coverage."},
    "Investment-Linked Funds": {title:"Investment-Linked Funds",description:"Part of your premiums are invested in funds that can grow over time, providing potential returns in addition to insurance protection."},
    "Term Life Insurance": {title:"Term Life Insurance",description:"A life insurance plan that provides coverage for a specific period. Beneficiaries receive a lump-sum payment if the insured passes away or suffers TPD during the term."},
    "Total Permanent Disability": {title:"Total Permanent Disability (TPD)",description:"A condition where the insured becomes permanently unable to work due to illness or injury. The plan pays a lump sum in this case."},
    "Sum Assured": {title:"Sum Assured",description:"The guaranteed amount your insurance company will pay in the event of death or TPD, based on the coverage selected."},
    "Optional Riders": {title:"Optional Riders",description:"Additional benefits that can be purchased to enhance coverage, such as critical illness, accidental benefit, and weekly income benefit."},
    "Lumpsum": {title:"Lump sum",description:"one-time payment of a large amount of money"}
    };

    function escapeRegex(string) { return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'); }

    function applyGlossary() {
        const container = document.querySelector('.glossary-content');
        if (!container) return;
        Object.keys(glossaryData).forEach(term => {
            const escapedTerm = escapeRegex(term);
            const regex = new RegExp(escapedTerm, 'gi');
            container.innerHTML = container.innerHTML.replace(regex, `<span class="glossary-term" data-term="${term}">$&</span>`);
        });
        bindGlossaryClicks();
    }

    function bindGlossaryClicks() {
        document.querySelectorAll('.glossary-term').forEach(el => {
            el.addEventListener('click', function () {
                const key = this.dataset.term;
                document.getElementById('glossaryTitle').innerText = glossaryData[key].title;
                document.getElementById('glossaryDescription').innerText = glossaryData[key].description;
                document.getElementById('glossaryModal').style.display = 'block';
            });
        });
    }

    function closeGlossaryModal() {
        document.getElementById('glossaryModal').style.display = 'none';
    }

    window.closeGlossaryModal = closeGlossaryModal;

    applyGlossary();
});
</script>

@endsection
