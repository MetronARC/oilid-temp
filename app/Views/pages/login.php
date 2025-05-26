<?= $this->extend('template/index') ?>
<?= $this->section('page-content') ?>


<a href="/" class="logo" target="_blank">
    <img src="<?= base_url('img/oilid.png') ?>" alt="" style="width: 75px; height: 50px;" />
</a>

<div class="section">
    <div class="container">
        <div class="row full-height justify-content-center">
            <div class="col-12 text-center align-self-center py-5">
                <div class="section pb-5 pt-5 pt-sm-2 text-center">
                    <h6 class="mb-0 pb-3">
                        <span>Inspector </span><span>Guest</span>
                    </h6>
                    <input
                        class="checkbox"
                        type="checkbox"
                        id="reg-log"
                        name="reg-log" />
                    <label for="reg-log"></label>
                    <div class="card-3d-wrap mx-auto">
                        <div class="card-3d-wrapper">
                            <div class="card-front">
                                <div class="center-wrap">
                                    <div class="section text-center">
                                        <h4 class="mb-4 pb-3">Inspector Login</h4>
                                        <div id="nfc-container" class="form-group mb-4">
                                            <div class="nfc-message">
                                                <button id="nfc-button" class="nfc-scan-button">
                                                    <i class="uil uil-nfc"></i>
                                                </button>
                                                <div class="loading-spinner" style="display: none;">
                                                    <div class="spinner"></div>
                                                    <p>Verifying card...</p>
                                                </div>
                                                <p class="mt-3">Click the button to scan your Inspector RFID card</p>
                                                <div id="nfc-status" class="mt-2"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-back">
                                <div class="center-wrap">
                                    <div class="section text-center">
                                        <h4 class="mb-4 pb-3">Guest Login</h4>
                                        <div class="form-group">
                                            <input
                                                type="text"
                                                name="logname"
                                                class="form-style"
                                                placeholder="Your User Name"
                                                id="logname"
                                                autocomplete="off" />
                                            <i class="input-icon uil uil-user"></i>
                                        </div>
                                        <div class="form-group mt-2">
                                            <input
                                                type="password"
                                                name="logpass"
                                                class="form-style"
                                                placeholder="Your Password"
                                                id="logpass"
                                                autocomplete="off" />
                                            <i class="input-icon uil uil-lock-alt"></i>
                                        </div>
                                        <a href="#" class="btn mt-4">Submit</a>
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

    /* Add styles for test mode section */
    .test-mode-section {
        border-top: 1px solid rgba(255,255,255,0.1);
        padding-top: 20px;
    }

    .test-mode-section h5 {
        color: #ffeba7;
        font-weight: 500;
    }
</style>
<script>
    // Check if we were redirected due to session expiry
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('session') === 'expired') {
        showErrorAlert('Your session has expired. Please log in again.');
    }

    let isScanning = false;

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

    // Function to show success message
    function showSuccessAlert(message) {
        hideLoading();
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: message
        });
    }

    // Function to show error message
    function showErrorAlert(message) {
        hideLoading();
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: message
        });
    }

    // Function to log messages on the page
    function log(message) {
        console.log(message);
        const statusElement = document.getElementById('nfc-status');
        statusElement.textContent = message;
    }

    // Function to validate RFID with the server
    async function processLogin(uid) {
        try {
            showLoading();
            log(`Initiating login process for UID: ${uid}`);

            const response = await fetch('<?= base_url('inspector/nfc_login') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: `uid=${encodeURIComponent(uid)}`
            });

            const data = await response.json();
            hideLoading();

            if (data.success) {
                showSuccessAlert(data.message);
                // Redirect after showing the success message
                setTimeout(() => {
                    window.location.href = '<?= base_url('dashboard') ?>';
                }, 1500);
            } else {
                if (data.redirect) {
                    // Session expired or unauthorized access
                    window.location.href = data.redirect;
                } else {
                    showErrorAlert(data.message);
                }
            }
        } catch (error) {
            hideLoading();
            showErrorAlert('An error occurred during login. Please try again.');
            console.error('Error:', error);
        }
    }

    // Function to start NFC scanning
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
            log("Scanning... Please tap your card");

            ndef.addEventListener("readingerror", () => {
                hideLoading();
                showErrorAlert("Cannot read data from the NFC tag. Try another one?");
            });

            ndef.addEventListener("reading", ({
                serialNumber
            }) => {
                log(`Card detected: ${serialNumber}`);
                processLogin(serialNumber);
                nfcButton.classList.remove('scanning');
                isScanning = false;
            });
        } catch (error) {
            nfcButton.classList.remove('scanning');
            isScanning = false;
            showErrorAlert(`Error: ${error.message || error}`);
        }
    }

    // Add click event listener to the NFC button
    document.addEventListener('DOMContentLoaded', function() {
        const nfcButton = document.getElementById('nfc-button');
        nfcButton.addEventListener('click', startNFCScanning);
    });

    async function logout() {
        try {
            // For a regular HTTP request, use a form submission
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= base_url('logout') ?>';
            document.body.appendChild(form);
            form.submit();
        } catch (error) {
            console.error('Error during logout:', error);
            showErrorAlert('An error occurred during logout');
        }
    }
</script>

<?= $this->endSection() ?>