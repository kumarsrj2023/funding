<?php

namespace App\Helpers;

use App\Models\AgreementToSendOut;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use App\Models\BusinessInfo;
use App\Models\CompleteOnceFunded;
use App\Models\Lender;
use App\Models\PreSendingOutTheAgreement;
use App\Models\PriceModel;
use App\Models\SifClient;
use App\Models\SifInvoice;
use App\Models\SifVerificationAndCompletion;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use PhpOffice\PhpWord\Style\Font as StyleFont;


class DocumentService
{
    public static function createCommiteePaperDoc($id, $loanId)
    {
        try {
            $business_info = BusinessInfo::where('id', $id)->first();
            $loanInfo = DB::table('wp_loan_info')->where('id', $loanId)->first();

            $businessInfo = $business_info;
            $loanInfo = $loanInfo ?: [];

            $wpIntroducersInfo = [];
            $bcaPaymentFrequencyTypes = [];
            $bcaRePaymentTypes = [];
            $gaCreditSafe = [];
            $gaCreditSafeShareHolders = [];
            $directors = [];
            $priceModelData = [];
            $gaCreditSafeCountyCourtJudgements = [];

            if ($loanInfo) {
                $wpIntroducersInfo = DB::table('wp_introducers_info')
                    ->where('id', $loanInfo->broker_id)
                    ->first() ?: [];

                $bcaPaymentFrequencyTypes = DB::table('bca_payment_frequency_types')
                    ->where('id', $loanInfo->bca_payment_frequency_type_id)
                    ->first() ?: [];

                $bcaRePaymentTypes = DB::table('bca_repayment_types')
                    ->where('id', $loanInfo->bca_repayment_type_id)
                    ->first() ?: [];

                $wpCommitteePaper = DB::table('wp_committee_paper')->where('wp_loan_info_id', $loanInfo->id)->where('wp_business_info_id', $id)->first() ?: [];
            }

            // Other queries
            $gaCreditSafe = DB::table('ga_credit_safe')
                ->where('id', $business_info->ga_credit_safe_id)
                ->first() ?: [];

            $gaCreditSafeShareHolders = DB::table('ga_credit_safe_share_holders')
                ->where('ga_credit_safe_id', $business_info->ga_credit_safe_id)
                ->get()->toArray() ?: [];

            $directors = DB::table('wp_director_info')
                ->where('wp_business_info_id', $id)
                ->orderBy('id', 'asc')
                ->get()->toArray() ?: [];

            $priceModelData = PriceModel::where('wp_business_info_id', $id)
                ->first() ?: [];

            $gaCreditSafeCountyCourtJudgements = DB::table('ga_credit_safe_county_court_judgements')
                ->where('ga_credit_safe_id', $business_info->ga_credit_safe_id)
                ->get();

            // $gaCreditSafeCountyCourtJudgements = DB::table('ga_credit_safe_county_court_judgements')
            //     ->where('ga_credit_safe_id', 176)
            //     ->get() ?: [];

            $directorIds = array_column($directors, 'id');
            $propertiesAndAssets = DB::table('wp_director_properties_and_other_assets')
                ->whereIn('wp_director_info_id', $directorIds)
                ->orderBy('wp_director_info_id', 'asc')
                ->get()
                ->groupBy('wp_director_info_id');

            foreach ($directors as &$director) {
                $director->properties_and_other_assets = $propertiesAndAssets->get($director->id, []);
            }
            unset($director);


            $sectionStyle = [
                'marginLeft' => 500,
                'marginRight' => 500,
                'marginTop' => 500,
                'marginBottom' => 500,
            ];

            // Define table style
            $tableStyle = [
                // 'width' => 100 * 50, // 100% of the document width
                // 'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::PERCENT,
                'borderSize' => 6,
                'borderColor' => '000000',
                'cellMargin' => 80,
            ];

            $headerCellStyle = [
                'bgColor' => '1F497D',
                'alignment' => 'center',
            ];

            $labelCellStyle = [
                'bgColor' => 'D9E1F2',
                'alignment' => 'center',
            ];

            $labelTextStyle = [
                'bold' => true,
            ];

            $headerTextStyle = [
                'bold' => true,
                'color' => 'FFFFFF',
            ];

            $paragraphStyle = [
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
            ];

            $a4WidthInTwips = 11906;
            $leftMargin = 500;
            $rightMargin = 500;

            $totalAvailableWidth = $a4WidthInTwips - $leftMargin - $rightMargin;

            $headerCellValue = $totalAvailableWidth;
            $subHeaderCellValue = $totalAvailableWidth / 2;
            $cellValue = $totalAvailableWidth / 4;

            $phpWord = new PhpWord();
            $section = $phpWord->addSection($sectionStyle);

            $table = $section->addTable($tableStyle);

            $table->addRow();
            $cell = $table->addCell($headerCellValue, ['gridSpan' => 4] + $headerCellStyle);
            $cell->addText('SECTION ONE: Company and Application Details', $headerTextStyle, $paragraphStyle);


            $table->addRow();
            $table->addCell($subHeaderCellValue, ['gridSpan' => 2] + $headerCellStyle)->addText('Company Key Details', $headerTextStyle, $paragraphStyle);
            $table->addCell($subHeaderCellValue, ['gridSpan' => 2] + $headerCellStyle)->addText('Application Key Details', $headerTextStyle, $paragraphStyle);

            $table->addRow();
            $table->addCell($cellValue, $labelCellStyle)->addText('Business Name', $labelTextStyle);
            $table->addCell($cellValue)->addText($gaCreditSafe->compSum_businessName ?? '');
            $table->addCell($cellValue, $labelCellStyle)->addText('Introduction', $labelTextStyle);
            $table->addCell($cellValue)->addText($wpIntroducersInfo->broker_name ?? "");

            $table->addRow();
            $table->addCell($cellValue, $labelCellStyle)->addText('Company Number', $labelTextStyle);
            $table->addCell($cellValue)->addText($gaCreditSafe->compSum_companyRegistrationNumber ?? '');
            $table->addCell($cellValue, $labelCellStyle)->addText('Amount Funding', $labelTextStyle);
            $table->addCell($cellValue)->addText(!empty($loanInfo->advance_requested) ? Helper::format_currency($loanInfo->advance_requested) : '');

            $table->addRow();
            $table->addCell($cellValue, $labelCellStyle)->addText('Registered Address', $labelTextStyle);
            $table->addCell($cellValue)->addText(Helper::escapeXmlText($gaCreditSafe->compIdentiBasicInfo_contactAddress_simpleValue ?? ''));
            $table->addCell($cellValue, $labelCellStyle)->addText(Helper::escapeXmlText('Servicing & Administration Fee'), $labelTextStyle);
            $table->addCell($cellValue)->addText(!empty($priceModelData->arrangement_fee_excl_VAT) ? (Helper::format_currency($priceModelData->arrangement_fee_excl_VAT) . ' + VAT') : Helper::format_currency(0));

            $table->addRow();
            $table->addCell($cellValue, $labelCellStyle)->addText('Date Of Incorporation', $labelTextStyle);
            $table->addCell($cellValue)->addText(!empty($gaCreditSafe->compIdentiBasicInfo_companyRegistrationDate) ? \Carbon\Carbon::parse($gaCreditSafe->compIdentiBasicInfo_companyRegistrationDate)->format('d-m-Y') : '');
            $table->addCell($cellValue, $labelCellStyle)->addText('Multiple', $labelTextStyle);
            $table->addCell($cellValue)->addText(!empty($priceModelData->multiple) ? number_format((float)$priceModelData->multiple, 2, '.', '') . 'x' : '');

            $table->addRow();
            $table->addCell($cellValue, $labelCellStyle)->addText('Website', $labelTextStyle);
            $table->addCell($cellValue)->addText($wpCommitteePaper->website ?? '');
            $table->addCell($cellValue, $labelCellStyle)->addText('Total Repayable', $labelTextStyle);
            $table->addCell($cellValue)->addText(!empty($priceModelData->total_repayable) ? Helper::format_currency($priceModelData->total_repayable) : '');

            $table->addRow();
            $table->addCell($cellValue, ['vMerge' => 'restart'] + $labelCellStyle)->addText(Helper::escapeXmlText('Shareholding & Directors'), $labelTextStyle);

            $shareHolderTextRun = $table->addCell($cellValue);

            if (count($gaCreditSafeShareHolders) > 0) {
                foreach ($gaCreditSafeShareHolders as $index => $shareHolder) {
                    $shareHolderTextRun->addText('Shareholders ' . ($index + 1) . ': ' . $shareHolder->name . ' (' . $shareHolder->percent_shares_held . '%)');
                }
            }

            $table->addCell($cellValue, $labelCellStyle)->addText('Allocation Of The Funds', $labelTextStyle);
            $table->addCell($cellValue)->addText($wpCommitteePaper->allocation_of_the_funds ?? '');

            $table->addRow();
            $table->addCell($cellValue, ['vMerge' => 'continue']);
            $directorsTextRun = $table->addCell($cellValue);

            if (count($directors) > 0) {
                foreach ($directors as $index => $director) {
                    $directorsTextRun->addText('Director ' . ($index + 1) . ': ' . $director->name);
                }
            }

            $table->addCell($cellValue, $labelCellStyle)->addText('Personal Guarantee(s)', $labelTextStyle);
            $table->addCell($cellValue)->addText($wpCommitteePaper->personal_guarantees ?? '');


            $table->addRow();
            $table->addCell($cellValue, ['vMerge' => 'restart'] + $labelCellStyle)->addText('How They Make Their Money', $labelTextStyle);
            $table->addCell($cellValue, ['vMerge' => 'restart'])->addText($wpCommitteePaper->how_they_make_their_money ?? '');
            $table->addCell($cellValue, $labelCellStyle)->addText('IRR', $labelTextStyle);

            $introducerTextRun = $table->addCell($cellValue)->addTextRun();
            $introducerTextRun->addText('Pre Introducer: ' . Helper::format_currency($priceModelData->pre_introducer_IRR ?? '0.00'));
            $introducerTextRun->addTextBreak();
            $introducerTextRun->addText('Post Introducer: ' . Helper::format_currency($priceModelData->post_introducer_IRR ?? '0.00'));

            $table->addRow();
            $table->addCell($cellValue, ['vMerge' => 'continue']);
            $table->addCell($cellValue, ['vMerge' => 'continue']);
            $table->addCell($cellValue, $labelCellStyle)->addText('Why IRR Chosen', $labelTextStyle);
            $table->addCell($cellValue)->addText($wpCommitteePaper->why_irr_chosen ?? '');

            $table->addRow();
            $table->addCell($cellValue, ['vMerge' => 'continue']);
            $table->addCell($cellValue, ['vMerge' => 'continue']);
            $table->addCell($cellValue, $labelCellStyle)->addText('Duration', $labelTextStyle);
            $table->addCell($cellValue)->addText(!empty($priceModelData->duration) ? $priceModelData->duration . ' Weeks' : '');

            $table->addRow();
            $table->addCell($cellValue, ['vMerge' => 'continue']);
            $table->addCell($cellValue, ['vMerge' => 'continue']);
            $table->addCell($cellValue, $labelCellStyle)->addText('How Long Until Breakeven', $labelTextStyle);
            $table->addCell($cellValue)->addText(!empty($priceModelData->how_long_until_breakeven) ? ($priceModelData->how_long_until_breakeven . ' Weeks') : '');

            $table->addRow();
            $table->addCell($cellValue, ['vMerge' => 'continue']);
            $table->addCell($cellValue, ['vMerge' => 'continue']);
            $table->addCell($cellValue, $labelCellStyle)->addText('Rate Of Income', $labelTextStyle);
            $table->addCell($cellValue)->addText(!empty($priceModelData->rate_of_income) ? ($priceModelData->rate_of_income . '%') : '');

            $table->addRow();
            $table->addCell($cellValue, ['vMerge' => 'continue']);
            $table->addCell($cellValue, ['vMerge' => 'continue']);
            $table->addCell($cellValue, $labelCellStyle)->addText('Applicable commission %', $labelTextStyle);
            $table->addCell($cellValue)->addText(!empty($priceModelData->commission) ? ($priceModelData->commission . '%') : '');

            $table->addRow();
            $table->addCell($cellValue, ['vMerge' => 'continue']);
            $table->addCell($cellValue, ['vMerge' => 'continue']);
            $table->addCell($cellValue, $labelCellStyle)->addText('Commission payable', $labelTextStyle);
            $commissionPayble = !empty($priceModelData->commission) && !empty($priceModelData->advance_requested) ? ($priceModelData->advance_requested * ($priceModelData->commission / 100)) : '';
            $table->addCell($cellValue)->addText(Helper::format_currency($commissionPayble));

            $table->addRow();
            $table->addCell($cellValue, $labelCellStyle)->addText('Creditsafe Status', $labelTextStyle);
            $table->addCell($cellValue)->addText($gaCreditSafe->compSum_companyStatus_description ?? '');
            $table->addCell($cellValue, $labelCellStyle)->addText('Repayment Frequency', $labelTextStyle);
            $table->addCell($cellValue)->addText($bcaPaymentFrequencyTypes->type ?? '');

            $table->addRow();
            $table->addCell($cellValue, $labelCellStyle)->addText('Active CCJ’s', $labelTextStyle);
            $tableCell = $table->addCell($cellValue);

            $textRun = $tableCell->addTextRun();
            if (count($gaCreditSafeCountyCourtJudgements) > 0) {
                foreach ($gaCreditSafeCountyCourtJudgements as $index => $ccj) {

                    if ($ccj->court) {
                        $textRun->addText('Court: ' . Helper::escapeXmlText($ccj->court));
                        $textRun->addTextBreak();
                    }

                    if ($ccj->ccjDate) {
                        $formattedDate = date('d-m-Y', strtotime($ccj->ccjDate));
                        $textRun->addText('Date: ' . $formattedDate);
                        $textRun->addTextBreak();
                    }

                    if ($ccj->ccjAmount) {
                        $textRun->addText('Amount: ' . $ccj->ccjAmount);
                        $textRun->addTextBreak();
                    }

                    if ($ccj->caseNumber) {
                        $textRun->addText('Case Number: ' . $ccj->caseNumber);
                        $textRun->addTextBreak();
                    }

                    if ($ccj->ccjStatus) {
                        $textRun->addText('Status: ' . $ccj->ccjStatus);
                        $textRun->addTextBreak();
                    }

                    if ($ccj->incomingRecordDetails) {
                        $textRun->addText('Details: ' . Helper::escapeXmlText($ccj->incomingRecordDetails));
                        $textRun->addTextBreak();
                    }

                    if ($index !== count($gaCreditSafeCountyCourtJudgements) - 1) {
                        $textRun->addText('--------------------------------');
                        $textRun->addTextBreak();
                    }
                }
            } else {
                $textRun->addText('None');
            }

            $table->addCell($cellValue, $labelCellStyle)->addText('Repayment Type', $labelTextStyle);
            $table->addCell($cellValue)->addText(!empty($bcaRePaymentTypes->type) ? $bcaRePaymentTypes->type : '');

            $table->addRow();
            $table->addCell($cellValue, $labelCellStyle)->addText('Weighted Scorecard', $labelTextStyle);
            $table->addCell($cellValue)->addText($wpCommitteePaper->weighted_scorecard ?? '');
            $table->addCell($cellValue, $labelCellStyle)->addText('Fixed Repayment Amount', $labelTextStyle);
            $table->addCell($cellValue)->addText(!empty($priceModelData->fixed_repayment_amount) ? Helper::format_currency($priceModelData->fixed_repayment_amount) : Helper::format_currency(0));

            // Second section table
            $section_two = $phpWord->addSection($sectionStyle);
            $second_section_table = $section_two->addTable($tableStyle);

            $fixedColumnWidth = 2000;
            $directorCount = count($directors);

            $remainingWidth = $totalAvailableWidth - $fixedColumnWidth;
            $directorCellWidth = $directorCount > 0 ? $remainingWidth / $directorCount : 0;

            $second_section_table->addRow();
            $second_section_table->addCell($totalAvailableWidth, ['gridSpan' => $directorCount + 1] + $headerCellStyle)
                ->addText('SECTION TWO: Director and Homeownership', $headerTextStyle, $paragraphStyle);

            $second_section_table->addRow();
            $second_section_table->addCell($fixedColumnWidth, $headerCellStyle)
                ->addText('Director', $headerTextStyle, $paragraphStyle);

            if ($directorCount > 0) {
                foreach ($directors as $index => $shareHolder) {
                    $second_section_table->addCell($directorCellWidth, $headerCellStyle)
                        ->addText('Director #' . ($index + 1), $headerTextStyle, $paragraphStyle);
                }
            }

            $fields = [
                'Full Name' => 'name',
                'Date Of Birth' => 'date_of_birth',
                'Nationality' => 'nationality',
                'CIFAS Return' => 'cifas_return',
                'Appointed Date' => 'date_appointed',
                'Transunion' => 'transunion',
                'Home Address' => 'address_simple_value'
            ];

            foreach ($fields as $label => $property) {
                $second_section_table->addRow();
                $second_section_table->addCell($fixedColumnWidth, $labelCellStyle)
                    ->addText($label, $labelTextStyle);

                if ($directorCount > 0) {
                    foreach ($directors as $director) {
                        $value = $director->{$property} ?? '';

                        if ($property === 'date_of_birth' && !empty($value)) {
                            $value = Carbon::parse($value)->format('d-m-Y');
                        }

                        if ($property === 'date_appointed' && !empty($value)) {
                            $value = Carbon::parse($value)->format('d-m-Y');
                        }

                        if ($property === 'address_simple_value' && !empty($value)) {
                            $value = Helper::escapeXmlText($value);
                        }

                        $second_section_table->addCell($directorCellWidth)->addText($value);
                    }
                }
            }

            // Section Three table
            $section_three = $phpWord->addSection($sectionStyle);
            $section_three_table = $section_three->addTable($tableStyle);

            $headerCellValue = $totalAvailableWidth;
            $fixedColumnWidth = 3000;
            $cellValue = $totalAvailableWidth - $fixedColumnWidth;

            $section_three_table->addRow();
            $section_three_table->addCell($headerCellValue, ['gridSpan' =>  2] + $headerCellStyle)->addText('Assets', $headerTextStyle, $paragraphStyle);

            if ($directorCount > 0) {
                foreach ($directors as $index => $director) {
                    $section_three_table->addRow();
                    $section_three_table->addCell($fixedColumnWidth)->addText('Director ' . $index + 1, $labelTextStyle);

                    $tableCell = $section_three_table->addCell($directorCellWidth);

                    if (count($director->properties_and_other_assets) > 0) {
                        $total_estimated_value = 0;

                        foreach ($director->properties_and_other_assets as $i => $assets) {
                            $textRun = $tableCell->addTextRun();

                            $textRun->addText('Property ' . ($i + 1) . ',', ['bold' => true]);
                            $textRun->addText(Helper::escapeXmlText($assets->property_address_and_assets ?? ''));
                            $textRun->addText(!empty($assets->estimated_value) ? 'Value: ' . Helper::format_currency($assets->estimated_value) : '');

                            if (!empty($assets->estimated_value)) {
                                $total_estimated_value += floatval($assets->estimated_value);
                            }
                        }
                        $textRun->addTextBreak(2);
                        $textRun->addText('Total Estimated Value:  ', ['bold' => true]);
                        $textRun->addText(!empty($total_estimated_value) ? Helper::format_currency($total_estimated_value) : '');
                    }
                }
            }

            // Section four table
            $section_four = $phpWord->addSection($sectionStyle);
            $section_four_table = $section_four->addTable($tableStyle);

            $headerCellValue = $totalAvailableWidth;
            $fixedColumnWidth = 3000;
            $cellValue = $totalAvailableWidth - $fixedColumnWidth;

            $section_four_table->addRow();
            $section_four_table->addCell($headerCellValue, ['gridSpan' =>  2] + $headerCellStyle)->addText('SECTION THREE: Cashflows and Payment Behaviour', $headerTextStyle, $paragraphStyle);

            $fields = [
                'Cashflow Source' => 'name',
                'Avg. Monthly Revenue' => 'date_of_birth',
                'Avg. Operating Before Tax' => 'nationality',
                'Avg. Operating After Tax' => 'cifas_return',
                'Avg. Operating After Tax' => 'appointed_date',
                'Bounced Payments' => 'transunion',
                'Tax Repayments' => 'home_address',
                'Bank Balance Overdraft' => 'home_address',
                'Key Takeaways' => 'home_address',
            ];

            foreach ($fields as $label => $property) {
                $section_four_table->addRow();
                $section_four_table->addCell($fixedColumnWidth)->addText($label, $labelTextStyle);
                $section_four_table->addCell($cellValue)->addText('');
            }

            // Save document
            $fileName = strtoupper($business_info->business_name) . ' - Committee Paper.docx';
            $tempFile = tempnam(sys_get_temp_dir(), $fileName);

            $phpWordWriter = IOFactory::createWriter($phpWord, 'Word2007');
            $phpWordWriter->save($tempFile);

            return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
        } catch (Exception $e) {
            dd($e);
            Log::error('Error creating document: ' . $e->getMessage());
            return redirect()->back()->with('message', 'Creating document errors.');
        }
    }

