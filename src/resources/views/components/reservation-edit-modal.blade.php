<div class="modal {{ $errors->hasAny(['date_' . $reservation->id, 'time_' . $reservation->id, 'number_' . $reservation->id]) ? 'open' : ' ' }}" id="modal{{$reservation->id}}">
    <div class="modal__inner">
        <div class="modal__content">
            <form class="modal__form" action="{{ route('update',$reservation->id) }}" method="post">
                @csrf
                @method('PATCH')
                <div class="modal__group">
                    <label class="modal__label" for="restaurant_name">Shop</label>
                    <input class="modal__input" type="type" name="restaurant_name" value="{{ $reservation->restaurant->name }}" id="restaurant_name" disabled>
                </div>
                <div class="modal__group">
                    <input type="hidden" name="restaurant_id" value="{{ $reservation->restaurant->id }}">
                    <label class="modal__label" for="date_{{$reservation->id}}">Date</label>
                    <input class="modal__calender" type="date" id="date_{{$reservation->id}}" name="date_{{$reservation->id}}" value="{{ old('date_' . $reservation->id, \Carbon\Carbon::parse($reservation->date)->format('Y-m-d')) }}">
                </div>
                @error('date_' . $reservation->id)
                <div class="error">
                    {{ $message }}
                </div>
                @enderror
                <div class="modal__group">
                    <label class="modal__label" for="time_{{$reservation->id}}">Time</label>
                    <input class="modal__input" type="time" id="time_{{$reservation->id}}" name="time_{{$reservation->id}}" value="{{ old('time_' . $reservation->id, \Carbon\Carbon::parse($reservation->date)->format('H:i')) }}" max="22:00" min="09:00">
                </div>
                @error('time_' . $reservation->id)
                <div class="error">
                    {{ $message }}
                </div>
                @enderror
                <div class="modal__group">
                    <label class="modal__label" for="number_{{$reservation->id}}">Number</label>
                    <select class="modal__select" name="number_{{$reservation->id}}" id="number_{{$reservation->id}}">
                        <option value="" selected hidden>人数</option>
                        @foreach($numbers as $number)
                        <option value="{{ $number }}" {{ old('number_' . $reservation->id ,$reservation->number) == $number ? 'selected' : '' }}>{{ $number }}人</option>
                        @endforeach
                    </select>
                </div>
                @error('number_' . $reservation->id)
                <div class="error">
                    {{ $message }}
                </div>
                @enderror
                <input class="res-modal__button" type="submit" value="登録"></input>
            </form>
        </div>
    </div>
</div>