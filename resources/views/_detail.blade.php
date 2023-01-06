@foreach($attributes as $label => $attribute)
  <div class="form-group row align-items-baseline">
    <label class="col-sm-2 control-label">
      {{ $attribute['label'] }}
    </label>
    <div class="col-sm-9">
      <div class="input-group">
        <div class="form-control">
          @if($label === 'url')
            <a href="{{$attribute['value']}}" target="_blank">{{$attribute['value']}}</a>
          @else
            {{ $attribute['value'] }}
          @endif
        </div>
        <div class="input-group-append">
          <button class="copy-btn btn btn-secondary">
            <i class="feather icon-clipboard"></i>
          </button>
        </div>
      </div>
    </div>
  </div>
@endforeach