    public static function createFundingChecklistDoc($id)
    {
        try {
            if (empty($id)) {
                return redirect()->route('home');
            }

            $business_info = BusinessInfo::where('id', $id)->first();

            if (empty($business_info)) {
                return redirect()->route('home');
            }

            // Fetch existing checklist items for this business
            $existingChecklistItems = PreSendingOutTheAgreement::where('wp_business_info_id', $id)->get()->toArray();
            $existingAgreementItems = AgreementToSendOut::where('wp_business_info_id', $id)->get()->toArray();
            $existingOnceFundedItems = CompleteOnceFunded::where('wp_business_info_id', $id)->get()->toArray();

            $sectionStyle = [
                'marginLeft' => 500,
                'marginRight' => 500,
                'marginTop' => 500,
                'marginBottom' => 500,
            ];

            $tableStyle = [
                'borderSize' => 6,
                'borderColor' => '000000',
                'cellMargin' => 80,
            ];

            $headerCellStyle = [
                'bgColor' => '1F497D',
                'alignment' => 'center',
                'borderSize' => 6,
                'borderColor' => '000000',
            ];

            $headerTextStyle = [
                'bold' => true,
                'color' => 'FFFFFF',
            ];

            $paragraphStyle = [
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER
            ];

            $noCellStyle = [
                'bgColor' => 'FFC7CE',
                'alignment' => 'center',
                'color' => '000000',
            ];

            $yesCellStyle = [
                'bgColor' => 'C6EFCE',
                'alignment' => 'center',
                'color' => '000000',
            ];

            $a4WidthInTwips = 11906;
            $leftMargin = 500;
            $rightMargin = 500;

            $totalAvailableWidth = $a4WidthInTwips - $leftMargin - $rightMargin;
            $folderCellWidth = $totalAvailableWidth * 0.15;
            $checklistCellWidth = $totalAvailableWidth * 0.40;
            $completedCellWidth = $totalAvailableWidth * 0.15;
            $secondCompletedCellWidth = $totalAvailableWidth * 0.15;
            $notesCellWidth = $totalAvailableWidth * 0.15;
            $updatedByCellWidth = $totalAvailableWidth * 0.15;

            $phpWord = new PhpWord();
            $section = $phpWord->addSection($sectionStyle);
            $table = $section->addTable($tableStyle);

            $table->addRow();
            $cell = $table->addCell(2000, ['gridSpan' => 4] + $headerCellStyle);
            $cell->addText('Invoice Approval Checklist', $headerTextStyle, $paragraphStyle);

            $cell = $table->addCell(2000, ['gridSpan' => 3] + $headerCellStyle);
            $cell->addText('Second Checker (Other Underwriter)', $headerTextStyle, $paragraphStyle);

            if (count($existingChecklistItems) > 0) {
                $table->addRow();
                $table->addCell($folderCellWidth, $headerCellStyle)->addText('Folder to save', $headerTextStyle);
                $table->addCell($checklistCellWidth, $headerCellStyle)->addText('Checklist', $headerTextStyle);
                $table->addCell($completedCellWidth, $headerCellStyle)->addText('Completed by', $headerTextStyle);
                $table->addCell($updatedByCellWidth, $headerCellStyle)->addText('Updated by', $headerTextStyle);
                $table->addCell($secondCompletedCellWidth, $headerCellStyle)->addText('Completed by', $headerTextStyle);
                $table->addCell($updatedByCellWidth, $headerCellStyle)->addText('Updated by', $headerTextStyle);
                $table->addCell($notesCellWidth, $headerCellStyle)->addText('Notes', $headerTextStyle);

                foreach ($existingChecklistItems as $row) {
                    $table->addRow();

                    $table->addCell($folderCellWidth)->addText(Helper::escapeXmlText($row['folder_to_save']));
                    $table->addCell($checklistCellWidth)->addText(Helper::escapeXmlText($row['checklist']));

                    // Add first "Completed" status with conditional styling
                    if ($row['completed'] == 'Yes') {
                        $table->addCell($completedCellWidth, $yesCellStyle)->addText($row['completed'], [], $paragraphStyle);
                    } else {
                        $table->addCell($completedCellWidth, $noCellStyle)->addText($row['completed'], [], $paragraphStyle);
                    }

                    $updatedByFirstChecker = Helper::getUpdatedInfo(
                        $row,
                        'updated_at_first_checker',
                        'logged_user_id_first_checker'
                    );

                    $table->addCell($updatedByCellWidth)->addText(
                        Helper::escapeXmlText($updatedByFirstChecker)
                    );

                    // Add second "Completed" status with conditional styling
                    if ($row['second_checker_completed'] == 'Yes') {
                        $table->addCell($secondCompletedCellWidth, $yesCellStyle)->addText($row['second_checker_completed'], [], $paragraphStyle);
                    } else {
                        $table->addCell($secondCompletedCellWidth, $noCellStyle)->addText($row['second_checker_completed'], [], $paragraphStyle);
                    }

                    $updatedBySecondChecker = Helper::getUpdatedInfo(
                        $row,
                        'updated_at_second_checker',
                        'logged_user_id_second_checker'
                    );

                    $table->addCell($updatedByCellWidth)->addText(
                        Helper::escapeXmlText($updatedBySecondChecker)
                    );

                    $table->addCell($notesCellWidth)->addText(Helper::escapeXmlText($row['notes']));
                }
            }

            if (count($existingAgreementItems) > 0) {
                $table->addRow();
                $table->addCell($folderCellWidth, $headerCellStyle)->addText('Folder to save', $headerTextStyle);
                $table->addCell($checklistCellWidth, $headerCellStyle)->addText('Agreement To Send Out', $headerTextStyle);
                $table->addCell($completedCellWidth, $headerCellStyle)->addText('Completed by', $headerTextStyle);
                $table->addCell($updatedByCellWidth, $headerCellStyle)->addText('Updated by', $headerTextStyle);
                $table->addCell($secondCompletedCellWidth, $headerCellStyle)->addText('Completed by', $headerTextStyle);
                $table->addCell($updatedByCellWidth, $headerCellStyle)->addText('Updated by', $headerTextStyle);
                $table->addCell($notesCellWidth, $headerCellStyle)->addText('Notes', $headerTextStyle);

                foreach ($existingAgreementItems as $row) {
                    $table->addRow();

                    $table->addCell($folderCellWidth)->addText(Helper::escapeXmlText($row['folder_to_save']));
                    $table->addCell($checklistCellWidth)->addText(Helper::escapeXmlText($row['agreement_to_send_out']));

                    // Add first "Completed" status with conditional styling
                    if ($row['completed'] == 'Yes') {
                        $table->addCell($completedCellWidth, $yesCellStyle)->addText($row['completed'], [], $paragraphStyle);
                    } else {
                        $table->addCell($completedCellWidth, $noCellStyle)->addText($row['completed'], [], $paragraphStyle);
                    }

                    $updatedByFirstChecker = Helper::getUpdatedInfo(
                        $row,
                        'updated_at_first_checker',
                        'logged_user_id_first_checker'
                    );

                    $table->addCell($updatedByCellWidth)->addText(
                        Helper::escapeXmlText($updatedByFirstChecker)
                    );

                    // Add second "Completed" status with conditional styling
                    if ($row['second_checker_completed'] == 'Yes') {
                        $table->addCell($secondCompletedCellWidth, $yesCellStyle)->addText($row['second_checker_completed'], [], $paragraphStyle);
                    } else {
                        $table->addCell($secondCompletedCellWidth, $noCellStyle)->addText($row['second_checker_completed'], [], $paragraphStyle);
                    }

                    $updatedBySecondChecker = Helper::getUpdatedInfo(
                        $row,
                        'updated_at_second_checker',
                        'logged_user_id_second_checker'
                    );

                    $table->addCell($updatedByCellWidth)->addText(
                        Helper::escapeXmlText($updatedBySecondChecker)
                    );

                    $table->addCell($notesCellWidth)->addText(Helper::escapeXmlText($row['notes']));
                }
            }

            if (count($existingOnceFundedItems) > 0) {
                $table->addRow();
                $table->addCell($folderCellWidth, $headerCellStyle)->addText('Folder to save', $headerTextStyle);
                $table->addCell($checklistCellWidth, $headerCellStyle)->addText('To complete Once funded', $headerTextStyle);
                $table->addCell($completedCellWidth, $headerCellStyle)->addText('Completed by', $headerTextStyle);
                $table->addCell($updatedByCellWidth, $headerCellStyle)->addText('Updated by', $headerTextStyle);
                $table->addCell($secondCompletedCellWidth, $headerCellStyle)->addText('Completed by', $headerTextStyle);
                $table->addCell($updatedByCellWidth, $headerCellStyle)->addText('Updated by', $headerTextStyle);
                $table->addCell($notesCellWidth, $headerCellStyle)->addText('Notes', $headerTextStyle);

                foreach ($existingOnceFundedItems as $row) {
                    $table->addRow();

                    $table->addCell($folderCellWidth)->addText(Helper::escapeXmlText($row['folder_to_save']));

                    $sanitizedText = Helper::escapeXmlText($row['to_complete_once_funded']);
                    $table->addCell($checklistCellWidth)->addText($sanitizedText);

                    // Add first "Completed" status with conditional styling
                    if ($row['completed'] == 'Yes') {
                        $table->addCell($completedCellWidth, $yesCellStyle)->addText($row['completed'], [], $paragraphStyle);
                    } else {
                        $table->addCell($completedCellWidth, $noCellStyle)->addText($row['completed'], [], $paragraphStyle);
                    }

                    $updatedByFirstChecker = Helper::getUpdatedInfo(
                        $row,
                        'updated_at_first_checker',
                        'logged_user_id_first_checker'
                    );

                    $table->addCell($updatedByCellWidth)->addText(
                        Helper::escapeXmlText($updatedByFirstChecker)
                    );

                    // Add second "Completed" status with conditional styling
                    if ($row['second_checker_completed'] == 'Yes') {
                        $table->addCell($secondCompletedCellWidth, $yesCellStyle)->addText($row['second_checker_completed'], [], $paragraphStyle);
                    } else {
                        $table->addCell($secondCompletedCellWidth, $noCellStyle)->addText($row['second_checker_completed'], [], $paragraphStyle);
                    }

                    $updatedBySecondChecker = Helper::getUpdatedInfo(
                        $row,
                        'updated_at_second_checker',
                        'logged_user_id_second_checker'
                    );

                    $table->addCell($updatedByCellWidth)->addText(
                        Helper::escapeXmlText($updatedBySecondChecker)
                    );

                    $table->addCell($notesCellWidth)->addText(Helper::escapeXmlText($row['notes']));
                }
            }

            $section = $phpWord->addSection($sectionStyle);
            $section->getStyle()->setBreakType('continuous');

            // Save the document
            $fileName = 'BCA Funding Checklist.docx';
            $tempFile = tempnam(sys_get_temp_dir(), $fileName);

            // // Set the writer to use PclZip instead of ZipArchive
            // \PhpOffice\PhpWord\Settings::setZipClass(\PhpOffice\PhpWord\Settings::PCLZIP);

            $phpWordWriter = IOFactory::createWriter($phpWord, 'Word2007');
            $phpWordWriter->save($tempFile);

            return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
        } catch (Exception $e) {
            // dd($e);
            Log::error('Error creating document: ' . $e->getMessage());
            return redirect()->back()->with('message', 'Creating document errors.');
        }
    }

