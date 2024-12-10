<?php

namespace Database\Seeders;

use App\Enum\GeneralReportType;
use App\Models\Parameter;
use App\Models\Report;
use App\Models\ReportParameter;
use App\Models\ReportType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReportsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $reports = [
            // Infrastructure
            [
                'report_type' => GeneralReportType::INFRASTRUCTURE,
                'reports' => [
                    [
                        'name' => 'Infrastructure Report',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::INFRASTRUCTURE,
                        'report_url' => '/reports/ZRA/Infrastructure/infrastructure',
                        'parameters' => [Parameter::DYNAMIC_DATE]
                    ],
                    [
                        'name' => 'VAT Number of Bed Nights',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::INFRASTRUCTURE,
                        'report_url' => '/reports/ZRA/Infrastructure/vat_bed_nights',
                        'parameters' => [Parameter::DYNAMIC_DATE]
                    ],
                    [
                        'name' => 'Hotel Number of Bed Nights',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::INFRASTRUCTURE,
                        'report_url' => '/reports/ZRA/Infrastructure/hotel_bed_nights',
                        'parameters' => [Parameter::DYNAMIC_DATE]
                    ],
                    [
                        'name' => 'Petroleum',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::INFRASTRUCTURE,
                        'report_url' => '/reports/ZRA/Infrastructure/petroleum',
                        'parameters' => [Parameter::DYNAMIC_DATE]
                    ],
                ]
            ],
            // Property Tax
            [
                'report_type' => GeneralReportType::PROPERTY_TAX,
                'reports' => [
                    [
                        'name' => 'Property Tax Tax Payers',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::PROPERTY_TAX,
                        'report_url' => '/reports/ZRA/PropertyTax/property_tax_taxpayers',
                        'parameters' => [Parameter::DYNAMIC_DATE]
                    ],
                    [
                        'name' => 'Property Tax Paid Payments',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::PROPERTY_TAX,
                        'report_url' => '/reports/ZRA/PropertyTax/property_tax_paid_payments',
                        'parameters' => [Parameter::DYNAMIC_DATE, Parameter::REGION_NAME]
                    ],
                    [
                        'name' => 'Property Tax Unpaid Payments',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::PROPERTY_TAX,
                        'report_url' => '/reports/ZRA/PropertyTax/property_tax_unpaid_payments',
                        'parameters' => [Parameter::DYNAMIC_DATE, Parameter::REGION_NAME]
                    ],
                ]
            ],
            // Business
            [
                'report_type' => GeneralReportType::BUSINESS,
                'reports' => [
                    [
                        'name' => 'Business without Z-Number',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::BUSINESS,
                        'report_url' => '/reports/ZRA/Business/business_without_z_number',
                        'parameters' => [Parameter::DYNAMIC_DATE]
                    ],
                    [
                        'name' => 'Registrations',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::BUSINESS,
                        'report_url' => '/reports/ZRA/Business/business_registrations',
                        'parameters' => [Parameter::DYNAMIC_DATE]
                    ],
                    [
                        'name' => 'Renting Premises Report',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::BUSINESS,
                        'report_url' => '/reports/ZRA/TaxPayer/renting_premises_report',
                        'parameters' => [Parameter::DYNAMIC_DATE]
                    ],
                    [
                        'name' => 'Taxpayer for the Past Twelve Months',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::BUSINESS,
                        'report_url' => '/reports/ZRA/TaxPayer/tax_payer_for_the_past_twelve_months_report',
                        'parameters' => [Parameter::START_DATE, Parameter::END_DATE]
                    ],
                    [
                        'name' => 'Taxpayer By Tax Type',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::BUSINESS,
                        'report_url' => '/reports/ZRA/Business/business_by_tax_type',
                        'parameters' => [Parameter::TAX_TYPE, Parameter::DYNAMIC_DATE]
                    ],
                ]
            ],
            // Returns
            [
                'report_type' => GeneralReportType::RETURNS,
                'reports' => [
                    [
                        'name' => 'Hotel Report',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::RETURNS,
                        'report_url' => '/reports/ZRA/TaxPayer/hotel_reports',
                        'parameters' => [Parameter::START_DATE, Parameter::END_DATE]
                    ],
                    [
                        'name' => 'Expected Returns',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::RETURNS,
                        'report_url' => '/reports/ZRA/Returns/expected_returns_report',
                        'parameters' => [Parameter::START_DATE, Parameter::END_DATE]
                    ],
                    [
                        'name' => 'Returns By ZTN Number',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::RETURNS,
                        'report_url' => '/reports/ZRA/Returns/returns_by_ztn_number',
                        'parameters' => [Parameter::ZTN_NUMBER]
                    ],
                    [
                        'name' => 'Non Filers On Financial Month',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::RETURNS,
                        'report_url' => '/reports/ZRA/Returns/non_filer_returns_report',
                        'parameters' => [Parameter::FINANCIAL_MONTH]
                    ],
                    [
                        'name' => 'Importation Report',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::RETURNS,
                        'report_url' => '/reports/ZRA/Returns/importation_reports',
                        'parameters' => [Parameter::DYNAMIC_DATE]
                    ],
                    [
                        'name' => 'All Filed Tax Returns',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::RETURNS,
                        'report_url' => '/reports/ZRA/Returns/filed_tax_returns',
                        'parameters' => [Parameter::DYNAMIC_DATE]
                    ],
                    [
                        'name' => 'Filed Tax Returns By Department or Tax Region',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::RETURNS,
                        'report_url' => '/reports/ZRA/Returns/filed_tax_returns_by_department_region',
                        'parameters' => [Parameter::DYNAMIC_DATE, Parameter::DEPARTMENT, Parameter::TAX_REGION_NAME]
                    ],
                    //                    [
                    //                        'name' => 'Non Filers based on Financial Month',
                    //                        'has_parameter' => 1,
                    //                        'report_type_name' => GeneralReportType::RETURNS,
                    //                        'report_url' => '/reports/ZRA/Returns/non_filers_per_financial_month',
                    //                        'parameters' => [Parameter::FINANCIAL_YEAR, Parameter::FINANCIAL_MONTH]
                    //                    ],
                ]
            ],
            // Debt
            [
                'report_type' => GeneralReportType::DEBT,
                'reports' => [
                    [
                        'name' => 'Offence Report',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::DEBT,
                        'report_url' => '/reports/ZRA/Debt/offence_report',
                        'parameters' => [Parameter::DYNAMIC_DATE, Parameter::DEPARTMENT]
                    ],
                    [
                        'name' => 'Tax Clearance',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::DEBT,
                        'report_url' => '/reports/ZRA/Debt/clearance_report',
                        'parameters' => [Parameter::DYNAMIC_DATE]
                    ],
                    [
                        'name' => 'Debt Report',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::DEBT,
                        'report_url' => '/reports/ZRA/Debt/debt_report',
                        'parameters' => [Parameter::DYNAMIC_DATE]
                    ],
                ]
            ],
            // Land Lease
            [
                'report_type' => GeneralReportType::LAND_LEASE,
                'reports' => [
                    [
                        'name' => 'Land Lease Regional Report',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::LAND_LEASE,
                        'report_url' => '/reports/ZRA/land_lease/land_lease_regional_report',
                        'parameters' => [Parameter::START_DATE, Parameter::END_DATE]
                    ],
                    [
                        'name' => 'Land Lease Registration Report',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::LAND_LEASE,
                        'report_url' => '/reports/ZRA/land_lease/land_lease_registration_report',
                        'parameters' => [Parameter::START_DATE, Parameter::END_DATE]
                    ],
                ]
            ],
            // MVR
            [
                'report_type' => GeneralReportType::MVR,
                'reports' => [
                    [
                        'name' => 'De Registered Vehicles',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::MVR,
                        'report_url' => '/reports/ZRA/mvr/_de_registered_vehicles',
                        'parameters' => [Parameter::DYNAMIC_DATE]
                    ],
                    [
                        'name' => 'General New Vehicle Imported',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::MVR,
                        'report_url' => '/reports/ZRA/mvr/general_new_vehicle_imported',
                        'parameters' => [Parameter::DYNAMIC_DATE]
                    ],
                    [
                        'name' => 'Paid Registered Vehicles',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::MVR,
                        'report_url' => '/reports/ZRA/mvr/_paid_registered_vehicles',
                        'parameters' => [Parameter::DYNAMIC_DATE]
                    ],
                    [
                        'name' => 'Plate Report',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::MVR,
                        'report_url' => '/reports/ZRA/mvr/plate_reports',
                        'parameters' => [Parameter::DYNAMIC_DATE]
                    ],
                    [
                        'name' => 'Sales Of Plates And Stickers Update And Deregistered',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::MVR,
                        'report_url' => '/reports/ZRA/mvr/sales_of_plates_and_stickers_update_and_deregistered',
                        'parameters' => [Parameter::DYNAMIC_DATE]
                    ],
                    [
                        'name' => 'Total Cards Printed',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::MVR,
                        'report_url' => '/reports/ZRA/mvr/total_cards_printed',
                        'parameters' => [Parameter::DYNAMIC_DATE]
                    ],
                    [
                        'name' => 'Transferred Vehicle Report',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::MVR,
                        'report_url' => '/reports/ZRA/mvr/_transferred_vehicle_report',
                        'parameters' => [Parameter::DYNAMIC_DATE]
                    ],
                    [
                        'name' => 'Total Number of Driving License By Duration (Years)',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::MVR,
                        'report_url' => '/reports/ZRA/mvr/total_no_of_dl_by_duration',
                        'parameters' => [Parameter::DYNAMIC_DATE]
                    ],
                    [
                        'name' => 'Temporary Registered Vehicles to Mainland',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::MVR,
                        'report_url' => '/reports/ZRA/mvr/mvr_temporary_registrations_to_mainland',
                        'parameters' => [Parameter::DYNAMIC_DATE]
                    ],
                ]
            ],
            // Research
            [
                'report_type' => GeneralReportType::RESEARCH_REPORT,
                'reports' => [
                    [
                        'name' => 'Collection By Department',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::RESEARCH_REPORT,
                        'report_url' => '/reports/ZRA/Research/collection_by_department',
                        'parameters' => [Parameter::DYNAMIC_DATE]
                    ],
                    [
                        'name' => 'Collection By District',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::RESEARCH_REPORT,
                        'report_url' => '/reports/ZRA/Research/collection_by_district',
                        'parameters' => [Parameter::DYNAMIC_DATE]
                    ],
                    [
                        'name' => 'Collection By Region',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::RESEARCH_REPORT,
                        'report_url' => '/reports/ZRA/Research/collection_by_region',
                        'parameters' => [Parameter::DYNAMIC_DATE]
                    ],
                    [
                        'name' => 'Audited Cases',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::RESEARCH_REPORT,
                        'report_url' => '/reports/ZRA/Research/audited_cases_general',
                        'parameters' => [Parameter::DYNAMIC_DATE]
                    ],
                    [
                        'name' => 'Imported Petroleum Products',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::RESEARCH_REPORT,
                        'report_url' => '/reports/ZRA/Research/imported_petroleum_product',
                        'parameters' => [Parameter::DYNAMIC_DATE]
                    ],
                    [
                        'name' => 'Source Distribution by Department',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::RESEARCH_REPORT,
                        'report_url' => '/reports/ZRA/Research/monthly_distribution_source_by_department',
                        'parameters' => [Parameter::DYNAMIC_DATE]
                    ],
                    [
                        'name' => 'All Source Distributions',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::RESEARCH_REPORT,
                        'report_url' => '/reports/ZRA/Research/monthly_distribution_source_general',
                        'parameters' => [Parameter::DYNAMIC_DATE]
                    ],
                    [
                        'name' => 'All Source Distributions',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::RESEARCH_REPORT,
                        'report_url' => '/reports/ZRA/Research/number_of_taxpayers_in_departments_by_source',
                        'parameters' => [Parameter::DYNAMIC_DATE]
                    ],
                    [
                        'name' => 'Penalized Taxpayers',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::RESEARCH_REPORT,
                        'report_url' => '/reports/ZRA/Research/penalized_taxpayers',
                        'parameters' => [Parameter::DYNAMIC_DATE]
                    ],
                    [
                        'name' => 'Returns Submitted/Corrected',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::RESEARCH_REPORT,
                        'report_url' => '/reports/ZRA/Research/returns_submitted_corrected_correction',
                        'parameters' => [Parameter::DYNAMIC_DATE]
                    ],
                ]
            ],
            // Compliance
            [
                'report_type' => GeneralReportType::COMPLIANCE,
                'reports' => [
                    [
                        'name' => 'All Audits, Investigation & Verifications by ZTN Number',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::COMPLIANCE,
                        'report_url' => '/reports/ZRA/Compliance/compliance_by_ztn_number',
                        'parameters' => [Parameter::START_DATE, Parameter::END_DATE, Parameter::ZTN_NUMBER]
                    ],
                    [
                        'name' => 'Unpaid Audits, Investigation & Verifications by ZTN Number',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::COMPLIANCE,
                        'report_url' => '/reports/ZRA/Compliance/compliance_by_ztn_number_debt',
                        'parameters' => [Parameter::START_DATE, Parameter::END_DATE, Parameter::ZTN_NUMBER]
                    ],
                ]
            ],
            // Tax Claims
            [
                'report_type' => GeneralReportType::TAX_CLAIMS,
                'reports' => [
                    [
                        'name' => 'All Tax Claim Reports',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::TAX_CLAIMS,
                        'report_url' => '/reports/ZRA/TaxClaims/tax_claims_report_general',
                        'parameters' => [Parameter::DYNAMIC_DATE]
                    ],
                    [
                        'name' => 'All Tax Claims by ZTN Number',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::TAX_CLAIMS,
                        'report_url' => '/reports/ZRA/TaxClaims/tax_claims_report_by_taxpayer',
                        'parameters' => [Parameter::DYNAMIC_DATE, Parameter::ZTN_NUMBER]
                    ],
                ]
            ],
            // Relief
            [
                'report_type' => GeneralReportType::RELIEF,
                'reports' => [
                    [
                        'name' => 'Taxpayer Relief Report',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::RELIEF,
                        'report_url' => '/reports/ZRA/Relief/taxpayer_relief_report',
                        'parameters' => [Parameter::DYNAMIC_DATE, Parameter::ZTN_LOCATION_NUMBER, Parameter::RATE, Parameter::PROJECT_ID]
                    ],
                    [
                        'name' => 'Ceiling Report',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::RELIEF,
                        'report_url' => '/reports/ZRA/Relief/ceiling_report',
                        'parameters' => [Parameter::DYNAMIC_DATE]
                    ],
                    [
                        'name' => 'Relief Items',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::RELIEF,
                        'report_url' => '/reports/ZRA/Relief/relief_items',
                        'parameters' => [Parameter::DYNAMIC_DATE]
                    ],
                ]
            ],
            // Tax audit
            [
                'report_type' => GeneralReportType::TAX_AUDIT,
                'reports' => [
                    [
                        'name' => 'Audited Cases',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::TAX_AUDIT,
                        'report_url' => '/reports/ZRA/Research/audited_cases_general',
                        'parameters' => [Parameter::DYNAMIC_DATE]
                    ]
                ]
            ],
            // Tax Investigation
            [
                'report_type' => GeneralReportType::TAX_INVESTIGATION,
                'reports' => [
                    [
                        'name' => 'Investigation Cases',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::TAX_INVESTIGATION,
                        'report_url' => '/reports/ZRA/TaxInvestigation/investigation_cases_conducted',
                        'parameters' => [Parameter::DYNAMIC_DATE]
                    ],
                    [
                        'name' => 'Taxpayer Withholding Report',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::TAX_INVESTIGATION,
                        'report_url' => '/reports/ZRA/Research/taxpayer_withholding_report',
                        'parameters' => [Parameter::DYNAMIC_DATE, Parameter::ZTN_NUMBER]
                    ]
                ]
            ],
            // Disputes
            [
                'report_type' => GeneralReportType::DISPUTE,
                'reports' => [
                    [
                        'name' => 'Approved Disputes',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::DISPUTE,
                        'report_url' => '/reports/ZRA/Dispute/all_approved_disputes',
                        'parameters' => [Parameter::DYNAMIC_DATE]
                    ],
                    [
                        'name' => 'Pending Disputes',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::DISPUTE,
                        'report_url' => '/reports/ZRA/Dispute/all_pending_disputes',
                        'parameters' => [Parameter::DYNAMIC_DATE]
                    ]
                ]
            ],
            // DST
            [
                'report_type' => GeneralReportType::DST,
                'reports' => [
                    [
                        'name' => 'Registered Businesses',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::DST,
                        'report_url' => '/reports/ZRA/Dst/registered_businesses',
                        'parameters' => [Parameter::DYNAMIC_DATE, Parameter::DST_BUSINESS_TYPE]
                    ]
                ]
            ],
            // Report Register
            [
                'report_type' => GeneralReportType::REPORT_REGISTER,
                'reports' => [
                    [
                        'name' => 'Logged Reports',
                        'has_parameter' => 1,
                        'report_type_name' => GeneralReportType::REPORT_REGISTER,
                        'report_url' => '/reports/ZRA/ReportRegister/incidents_per_duration',
                        'parameters' => [Parameter::RG_CATEGORY_ID, Parameter::RG_SUB_CATEGORY_ID, Parameter::DYNAMIC_DATE]
                    ],
                ]
            ],
        ];

        DB::table('report_parameters')->truncate();
        DB::table('reports')->truncate();

        $this->call(ReportTypesSeeder::class);
        $this->call(ParametersSeeder::class);


        foreach ($reports as $i => $report) {
            $reportType = ReportType::select('id')
                ->where('name', $report['report_type'])
                ->first();

            if (!$reportType) {
                throw new \Exception('Missing report type');
            }

            foreach ($report['reports'] as $j => $r) {
                $code = strtolower($r['name']);
                // Replace spaces with hyphens
                $code = str_replace(' ', '-', $code);
                // Add report
                Report::updateOrCreate(
                    [
                        'name' => $r['name']
                    ],
                    [
                        'name' => $r['name'],
                        'has_parameter' => $r['has_parameter'],
                        'report_type_id' => $reportType->id,
                        'code' => $code,
                        'report_url' => $r['report_url']
                    ]
                );

                $reportData = Report::where('name', $r['name'])->first();

                if (!$reportData) {
                    throw new \Exception('Missing report saved data');
                }

                if (count($r['parameters'] ?? [])) {
                    foreach ($r['parameters'] as $parameter) {
                        $parameter = Parameter::where('code', $parameter)->firstOrFail();
                        // Add report parameters
                        ReportParameter::updateOrCreate([
                            'report_id' => $reportData->id,
                            'parameter_id' => $parameter->id,
                        ]);
                    }
                }
            }
        }
    }
}
