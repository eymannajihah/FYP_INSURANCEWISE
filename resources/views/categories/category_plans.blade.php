@extends('layouts.app')

@section('content')

<!-- Custom Styles -->
<style>
    /* Background wrapper, leaves space for navbar */
    .content-wrapper {
        background-image: url("{{ asset('image/requestform.jpeg') }}");
        background-repeat: no-repeat;
        background-position: center top;
        background-size: cover;
        min-height: 100vh;
        padding-bottom: 60px;
    }

   

    /* Plan Cards */
    .plan-card {
        transition: 0.3s;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        margin-bottom: 30px;
        background: #fff;
    }
    .plan-card:hover {
        transform: scale(1.05);
        box-shadow: 0 10px 30px rgba(0,0,0,0.25);
    }
    .plan-card img {
        width: 100%;
        height: 220px;
        object-fit: cover;
    }
    .plan-title {
        font-weight: 600;
        font-size: 20px;
        margin-top: 12px;
    }
    .plan-desc {
        font-size: 14px;
        color: #555;
        min-height: 50px;
        margin-bottom: 15px;
    }
    .btn-view {
        background-color: #dc3545;
        color: #fff;
        border-radius: 6px;
        padding: 8px 20px;
        font-weight: 600;
        transition: 0.3s;
        text-decoration: none;
        display: inline-block;
    }
    .btn-view:hover {
        background-color: #b71c1c;
        color: #fff;
    }

    /* Center the cards row */
    .plans-row {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 25px;
    }

    /* Back Button */
    .back-btn {
        display: inline-block;
        margin-top: 30px;
        padding: 10px 25px;
        border-radius: 6px;
        background: rgba(255,255,255,0.9);
        color: #dc3545;
        font-weight: 600;
        text-decoration: none;
        transition: 0.3s;
    }
    .back-btn:hover {
        background: #dc3545;
        color: #fff;
    }

    /* Glossary term highlight */
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
</style>

<div class="content-wrapper">
    <div class="container">

        <!-- Header -->
        <div class="header-section">
            <h1 class="text-capitalize">{{ $category }} Insurance Plans</h1>
            <p>Choose the best plan for your protection needs</p>
        </div>

        <!-- Plans Grid -->
        <div class="plans-row">
            @foreach ($plans as $id => $plan)
            <div style="max-width: 350px; flex: 1 1 auto;">
                <div class="plan-card text-center">
                    @if(!empty($plan['banner_image']))
                        <img src="{{ asset('storage/' . $plan['banner_image']) }}" alt="Banner">
                    @else
                        <img src="{{ asset('image/default.jpg') }}" alt="No image">
                    @endif
                    <div class="p-3">
                        <h4 class="plan-title">{{ $plan['name'] }}</h4>
                        <p class="plan-desc">
                            {{ $plan['highlight'] ?? ($plan['overview'] ? explode('.', $plan['overview'])[0] : 'Insurance plan for better protection.') }}
                        </p>
                        <a href="{{ route('plans.view', $id) }}" class="btn-view">View Details</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Back Button -->
        <div class="text-center">
            <a href="{{ route('dashboard') }}" class="back-btn">← Back to Dashboard</a>
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

<!-- Glossary Script -->
<script>
document.addEventListener('DOMContentLoaded', function () {
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
        "Optional Riders": {title:"Optional Riders",description:"Additional benefits that can be purchased to enhance coverage, such as critical illness, accidental benefit, and weekly income benefit."}
    };

    function escapeRegex(string) { return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'); }

    function applyGlossary() {
        const container = document.querySelector('.plans-row');
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