    public static function createSifFundingChecklistDoc($id)
    {
        try {
            if (empty($id)) {
                return redirect()->route('home');
            }

            $business_info = BusinessInfo::where('id', $id)->first();

            if (empty($business_info)) {
                return redirect()->route('home');
            }

            // Fetch existing checklist items for this business
            $existingInvoiceItems = SifInvoice::where('wp_business_info_id', $id)->get()->toArray();
            $existingClientItems = SifClient::where('wp_business_info_id', $id)->get()->toArray();
            $existingVerificationAndCompletionItems = SifVerificationAndCompletion::where('wp_business_info_id', $id)->get()->toArray();

            $sectionStyle = [
                'marginLeft' => 500,
                'marginRight' => 500,
                'marginTop' => 500,
                'marginBottom' => 500,
            ];

            $tableStyle = [
                // 'width' => 100 * 50, // 100% of the document width
                // 'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::PERCENT,
                'borderSize' => 6,
                'borderColor' => '000000',
                'cellMargin' => 80,
            ];

            $headerCellStyle = [
                'bgColor' => '1F497D',
                'alignment' => 'center',
                'borderSize' => 6,
                'borderColor' => '000000',
            ];

            $headerTextStyle = [
                'bold' => true,
                'color' => 'FFFFFF',
            ];

            $paragraphStyle = [
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER
            ];

            $noCellStyle = [
                'bgColor' => 'FFC7CE',
                'alignment' => 'center',
                'color' => '000000',
            ];

            $yesCellStyle = [
                'bgColor' => 'C6EFCE',
                'alignment' => 'center',
                'color' => '000000',
            ];

            $phpWord = new PhpWord();
            $section = $phpWord->addSection($sectionStyle);
            $table = $section->addTable($tableStyle);

            // Define adjusted widths
            $a4WidthInTwips = 11906;
            $leftMargin = 500;
            $rightMargin = 500;
            $totalAvailableWidth = $a4WidthInTwips - $leftMargin - $rightMargin;

            $SrCellWidth = $totalAvailableWidth * 0.10;
            $checklistCellWidth = $totalAvailableWidth * 0.45;
            $completeCellWidth = $totalAvailableWidth * 0.15;
            $secondCompletedCellWidth = $totalAvailableWidth * 0.15;
            $notesCellWidth = $totalAvailableWidth * 0.15;
            $updatedByCellWidth = $totalAvailableWidth * 0.15;

            if (count($existingInvoiceItems) > 0) {
                $table->addRow();
                $cell = $table->addCell(2000, ['gridSpan' => 4] + $headerCellStyle);
                $cell->addText('Invoice Approval Checklist', $headerTextStyle, $paragraphStyle);

                $cell = $table->addCell(2000, ['gridSpan' => 3] + $headerCellStyle);
                $cell->addText('Second Checker (Other Underwriter)', $headerTextStyle, $paragraphStyle);

                $table->addRow();
                $table->addCell($SrCellWidth, $headerCellStyle)->addText('', $headerTextStyle);
                $table->addCell($checklistCellWidth, $headerCellStyle)->addText('Section 1 - Invoice', $headerTextStyle);
                $table->addCell($completeCellWidth, $headerCellStyle)->addText('Completed by', $headerTextStyle);
                $table->addCell($updatedByCellWidth, $headerCellStyle)->addText('Updated by', $headerTextStyle);
                $table->addCell($secondCompletedCellWidth, $headerCellStyle)->addText('Completed by', $headerTextStyle);
                $table->addCell($updatedByCellWidth, $headerCellStyle)->addText('Updated by', $headerTextStyle);
                $table->addCell($notesCellWidth, $headerCellStyle)->addText('Notes', $headerTextStyle);

                foreach ($existingInvoiceItems as $index => $row) {
                    $table->addRow();
                    $table->addCell($SrCellWidth)->addText($index + 1);
                    $table->addCell($checklistCellWidth)->addText(Helper::escapeXmlText($row['invoice']));

                    // First "Completed" cell with conditional style
                    if ($row['completed'] == 'Yes') {
                        $table->addCell($completeCellWidth, $yesCellStyle)->addText($row['completed'], [], $paragraphStyle);
                    } else {
                        $table->addCell($completeCellWidth, $noCellStyle)->addText($row['completed'], [], $paragraphStyle);
                    }

                    $updatedByFirstChecker = Helper::getUpdatedInfo(
                        $row,
                        'updated_at_first_checker',
                        'logged_user_id_first_checker'
                    );

                    $table->addCell($updatedByCellWidth)->addText(
                        Helper::escapeXmlText($updatedByFirstChecker)
                    );

                    // Second "Completed" cell with conditional style
                    if ($row['second_checker_completed'] == 'Yes') {
                        $table->addCell($secondCompletedCellWidth, $yesCellStyle)->addText($row['second_checker_completed'], [], $paragraphStyle);
                    } else {
                        $table->addCell($secondCompletedCellWidth, $noCellStyle)->addText($row['second_checker_completed'], [], $paragraphStyle);
                    }

                    $updatedBySecondChecker = Helper::getUpdatedInfo(
                        $row,
                        'updated_at_second_checker',
                        'logged_user_id_second_checker'
                    );

                    $table->addCell($updatedByCellWidth)->addText(
                        Helper::escapeXmlText($updatedBySecondChecker)
                    );

                    $table->addCell($notesCellWidth)->addText(Helper::escapeXmlText($row['notes']));
                }
            }

            if (count($existingClientItems) > 0) {
                // $section = $phpWord->addSection($sectionStyle);
                $table->addRow();

                $table->addCell($SrCellWidth, $headerCellStyle)->addText('', $headerTextStyle);
                $table->addCell($checklistCellWidth, $headerCellStyle)->addText(Helper::escapeXmlText('Section 2 - Client'), $headerTextStyle);
                $table->addCell($completeCellWidth, $headerCellStyle)->addText('Completed by', $headerTextStyle);
                $table->addCell($updatedByCellWidth, $headerCellStyle)->addText('Updated by', $headerTextStyle);
                $table->addCell($secondCompletedCellWidth, $headerCellStyle)->addText('Completed by', $headerTextStyle);
                $table->addCell($updatedByCellWidth, $headerCellStyle)->addText('Updated by', $headerTextStyle);
                $table->addCell($notesCellWidth, $headerCellStyle)->addText('Notes', $headerTextStyle);

                foreach ($existingClientItems as $index => $row) {
                    $table->addRow();
                    $table->addCell($SrCellWidth)->addText($index + 1);
                    $table->addCell($checklistCellWidth)->addText(Helper::escapeXmlText($row['client']));

                    // Add first "Completed" status with conditional styling
                    if ($row['completed'] == 'Yes') {
                        $table->addCell($completeCellWidth, $yesCellStyle)->addText($row['completed'], [], $paragraphStyle);
                    } else {
                        $table->addCell($completeCellWidth, $noCellStyle)->addText($row['completed'], [], $paragraphStyle);
                    }

                    
                    $updatedByFirstChecker = Helper::getUpdatedInfo(
                        $row,
                        'updated_at_first_checker',
                        'logged_user_id_first_checker'
                    );

                    $table->addCell($updatedByCellWidth)->addText(
                        Helper::escapeXmlText($updatedByFirstChecker)
                    );

                    // Add second "Completed" status with conditional styling
                    if ($row['second_checker_completed'] == 'Yes') {
                        $table->addCell($secondCompletedCellWidth, $yesCellStyle)->addText($row['second_checker_completed'], [], $paragraphStyle);
                    } else {
                        $table->addCell($secondCompletedCellWidth, $noCellStyle)->addText($row['second_checker_completed'], [], $paragraphStyle);
                    }

                    $updatedBySecondChecker = Helper::getUpdatedInfo(
                        $row,
                        'updated_at_second_checker',
                        'logged_user_id_second_checker'
                    );

                    $table->addCell($updatedByCellWidth)->addText(
                        Helper::escapeXmlText($updatedBySecondChecker)
                    );

                    $table->addCell($notesCellWidth)->addText(Helper::escapeXmlText($row['notes']));
                }
            }

            if (count($existingVerificationAndCompletionItems) > 0) {
                // $section = $phpWord->addSection($sectionStyle);
                $table->addRow();

                $table->addCell($SrCellWidth, $headerCellStyle)->addText('', $headerTextStyle);
                $table->addCell($checklistCellWidth, $headerCellStyle)->addText(Helper::escapeXmlText('Section 3 - Verification & Completion'), $headerTextStyle);
                $table->addCell($completeCellWidth, $headerCellStyle)->addText('Completed by', $headerTextStyle);
                $table->addCell($updatedByCellWidth, $headerCellStyle)->addText('Updated by', $headerTextStyle);
                $table->addCell($secondCompletedCellWidth, $headerCellStyle)->addText('Completed by', $headerTextStyle);
                $table->addCell($updatedByCellWidth, $headerCellStyle)->addText('Updated by', $headerTextStyle);
                $table->addCell($notesCellWidth, $headerCellStyle)->addText('Notes', $headerTextStyle);

                foreach ($existingVerificationAndCompletionItems as $index => $row) {
                    $table->addRow();

                    $table->addCell($SrCellWidth)->addText($index + 1);

                    $sanitizedText = Helper::escapeXmlText($row['verification_and_completion']);
                    $table->addCell($checklistCellWidth)->addText($sanitizedText);

                    // Add first "Completed" status with conditional styling
                    if ($row['completed'] == 'Yes') {
                        $table->addCell($completeCellWidth, $yesCellStyle)->addText($row['completed'], [], $paragraphStyle);
                    } else {
                        $table->addCell($completeCellWidth, $noCellStyle)->addText($row['completed'], [], $paragraphStyle);
                    }

                    $updatedByFirstChecker = Helper::getUpdatedInfo(
                        $row,
                        'updated_at_first_checker',
                        'logged_user_id_first_checker'
                    );

                    $table->addCell($updatedByCellWidth)->addText(
                        Helper::escapeXmlText($updatedByFirstChecker)
                    );

                    // Add second "Completed" status with conditional styling
                    if ($row['second_checker_completed'] == 'Yes') {
                        $table->addCell($secondCompletedCellWidth, $yesCellStyle)->addText($row['second_checker_completed'], [], $paragraphStyle);
                    } else {
                        $table->addCell($secondCompletedCellWidth, $noCellStyle)->addText($row['second_checker_completed'], [], $paragraphStyle);
                    }

                    $updatedBySecondChecker = Helper::getUpdatedInfo(
                        $row,
                        'updated_at_second_checker',
                        'logged_user_id_second_checker'
                    );

                    $table->addCell($updatedByCellWidth)->addText(
                        Helper::escapeXmlText($updatedBySecondChecker)
                    );

                    $table->addCell($notesCellWidth)->addText(Helper::escapeXmlText($row['notes']));
                }
            }

            $section = $phpWord->addSection($sectionStyle);
            $section->getStyle()->setBreakType('continuous');


            // Save the document
            $fileName = 'SIF funding Checklist.docx';
            $tempFile = tempnam(sys_get_temp_dir(), $fileName);

            // // Set the writer to use PclZip instead of ZipArchive
            // \PhpOffice\PhpWord\Settings::setZipClass(\PhpOffice\PhpWord\Settings::PCLZIP);

            $phpWordWriter = IOFactory::createWriter($phpWord, 'Word2007');
            $phpWordWriter->save($tempFile);

            return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
        } catch (Exception $e) {
            // dd($e);
            Log::error('Error creating document: ' . $e->getMessage());
            return redirect()->back()->with('message', 'Creating document errors.');
        }
    }


