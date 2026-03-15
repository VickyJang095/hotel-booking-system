<el-dialog>
    <dialog id="auth-dialog" aria-labelledby="auth-dialog-title" class="fixed inset-0 size-auto max-h-none max-w-none overflow-y-auto backdrop:bg-black/40 bg-transparent">
        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <el-dialog-panel class="relative w-full max-w-lg min-h-[520px] transform overflow-hidden rounded-3xl bg-white text-left shadow-xl transition-all">
                <!-- Header -->
                <div class="relative border-b border-gray-200 px-4 py-3 text-center">
                    <button id="backBtn" class="absolute hidden left-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 text-gray-500">
                        <i class="fa-solid fa-arrow-left text-gray-600 text-lg"></i>
                    </button>
                    <h2 id="auth-dialog-title" class="text-base font-bold text-gray-900 mt-2">
                        Log in or sign up
                    </h2>
                    <button command="close" commandfor="auth-dialog" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-xmark text-gray-600 text-lg"></i>
                    </button>
                </div>

                <!-- Step 1 -->
                <div class="px-14 py-10 text-center" id="step-email">
                    <h3 class="text-2xl font-bold text-gray-900">Welcome to Tripto</h3>

                    <div class="mt-6 text-left">
                        <label class="block text-base font-medium text-gray-500">Email address</label>
                        <input type="email" id="emailInput" placeholder="Enter your email address"
                            class="mt-1 w-full rounded-lg border border-gray-300 px-6 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500" />
                    </div>

                    <button id="continueBtn" disabled
                        class="mt-4 w-full cursor-not-allowed rounded-lg bg-gray-200 py-2 text-sm font-semibold text-gray-400">
                        Continue
                    </button>

                    <div class="my-5 flex items-center gap-3">
                        <div class="h-px flex-1 bg-gray-200"></div>
                        <span class="text-xs text-gray-400">or</span>
                        <div class="h-px flex-1 bg-gray-200"></div>
                    </div>

                    <div class="space-y-3">
                        <button class="flex w-full items-center justify-center gap-2 rounded-lg border border-gray-400 px-6 py-3 text-sm font-medium hover:bg-gray-50">
                            <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="h-5 w-5">
                            Continue with Google
                        </button>
                        <button class="flex w-full items-center justify-center gap-2 rounded-lg border border-gray-400 px-6 py-3 text-sm font-medium hover:bg-gray-50">
                            <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/apple.svg" class="h-5 w-5">
                            Continue with Apple
                        </button>
                        <button class="flex w-full items-center justify-center gap-2 rounded-lg border border-gray-400 px-6 py-3 text-sm font-medium hover:bg-gray-50">
                            <img src="https://www.svgrepo.com/show/475647/facebook-color.svg" class="h-5 w-5">
                            Continue with Facebook
                        </button>
                    </div>
                </div>

                <!-- Step 2 -->
                <div id="step-verify" class="hidden text-center px-14 py-10">
                    <p class="mb-4 text-base text-gray-500">
                        Enter verification code sent to <span id="emailText" class="font-medium"></span>
                    </p>
                    <div class="m-4 flex justify-center gap-3">
                        <input maxlength="1" class="otp-input">
                        <input maxlength="1" class="otp-input">
                        <input maxlength="1" class="otp-input">
                        <input maxlength="1" class="otp-input">
                    </div>

                    <button id="verifyBtn" disabled
                        class="m-4 w-full cursor-not-allowed rounded-lg bg-gray-200 py-2 text-sm font-semibold text-gray-400">
                        Verify
                    </button>

                    <p class="mt-4 text-sm text-gray-500">
                        Didn't receive email? Check your spam folder or request another code in
                        <span id="countdown">60</span> seconds
                    </p>

                    <button onclick="editEmail()" class="mt-3 text-base text-blue-600">
                        Edit Email
                    </button>
                </div>
            </el-dialog-panel>
        </div>
    </dialog>
</el-dialog>

<style>
    .otp-input {
        width: 48px;
        height: 48px;
        text-align: center;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 18px;
    }
</style>

<script>
    const emailInput = document.getElementById('emailInput');
    const continueBtn = document.getElementById('continueBtn');
    const verifyBtn = document.getElementById('verifyBtn');
    const otpInputs = document.querySelectorAll('.otp-input');

    // ── Enable/disable Continue button ──────────────────────
    emailInput.addEventListener('input', function() {
        const len = emailInput.value.length;
        if (len >= 8 && len <= 64) {
            continueBtn.disabled = false;
            continueBtn.className = 'mt-4 w-full rounded-lg bg-blue-600 py-2 text-sm font-semibold text-white hover:bg-blue-700';
        } else {
            continueBtn.disabled = true;
            continueBtn.className = 'mt-4 w-full cursor-not-allowed rounded-lg bg-gray-200 py-2 text-sm font-semibold text-gray-400';
        }
    });

    // ── Send OTP ─────────────────────────────────────────────
    continueBtn.addEventListener('click', async () => {
        const email = emailInput.value;

        await fetch('/auth/send-code', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                email
            })
        });

        document.getElementById('step-email').classList.add('hidden');
        document.getElementById('step-verify').classList.remove('hidden');
        document.getElementById('backBtn').classList.remove('hidden');
        document.getElementById('emailText').innerText = email;
    });

    // ── Back button ──────────────────────────────────────────
    document.getElementById('backBtn').addEventListener('click', () => {
        document.getElementById('step-email').classList.remove('hidden');
        document.getElementById('step-verify').classList.add('hidden');
        document.getElementById('backBtn').classList.add('hidden');
    });

    // ── OTP input auto-focus + enable Verify ─────────────────
    otpInputs.forEach((input, index) => {
        input.addEventListener('input', () => {
            if (input.value && otpInputs[index + 1]) {
                otpInputs[index + 1].focus();
            }
            const code = [...otpInputs].map(i => i.value).join('');
            if (code.length === 4) {
                verifyBtn.disabled = false;
                verifyBtn.className = 'm-4 w-full rounded-lg bg-blue-600 py-2 text-sm font-semibold text-white hover:bg-blue-700';
            }
        });

        // Backspace để quay lại ô trước
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && !input.value && otpInputs[index - 1]) {
                otpInputs[index - 1].focus();
            }
        });
    });

    // ── Verify OTP (single listener) ─────────────────────────
    verifyBtn.addEventListener('click', async () => {
        const code = [...otpInputs].map(i => i.value).join('');
        const email = emailInput.value;

        verifyBtn.disabled = true;
        verifyBtn.textContent = 'Verifying...';

        const response = await fetch('/auth/verify-code', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                email,
                code
            })
        });

        const data = await response.json();

        if (response.ok) {
            // Redirect theo role từ server
            window.location.href = data.redirect_url;
        } else {
            alert(data.message || 'Invalid code, please try again.');
            verifyBtn.disabled = false;
            verifyBtn.textContent = 'Verify';
        }
    });

    function editEmail() {
        document.getElementById('step-email').classList.remove('hidden');
        document.getElementById('step-verify').classList.add('hidden');
        document.getElementById('backBtn').classList.add('hidden');
        otpInputs.forEach(i => i.value = '');
    }
</script>