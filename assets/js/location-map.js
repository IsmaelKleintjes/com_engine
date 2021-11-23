var $j = jQuery.noConflict();
var locationMarker;
var locationMap;
var myZoom;

$j(window).load(function(){
    initializeLocationMap($j('#jform_latitude').val(), $j('#jform_longitude').val());

    $j('.location').focusout(function() { getLatLng(); });
});

function initializeLocationMap(lat, lon)
{
    var removeMarkerAfter = false;

    if(lat == 0 && lon == 0){
        myZoom = 2;
        lat = 52.043988;
        lon = 4.473398;

        removeMarkerAfter = true;
    } else {
        myZoom = 16;
    }

    var myLatLng = new google.maps.LatLng(lat, lon);

    var myOptions = {
        center: myLatLng,
        zoom: myZoom,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    locationMap = new google.maps.Map(document.getElementById("location_map"), myOptions);

    if(lat > 0 && lon > 0){
        createLocationMarker(lat, lon, removeMarkerAfter);
    }

    google.maps.event.addListener(locationMarker, 'drag', function() {
            $j('#jform_latitude').val(locationMarker.position.lat());
            $j('#jform_longitude').val(locationMarker.position.lng());
        }
    );
}

function createLocationMarker(lat, lon, removeMarkerAfter)
{
    locationMarker = new google.maps.Marker({
        position: new google.maps.LatLng(lat, lon),
        map: locationMap,
        draggable:true
    });

    if(removeMarkerAfter){
        locationMarker.setMap(null);
    }
}

function getLatLng()
{
    var fulladdress = '';

    $j('.location').each(function(){
        fulladdress += $j(this).val() + ' ';
    });

    fulladdress += 'The Netherlands';

    console.log(fulladdress);
    var geocoder = new google.maps.Geocoder();
    geocoder.geocode( { 'address': fulladdress}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            var latitude = results[0].geometry.location.lat();
            var longitude = results[0].geometry.location.lng();

            $j('#jform_latitude').val(latitude);
            $j('#jform_longitude').val(longitude);

            initializeLocationMap(latitude, longitude, false);
        }
    });
}