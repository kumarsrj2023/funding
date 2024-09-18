<div>
    <p style="font-size: 13px; margin: 0;">
        ALL INFORMATION IS HELD IN STRICTEST CONFIDENCE IN ACCORDANCE WITH OUR General Data Protection Regulation (GDPR)
        POLICY.
    </p>

    <!-- Company Name or Registration Number -->
    <div class="form-section" style="margin-bottom: 1rem;">
        <h6
            style="background-color: rgb(26, 51, 95); color: white; padding: 10px; font-size: 14px; font-weight: 600; margin-bottom: 0;">
            Full Company Business Name and Registration Number
        </h6>
        <p style="width: 100%; padding: 10px; border: 1px solid; box-sizing: border-box; font-size: 14px; margin: 0;">
            {{ $businessInfo->business_name }}
        </p>
    </div>

    <!-- Name And Other Details -->
    <div class="name-and-other-details" style="margin-bottom: 1rem;">
        <h2
            style="background-color: rgb(26, 51, 95); color: white; padding: 10px; font-size: 14px; font-weight: 600; margin: 0;">
            Name and Other Details of Proposed Guarantor
        </h2>
        <table style="border-collapse: collapse; width: 100%;">
            <tr>
                <td style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); width: 60px;">Title
                </td>
                <td style="padding: 8px; border: 1px solid;">{{ $directorInfo->title }}</td>
                <td style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); width: 110px;">First
                    Name</td>
                <td style="padding: 8px; border: 1px solid;">{{ $directorInfo->first_name }}</td>
                <td style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); width: 110px;">Middle
                    Name</td>
                <td style="padding: 8px; border: 1px solid;">{{ $directorInfo->middle_name }}</td>
                <td style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); width: 110px;">Last
                    Name</td>
                <td style="padding: 8px; border: 1px solid;">{{ $directorInfo->surname }}</td>
            </tr>

            <tr>
                <td colspan="3"
                    style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); width: 110px;">Full
                    Street Address</td>
                <td colspan="5" style="padding: 8px; border: 1px solid;">{{ $directorInfo->address_simple_value }}</td>
            </tr>

            <tr>
                <td colspan="3"
                    style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); width: 110px;">Time in
                    current address (months)</td>
                <td colspan="5" style="padding: 8px; border: 1px solid;">{{ $directorInfo->time_in_curr_address }}</td>
            </tr>

            <tr>
                <?php
                    $date = new DateTime($directorInfo->date_of_birth);
                    $formattedDate = $date->format('d-m-Y');
                    ?>
                <td colspan="2"
                    style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); width: 110px;">Date of
                    Birth</td>
                <td colspan="2" style="padding: 8px; border: 1px solid;">{{ $formattedDate }}</td>

                <td style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); width: 110px;">Email
                </td>
                <td colspan="3" style="padding: 8px; border: 1px solid;">{{ $directorInfo->email }}</td>
            </tr>

            <tr>
                <td colspan="2"
                    style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); width: 110px;">Tel
                    Home</td>
                <td colspan="2" style="padding: 8px; border: 1px solid;">{{ $directorInfo->tel_home }}</td>

                <td colspan="1"
                    style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); width: 110px;">Tel
                    Business</td>
                <td colspan="1" style="padding: 8px; border: 1px solid;">{{ $directorInfo->tel_business }}</td>

                <td style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); width: 110px;">Mobile
                </td>
                <td colspan="1" style="padding: 8px; border: 1px solid;">{{ $directorInfo->mobile }}</td>
            </tr>
            <tr>
                <td colspan="6"
                    style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); width: 110px;">Are You
                    or Have You Ever Been Declared Bankrupt? (Y/N)</td>
                <td colspan="2" style="padding: 8px; border: 1px solid;">{{ $directorInfo->declared_bankrupt }}</td>
            </tr>
        </table>
    </div>

    <!-- Assets and Liabilities Checkbox -->
    <div class="name-and-other-details" style="margin-bottom: 1rem;">
        <h2
            style="background-color: rgb(26, 51, 95); color: white; padding: 10px; font-size: 14px; font-weight: 600; margin: 0;">
            Assets and Liabilities
        </h2>
        <div class="text" style="padding: 8px;border:1px solid;padding-bottom:320px">
            <p class="" style="margin:0;margin-bottom: 10px;">Please provide a full list of assets whether held in your
                name or otherwise and liabilities. If you need more space for details please set out on a separate
                sheet.</p>
            <div class="checkbox-group">
                <?php
            $checked = '<span style="font-family: DejaVu Sans, sans-serif;display:inline-block;font-size:20px;">&#9745;</span>'; // ☑
            $unchecked = '<span style="font-family: DejaVu Sans, sans-serif;display:inline-block;font-size:20px;">&#9744;</span>'; // ☐                    
            ?>

                <?php echo $checked; ?>
                <label for="assets_trust">Tick box if any of these assets are owned by the proposed guarantor/individual
                    as trustee of a trust for someone else or are owned by others but the others hold the asset as
                    trustee for the benefit of the individual proposed Guarantor or are in the name of a spouse or any
                    other person/entity. If so please specify which asset(s) and identify the third parties
                    below.</label>
            </div>
        </div>
    </div>

    <!-- Assets and Liabilities Details -->
    <div class="" style="margin-bottom: 1rem;">
        <table style="border-collapse: collapse; width: 100%; border: 1px solid rgb(26, 51, 95)">
            <thead>
                <tr>
                    <th colspan="4"
                        style="background-color: rgb(26, 51, 95); color: white; padding: 10px; font-size: 14px; font-weight: 600; text-align: left;">
                        Assets
                    </th>
                    <th colspan="4"
                        style="background-color: rgb(26, 51, 95); color: white; padding: 10px; font-size: 14px; font-weight: 600; text-align: left;">
                        Liabilities
                    </th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <th colspan="2"
                        style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); text-align: left;">
                        Where appropriate please provide account/registration numbers
                    </th>
                    <td colspan="2" style="padding: 8px; border: 1px solid;">
                        {{ $assetInfo->account_or_regnumber }}
                    </td>
                    <th colspan="2"
                        style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); text-align: left;">
                        Where appropriate please provide account/registration numbers
                    </th>
                    <td colspan="2" style="padding: 8px; border: 1px solid;">
                        {{ $liabilitiesInfo->account_or_regnumber }}
                    </td>
                </tr>
                <tr>
                    <td colspan="2"
                        style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); text-align: left;">
                        Cash in Bank & Deposit (£)
                    </td>
                    <td colspan="2" style="padding: 8px; border: 1px solid;">
                        {{ $assetInfo->cash_in_bank_and_deposit }}
                    </td>
                    <td colspan="2"
                        style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); text-align: left;">
                        Personal Loans & Overdrafts
                    </td>
                    <td colspan="2" style="padding: 8px; border: 1px solid;">
                        {{ $liabilitiesInfo->personal_loans_and_overdrafts }}
                    </td>
                </tr>
                <tr>
                    <td colspan="2"
                        style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); text-align: left;">
                        Publicly Listed Shares
                    </td>
                    <td colspan="2" style="padding: 8px; border: 1px solid;">
                        {{ $assetInfo->public_listed_shares }}
                    </td>
                    <td colspan="2"
                        style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); text-align: left;">
                        Mortgages
                    </td>
                    <td colspan="2" style="padding: 8px; border: 1px solid;">
                        {{ $liabilitiesInfo->mortgages }}
                    </td>
                </tr>
                <tr>
                    <td colspan="2"
                        style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); text-align: left;">
                        Properties Y/N (See below)
                    </td>
                    <td colspan="2" style="padding: 8px; border: 1px solid;">
                        {{ $assetInfo->properties == 1 ? 'Y' : 'N' }}
                    </td>
                    <td colspan="2"
                        style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); text-align: left;">
                        Credit Card Debts
                    </td>
                    <td colspan="2" style="padding: 8px; border: 1px solid;">
                        {{ $liabilitiesInfo->credit_card_debts }}
                    </td>
                </tr>
                <tr>
                    <td colspan="2"
                        style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); text-align: left;">
                        Motor Vehicles & Boats
                    </td>
                    <td colspan="2" style="padding: 8px; border: 1px solid;">
                        {{ $assetInfo->motor_vehicles_boats }}
                    </td>
                    <td colspan="2"
                        style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); text-align: left;">
                        Motor Loan
                    </td>
                    <td colspan="2" style="padding: 8px; border: 1px solid;">
                        {{ $liabilitiesInfo->motor_loan }}
                    </td>
                </tr>
                <tr>
                    <td colspan="2"
                        style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); text-align: left;">
                        Other Cash Investments
                    </td>
                    <td colspan="2" style="padding: 8px; border: 1px solid;">
                        {{ $assetInfo->other_cash_investments }}
                    </td>
                    <td colspan="2"
                        style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); text-align: left;">
                        Property Rental
                    </td>
                    <td colspan="2" style="padding: 8px; border: 1px solid;">
                        {{ $liabilitiesInfo->property_rental }}
                    </td>
                </tr>
                <tr>
                    <td colspan="2"
                        style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); text-align: left;">
                        Details of Personal Pension
                    </td>
                    <td colspan="2" style="padding: 8px; border: 1px solid;">
                        {{ $assetInfo->details_of_personal_pension }}
                    </td>
                    <td colspan="2"
                        style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); text-align: left;">
                        Other Debts & Contingent Liabilities
                    </td>
                    <td colspan="2" style="padding: 8px; border: 1px solid;">
                        {{ $liabilitiesInfo->other_debt_and_contingent_liabilities }}
                    </td>
                </tr>
                <tr>
                    <td colspan="2"
                        style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); text-align: left;">
                        Any other assets (please specify)
                    </td>
                    <td colspan="2" style="padding: 8px; border: 1px solid;">
                        {{ $assetInfo->other_assets }}
                    </td>
                    <td colspan="2"
                        style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); text-align: left;">
                        Any other liabilities (please specify) recurring or otherwise
                    </td>
                    <td colspan="2" style="padding: 8px; border: 1px solid;">
                        {{ $liabilitiesInfo->other_liabilities }}
                    </td>
                </tr>
                <tr>
                    <th colspan="2"
                        style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); text-align: left;">
                        Total (£)
                    </th>
                    <td colspan="2" style="padding: 8px; border: 1px solid;">
                        N/A
                    </td>
                    <th colspan="2"
                        style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); text-align: left;">
                        Total (£)
                    </th>
                    <td colspan="2" style="padding: 8px; border: 1px solid;">
                        N/A
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Properties and other assets with value -->
    <div class="" style="margin-bottom: 1rem;">
        <h2
            style="background-color: rgb(26, 51, 95); color: white; padding: 10px; font-size: 14px; font-weight: 600; margin: 0;">
            Properties and other assets with value more than £5,000
        </h2>
        <table style="border-collapse: collapse; width: 100%;">
            <thead>
                <tr>
                    <th style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243);font-size: 12px;">
                        Property Address(es) and Assets </th>
                    <th style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243);font-size: 12px;">
                        Estimated Value (£)</th>
                    <th style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243);font-size: 12px;">
                        Debt (if any) (£)</th>
                    <th style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243);font-size: 12px;">
                        Financing Costs (£)</th>
                    <th style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243);font-size: 12px;">
                        Income (if any) (£)</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($otherAssets as $asset) { ?>
                <tr>
                    <td colspan="1" style="padding: 8px; border: 1px solid;">
                        <?= $asset['property_address_and_assets']; ?>
                    </td>
                    <td colspan="1" style="padding: 8px; border: 1px solid;">
                        <?= $asset['estimated_value']; ?>
                    </td>
                    <td colspan="1" style="padding: 8px; border: 1px solid;">
                        <?= $asset['debt']; ?>
                    </td>
                    <td colspan="1" style="padding: 8px; border: 1px solid;">
                        <?= $asset['financing_costs']; ?>
                    </td>
                    <td colspan="1" style="padding: 8px; border: 1px solid;">
                        <?= $asset['income']; ?>
                    </td>
                </tr>
                <?php }  ?>
            </tbody>
        </table>
    </div>

    <!-- Contingent Liabilities -->
    <div class="" style="margin-bottom: 1rem;">
        <h2
            style="background-color: rgb(26, 51, 95); color: white; padding: 10px; font-size: 14px; font-weight: 600; margin: 0;">
            Contingent Liabilities (Personal Guarantees)
        </h2>
        <table style="border-collapse: collapse; width: 100%;">
            <thead>
                <tr>
                    <th style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243);font-size: 12px;">
                        Creditor </th>
                    <th style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243);font-size: 12px;">
                        Nature of PG (supplier vs lender)</th>
                    <th style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243);font-size: 12px;">
                        Unlimited Guarantee or Limit Value (£)</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($contingentLib as $asset) { ?>
                <tr>
                    <td colspan="1" style="padding: 8px; border: 1px solid;">
                        <?= $asset['creditor']; ?>
                    </td>
                    <td colspan="1" style="padding: 8px; border: 1px solid;">
                        <?= $asset['nature_of_pg']; ?>

                    </td>
                    <td colspan="1" style="padding: 8px; border: 1px solid;">
                        <?= $asset['unlimited_guarantee_or_limit_value']; ?>

                    </td>
                </tr>
                <?php }  ?>
            </tbody>
        </table>
    </div>

    <!-- Household Income -->
    <div class="" style="margin-bottom: 1rem;">
        <h2
            style="background-color: rgb(26, 51, 95); color: white; padding: 10px; font-size: 14px; font-weight: 600; margin: 0;">
            Household Income
        </h2>
        <table style="border-collapse: collapse; width: 100%;">
            <thead>
                <tr>
                    <th style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243);font-size: 12px;">
                        Type and sources, e.g., dividends, salary/employment, benefits, investments </th>
                    <th style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243);font-size: 12px;">
                        Who in your household?</th>
                    <th style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243);font-size: 12px;">
                        Gross Annual Income (£)</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($householdIncome as $asset) { ?>
                <tr>
                    <td colspan="1" style="padding: 8px; border: 1px solid;">
                        <?= $asset['who_in_household']; ?>
                    </td>
                    <td colspan="1" style="padding: 8px; border: 1px solid;">
                        <?= $asset['type_and_source']; ?>

                    </td>
                    <td colspan="1" style="padding: 8px; border: 1px solid;">
                        <?= $asset['gross_annual_income']; ?>

                    </td>
                </tr>
                <?php }  ?>
            </tbody>
        </table>
    </div>

    <!-- Unlisted Shares -->
    <div class="" style="margin-bottom: 1rem;">
        <h2
            style="background-color: rgb(26, 51, 95); color: white; padding: 10px; font-size: 14px; font-weight: 600; margin: 0;">
            Unlisted Shares
        </h2>
        <table style="border-collapse: collapse; width: 100%;">
            <thead>
                <tr>
                    <th style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243);font-size: 12px;">
                        Company Name</th>
                    <th style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243);font-size: 12px;">
                        Registration Number</th>
                    <th style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243);font-size: 12px;">
                        Status</th>
                    <th style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243);font-size: 12px;">
                        Registered</th>
                    <th style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243);font-size: 12px;">%
                        Shareholding</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($unlistedShares as $asset) { ?>
                <tr>
                    <td colspan="1" style="padding: 8px; border: 1px solid;">
                        <?= $asset['company_name']; ?>
                    </td>
                    <td colspan="1" style="padding: 8px; border: 1px solid;">
                        <?= $asset['reg_number']; ?>
                    </td>
                    <td colspan="1" style="padding: 8px; border: 1px solid;">
                        <?= $asset['status'] == 0 ? 'In-active' : 'Active'; ?>
                    </td>
                    <td colspan="1" style="padding: 8px; border: 1px solid;">
                        <?= $asset['registered']; ?>
                    </td>
                    <td colspan="1" style="padding: 8px; border: 1px solid;">
                        <?= $asset['shareholding_percentage']; ?>
                    </td>

                </tr>
                <?php }  ?>
            </tbody>
        </table>
    </div>

    <div class="notes" style="margin-bottom: 2rem;">
        <table>
            <tr style="padding:0;padding-bottom: 1rem">
                <th style="font-size: 12px;text-align: start;">*Please add any additional company’s not already included
                    in the above table.</th>
            </tr>
        </table>
    </div>

    <div class="notes" style="margin-bottom: 1rem;">
        <table>
            <tr>
                <th style="font-size: 12px;text-align: start;">Data Protection Acknowledgment, Consent and Declaration
                </th>
            </tr>
        </table>

        <p style="font-size: 12px;line-height: 18px;">By providing this information you agree and consent to Funding
            Alternative Group Ltd and any of its subsidiaries to (a) use and retain your personal information as
            reasonably required and according to our General Data Protection Regulation (GDPR) Policy <a
                href="#">(www.fundingalternative.co.uk/privacy-policy)</a>, (b) contact and enquire registered credit
            bureaux and other credit reporting agencies to investigate your creditworthiness including indebtedness,
            payment patterns, behaviour profile, (c) exchange with other credit providers or other third parties, your
            and your clients payment behaviour as well as any other information provided to us, and (d) contact, request
            and obtain information from other credit providers or third parties such as previous employers, trade and
            business partners and (e) act as personal Guarantor in relation to any possible lending arrangements made by
            us to your company. You are also consenting to continuing to receive electronic communications from our
            group of companies. You may unsubscribe or withdraw your consent at any time by sending an email to <a
                href="#">applications@fundingalternative.co.uk.</a></p>

        <p style="font-size: 12px;line-height: 18px;">The personal information we have collected from you will be shared
            with fraud prevention agencies who will use it to prevent fraud and money-laundering and to verify your
            identity. If fraud is detected, you could be refused certain services, finance, or employment. Further
            details of how your information will be used by us and these fraud prevention agencies, and your data
            protection rights, can be found in our <a href="#">privacy policy</a>.</p>

        <table>
            <tr>
                <th style="font-size: 12px;text-align: start;"> The proposed Guarantor confirms that (i) the above
                    information is true and correct to the best of my knowledge, (ii) I have disclosed all the
                    information pertaining to my creditworthiness, financial situation, assets and liabilities, and
                    sources of income and (iii) have not hidden any information that may reasonably influence my current
                    and future financial situation and the loan / lending application to Funding Alternative Group Ltd
                    or any of its subsidiaries. By signing this statement, the proposed Guarantor is aware that the
                    information supplied will be used by Funding Alternative Group Ltd for the purposes of making a
                    decision as to whether to provide a facility to the Company</th>
            </tr>
        </table>
    </div>


    <div class="footer-content" style="overflow-x:auto;">
        <table style="border-collapse: collapse; width: 100%;">
            <tr>

                <td style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); width: 110px;">
                    Guarantor signature</td>
                <td style="border: 1px solid;">
                    <?php if (!empty($directorInfo->signature)) { ?>
                        <img src="signatures/<?= $directorInfo->signature ?>" alt="Signature" style="width:60px;" />
                    <?php } ?>

                </td>


                <td style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); width: 110px;">Print
                    Full Name</td>
                <td style="padding: 8px; border: 1px solid;">
                    <?= $directorInfo->name; ?>
                </td>

                <td style="padding: 8px; border: 1px solid; background-color: rgb(217, 226, 243); width: 110px;">Date
                </td>
                <td style="padding: 8px; border: 1px solid;">
                    <?= date("d-m-Y"); ?>
                </td>
            </tr>
        </table>
    </div>

</div>