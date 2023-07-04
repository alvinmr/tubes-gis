<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Marker</title>
    {{-- boostrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    {{-- leaflet --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.2.0/dist/leaflet.css" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.4.1/MarkerCluster.css" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.4.1/MarkerCluster.Default.css" />

    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />


    <style>
        .info {
            padding: 6px 8px;
            font: 14px/16px Arial, Helvetica, sans-serif;
            background: white;
            background: rgba(255, 255, 255, 0.8);
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            border-radius: 5px;
        }

        .sidebar {
            position: absolute;
            top: 0;
            bottom: 0;
            left: -500px;
            width: 500px;
            padding: 10px;
            background-color: #fff;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
            z-index: 1000;
            overflow-y: auto;
            transition: left 0.3s ease-in-out;
        }

        .sidebar.active {
            left: 0;
        }

        .close {
            float: right;
            font-weight: bold;
            line-height: 1;
            color: #000000;
            text-shadow: 0 1px 0 #000000;
            opacity: 0.2;
            font-size: 24px;
            text-decoration: none;
            cursor: pointer;
        }

        .filter-display {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 999;
            background-color: #fff;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
        }

        .filterSelect {
            position: absolute;
            top: 100px;
            right: 10px;
            z-index: 999;
            background-color: #fff;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
        }

        .filterLayer {
            position: absolute;
            top: 200px;
            right: 10px;
            z-index: 999;
            background-color: #fff;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
        }

        #btn-delete-route {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 999;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
        }
    </style>
</head>

<body>
    <div id="faskes-sidebar" class="sidebar"></div>

    <div class="form-group filter-display">
        <label for="display-option">Opsi Tampilan:</label>
        <select class="form-control" id="display-option">
            <option value="geojson">GeoJSON</option>
            <option value="radius">Radius</option>
        </select>
    </div>


    <div class="form-group filterSelect">
        <label for="display-option">Tampilkan Kasus:</label>
        <select class="form-control" id="filterSelect">
            <option value="suspek" selected>Kasus Suspek</option>
            <option value="sembuh">Kasus Sembuh</option>
            <option value="dirawat">Kasus Dirawat</option>
            <option value="meninggal">Kasus Meninggal</option>
        </select>
    </div>

    <div class="form-group filterLayer">
        <label for="display-option">Tampilkan Layer:</label>
        <select class="form-control" id="filterLayer">
            <option value="provinsi" selected>Provinsi</option>
            <option value="kabupaten">Kabupaten</option>
            <option value="kecamatan">Kecamatan</option>
            <option value="desa">Desa</option>
        </select>
    </div>

    {{-- <div>
        <button id="btn-delete-route" class="btn btn-primary">Delete Route</button>
    </div> --}}


    <div id="mapid" style="width:100vw;height:100vh;"></div>


    {{-- jquery --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    {{-- boostrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <!-- Load Leaflet from CDN -->
    <script src="https://unpkg.com/leaflet@1.2.0/dist/leaflet.js"></script>
    <script src="https://leaflet.github.io/Leaflet.Editable/src/Leaflet.Editable.js"></script>

    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>

    <!-- Easybutton -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet-easybutton@2/src/easy-button.css" />
    <script src="https://cdn.jsdelivr.net/npm/leaflet-easybutton@2/src/easy-button.js"></script>


    <!-- fontawesome -->
    <script src="https://kit.fontawesome.com/2c36e9b7b1.js" crossorigin="anonymous"></script>
    <!-- sweet alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script>
        let currLat = -8.6726745
        let currLong = 115.1418712
        // get current location based on gps
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition((position) => {
                currLat = position.coords.latitude
                currLong = position.coords.longitude
                const marker = L.marker([currLat, currLong]).addTo(map);
                marker.bindPopup('Lokasi Anda').openPopup();
            })
        }

        const map = L.map('mapid', {
            editable: true,
            zoomControl: false
        }).setView(
            [currLat, currLong],
            11
        );
        $('.leaflet-control-attribution').hide()




        L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; Tugas GIS',
            maxZoom: 18,
        }).addTo(map)


        // add marker
        let arrayGeoJson = {!! json_encode($wilayah) !!}

        const getColor = (type, d) => {
            // '#f1a69f', '#e7685c', '#e7685c', '#d7301f'
            if (type === 'suspek') {
                return d > 50 ? '#e13f2f' :
                    d > 20 ? '#e45445' :
                    d > 10 ? '#e7685c' :
                    '#eb7d72';
            } else if (type === 'sembuh') {
                return d > 50 ? '#238443' : // dark green
                    d > 20 ? '#41ab5d' : // medium green
                    d > 10 ? '#a1d99b' : // light green
                    '#e5f5e0'; // very light green
            } else if (type === 'dirawat') {
                return d > 50 ? '#993404' : // dark orange
                    d > 20 ? '#d95f0e' : // medium orange
                    d > 10 ? '#fe9929' : // light orange
                    '#fee6ce'; // very light orange
            } else if (type === 'meninggal') {
                return d > 50 ? '#67000d' : // dark red
                    d > 20 ? '#a50f15' : // medium red
                    d > 10 ? '#cb181d' : // light red
                    '#fcbba1'; // very light red
            } else {
                return '#636363'; // default color
            }

        }


        const style = (feature) => {
            const filterValue = document.getElementById('filterSelect').value;
            let fillColor = '#fdbb84';

            if (filterValue !== 'all' && feature.kasus_covid[0][filterValue]) {
                const value = feature.kasus_covid[0][filterValue];
                const type = $('#filterSelect').val();
                fillColor = getColor(type, value);

            }

            return {
                fillColor: fillColor,
                weight: 1,
                opacity: 1,
                color: 'white',
                dashArray: '5',
                fillOpacity: 0.7,
            }
        };

        const styleSum = (feature) => {
            const filterValue = document.getElementById('filterSelect').value;
            let fillColor = '#fdbb84';

            if (filterValue !== 'all' && feature['total_' + filterValue]) {
                const value = feature['total_' + filterValue];
                const type = $('#filterSelect').val();
                fillColor = getColor(type, value);

            }

            return {
                fillColor: fillColor,
                weight: 1,
                opacity: 1,
                color: 'white',
                dashArray: '5',
                fillOpacity: 0.7,
            }
        }


        // Fetch first time
        const filetLayer = $('#filterLayer').val();
        if (filetLayer === 'provinsi') {
            fetch('/case/province')
                .then((res) => res.json())
                .then(function(res) {
                    for (let i = 0; i < res.length; i++) {
                        fetch('storage/'+res[i].geojson)
                            .then((geo) => geo.json())
                            .then((geo) => {
                                L.geoJson(geo, {
                                    onEachFeature: function(feature, layer) {
                                        const content = `
                                        <div class='card'>
                                            <div class='card-header alert-primary text-center justify-content-center'>
                                                <strong>Kabupaten<br>${res[i].nama}</strong>
                                            </div>
                                            <div class='card-body p-0 scrollable-table'>
                                                <table class='table m-0'>
                                                    <tr>
                                                        <th><i class='far fa-sad-tear'></i> Suspek </th>
                                                        <th>${res[i].total_suspek}</th>
                                                    </tr>
                                                    <tr class='text-success'>
                                                        <th><i class='far fa-smile'></i> Sembuh</th>
                                                        <th>${res[i].total_sembuh}</th>
                                                    </tr>
                                                    <tr class='text-warning'>
                                                        <th><i class='far fa-hospital'></i> Dirawat</th>
                                                        <th>${res[i].total_dirawat}</th>
                                                    </tr>
                                                    <tr class='text-danger'>
                                                        <th><i class='far fa-frown'></i> Meninggal</th>
                                                        <th>${res[i].total_meninggal}</th>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    `;
                                        layer.bindPopup(content)
                                    },
                                    style: styleSum(res[i])
                                }).addTo(map)
                            })
                    }

                })
        }
        if (filetLayer === 'kabupaten') {
            fetch('/case/regency')
                .then((res) => res.json())
                .then(function(res) {
                    // Clear previously added layers
                    map.eachLayer(function(layer) {
                        if (layer instanceof L.GeoJSON || layer instanceof L.Circle) {
                            map.removeLayer(layer);
                        }
                    });

                    for (let i = 0; i < res.length; i++) {
                        fetch('storage/'+res[i].geojson)
                            .then((geo) => geo.json())
                            .then((geo) => {
                                L.geoJson(geo, {
                                    onEachFeature: function(feature, layer) {
                                        const content = `
                                        <div class='card'>
                                            <div class='card-header alert-primary text-center justify-content-center'>
                                                <strong>Kabupaten<br>${res[i].nama}</strong>
                                            </div>
                                            <div class='card-body p-0 scrollable-table'>
                                                <table class='table m-0'>
                                                    <tr>
                                                        <th><i class='far fa-sad-tear'></i> Suspek </th>
                                                        <th>${res[i].total_suspek}</th>
                                                    </tr>
                                                    <tr class='text-success'>
                                                        <th><i class='far fa-smile'></i> Sembuh</th>
                                                        <th>${res[i].total_sembuh}</th>
                                                    </tr>
                                                    <tr class='text-warning'>
                                                        <th><i class='far fa-hospital'></i> Dirawat</th>
                                                        <th>${res[i].total_dirawat}</th>
                                                    </tr>
                                                    <tr class='text-danger'>
                                                        <th><i class='far fa-frown'></i> Meninggal</th>
                                                        <th>${res[i].total_meninggal}</th>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    `;
                                        layer.bindPopup(content)
                                    },
                                    style: styleSum(res[i])
                                }).addTo(map)
                            })
                    }

                })
        }
        if (filetLayer === 'kecamatan') {
            fetch('/case/district')
                .then((res) => res.json())
                .then(function(res) {
                    // Clear previously added layers
                    map.eachLayer(function(layer) {
                        if (layer instanceof L.GeoJSON || layer instanceof L.Circle) {
                            map.removeLayer(layer);
                        }
                    });

                    for (let i = 0; i < res.length; i++) {
                        fetch('storage/'+res[i].geojson)
                            .then((geo) => geo.json())
                            .then((geo) => {
                                L.geoJson(geo, {
                                    onEachFeature: function(feature, layer) {
                                        const content = `
                                        <div class='card'>
                                            <div class='card-header alert-primary text-center justify-content-center'>
                                                <strong>Kabupaten<br>${res[i].nama}</strong>
                                            </div>
                                            <div class='card-body p-0 scrollable-table'>
                                                <table class='table m-0'>
                                                    <tr>
                                                        <th><i class='far fa-sad-tear'></i> Suspek </th>
                                                        <th>${res[i].total_suspek}</th>
                                                    </tr>
                                                    <tr class='text-success'>
                                                        <th><i class='far fa-smile'></i> Sembuh</th>
                                                        <th>${res[i].total_sembuh}</th>
                                                    </tr>
                                                    <tr class='text-warning'>
                                                        <th><i class='far fa-hospital'></i> Dirawat</th>
                                                        <th>${res[i].total_dirawat}</th>
                                                    </tr>
                                                    <tr class='text-danger'>
                                                        <th><i class='far fa-frown'></i> Meninggal</th>
                                                        <th>${res[i].total_meninggal}</th>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    `;
                                        layer.bindPopup(content)
                                    },
                                    style: styleSum(res[i])
                                }).addTo(map)
                            })
                    }

                })
        }
        if (filetLayer === 'desa') {
            fetch('/case/village')
                .then((res) => res.json())
                .then(function(res) {
                    // Clear previously added layers
                    map.eachLayer(function(layer) {
                        if (layer instanceof L.GeoJSON || layer instanceof L.Circle) {
                            map.removeLayer(layer);
                        }
                    });

                    for (let i = 0; i < res.length; i++) {
                        fetch('storage/'+res[i].geojson)
                            .then((geo) => geo.json())
                            .then((geo) => {
                                L.geoJson(geo, {
                                    onEachFeature: function(feature, layer) {
                                        const content = `
                                        <div class='card'>
                                            <div class='card-header alert-primary text-center justify-content-center'>
                                                <strong>Kabupaten<br>${res[i].nama}</strong>
                                            </div>
                                            <div class='card-body p-0 scrollable-table'>
                                                <table class='table m-0'>
                                                    <tr>
                                                        <th><i class='far fa-sad-tear'></i> Suspek </th>
                                                        <th>${res[i].total_suspek}</th>
                                                    </tr>
                                                    <tr class='text-success'>
                                                        <th><i class='far fa-smile'></i> Sembuh</th>
                                                        <th>${res[i].total_sembuh}</th>
                                                    </tr>
                                                    <tr class='text-warning'>
                                                        <th><i class='far fa-hospital'></i> Dirawat</th>
                                                        <th>${res[i].total_dirawat}</th>
                                                    </tr>
                                                    <tr class='text-danger'>
                                                        <th><i class='far fa-frown'></i> Meninggal</th>
                                                        <th>${res[i].total_meninggal}</th>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    `;
                                        layer.bindPopup(content)
                                    },
                                    style: styleSum(res[i])
                                }).addTo(map)
                            })
                    }

                })
        }


        // Handle filter select box change event
        $('#filterLayer').change(function() {
            const option = $(this).val();
            if (option === 'provinsi') {
                fetch('/case/province')
                    .then((res) => res.json())
                    .then(function(res) {
                        // Clear previously added layers
                        map.eachLayer(function(layer) {
                            if (layer instanceof L.GeoJSON || layer instanceof L.Circle) {
                                map.removeLayer(layer);
                            }
                        });

                        for (let i = 0; i < res.length; i++) {
                            fetch('storage/'+res[i].geojson)
                                .then((geo) => geo.json())
                                .then((geo) => {
                                    L.geoJson(geo, {
                                        onEachFeature: function(feature, layer) {
                                            const content = `
                                        <div class='card'>
                                            <div class='card-header alert-primary text-center justify-content-center'>
                                                <strong>Provinsi<br>${res[i].nama}</strong>
                                            </div>
                                            <div class='card-body p-0 scrollable-table'>
                                                <table class='table m-0'>
                                                    <tr>
                                                        <th><i class='far fa-sad-tear'></i> Suspek </th>
                                                        <th>${res[i].total_suspek}</th>
                                                    </tr>
                                                    <tr class='text-success'>
                                                        <th><i class='far fa-smile'></i> Sembuh</th>
                                                        <th>${res[i].total_sembuh}</th>
                                                    </tr>
                                                    <tr class='text-warning'>
                                                        <th><i class='far fa-hospital'></i> Dirawat</th>
                                                        <th>${res[i].total_dirawat}</th>
                                                    </tr>
                                                    <tr class='text-danger'>
                                                        <th><i class='far fa-frown'></i> Meninggal</th>
                                                        <th>${res[i].total_meninggal}</th>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    `;
                                            layer.bindPopup(content)
                                        },
                                        style: styleSum(res[i])
                                    }).addTo(map)
                                })
                        }

                    })
            }
            if (option === 'kabupaten') {
                fetch('/case/regency')
                    .then((res) => res.json())
                    .then(function(res) {
                        // Clear previously added layers
                        map.eachLayer(function(layer) {
                            if (layer instanceof L.GeoJSON || layer instanceof L.Circle) {
                                map.removeLayer(layer);
                            }
                        });

                        for (let i = 0; i < res.length; i++) {
                            fetch('storage/'+res[i].geojson)
                                .then((geo) => geo.json())
                                .then((geo) => {
                                    L.geoJson(geo, {
                                        onEachFeature: function(feature, layer) {
                                            const content = `
                                        <div class='card'>
                                            <div class='card-header alert-primary text-center justify-content-center'>
                                                <strong>Kabupaten<br>${res[i].nama}</strong>
                                            </div>
                                            <div class='card-body p-0 scrollable-table'>
                                                <table class='table m-0'>
                                                    <tr>
                                                        <th><i class='far fa-sad-tear'></i> Suspek </th>
                                                        <th>${res[i].total_suspek}</th>
                                                    </tr>
                                                    <tr class='text-success'>
                                                        <th><i class='far fa-smile'></i> Sembuh</th>
                                                        <th>${res[i].total_sembuh}</th>
                                                    </tr>
                                                    <tr class='text-warning'>
                                                        <th><i class='far fa-hospital'></i> Dirawat</th>
                                                        <th>${res[i].total_dirawat}</th>
                                                    </tr>
                                                    <tr class='text-danger'>
                                                        <th><i class='far fa-frown'></i> Meninggal</th>
                                                        <th>${res[i].total_meninggal}</th>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    `;
                                            layer.bindPopup(content)
                                        },
                                        style: styleSum(res[i])
                                    }).addTo(map)
                                })
                        }

                    })
            }
            if (option === 'kecamatan') {
                fetch('/case/district')
                    .then((res) => res.json())
                    .then(function(res) {
                        // Clear previously added layers
                        map.eachLayer(function(layer) {
                            if (layer instanceof L.GeoJSON || layer instanceof L.Circle) {
                                map.removeLayer(layer);
                            }
                        });

                        for (let i = 0; i < res.length; i++) {
                            fetch('storage/'+res[i].geojson)
                                .then((geo) => geo.json())
                                .then((geo) => {
                                    L.geoJson(geo, {
                                        onEachFeature: function(feature, layer) {
                                            const content = `
                                        <div class='card'>
                                            <div class='card-header alert-primary text-center justify-content-center'>
                                                <strong>Kecamatan<br>${res[i].nama}</strong>
                                            </div>
                                            <div class='card-body p-0 scrollable-table'>
                                                <table class='table m-0'>
                                                    <tr>
                                                        <th><i class='far fa-sad-tear'></i> Suspek </th>
                                                        <th>${res[i].total_suspek}</th>
                                                    </tr>
                                                    <tr class='text-success'>
                                                        <th><i class='far fa-smile'></i> Sembuh</th>
                                                        <th>${res[i].total_sembuh}</th>
                                                    </tr>
                                                    <tr class='text-warning'>
                                                        <th><i class='far fa-hospital'></i> Dirawat</th>
                                                        <th>${res[i].total_dirawat}</th>
                                                    </tr>
                                                    <tr class='text-danger'>
                                                        <th><i class='far fa-frown'></i> Meninggal</th>
                                                        <th>${res[i].total_meninggal}</th>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    `;
                                            layer.bindPopup(content)
                                        },
                                        style: styleSum(res[i])
                                    }).addTo(map)
                                })
                        }

                    })
            }
            if (option === 'desa') {
                fetch('/case/village')
                    .then((res) => res.json())
                    .then(function(res) {
                        // Clear previously added layers
                        map.eachLayer(function(layer) {
                            if (layer instanceof L.GeoJSON || layer instanceof L.Circle) {
                                map.removeLayer(layer);
                            }
                        });

                        for (let i = 0; i < res.length; i++) {
                            fetch('storage/'+res[i].geojson)
                                .then((geo) => geo.json())
                                .then((geo) => {
                                    L.geoJson(geo, {
                                        onEachFeature: function(feature, layer) {
                                            const content = `
                                        <div class='card'>
                                            <div class='card-header alert-primary text-center justify-content-center'>
                                                <strong>Desa<br>${res[i].nama}</strong>
                                            </div>
                                            <div class='card-body p-0 scrollable-table'>
                                                <table class='table m-0'>
                                                    <tr>
                                                        <th><i class='far fa-sad-tear'></i> Suspek </th>
                                                        <th>${res[i].total_suspek}</th>
                                                    </tr>
                                                    <tr class='text-success'>
                                                        <th><i class='far fa-smile'></i> Sembuh</th>
                                                        <th>${res[i].total_sembuh}</th>
                                                    </tr>
                                                    <tr class='text-warning'>
                                                        <th><i class='far fa-hospital'></i> Dirawat</th>
                                                        <th>${res[i].total_dirawat}</th>
                                                    </tr>
                                                    <tr class='text-danger'>
                                                        <th><i class='far fa-frown'></i> Meninggal</th>
                                                        <th>${res[i].total_meninggal}</th>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    `;
                                            layer.bindPopup(content)
                                        },
                                        style: styleSum(res[i])
                                    }).addTo(map)
                                })
                        }

                    })
            }
        })

        // Handle select box change event
        $('#display-option').change(function() {
            const option = $(this).val();
            // Clear previously added layers
            map.eachLayer(function(layer) {
                if (layer instanceof L.GeoJSON || layer instanceof L.Circle) {
                    map.removeLayer(layer);
                }
            });

            // Show data based on selected option
            if (option === 'geojson') {
                $('.filterSelect').show();
                $('.filterLayer').show();
                const filterSelect = document.getElementById('filterSelect');

                if (filterSelect.value === 'suspek') {
                    legendAdd('#eb7d72', '#e7685c', '#e45445', '#e13f2f')
                } else if (filterSelect.value === 'sembuh') {
                    legendAdd('#e5f5e0', '#a1d99b', '#41ab5d', '#238443')
                } else if (filterSelect.value === 'dirawat') {
                    legendAdd('#fee6ce', '#fe9929', '#d95f0e', '#993404')
                } else if (filterSelect.value === 'meninggal') {
                    legendAdd('#fcbba1', '#cb181d', '#a50f15', '#67000d')
                }
                const filetLayer = $('#filterLayer').val();
                if (filetLayer === 'provinsi') {
                    fetch('/case/province')
                        .then((res) => res.json())
                        .then(function(res) {
                            for (let i = 0; i < res.length; i++) {
                                fetch('storage/'+res[i].geojson)
                                    .then((geo) => geo.json())
                                    .then((geo) => {
                                        L.geoJson(geo, {
                                            onEachFeature: function(feature, layer) {
                                                const content = `
                                        <div class='card'>
                                            <div class='card-header alert-primary text-center justify-content-center'>
                                                <strong>Kabupaten<br>${res[i].nama}</strong>
                                            </div>
                                            <div class='card-body p-0 scrollable-table'>
                                                <table class='table m-0'>
                                                    <tr>
                                                        <th><i class='far fa-sad-tear'></i> Suspek </th>
                                                        <th>${res[i].total_suspek}</th>
                                                    </tr>
                                                    <tr class='text-success'>
                                                        <th><i class='far fa-smile'></i> Sembuh</th>
                                                        <th>${res[i].total_sembuh}</th>
                                                    </tr>
                                                    <tr class='text-warning'>
                                                        <th><i class='far fa-hospital'></i> Dirawat</th>
                                                        <th>${res[i].total_dirawat}</th>
                                                    </tr>
                                                    <tr class='text-danger'>
                                                        <th><i class='far fa-frown'></i> Meninggal</th>
                                                        <th>${res[i].total_meninggal}</th>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    `;
                                                layer.bindPopup(content)
                                            },
                                            style: styleSum(res[i])
                                        }).addTo(map)
                                    })
                            }

                        })
                }
                if (filetLayer === 'kabupaten') {
                    fetch('/case/regency')
                        .then((res) => res.json())
                        .then(function(res) {
                            // Clear previously added layers
                            map.eachLayer(function(layer) {
                                if (layer instanceof L.GeoJSON || layer instanceof L.Circle) {
                                    map.removeLayer(layer);
                                }
                            });

                            for (let i = 0; i < res.length; i++) {
                                fetch('storage/'+res[i].geojson)
                                    .then((geo) => geo.json())
                                    .then((geo) => {
                                        L.geoJson(geo, {
                                            onEachFeature: function(feature, layer) {
                                                const content = `
                                        <div class='card'>
                                            <div class='card-header alert-primary text-center justify-content-center'>
                                                <strong>Kabupaten<br>${res[i].nama}</strong>
                                            </div>
                                            <div class='card-body p-0 scrollable-table'>
                                                <table class='table m-0'>
                                                    <tr>
                                                        <th><i class='far fa-sad-tear'></i> Suspek </th>
                                                        <th>${res[i].total_suspek}</th>
                                                    </tr>
                                                    <tr class='text-success'>
                                                        <th><i class='far fa-smile'></i> Sembuh</th>
                                                        <th>${res[i].total_sembuh}</th>
                                                    </tr>
                                                    <tr class='text-warning'>
                                                        <th><i class='far fa-hospital'></i> Dirawat</th>
                                                        <th>${res[i].total_dirawat}</th>
                                                    </tr>
                                                    <tr class='text-danger'>
                                                        <th><i class='far fa-frown'></i> Meninggal</th>
                                                        <th>${res[i].total_meninggal}</th>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    `;
                                                layer.bindPopup(content)
                                            },
                                            style: styleSum(res[i])
                                        }).addTo(map)
                                    })
                            }

                        })
                }
                if (filetLayer === 'kecamatan') {
                    fetch('/case/district')
                        .then((res) => res.json())
                        .then(function(res) {
                            // Clear previously added layers
                            map.eachLayer(function(layer) {
                                if (layer instanceof L.GeoJSON || layer instanceof L.Circle) {
                                    map.removeLayer(layer);
                                }
                            });

                            for (let i = 0; i < res.length; i++) {
                                fetch('storage/'+res[i].geojson)
                                    .then((geo) => geo.json())
                                    .then((geo) => {
                                        L.geoJson(geo, {
                                            onEachFeature: function(feature, layer) {
                                                const content = `
                                        <div class='card'>
                                            <div class='card-header alert-primary text-center justify-content-center'>
                                                <strong>Kabupaten<br>${res[i].nama}</strong>
                                            </div>
                                            <div class='card-body p-0 scrollable-table'>
                                                <table class='table m-0'>
                                                    <tr>
                                                        <th><i class='far fa-sad-tear'></i> Suspek </th>
                                                        <th>${res[i].total_suspek}</th>
                                                    </tr>
                                                    <tr class='text-success'>
                                                        <th><i class='far fa-smile'></i> Sembuh</th>
                                                        <th>${res[i].total_sembuh}</th>
                                                    </tr>
                                                    <tr class='text-warning'>
                                                        <th><i class='far fa-hospital'></i> Dirawat</th>
                                                        <th>${res[i].total_dirawat}</th>
                                                    </tr>
                                                    <tr class='text-danger'>
                                                        <th><i class='far fa-frown'></i> Meninggal</th>
                                                        <th>${res[i].total_meninggal}</th>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    `;
                                                layer.bindPopup(content)
                                            },
                                            style: styleSum(res[i])
                                        }).addTo(map)
                                    })
                            }

                        })
                }
                if (filetLayer === 'desa') {
                    fetch('/case/village')
                        .then((res) => res.json())
                        .then(function(res) {
                            // Clear previously added layers
                            map.eachLayer(function(layer) {
                                if (layer instanceof L.GeoJSON || layer instanceof L.Circle) {
                                    map.removeLayer(layer);
                                }
                            });

                            for (let i = 0; i < res.length; i++) {
                                fetch('storage/'+res[i].geojson)
                                    .then((geo) => geo.json())
                                    .then((geo) => {
                                        L.geoJson(geo, {
                                            onEachFeature: function(feature, layer) {
                                                const content = `
                                        <div class='card'>
                                            <div class='card-header alert-primary text-center justify-content-center'>
                                                <strong>Kabupaten<br>${res[i].nama}</strong>
                                            </div>
                                            <div class='card-body p-0 scrollable-table'>
                                                <table class='table m-0'>
                                                    <tr>
                                                        <th><i class='far fa-sad-tear'></i> Suspek </th>
                                                        <th>${res[i].total_suspek}</th>
                                                    </tr>
                                                    <tr class='text-success'>
                                                        <th><i class='far fa-smile'></i> Sembuh</th>
                                                        <th>${res[i].total_sembuh}</th>
                                                    </tr>
                                                    <tr class='text-warning'>
                                                        <th><i class='far fa-hospital'></i> Dirawat</th>
                                                        <th>${res[i].total_dirawat}</th>
                                                    </tr>
                                                    <tr class='text-danger'>
                                                        <th><i class='far fa-frown'></i> Meninggal</th>
                                                        <th>${res[i].total_meninggal}</th>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    `;
                                                layer.bindPopup(content)
                                            },
                                            style: styleSum(res[i])
                                        }).addTo(map)
                                    })
                            }

                        })
                }
            } else if (option === 'radius') {
                $('.filterSelect').hide();
                $('.filterLayer').hide();
                // legendRadius()
                // Show data within radius based on coordinates and radius
                for (let i = 0; i < arrayGeoJson.length; i++) {
                    for (let j = 0; j < arrayGeoJson[i].kasus_covid.length; j++) {

                        const radius = 100
                        const circle = L.circle(arrayGeoJson[i].kasus_covid[j].coordinate, {
                            radius: radius,
                        }).addTo(map);
                        circle.setStyle({
                            color: '#f0',
                            fillColor: '#f03',
                            fillOpacity: 0.5,
                        });
                    }
                }
            }
        });

        // wut
        const filterSelect = document.getElementById('filterSelect');
        legendAdd('#eb7d72', '#e7685c', '#e45445', '#e13f2f')
        filterSelect.addEventListener('change', (e) => {
            // Remove existing GeoJSON layers from the map
            if (filterSelect.value === 'suspek') {
                legendAdd('#eb7d72', '#e7685c', '#e45445', '#e13f2f')
            } else if (filterSelect.value === 'sembuh') {
                legendAdd('#e5f5e0', '#a1d99b', '#41ab5d', '#238443')
            } else if (filterSelect.value === 'dirawat') {
                legendAdd('#fee6ce', '#fe9929', '#d95f0e', '#993404')
            } else if (filterSelect.value === 'meninggal') {
                legendAdd('#fcbba1', '#cb181d', '#a50f15', '#67000d')
            }

            map.eachLayer((layer) => {
                if (layer instanceof L.GeoJSON) {
                    map.removeLayer(layer);
                }
            });

            // Re-add the GeoJSON layers based on the selected filter
            const filetLayer = $('#filterLayer').val();
            if (filetLayer === 'provinsi') {
                fetch('/case/province')
                    .then((res) => res.json())
                    .then(function(res) {
                        for (let i = 0; i < res.length; i++) {
                            fetch('storage/'+res[i].geojson)
                                .then((geo) => geo.json())
                                .then((geo) => {
                                    L.geoJson(geo, {
                                        onEachFeature: function(feature, layer) {
                                            const content = `
                                        <div class='card'>
                                            <div class='card-header alert-primary text-center justify-content-center'>
                                                <strong>Kabupaten<br>${res[i].nama}</strong>
                                            </div>
                                            <div class='card-body p-0 scrollable-table'>
                                                <table class='table m-0'>
                                                    <tr>
                                                        <th><i class='far fa-sad-tear'></i> Suspek </th>
                                                        <th>${res[i].total_suspek}</th>
                                                    </tr>
                                                    <tr class='text-success'>
                                                        <th><i class='far fa-smile'></i> Sembuh</th>
                                                        <th>${res[i].total_sembuh}</th>
                                                    </tr>
                                                    <tr class='text-warning'>
                                                        <th><i class='far fa-hospital'></i> Dirawat</th>
                                                        <th>${res[i].total_dirawat}</th>
                                                    </tr>
                                                    <tr class='text-danger'>
                                                        <th><i class='far fa-frown'></i> Meninggal</th>
                                                        <th>${res[i].total_meninggal}</th>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    `;
                                            layer.bindPopup(content)
                                        },
                                        style: styleSum(res[i])
                                    }).addTo(map)
                                })
                        }

                    })
            }
            if (filetLayer === 'kabupaten') {
                fetch('/case/regency')
                    .then((res) => res.json())
                    .then(function(res) {
                        // Clear previously added layers
                        map.eachLayer(function(layer) {
                            if (layer instanceof L.GeoJSON || layer instanceof L.Circle) {
                                map.removeLayer(layer);
                            }
                        });

                        for (let i = 0; i < res.length; i++) {
                            fetch('storage/'+res[i].geojson)
                                .then((geo) => geo.json())
                                .then((geo) => {
                                    L.geoJson(geo, {
                                        onEachFeature: function(feature, layer) {
                                            const content = `
                                        <div class='card'>
                                            <div class='card-header alert-primary text-center justify-content-center'>
                                                <strong>Kabupaten<br>${res[i].nama}</strong>
                                            </div>
                                            <div class='card-body p-0 scrollable-table'>
                                                <table class='table m-0'>
                                                    <tr>
                                                        <th><i class='far fa-sad-tear'></i> Suspek </th>
                                                        <th>${res[i].total_suspek}</th>
                                                    </tr>
                                                    <tr class='text-success'>
                                                        <th><i class='far fa-smile'></i> Sembuh</th>
                                                        <th>${res[i].total_sembuh}</th>
                                                    </tr>
                                                    <tr class='text-warning'>
                                                        <th><i class='far fa-hospital'></i> Dirawat</th>
                                                        <th>${res[i].total_dirawat}</th>
                                                    </tr>
                                                    <tr class='text-danger'>
                                                        <th><i class='far fa-frown'></i> Meninggal</th>
                                                        <th>${res[i].total_meninggal}</th>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    `;
                                            layer.bindPopup(content)
                                        },
                                        style: styleSum(res[i])
                                    }).addTo(map)
                                })
                        }

                    })
            }
            if (filetLayer === 'kecamatan') {
                fetch('/case/district')
                    .then((res) => res.json())
                    .then(function(res) {
                        // Clear previously added layers
                        map.eachLayer(function(layer) {
                            if (layer instanceof L.GeoJSON || layer instanceof L.Circle) {
                                map.removeLayer(layer);
                            }
                        });

                        for (let i = 0; i < res.length; i++) {
                            fetch('storage/'+res[i].geojson)
                                .then((geo) => geo.json())
                                .then((geo) => {
                                    L.geoJson(geo, {
                                        onEachFeature: function(feature, layer) {
                                            const content = `
                                        <div class='card'>
                                            <div class='card-header alert-primary text-center justify-content-center'>
                                                <strong>Kabupaten<br>${res[i].nama}</strong>
                                            </div>
                                            <div class='card-body p-0 scrollable-table'>
                                                <table class='table m-0'>
                                                    <tr>
                                                        <th><i class='far fa-sad-tear'></i> Suspek </th>
                                                        <th>${res[i].total_suspek}</th>
                                                    </tr>
                                                    <tr class='text-success'>
                                                        <th><i class='far fa-smile'></i> Sembuh</th>
                                                        <th>${res[i].total_sembuh}</th>
                                                    </tr>
                                                    <tr class='text-warning'>
                                                        <th><i class='far fa-hospital'></i> Dirawat</th>
                                                        <th>${res[i].total_dirawat}</th>
                                                    </tr>
                                                    <tr class='text-danger'>
                                                        <th><i class='far fa-frown'></i> Meninggal</th>
                                                        <th>${res[i].total_meninggal}</th>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    `;
                                            layer.bindPopup(content)
                                        },
                                        style: styleSum(res[i])
                                    }).addTo(map)
                                })
                        }

                    })
            }
            if (filetLayer === 'desa') {
                fetch('/case/village')
                    .then((res) => res.json())
                    .then(function(res) {
                        // Clear previously added layers
                        map.eachLayer(function(layer) {
                            if (layer instanceof L.GeoJSON || layer instanceof L.Circle) {
                                map.removeLayer(layer);
                            }
                        });

                        for (let i = 0; i < res.length; i++) {
                            fetch('storage/'+res[i].geojson)
                                .then((geo) => geo.json())
                                .then((geo) => {
                                    L.geoJson(geo, {
                                        onEachFeature: function(feature, layer) {
                                            const content = `
                                        <div class='card'>
                                            <div class='card-header alert-primary text-center justify-content-center'>
                                                <strong>Kabupaten<br>${res[i].nama}</strong>
                                            </div>
                                            <div class='card-body p-0 scrollable-table'>
                                                <table class='table m-0'>
                                                    <tr>
                                                        <th><i class='far fa-sad-tear'></i> Suspek </th>
                                                        <th>${res[i].total_suspek}</th>
                                                    </tr>
                                                    <tr class='text-success'>
                                                        <th><i class='far fa-smile'></i> Sembuh</th>
                                                        <th>${res[i].total_sembuh}</th>
                                                    </tr>
                                                    <tr class='text-warning'>
                                                        <th><i class='far fa-hospital'></i> Dirawat</th>
                                                        <th>${res[i].total_dirawat}</th>
                                                    </tr>
                                                    <tr class='text-danger'>
                                                        <th><i class='far fa-frown'></i> Meninggal</th>
                                                        <th>${res[i].total_meninggal}</th>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    `;
                                            layer.bindPopup(content)
                                        },
                                        style: styleSum(res[i])
                                    }).addTo(map)
                                })
                        }

                    })
            }
        });

        function calculateDistance(lat1, lon1, lat2, lon2, unit) {
            const radlat1 = Math.PI * lat1 / 180;
            const radlat2 = Math.PI * lat2 / 180;
            const theta = lon1 - lon2;
            const radtheta = Math.PI * theta / 180;
            let distance = Math.sin(radlat1) * Math.sin(radlat2) + Math.cos(radlat1) * Math.cos(radlat2) * Math.cos(
                radtheta);
            distance = Math.acos(distance);
            distance = distance * 180 / Math.PI;
            distance = distance * 60 * 1.1515;
            if (unit === "K") {
                distance = distance * 1.609344
            }
            if (unit === "N") {
                distance = distance * 0.8684
            }
            return distance.toFixed(2);
        }



        // add marker
        // fetch marker faskes from laravel controller model faskes
        const faskes = {!! json_encode($faskes) !!};
        faskes.forEach((faskes) => {
            const marker = L.marker([faskes.coordinate[0], faskes.coordinate[1]], {
                icon: L.icon({
                    iconUrl: faskes.type.path,
                    iconSize: [25, 25],
                    iconAnchor: [12.5, 12.5],
                })
            }).addTo(map);
            marker.on('click', () => {
                // delete routing before add new routing

                const distance = calculateDistance(currLat, currLong, faskes.coordinate[0], faskes
                    .coordinate[1], 'K');
                const sidebar = document.getElementById('faskes-sidebar');

                let content = generateSidebarContent(faskes);
                content += `<p>Jarak Pada Lokasi Anda: ${distance} kilometer</p>`

                sidebar.innerHTML = content;
                sidebar.classList.add('active');

                const closeButton = document.querySelector('#faskes-sidebar .close');
                closeButton.addEventListener('click', () => {
                    sidebar.classList.toggle('active');
                });
            });

            // click right mouse
            var control = ''
            marker.on('contextmenu', () => {
                control = L.Routing.control({
                    waypoints: [
                        L.latLng(currLat, currLong),
                        L.latLng(faskes.coordinate[0], faskes.coordinate[1]),
                    ],
                    closeButton: true,
                    router: L.Routing.mapbox(
                        'pk.eyJ1IjoiYWx2aW5tcjEwIiwiYSI6ImNsaXkwMW4xaTBkYmczc3FtZWMwb21kbXoifQ.ZamCsWI-jT_vrafEaQM2OQ'
                    )
                }).addTo(map);
                if (control === '') {
                    control.setWaypoints([]);
                }
            })

            marker.on('popupopen', () => {
                $('#carousel').carousel();
            });
        });

        function generateSidebarContent(faskes) {
            let content = `
            <div>
                <span class="close">&times;</span>
            </div>
            <div class="card">
                <div id="carouselFaskes" class="carousel slide" data-bs-ride="carousel">`;
            content += `<div class="carousel-inner">`;
            faskes.images.forEach((image, index) => {
                if (index === 0) {
                    content += `
                <div class="carousel-item active">
                    <img src="${image}" class="d-block w-100" alt="Faskes image">
                </div>`
                } else {
                    content += `
                <div class="carousel-item">
                    <img src="${image}" class="d-block w-100" alt="Faskes image">
                </div>`
                }
            });

            content += `
                </div>
                <button class="carousel-control-prev" data-bs-target="#carouselFaskes" type="button" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </button>
                <button class="carousel-control-next" data-bs-target="#carouselFaskes" type="button" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </button>
                </div>
            </div>
            <div class="card-body">
                <h5 class="card-title">${faskes.nama}</h5>
                <p>${faskes.alamat}</p>
                <p>Fasilitas:</p>
                <ul class="list-group">`;
            if (faskes.fasilitas.length === 0) content += `<li class="list-group-item">Tidak ada fasilitas</li>`;
            else {
                faskes.fasilitas.forEach((facility) => {
                    content += `<li class="list-group-item">${facility.nama}</li>`;
                });
            }
            content += `
                </ul>
            </div>`;

            return content;
        }

        // add legend
        function legendAdd(color1, color2, color3, color4) {
            // delete legend before add new legend
            if (document.querySelector('.info')) {
                document.querySelector('.info').remove('.info leaflet-control');
            }

            var legend = new L.Control({
                position: 'bottomright'
            });

            legend.update = function() {
                this._div.innerHTML = '<h5>Legenda Kasus COVID-19</h5>';

                const colorRanges = [{
                        color: color1,
                        range: 'Kasus 1 - 10'
                    },
                    {
                        color: color2,
                        range: 'Kasus 11 - 20'
                    },
                    {
                        color: color3,
                        range: 'Kasus 21 - 50'
                    },
                    {
                        color: color4,
                        range: 'Kasus > 50'
                    }
                ];

                for (let i = 0; i < colorRanges.length; i++) {
                    const range = colorRanges[i];
                    this._div.innerHTML += `<svg width="140" height="20">
                                    <rect width="140" height="17" style="fill:${range.color};stroke-width:0.1;stroke:rgb(0,0,0)" />
                                    <text x="7" y="13.5" font-size="14" fill="black">${range.range}</text>
                                 </svg>`;
                }
            };
            legend.onAdd = function(map) {
                this._div = L.DomUtil.create('div', 'info');
                this.update();
                return this._div;
            };
            legend.addTo(map);
        }

        function legendRadius() {
            // delete legend before add new legend
            if (document.querySelector('.info')) {
                document.querySelector('.info').remove('.info leaflet-control');
            }

            var legend = new L.Control({
                position: 'bottomright'
            });

            legend.update = function() {
                this._div.innerHTML = '<h5>Legenda Radius</h5>';

                const radiusRanges = [{
                        radius: 500,
                        range: '500 meter'
                    },
                    {
                        radius: 1000,
                        range: '1 kilometer'
                    },
                    {
                        radius: 2000,
                        range: '2 kilometer'
                    },
                    {
                        radius: 5000,
                        range: '5 kilometer'
                    }
                ];

                for (let i = 0; i < radiusRanges.length; i++) {
                    const range = radiusRanges[i];
                    // range
                    this._div.innerHTML += `<svg width="140" height="20">
                                    <circle cx="10" cy="10" r="5" stroke="black" stroke-width="1" fill="none" />
                                    <text x="30" y="13.5" font-size="14" fill="black">${range.range}</text>
                                 </svg>`;
                }
            };
            legend.onAdd = function(map) {
                this._div = L.DomUtil.create('div', 'info');
                this.update();
                return this._div;
            };
            legend.addTo(map);
        }
    </script>
</body>

</html>
