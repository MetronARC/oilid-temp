<?= $this->extend('template/index') ?>
<?= $this->section('page-content') ?>

<!-- Add Bootstrap JS and Popper.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>

<a href="/" class="logo" target="_blank">
    <img src="<?= base_url('img/oilid.png') ?>" alt="" style="width: 75px; height: 50px;" />
</a>

<div class="section">
    <div class="container">
        <div class="row full-height justify-content-center">
            <div class="col-12 text-center align-self-center py-5">
                <div class="section pb-5 pt-5 pt-sm-2 text-center">
                    <div class="card-3d-wrap mx-auto">
                        <div class="card-3d-wrapper">
                            <div class="card-front">
                                <div class="center-wrap">
                                    <div class="section text-center">
                                        <h4 class="mb-4 pb-3">Inspect Item</h4>
                                        <div id="nfc-container" class="form-group">
                                            <div class="nfc-message">
                                                <button id="nfc-button" class="nfc-scan-button">
                                                    <i class="uil uil-nfc"></i>
                                                </button>
                                                <div class="loading-spinner" style="display: none;">
                                                    <div class="spinner"></div>
                                                    <p>Querying the Database...</p>
                                                </div>
                                                <p class="mt-3">Click the button to scan your item's RFID tag</p>
                                                <div id="nfc-status" class="mt-2"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Item Details Modal -->
<div class="modal fade" id="itemModal" tabindex="-1" aria-labelledby="itemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background-color: #2a2b38; color: #c4c3ca;">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="itemModalLabel" style="color: #ffeba7;">Item Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="itemImage" src="" alt="Item Image" class="img-fluid mb-4" style="max-height: 200px; width: auto;">
                <div class="item-details">
                    <div class="mb-3">
                        <span class="detail-label" style="color: #ffeba7;">Item Name:</span>
                        <h5 id="itemName" class="d-inline ms-2" style="color: #c4c3ca;"></h5>
                    </div>
                    <div class="mb-3">
                        <span class="detail-label" style="color: #ffeba7;">Item Type:</span>
                        <p id="itemType" class="d-inline ms-2 mb-0"></p>
                    </div>
                    <div class="mb-3">
                        <span class="detail-label" style="color: #ffeba7;">Last Inspection:</span>
                        <div id="lastInspection" class="inspection-section mt-2"></div>
                    </div>
                    <hr class="border-light">
                    <form id="inspectionForm" class="text-start">
                        <input type="hidden" id="itemUid" name="uid">
                        <div class="mb-3">
                            <label for="inspectionStatus" class="form-label" style="color: #ffeba7;">Inspection Status</label>
                            <select class="form-select bg-dark text-light" id="inspectionStatus" name="status" required>
                                <option value="">Select Status</option>
                                <option value="Accept">Accept</option>
                                <option value="Repair">Repair</option>
                                <option value="Failed">Failed</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="inspectionNotes" class="form-label" style="color: #ffeba7;">Inspection Notes</label>
                            <textarea class="form-control bg-dark text-light" id="inspectionNotes" name="notes" rows="3" placeholder="Enter inspection notes..."></textarea>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn" style="background: linear-gradient(to right, #ffeba7 0%, #f5ce62 100%); color: #102770;">
                                Submit Inspection
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .nfc-scan-button {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: linear-gradient(to right, #ffeba7 0%, #f5ce62 100%);
        border: none;
        cursor: pointer;
        transition: all 0.4s ease-in-out;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }

    .nfc-scan-button:hover {
        transform: scale(1.1);
        box-shadow: 0 8px 24px 0 rgba(16, 39, 112, .2);
    }

    .nfc-scan-button i {
        font-size: 48px;
        color: #102770;
    }

    .scanning .nfc-scan-button {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(245, 206, 98, 0.7);
        }

        70% {
            transform: scale(1.05);
            box-shadow: 0 0 0 20px rgba(245, 206, 98, 0);
        }

        100% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(245, 206, 98, 0);
        }
    }

    /* Modal Styles */
    .modal-content {
        border: none;
        box-shadow: 0 8px 24px 0 rgba(0,0,0,.2);
    }
    .btn-close-white {
        filter: invert(1) grayscale(100%) brightness(200%);
    }

    /* Add these new styles */
    .item-details {
        text-align: left;
        padding: 0 20px;
    }
    .detail-label {
        font-weight: 600;
        font-size: 1.1rem;
    }
    #itemName, #itemType {
        font-size: 1.1rem;
    }

    .loading-spinner {
        margin-top: 20px;
        text-align: center;
    }

    .spinner {
        width: 40px;
        height: 40px;
        margin: 0 auto;
        border: 4px solid #f3f3f3;
        border-top: 4px solid #ffeba7;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .loading-spinner p {
        margin-top: 10px;
        color: #ffeba7;
    }

    /* Add these new styles */
    .form-select, .form-control {
        border: 1px solid #ffeba7;
    }
    
    .form-select:focus, .form-control:focus {
        border-color: #f5ce62;
        box-shadow: 0 0 0 0.25rem rgba(245, 206, 98, 0.25);
    }
    
    .form-select option {
        background-color: #2a2b38;
        color: #c4c3ca;
    }
    
    hr.border-light {
        border-color: rgba(255,255,255,0.1);
        margin: 1.5rem 0;
    }

    /* Add these new styles for status badges */
    .status-badge {
        padding: 4px 8px;
        border-radius: 4px;
        font-weight: 600;
        display: inline-block;
    }
    .status-Accept {
        background-color: #28a745;
        color: white;
    }
    .status-Repair {
        background-color: #ffc107;
        color: black;
    }
    .status-Failed {
        background-color: #dc3545;
        color: white;
    }
    .inspection-section {
        background-color: rgba(255, 255, 255, 0.05);
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 15px;
    }
    .inspection-notes {
        font-style: italic;
        color: #a9a9b1;
        margin-top: 8px;
    }
    .inspection-meta {
        font-size: 0.9rem;
        color: #8e8e96;
        margin-top: 5px;
    }

    /* Add styles for test mode section */
    .test-mode-section {
        border-top: 1px solid rgba(255,255,255,0.1);
        padding-top: 20px;
        margin-top: 20px;
    }

    .test-mode-section h5 {
        color: #ffeba7;
        font-weight: 500;
    }
