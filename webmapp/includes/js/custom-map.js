jQuery(document).ready(function ($) {

  $custom_poi_map = $('#custom-poi-map')

  if ($custom_poi_map.length) {

    var lat = $custom_poi_map.data('lat'),
      lng = $custom_poi_map.data('lng'),
      modal = '<div id="modal-map"><div class="modal-content"><i class="fa fa-times close-modal" aria-hidden="true"></i><iframe src="' + data.appUrl + '/#/?map=' + data.zoom + '/' + lat + '/' + lng + '" width="100%"></iframe></div></div>';

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
      marker = L.marker([lat, lng]).addTo(map)

      if ( data.modal_mode === 'false' ) {
        marker.on('click', function () {
          window.open(data.appUrl + '/#/?map=' + data.zoom + '/' + lat + '/' +
            lng,
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

      if (data.modal_mode === 'false') {
        attr = 'open-poi-map';
      }
      else {
        attr = 'open-modal-map';
      }

      html = '<a target="_blank" class="' + attr + '" href="#" title="apri tutta la mappa"><span class="wm-icon-arrow-expand"></span></a>';
      $custom_poi_map.prepend(html)

      $('.open-poi-map').on('click', function (e) {
        e.preventDefault()
        window.open(data.appUrl + '/#/?map=' + data.zoom + '/' + lat + '/' +
          lng, '_blank')
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

    var modal = '<div id="modal-map"><div class="modal-content"><i class="fa fa-times close-modal" aria-hidden="true"></i><iframe src="' + data.appUrl + '/#/?map=' + zoom + '/' + center.lat + '/' + center.lng + '" width="100%"></iframe></div></div>';

    if ( data.show_expand === 'true' ) {
      if (data.modal_mode === 'false') {
        attr = 'open-track-map';
      }
      else {
        attr = 'open-modal-map';
      }
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

});