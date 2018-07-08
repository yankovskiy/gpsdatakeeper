var toGeoJSON = (function () {
    'use strict';

    var removeSpace = /\s*/g,
        trimSpace = /^\s*|\s*$/g,
        splitSpace = /\s+/;

    // generate a short, numeric hash of a string
    function okhash(x) {
        if (!x || !x.length) return 0;
        for (var i = 0, h = 0; i < x.length; i++) {
            h = ((h << 5) - h) + x.charCodeAt(i) | 0;
        }
        return h;
    }

    // all Y children of X
    function get(x, y) {
        return x.getElementsByTagName(y);
    }

    function attr(x, y) {
        return x.getAttribute(y);
    }

    function attrf(x, y) {
        return parseFloat(attr(x, y));
    }

    // one Y child of X, if any, otherwise null
    function get1(x, y) {
        var n = get(x, y);
        return n.length ? n[0] : null;
    }

    // https://developer.mozilla.org/en-US/docs/Web/API/Node.normalize
    function norm(el) {
        if (el.normalize) {
            el.normalize();
        }
        return el;
    }

    // cast array x into numbers
    function numarray(x) {
        for (var j = 0, o = []; j < x.length; j++) {
            o[j] = parseFloat(x[j]);
        }
        return o;
    }

    // get the content of a text node, if any
    function nodeVal(x) {
        if (x) {
            norm(x);
        }
        return (x && x.textContent) || '';
    }

    // get one coordinate from a coordinate array, if any
    function coord1(v) {
        return numarray(v.replace(removeSpace, '').split(','));
    }

    // get all coordinates from a coordinate array as [[],[]]
    function coord(v) {
        var coords = v.replace(trimSpace, '').split(splitSpace),
            o = [];
        for (var i = 0; i < coords.length; i++) {
            o.push(coord1(coords[i]));
        }
        return o;
    }

    function coordPair(x) {
        var ll = [attrf(x, 'lon'), attrf(x, 'lat')],
            ele = get1(x, 'ele'),
            e;
        if (ele) {
            e = parseFloat(nodeVal(ele));
            if (!isNaN(e)) {
                ll.push(e);
            }
        }
        return {
            coordinates: ll,
        };
    }

    // create a new feature collection parent object
    function fc() {
        return {
            type: 'FeatureCollection',
            features: []
        };
    }
    
    var serializer;
    if (typeof XMLSerializer !== 'undefined') {
        /* istanbul ignore next */
        serializer = new XMLSerializer();
    } else {
        var isNodeEnv = (typeof process === 'object' && !process.browser);
        var isTitaniumEnv = (typeof Titanium === 'object');
        if (typeof exports === 'object' && (isNodeEnv || isTitaniumEnv)) {
            serializer = new (require('xmldom').XMLSerializer)();
        } else {
            throw new Error('Unable to initialize serializer');
        }
    }

    function xml2str(str) {
        // IE9 will create a new XMLSerializer but it'll crash immediately.
        // This line is ignored because we don't run coverage tests in IE9
        /* istanbul ignore next */
        if (str.xml !== undefined) return str.xml;
        return serializer.serializeToString(str);
    }

    var t = {
        kml: function (doc) {

            var gj = fc(),
                // styleindex keeps track of hashed styles in order to match features
                styleIndex = {}, styleByHash = {},
                // stylemapindex keeps track of style maps to expose in properties
                styleMapIndex = {},
                // atomic geospatial types supported by KML - MultiGeometry is
                // handled separately
                geotypes = ['Polygon', 'LineString', 'Point', 'Track', 'gx:Track'],
                // all root placemarks in the file
                placemarks = get(doc, 'Placemark'),
                styles = get(doc, 'Style'),
                styleMaps = get(doc, 'StyleMap');

            for (var k = 0; k < styles.length; k++) {
                var hash = okhash(xml2str(styles[k])).toString(16);
                styleIndex['#' + attr(styles[k], 'id')] = hash;
                styleByHash[hash] = styles[k];
            }
            for (var l = 0; l < styleMaps.length; l++) {
                styleIndex['#' + attr(styleMaps[l], 'id')] = okhash(xml2str(styleMaps[l])).toString(16);
                var pairs = get(styleMaps[l], 'Pair');
                var pairsMap = {};
                for (var m = 0; m < pairs.length; m++) {
                    pairsMap[nodeVal(get1(pairs[m], 'key'))] = nodeVal(get1(pairs[m], 'styleUrl'));
                }
                styleMapIndex['#' + attr(styleMaps[l], 'id')] = pairsMap;

            }
            for (var j = 0; j < placemarks.length; j++) {
                gj.features = gj.features.concat(getPlacemark(placemarks[j]));
            }

            function gxCoord(v) {
                return numarray(v.split(' '));
            }

            function gxCoords(root) {
                var elems = get(root, 'coord', 'gx'), coords = [];
                if (elems.length === 0) elems = get(root, 'gx:coord');
                for (var i = 0; i < elems.length; i++) coords.push(gxCoord(nodeVal(elems[i])));
                return {
                    coords: coords,
                };
            }

            function getGeometry(root) {
                var geomNode, geomNodes, i, j, k, geoms = [];
                if (get1(root, 'MultiGeometry')) {
                    return getGeometry(get1(root, 'MultiGeometry'));
                }
                if (get1(root, 'MultiTrack')) {
                    return getGeometry(get1(root, 'MultiTrack'));
                }
                if (get1(root, 'gx:MultiTrack')) {
                    return getGeometry(get1(root, 'gx:MultiTrack'));
                }
                for (i = 0; i < geotypes.length; i++) {
                    geomNodes = get(root, geotypes[i]);
                    if (geomNodes) {
                        for (j = 0; j < geomNodes.length; j++) {
                            geomNode = geomNodes[j];
                            if (geotypes[i] === 'Point') {
                                geoms.push({
                                    type: 'Point',
                                    coordinates: coord1(nodeVal(get1(geomNode, 'coordinates')))
                                });
                            } else if (geotypes[i] === 'LineString') {
                                geoms.push({
                                    type: 'LineString',
                                    coordinates: coord(nodeVal(get1(geomNode, 'coordinates')))
                                });
                            } else if (geotypes[i] === 'Polygon') {
                                var rings = get(geomNode, 'LinearRing'),
                                    coords = [];
                                for (k = 0; k < rings.length; k++) {
                                    coords.push(coord(nodeVal(get1(rings[k], 'coordinates'))));
                                }
                                geoms.push({
                                    type: 'Polygon',
                                    coordinates: coords
                                });
                            } else if (geotypes[i] === 'Track' ||
                                geotypes[i] === 'gx:Track') {
                                var track = gxCoords(geomNode);
                                geoms.push({
                                    type: 'LineString',
                                    coordinates: track.coords
                                });
                            }
                        }
                    }
                }
                return {
                    geoms: geoms,
                };
            }

            function getPlacemark(root) {
                var geomsAndTimes = getGeometry(root);

                if (!geomsAndTimes.geoms.length) return [];

                var feature = {
                    type: 'Feature',
                    geometry: (geomsAndTimes.geoms.length === 1) ? geomsAndTimes.geoms[0] : {
                        type: 'GeometryCollection',
                        geometries: geomsAndTimes.geoms
                    },
                    properties: {}
                };
                if (attr(root, 'id')) feature.id = attr(root, 'id');
                return [feature];
            }

            return gj;
        },
        gpx: function (doc) {
            var i,
                tracks = get(doc, 'trk'),
                routes = get(doc, 'rte'),
                waypoints = get(doc, 'wpt'),
                // a feature collection
                gj = fc(),
                feature;
            for (i = 0; i < tracks.length; i++) {
                feature = getTrack(tracks[i]);
                if (feature) gj.features.push(feature);
            }
            for (i = 0; i < routes.length; i++) {
                feature = getRoute(routes[i]);
                if (feature) gj.features.push(feature);
            }
            for (i = 0; i < waypoints.length; i++) {
                gj.features.push(getPoint(waypoints[i]));
            }

            function getPoints(node, pointname) {
                var pts = get(node, pointname),
                    line = [],
                    l = pts.length;
                if (l < 2) return {};  // Invalid line in GeoJSON
                for (var i = 0; i < l; i++) {
                    var c = coordPair(pts[i]);
                    line.push(c.coordinates);
                }
                return {
                    line: line,
                };
            }

            function getTrack(node) {
                var segments = get(node, 'trkseg'),
                    track = [],
                    line;
                for (var i = 0; i < segments.length; i++) {
                    line = getPoints(segments[i], 'trkpt');
                    if (line) {
                        if (line.line) track.push(line.line);
                    }
                }
                if (track.length === 0) return;

                return {
                    type: 'Feature',
                    properties: {},
                    geometry: {
                        type: track.length === 1 ? 'LineString' : 'MultiLineString',
                        coordinates: track.length === 1 ? track[0] : track
                    }
                };
            }

            function getRoute(node) {
                var line = getPoints(node, 'rtept');
                if (!line.line) return;

                return {
                    type: 'Feature',
                    properties: {},
                    geometry: {
                        type: 'LineString',
                        coordinates: line.line
                    }
                };
            }

            function getPoint(node) {
                return {
                    type: 'Feature',
                    properties: {},
                    geometry: {
                        type: 'Point',
                        coordinates: coordPair(node).coordinates
                    }
                };
            }

            return gj;
        }
    };
    return t;
})();

if (typeof module !== 'undefined') module.exports = toGeoJSON;