    public static function createAIPDoc($inputData, $id)
    {

        try {
            if (empty($id)) {
                return redirect()->route('home');
            }

            $business_info = BusinessInfo::where('id', $id)->first();
            if (empty($business_info)) {
                return redirect()->route('home');
            }

            $business_detail = $business_info;
            $price_model = PriceModel::where('wp_business_info_id', $id)->first();
            $customerInfo = DB::table('wp_customer_info')->where('reg_number', $business_info->registration_number)->first();
            $lender = Lender::where('wp_business_info_id', $id)->get() ?? [];

            $sectionStyle = [
                'marginLeft' => 500,
                'marginRight' => 500,
                'marginTop' => 500,
                'marginBottom' => 500,
            ];
            $phpWord = new PhpWord();
            $section = $phpWord->addSection($sectionStyle);

            $a4WidthInTwips = 11906;
            $leftMargin = 500;
            $rightMargin = 500;
            $totalAvailableWidth = $a4WidthInTwips - $leftMargin - $rightMargin;

            $firstColumnWidth = $totalAvailableWidth * 0.25;
            $secondColumnWidth = $totalAvailableWidth * 0.25;
            $thirdColumnWidth = $totalAvailableWidth * 0.25;
            $fourthColumnWidth = $totalAvailableWidth * 0.25;

            // Title heading
            $titleFontStyle = ['bold' => true, 'size' => 16, 'color' => 'FFFFFF', 'bgColor' => '1F497D',];
            $tableStyle = ['width' => 100 * 50, 'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::PERCENT];

            $table = $section->addTable($tableStyle);
            $table->addRow();

            $cell = $table->addCell(100 * 50, ['bgColor' => '1F497D']);
            $cell->addText(
                "Congratulations, you've been pre-approved!",
                $titleFontStyle,
                ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]
            );

            $section->addTextBreak(1);
            $section->addText(
                "Here are our Headline Terms of Offer, which are subject to satisfactory due diligence checks, provision of Further Information and the conclusion of any Conditions Precedent (as outlined below).",
                ['size' => 11],
                ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH]
            );

            $section->addText(
                "When these are in place, we can sign the funding agreements, and fund upon signing of the agreement!",
                ['bold' => true, 'size' => 11],
                ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH]
            );

