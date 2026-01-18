<div x-data="{ step: 1, email: '' }" class="relative z-50">
    
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                
                <div class="border-b border-gray-100 px-6 py-4 flex items-center justify-between">
                    <div class="w-6">
                        <button x-show="step === 2" @click="step = 1" class="text-gray-800 hover:text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                            </svg>
                        </button>
                    </div>
                    
                    <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">Log in or sign up</h3>
                    
                    <button type="button" class="w-6 text-gray-400 hover:text-gray-500 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="px-6 py-8">

                    <div x-show="step === 1" x-transition:enter="transition ease-out duration-300">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-6">Welcome to Tripto</h2>
                        
                        <div class="space-y-4">
                            <div>
                                <input type="email" x-model="email" placeholder="Email address" 
                                    class="block w-full rounded-lg border-0 py-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6">
                            </div>
                            
                            <button @click="if(email) step = 2" :disabled="!email"
                                :class="email ? 'bg-gray-100 hover:bg-gray-200 text-gray-900' : 'bg-gray-100 text-gray-400 cursor-not-allowed'"
                                class="flex w-full justify-center rounded-lg px-3 py-3 text-sm font-semibold shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-colors">
                                Continue
                            </button>
                        </div>

                        <div class="relative my-6">
                            <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                <div class="w-full border-t border-gray-200"></div>
                            </div>
                            <div class="relative flex justify-center">
                                <span class="bg-white px-2 text-sm text-gray-500">or</span>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <button type="button" class="flex w-full items-center justify-between rounded-lg bg-white px-3 py-3 text-sm font-medium text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                                </svg>
                                <span class="flex-1 text-center">Continue with Google</span>
                                <span class="w-5"></span>
                            </button>

                            <button type="button" class="flex w-full items-center justify-between rounded-lg bg-white px-3 py-3 text-sm font-medium text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M17.05 20.28c-.98.95-2.05.88-3.08.47-1.07-.42-2.03-.42-3.15.02-.93.37-2.1.47-3.13-.59-2.52-2.61-2.13-7.53 1.84-9.15 1.03-.42 2.03-.1 2.85.24.77.33 1.5.3 2.14-.02.94-.48 2.45-.63 3.66.02.5.27 2.15 1.25 2.18 1.27-1.89.04-2.83 1.34-2.83 3.1 0 2.46 1.63 4.27 1.63 4.27s-1.12 3.14-2.11 4.37zM12.03 7.25c-.14-2.45 2.02-3.76 2.02-3.76s-1.82-1.92-4.13-1.63c-2.3.29-3.72 2.18-3.72 2.18.91 2.89 2.21 4.54 3.78 4.6.93.03 1.65-.62 2.05-1.39z"/>
                                </svg>
                                <span class="flex-1 text-center">Continue with Apple</span>
                                <span class="w-5"></span>
                            </button>

                            <button type="button" class="flex w-full items-center justify-between rounded-lg bg-white px-3 py-3 text-sm font-medium text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                <svg class="h-5 w-5 text-[#1877F2]" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                                <span class="flex-1 text-center">Continue with Facebook</span>
                                <span class="w-5"></span>
                            </button>
                        </div>
                    </div>

                    <div x-show="step === 2" x-transition:enter="transition ease-out duration-300" style="display: none;">
                        <div class="mb-6">
                            <p class="text-sm text-gray-600">
                                Enter verification code has sent <strong class="text-gray-900" x-text="email"></strong>
                            </p>
                        </div>

                        <div class="flex justify-between gap-3 mb-6">
                            <template x-for="i in 4">
                                <input type="text" maxlength="1" 
                                    class="w-14 h-14 rounded-lg border border-gray-300 text-center text-2xl font-semibold text-gray-900 focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none transition-all shadow-sm">
                            </template>
                        </div>

                        <button disabled class="flex w-full justify-center rounded-lg bg-gray-100 px-3 py-3 text-sm font-semibold text-gray-400 shadow-sm cursor-not-allowed mb-4">
                            Verify email
                        </button>

                        <div class="text-center space-y-2">
                            <p class="text-xs text-gray-500">
                                Didn't receive email? Check your spam folder or request another code in <span class="text-gray-900 font-medium">58 seconds</span>
                            </p>
                            <button @click="step = 1" class="text-sm font-semibold text-blue-600 hover:text-blue-500">
                                Edit Email
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>