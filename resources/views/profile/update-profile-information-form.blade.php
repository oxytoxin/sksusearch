<x-jet-form-section submit="updateProfileInformation">
    <x-slot name="title">
        {{ __('Profile Information') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Update your account\'s profile information.') }}
    </x-slot>

    <x-slot name="form">
        <!-- Profile Photo -->
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <div class="col-span-6 sm:col-span-4" x-data="{ photoName: null, photoPreview: null }">
                <!-- Current Profile Photo -->
                <div class="mt-2" x-show="! photoPreview">
                    <img class="h-20 w-20 rounded-full object-cover" src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}">
                </div>
            </div>
        @endif

        <!-- Name -->
        <div class="col-span-6 sm:col-span-4">
            <h4 class="block text-sm font-medium text-gray-700">Name: {{ $this->user->employee_information->full_name }}</h4>
        </div>

        <!-- Contact Number -->
        <div class="col-span-6 sm:col-span-4">
            <h4 class="block text-sm font-medium text-gray-700">Contact Number: {{ $this->user->employee_information->contact_number == null ? 'N/A' : $this->user->employee_information->contact_number }}</h4>
        </div>

        <!-- Email -->
        <div class="col-span-6 sm:col-span-4">
            <h4 class="block text-sm font-medium text-gray-700">Email: {{ $this->user->email }}</h4>
        </div>

        <div class="col-span-6 sm:col-span-4">
            <h4 class="block text-sm font-medium text-gray-700">Signature <a class="ml-2 font-semibold text-primary-600 underline" href="{{ route('requisitioner.signature') }}">Update</a></h4>
            <img src="{{ $this->user->signature?->content }}" alt="">
        </div>
    </x-slot>
</x-jet-form-section>
