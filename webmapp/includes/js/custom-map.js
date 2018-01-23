jQuery(document).ready(function( $ ) {

  $custom_poi_map = $('#custom-poi-map');

  if ( $custom_poi_map.length ) {

    var lat = $custom_poi_map.data('lat');
    var lng = $custom_poi_map.data('lng');
    map = L.map('custom-poi-map').setView([lat, lng], data.zoom);

    L.tileLayer(data.tilesUrl, {
      layers: [
        {
          label: data.label,
          type: "maptile",
          tilesUrl: data.tilesUrl,
          default: true
        }]
    }).addTo(map);

    marker = L.marker([lat, lng]).addTo(map);

    map.touchZoom.disable();
    map.doubleClickZoom.disable();
    map.scrollWheelZoom.disable();
    map.boxZoom.disable();
    map.keyboard.disable();
    $(".leaflet-control-zoom").css("visibility", "hidden");
    var html = '<a target="_blank" class="open-poi-map" href="#" title="apri tutta la mappa"><span class="wm-icon-arrow-expand"></span></a>';
    $custom_poi_map.prepend(html);
    
    $('.open-poi-map').on('click', function () {
      window.open( data.appUrl + '/#/?map='+ data.zoom + '/' + lat + '/' + lng , '_blank');
    })
  }


  $custom_track_map = $('#custom-track-map');

  if ( $custom_track_map.length ) {

    var geojson = $custom_track_map.data('geojson');

    map = L.map('custom-track-map').setView([0, 0], data.zoom );

    L.tileLayer(data.tilesUrl, {
      layers: [
        {
          label: data.label,
          type: "maptile",
          tilesUrl: data.tilesUrl,
          default: true
        }]
    }).addTo(map);

    L.geoJSON(geojson).addTo(map);

    var geojsonLayer = L.geoJson(geojson).addTo(map);
    map.fitBounds(geojsonLayer.getBounds());

    map.touchZoom.disable();
    map.doubleClickZoom.disable();
    map.scrollWheelZoom.disable();
    map.boxZoom.disable();
    map.keyboard.disable();

    $(".leaflet-control-zoom").css("visibility", "hidden");

    center = map.getCenter();
    zoom = map.getZoom();

    var html = '<a target="_blank" class="open-track-map" href="#" title="apri tutta la mappa"><span class="wm-icon-arrow-expand"></span></a>';
    $custom_track_map.prepend(html);

    $('.open-track-map').on('click', function () {
      window.open( data.appUrl + '/#/?map=' + zoom + '/' + center.lat + '/' + center.lng , '_blank');
    })
  }


});