@props(['reservation', 'tripStartDate', 'tripEndDate']) 
@php
    // Calculate the minimum start date (1 week from today)
    $minStartDate = \Carbon\Carbon::now()->addWeek()->format('Y-m-d');
    $minEndDate = !empty($preferred_start_date)
        ? \Carbon\Carbon::parse($preferred_start_date)->addDay()->format('Y-m-d')
        : '';

    $buttonText = $tripAvailability == 'coming soon' ? 'Reserve' : 'Book';

@endphp

<div class="booking-form">
    <form wire:submit.prevent="bookTrip" class="p-4 rounded bg-white">
        <!-- Step 1 -->
        @if ($currentStep === 1)
            <div class="step">
              <div class="form-group mb-3">
                <input name="name" id="name" autofocus wire:model="name" class="form-control" type="text"
                    placeholder="First name & last name"
                    style="{{ $errors->has('name') ? 'border:1px solid #dc2626' : '' }}"
                    {{$reservation && $reservation->name ? 'readonly' : ''}} />

                <x-input-error :messages="$errors->get('name')" class="mt-2 text-danger" style="list-style: none;" />
            </div>

                <div class="form-group mb-3">
                    <input name="email" id="email" wire:model="email" class="form-control" type="email"
                        placeholder="Email" style="{{ $errors->has('email') ? 'border:1px solid #dc2626' : '' }}" 
                        {{$reservation && $reservation->email ? 'readonly' : ''}}
                        />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" style="list-style: none;" />
                </div>

                <div class="form-group mb-3">
                    <input id="phone_number" name="phone_number" wire:model="phone_number" class="form-control"
                        type="text" placeholder="xxx-xxx-xxxx"
                        {{$reservation && $reservation->phone_number ? 'readonly' : ''}}
                        style="{{ $errors->has('phone_number') ? 'border:1px solid #dc2626' : '' }}" />

                    <x-input-error :messages="$errors->get('phone_number')" class="mt-2 text-danger" style="list-style: none;" />
                </div>

                <div class="d-flex justify-content-end">
                    <button type="button" class="next-btn" wire:click="nextStep">Next</button>
                </div>
            </div>
        @endif

        <!-- Step 2 -->
        @if ($currentStep === 2)
            <div class="step">
                <div class="form-group mb-3">
                    <input id="address_line_1" name="address_line_1" wire:model="address_line_1" class="form-control"
                        type="text" placeholder="Street Address"
                        {{$reservation && $reservation->address_line_1 ? 'readonly' : ''}}
                        style="{{ $errors->has('address_line_1') ? 'border:1px solid #dc2626' : '' }}" />

                    <x-input-error :messages="$errors->get('address_line_1')" class="mt-2 text-danger" style="list-style: none;" />
                </div>

                <div class="form-group mb-3">
                    <input id="address_line_2" name="address_line_2" wire:model="address_line_2" class="form-control"
                        type="text" placeholder="Suite / P.O. Box"
                        {{$reservation && $reservation->address_line_2 ? 'readonly' : ''}}
                        style="{{ $errors->has('address_line_2') ? 'border:1px solid #dc2626' : '' }}" />

                    <x-input-error :messages="$errors->get('address_line_2')" class="mt-2 text-danger" style="list-style: none;" />
                </div>

                <div class="form-group mb-3">
                    <input id="city" name="city" wire:model="city" class="form-control" type="text"
                        placeholder="City" style="{{ $errors->has('city') ? 'border:1px solid #dc2626' : '' }}"
                        {{$reservation && $reservation->city ? 'readonly' : ''}}
                         />

                    <x-input-error :messages="$errors->get('city')" class="mt-2 text-danger" style="list-style: none;" />
                </div>

                <div class="form-group mb-3">
                <select name="state" id="state" wire:model="state" class="form-control"
                        style="{{ $errors->has('state') ? 'border:1px solid #dc2626' : '' }}"
                        {{$reservation && $reservation->state ? 'disabled' : ''}}
                        >

                    <option value="" disabled>State</option>
                    @foreach ($states as $state)
                        <option value="{{ $state['code'] }}">{{ $state['name'] }}</option>
                    @endforeach
                </select>

                <x-input-error :messages="$errors->get('state')" class="mt-2 text-danger" style="list-style: none;" />
            </div>


                <div class="form-group mb-3">
                    <input id="zipcode" name="zipcode" wire:model="zipcode" class="form-control" type="text"
                        placeholder="Zipcode" style="{{ $errors->has('zipcode') ? 'border:1px solid #dc2626' : '' }}"
                       {{$reservation && $reservation->zip_code ? 'readonly' : ''}}
                         />

                    <x-input-error :messages="$errors->get('zipcode')" class="mt-2 text-danger" style="list-style: none; " />
                </div>



                <div class="d-flex justify-content-between">
                    <button type="button" class="previous-btn" wire:click="previousStep">Previous</button>
                    <button type="button" class="next-btn" wire:click="nextStep">Next</button>
                </div>
            </div>
        @endif

        <!-- Final Step -->
        @if ($currentStep === 3)
            @if ($error)
                <div class="alert alert-danger">
                    {{ $error }}
                </div>
            @endif

          

                 <!-- Preferred Start Date -->
              
                <div class="form-group mb-3">
                    <label class="form-label text-dark float-start">Preferred Start Date</label>
                    {{$reservation && $reservation->preferred_start_date ? $reservation->preferred_start_date : ''}}
                    <input type="date" id="preferred_start_date" name="preferred_start_date"
                        wire:model="preferred_start_date" class="form-control"
                        style="{{ $errors->has('preferred_start_date') ? 'border:1px solid #dc2626' : '' }} {{$reservation && $reservation->preferred_start_date ? 'visibility: hidden' : ''}}"
                        {{$reservation && $reservation->preferred_start_date ? 'readonly' : ''}}
                        min="{{ $minStartDate }}" />

                    <x-input-error :messages="$errors->get('preferred_start_date')" class="mt-2 text-danger" style="list-style: none;" />
                </div>

                <div class="form-group mb-3">
                    <label class="form-label text-dark float-start">Preferred End Date</label>
                        {{$reservation && $reservation->preferred_end_date ? $reservation->preferred_end_date : ''}}
                    <input type="date" id="preferred_end_date" name="preferred_end_date"
                        wire:model="preferred_end_date" class="form-control"
                        style="{{ $errors->has('preferred_end_date') ? 'border:1px solid #dc2626' : '' }} {{$reservation && $reservation->preferred_end_date ? 'visibility: hidden' : ''}}"
                        {{$reservation && $reservation->preferred_end_date ? 'readonly' : ''}}
                        min="{{ $minEndDate }}" />

                    <x-input-error :messages="$errors->get('preferred_end_date')" class="mt-2 text-danger" style="list-style: none;" />
                </div>
                <!-- Preferred End Date -->
         

            <div class="step text-center">
                <div class="form-btn">
                    <button type="button" class="previous-btn" wire:click="previousStep">Previous</button>
                    <button class="submit-btn" wire:loading.remove type="submit">{{ $buttonText }}</button>
                </div>

                <div class="spinner-border text-primary mt-3" role="status" wire:loading></div>
            </div>
        @endif
    </form>
</div>