</style>

<script>
    let isScanning = false;
    let itemModal = null;

    // Function to show loading state
    function showLoading() {
        const loadingSpinner = document.querySelector('.loading-spinner');
        const nfcButton = document.getElementById('nfc-button');
        loadingSpinner.style.display = 'block';
        nfcButton.style.display = 'none';
    }

    // Function to hide loading state
    function hideLoading() {
        const loadingSpinner = document.querySelector('.loading-spinner');
        const nfcButton = document.getElementById('nfc-button');
        loadingSpinner.style.display = 'none';
        nfcButton.style.display = 'block';
    }

    // Initialize the modal when the document is ready
    document.addEventListener('DOMContentLoaded', function() {
        itemModal = new bootstrap.Modal(document.getElementById('itemModal'));
    });

    function showSuccessModal(data) {
        hideLoading();
        document.getElementById('itemImage').src = `<?= base_url('itemImage/') ?>/${data.item_image}`;
        document.getElementById('itemName').textContent = data.item_name;
        document.getElementById('itemType').textContent = data.item_type;
        document.getElementById('itemUid').value = data.item_uid;
        
        // Format and display last inspection info
        const lastInspectionElement = document.getElementById('lastInspection');
        if (data.last_inspection_date && data.last_status !== 'No Previous Inspection') {
            const inspectionDate = new Date(data.last_inspection_date);
            const formattedDate = inspectionDate.toLocaleString('en-US', {
                day: 'numeric',
                month: 'long',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: true
            });
            
            lastInspectionElement.innerHTML = `
                <div>
                    <span class="status-badge status-${data.last_status}">${data.last_status}</span>
                </div>
                <div class="inspection-meta">
                    <div>Date: ${formattedDate}</div>
                    ${data.inspection_user ? `<div>Inspected by: ${data.inspection_user}</div>` : ''}
                </div>
                ${data.last_notes ? `
                    <div class="inspection-notes">
                        <strong>Notes:</strong><br>
                        ${data.last_notes}
                    </div>
                ` : ''}
            `;
        } else {
            lastInspectionElement.innerHTML = '<em>No previous inspection</em>';
        }
        
        // Reset form
        document.getElementById('inspectionForm').reset();
        
        if (itemModal) {
            itemModal.show();
        } else {
            console.error('Modal not initialized');
            itemModal = new bootstrap.Modal(document.getElementById('itemModal'));
            itemModal.show();
        }
    }

    function showErrorAlert(message, errorType = null, details = null) {
        hideLoading();
        let errorMessage = message;
        
        // Add additional context based on error type
        if (errorType) {
            switch (errorType) {
                case 'database_error':
                    errorMessage = 'Database Error: ' + message;
                    break;
                case 'not_found':
                    errorMessage = `Item was not found in the database`;
                    break;
                case 'validation_error':
                    errorMessage = 'Validation Error: ' + message;
                    break;
                case 'request_error':
                    errorMessage = 'Request Error: ' + message;
                    break;
                case 'server_error':
                    errorMessage = 'Server Error: ' + message;
                    break;
            }
        }

        Swal.fire({
            icon: 'error',
            title: errorType ? errorType.replace('_', ' ').toUpperCase() : 'Error',
            text: errorMessage,
            background: '#2a2b38',
            color: '#c4c3ca',
            confirmButtonColor: '#ffeba7',
        });

        // Log the error for debugging
        console.error('Error Details:', {
            type: errorType,
            message: message,
            details: details
        });
    }

    function log(message) {
        console.log(message);
        const statusElement = document.getElementById('nfc-status');
        statusElement.textContent = message;
    }

    async function processRFID(serialNumber) {
        try {
            showLoading();
            const response = await fetch('<?= base_url('read/item_inspect') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: `uid=${encodeURIComponent(serialNumber)}`
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();

            if (data.success) {
                showSuccessModal(data.item);
            } else {
                showErrorAlert(
                    data.message,
                    data.error_type,
                    {
                        uid: data.uid,
                        status: response.status,
                        statusText: response.statusText
                    }
                );
            }
        } catch (error) {
            showErrorAlert(
                'An error occurred while processing your request. Please try again.',
                'network_error',
                {
                    error: error.message,
                    stack: error.stack
                }
            );
            console.error('Error:', error);
        }
    }

    async function startNFCScanning() {
        if (isScanning) return;

        if (!("NDEFReader" in window)) {
            showErrorAlert("Web NFC is not available on your device.");
            return;
        }

        try {
            const nfcButton = document.getElementById('nfc-container');
            nfcButton.classList.add('scanning');
            isScanning = true;

            log("Initializing NFC scanning...");
            const ndef = new NDEFReader();
            await ndef.scan();
            log("Scanning... Please tap your item's RFID tag");

            ndef.addEventListener("readingerror", () => {
                showErrorAlert("Cannot read data from the NFC tag. Try another one?");
                nfcButton.classList.remove('scanning');
                isScanning = false;
            });

            ndef.addEventListener("reading", ({ serialNumber }) => {
                log(`Tag detected: ${serialNumber}`);
                processRFID(serialNumber);
                nfcButton.classList.remove('scanning');
                isScanning = false;
            });
        } catch (error) {
            nfcButton.classList.remove('scanning');
            isScanning = false;
            showErrorAlert(`Error: ${error.message || error}`);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const nfcButton = document.getElementById('nfc-button');
        nfcButton.addEventListener('click', startNFCScanning);

        // Add form submission handler
        const inspectionForm = document.getElementById('inspectionForm');
        inspectionForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            try {
                const response = await fetch('<?= base_url('read/save_inspection') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: new URLSearchParams(formData)
                });

                const data = await response.json();

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Inspection data saved successfully',
                        background: '#2a2b38',
                        color: '#c4c3ca',
                        confirmButtonColor: '#ffeba7'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            itemModal.hide();
                            window.location.reload(); // Reload the page after clicking OK
                        }
                    });
                } else {
                    if (data.error_type === 'auth_error') {
                        // Redirect to login page if session expired
                        window.location.href = '<?= base_url('login') ?>';
                    } else {
                        showErrorAlert(data.message, data.error_type);
                    }
                }
            } catch (error) {
                showErrorAlert('Failed to save inspection data', 'network_error');
                console.error('Error:', error);
            }
        });
    });
</script>

<?= $this->endSection() ?>
