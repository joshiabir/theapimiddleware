<x-app-layout :data=[0,0,0]>
    <x-slot name="page">{{ __('Setup') }}</x-slot>
    <x-slot name='crumb'></x-slot>

    <div class="max-w-2xl space-y-6">

        {{-- License error states --}}
        @if(isset($error) && in_array($error, ['date_expired', 'domain_expired', 'no_license']))
        <div class="bg-white border border-red-200 rounded-xl p-6">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-red-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                </svg>
                <div>
                    @if($error == 'date_expired')
                    <p class="text-sm font-medium text-red-700">Your license has expired.</p>
                    <p class="text-sm text-red-600 mt-1">Please contact <a href="mailto:sales@theapimiddleware.com" class="underline">sales@theapimiddleware.com</a> to renew.</p>
                    @elseif($error == 'domain_expired')
                    <p class="text-sm font-medium text-red-700">Domain limit reached.</p>
                    <p class="text-sm text-red-600 mt-1">Please contact <a href="mailto:sales@theapimiddleware.com" class="underline">sales@theapimiddleware.com</a> to add more domains.</p>
                    @elseif($error == 'no_license')
                    <p class="text-sm font-medium text-red-700">No license found.</p>
                    <p class="text-sm text-red-600 mt-1">Please contact <a href="mailto:sales@theapimiddleware.com" class="underline">sales@theapimiddleware.com</a>.</p>
                    @endif
                </div>
            </div>
        </div>

        @else

        {{-- Step indicator --}}
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-2">
                <span class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-semibold
                    {{ $step == '1' ? 'bg-blue-600 text-white' : 'bg-green-500 text-white' }}">
                    {{ $step == '1' ? '1' : '✓' }}
                </span>
                <span class="text-sm font-medium {{ $step == '1' ? 'text-zinc-900' : 'text-zinc-400' }}">Add Domain</span>
            </div>
            <div class="flex-1 h-px bg-zinc-200"></div>
            <div class="flex items-center gap-2">
                <span class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-semibold
                    {{ $step == '2' ? 'bg-blue-600 text-white' : 'bg-zinc-200 text-zinc-500' }}">2</span>
                <span class="text-sm font-medium {{ $step == '2' ? 'text-zinc-900' : 'text-zinc-400' }}">Activate</span>
            </div>
        </div>

        {{-- Step 1: Add Domain --}}
        @if($step == '1')
        <div class="bg-white border border-zinc-200 rounded-xl">
            <div class="px-6 py-4 border-b border-zinc-100">
                <p class="text-sm font-semibold text-zinc-800">Connect your backend</p>
                <p class="text-xs text-zinc-500 mt-0.5">Tell us where your API server lives and what domain will route through this gateway.</p>
            </div>
            <form action="{{ route('init') }}?step=1" method="POST" class="p-6 space-y-5">
                @csrf

                @if(isset($error) && $error != '')
                <div class="px-4 py-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">{{ $error }}</div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-1.5">Backend IP / Hostname</label>
                    <input type="text" name="ip" placeholder="192.168.1.1 or api.yourserver.com"
                        class="w-full px-3 py-2.5 text-sm bg-zinc-50 border border-zinc-200 rounded-lg text-zinc-800 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required />
                    <p class="text-xs text-zinc-400 mt-1.5">The server where your APIs are hosted. Can be an IP or domain.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-1.5">Gateway Domain</label>
                    <input type="text" name="domain_a" placeholder="api.yourdomain.com"
                        class="w-full px-3 py-2.5 text-sm bg-zinc-50 border border-zinc-200 rounded-lg text-zinc-800 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required />
                    <p class="text-xs text-zinc-400 mt-1.5">The public-facing domain that will point to this gateway. You'll configure DNS in the next step.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-1.5">Protocol</label>
                    <select name="protocol"
                        class="w-full px-3 py-2.5 text-sm bg-zinc-50 border border-zinc-200 rounded-lg text-zinc-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="https">HTTPS</option>
                        <option value="http">HTTP</option>
                    </select>
                    <p class="text-xs text-zinc-400 mt-1.5">Protocol used to connect to your backend server.</p>
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                        Continue
                    </button>
                </div>
            </form>
        </div>

        {{-- Step 2: DNS + Activate --}}
        @elseif($step == '2')
        <div class="bg-white border border-zinc-200 rounded-xl">
            <div class="px-6 py-4 border-b border-zinc-100">
                <p class="text-sm font-semibold text-zinc-800">Point your DNS to this gateway</p>
                <p class="text-xs text-zinc-500 mt-0.5">Create an A record for <span class="font-mono text-zinc-700">{{ $domain }}</span> pointing to the gateway server.</p>
            </div>
            <div class="p-6 space-y-5">
                <div class="bg-zinc-50 border border-zinc-200 rounded-lg overflow-hidden">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-zinc-200">
                                <th class="px-4 py-2.5 text-left text-xs font-medium text-zinc-400 uppercase tracking-wide">Type</th>
                                <th class="px-4 py-2.5 text-left text-xs font-medium text-zinc-400 uppercase tracking-wide">Name</th>
                                <th class="px-4 py-2.5 text-left text-xs font-medium text-zinc-400 uppercase tracking-wide">Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="px-4 py-3 font-mono text-xs text-zinc-700">A</td>
                                <td class="px-4 py-3 font-mono text-xs text-zinc-700">{{ $domain }}</td>
                                <td class="px-4 py-3 font-mono text-xs text-blue-600">{{ env('SERVER_IP', 'your-server-ip') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="flex items-start gap-3 px-4 py-3 bg-amber-50 border border-amber-200 rounded-lg">
                    <svg class="w-4 h-4 text-amber-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                    </svg>
                    <p class="text-xs text-amber-700">DNS changes can take a few minutes to propagate. Once updated, click Activate below.</p>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <a href="{{ route('init') }}?step=3&domain={{ $domain }}"
                        class="px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                        Activate Domain
                    </a>
                    <a href="{{ route('init') }}?step=1" class="text-sm text-zinc-500 hover:text-zinc-700">Back</a>
                </div>
            </div>
        </div>

        {{-- Step 3: Done --}}
        @elseif($step == '3')
        <div class="bg-white border border-zinc-200 rounded-xl p-8 text-center">
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                </svg>
            </div>
            <p class="text-base font-semibold text-zinc-900 mb-1">Domain activated</p>
            <p class="text-sm text-zinc-500 mb-6"><span class="font-mono">{{ $domain }}</span> is now routing through the gateway.</p>
            <a href="/dashboard/{{ base64_encode($domain) }}"
                class="inline-flex px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                Go to Dashboard
            </a>
        </div>
        @endif

        @endif
    </div>
</x-app-layout>
