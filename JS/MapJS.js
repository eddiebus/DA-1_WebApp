const containerName = "Mapbox_Map";
const mapboxToken = "pk.eyJ1IjoiZWRkaWVidXMiLCJhIjoiY2w0Y2p3bjR5MDBpeDNrcGlnZGVsZHdieSJ9.s7qo4SWniW11X0nn3y96ow";

let map = null;
const MapDebug = false;

var DataView = {
    isActive: false,
    YPosition: 0
}

//Set up the Mapbox Map
function MapLoad()
{
    mapboxgl.accessToken = mapboxToken;
    map = new mapboxgl.Map({
        container: containerName, // container ID
        style: 'mapbox://styles/mapbox/streets-v11', // style URL
        center: [-4,55], // starting position [lng, lat]
        zoom: 4, // starting zoom
        projection: 'equirectangular' // display in default view
    });

    map.on('style.load', () => {
        map.setFog({}); // Set the default atmosphere style
    });

    //Disable Map Rotation
    map.dragRotate.disable();
    map.touchZoomRotate.disableRotation();

    //Turn off X Rotation
    map.setMaxPitch(0);

    let GeoLocateControl = new mapboxgl.GeolocateControl({
        positionOptions: {
            enableHighAccuracy: true
        },
        // When active the map will receive updates to the device's location as it changes.
        trackUserLocation: true,
        // Draw an arrow next to the location dot to indicate which direction the device is heading.
        showUserHeading: true
    });

    map.addControl(GeoLocateControl);

    $.ajax({
        type: "GET",
        url: "PHP//DataGet.php",
        dataType: 'json',
        success: function (data) {
            console.log("AJAX function OK");
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log("AJAX Error: "+jqXHR + '\n' + textStatus + '\n' + errorThrown);
        }
    });

    requestAnimationFrame(MapSystemUpdate);

}

function MapDebugLog()
{
    let position = map.getCenter();
    console.log("Map Debug:\n" +
        `Map Pos: ${position}`)
}

function MapSystemUpdate() {
    if (MapDebugLog == true)
    {
        MapDebugLog();
    }

    requestAnimationFrame(MapSystemUpdate);
}