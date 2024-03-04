<x-card title="Login" shadow class="mx-auto w-[450px]">
    @if($errors->hasAny(['invalidCredentials','rateLimiter']))
        <x-alert  icon="o-home" class="alert-warning mb-2" >
            @error('invalidCredentials')
            {{$message}}
            @enderror
            @error('rateLimiter')
            {{$message}}
            @enderror
        </x-alert>
    @endif

    <x-form wire:submit="tryToLogin">
        <x-input label="Email" wire:model="email"/>
        <x-input label="Password" wire:model="password" type="password"/>
        <div class="w-full text-right text-sm">
            <a href="{{route('password.recovery')}}" class="link link-primary">Forgot your password</a>
        </div>
        <x-slot:actions>
          <div class="w-full flex items-center justify-between">
              <a class="link link-primary" wire:navigate href="{{route('auth.register')}}">I want to create an account</a>
             <div>
                 <x-button label="Login" class="btn-primary" type="submit" spinner="submit"/>
             </div>
          </div>
        </x-slot:actions>
    </x-form>
</x-card>