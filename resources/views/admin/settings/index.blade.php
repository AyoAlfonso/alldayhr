@extends('layouts.app')

@push('head-script')
    <link rel="stylesheet" href="{{ asset('assets/node_modules/dropify/dist/css/dropify.min.css') }}">
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form id="editSettings" class="ajax-form">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="company_name">@lang('modules.accountSettings.companyName')</label>
                            <input type="text" class="form-control" id="company_name" name="company_name"
                                   value="{{ $global->company_name }}">
                        </div>
                        <div class="form-group">
                            <label for="company_email">@lang('modules.accountSettings.companyEmail')</label>
                            <input type="email" class="form-control" id="company_email" name="company_email"
                                   value="{{ $global->company_email }}">
                        </div>
                        <div class="form-group">
                            <label for="company_phone">@lang('modules.accountSettings.companyPhone')</label>
                            <input type="tel" class="form-control" id="company_phone" name="company_phone"
                                   value="{{ $global->company_phone }}">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">@lang('modules.accountSettings.companyWebsite')</label>
                            <input type="text" class="form-control" id="website" name="website"
                                   value="{{ $global->website }}">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">@lang('modules.accountSettings.companyLogo')</label>
                             <div class="card">
                                    <div class="card-body">
                                        <input type="file" id="input-file-now" name="logo" class="dropify"
                                               @if(is_null($global->logo))
                                                   data-default-file="{{ asset('app-logo.png') }}"
                                               @else
                                                   data-default-file="{{ asset('user-uploads/app-logo/'.$global->logo) }}"
                                               @endif
                                        />
                                    </div>
                                </div>
                        </div>


                        <div class="form-group">
                            <label for="address">@lang('modules.accountSettings.companyAddress')</label>
                            <textarea class="form-control" id="address" rows="5"
                                      name="address">{{ $global->address }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="address">@lang('modules.accountSettings.defaultTimezone')</label>
                            <select name="timezone" id="timezone" class="form-control select2 custom-select">
                                @foreach($timezones as $tz)
                                    <option @if($global->timezone == $tz) selected @endif>{{ $tz }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="address">@lang('modules.accountSettings.changeLanguage')</label>

                            <select class="form-control" name="locale">
                                <option value="en" @if($global->locale == "en") selected @endif >English</option>
                                @foreach($languageSettings as $language)
                                    <option value="{{ $language->language_code }}" @if($global->locale == $language->language_code) selected @endif  data-content='<span class="flag-icon flag-icon-{{ $language->language_code }}"></span> {{ $language->language_name }}'>{{ $language->language_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            {{--<a href="javascript:;" id="getLoaction" class="btn btn-warning m-b-10"><i class="ti-location-pin"></i> @lang('modules.accountSettings.getLocation')</a>--}}
                            <label for="address">@lang('modules.accountSettings.getLocation')</label>

                            <input type="text" class="form-control" id="gmap_geocoding_address">
                            <input type="hidden" id="latitude" name="latitude"
                                   value="{{ $global->latitude }}">
                            <input type="hidden" id="longitude" name="longitude"
                                   value="{{ $global->longitude }}">

                            <div id="gmap_geocoding" class="gmaps"></div>
                        </div>


                        <button type="button" id="save-form"
                                class="btn btn-success waves-effect waves-light m-r-10">
                            @lang('app.save')
                        </button>
                        <button type="reset"
                                class="btn btn-inverse waves-effect waves-light">@lang('app.reset')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('footer-script')
    <script src="{{ asset('assets/node_modules/select2/dist/js/select2.full.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/node_modules/bootstrap-select/bootstrap-select.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/node_modules/dropify/dist/js/dropify.min.js') }}" type="text/javascript"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDw9cQQsGxYkPicGbigZG1koUGRC4TAbSs&libraries=places"></script>

    <script>
        // For select 2
        $(".select2").select2();

        $('.dropify').dropify({
            messages: {
                default: '@lang("app.dragDrop")',
                replace: '@lang("app.dragDropReplace")',
                remove: '@lang("app.remove")',
                error: '@lang('app.largeFile')'
            }
        });

        $(document).ready(function () {
            $("#getLoaction").click(function () {
                $('body').block({
                    message: '<p style="margin:0;padding:8px;font-size:24px;">Just a moment...</p>'
                    , css: {
                        color: '#fff'
                        , border: '1px solid #fb9678'
                        , backgroundColor: '#fb9678'
                    }
                });

                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(showPosition);
                } else {
                    alert("Geolocation is not supported by this browser.");
                    $("#locationMsg").html('');
                }
            });
        });


        function showPosition(position) {
            $('#latitude').val(position.coords.latitude);
            $('#longitude').val(position.coords.longitude);
            initialize();
            $('body').unblock();
        }

        $('#save-form').click(function () {
            $.easyAjax({
                url: '{{route('admin.settings.update', ['1'])}}',
                container: '#editSettings',
                type: "POST",
                redirect: true,
                file: true
            })
        });
    </script>

    <script>
        //Get Latitude And Longitude
        var geocoder = new google.maps.Geocoder();

        function geocodePosition(pos) {
            geocoder.geocode(
                {
                    latLng: pos
                }, function (responses) {
                    if (responses && responses.length > 0) {
                        updateMarkerAddress(responses[0].formatted_address);
                    } else {
                        updateMarkerAddress('Cannot determine address at this location.');
                    }
                });
        }

        function updateMarkerStatus(str) {
            //document.getElementById('markerStatus').innerHTML = str;
        }

        function updateMarkerPosition(latLng) {
            $('#latitude').val(latLng.lat());
            $('#longitude').val(latLng.lng());
        }

        function updateMarkerAddress(str) {

            //  $('#currentlocation').val(str);

        }

        function initialize() {
            //Latitude longitude of default

            var clat = $('#latitude').val();
            var clong = $('#longitude').val();

            clat = parseFloat(clat);
            clong = parseFloat(clong);

            var latLng = new google.maps.LatLng(clat, clong);

            var mapOptions = {
                center: latLng,
                zoom: 16,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };

            map = new google.maps.Map(document.getElementById('gmap_geocoding'),
                mapOptions);

            var input = document.getElementById('gmap_geocoding_address');

            var autocomplete = new google.maps.places.Autocomplete(input);

            //autocomplete.bindTo('bounds', map);

            var infowindow = new google.maps.InfoWindow();
            marker = new google.maps.Marker({
                map: map,
                position: latLng,
                title: 'ReferSell',
                map: map,
                draggable: true
            });
            updateMarkerPosition(latLng);
            geocodePosition(latLng);

            // Add dragging event listeners.
            google.maps.event.addListener(marker, 'dragstart', function () {
                updateMarkerAddress('Dragging...');
            });

            google.maps.event.addListener(marker, 'drag', function () {
                updateMarkerStatus('Dragging...');
                updateMarkerPosition(marker.getPosition());
            });

            google.maps.event.addListener(marker, 'dragend', function () {

                updateMarkerStatus('Drag ended');
                geocodePosition(marker.getPosition());
            });
            google.maps.event.addListener(autocomplete, 'place_changed', function () {
                infowindow.close();
                var place = autocomplete.getPlace();

                if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    map.setZoom(10);  // Why 17? Because it looks good.
                }

                /* var image = new google.maps.MarkerImage(
                 place.icon,
                 new google.maps.Size(71, 71),
                 new google.maps.Point(0, 0),
                 new google.maps.Point(17, 34),
                 new google.maps.Size(35, 35));
                 marker.setIcon(image);*/
                marker.setPosition(place.geometry.location);
                updateMarkerPosition(place.geometry.location);

                var address = '';

            });

            // Sets a listener on a radio button to change the filter type on Places
            // Autocomplete.
            function setupClickListener(id, types) {
                var radioButton = document.getElementById(id);
                google.maps.event.addDomListener(radioButton, 'click', function () {
                    autocomplete.setTypes(types);
                });
            }

        }

        $('#gmap_geocoding_address').keydown(function (event) {
            if (event.keyCode == 13) {
                event.preventDefault();
                return false;
            }
        });


        @if(!is_null($global->latitude))
        initialize();
        @endif

    </script>
@endpush