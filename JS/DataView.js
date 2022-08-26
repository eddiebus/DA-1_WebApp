//To be used with MapJS.js
//Provides function for the data view

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

//Set the Device that the data view will display
function SetDataViewDevice(DeviceName){
    console.log(DeviceName);
    let selectIndex = -1;

    for (let i = 0; i < mapboxMap.deviceMarkers.length; i++)
    {

        if (mapboxMap.deviceMarkers[i].Name == DeviceName)
        {
            selectIndex = i;
            break;
        }
    }
    if (selectIndex >= 0) {
        console.log("Device Found");
        let selectDevice = mapboxMap.deviceMarkers[selectIndex];
        let speedTextElement = document.getElementById("DataView_Speed");
        speedTextElement.innerHTML = selectDevice.recentSpeed.toString();

        let altitudeTextElement = document.getElementById("DataView_Altitude");
        altitudeTextElement.innerHTML = selectDevice.recentAltitude.toString();

        let locationTextElement = document.getElementById("DataView_Location");
        let LatLongString = [selectDevice.LocationJSON[0]["Latitude"],selectDevice.LocationJSON[0]["Longitude"]];

        for (let i = 0; i < LatLongString.length; i++)
        {
            let maxChar = 6;
            if (LatLongString[i].length > maxChar)
            {
                LatLongString[i] = LatLongString[i].substring(0,maxChar);
            }
        }
        let locationString = LatLongString[0] + " , " + LatLongString[1];

        mapboxMap.map.flyTo(
            {
                center: [LatLongString[1],LatLongString[0]],
                zoom: 8
            }
        );
        locationTextElement.innerHTML = locationString;

        let timeTextElement = document.getElementById("DataView_Time");
        timeTextElement.innerHTML = selectDevice.StartDate.toString();
    } else {
        console.log("Couldn't find device. Weird");
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

    //Set Size dependent on animation
    let mapObject = document.getElementById("DataView");
    let dataview_objectHeight = 0;
    if (mapObject){
        dataview_objectHeight = mapObject.offsetHeight;
        mapObject.style.setProperty("bottom",`calc(-${dataview_objectHeight}px  + (${dataview_objectHeight}px * ${DataView.AnimationDelta}) )`);
    }

}
