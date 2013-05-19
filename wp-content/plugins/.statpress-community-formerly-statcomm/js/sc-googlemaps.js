jQuery(document).ready(function () {
    // Define the latitude and longitude positions
    var latitude = parseFloat("51.515252");
    var longitude = parseFloat("-0.189852");
    var latlngPos = new google.maps.LatLng(latitude, longitude);
    // Set up options for the Google map
    var myOptions = {
        zoom: 10,
        center: latlngPos,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    // Define the map
    map = new google.maps.Map(document.getElementById("statcomm-map"), myOptions);
    // Add the marker
    var marker = new google.maps.Marker({
        position: latlngPos,
        map: map,
        title: "PC Pro Offices"
    });
});