            // Create table with header
            $tableStyle = [
                'borderSize' => 6,
                'borderColor' => '000000',
                'cellMargin' => 80,
            ];


            $headerCellStyle = ['bgColor' => '1F4E78'];
            $headerTextStyle = ['bold' => true, 'color' => 'FFFFFF'];

            $table = $section->addTable($tableStyle);
            $table->addRow();
            $table->addCell(2000, ['gridSpan' => 4] + $headerCellStyle)->addText("Terms of Advance", $headerTextStyle);

            // Data rows
            $cellLabelStyle = ['bgColor' => 'D9E2F3'];

            $table->addRow();
            $table->addCell($firstColumnWidth, $cellLabelStyle)->addText("Advance");
            $table->addCell($secondColumnWidth)->addText(!empty($price_model->advance_requested) ? ('£' . $price_model->advance_requested) : '');
            $table->addCell($thirdColumnWidth, $cellLabelStyle)->addText("Servicing and Administration Fee");
            $table->addCell($fourthColumnWidth)->addText(!empty($price_model->arrangement_fee_excl_VAT) ? ('£' . $price_model->arrangement_fee_excl_VAT . ' + VAT') : '');

            $table->addRow();
            $table->addCell($firstColumnWidth, $cellLabelStyle)->addText("Maximum Total Repayment");
            $table->addCell($secondColumnWidth)->addText(!empty($price_model->total_repayable) ? ('£' . $price_model->total_repayable) : '');
            $table->addCell($thirdColumnWidth, $cellLabelStyle)->addText("Purpose of Advance");
            $table->addCell($fourthColumnWidth)->addText(!empty($business_detail->reason_for_funding) ? Helper::escapeXmlText($business_detail->reason_for_funding) : '');

