(function ($) {
    const customMarker = {
        url: "data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMy4yNTgiIGhlaWdodD0iMTMuMjU4IiB2aWV3Qm94PSIwIDAgMTMuMjU4IDEzLjI1OCI+PHBhdGggZD0iTTYuNjI5LDBhNi42MjksNi42MjksMCwxLDAsNi42MjksNi42MjlBNi42MjksNi42MjksMCwwLDAsNi42MjksMFoiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDAgMCkiIGZpbGw9IiNmMDg5NzQiLz48ZyB0cmFuc2Zvcm09InRyYW5zbGF0ZSg0LjI3NCA0LjQxMikiPjxwYXRoIGQ9Ik0xMTg3MS0xNDUzMy43NzRoLTF2LTQuNDM1aDFaIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgtMTE4NjguMjc4IDE0NTM4LjIwOSkiIGZpbGw9IiNmZmYiLz48cGF0aCBkPSJNLjUsNC40MzVoLTFWMGgxWiIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoNC40MzUgMi4yMTgpIHJvdGF0ZSg5MCkiIGZpbGw9IiNmZmYiLz48L2c+PC9zdmc+",
        scaledSize: new google.maps.Size(50, 50),
    };

    /**
     * initMap
     *
     * Renders a Google Map onto the selected jQuery element
     *
     * @date    22/10/19
     * @since   5.8.6
     *
     * @param   jQuery $el The jQuery element.
     * @return  object The map instance.
     */
    function initMap($el) {

        // Find marker elements within map.
        var $markers = $el.find('.marker');

        // Create gerenic map.
        var mapArgs = {
            zoom: $el.data('zoom') || 16,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            disableDefaultUI: true,
            mapId: '53a7c164a878ab22'
        };
        var map = new google.maps.Map($el[0], mapArgs);

        // Add markers.
        map.markers = [];
        $markers.each(function () {
            initMarker($(this), map);
        });

        // Center map based on markers.
        centerMap(map);

        // Return map instance.
        return map;
    }

    /**
     * initMarker
     *
     * Creates a marker for the given jQuery element and map.
     *
     * @date    22/10/19
     * @since   5.8.6
     *
     * @param   jQuery $el The jQuery element.
     * @param   object The map instance.
     * @return  object The marker instance.
     */
    function initMarker($marker, map) {

        // Get position from marker.
        var lat = $marker.data('lat');
        var lng = $marker.data('lng');
        var latLng = {
            lat: parseFloat(lat),
            lng: parseFloat(lng)
        };

        // Create marker instance.
        var marker = new google.maps.Marker({
            position: latLng,
            map: map,
            icon: customMarker
        });

        // Append to reference for later use.
        map.markers.push(marker);

        // If marker contains HTML, add it to an infoWindow.
        if ($marker.html()) {

            // Create info window.
            var infowindow = new google.maps.InfoWindow({
                content: $marker.html()
            });

            // Show info window when marker is clicked.
            google.maps.event.addListener(marker, 'click', function () {
                infowindow.open(map, marker);
            });
        }
    }

    /**
     * centerMap
     *
     * Centers the map showing all markers in view.
     *
     * @date    22/10/19
     * @since   5.8.6
     *
     * @param   object The map instance.
     * @return  void
     */
    function centerMap(map) {

        // Create map boundaries from all map markers.
        var bounds = new google.maps.LatLngBounds();
        map.markers.forEach(function (marker) {
            bounds.extend({
                lat: marker.position.lat(),
                lng: marker.position.lng()
            });
        });

        // Case: Single marker.
        if (map.markers.length == 1) {
            map.setCenter(bounds.getCenter());

            // Case: Multiple markers.
        } else {
            map.fitBounds(bounds);
        }
    }

    // Render maps on page load.
    $(document).ready(function () {
        $('.acf-map').each(function () {
            var map = initMap($(this));
        });
    });

})(jQuery);