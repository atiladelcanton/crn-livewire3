<x-card title="Register" shadow class="mx-auto w-[450px]">
    <x-form wire:submit="submit">
        <x-input label="Name" wire:model="name"/>
        <x-input label="Email" wire:model="email"/>
        <x-input label="Confirm Email" wire:model="email_confirmation"/>
        <x-input label="Password" wire:model="password" type="password"/>

        <x-slot:actions>
            <div class="w-full flex items-center justify-between">
                <a class="link link-primary" wire:navigate href="{{route('login')}}">I already have an account</a>
                <div>
                    <x-button label="Reset" type="reset"/>
                    <x-button label="Register" class="btn-primary" type="submit" spinner="submit"/>
                </div>
            </div>
        </x-slot:actions>
    </x-form>
</x-card>