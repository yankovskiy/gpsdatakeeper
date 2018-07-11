/**
 * @param {Object} options
 * Utils for map interaction
 */
function leaflet(options) {
    /**
     * Contains drawing data
     */
    const drawnItems = L.featureGroup();
    /**
     * Drawing control toolbar
     */
    const drawControl = new L.Control.Draw({
        edit: {
            featureGroup: drawnItems,
        },
        draw: {
            polygon: false,
            circle: false,
            rectangle: false,
            circlemarker: false,
            polyline: {
                shapeOptions: {
                    opacity: 1,
                    color: '#ff241c'
                }
            },
        },
        position: 'topright'
    });
    /**
     * Map
     */
    var map;

    /**
     * GPS-data token
     */
    var gpsDataToken;

    /**
     * Prompt title
     */
    var gpsDataTitle = 'My tracks';

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * Sets click listener for "save to server" link
     * Opens confirmation dialog if geo data present
     */
    function saveToServerClickListener() {
        $('#save-to-server').click(function () {
            if (hasGeoData()) {
                $('#save-data-modal').modal('show');
            }
        });
    }

    /**
     * Sets onchange listener for "open file" input
     * Opens dialog for choose file and draw it on the map
     */
    function openFileChangeListener() {
        /**
         * Handles GPS-data files for draws it on the maps
         * @param input
         */
        function handleFileSelect(input) {
            /**
             * Gets file extension
             * @param {File} file
             * @returns {string} file extension
             */
            function getFileExtension(file) {
                const parts = file.name.split('.');
                const length = parts.length;
                return parts[length - 1].toLowerCase();
            }

            /**
             * Checks file extension for valid
             * @param {string} ext
             * @returns {boolean} true for GPX or KML extension
             */
            function isValidExt(ext) {
                return ext === 'gpx' || ext === 'kml';
            }

            const file = input.files[0];
            const fileExtension = getFileExtension(file);
            const reader = new FileReader();

            if (!isValidExt(fileExtension)) {
                alert('Incorrect file extension');
                return;
            }

            reader.onload = function (event) {
                const parser = new DOMParser();
                const data = parser.parseFromString(event.target.result, 'text/xml');
                const geoJson = fileExtension === 'gpx' ? toGeoJSON.gpx(data) : toGeoJSON.kml(data);

                drawDataOnMap(geoJson);
                map.fitBounds(drawnItems.getBounds());
            };

            reader.readAsText(file);
        }

        $('#open-file').change(function () {
            handleFileSelect(this);
        });
    }

    /**
     * Draws geo data on the map
     * @param {GeoJSON} geoData
     */
    function drawDataOnMap(geoData) {
        var layer = L.geoJSON(geoData);
        layer.eachLayer(
            function (layer) {
                drawnItems.addLayer(layer);
                const content = getPopupContent(layer);
                if (content !== null) {
                    layer.bindPopup(content);
                }
            }
        );
    }

    /**
     * Gets data from layer for bind into popup
     * For Marker layer data should be latitude and longitude.
     * For Polyline layer data should be distance in metric system.
     * @param {Object} layer layer for get data
     * @returns {*} string for supported layer or null for unsupported layer
     */
    function getPopupContent(layer) {
        /**
         * Formats latLng object to string (x.xxxxxx, y.yyyyyy)
         * @param {Object} latlng
         * @returns {string} formated string
         */
        function strLatLng(latlng) {
            /**
             * Truncates value based on number of decimals
             * @param {number} num value for truncate
             * @param {number} len length
             * @returns {number} truncated value
             * @private
             */
            function _round(num, len) {
                return Math.round(num * (Math.pow(10, len))) / (Math.pow(10, len));
            }

            return "(" + _round(latlng.lat, 6) + ", " + _round(latlng.lng, 6) + ")";
        }

        // Marker - add lat/long
        if (layer instanceof L.Marker || layer instanceof L.CircleMarker) {
            return strLatLng(layer.getLatLng());
        } else if (layer instanceof L.Polyline) {
            var latlngs = layer._defaultShape ? layer._defaultShape() : layer.getLatLngs(),
                distance = 0;
            if (latlngs.length < 2) {
                return "Distance: N/A";
            } else {
                for (var i = 0; i < latlngs.length - 1; i++) {
                    distance += latlngs[i].distanceTo(latlngs[i + 1]);
                }
                return "Distance: " + L.GeometryUtil.readableDistance(distance, 'metric');
            }
        }
        return null;
    }

    /**
     * Inits map and control elements
     * @param {Object} options for init map
     * @see defaultInitOptions
     */
    function init(options) {
        // Default options
        const defaultInitOptions = {
            mapCenter: [0.01, 0.01],
            mapZoom: 2,
            geoData: undefined,
            isOwner: undefined,
            isGuest: undefined,
            gpsDataTitle: gpsDataTitle,
            gpsDataToken: undefined,
        };
        const localOptions = {
            mapCenter: options.mapCenter !== undefined ? options.mapCenter : defaultInitOptions.mapCenter,
            mapZoom: options.mapZoom !== undefined ? options.mapZoom : defaultInitOptions.mapZoom,
            geoData: options.geoData,
            isOwner: options.isOwner,
            isGuest: options.isGuest,
            gpsDataTitle: options.gpsDataTitle !== undefined ? options.gpsDataTitle : defaultInitOptions.gpsDataTitle,
            gpsDataToken: options.gpsDataToken,
        };

        const osmUrl = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
        const osmAttrib = '&copy; <a href="http://openstreetmap.org/copyright">OpenStreetMap</a> contributors';
        const osm = L.tileLayer(osmUrl, {maxZoom: 19, attribution: osmAttrib});

        map = new L.Map('map', {center: localOptions.mapCenter, zoom: localOptions.mapZoom, zoomControl: false});
        drawnItems.addTo(map);

        // comment line when activate other layers
        osm.addTo(map);
        var mapLayer = {
            OpenStreetMap: osm.addTo(map),
            OpenTopoMap: L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
                maxZoom: 17,
                attribution: 'Map data: &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>, <a href="http://viewfinderpanoramas.org">SRTM</a> | Map style: &copy; <a href="https://opentopomap.org">OpenTopoMap</a> (<a href="https://creativecommons.org/licenses/by-sa/3.0/">CC-BY-SA</a>)'
            }),
            Satellite:  L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                maxZoom: 19,
                attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
            }),
        };

        if (options.mapKeys.thunderforest !== '') {
            mapLayer['OpenCycleMap'] = L.tileLayer('https://{s}.tile.thunderforest.com/cycle/{z}/{x}/{y}.png?apikey=' + options.mapKeys.thunderforest, {
                maxZoom: 22,
                attribution: '&copy; <a href="http://www.thunderforest.com/">Thunderforest</a>, &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            });
            mapLayer['Outdoor'] = L.tileLayer('https://{s}.tile.thunderforest.com/outdoors/{z}/{x}/{y}.png?apikey=' + options.mapKeys.thunderforest, {
                maxZoom: 22,
                attribution: '&copy; <a href="http://www.thunderforest.com/">Thunderforest</a>, &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            });
        }

        L.control.layers(
            mapLayer,
            // overlays
            {
                'Show drawing': drawnItems
            },
            // options
            {
                position: 'topright',
                collapsed: false
            }).addTo(map);

        map.on(L.Draw.Event.CREATED, function (event) {
            const layer = event.layer;

            drawnItems.addLayer(layer);
            const content = getPopupContent(layer);
            if (content !== null) {
                layer.bindPopup(content);
            }
        });

        // recalculate distance and marker position after edit
        map.on(L.Draw.Event.EDITED, function (event) {
            const layers = event.layers;
            var content = null;
            layers.eachLayer(function (layer) {
                content = getPopupContent(layer);
                if (content !== null) {
                    layer.setPopupContent(content);
                }
            });
        });

        var isAllowEdit = true;
        const isGuest = (localOptions.isGuest !== undefined && localOptions.isGuest);
        if (localOptions.geoData !== undefined) {
            // draw loaded gps-data
            const geoData = JSON.parse(localOptions.geoData);
            drawDataOnMap(geoData);

            if (isGuest) {
                hideSaveDataLink();
            }

            // if guest or not gps-data owner
            if (isGuest || (localOptions.isOwner !== undefined && !localOptions.isOwner)) {
                isAllowEdit = false;
            }

        }

        if (isAllowEdit) {
            map.addControl(drawControl);

            gpsDataTitle = localOptions.gpsDataTitle;
            gpsDataToken = localOptions.gpsDataToken;

        }

        L.control.sidebar('sidebar').addTo(map);

        L.control.locate({
            position: 'topright',
            icon: 'glyphicon glyphicon-globe',
            iconLoading: 'glyphicon glyphicon-refresh'
        }).addTo(map);

        setEventsListeners();
    }

    /**
     * Checks layer for user geo data
     * @returns {boolean} true if geo data present
     */
    function hasGeoData() {
        return drawnItems.getLayers().length !== 0;
    }

    /**
     * Hides link for save data to the server
     */
    function hideSaveDataLink() {
        $('.save-block').remove();
    }

    /**
     * Sets events listeners
     */
    function setEventsListeners() {
        // handle #download-kml click
        handleDownload('kml');
        // handle #download-gpx click
        handleDownload('gpx');
        // handle #save-to-server click
        saveToServerClickListener();
        //
        handleSaveDataModal();
        //
        openFileChangeListener();
    }

    /**
     * Handles behaviors for Save Data modal
     */
    function handleSaveDataModal() {
        $('#save-button').click(function () {
            const title = $('#gps-data-title').val();

            /**
             * Displays error message about gps-data title was empty
             */
            function displayEmptyTitleError() {
                $('#default-form').addClass('has-error');
            }

            /**
             * Displays information about upload progress
             * Function also hide 'save' button
             */
            function displayTransferInfo() {
                $('#save-button').hide();
                $('#default-form').hide();
                $('#save-progress').show();
            }

            /**
             * Displays error message about incorrect gps-data
             * Function also hide 'save' button
             */
            function displayIncorrectDataError() {
                $('#save-progress').hide();
                $('#save-error').show();
            }

            /**
             * Displays successfully message
             */
            function displaySuccessfullyMessage() {
                $('#save-progress').hide();
                $('#save-success').show();
            }

            /**
             * Uploads gps-data into server and shows status about it
             */
            function uploadDataToServer() {
                const center = map.getCenter();
                const zoom = map.getZoom();
                const geoData = JSON.stringify(getGeoData());

                $.post('/map/save-data', {
                    data: geoData,
                    title: gpsDataTitle,
                    token: gpsDataToken,
                    center: [center.lat, center.lng],
                    zoom: zoom,
                }, function (data) {
                    if (data.status === 'error') {
                        displayIncorrectDataError();
                    } else if (data.status === 'success') {
                        if (data.isOwner === false) {
                            map.removeControl(drawControl);
                        } else {
                            map.addControl(drawControl);
                        }

                        if (data.isGuest) {
                            hideSaveDataLink();
                        }
                        history.pushState(null, null, data.url);
                        gpsDataToken = data.token;
                        displaySuccessfullyMessage();
                    }
                });
            }

            if (title !== null && title.trim().length > 0) {
                gpsDataTitle = title;
                displayTransferInfo();
                uploadDataToServer();
            } else {
                displayEmptyTitleError();
            }
        });

        $('#save-data-modal').on('show.bs.modal', function (e) {
            $('#gps-data-title').val(gpsDataTitle);
            $('#default-form').show().removeClass('has-error');
            $('#save-button').show();
            $('#save-success').hide();
            $('#save-progress').hide();
            $('#save-error').hide();
        })
    }

    /**
     * Handle click on the '#download-$format' links.
     * Runs download process for gps data from map.
     * If map does not contains gps data function was break.
     * If map does contains gps data - data converts into specified format and starts download process
     * @param {String} format file format for download data. Must be 'kml' or 'gpx'
     */
    function handleDownload(format) {
        var element = 'download-';

        if (format === 'kml') {
            element += 'kml';
        } else if (format === 'gpx') {
            element += 'gpx';
        } else {
            // unsupported format
            console.error("Unsupported format");
            return;
        }

        const download = document.getElementById(element);
        download.onclick = function (e) {
            var data = getGeoData();
            if (data === null) {
                return;
            }

            console.log(JSON.stringify(data));
            if (format === 'kml') {
                data = tokml(data);
            } else if (format === 'gpx') {
                data = togpx(data);
            }

            const convertedData = 'application/xml;charset=utf-8,' + encodeURIComponent(data);
            download.setAttribute('href', 'data:' + convertedData);
            download.setAttribute('download', 'data.' + format);
        }
    }

    /**
     * Gets GeoJSON data from drawing layer
     * @returns {*} GeoJSON data if layer contains drawing data otherwise null
     */
    function getGeoData() {
        if (!hasGeoData()) {
            return null;
        }

        return drawnItems.toGeoJSON();
    }

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    init(options);
}