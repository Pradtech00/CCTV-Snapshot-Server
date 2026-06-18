document.addEventListener("DOMContentLoaded", function () {

    //---------------------------------------
    // Jam realtime
    //---------------------------------------

    const clock = document.getElementById("server-time");

    let serverTime = new Date(
        clock.dataset.time.replace(" ","T")
    );

    function updateClock(){

        serverTime.setSeconds(serverTime.getSeconds()+1);

        clock.textContent =
        serverTime.toLocaleTimeString("id-ID",
        {hour12:false});

    }

    updateClock();

    setInterval(updateClock,1000);


    //---------------------------------------
    // Ambil data dashboard
    //---------------------------------------

    async function refreshDashboard(){

        try{

            const res = await fetch("api/status.php");

            const data = await res.json();

            document.getElementById("camera-name").textContent =
                data.camera;

            document.getElementById("camera-ip").textContent =
                data.ip;

            document.getElementById("snapshot-count").textContent =
                data.snapshot_today;

            document.getElementById("storage-size").textContent =
                data.storage;

            if(data.image){

                document.getElementById("snapshot-image").src =
                    data.image+"?t="+Date.now();

                document.getElementById("snapshot-name").textContent =
                    data.image_name;

            }

            document.getElementById("activity-log").textContent =
                data.activity.join("\n");

        }

        catch(e){

            console.log(e);

        }

    }

    refreshDashboard();

    setInterval(refreshDashboard,5000);

});