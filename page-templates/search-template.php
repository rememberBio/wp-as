<?php
/**
 * Template Name: Search
 */

get_header();

$url = get_site_url();
$action = "/search-results";

$current_lang = apply_filters( 'wpml_current_language', NULL );
if($current_lang !== 'en') {
    $action = apply_filters( 'wpml_permalink', get_site_url() . $action, $current_lang,true ); 
    //$action = str_replace($url, "",$action);
}

?>

<script>

/*function initAutocomplete() {
    navigator.geolocation.getCurrentPosition(
        function (position) {
            initMap(position.coords.latitude, position.coords.longitude)
        },
        function errorCallback(error) {
            console.log(error)
        }
    );
}*/

function /*initMap(lat, lng)*/initAutocomplete() {
    /*var myLatLng = {
      lat,
      lng
   };*/
    const mapDomEl = document.getElementById("map");
    const map = new google.maps.Map(mapDomEl, {
      zoom: 15,
      mapTypeId: "roadmap",
      //center: myLatLng
    });
    
   /* var marker = new google.maps.Marker({
        position: myLatLng,
        map: map,
    });*/
    // Create the search box and link it to the UI element.
    const input = document.getElementById("pac-input");
    const searchBox = new google.maps.places.SearchBox(input);
  
    //map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

    // Bias the SearchBox results towards current map's viewport.
    map.addListener("bounds_changed", () => {
      searchBox.setBounds(map.getBounds());
    });
  
    let markers = [];
  
    // Listen for the event fired when the user selects a prediction and retrieve
    // more details for that place.
    searchBox.addListener("places_changed", () => {
        
        //hide map element
        mapDomEl.style.display = "none";

        const places = searchBox.getPlaces();
        if (places.length == 0) {
            return;
        }
        //set lat and lng to inputs
        const latDomEl = document.getElementById("lat-input");
        const lngDomEl = document.getElementById("lng-input");
        const place = places[0];
        if (!place.geometry || !place.geometry.location) {
            console.log("Returned place contains no geometry");
            return;
        }
        let lat = place.geometry.location.lat();
        let lng = place.geometry.location.lng();
        
        latDomEl.value = lat;
        lngDomEl.value = lng;

        // Clear out the old markers.
        //marker.setMap(null);
        markers.forEach((marker) => {
            marker.setMap(null);
        });
        markers = [];
  
        // For each place, get the icon, name and location.
        const bounds = new google.maps.LatLngBounds();
    
        places.forEach((place) => {
            if (!place.geometry || !place.geometry.location) {
            console.log("Returned place contains no geometry");
            return;
            }
    
            const icon = {
            url: place.icon,
            size: new google.maps.Size(71, 71),
            origin: new google.maps.Point(0, 0),
            anchor: new google.maps.Point(17, 34),
            scaledSize: new google.maps.Size(25, 25),
            };
    
            // Create a marker for each place.
            markers.push(
                new google.maps.Marker({
                    map,
                    icon,
                    title: place.name,
                    position: place.geometry.location,
                })
            );
            if (place.geometry.viewport) {
                // Only geocodes have viewport.
                bounds.union(place.geometry.viewport);
            } else {
                bounds.extend(place.geometry.location);
            }
        });
        map.fitBounds(bounds);
        //show map element
        mapDomEl.style.display = "block";
    });
}
</script>

<div class="wrap-search-page">
    <?php if ( function_exists('yoast_breadcrumb') ) {
        yoast_breadcrumb( '<p id="breadcrumbs">','</p>' );
    } ?>
    <h1><?php _e('Find Remember Page','search'); ?></h1>
    <div class="wrap-serach-forms">
        <div class="wrap-search-name">
            <div class="heading">
                <span class="icon">
                    <img src="/wp-content/uploads/2022/04/Group-221.svg" alt="">
                </span>
                <span class="text">
                    <?php _e('By Name','search'); ?>
                </span>
            </div>
            <span class="instructions">
                <?php _e('Find a memorial page of a deceased person by name','search'); ?>
            </span>
            <div class="wrap-form">
                <form action="<?php echo $action; ?>" method="POST" id="searchNameForm">
                    <input type="text" required name="byName" id="byName">
                    <input type="submit" value="<?php _e('search','search'); ?>">
                </form>
            </div>

        </div>
        <div class="wrap-search-location">
            <div class="heading">
                <span class="icon">
                    <img src="/wp-content/uploads/2022/04/Group-936.svg" alt="">
                </span>
                <span class="text">
                    <?php _e('By Location','search'); ?>
                </span>
            </div>
            <span class="instructions">
                <?php _e('Find a memorial page of a deceased person by Tomb location on the map','search'); ?>
            </span>
            <div class="wrap-form">
                <form action="<?php echo $action; ?>" id="searchLocationForm" method="POST">
                    <input type="text" required name="pac-input" id="pac-input" placeholder="<?php _e('enter location','search'); ?>">
                    <input type="hidden" name="lng" id="lng-input">
                    <input type="hidden" name="lat" id="lat-input">
                    <input type="submit" value="<?php _e('search','search'); ?>">
                </form>
                <div class="wrap-map-search">
                    <div id="map"></div>
                </div>
                <!-- Async script executes immediately and must be after any DOM elements used in callback. -->
               <script
                src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAk5oOe33Nmwh6X_PbL13W50atji4wcUfo&callback=initAutocomplete&libraries=places&v=weekly"
                async
                ></script>
            </div>
        </div>
        <div class="wrap-search-date">
            <div class="heading">
                <span class="icon">
                    <img src="/wp-content/uploads/2022/04/Group-940.svg" alt="">
                </span>
                <span class="text">
                    <?php _e('By Date','search'); ?>
                </span>
            </div>
            <span class="instructions">
                <?php _e('Find a memorial page of a deceased person by Date of death','search'); ?>
            </span>
            <div class="wrap-form">
                <form action="<?php echo $action; ?>" id="searchDateForm" method="POST">
                    <select name="byDaetY" id="byDaetY" required>
                        <option value=""><?php _e('select year','search'); ?></option>
                        <?php 
                            for ($i = date("Y"); $i > 0; $i--) {  ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php } ?>
                    </select>
                    <select name="byDaetMo" id="byDaetMo" required>
                        <option value=""><?php _e('select month','search'); ?></option>
                        <?php for ($i=1; $i < 13; $i++) {  ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php } ?>
                    </select>
                    <input type="submit" value="<?php _e('search','search'); ?>">
                </form>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
