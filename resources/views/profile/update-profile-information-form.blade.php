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
            <div x-data="{ photoName: null, photoPreview: null }" class="col-span-6 sm:col-span-4">
                <!-- Current Profile Photo -->
                <div class="mt-2" x-show="! photoPreview">
                    <img src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}" class="object-cover w-20 h-20 rounded-full">
                </div>
            </div>
        @endif

        <!-- Name -->
        <div class="col-span-6 sm:col-span-4">
            <h4 class="block text-sm font-medium text-gray-700">Name: {{ $this->user->employee_information->full_name }}</h4>
        </div>

        <!-- Email -->
        <div class="col-span-6 sm:col-span-4">
            <h4 class="block text-sm font-medium text-gray-700">Email: {{ $this->user->email }}</h4>
        </div>

    </x-slot>
</x-jet-form-section>