            $section->addTextBreak(1);
            $table = $section->addTable($tableStyle);
            $table->addRow();
            $table->addCell(2000, ['gridSpan' => 4] + $headerCellStyle)->addText("Weekly Repayment Option – Fixed Weekly Repayment", $headerTextStyle);

            $table->addRow();
            $table->addCell($firstColumnWidth, $cellLabelStyle)->addText("Maturity");
            $table->addCell($secondColumnWidth)->addText("6 months");
            $table->addCell($thirdColumnWidth, $cellLabelStyle)->addText("Weekly Repayment");
            $table->addCell($fourthColumnWidth)->addText(!empty($price_model->fixed_repayment_amount) ? ('£' . $price_model->fixed_repayment_amount) : '');

            $section->addTextBreak(1);
            $table = $section->addTable($tableStyle);
            $table->addRow();
            $table->addCell(2000, ['gridSpan' => 4] + $headerCellStyle)->addText("Daily Repayment Option – Fixed Daily Repayment", $headerTextStyle);

            $table->addRow();
            $table->addCell($firstColumnWidth, $cellLabelStyle)->addText("Maturity");
            $table->addCell($secondColumnWidth)->addText("6 months");
            $table->addCell($thirdColumnWidth, $cellLabelStyle)->addText("Daily Repayment");
            $table->addCell($fourthColumnWidth)->addText(!empty($price_model->daily_repayment_amount) ? ('£' . $price_model->daily_repayment_amount) : '');

            $section->addTextBreak(1);
            $table = $section->addTable($tableStyle);
            $table->addRow();
            $table->addCell(2000, ['gridSpan' => 4] + $headerCellStyle)->addText('Your Company Information ("You")', $headerTextStyle);

