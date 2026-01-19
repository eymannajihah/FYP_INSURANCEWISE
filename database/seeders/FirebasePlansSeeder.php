<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Kreait\Firebase\Factory;

class FirebasePlansSeeder extends Seeder
{
    protected $database;

    public function __construct()
    {
        $factory = (new Factory)
            ->withServiceAccount(config('firebase.credentials.file'))
            ->withDatabaseUri(config('firebase.database.url'));

        $this->database = $factory->createDatabase();
    }

    public function run(): void
    {
        $plans = [

            'prulady' => [
                'name' => 'PRULady',
                'category' => 'Critical Illness',
                'overview' => 'PRULady is a gender-specific critical illness plan designed to protect women through every stage of life...',
                'key_features' => [
                    'Up to 260% total protection based on Basic Sum Assured (BSA)',
                    'Female-specific critical illness coverage',
                    'Carcinoma-in-Situ (CIS) coverage – 50% of BSA',
                    'Pregnancy Care & Baby Care Benefits – 10% of BSA each (up to age 50)',
                    'Fertility Care Benefit – up to 20% of BSA (max RM15,000 per life)',
                    'Recovery Benefit – up to 60% of BSA for 7 conditions',
                    'Wellness Care Benefit – reimburse up to RM250 per visit (max RM5,000 per life)',
                    'Mental and dermatology care support',
                ],
                'cash_rewards' => [
                    'Life Celebration Benefit – 3% of BSA (claimable up to 3 times)',
                    'Golden Cash Reward – 5% of total premiums paid at ages 60 and 65',
                    'Money Back Benefit – 100% premium refund at age 70',
                ],
                'eligibility' => 'Entry age: 19 – 45 years old, Coverage up to age 70, Applicable to women only',
                'fees' => 'Premium depends on age, health, coverage amount, and optional riders.',
                'brochure' => 'brochures/PRUMan-PRULady-Leaflet-ENG.pdf',
            ],

            'pruman' => [
                'name' => 'PRUMan',
                'category' => 'Critical Illness',
                'overview' => 'PRUMan is a gender-specific critical illness plan for men, providing protection against male-related illnesses...',
                'key_features' => [
                    'Up to 260% total protection based on Basic Sum Assured (BSA)',
                    'Male-specific critical illness coverage',
                    'Carcinoma-in-Situ (CIS) coverage – 50% of BSA (including Early Prostate Cancer)',
                    'Fertility Care Benefit – up to 20% of BSA (max RM15,000 per life, until age 45)',
                    'Recovery Benefit – up to 60% of BSA for 9 male-related conditions',
                    'Wellness Care Benefit – reimburse up to RM250 per visit (max RM5,000 per life)',
                    'Mental health & dermatology treatment coverage',
                ],
                'cash_rewards' => [
                    'Life Celebration Benefit – 3% of BSA (claimable up to 3 times)',
                    'Golden Cash Reward – 5% of total premiums paid at ages 60 and 65',
                    'Money Back Benefit – 100% premium refund at age 70',
                ],
                'eligibility' => 'Entry age: 19 – 65 years old, Coverage up to age 70, Applicable to men only',
                'fees' => 'Premium based on age, smoking status, coverage amount, and optional riders.',
                'brochure' => 'brochures/PRUMan-PRULady-Leaflet-ENG.pdf',
            ],

            'prumy_critical' => [
                'name' => 'PRUMy Critical Care',
                'category' => 'Critical Illness',
                'overview' => 'Provides financial support when critical illnesses strike, offering lump sum payments beyond medical costs...',
                'key_features' => [
                    'Covers up to 160 critical illness conditions',
                    'Early, intermediate, and late-stage coverage',
                    'Multiple claims up to 400% of rider sum assured',
                    'Re-diagnosis coverage for Cancer, Heart Attack, and Stroke (up to 200% per category)',
                    'Special Benefit: 20% payout for diabetic or joint-related conditions (one-time, up to RM100,000 lifetime)',
                    'Flexible plans starting from RM125 per month',
                    'Coverage extendable up to age 100 with auto-extension feature',
                ],
                'benefits' => [
                    'Early & Intermediate Stage: 50% payout of rider sum assured',
                    'Late Stage: 100% payout of rider sum assured',
                    'Multiple claims allowed across different illness categories',
                    'Re-diagnosis coverage for major illnesses before age 85',
                    'Special Benefit payout for diabetic or joint-related conditions',
                ],
                'eligibility' => 'Entry age: 1 – 60 years old, Coverage term up to 80 (extendable to 100), Available for Malaysians and PRs, Required documents: IC/Passport and Health Declaration',
                'fees' => 'Premiums depend on age, gender, coverage amount, health condition, smoker status.',
                'brochure' => 'brochures/PRUMy-Critical-Care-ELeaflet-ENG-FA.pdf',
            ],

            'pru_plus' => [
                'name' => 'PRUWith You Plus',
                'category' => 'Life Insurance',
                'overview' => 'A flexible investment-linked life insurance plan that grows with you and your loved ones...',
                'key_features' => [
                    'Sum Assured Booster – increases protection by 1% per year, up to 50% BSA',
                    'Automatic policy term extension up to age 100',
                    'Free child coverage from birth to age 7',
                    'Goal Achievement Benefit – RM500 cash reward for milestones',
                    'Comprehensive riders available',
                    'Investment-linked funds for long-term growth',
                ],
                'eligibility' => 'Entry age: 14 days – 70 years old, Coverage extends up to age 100, Available to Malaysians & PRs',
                'fees' => 'Charges depend on age, coverage amount, riders, and health status.',
                'brochure' => 'brochures/PRUWith-You-Plus-Digital-Flyer_ENG-Final.pdf',
            ],

            'pru_term' => [
                'name' => 'PRUTerm',
                'category' => 'Life Insurance',
                'overview' => 'Budget-friendly term life protection plan with lump-sum payment for death or TPD during coverage term.',
                'key_features' => [
                    'Affordable premiums for high protection value',
                    'Flexible policy term 5 years up to age 70',
                    'Covers death and TPD benefit',
                    'Minimum sum assured from RM100,000',
                    'Optional add-on plans/riders available',
                ],
                'eligibility' => 'Entry age: 16 – 65 years old, Coverage expiry age up to 70, Available to Malaysians & PRs',
                'fees' => 'Premiums based on age, sum assured, coverage term, smoker status, optional riders.',
                'brochure' => null,
            ],

            'pru_well' => [
                'name' => 'PRULive Well',
                'category' => 'Life Insurance',
                'overview' => 'Long-term life insurance plan providing financial protection in the event of disability or death. Offers guaranteed Monthly Income Benefit (MIB) and wellness rewards.',
                'key_features' => [
                    'Guaranteed Monthly Income Benefit (MIB) up to 20 years upon disability',
                    '50% of MIB when unable to perform 2 ADLs',
                    '100% of MIB when unable to perform at least 3 ADLs',
                    'Premium waiver during disability',
                    '100% refund of total premiums paid at maturity (less MIB paid)',
                    'Death benefit up to 250 times MIB',
                    'Large Sum Assured Discount (up to 12%)',
                    'Free-look period of 15 days',
                ],
                'eligibility' => 'Entry age: 19 – 65 years old, Coverage term up to age 100, Available for Malaysians & PRs',
                'fees' => 'Premium depends on age, coverage term, MIB amount, and optional riders.',
                'brochure' => 'brochures/PRULive-Well-Flyer-ENG.pdf',
            ],

            'prumillion_med_2' => [
                'name' => 'PRUMillion Med 2.0',
                'category' => 'Medical Insurance',
                'overview' => 'Comprehensive medical protection with high annual limits and flexible deductible & booster options.',
                'key_features' => [
                    'Annual limit: RM8 million',
                    'No lifetime limit',
                    'Flexible deductible options: RM500 – RM10,000',
                    'Booster add-on: additional RM10 million coverage',
                    'Hospital Daily Room & Board (R&B) and ICU coverage up to 150 days',
                    'Pre- and post-hospitalisation coverage',
                    'Outpatient cancer treatment and kidney dialysis',
                    'Outpatient rehabilitation and home nursing care',
                    'Preventive care benefit: up to RM1,000/year if no claims',
                ],
                'additional_benefits' => [
                    'Maternity complications coverage (up to 13 conditions with booster)',
                    'Comprehensive cancer care including genetic testing and follow-up',
                    'Emergency treatment for accidental injuries',
                    'Preventive care rewards for claim-free years',
                    'Emergency Medical Assistance and Expert Medical Opinion',
                ],
                'eligibility' => 'Entry age: 14 days – 70 years old, Coverage renewable up to age 100, Malaysians & PR holders, IC/Passport & Health Declaration required',
                'fees' => 'Premiums depend on age, gender, deductible, plan tier, optional boosters and riders',
                'brochure' => 'brochures/prumillion-med-2_0-leaflet-ENG.pdf',
            ],

            'prumillion_med_active' => [
                'name' => 'PRUMillion Med Active',
                'category' => 'Medical Insurance',
                'overview' => 'Health protection plan focusing on active lifestyle and preventive care.',
                'key_features' => [
                    'High annual coverage limit (varies by plan)',
                    'No lifetime limit on claims',
                    'No Claim Bonus (NCB) rewards for staying claim-free',
                    'Medical Booster for enhanced coverage when needed',
                    'Unlimited ICU coverage days',
                    'Flexible deductible options to manage premium costs',

                ],
                'eligibility' => 'Entry age: 14 days – 70 years old, Coverage renewable up to age 100, Available to Malaysians and Permanent Residents',
                'fees' => 'Age and gender, Selected coverage option, Chosen deductible level, Health and lifestyle declaration',
                'brochure' => 'brochures/PRUMillion-Med-Active-Brochure-ENG.pdf',
            ],

            'pruvalue_med' => [
                'name' => 'PRUValue Med',
                'category' => 'Medical Insurance',
                'overview' => 'Affordable hospitalisation and surgical plan providing essential medical protection.',
                'key_features' => [
                    'Comprehensive inpatient and surgical coverage at panel hospitals',
                    'Special lifetime limit (Med Value Point) of up to RM2 million depending on plan',
                    'After exceeding the Med Value Point, 80% of eligible costs continue to be covered',
                    'Choose your Room & Board rate — from RM150 to RM600 per day',
                    'Flexible Med Saver or Deductible options to reduce premiums',
                    'Pre- and post-hospitalisation coverage for continuous care',
                    'Cashless admission through Prudential’s medical card network',
                ],
                'eligibility' => 'Entry age: 14 days – 70 years old (next birthday), Coverage renewable up to age 100 (next birthday), Available to Malaysians and Permanent Residents',
                'fees' => 'Premium depends on age, coverage amount, R&B rate, deductible, and health status',
                'brochure' => null,
            ],
        ];

        foreach ($plans as $key => $data) {
            $this->database->getReference('plans/' . $key)
                ->set($data);
        }

        $this->command->info('All 9 plans have been added to Firebase successfully!');
    }
}
