jQuery(document).ready(function ($) {

  $custom_poi_map = $('#custom-poi-map')

  if ($custom_poi_map.length) {

    var lat = $custom_poi_map.data('lat'),
      lng = $custom_poi_map.data('lng'),
      id = $custom_poi_map.data('id'),
      icon_class = $custom_poi_map.data('icon'),
      color = $custom_poi_map.data('icon-color'),
      modal = '<div id="modal-map"><div class="modal-content"><i class="fa fa-times close-modal" aria-hidden="true"></i><iframe src="' + data.appUrl + '/#/poi/' + id + '/' + data.zoom + '" width="100%"></iframe></div></div>';

    map = L.map('custom-poi-map').setView([lat, lng], data.zoom)

    L.tileLayer(data.tilesUrl, {
      layers: [
        {
          label: data.label,
          type: 'maptile',
          tilesUrl: data.tilesUrl,
          default: true,
        }],
    }).addTo(map)

    if ( data.show_pin === 'true' ) {

      if( icon_class !== '' || color !== '' ){
        var iconMarker = L.VectorMarkers.icon({
          icon: 'poi',
          prefix: 'wm',
          extraClasses: icon_class,
          markerColor: color,
          iconSize: [36, 45]
        });
        marker = L.marker([lat, lng], {icon: iconMarker}).addTo(map)
      } else {
        marker = L.marker([lat, lng]).addTo(map)
      }


      if ( data.modal_mode === 'false' ) {
        marker.on('click', function () {
          window.open(data.appUrl + '/#/poi/' + id + '/' + data.zoom + '/',
            '_blank')
        });
      } else {
        marker.on('click', function () {
          $('body').prepend(modal);
          $('#modal-map iframe').height($(window).height() * 80 / 100 );
        });
      }
    }

    if ( data.show_expand === 'true' ) {

      attr = 'open-modal-map';

      html = '<a target="_blank" class="' + attr + '" href="#" title="apri tutta la mappa"><span class="wm-icon-arrow-expand"></span></a>';
      $custom_poi_map.prepend(html)

      $('.open-poi-map').on('click', function (e) {
        e.preventDefault()
        window.open(data.appUrl + '/#/poi/' + id + '/' + data.zoom  , '_blank')
      });

      $('.open-modal-map').on('click', function (e) {
        e.preventDefault();
        $('body').prepend(modal);
        $('#modal-map iframe').height($(window).height() * 80 / 100 );

      });
    }

    if ( data.click_iframe === 'true' ) {
      $('#custom-poi-map').on('click', function (e) {
        e.preventDefault();
        $('body').prepend(modal);
        $('#modal-map iframe').height($(window).height() * 80 / 100 );
      });
    }


    $('body').on('click', '.close-modal', function (e) {
      e.preventDefault();
      $('#modal-map').remove();
    });

    map.touchZoom.disable()
    map.dragging.disable()
    map.touchZoom.disable()
    map.doubleClickZoom.disable()
    map.scrollWheelZoom.disable()
    map.boxZoom.disable()
    map.keyboard.disable()
    $('.leaflet-control-zoom').css('visibility', 'hidden')

  }

  $custom_track_map = $('#custom-track-map');

  if ($custom_track_map.length) {

    var geojson = $custom_track_map.data('geojson');

    map = L.map('custom-track-map').setView([0, 0], data.zoom)

    $related_pois = $('.related_poi');

    if ($related_pois.length){

      $related_pois.each(function( index, element ) {

        console.log(element);
        var lat = $(element).data('lat'),
          lng = $(element).data('lng'),
          title = $(element).data('title');

        marker = L.marker([lat, lng]).addTo(map);

      });


    }

    L.tileLayer(data.tilesUrl, {
      layers: [
        {
          label: data.label,
          type: 'maptile',
          tilesUrl: data.tilesUrl,
          default: true,
        }],
    }).addTo(map)

    L.geoJSON(geojson).addTo(map)

    var geojsonLayer = L.geoJson(geojson).addTo(map)
    map.fitBounds(geojsonLayer.getBounds())

    map.touchZoom.disable()
    map.dragging.disable()
    map.touchZoom.disable()
    map.doubleClickZoom.disable()
    map.scrollWheelZoom.disable()
    map.boxZoom.disable()
    map.keyboard.disable()

    $('.leaflet-control-zoom').css('visibility', 'hidden')

    center = map.getCenter()
    zoom = map.getZoom()

    id = $custom_track_map.data('id');

    var modal = '<div id="modal-map"><div class="modal-content"><i class="fa fa-times close-modal" aria-hidden="true"></i><iframe src="' + data.appUrl + '/#/track/' + id +'" width="100%"></iframe></div></div>';

    if ( data.show_expand === 'true' ) {

      attr = 'open-modal-map';

      var html = '<a target="_blank" class="' + attr + '" href="#" title="apri tutta la mappa"><span class="wm-icon-arrow-expand"></span></a>';

      $custom_track_map.prepend(html);

      $('.open-track-map').on('click', function () {
        window.open(data.appUrl + '/#/?map=' + zoom + '/' + center.lat + '/' +
          center.lng, '_blank');
        $('#modal-map iframe').height($(window).height() * 80 / 100);
      });

      $('.open-modal-map').on('click', function (e) {
        e.preventDefault();
        $('body').prepend(modal);
        $('#modal-map iframe').height($(window).height() * 80 / 100);
      });
    }

    if ( data.click_iframe === 'true' ) {
      $('#custom-track-map').on('click', function (e) {
        e.preventDefault();
        $('body').prepend(modal);
        $('#modal-map iframe').height($(window).height() * 80 / 100 );
      });
    }

    $('body').on('click', '.close-modal', function (e) {
      e.preventDefault();
      $('#modal-map').remove();
    });
  }

  $custom_shortcode_map = $('#custom-shortcode-map')

  if ($custom_shortcode_map.length) {

    var lat = $custom_shortcode_map.data('lat'),
      lng = $custom_shortcode_map.data('lng'),
      zoom = $custom_shortcode_map.data('zoom'),
      modal = '<div id="modal-map"><div class="modal-content"><i class="fa fa-times close-modal" aria-hidden="true"></i><iframe src="' + data.appUrl + '/#/?map=' + zoom + '/' + lat + '/' + lng + '" width="100%"></iframe></div></div>';

    map = L.map('custom-shortcode-map').setView([lat, lng], zoom)

    L.tileLayer(data.tilesUrl, {
      layers: [
        {
          label: data.label,
          type: 'maptile',
          tilesUrl: data.tilesUrl,
          default: true,
        }],
    }).addTo(map)

    html = '<a target="_blank" class="open-modal-map" href="#" title="apri tutta la mappa"><span class="wm-icon-arrow-expand"></span></a>';
    $custom_shortcode_map.prepend(html)

    $('.open-modal-map').on('click', function (e) {
      e.preventDefault();
      $('body').prepend(modal);
      $('#modal-map iframe').height($(window).height() * 80 / 100 );

    });


    if ( data.click_iframe === 'true' ) {
      $custom_shortcode_map.css('cursor', 'pointer');
      $custom_shortcode_map.on('click', function (e) {
        e.preventDefault();
        $('body').prepend(modal);
        $('#modal-map iframe').height($(window).height() * 80 / 100 );
      });
    }


    $('body').on('click', '.close-modal', function (e) {
      e.preventDefault();
      $('#modal-map').remove();
    });

    map.touchZoom.disable()
    map.dragging.disable()
    map.touchZoom.disable()
    map.doubleClickZoom.disable()
    map.scrollWheelZoom.disable()
    map.boxZoom.disable()
    map.keyboard.disable()
    $('.leaflet-control-zoom').css('visibility', 'hidden')

  }

});