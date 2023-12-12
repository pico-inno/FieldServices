<!-- AIzaSyB6y-549HrO6No2H4yELrxw-phFYRHo5I0 -->
<!DOCTYPE html>
<html>

<head>
    <title>Get Location from Lat/Lng</title>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB6y-549HrO6No2H4yELrxw-phFYRHo5I0"></script>
</head>

<body>
    <div id="location"></div>

    <script>
        function getLocation(latitude, longitude) {
            var latlng = new google.maps.LatLng(latitude, longitude);
            var geocoder = new google.maps.Geocoder();

            geocoder.geocode({ 'location': latlng }, function (results, status) {
                if (status === google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                        document.getElementById('location').innerHTML = 'Location: ' + results[0].formatted_address;
                    } else {
                        document.getElementById('location').innerHTML = 'No results found';
                    }
                } else {
                    document.getElementById('location').innerHTML = 'Geocoder failed due to: ' + status;
                }
            });
        }

        // Example usage:
        var latitude = 40.7128; // Replace with your latitude
        var longitude = -74.0060; // Replace with your longitude
        getLocation(latitude, longitude);
    </script>
</body>

</html>