            $table->addRow();
            $table->addCell(2000, $cellLabelStyle)->addText("Business Name");
            $table->addCell(2000, ['gridspan' => 3])->addText(!empty($business_detail->business_name) ? Helper::escapeXmlText($business_detail->business_name) : '');

            $table->addRow();
            $table->addCell(2000, $cellLabelStyle)->addText("Trading Name");
            $table->addCell(2000, ['gridspan' => 3])->addText(!empty($business_detail->business_name) ? Helper::escapeXmlText($business_detail->business_name) : '');

            $table->addRow();
            $table->addCell(2000, $cellLabelStyle)->addText("Registered Address");
            $table->addCell(2000, ['gridspan' => 3])->addText(!empty($business_detail->address) ? Helper::escapeXmlText($business_detail->address) : '');

            $table->addRow();
            $table->addCell(2000, $cellLabelStyle)->addText("Type of Entity");
            $table->addCell(2000, ['gridspan' => 3])->addText(ucwords($inputData['type_of_entity'] ?? ''));

            $table->addRow();
            $table->addCell(2000, $cellLabelStyle)->addText("Registration No.");
            $table->addCell(2000, ['gridspan' => 3])->addText(!empty($business_detail->registration_number) ? $business_detail->registration_number : '');

            $firstColumnWidth = $totalAvailableWidth * 0.25;
            $secondColumnWidth = $totalAvailableWidth * 0.25;

            $table->addRow();
            $table->addCell($firstColumnWidth, $cellLabelStyle)->addText("Website");
            $table->addCell($secondColumnWidth)->addText(!empty($business_detail->website_address) ? $business_detail->website_address : '');
            $table->addCell($thirdColumnWidth, $cellLabelStyle)->addText("Main Phone");
            $table->addCell($fourthColumnWidth)->addText("-");

            $section->addTextBreak(1);
            $table = $section->addTable($tableStyle);
            $table->addRow();
            $table->addCell($firstColumnWidth, ['gridSpan' => 4] + $headerCellStyle)->addText("Your Contact Details", $headerTextStyle);

            $table->addRow();
            $table->addCell($firstColumnWidth, $cellLabelStyle)->addText("Full Name");
            $table->addCell($secondColumnWidth)->addText(trim($customerInfo->first_name . ' ' . $customerInfo->middle_name . ' ' . $customerInfo->last_name));
            $table->addCell($thirdColumnWidth, $cellLabelStyle)->addText("Position");
            $table->addCell($fourthColumnWidth)->addText("-");

            $table->addRow();
            $table->addCell($firstColumnWidth, $cellLabelStyle)->addText("Email");
            $table->addCell($secondColumnWidth)->addText(!empty($customerInfo->email) ? $customerInfo->email : '');
            $table->addCell($thirdColumnWidth, $cellLabelStyle)->addText("Mobile");
            $table->addCell($fourthColumnWidth)->addText(!empty($customerInfo->phone) ? $customerInfo->phone : '');

            // Second Page
            $contentHeaderStyle = ['bold' => true, 'size' => 14, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH];
            $contentTextStyle = ['size' => 11, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH];
            $urlStyle = ['size' => 11, 'color' => '0000FF', 'underline' => 'single'];

            $sectionTwo = $phpWord->addSection($sectionStyle);
            // Content-1
            $sectionTwo->addText(
                "You’ve been pre-approved for a Business Cash Advance",
                $contentHeaderStyle
            );

            $textRun = $sectionTwo->addTextRun();

            $textRun->addText(
                "You’ve been pre-approved for a Business Cash Advance from the Funding Alternative Group! To finalise your Business Cash Advance, we need your signed agreement to a few items which we’ve listed under the ‘Accepting this Offer’ section below. This preliminary offer is subject to terms as outlined in ",
                $contentTextStyle
            );

            $textRun->addLink(
                'https://fundingalternative.co.uk/preliminary-offer-terms-bca/',
                'https://fundingalternative.co.uk/preliminary-offer-terms-bca/',
                $urlStyle
            );

            $textRun->addText(
                " - by signing this agreement, you agree to these terms and conditions – please review these carefully – if you have any questions, please contact us at applications@fundingalternative.co.uk or call 0800 652 1977.",
                $contentTextStyle
            );

            // Content 2
            $sectionTwo->addTextBreak(2);
            $sectionTwo->addText(
                "Assumptions We’ve Made in Determining this Offer",
                $contentHeaderStyle
            );

            $listParagraphStyle = ['spaceAfter' => 20, 'indentation' => ['left' => 360]];

            $listfontStyle = new StyleFont();
            $listfontStyle->setSize(11);

            $sectionTwo->addListItem('All materials are provided in good faith, are accurate and up to date and have been completed to the best of your knowledge.', 0, $listfontStyle, $listParagraphStyle);
            $sectionTwo->addListItem('The business is currently up to date with all taxes.', 0, $listfontStyle, $listParagraphStyle);
            $sectionTwo->addListItem('Latest financial information available upon request.', 0, $listfontStyle, $listParagraphStyle);

            $ownershipClass = $customerInfo->ownership ?? '';
            $isHomeowner = !empty($ownershipClass) && (
                Helper::hasText('home owner', $ownershipClass) ||
                Helper::hasText('Home Owner (Own Home)', $ownershipClass)
            );
            $sectionTwo->addListItem(
                'Directors ' . ($isHomeowner ? 'are' : 'are not') . ' homeowners with sufficient home equity.',
                0,
                $listfontStyle,
                $listParagraphStyle
            );

            $business_industry = !empty($business_detail->industry)
                ? preg_replace('/\s+/', ' ', trim(str_replace('&nbsp;', '', $business_detail->industry)))
                : '___';
            $sectionTwo->addListItem('The business provides ' . $business_industry . '.', 0, $listfontStyle, $listParagraphStyle);

            $industry = preg_replace('/\s+/', ' ', trim(str_replace('&nbsp;', '', $business_detail->industry)));

            if (Helper::hasText('construction and building', $industry)) {
                $sectionTwo->addListItem(
                    'Signed contracts for both current and forthcoming projects are in place, substantiating revenue projections for the upcoming six months.',
                    0,
                    $listfontStyle,
                    $listParagraphStyle
                );
            }

            $sectionTwo->addListItem('The cash flow projections for the business demonstrate a definitive capacity to service the required repayments associated with the requested advance.', 0, $listfontStyle, $listParagraphStyle);
            $sectionTwo->addListItem('The directors personal credit report obtained via TransUnion will not display any adverse information.', 0, $listfontStyle, $listParagraphStyle);

            $companyName = $business_detail->business_name ?? '';
            $sectionTwo->addListItem($companyName . ' will repay the Business Cash Advance through a Funding Alternative approved recurring payment provider.', 0, $listfontStyle, $listParagraphStyle);

            $sectionTwo->addListItem('Open Banking will be provided prior and after funding.', 0, $listfontStyle, $listParagraphStyle);

            // Content-3
            $sectionTwo->addTextBreak(2);
            $sectionTwo->addText(
                "Conditions Precedent",
                $contentHeaderStyle
            );

            $contentText = 'The borrower shall provide debenture on the company and its subsidiaries (if any) that will be filed with Companies House on or prior to the Funding Date. All directors and major shareholders shall provide a Personal Guarantee as well as a Statement of Financial Position, on the fulfilment of the terms, on or prior to the Funding Date.';
            $sectionTwo->addText(
                $contentText,
                $contentTextStyle
            );

            $contentText = 'The company shall authorise us to monitor the company’s bank accounts via Open Banking until such a time as the Principal Amount is repaid in full. ';
            $sectionTwo->addText(
                $contentText,
                $contentTextStyle
            );

            // Content-4
            $sectionTwo->addTextBreak(2);
            $sectionTwo->addText(
                "Data Consent",
                $contentHeaderStyle
            );

            $contentText = 'By providing us with your personal information you consent to Funding Alternative Ltd using and retaining your personal information as is required and according to our General Data Protection Regulation (GDPR) Policy (https://fundingalternative.co.uk/privacy-policy/). You hereby give permission for us to process your personal data in a manner which is consistent with, and reasonably required for the effective performance of any potential agreement between us. You are also consenting to continuing to receive electronic communications from our group of companies.';
            $sectionTwo->addText(
                $contentText,
                $contentTextStyle
            );

            $textRun = $sectionTwo->addTextRun();
            $textRun->addText(
                "Please note that you can withdraw your consent at any time by writing to the Information Officer at ",
                $contentTextStyle
            );

