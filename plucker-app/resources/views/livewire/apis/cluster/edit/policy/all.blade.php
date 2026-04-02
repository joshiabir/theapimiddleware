<div class="space-y-6">
    @if(session('status'))
    <div class="flex items-center gap-2 p-3 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg">
        <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/></svg>
        <span><span class="font-medium">Policy saved.</span></span>
    </div>
    @endif

    {{-- What is a policy --}}
    <div class="flex items-start gap-3 px-4 py-3 bg-blue-50 border border-blue-100 rounded-xl">
        <svg class="w-4 h-4 text-blue-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
        </svg>
        <div class="text-xs text-blue-700 space-y-1">
            <p>A <span class="font-semibold">cluster policy</span> defines the security and access rules applied to every API inside a cluster. A policy can be shared across multiple clusters.</p>
            <p>These settings <span class="font-semibold">override</span> the domain-level defaults from the Config page for any API in this cluster. The domain rate limit still acts as the outer ceiling.</p>
        </div>
    </div>

    <form wire:submit="save">

        {{-- Identity --}}
        <div class="bg-white border border-zinc-200 rounded-xl overflow-hidden mb-6">
            <div class="px-5 py-3 border-b border-zinc-100">
                <p class="text-xs text-zinc-500 uppercase tracking-wide font-medium">Policy Details</p>
            </div>
            <div class="p-5 space-y-4">
                <div>
                    <label class="block mb-1.5 text-xs font-medium text-zinc-500 uppercase tracking-wide">Policy Name</label>
                    <input type="text" wire:model="name" required placeholder="e.g. Default Cluster Policy"
                           class="w-full px-3 py-2 bg-zinc-50 border border-zinc-200 rounded-md text-sm text-zinc-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"/>
                    <p class="text-xs text-zinc-400 mt-1.5">A descriptive name helps identify this policy when assigning it to multiple clusters.</p>
                </div>
                <div>
                    <label class="block mb-1.5 text-xs font-medium text-zinc-500 uppercase tracking-wide">Description</label>
                    <textarea wire:model="description" rows="3" placeholder="Optional description"
                              class="w-full px-3 py-2 bg-zinc-50 border border-zinc-200 rounded-md text-sm text-zinc-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"></textarea>
                </div>
            </div>
        </div>

        {{-- Logging --}}
        <div class="bg-white border border-zinc-200 rounded-xl overflow-hidden mb-6">
            <div class="px-5 py-3 border-b border-zinc-100">
                <p class="text-xs text-zinc-500 uppercase tracking-wide font-medium">Logging</p>
            </div>
            <div class="px-5 pt-3 pb-1">
                <p class="text-xs text-zinc-400">Controls what is recorded for each request passing through this cluster. Logs are viewable under the Logs section.</p>
            </div>
            <div class="divide-y divide-zinc-50">
                <div class="px-5 py-4 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-zinc-800">Log HTTP/S Requests</p>
                        <p class="text-xs text-zinc-400 mt-0.5">Record all request and response headers and body for every call. Disable only on very high-volume, low-risk APIs where storage is a concern.</p>
                    </div>
                    <label class="inline-flex items-center cursor-pointer shrink-0 ml-8">
                        <input type="checkbox" value="" class="sr-only peer" wire:model='logging_http'>
                        <div class="relative w-9 h-5 bg-zinc-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-zinc-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-500"></div>
                    </label>
                </div>
                <div class="px-5 py-4 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-zinc-800">GDPR Compliant Logging</p>
                        <p class="text-xs text-zinc-400 mt-0.5">Automatically redact PII (names, emails, IDs) from logs before they are stored. Recommended for any APIs handling personal data to comply with GDPR, HIPAA, and similar regulations.</p>
                    </div>
                    <label class="inline-flex items-center cursor-pointer shrink-0 ml-8">
                        <input type="checkbox" value="" class="sr-only peer" wire:model='logging_gdpr'>
                        <div class="relative w-9 h-5 bg-zinc-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-zinc-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-500"></div>
                    </label>
                </div>
                <div class="px-5 py-4 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-zinc-800">Redact Authentication Headers</p>
                        <p class="text-xs text-zinc-400 mt-0.5">Strip <span class="font-mono text-zinc-500">Authorization</span> headers and <span class="font-mono text-zinc-500">?authorization</span> query parameters before writing to logs. Prevents API keys and bearer tokens from appearing in stored logs.</p>
                    </div>
                    <label class="inline-flex items-center cursor-pointer shrink-0 ml-8">
                        <input type="checkbox" value="" class="sr-only peer" wire:model='redact_auth'>
                        <div class="relative w-9 h-5 bg-zinc-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-zinc-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-500"></div>
                    </label>
                </div>
            </div>
        </div>

        {{-- Authentication --}}
        <div class="bg-white border border-zinc-200 rounded-xl overflow-hidden mb-6">
            <div class="px-5 py-3 border-b border-zinc-100">
                <p class="text-xs text-zinc-500 uppercase tracking-wide font-medium">Authentication</p>
            </div>
            <div class="px-5 pt-3 pb-1">
                <p class="text-xs text-zinc-400">Controls whether callers must prove their identity before reaching your backend APIs.</p>
            </div>
            <div class="divide-y divide-zinc-50">
                <div class="px-5 py-4 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-zinc-800">Require Authentication Header</p>
                        <p class="text-xs text-zinc-400 mt-0.5">Reject any request that does not include an <span class="font-mono text-zinc-500">Authorization</span> header or <span class="font-mono text-zinc-500">?authorization</span> parameter. The gateway checks for presence only — your backend is still responsible for validating the token.</p>
                    </div>
                    <label class="inline-flex items-center cursor-pointer shrink-0 ml-8">
                        <input type="checkbox" value="" class="sr-only peer" wire:model='required_auth'>
                        <div class="relative w-9 h-5 bg-zinc-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-zinc-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-500"></div>
                    </label>
                </div>
                <div class="px-5 py-4 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-zinc-800">Enforce Encryption</p>
                        <p class="text-xs text-zinc-400 mt-0.5">Require all requests to arrive over HTTPS. Plain HTTP requests are rejected with a <span class="font-mono text-zinc-500">403</span>. Use <span class="font-medium text-zinc-600">HTTP</span> only for internal or development clusters where TLS is terminated upstream.</p>
                    </div>
                    <select wire:model='encryption'
                            class="ml-8 px-3 py-2 bg-zinc-50 border border-zinc-200 rounded-md text-sm text-zinc-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="http">HTTP (no enforcement)</option>
                        <option value="https">HTTPS only</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Throttling --}}
        <div class="bg-white border border-zinc-200 rounded-xl overflow-hidden mb-6">
            <div class="px-5 py-3 border-b border-zinc-100">
                <p class="text-xs text-zinc-500 uppercase tracking-wide font-medium">Throttling</p>
            </div>
            <div class="px-5 pt-3 pb-1">
                <p class="text-xs text-zinc-400">Cluster-level rate limits are enforced <span class="font-medium text-zinc-600">in addition to</span> the domain-level limit set in Config. A request must pass both. Set cluster limits lower than your domain default to protect sensitive APIs more aggressively.</p>
            </div>
            <div class="divide-y divide-zinc-50">
                <div class="px-5 py-4 flex items-center justify-between gap-8">
                    <div>
                        <p class="text-sm font-medium text-zinc-800">Cluster Global Rate Limit</p>
                        <p class="text-xs text-zinc-400 mt-0.5">Maximum total requests per minute across all users for this cluster combined. Protects cluster APIs from bulk traffic spikes regardless of the source.</p>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <input type="number" wire:model='global_rpm' required placeholder="60"
                               class="w-24 px-3 py-2 bg-zinc-50 border border-zinc-200 rounded-md text-sm text-zinc-700 font-mono text-right focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                        <span class="text-xs text-zinc-400 whitespace-nowrap">req / min</span>
                    </div>
                </div>
                <div class="px-5 py-4 flex items-center justify-between gap-8">
                    <div>
                        <p class="text-sm font-medium text-zinc-800">Cluster Per-User Rate Limit</p>
                        <p class="text-xs text-zinc-400 mt-0.5">Maximum requests per minute from a single IP address to this cluster. Prevents individual clients from monopolising capacity or performing enumeration attacks.</p>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <input type="number" wire:model='user_rpm' required placeholder="10"
                               class="w-24 px-3 py-2 bg-zinc-50 border border-zinc-200 rounded-md text-sm text-zinc-700 font-mono text-right focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                        <span class="text-xs text-zinc-400 whitespace-nowrap">req / min</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Security --}}
        <div class="bg-white border border-zinc-200 rounded-xl overflow-hidden mb-6">
            <div class="px-5 py-3 border-b border-zinc-100">
                <p class="text-xs text-zinc-500 uppercase tracking-wide font-medium">Security</p>
            </div>
            <div class="divide-y divide-zinc-50">
                <div class="px-5 py-4 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-zinc-800">Anomaly Detection</p>
                        <p class="text-xs text-zinc-400 mt-0.5">Monitor traffic patterns and flag requests that deviate from normal behaviour (unusual frequency, payload size, or timing). Flagged requests are recorded as incidents and visible in the Security section.</p>
                    </div>
                    <label class="inline-flex items-center cursor-pointer shrink-0 ml-8">
                        <input type="checkbox" wire:model='anomally_detection' value="" class="sr-only peer">
                        <div class="relative w-9 h-5 bg-zinc-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-zinc-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-500"></div>
                    </label>
                </div>
                <div class="px-5 py-4 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-zinc-800">Honey Pots</p>
                        <p class="text-xs text-zinc-400 mt-0.5">Expose decoy API endpoints that legitimate clients should never call. Any request hitting a honey pot is logged as a security incident, helping identify scanners, bots, and attackers probing your API surface.</p>
                    </div>
                    <label class="inline-flex items-center cursor-pointer shrink-0 ml-8">
                        <input type="checkbox" value="" wire:model='honey_pots' class="sr-only peer">
                        <div class="relative w-9 h-5 bg-zinc-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-zinc-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-green-500"></div>
                    </label>
                </div>
                <div class="px-5 py-4 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-zinc-800">DLP Action</p>
                        <p class="text-xs text-zinc-400 mt-0.5">What to do when a DLP keyword or pattern match is found in a request or response body. The detection list (keywords and patterns) is configured under <span class="font-medium text-zinc-600">Config → DLP</span>.
                            <span class="block mt-1 space-y-0.5">
                                <span class="block"><span class="font-medium text-zinc-600">No Action</span> — pass through silently.</span>
                                <span class="block"><span class="font-medium text-zinc-600">Alert Only</span> — forward the request but log the match as an incident.</span>
                                <span class="block"><span class="font-medium text-zinc-600">Redact</span> — replace matched values with <span class="font-mono">[REDACTED]</span> before forwarding.</span>
                                <span class="block"><span class="font-medium text-zinc-600">Block</span> — reject the request entirely with a 403.</span>
                            </span>
                        </p>
                    </div>
                    <select wire:model='pii_dlp'
                            class="ml-8 px-3 py-2 bg-zinc-50 border border-zinc-200 rounded-md text-sm text-zinc-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="no-action">No Action</option>
                        <option value="alert">Alert Only</option>
                        <option value="redact">Redact</option>
                        <option value="block">Block</option>
                    </select>
                </div>

                {{-- DLP list --}}
                <div class="px-5 py-4">
                    <p class="text-xs font-medium text-zinc-500 uppercase tracking-wide mb-1">Cluster-Specific DLP Overrides</p>
                    <p class="text-xs text-zinc-400 mb-3">Additional keywords or patterns that apply only to this cluster, on top of the domain-wide DLP list in Config.</p>
                    <table class="w-full text-sm mb-3">
                        <thead>
                            <tr class="border-b border-zinc-100">
                                <th class="pb-2 text-left text-xs font-medium text-zinc-400 uppercase tracking-wide">Type</th>
                                <th class="pb-2 text-left text-xs font-medium text-zinc-400 uppercase tracking-wide">Value</th>
                                <th class="pb-2 text-center text-xs font-medium text-zinc-400 uppercase tracking-wide"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-50">
                            @foreach($dlp_list as $list)
                            <tr>
                                <td class="py-2 pr-4 text-xs text-zinc-500">{{ $list->type }}</td>
                                <td class="py-2 pr-4 text-xs font-mono text-zinc-700">{{ $list->value }}</td>
                                <td class="py-2 text-center">
                                    <button type="button" wire:click="remove_list('{{ $list->id }}')"
                                            class="text-xs text-red-500 hover:text-red-700 hover:underline">Remove</button>
                                </td>
                            </tr>
                            @endforeach
                            @foreach($update_list as $list)
                            <tr>
                                <td class="py-2 pr-4 text-xs text-zinc-500">{{ $list['type'] }}</td>
                                <td class="py-2 pr-4 text-xs font-mono text-zinc-700">{{ $list['value'] }}</td>
                                <td class="py-2 text-center">
                                    <span class="text-xs text-green-600 font-medium">Saved</span>
                                </td>
                            </tr>
                            @endforeach
                            <tr>
                                <td class="pt-3 pr-2">
                                    <select wire:model.live='new_list_type'
                                            class="w-full px-2 py-1.5 bg-zinc-50 border border-zinc-200 rounded-md text-xs text-zinc-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">Type</option>
                                        <option value="Keyword">Keyword</option>
                                        <option value="Pattern">Pattern (regex)</option>
                                    </select>
                                </td>
                                <td class="pt-3 pr-2">
                                    <input wire:model='new_list_value' type="text" placeholder="Value"
                                           class="w-full px-2 py-1.5 bg-zinc-50 border border-zinc-200 rounded-md text-xs font-mono text-zinc-700 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                                </td>
                                <td class="pt-3 text-center">
                                    <button type="button" wire:click='add_list'
                                            class="px-3 py-1.5 bg-zinc-900 hover:bg-zinc-700 text-white text-xs font-medium rounded-md transition-colors">
                                        Add
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Access Management --}}
        <div class="bg-white border border-zinc-200 rounded-xl overflow-hidden mb-6">
            <div class="px-5 py-3 border-b border-zinc-100">
                <p class="text-xs text-zinc-500 uppercase tracking-wide font-medium">Access Management</p>
            </div>
            <div class="px-5 pt-3 pb-1">
                <p class="text-xs text-zinc-400">Restrict which IP addresses can reach the APIs in this cluster. Use an <span class="font-medium text-zinc-600">Allowlist</span> to permit only known IPs (e.g. internal services, partner systems). Use a <span class="font-medium text-zinc-600">Blocklist</span> to deny specific known bad actors while allowing everyone else.</p>
            </div>
            <div class="divide-y divide-zinc-50">
                <div class="px-5 py-4 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-zinc-800">IP List Mode</p>
                        <p class="text-xs text-zinc-400 mt-0.5"><span class="font-medium text-zinc-600">Allowlist</span> — only IPs in the list below are permitted; all others are blocked. <span class="font-medium text-zinc-600">Blocklist</span> — IPs in the list are denied; all others pass through.</p>
                    </div>
                    <select wire:model='access_type'
                            class="ml-8 px-3 py-2 bg-zinc-50 border border-zinc-200 rounded-md text-sm text-zinc-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="white">Allowlist</option>
                        <option value="black">Blocklist</option>
                    </select>
                </div>

                {{-- IP list --}}
                <div class="px-5 py-4">
                    <p class="text-xs font-medium text-zinc-500 uppercase tracking-wide mb-1">IP Addresses</p>
                    <p class="text-xs text-zinc-400 mb-3">Add individual IPv4 or IPv6 addresses. CIDR ranges are not currently supported — add each address individually.</p>
                    <table class="w-full text-sm mb-3">
                        <tbody class="divide-y divide-zinc-50">
                            @foreach($users as $user)
                            <tr>
                                <td class="py-2 pr-4 text-xs font-mono text-zinc-700">{{ $user }}</td>
                                <td class="py-2 text-right">
                                    <button type="button" wire:click="remove_user('{{ $user }}')"
                                            class="text-xs text-red-500 hover:text-red-700 hover:underline">Remove</button>
                                </td>
                            </tr>
                            @endforeach
                            <tr>
                                <td class="pt-3 pr-2">
                                    <input wire:model='new_user' type="text" placeholder="e.g. 192.168.1.1"
                                           class="w-full px-2 py-1.5 bg-zinc-50 border border-zinc-200 rounded-md text-xs font-mono text-zinc-700 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                                </td>
                                <td class="pt-3 text-right">
                                    <button type="button" wire:click='add_user'
                                            class="px-3 py-1.5 bg-zinc-900 hover:bg-zinc-700 text-white text-xs font-medium rounded-md transition-colors">
                                        Add
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <button type="submit"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors">
            Save Policy
        </button>

    </form>
</div>
