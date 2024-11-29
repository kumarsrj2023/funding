function committeePaper(routes) {
    return {
        loadingStates: {
            saveData: false,
            exportDoc: false,
            onModel: false
        },
        isLoading: false,
        selectedLoan: null,
        table: null,
        formatter: new Intl.NumberFormat('en-GB', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }),
        currencySymbol: '£',
        loanInfo: {},
        gaCreditSafe: {},
        businessInfo: {},
        gaCreditSafeShareHolders: [],
        gaCreditSafeCountyCourtJudgements: [],
        wpIntroducersInfo: {},
        bcaPaymentFrequencyTypes: {},
        directors: [],
        priceModelData: [],
        bcaRePaymentTypes: [],
        wpCommitteePaper: {},
        propertiesAndAssets: [],
        $sectonTwoFileds: {
            'Full Name': 'name',
            'Date Of Birth': 'date_of_birth',
            'Nationality': 'nationality',
            'CIFAS Return': 'cifas_return',
            'Appointed Date': 'appointed_date',
            'Transunion': 'transunion',
            'Home Address': 'home_address'
        },
        newData: {
            website: '',
            allocationOfTheFunds: '',
            personalGuarantee: '',
            howTheyMakeTheirMoney: '',
            whyIRRChosen: '',
            weightedScorecard: '',
        },

        async getLoanDetails() {
            let self = this;

            if (!self.selectedLoan) {
                alert('Please select a loan.');
                return;
            }

            self.loadingStates.onModel = true;

            let dataToSend = {
                loanId: self.selectedLoan
            };

            try {
                const response = await fetch(routes.loanData, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(dataToSend),
                });

                const data = await response.json();

                if (data.status) {
                    self.loanInfo = data.data.loanInfo || {};
                    self.gaCreditSafe = data.data.gaCreditSafe || {};
                    self.businessInfo = data.data.businessInfo || {};
                    self.gaCreditSafeShareHolders = data.data.gaCreditSafeShareHolders;
                    self.gaCreditSafeCountyCourtJudgements = data.data.gaCreditSafeCountyCourtJudgements || [];
                    self.wpIntroducersInfo = data.data.wpIntroducersInfo || [];
                    self.bcaPaymentFrequencyTypes = data.data.bcaPaymentFrequencyTypes || [];
                    self.directors = data.data.directors || [];
                    self.priceModelData = data.data.priceModelData || {};
                    self.bcaRePaymentTypes = data.data.bcaRePaymentTypes || [];
                    self.wpCommitteePaper = data.data.wpCommitteePaper || {};
                    self.propertiesAndAssets = data.data.propertiesAndAssets || [];

                    self.closeModel('committeePaperModal');
                } else {
                    alert('Something went wrong.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while fetching the loan details.');
            } finally {
                self.loadingStates.onModel = false;
            }
        },

        mappedDirectors() {
            return this.directors.map(director => ({
                id: director.id,
                fullName: (director.title ? director.title + ' ' : '') + director.first_name + ' ' + (director.middle_name ? director.middle_name + ' ' : '') + director.surname,
                firstName: director.first_name,
                middleName: director.middle_name || 'N/A',
                surname: director.surname,
                title: director.title || 'N/A',
                fullNameAlt: director.name
            }));
        },

        async saveData() {
            const self = this;
            let dataToSend = {
                loanId: self.selectedLoan,
                loanInfo: self.loanInfo,
                gaCreditSafe: self.gaCreditSafe,
                businessInfo: self.businessInfo,
                gaCreditSafeShareHolders: self.gaCreditSafeShareHolders,
                gaCreditSafeCountyCourtJudgements: self.gaCreditSafeCountyCourtJudgements,
                wpIntroducersInfo: self.wpIntroducersInfo,
                directors: self.directors,
                bcaPaymentFrequencyTypes: self.bcaPaymentFrequencyTypes,
                priceModelData: self.priceModelData,
                bcaRePaymentTypes: self.bcaRePaymentTypes,
                wpCommitteePaper: self.wpCommitteePaper,
                newData: self.newData,
            };

            self.loadingStates.saveData = true;

            try {
                const response = await fetch(routes.saveCommitteePaperData, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(dataToSend),
                });

                const data = await response.json();

                if (data.status) {
                    const loanId = self.selectedLoan;
                    const routeUrl = routes.commiteePaperDoc.replace(':loanId', loanId);
                    self.prompt(
                        'Success!',
                        'Would you like to download the submitted data as a Word document?',
                        routeUrl,
                        loanId
                    );
                } else {
                    alert('Failed to save data. Please try again.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while saving the data.');
            } finally {
                self.loadingStates.saveData = false;
            }
        },

        prompt(title, text, url, loanId) {
            const self = this;

            Swal.fire({
                title: title || 'Data Saved Successfully?',
                text: text || "Do you want to download File?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, download it!',
                cancelButtonText: 'No, Thanks!',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                } else {
                    // console.log('User chose not to download the PDF.');
                }
            });
        },


        initTable() {
            const self = this;
            if (!self.table) {
                self.table = $('#loanTable').DataTable({
                    "pageLength": 15,
                    "scrollX": false,
                    "ordering": false,
                    "lengthChange": true,
                    "searching": true,
                    "responsive": true,
                    "processing": true,
                    "serverSide": true,
                    "language": {
                        "emptyTable": "{{ __('No result found') }}",
                        "search": ''
                    },
                    "layout": {
                        topStart: {
                            search: {
                                placeholder: 'Search...'
                            }
                        },
                        topEnd: function () {
                            return '';
                        }
                    },
                    ajax: {
                        url: window.location.href, // Your route for AJAX
                        type: 'GET',
                        dataSrc: function (json) {
                            if (json.data && json.data.length > 0) {
                                return json.data;
                            } else {
                                console.error("No data returned from server.");
                                return [];
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error("AJAX error:", error); // Log any errors
                        }
                    },
                    columns: [{
                        data: 'checkbox',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'loan_number'
                    },
                    {
                        data: 'advance_requested'
                    },
                    {
                        data: 'loan_purpose'
                    },
                    {
                        data: 'deal_status'
                    },
                    {
                        data: 'funded_date'
                    }
                    ],
                    pageLength: 10,
                    drawCallback: function (settings) { }
                });
            } else {
                self.table.ajax.reload();
            }
        },

        openModel(modalId) {
            const self = this;

            if (!self[modalId]) {
                self[modalId] = new bootstrap.Modal(document.getElementById(modalId), {
                    backdrop: 'static',
                    keyboard: false
                });
            }
            self[modalId].show();
        },

        closeModel(modalId) {
            const self = this;

            if (self[modalId]) {
                self[modalId].hide();
            }
        },

        formatDate(dateString) {
            if (!dateString) return;
            const date = new Date(dateString);
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are 0-indexed
            const year = String(date.getFullYear()); // Get last two digits of the year
            return `${day}-${month}-${year}`;
        },

        exportButton() {
            const self = this;
            self.loadingStates.exportDoc = true; // Set loading state to true

            const routeUrl = routes.commiteePaperDoc.replace(':loanId', self.selectedLoan);

            setTimeout(() => {
                window.location.href = routeUrl;
                self.loadingStates.exportDoc = false;
            }, 2000);
        },

        formatCurrency(value) {
            if (value === null || value === undefined || isNaN(value)) {
                return `${this.currencySymbol}0.00`;
            }
            return `${this.currencySymbol}${this.formatter.format(Number(value))}`;
        },

        stripCurrency(value) {
            return value.replace(/[^0-9.]/g, '');  // Remove non-numeric characters
        },

        init() {
            this.initTable();
            this.openModel('committeePaperModal');
        }
    }
}