            $textRun->addLink(
                'privacy@fundingalternative.co.uk/',
                'privacy@fundingalternative.co.uk/',
                $urlStyle
            );

            $textRun->addText(
                ".",
                $contentTextStyle
            );

            // content-5
            $sectionTwo->addTextBreak(2);
            $sectionTwo->addText(
                "Credit Searches and Identity Checks",
                $contentHeaderStyle
            );

            $sectionTwo->addText(
                'You hereby consent that, and authorise Funding Alternative Ltd to, from time to time and from the date of signature below until the termination of our agreement:',
                $contentTextStyle
            );

            $listParagraphStyle = [
                'size' => 11,
                'spaceAfter' => 20,
                'indentation' => ['left' => 360]
            ];
            $sectionTwo->addText('- contact and enquire registered credit bureaux and other credit reporting agencies to investigate your creditworthiness including indebtedness, payment patterns, behaviour profile', $listfontStyle, $listParagraphStyle);
            $sectionTwo->addText('- exchange with other credit providers or other third parties, you and your client’s payment behaviour as well as any other information provided to us, and ', $listfontStyle, $listParagraphStyle);
            $sectionTwo->addText('- contact, request and obtain information from other credit providers or third parties such as previous employers, trade and business partners ', $listfontStyle, $listParagraphStyle);

            $sectionTwo->addTextBreak(1);
            $textRun = $sectionTwo->addTextRun();
            $textRun->addText(
                "The personal information we have collected from you will be shared with fraud prevention agencies who will use it to prevent fraud and money-laundering and to verify your identity. If fraud is detected, you could be refused certain services, finance, or employment. Further details of how your information will be used by us and these fraud prevention agencies, and your data protection rights, can be found in our ",
                $contentTextStyle
            );

            $textRun->addLink(
                'privacy policy',
                'https://fundingaltgroup.co.uk/privacy-policy/',
                $urlStyle
            );

            $textRun->addText(
                ".",
                $contentTextStyle
            );

            // content-6
            $sectionTwo->addTextBreak(2);
            $sectionTwo->addText(
                "Further Information",
                $contentHeaderStyle
            );

            $sectionTwo->addText(
                'This offer is presented based on the assumptions we’ve made, and is subject to our complete satisfaction following our review of the additional materials below, including but not limited to:',
                $contentTextStyle
            );

            $listParagraphStyle = [
                'size' => 11,
                'spaceAfter' => 20,
                'indentation' => ['left' => 360]
            ];

            if (isset($price_model) && $price_model->advance_requested >= 50000) {
                $sectionTwo->addListItem('Most recent Aged Debtors and Creditors List, including any tax arrears.', 0, $listfontStyle, $listParagraphStyle);
            }
            $sectionTwo->addListItem('Provide a breakdown of the companies tax situation, stating the outstanding balances for VAT, PAYE and Corporate Tax. If applicable, please include any payment arrangements that are in place with supporting HMRC screenshots where applicable.', 0, $listfontStyle, $listParagraphStyle);
            // $sectionTwo->addListItem('REMOVE IF NO LENDERS Complete our lender table in full, outlining any existing debt alongside the payment details and security given (table included in accompanying email).', 0, $listfontStyle, $listParagraphStyle);
            // $sectionTwo->addListItem('[Provide signed contracts for current and future work that substantiate revenue over the next 6-months.] (If not needed, delete)', 0, $listfontStyle, $listParagraphStyle);

            $sectionTwo->addTextBreak(1);
            $table = $sectionTwo->addTable($tableStyle);

            $headerCellStyle = ['bgColor' => '1F4E78'];
            $headerTextStyle = ['bold' => true, 'color' => 'FFFFFF'];

            $table->addRow();
            $table->addCell(2000, ['gridSpan' => 6] + $headerCellStyle)->addText("Lender Table", $headerTextStyle);

            // Data rows
            $cellLabelStyle = ['bgColor' => 'D9E2F3'];

            $table->addRow();
            $table->addCell($firstColumnWidth, $cellLabelStyle)->addText("Lender Name");
            $table->addCell($firstColumnWidth, $cellLabelStyle)->addText("Outstanding amount");
            $table->addCell($firstColumnWidth, $cellLabelStyle)->addText("How much gets paid?");
            $table->addCell($firstColumnWidth, $cellLabelStyle)->addText("How often it is paid?");
            $table->addCell($firstColumnWidth, $cellLabelStyle)->addText("Expected Maturity");
            $table->addCell($firstColumnWidth, $cellLabelStyle)->addText("Security given (PG, Asset? Etc.)");


            if (isset($lender) && count($lender) > 0) {
                foreach ($lender as $key => $value) {
                    $table->addRow();
                    $table->addCell($firstColumnWidth)->addText($value['lender_name']);
                    $table->addCell($firstColumnWidth)->addText('£' . $value['outstanding_amount']);
                    $table->addCell($firstColumnWidth)->addText('£' . $value['how_much_gets_paid']);
                    $table->addCell($firstColumnWidth)->addText($value['how_often_it_is_paid']);
                    $table->addCell($firstColumnWidth)->addText($value['expected_maturity']);
                    $table->addCell($firstColumnWidth)->addText($value['security_given']);
                }
            }

            // Content-7
            $sectionThree = $phpWord->addSection($sectionStyle);

            $sectionThree->addText(
                "Acceptance",
                $contentHeaderStyle
            );

            $contentText = 'This Pre-Approval Offer is valid until close of business on ' . date('l jS F Y');
            $sectionThree->addText(
                $contentText,
                $contentTextStyle
            );

            $contentText = 'By agreeing to the terms presented and signing below (which includes all accepted forms of electronic signature), you consent to proceed under these conditions and authorise the processing of your personal data.';
            $sectionThree->addText(
                $contentText,
                $contentTextStyle
            );



            // Define font and paragraph styles
            $boldStyle = ['bold' => true];
            $paragraphLeft = ['alignment' => 'left'];
            $borderNone = [
                'borderTopSize' => 0,
                'borderLeftSize' => 0,
                'borderRightSize' => 0,
                'borderBottomSize' => 0,
                'borderColor' => 'FFFFFF'
            ];

            $sectionThree->addTextBreak(2);
            $table = $sectionThree->addTable(['borderSize' => 0, 'borderColor' => 'FFFFFF', 'cellMargin' => 0]);

            $table->addRow();
            $cell1 = $table->addCell(4000, $borderNone);
            $cell1->addText('Signed by', [], $paragraphLeft);
            $cell1->addText('Warren Collocott', $boldStyle, $paragraphLeft);

            $cell2 = $table->addCell(4000, $borderNone);
            $imagePath = public_path('signatures/signature_3437.jpg');

            if (file_exists($imagePath)) {
                $cell2->addImage($imagePath, [
                    'width' => 150, // Adjust the width to fit the cell
                    'height' => 50, // Adjust the height to fit the cell
                    'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::LEFT, // Align the image as needed
                ]);
            }
            $cell2->addText('---------------------------------', [], $paragraphLeft);
            $cell2->addText('Authorised signatory', [], $paragraphLeft);

            $table->addRow();
            $cell1 = $table->addCell(4000, $borderNone);
            $cell1->addText('for and on behalf of', [], $paragraphLeft);
            $cell1->addText('Funding Alternative Group Ltd', $boldStyle, $paragraphLeft);

            $cell2 = $table->addCell(4000, $borderNone);
            $cell2->addText('Date:  dd-mm-yyyy', [], $paragraphLeft);



            $sectionThree = $phpWord->addSection($sectionStyle);
            $sectionThree->getStyle()->setBreakType('continuous');



            // Save the document
            $fileName = ucwords(strtolower(str_replace(['_', '-', '.'], ' ', $business_detail->business_name))) . ' - Agreement In Principle.docx';

            $tempFile = tempnam(sys_get_temp_dir(), $fileName);

            // // Set the writer to use PclZip instead of ZipArchive
            // \PhpOffice\PhpWord\Settings::setZipClass(\PhpOffice\PhpWord\Settings::PCLZIP);

            $phpWordWriter = IOFactory::createWriter($phpWord, 'Word2007');
            $phpWordWriter->save($tempFile);

            return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
        } catch (Exception $e) {
            dd($e);
            Log::error('Error creating document: ' . $e->getMessage());
            return redirect()->back()->with('message', 'Creating document errors.');
        }
    }
}
