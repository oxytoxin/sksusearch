<x-guest-layout>
    <div class="flex flex-col items-center min-h-screen pt-6 bg-gray-100 sm:justify-center sm:pt-0">
        <div>
            <x-jet-authentication-card-logo />
        </div>
        <div class="font-extrabold tracking-widest text-md text-primary md:text-2xl sm:text-md">
            <span class="uppercase">SKSU<span class="capitalize"> electronic archiving, retrieval <span class="lowercase">and</span> content handling</span></span>
        </div>

        <div class="w-full px-6 py-4 mt-6 overflow-hidden shadow-md sm:max-w-7xl sm:rounded-lg">
            <div class="grid grid-cols-1 lg:grid-cols-7 gap-6">

                {{-- Left: How to Register --}}
                <div class="lg:col-span-2 bg-primary-600 rounded-lg p-6 text-white">
                    <h2 class="text-xl font-bold mb-4">Employee Registration</h2>
                    <p class="text-sm text-white/80 mb-6">
                        Register using your institutional email to get started with the S.E.A.R.C.H. system.
                    </p>

                    <div class="space-y-4">
                        <h3 class="text-sm font-bold uppercase tracking-wider">How to Register</h3>
                        <ol class="list-decimal list-inside space-y-2 text-sm text-white/90">
                            <li>Fill in your personal information (first name, last name, and full name as it appears in official documents).</li>
                            <li>Enter your SKSU institutional email (<span class="font-semibold">@sksu.edu.ph</span>).</li>
                            <li>Select your campus, office, and position under the Assignment section.</li>
                            <li>Click <span class="font-semibold">Submit</span> to create your account.</li>
                            <li>After registration, use <span class="font-semibold">Login with Google</span> using the same institutional email to access the system.</li>
                        </ol>
                    </div>

                    <div class="mt-6 pt-4 border-t border-white/20">
                        <a href="{{ route('login') }}" class="text-sm underline text-white/80 hover:text-white">
                            &larr; Back to Login
                        </a>
                    </div>
                </div>

                {{-- Center: Important Notes --}}
                <div class="lg:col-span-2 bg-primary-600 rounded-lg p-6 text-white">
                    <h3 class="text-sm font-bold uppercase tracking-wider mb-4">Important Notes</h3>

                    <div class="space-y-3 text-sm text-white/90">
                        <div>
                            <p class="font-semibold text-white mb-1">a. Office Assignment</p>
                            <p>Office assignments must be selected based on organic function (as indicated by plantilla item). Designations (division/center directorship, project leadership, committee membership, etc.) will be handled by the HR module. For now, designations are not user-directed.</p>
                        </div>

                        <div>
                            <p class="font-semibold text-white mb-1">b. Position Selection</p>
                            <ol class="ml-6 space-y-1" style="list-style-type: lower-roman;">
                                <li>Regular teaching and non-teaching personnel must select positions based on plantilla item.</li>
                                <li>All COS teaching personnel must select <span class="font-semibold text-white">COS Faculty</span>.</li>
                                <li>All office-based job order personnel must select <span class="font-semibold text-white">Job Order Staff</span> regardless of assignment or classification.</li>
                                <li>All project-based (externally-funded/161) job order personnel must select <span class="font-semibold text-white">Project Staff</span> regardless of assignment, classification, or project staffing rank.</li>
                            </ol>
                            <div class="mt-2 p-2 bg-white/10 border border-white/20 rounded text-xs text-white/80">
                                <span class="font-semibold text-white">Note:</span> Many of our office-based and project-based "job order" personnel should technically be classified as Contract of Service personnel under COA-CSC-DBM Joint Circular No. 1, s. 2025. However, as a matter of administrative convenience, we will give way to term familiarity and use the generic positions described above.
                            </div>
                        </div>

                        <div>
                            <p class="font-semibold text-white mb-1">c. Duplicate Registration</p>
                            <p>The system will block new registration attempts from institutional emails that are already enrolled in the system.</p>
                        </div>
                    </div>

                    <div class="mt-4 p-3 bg-white/15 border border-white/30 rounded-lg">
                        <p class="text-sm font-bold text-white mb-1">Can't find your office or position?</p>
                        <p class="text-sm text-white/90">
                            Download this
                            <a href="{{ route('download.employee-template') }}" class="inline-flex items-center px-2 py-0.5 bg-white text-primary-600 font-semibold rounded hover:bg-white/90 text-xs">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Form
                            </a>
                            and email it to <span class="font-semibold text-white">sksusearch@sksu.edu.ph</span>
                        </p>
                    </div>
                </div>

                {{-- Right: Form --}}
                <div class="lg:col-span-3 bg-primary-400 rounded-lg p-6">
                    @if ($errors->any())
                        <div class="mb-4 p-3 bg-red-100 border border-red-400 rounded text-red-700 text-sm">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="space-y-4">
                            {{-- Employee Information --}}
                            <h3 class="text-sm font-bold text-white">Employee Information</h3>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <x-jet-label class="font-bold text-white drop-shadow" for="first_name" value="First Name *" />
                                    <x-jet-input class="block w-full mt-1 uppercase" id="first_name" name="first_name" type="text" :value="old('first_name')" required style="text-transform: uppercase;" />
                                    @error('first_name') <span class="text-xs text-red-300">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <x-jet-label class="font-bold text-white drop-shadow" for="last_name" value="Last Name *" />
                                    <x-jet-input class="block w-full mt-1 uppercase" id="last_name" name="last_name" type="text" :value="old('last_name')" required style="text-transform: uppercase;" />
                                    @error('last_name') <span class="text-xs text-red-300">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <x-jet-label class="font-bold text-white drop-shadow" for="full_name" value="Full Name (as it appears in documents) *" />
                                <x-jet-input class="block w-full mt-1 uppercase" id="full_name" name="full_name" type="text" :value="old('full_name')" placeholder="JUAN M. DELA CRUZ" required style="text-transform: uppercase;" />
                                @error('full_name') <span class="text-xs text-red-300">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <x-jet-label class="font-bold text-white drop-shadow" for="email" value="Institutional Email *" />
                                <x-jet-input class="block w-full mt-1" id="email" name="email" type="email" :value="old('email')" placeholder="yourname@sksu.edu.ph" required />
                                @error('email') <span class="text-xs text-red-300">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <x-jet-label class="font-bold text-white drop-shadow" for="address" value="Address" />
                                    <x-jet-input class="block w-full mt-1" id="address" name="address" type="text" :value="old('address')" />
                                </div>
                                <div>
                                    <x-jet-label class="font-bold text-white drop-shadow" for="contact_number" value="Contact Number *" />
                                    <div class="flex mt-1">
                                        <span class="inline-flex items-center px-3 text-sm text-gray-700 bg-gray-200 border border-r-0 border-gray-300 rounded-l-md">09</span>
                                        <input class="block w-full rounded-r-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="contact_number_suffix" type="text" value="{{ old('contact_number') ? substr(old('contact_number'), 2) : '' }}" maxlength="9" minlength="9" required oninput="this.value = this.value.replace(/[^0-9]/g, '')" />
                                        <input type="hidden" id="contact_number" name="contact_number" value="{{ old('contact_number') }}" />
                                    </div>
                                    @error('contact_number') <span class="text-xs text-red-300">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <x-jet-label class="font-bold text-white drop-shadow" for="birthday" value="Birthday" />
                                <x-jet-input class="block w-full mt-1" id="birthday" name="birthday" type="date" :value="old('birthday')" />
                            </div>

                            {{-- Assignment --}}
                            <div class="pt-3 mt-3 border-t border-white/20">
                                <h3 class="text-sm font-bold text-white mb-3">Assignment</h3>

                                <div class="space-y-3">
                                    <div>
                                        <x-jet-label class="font-bold text-white drop-shadow" for="campus_id" value="Campus *" />
                                        <select class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                                                id="campus_id" name="campus_id" onchange="loadOffices(this.value)">
                                            <option value="">-- Select Campus --</option>
                                            @foreach (\App\Models\Campus::orderBy('name')->get() as $campus)
                                                <option value="{{ $campus->id }}" {{ old('campus_id') == $campus->id ? 'selected' : '' }}>{{ $campus->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('campus_id') <span class="text-xs text-red-300">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <x-jet-label class="font-bold text-white drop-shadow" for="office_id" value="Office *" />
                                        <select class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50 opacity-50"
                                                id="office_id" name="office_id" disabled>
                                            <option value="">-- Select Campus first --</option>
                                        </select>
                                        @error('office_id') <span class="text-xs text-red-300">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <x-jet-label class="font-bold text-white drop-shadow" for="position_id" value="Position *" />
                                        <select class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                                                id="position_id" name="position_id">
                                            <option value="">-- Select Position --</option>
                                            @foreach (\App\Models\Position::orderBy('description')->get() as $position)
                                                <option value="{{ $position->id }}" {{ old('position_id') == $position->id ? 'selected' : '' }}>{{ $position->description }}</option>
                                            @endforeach
                                        </select>
                                        @error('position_id') <span class="text-xs text-red-300">{{ $message }}</span> @enderror
                                    </div>

                                </div>
                            </div>

                            {{-- Submit --}}
                            <div class="pt-3">
                                <button type="submit"
                                        class="inline-flex items-center justify-center w-full py-3 text-xs font-semibold tracking-widest text-white uppercase transition border rounded-md border-primary-600 bg-primary-600 hover:bg-primary-500 focus:outline-none focus:ring focus:ring-primary-300 disabled:opacity-25">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <script>
        const offices = @json(\App\Models\Office::orderBy('name')->get()->groupBy('campus_id')->map(fn($offices) => $offices->map(fn($o) => ['id' => $o->id, 'name' => $o->name])->values()));
        const oldOffice = "{{ old('office_id') }}";

        function loadOffices(campusId) {
            const select = document.getElementById('office_id');
            select.innerHTML = '<option value="">-- Select Office --</option>';

            if (campusId && offices[campusId]) {
                offices[campusId].forEach(function(office) {
                    const option = document.createElement('option');
                    option.value = office.id;
                    option.textContent = office.name;
                    if (oldOffice == office.id) option.selected = true;
                    select.appendChild(option);
                });
                select.disabled = false;
                select.classList.remove('opacity-50');
            } else {
                select.innerHTML = '<option value="">-- Select Campus first --</option>';
                select.disabled = true;
                select.classList.add('opacity-50');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const campusId = document.getElementById('campus_id').value;
            if (campusId) loadOffices(campusId);

            const suffix = document.getElementById('contact_number_suffix');
            const hidden = document.getElementById('contact_number');
            suffix.addEventListener('input', function() {
                hidden.value = suffix.value ? '09' + suffix.value : '';
            });
        });
    </script>
</x-guest-layout>
