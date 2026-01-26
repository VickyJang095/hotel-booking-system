<el-dialog>
    <dialog id="auth-dialog" aria-labelledby="auth-dialog-title" class="fixed inset-0 size-auto max-h-none max-w-none overflow-y-auto backdrop:bg-black/40 bg-transparent">
        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <el-dialog-panel
                class="relative w-full max-w-lg min-h-[520px] transform overflow-hidden rounded-3xl bg-white text-left shadow-xl transition-all">

                <!-- Header -->
                <div class="relative border-b border-gray-200 px-4 py-3 text-center">
                    <h2
                        id="auth-dialog-title"
                        class="text-base font-bold text-gray-900">
                        Log in or sign up
                    </h2>

                    <!-- Close -->
                    <button
                        command="close"
                        commandfor="auth-dialog"
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Body -->
                <div class="px-14 py-10 text-center">
                    <h3 class="text-2xl font-bold text-gray-900">
                        Welcome to Tripto
                    </h3>

                    <!-- Email -->
                    <div class="mt-6 text-left">
                        <label class="block text-base font-medium text-gray-500">
                            Email address
                        </label>
                        <input
                            type="email"
                            placeholder="Enter your email address"
                            class="mt-1 w-full rounded-lg border border-gray-300 px-6 py-3 text-sm
                                   focus:border-blue-500 focus:ring-2 focus:ring-blue-500" />
                    </div>

                    <!-- Continue -->
                    <button
                        class="mt-4 w-full cursor-not-allowed rounded-lg bg-gray-200 py-2
                               text-sm font-semibold text-gray-400">
                        Continue
                    </button>

                    <!-- Divider -->
                    <div class="my-5 flex items-center gap-3">
                        <div class="h-px flex-1 bg-gray-200"></div>
                        <span class="text-xs text-gray-400">or</span>
                        <div class="h-px flex-1 bg-gray-200"></div>
                    </div>

                    <!-- Social -->
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
            </el-dialog-panel>
        </div>
    </dialog>
</el-dialog>