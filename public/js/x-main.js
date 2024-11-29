window.addEventListener('alpine:init', () => {

    Alpine.store('funding', {
        formatter: new Intl.NumberFormat('en-GB', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }),

        formatCurrency(value) {
            if (value === null || value === undefined || isNaN(Number(value.toString().replace(/,/g, '')))) {
                return `0.00`;
            }
            // Remove commas, parse to a number, then format
            return `${this.formatter.format(Number(value.toString().replace(/,/g, '')))}`;
        },
        stripCurrency(value) {
            return value.replace(/[^0-9.]/g, '');
        },
    });

    Alpine.data(
        'bcaMilestoneFilter',
        (bcaMilestoneStages, bcaTypeLoans, routes, sifMilestoneStages, bcaSubStages, sifSubStages) => ({
            // Loading and Error States
            loadingStates: { saveData: false, exportDoc: false, onModel: false },
            errors: { isError: false, errorList: {} },

            // Dropdown States and Data
            isMilestoneDropdownOpen: false,
            milestones: [],
            subMilestones: [],
            bcaMilestoneStages,
            sifMilestoneStages,
            bcaSubStages,
            sifSubStages,
            bcaTypeLoans,
            underWriter: window.appData?.committee_members || [],

            // Filters
            selectedMilestones: [],
            selectedAnalyst: '',
            filteredLoans: {},
            milestoneSearchQueries: {},

            // Modal Data
            modalData: {},

            // Toggle milestone dropdown
            toggleMilestoneDropdown() {
                this.isMilestoneDropdownOpen = !this.isMilestoneDropdownOpen;
            },

            // Toggle milestone selection
            toggleMilestone(milestone) {
                this.selectedMilestones = this.selectedMilestones.includes(milestone)
                    ? this.selectedMilestones.filter(m => m !== milestone)
                    : [...this.selectedMilestones, milestone];
                this.filterLoans();
            },

            // Filter loans by milestones and analysts
            filterLoans() {
                this.filteredLoans = this.selectedMilestones.length
                    ? this.filterByAnalyst(this.filterByMilestones(this.bcaTypeLoans))
                    : this.filterByAnalyst(this.bcaTypeLoans);
            },

             // Filter loans by business name within each milestone
             filterLoansByBusinessName(loans, milestone) {
                const searchQuery = this.milestoneSearchQueries[milestone] || '';
                return loans.filter(loan => loan.business_name.toLowerCase().includes(searchQuery.toLowerCase()));
            },

            // Filter by milestones
            filterByMilestones(loans) {
                return Object.fromEntries(
                    Object.entries(loans).filter(([milestone]) =>
                        this.selectedMilestones.includes(milestone)
                    )
                );
            },

            // Filter by analyst
            filterByAnalyst(loans) {
                if (!this.selectedAnalyst) return loans;
                return Object.fromEntries(
                    Object.entries(loans)
                        .map(([milestone, data]) => [
                            milestone,
                            { ...data, loans: data.loans.filter(loan => loan.analyst === this.selectedAnalyst) },
                        ])
                        .filter(([, data]) => data.loans.length > 0)
                );
            },

            // Clear all filters
            clearFilters() {
                this.selectedMilestones = [];
                this.selectedAnalyst = '';
                this.filteredLoans = this.bcaTypeLoans;
                this.clearCheckboxStates();
                this.milestoneSearchQueries = {};
            },

            clearSearchQuery(index) {                
                this.milestoneSearchQueries[index] = '';
                this.filterLoans();
            } ,           

            // Reset checkbox states
            clearCheckboxStates() {
                this.$refs['milestone-filter']?.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                    checkbox.checked = false;
                });
            },

            // Save changes to the server
            async saveChanges() {
                this.loadingStates.onModel = true;
                try {
                    const response = await fetch(routes.currentUrl, {
                        method: 'POST',
                        headers: this.getRequestHeaders(),
                        body: JSON.stringify(this.modalData),
                    });
                    const data = await response.json();
                    if (data.status) {
                        this.bcaTypeLoans = data.bcaTypeLoans;
                        this.filterLoans();
                        this.closeModal();
                    } else {
                        this.handleError(data.errors || { general: 'An error occurred.' });
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('An error occurred while fetching the loan details.');
                } finally {
                    this.loadingStates.onModel = false;
                }
            },

            // Get headers for AJAX request
            getRequestHeaders() {
                return {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest',
                };
            },

            // Handle errors
            handleError(errors) {
                this.errors = { isError: true, errorList: errors };
            },

            // Close modal
            closeModal() {
                const modal = document.querySelector('.modal.show');
                if (modal) bootstrap.Modal.getInstance(modal).hide();
            },

            // Load milestones and optionally preserve selection
            loadMilestones(type, preserveSelections = false) {
                const isBCA = type === 'BCA';
                const isSIF = type === 'SIF';

                this.milestones = isSIF ? this.sifMilestoneStages : isBCA ? this.bcaMilestoneStages : [];
                this.subMilestones = [];

                if (!preserveSelections) {
                    this.modalData.milestone = '';
                    this.modalData.sub_milestone = '';
                }

                if (isBCA && this.modalData.milestone) {
                    const matchedMilestone = this.milestones.find(m => m.milestone === this.modalData.milestone);
                    if (matchedMilestone) {
                        this.modalData.milestone = matchedMilestone.milestone;
                        this.filterBCASubMilestones(matchedMilestone.milestone);
                    }
                }
            },

            // Filter sub-milestones
            filterBCASubMilestones(milestoneText) {
                const subStage = this.modalData.loan_type === 'BCA' ? this.bcaSubStages : this.sifSubStages;
                const matchedMilestone = this.milestones.find(m => m.milestone === milestoneText);

                this.subMilestones = matchedMilestone
                    ? subStage.filter(stage => stage.milestone === matchedMilestone.id)
                    : [];
                this.modalData.sub_milestone = this.subMilestones.some(
                    sub => sub.sub_milestone === this.modalData.sub_milestone
                )
                    ? this.modalData.sub_milestone
                    : '';
            },

            // Open modal with preloaded data
            openModalWithData(loan) {
                this.modalData = { ...loan };
                this.errors = { isError: false, errorList: {} };
                this.loadMilestones(this.modalData.loan_type, true);                
            },

            // Initialize component
            init() {
                this.selectedMilestones = Object.keys(this.bcaTypeLoans).slice(0, 5); // Select first 5 milestones by default
                this.filterLoans();
            },
        })
    );

    Alpine.data('sifMilestoneFilter', (bcaMilestoneStages, sifTypeLoans, routes, sifMilestoneStages, bcaSubStages, sifSubStages) => ({
        // States
        loadingStates: { saveData: false, exportDoc: false, onModel: false },
        errors: { isError: false, errorList: {} },
        isMilestoneDropdownOpen: false,
        milestones: [],
        subMilestones: [],
        bcaMilestoneStages,
        sifMilestoneStages,
        bcaSubStages,
        sifSubStages,
        sifTypeLoans,
        underWriter: window.appData?.committee_members || [],

        // Filters
        selectedMilestones: [],
        selectedAnalyst: '',
        filteredLoans: {},
        milestoneSearchQueries: {},

        // Modal Data
        modalData: {},

        // Toggles the milestone dropdown
        toggleMilestoneDropdown() {
            this.isMilestoneDropdownOpen = !this.isMilestoneDropdownOpen;
        },

        // Toggles the selection of milestones
        toggleMilestone(milestone) {
            this.selectedMilestones = this.selectedMilestones.includes(milestone)
                ? this.selectedMilestones.filter(m => m !== milestone)
                : [...this.selectedMilestones, milestone];
            this.filterLoans();
        },

        // Filters the loans based on selected milestones and analyst
        filterLoans() {
            this.filteredLoans = this.selectedMilestones.length
                ? this.filterByAnalyst(this.filterByMilestones(this.sifTypeLoans))
                : this.filterByAnalyst(this.sifTypeLoans);
        },

        filterLoansByBusinessName(loans, milestone) {
            const searchQuery = this.milestoneSearchQueries[milestone] || '';
            return loans.filter(loan => loan.business_name.toLowerCase().includes(searchQuery.toLowerCase()));
        },

        filterByMilestones(loans) {
            return Object.fromEntries(
                Object.entries(loans).filter(([milestone]) =>
                    this.selectedMilestones.includes(milestone)
                )
            );
        },

        filterByAnalyst(loans) {
            if (!this.selectedAnalyst) return loans;
            return Object.fromEntries(
                Object.entries(loans)
                    .map(([milestone, data]) => [
                        milestone,
                        { ...data, loans: data.loans.filter(loan => loan.analyst === this.selectedAnalyst) },
                    ])
                    .filter(([, data]) => data.loans.length > 0)
            );
        },

        clearFilters() {
            this.selectedMilestones = [];
            this.selectedAnalyst = '';
            this.filteredLoans = this.sifTypeLoans;
            this.clearCheckboxStates();
            this.milestoneSearchQueries = {};
        },

        clearCheckboxStates() {
            this.$refs['milestone-filter']?.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                checkbox.checked = false;
            });
        },

        clearSearchQuery(index) {
            this.milestoneSearchQueries[index] = '';
            this.filterLoans();
        },

        async saveChanges() {
            this.loadingStates.onModel = true;
            try {
                const response = await fetch(routes.currentUrl, {
                    method: 'POST',
                    headers: this.getRequestHeaders(),
                    body: JSON.stringify(this.modalData),
                });
                const data = await response.json();
                if (data.status) {
                    this.sifTypeLoans = data.sifTypeLoans;
                    this.filterLoans();
                    this.closeModal();
                } else {
                    this.handleError(data.errors || { general: 'An error occurred.' });
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while fetching the loan details.');
            } finally {
                this.loadingStates.onModel = false;
            }
        },

        getRequestHeaders() {
            return {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
            };
        },

        handleError(errors) {
            this.errors = { isError: true, errorList: errors };
        },

        closeModal() {
            const modal = document.querySelector('.modal.show');
            if (modal) bootstrap.Modal.getInstance(modal).hide();
        },

        loadMilestones(type, preserveSelections = false) {
            const isBCA = type === 'BCA';
            const isSIF = type === 'SIF';

            this.milestones = isSIF ? this.sifMilestoneStages : isBCA ? this.bcaMilestoneStages : [];
            this.subMilestones = [];

            if (!preserveSelections) {
                this.modalData.milestone = '';
                this.modalData.sub_milestone = '';
            }

            if (isSIF && this.modalData.milestone) {
                const matchedMilestone = this.milestones.find(m => m.milestone === this.modalData.milestone);
                if (matchedMilestone) {
                    this.modalData.milestone = matchedMilestone.milestone;
                    this.filterSIFSubMilestones(matchedMilestone.milestone);
                }
            }
        },

        filterSIFSubMilestones(milestoneText) {
            const subStage = this.sifSubStages;
            if (milestoneText) {
                const matchedMilestone = this.milestones.find(m => m.milestone === milestoneText);
                this.subMilestones = matchedMilestone
                    ? subStage.filter(stage => stage.milestone === matchedMilestone.id)
                    : [];
                this.modalData.sub_milestone = this.subMilestones.some(
                    sub => sub.sub_milestone === this.modalData.sub_milestone
                )
                    ? this.modalData.sub_milestone
                    : '';
            } else {
                this.subMilestones = [];
            }
        },

        openModalWithData(loan) {
            this.modalData = { ...loan };
            this.errors = { isError: false, errorList: {} };
            this.loadMilestones(this.modalData.loan_type, true); // Preserving selections
        },

        init() {
            this.selectedMilestones = this.sifMilestoneStages.slice(0, 5).map(m => m.milestone); // Preselect first 5 milestones
            this.filterLoans();
        },
    }));


    Alpine.data('loanInfo', () => ({
        loanType: window.appData.data?.loan_type || '',
        milestones: [],
        subMilestones: [],
        selectedMilestone: window.appData.data?.milestone || '',
        selectedSubMilestone: window.appData.data?.sub_milestone || '',
        stages: {
            BCA: {
                milestones: window.appData.bcaMilestoneStages || [],
                subMilestones: window.appData.bcaSubStages || [],
            },
            SIF: {
                milestones: window.appData.sifMilestoneStages || [],
                subMilestones: window.appData.sifSubStages || [],
            },
        },
        newData: {},

        updateMilestones(preserveSelections = false) {
            const typeStages = this.stages[this.loanType] || { milestones: [], subMilestones: [] };
            this.milestones = typeStages.milestones;

            // Preserve selections only during initialization
            if (!preserveSelections) {
                this.resetSelections();
            }

            if (this.selectedMilestone) {
                this.filterSubMilestones(this.selectedMilestone, preserveSelections);
            } else {
                this.subMilestones = [];
            }
        },

        resetSelections() {
            this.selectedMilestone = '';
            this.selectedSubMilestone = '';
        },

        filterSubMilestones(selectedMilestone, preserveSelections = false) {
            const typeStages = this.stages[this.loanType] || { subMilestones: [] };
            const matchedMilestone = this.milestones.find(m => m.milestone === selectedMilestone);

            if (matchedMilestone) {
                this.subMilestones = typeStages.subMilestones.filter(
                    stage => stage.milestone === matchedMilestone.id
                );

                if (!preserveSelections || !this.subMilestones.some(sub => sub.sub_milestone === this.selectedSubMilestone)) {
                    // Only reset selectedSubMilestone if the current value is invalid or preserve is false
                    this.selectedSubMilestone = '';
                }
            } else {
                this.resetSelections();
            }
        },
        init() {
            this.updateMilestones(true); // Pass true to preserve selections during initialization
            this.$watch('loanType', () => {
                this.updateMilestones();
            });
        },
    }));


    Alpine.data('sop', (routes, data) => ({
        loadingStates: {
            saveData: false,
            exportPDF: false,
        },
        errors: {
            isError: false,
            errorList: []
        },
        message: '',
        isLoading: false,
        newData: {},
        assetInfo: data?.assetInfo || {},
        liabilitiesInfo: data?.liabilitiesInfo || {},
        directorInfo: data?.directorInfo || {},

        totalAssets: 0,
        totalLiabilities: 0,

        calculateTotals(type) {
            if (type === 'assets') {
                this.totalAssets = Array.from(document.querySelectorAll('.assets'))
                    .reduce((sum, input) => {
                        const value = input.value.replace(/,/g, ''); // Remove commas
                        input.value = Alpine.store('funding').formatCurrency(value);
                        return sum + (parseFloat(value) || 0);
                    }, 0)
                    .toFixed(2);
            } else if (type === 'liabilities') {
                this.totalLiabilities = Array.from(document.querySelectorAll('.liabilities'))
                    .reduce((sum, input) => {
                        const value = input.value.replace(/,/g, ''); // Remove commas
                        input.value = Alpine.store('funding').formatCurrency(value);
                        return sum + (parseFloat(value) || 0);
                    }, 0)
                    .toFixed(2);
            }
        },

        async saveData() {
            const self = this;

            const form = document.querySelector('form');
            const formData = new FormData(form);

            self.loadingStates.saveData = true;

            try {
                const response = await fetch(routes.currentUrl, {
                    method: 'POST',
                    body: formData,
                });

                const data = await response.json();

                if (data.success) {
                    const routeUrl = routes.sopPdf;

                    self.prompt(
                        'Success!',
                        'Would you like to download the submitted data as a PDF?',
                        routeUrl
                    );
                } else {
                    if (data.errors) {
                        const errorMessages = Object.values(data.errors).join('<br/>'); // Join all error messages into one string

                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            html: errorMessages,
                            confirmButtonText: 'OK'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'An error occurred!',
                            text: 'An unexpected error occurred. Please try again later.',
                            confirmButtonText: 'OK'
                        });
                    }
                }
            } catch (error) {
                console.error('Fetch error:', error);

                Swal.fire({
                    icon: 'error',
                    title: 'Network Error',
                    text: 'An unexpected error occurred. Please try again later.',
                    confirmButtonText: 'OK'
                });
            } finally {
                self.loadingStates.saveData = false;
            }
        },

        prompt(title, text, url) {
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
                    self.exportButton();
                } else {
                    // console.log();
                }
            });
        },

        exportButton() {
            const self = this;
            self.loadingStates.exportPDF = true;

            const form = document.querySelector("#sopForm");
            form.action = routes.sopPdf;

            form.submit();

            form.action = routes.currentUrl;

            setTimeout(() => {
                self.loadingStates.exportPDF = false;
            }, 2000);
        },

        init() {
            // Calculate totals for both columns on page load
            setTimeout(() => {
                this.calculateTotals('assets');
                this.calculateTotals('liabilities');
            }, 1000);

            // Attach event listeners for each column's inputs
            const assetInputs = document.querySelectorAll('.assets');
            const liabilityInputs = document.querySelectorAll('.liabilities');

            // add event listeners
            assetInputs.forEach(input => {
                input.addEventListener('blur', () => this.calculateTotals('assets'));
                input.addEventListener('focus', () => input.value = Alpine.store('funding').stripCurrency(input.value));
            });

            liabilityInputs.forEach(input => {
                input.addEventListener('blur', () => this.calculateTotals('liabilities'));
                input.addEventListener('focus', () => input.value = Alpine.store('funding').stripCurrency(input.value));
            });

        }

    }));

    Alpine.data('signaturePadComponent', () => ({
        signaturePad: null,
        signatureData: null,
        canvas: null,

        init() {
            this.canvas = this.$refs.canvas;

            // Bootstrap modal event listeners
            const modalElement = document.getElementById('signatureModel');

            modalElement.addEventListener('shown.bs.modal', () => {
                if (!this.signaturePad) {
                    this.initSignaturePad();
                }
            });
            window.addEventListener('resize', this.resizeCanvas.bind(this));
        },

        initSignaturePad() {
            if (!this.canvas) return;

            this.$nextTick(() => {
                setTimeout(() => {
                    this.resizeCanvas();
                    this.signaturePad = new SignaturePad(this.canvas);
                }, 50);
            });
        },

        resizeCanvas() {
            if (!this.canvas) return;

            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            this.canvas.width = this.canvas.offsetWidth * ratio;
            this.canvas.height = this.canvas.offsetHeight * ratio;
            this.canvas.getContext('2d').scale(ratio, ratio);
        },

        saveSignature() {
            if (!this.signaturePad || this.signaturePad.isEmpty()) {
                alert('Please provide a signature first.');
                return;
            }

            const dataUrl = this.signaturePad.toDataURL();
            this.signatureData = dataUrl;

            // Shift focus to the modal-triggering button
            const modalTriggerButton = document.getElementById('signatureModelBtn');
            modalTriggerButton.focus();
        },

        resetSignaturePad() {
            if (this.signaturePad) {
                this.signaturePad.clear();
            }

            this.signatureData = null;
        },
    }));

});


function generateUniqueKey() {
    return 'row_' + Math.random().toString(36).substr(2, 9) + '_' + Date.now();
}

function dynamicRow(initialData = [], singleRow = [], selectInputValue = 'n', totalsInput = {}) {
    return {
        selectInputValue: selectInputValue,
        rows: initialData.length > 0
            ? initialData.map(row => ({ ...row, key: generateUniqueKey() }))
            : singleRow.map(row => ({ ...row, key: generateUniqueKey() })),

        totals: totalsInput,
        addRow() {
            const newRow = singleRow[0];
            this.rows.push({ ...newRow, key: generateUniqueKey() });
            this.updateTotals();
        },

        removeRow(index) {
            if (this.rows.length > 1) {
                this.rows.splice(index, 1);
                this.updateTotals();
            }
        },

        updateTotals() {
            Object.keys(this.totals).forEach((key) => {
                this.totals[key] = this.rows.reduce((sum, row) => sum + (parseFloat(row[key]) || 0), 0);
            });
        },

        init() {
            this.updateTotals();
        }
    };
}






