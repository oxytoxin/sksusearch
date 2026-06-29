<x-filament::page>
    <div class="space-y-6">
        <div class="grid gap-6 xl:grid-cols-2">
            {{-- Add Individual Employees --}}
            <x-filament::card>
                <div class="space-y-4">
                    <div>
                        <h2 class="text-lg font-bold tracking-tight">Add by Employee</h2>
                        <p class="text-sm text-gray-500">Search and select employees to grant access.</p>
                    </div>
                    <form wire:submit.prevent="addUsers" class="space-y-4">
                        {{ $this->employeeForm }}
                        <x-filament::button type="submit">
                            Grant Access
                        </x-filament::button>
                    </form>
                </div>
            </x-filament::card>

            {{-- Add by Office --}}
            <x-filament::card>
                <div class="space-y-4">
                    <div>
                        <h2 class="text-lg font-bold tracking-tight">Add by Office</h2>
                        <p class="text-sm text-gray-500">Grant access to all employees in an office, with optional exclusions.</p>
                    </div>
                    <form wire:submit.prevent="addOffice" class="space-y-4">
                        {{ $this->officeForm }}
                        <x-filament::button type="submit">
                            Grant Office Access
                        </x-filament::button>
                    </form>
                </div>
            </x-filament::card>
        </div>

        {{-- Employees with Access --}}
        <x-filament::card>
            <div class="space-y-4">
                <div>
                    <h2 class="text-lg font-bold tracking-tight">Employees with SMS Details Access</h2>
                </div>
                {{ $this->table }}
            </div>
        </x-filament::card>
    </div>
</x-filament::page>
