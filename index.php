<!DOCTYPE html>
<html>
<head>
	<title>E-weAther GIS</title>
	 <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css"
   integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="
   crossorigin=""/>
   <link rel="stylesheet" href="assets/leaflet-panel-layers.css" />
	 <link rel="stylesheet" href="assets/tailwind.min.css" />
   <style type="text/css">
   	#mapid {
			border-radius:.125em;
			border:3px solid #FFF;
			box-shadow: 0 0 8px #999;
			width:100%;
			max-width:900px;
			height:600px;
		}
   	.icon {
  	display: inline-block;
  	margin: 2px;
  	height: 16px;
  	width: 16px;
  	background-color: #ccc;
  	}
  	.info { padding: 6px 8px; font: 14px/16px Arial, Helvetica, sans-serif; background: white; background: rgba(255,255,255,0.8); box-shadow: 0 0 15px rgba(0,0,0,0.2); border-radius: 5px; } .info h4 { margin: 0 0 5px; color: #777; }
  	.legend { text-align: left; line-height: 18px; color: #555; } .legend i { width: 18px; height: 18px; float: left; margin-right: 8px; opacity: 0.7; }
   </style>
</head>
<body>
	<div class="flex h-screen">
		<div id="mapid" class="m-auto"></div>
	</div>
</body>
  <?php
    date_default_timezone_set("Asia/Bangkok");
    include 'model.php';
    $api = new getAPI("https://data.bmkg.go.id/datamkg/MEWS/DigitalForecast/DigitalForecast-JawaTimur.xml");
    $data = $api->getMainData();
    $dataCuacaSaatini = $api->cuacaSaatIni();
  ?>
 <!-- Make sure you put this AFTER Leaflet's CSS -->
 <script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js" integrity="sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA=="crossorigin=""></script>

 <script src="assets/leaflet-panel-layers.js"></script>
 <script src="assets/leaflet.ajax.js"></script>

 <script type="text/javascript">
   	var map = L.map('mapid').setView([-7.8818327,112.9018053], 8);

   	var maplayer = L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    maxZoom: 18,
    id: 'mapbox.streets',
    accessToken: 'pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw'
  });
	 map.addLayer(maplayer);

   var info = L.control();
   info.onAdd = function (map) {
     this._div = L.DomUtil.create('div', 'info');
     this.update();
     return this._div;
  };

  info.update = function (props) {
    var json_cuacaSaatIni = <?php print_r(json_encode($dataCuacaSaatini)); ?>;

    var json_kota = <?php print_r(json_encode($data)); ?>;
    var str_temp = "";
    if(props!=undefined){
				for(temp in json_kota[props['nama']]){
		      str_temp += (json_kota[props['nama']][temp].datetime+" perkiraan <b>"+json_kota[props['nama']][temp].cuaca+"</b><br />");
		    }
    }
    this._div.innerHTML = '<h4>Perkiraan Cuaca di Provinsi Jawa Timur</h4>' +  (props ?
      '<b>' + props['nama'] + '</b> (Update : '+json_cuacaSaatIni[props['nama']][0]['datetime']+')<br /> Saat ini sedang <b><i style="color:'+json_cuacaSaatIni[props['nama']][0]['warna']+';">' + json_cuacaSaatIni[props['nama']][0]['cuaca'] + '</i></b><hr />' + str_temp
      : 'Arahkan kursor pada Kabupaten/Kota');
  };

  info.addTo(map);

  function style(feature) {
		var json_cuacaSaatIni = <?php print_r(json_encode($dataCuacaSaatini)); ?>;
		return {
			weight: 2,
			opacity: 1,
			color: 'white',
			dashArray: '3',
			fillOpacity: 0.3,
			fillColor: json_cuacaSaatIni[feature.properties['nama']][0]['warna']
		};
	}

  function highlightFeature(e) {
		var layer = e.target;

		layer.setStyle({
			weight: 5,
			color: '#FFF',
			dashArray: '',
			fillOpacity: 0.9
		});

		if (!L.Browser.ie && !L.Browser.opera && !L.Browser.edge) {
			layer.bringToFront();
		}

		info.update(layer.feature.properties);
	}

  function resetHighlight(e) {
		var layer = e.target;

		layer.setStyle({
			weight: 2,
			opacity: 1,
			color: 'white',
			dashArray: '3',
			fillOpacity: 0.3
		});

		info.update();
	}

  function zoomToFeature(e) {
		map.fitBounds(e.target.getBounds());
	}

  function onEachFeature(feature, layer) {
		layer.on({
			mouseover: highlightFeature,
			mouseout: resetHighlight,
			click: zoomToFeature
		});
	}

  var legend = L.control({position: 'bottomright'});

	legend.onAdd = function (map) {
		var div = L.DomUtil.create('div', 'info legend'),
			weather = [["Cerah","#FAC900"],["Cerah Berawan","#DACF4B"],["Berawan","#90b8cf"],["Berawan Tebal","#094479"],["Udara Kabur","#8e7578"],["Asap","#564144"],["Kabut","#999999"],["Hujan Ringan","#8fe0ff"],["Hujan Sedang","#2daad8"],["Hujan Lebat","#54416d"],["Hujan Lokal","#2b235a"],["Hujan Petir","#094479"]],
			labels = [],
			from, to;

		for (var i = 0; i < weather.length; i++) {
			labels.push(
				'<i style="background:'+weather[i][1]+'"></i> '+weather[i][0]);
		}

		div.innerHTML = labels.join('<br>');
		return div;
	};

	legend.addTo(map);

  var baseLayers = [
		{
			name: "Peta OpenStreetMap",
			layer: maplayer
		}
	];

  <?php
		//Mengkelompokan Kabupaten/Kota sesuai dengan cuacanya hari ini
	  $kotas = ["Bangkalan","Surabaya","Batu","Banyuwangi","Kota Blitar","Kabupaten Blitar","Bojonegoro","Bondowoso","Gresik","Jember","Jombang","Kabupaten Kediri","Kota Kediri","Lamongan","Lumajang","Kabupaten Madiun","Kota Madiun","Magetan","Kabupaten Malang","Kota Malang","Kabupaten Mojokerto","Kota Mojokerto","Nganjuk","Ngawi","Pacitan","Pamekasan","Kabupaten Pasuruan","Kota Pasuruan","Ponorogo","Kabupaten Probolinggo","Kota Probolinggo","Sampang","Sidoarjo","Situbondo","Sumenep","Trenggalek","Tuban","Tulungagung"];
	  $arrayKotaDgnCuaca = ["Cerah"=>[],"Cerah Berawan"=>[],"Berawan"=>[],"Berawan Tebal"=>[],"Udara Kabur"=>[],"Asap"=>[],"Kabut"=>[],"Hujan Ringan"=>[],"Hujan Sedang"=>[],"Hujan Lebat"=>[],"Hujan Lokal"=>[],"Hujan Petir"=>[]];
	  foreach ($kotas as $kota) {
	    $cuacanya = $dataCuacaSaatini[$kota][0]['cuaca'];
	    array_push($arrayKotaDgnCuaca[$cuacanya], "assets/geojson/".strtolower($kota).".geojson");
	  }

		//Membuat array untuk penamaan .geojson sesuai cuacanya hari ini
	  foreach ($arrayKotaDgnCuaca as $key => $value) {
	    if($arrayKotaDgnCuaca[$key]!=null){
	      $temp = '[';
	      foreach ($arrayKotaDgnCuaca[$key] as $output) {
	        $temp .= "'".$output."',";
	      }
	      $temp .= ']';
	      $arrayKotaDgnCuacaFinal[$key]=$temp;
	    }
	  }

		//Memasukan array penamaan .geojson kedalam plugin
		foreach ($arrayKotaDgnCuacaFinal as $key => $value) {
			$arrayKota[]='{
				name: "'.$key.'",
				layer: new L.GeoJSON.AJAX('.$value.',{
					style: style,
					onEachFeature: onEachFeature
				}).addTo(map)
				}';
	  }
	?>

	var overLayers = [{
		group: "Filter Cuaca",
		layers: [<?=implode(',', $arrayKota);?>]}
	];

	var panelLayers = new L.Control.PanelLayers(baseLayers, overLayers,{
		position:'bottomleft'
	});

	map.addControl(panelLayers);

 </script>
</html>
