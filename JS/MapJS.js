
const containerName = "Mapbox_Map";
const mapboxToken = "pk.eyJ1IjoiZWRkaWVidXMiLCJhIjoiY2w0Y2p3bjR5MDBpeDNrcGlnZGVsZHdieSJ9.s7qo4SWniW11X0nn3y96ow";

class Time{
    constructor() {
        this.TimeStart = Date.now()
        this.DeltaStart = Date.now()
        this.DeltaCurrent = 0;

        this.DeltaTime = 0;
    }

    Tick(){
        this.DeltaCurrent = Date.now();
        this.DeltaTime = (this.DeltaCurrent - this.DeltaStart)/ 100;
        this.DeltaStart = this.DeltaCurrent;
    }
}

let timeObject = new Time();

//State & Info of mapbox map
const mapboxMap = {
    map : null,
    mapDebug : false,
    deviceMarkers: []
}

//State of Data View Box
let DataView = {
    isActive: false,
    AnimationDelta: 0
};

//Triggers in detail tracker view
function ToggleDataView(toggleBool)
{
    DataView.isActive = toggleBool;
    if (DataView.isActive == true) {
        console.log("DataView on!");
    }
    else
    {
        console.log("DataView off!");
    }
}

function UpdateDataView(){
    let speedRate = 0.8;
    if (DataView.isActive == false)
    {
        DataView.AnimationDelta -= timeObject.DeltaTime * speedRate;
        if (DataView.AnimationDelta < 0){
            DataView.AnimationDelta = 0;
        }
    }
    else{
        DataView.AnimationDelta += timeObject.DeltaTime * speedRate;
        if (DataView.AnimationDelta > 1)
        {
            DataView.AnimationDelta = 1;
        }
    }

    let mapObject = document.getElementById("DataView");
    if (mapObject){
        let animValue = (0.5 * DataView.AnimationDelta);
        mapObject.style.setProperty("bottom",`calc(-100% + (30% * ${DataView.AnimationDelta}))`);
    }
}

class DeviceMarker {
    constructor(Name, CSS_Style,positionArray) {
        this.Name = Name;
        this.LocationJSON = positionArray;

        this.divObject = document.createElement('div');
        this.divObject.addEventListener('click',function(){
            ToggleDataView(true);
        });
        this.divObject.className = CSS_Style;
        this.divObject.style.zIndex = 10;

        this.markerObject = new mapboxgl.Marker(this.divObject);

        //Set as most recent location
        let mostRecentGPS = this.LocationJSON[0];
        this.markerObject.setLngLat(
            [mostRecentGPS["Longitude"],mostRecentGPS["Latitude"]]
        );
    }

    SetToMap(map)
    {
        this.markerObject.addTo(map);
    }

    RemoveFromMap(){
        this.markerObject.remove();
    }
}

//GeoJson of Device Points
const TestDevicePoints = [];

function SetDeviceMarkers() {
    //Nested function to add markers to map
    //This function to be used once data has been retrieved
    function AddMarkers(DeviceData)
    {
        for (let device  = 0; device < DeviceData.length; device++)
        {
            let locationData = DeviceData[device][1];
            let marker = new DeviceMarker("Test",'mapMarker',locationData);
            mapboxMap.deviceMarkers.push(marker);
            marker.SetToMap(mapboxMap.map);

        }
    }
    //Get all Devices IMEI
    $.ajax({
        type: 'GET',
        url: "https://eddiebus-da1.azurewebsites.net/PHP/DataGet.php",
        dataType: 'jsonp',
        contentType: "application/json",
        success: function (data) {
            AddMarkers(data);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log("AJAX Error: "+jqXHR + '\n' + textStatus + '\n' + errorThrown);
        }
    });
}

//Set up the Mapbox Map
function MapLoad()
{
    mapboxgl.accessToken = mapboxToken;
    mapboxMap.map = new mapboxgl.Map({
        container: containerName, // container ID
        style: 'mapbox://styles/mapbox/streets-v11', // style URL
        center: [-1.499929041558289, 52.937081787318014], // starting position [lng, lat]
        zoom: 6, // starting zoom
        projection: 'equirectangular' // display in default view
    });

    mapboxMap.map.on('style.load', () => {
        mapboxMap.map.setFog({}); // Set the default atmosphere style
    });

    //Disable Map Rotation
    mapboxMap.map.dragRotate.disable();
    mapboxMap.map.touchZoomRotate.disableRotation();

    //Turn off X Rotation
    mapboxMap.map.setMaxPitch(0);

    let GeoLocateControl = new mapboxgl.GeolocateControl({
        positionOptions: {
            enableHighAccuracy: true
        },
        // When active the map will receive updates to the device's location as it changes.
        trackUserLocation: true,
        // Draw an arrow next to the location dot to indicate which direction the device is heading.
        showUserHeading: true
    });

    mapboxMap.map.addControl(GeoLocateControl);

    SetDeviceMarkers();
    requestAnimationFrame(MapSystemUpdate);

    helloModule();
}

function MapDebugLog()
{
    if (mapboxMap.mapDebug == true) {
        let position = mapboxMap.map.getCenter();
        console.log("Map Debug:\n" +
            `Map Pos: ${position}`)
    }
}

DataView.isActive = false;

function MapSystemUpdate() {
    MapDebugLog();
    UpdateDataView();
    timeObject.Tick();
    requestAnimationFrame(MapSystemUpdate);
}