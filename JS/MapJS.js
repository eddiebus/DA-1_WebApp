const containerName = "Mapbox_Map";
const mapboxToken = "pk.eyJ1IjoiZWRkaWVidXMiLCJhIjoiY2w0Y2p3bjR5MDBpeDNrcGlnZGVsZHdieSJ9.s7qo4SWniW11X0nn3y96ow";

function MapLoad()
{
    mapboxgl.accessToken = mapboxToken;
    const map = new mapboxgl.Map({
        container: containerName, // container ID
        style: 'mapbox://styles/mapbox/streets-v11', // style URL
        center: [-4,55], // starting position [lng, lat]
        zoom: 4, // starting zoom
        projection: 'mercator' // display in default view
    });

    map.on('style.load', () => {
        map.setFog({}); // Set the default atmosphere style
    });

    $.ajax({
        type: "GET",
        url: "PHP//DataGet.php",
        dataType: "json"
    });
}